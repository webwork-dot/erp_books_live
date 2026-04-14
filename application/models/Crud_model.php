<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Crud_model extends CI_Model{

    function __construct(){
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

	public function get_category_list(){
        $resultdata     = array();
        // $query = $this->db->query("SELECT id,category_name as name,category_slug as slug,category_image as image FROM tbl_category WHERE status='1' ORDER BY id asc");
        $query = $this->db->query("SELECT id, name, slug FROM categories WHERE status = '1' ORDER BY name ASC;");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
					"category_id"               => $item['id'],
					"category_name"      	    => $item['name'],
					"category_slug"      	    => base_url().$item['slug']
				);
            }
        }
        return $resultdata;
    }

	public function get_home_categories(){
        $curr_date = date("Y-m-d");
        $resultdata = array();
        $preloader = base_url() . 'assets/images/spinner.gif';
        $query = $this->db->query("SELECT id,name,slug FROM categories WHERE parent_id='0' AND status='1'");
        if (!empty($query)) {
            foreach ($query->result_array() as $item) {
                $page_url = base_url() . $item['slug'];

				$category_id = $item['id'];

				$sub_line='';
				$sql = $this->db->query("SELECT GROUP_CONCAT(name SEPARATOR ', ') AS product_names FROM products WHERE FIND_IN_SET('$category_id', category_id) limit 5");
				if ($sql->num_rows() > 0) {
					$result = $sql->row();
					$sub_line = $result->product_names;
				}


                $resultdata[] = array(
                    "id"         => $item['id'],
                    "name"       => $item['name'],
                    "slug"       => $item['slug'],
                    "url"        => $page_url,
                    "sub_line"   => $sub_line,
                    "preloader"  => $preloader,
                    "image"      => cdn_url() . $item['image'].'?tr=w-160',
                );
            }
        }
        return $resultdata;
    }

	public function get_menu_list(){
        $resultdata     = array();
        // $query = $this->db->query("SELECT id,category_name as name,category_slug as slug,category_image as image FROM tbl_category WHERE status='1' ORDER BY id asc");
        $query = $this->db->query("SELECT id, name, slug FROM categories WHERE status = '1' ORDER BY name ASC");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
					"category_id"               => $item['id'],
					"category_name"      	    => $item['name'],
					"category_slug"      	    => base_url().$item['slug'],
					"slug"      	    => $item['slug'],
					"image_1"      	    => $item['image_1']
				);
            }
        }
        return $resultdata;
    }

	public function get_menu_list3(){
        $resultdata     = array();
        // $query = $this->db->query("SELECT id,category_name as name,category_slug as slug,category_image as image FROM tbl_category WHERE status='1' ORDER BY id asc");
        $query = $this->db->query("SELECT id, name, slug FROM categories WHERE status = '1' ORDER BY name ASC");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
					"category_id"               => $item['id'],
					"category_name"      	    => $item['name'],
					"category_slug"      	    => base_url().'category/'.$item['slug'],
					"slug"      	    => $item['slug'],
					"image_1"      	    => $item['image_1']
				);
            }
        }
        return $resultdata;
    }

	public function get_product_list_count($cat){
        $where = '';
        // $query_cat = $this->db->query("SELECT id FROM tbl_category WHERE category_slug='$cat' AND status='1' LIMIT 1")->row_array();
        $query_cat = $this->db->query("SELECT id FROM categories WHERE slug='$cat' AND status='1' LIMIT 1")->row_array();
		$cat_id = $query_cat['id'];
		$where .= "and category_id='$cat_id'";

        // $query = $this->db->query("SELECT id FROM tbl_product WHERE status='1' $where ORDER BY product_title asc")->num_rows();
        $query = $this->db->query("SELECT id FROM products WHERE status='1' $where ORDER BY name asc")->num_rows();

        return $query;
    }

    public function update_wishlist($data){
        $resultpost = array();
        $type='logged';
        $user_id=$data['user_id'];
        $product_id=$data['product_id'];
        $query = $this->db->query("SELECT id FROM tbl_wishlist WHERE type='$type' AND user_id='$user_id' AND product_id='$product_id' LIMIT 1");
        if ($query->num_rows()==0) {
              $is_wishlist=1;
              $product_title=$this->common_model->getNameById('products','name',$product_id);

              $data=array();
              $data['type']           = $type;
              $data['user_id']        = $user_id;
              $data['product_id']     = $product_id;
              $data['product_title']  = $product_title;
           	  $data['created_at']     = date("Y-m-d H:i:s");
              $this->db->insert('tbl_wishlist', $data);
        }
        else{
           $is_wishlist=0;
           $this->db->query("DELETE FROM `tbl_wishlist` WHERE type='$type' AND user_id='$user_id' AND product_id='$product_id'");
        }

        $resultpost = array(
            'status' => 200,
            'message' => 'success',
            'is_wishlist' => $is_wishlist,
        );
        return $resultpost;
    }

    public function get_product_list($cat,$per_page,$offset){
        $resultdata     = array();
        $where = '';
        $new_slug = '';
        $head_code = '';
        // $query_cat = $this->db->query("SELECT id,head_code FROM tbl_category WHERE category_slug='$cat' AND status='1' LIMIT 1")->row_array();
        $query_cat = $this->db->query("SELECT id FROM categories WHERE slug='$cat' AND status='1' LIMIT 1")->row_array();
		$cat_id = $query_cat['id'];
		// $head_code = $query_cat['head_code'];
		// Category filtering is now done via JOIN with products_category table
		$new_slug = $cat;

        // $query = $this->db->query("SELECT p.*,pvar.selling_price as selling,pvar.product_mrp as mrp FROM tbl_product as p INNER JOIN product_variations as pvar ON p.id=pvar.product_id  WHERE p.status='1' $where GROUP BY pvar.product_id ORDER BY pvar.id ASC LIMIT $offset,$per_page");
        $query = $this->db->query("SELECT p.*, MIN(pvar.out_of_stock) as is_stock,pvar.selling_price as selling,pvar.size,pvar.product_mrp as mrp,pvar.out_of_stock FROM products as p INNER JOIN product_variations as pvar ON p.id=pvar.product_id INNER JOIN products_category as pc ON p.id = pc.product_id WHERE p.status='1' AND pc.category_id='$cat_id' GROUP BY pvar.product_id ORDER BY pvar.id ASC LIMIT $offset,$per_page");

        if (!empty($query)) {
            foreach($query->result_array() as $item){   
                $rowid=0;
                $flag = FALSE;
                $dataTmp = $this->cart->contents();
    			
    			$product_id = $item['id'];
				//$is_stock = $item['is_stock'];
				//$is_stock = $item['out_of_stock'];
				$is_stock = ($item['is_stock'] == 0) ? 0 : 1;
    			
                $size_name = '';
                $size_id = $item['size'];
                $size_option = $item['size'];
                if($item['size'] != '' && $item['size'] != NULL) {
                    $size_arr = $this->common_model->getRowById('oc_attribute_values','name,attribute_id',$item['size']);
                    $size_name = $size_arr['name'];
                    $size_option = $size_arr['attribute_id'];
                }

                foreach ($dataTmp as $cart) {
                    if ($cart['product_id'] == $item['id']) {
                        $flag  = TRUE;
                        $rowid = $cart['rowid']; 
                        break;
                    }
                }
                 
                if ($flag) {
					$is_added = 1;
					$quantity = $cart['qty'];
					$rowid = $rowid;
                } else {
					$is_added = 0;
					$quantity = 0;
					$rowid = 0;
                }
                
                if($item['you_save_amt']!='0'){
                    $product_price=$item['selling_price'];
    			} else{ 
    			    $product_price=$item['product_mrp'];
    			}  

                $image = $this->db->select('image')->where('product_id', $item['id'])->limit(1)->order_by('id', 'ASC')->get('product_images');

                if($image->num_rows() > 0){
                    $image = $image->row_array();
                    $image = cdn_url() . $image['image'];
                } else {
                    $image = '';
                }

                $is_wishlist = 0;
                if($this->session->userdata('user_login') == '1'){
                    $wishlist = $this->db->select('id')->where('product_id', $item['id'])->where('user_id', $this->session->userdata('user_id'))->get('tbl_wishlist');
                    if($wishlist->num_rows() > 0){
                        $is_wishlist = 1;
                    }
                }

                $avg_rating  = $this->crud_model->get_average_rating($item['id']); 
    	
                $resultdata[] = array(
                    "id"                    => $item['id'],
                    "category_id"      	    => $item['category_id'],
                    "category_slug"      	=> $new_slug,
                    "category_name"         => $this->common_model->get_category_name($item['category_id']),
                    "product_title"      	=> $item['name'],
                    "product_slug"          => $item['slug'],
                    // "featured_image"        => product_image_url().$item['featured_image'],
                    // "featured_image2"       => product_image_url().$item['featured_image2'],
                    "size_name"             => $size_name,
                    "size_id"               => $size_id,
                    "size_option"           => $size_option,
                    "featured_image"        => $image,
                    "product_mrp" 		    => $item['mrp'],						
                    "selling_price" 		=> $item['selling'],		
                    "you_save_amt" 		    => $item['you_save_amt'],				
                    "you_save_per" 		    => $item['you_save_per'],		
                    "rate_avg" 		        => $item['rate_avg'],		
                    "product_desc" 		    => $item['short_description'],		
                    "offer_id" 	            => $item['offer_id'],
                    "alt_tag" 	            => $item['alt_tag'],
                    "is_stock" 	            => $is_stock,
                    // "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']), 
                    "product_price"         => $product_price,
                    "is_added"              => $is_added,
                    "quantity"              => $quantity,
                    "rowid"                 => $rowid,  
                    "is_wishlist"                 => $is_wishlist,  
                    // "head_code"             => $head_code,  
                    "avg_rating"   => $avg_rating['average_rating']??0,
                    "total_review" => $avg_rating['total_review']??0,
                );
            }
        } 
        return $resultdata;
    }

    public function get_doctor_details($buyer_id) {
        $resultdata=array();
        $data=array();
        $query = $this->db->query("SELECT * FROM users WHERE id='$buyer_id' LIMIT 1");
        $doctor=$query->row_array();

       $start_outstanding=0;
       $dob='-';
       $doa='-';

       if(!empty($doctor)){
         $dob_=$doctor['dob'];
         $doa_=$doctor['doa'];

         if($dob_!='' && $dob_!=NULL && $dob_!='0000-00-00'){
           $dob=date("d M, Y", strtotime($dob_));
         }

         if($doa_!='' && $doa_!=NULL && $doa_!='0000-00-00'){
           $doa=date("d M, Y", strtotime($doa_));
         }
       }
       else{
         $doa='-';
       }


        $sales=$collection=$outstanding=0;
        $sales_query = $this->db->query("SELECT SUM(payable_amt) as payable_amt FROM `tbl_order_details` WHERE user_id='$buyer_id' AND payment_status='success' GROUP BY user_id LIMIT 1");

        if($sales_query->num_rows()>0){
          $sales_=$sales_query->row()->payable_amt;
          $sales=($sales_!='' ? $sales_:0);
        }
        else{
          $sales=0;
        }


        $collection=0;

       $outstanding = $sales-$collection;

    //  $last_order_amount=$this->get_last_order_amount($buyer_id);
    //  $last_payment_collection=$this->get_last_payment_collection($buyer_id);
    //  $total_orders=$this->get_doctor_total_orders($buyer_id);

      $resultdata = array(
           "id"    => $buyer_id,
           "name"  => $doctor['user_name'],
           "phone" => $doctor['code'].' '.$doctor['user_phone'],
           "alt_phone" => $doctor['alt_code'].' '.$doctor['alt_phone'],
           "email" => $doctor['user_email'],
           "country" => $doctor['country_name'],
           "state" => $doctor['state'],
           "city" => $doctor['city'],
           "area" => $doctor['area'],
           "address" => $doctor['address'],
           "pincode" => $doctor['pincode'],
           "qualification" => $doctor['qualifiaction'],
           "mr_name" => $doctor['mr_name'],
           "mr_phone" => $doctor['mr_phone'],
           "join_date" =>  date("d-M-Y h:i A", strtotime($doctor['created_at'])),
           "dob" => $dob,
           "doa" => $doa,
           "sales" => $sales,
           "collection" => $collection,
           "outstanding" => $outstanding,
        //    "last_order_amount" => $last_order_amount,
        //    "last_payment_collection" => $last_payment_collection,
        //    "total_orders" => $total_orders,
       );

      return $resultdata;
   }



	///////////////

    public function get_menu_list2(){
        $resultdata     = array();
        // $query = $this->db->query("SELECT id,category_name as name,category_slug as slug,category_image as image FROM tbl_category WHERE status='1' ORDER BY id asc");
        $query = $this->db->query("SELECT id,name,slug FROM categories WHERE status='1' ORDER BY id asc");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $category_id = $item['id'];

                $is_subcatgeory = 0;
                // $query_product = $this->db->query("SELECT id FROM tbl_product WHERE category_id='$category_id' and status='1' ORDER BY id asc");
                $query_product = $this->db->query("SELECT id FROM products WHERE category_id='$category_id' and status='1' ORDER BY id asc");
                $total_products = $query_product->num_rows();
                $products = array();
                // if($total_products > 0){
                    $resultdata[] = array(
                        "category_id"               => $item['id'],
                        "category_name"      	    => $item['name'],
                        "category_slug"      	    => $item['slug'],
                        "category_image"      	    => $item['image'],
                        "is_subcatgeory"      	    => $is_subcatgeory,
                        "products"      	        => $products,
                        "total_products"      	    => $total_products,
                    );
                // }

            }
        }

       return $resultdata;
    }


    public function get_menu_by_slug($cat){
        $resultdata     = array();

        $query = $this->db->query("SELECT id,name,slug FROM categories WHERE slug='$cat' AND status='1' ORDER BY id asc");
        if($query->num_rows() > 0){
            $item = $query->row_array();
			if ($this->agent->is_mobile()) {
			  $banner = $item['mobile_banner'];
			}
			else{
			  $banner = $item['banner'];
			}

			$resultdata = array(
				"id"     => $item['id'],
				"name"   => $item['name'],
				"slug"   => $item['slug'],
				"banner" => $banner,
			);

        }

        return $resultdata;
    }


    public function category_datas($search){
	    $resultdata     = array();

        if(!empty($search)){
            $keyword = implode(', ',$search);
            $keyword = " AND pc.category_id IN (" . $keyword . ")";

            $query = $this->db->query("SELECT p.*,pvar.selling_price as selling,pvar.size,pvar.product_mrp as mrp FROM products as p INNER JOIN product_variations as pvar ON p.id=pvar.product_id INNER JOIN products_category as pc ON p.id = pc.product_id WHERE p.status='1' $keyword GROUP BY pvar.product_id ORDER BY pvar.id ASC");

            // echo $this->db->last_query();

            if (!empty($query)) {
                foreach($query->result_array() as $item){

                    $size_name = '';
                    $size_id = $item['size'];
                    $size_option = $item['size'];
                    if($item['size'] != '' && $item['size'] != NULL) {
                        $size_arr = $this->common_model->getRowById('oc_attribute_values','name,attribute_id',$item['size']);
                        $size_name = $size_arr['name'];
                        $size_option = $size_arr['attribute_id'];
                    }

                    $rowid=0;
                    $flag = FALSE;
                    $dataTmp = $this->cart->contents();

                    $product_id = $item['id'];
                    $category_id = $item['category_id'];

                    foreach ($dataTmp as $cart) {
                        if ($cart['product_id'] == $item['id']) {
                            $flag  = TRUE;
                            $rowid = $cart['rowid'];
                            break;
                        }
                    }

                    if ($flag) {
                        $is_added = 1;
                        $quantity = $cart['qty'];
                        $rowid = $rowid;
                    }
                    else{
                        $is_added = 0;
                        $quantity = 0;
                        $rowid = 0;
                    }

                    if($item['you_save_amt']!='0'){
                        $product_price=$item['selling_price'];
                    } else{
                        $product_price=$item['product_mrp'];
                    }

                    $image = $this->db->select('image')->where('product_id', $item['id'])->limit(1)->order_by('id', 'ASC')->get('product_images');

                    if($image->num_rows() > 0){
                        $image = $image->row_array();
                        $image = cdn_url() . $image['image'];
                    } else {
                        $image = '';
                    }

                    $is_wishlist = 0;
                    if($this->session->userdata('user_login') == '1'){
                        $wishlist = $this->db->select('id')->where('product_id', $item['id'])->where('user_id', $this->session->userdata('user_id'))->get('tbl_wishlist');
                        if($wishlist->num_rows() > 0){
                            $is_wishlist = 1;
                        }
                    }

                    $query_cat = $this->db->query("SELECT slug FROM categories WHERE id='$category_id' LIMIT 1")->row_array();
                    $new_slug = $query_cat['slug'];

                    $avg_rating  = $this->crud_model->get_average_rating($product_id);

                    if($image!=''){
                        $resultdata[] = array(
                            "id"                    => $item['id'],
                            "category_id"      	    => $item['category_id'],
                            "category_slug"      	=> $new_slug,
                            "category_name"         => $this->common_model->get_category_name($item['category_id']),
                            "product_title"      	=> $item['name'],
                            "product_slug"          => $item['slug'],
                            // "featured_image"        => product_image_url().$item['featured_image'],
                            // "featured_image2"       => product_image_url().$item['featured_image2'],
                            "featured_image"        => $image,
                            "tags" 		    => $item['tags'],
                            "product_mrp" 		    => $item['mrp'],
                            "selling_price" 		=> $item['selling'],
                            "you_save_amt" 		    => $item['you_save_amt'],
                            "you_save_per" 		    => $item['you_save_per'],
                            "rate_avg" 		        => $item['rate_avg'],
                            "product_desc" 		    => $item['short_description'],
                            "offer_id" 	            => $item['offer_id'],
                            "alt_tag" 	            => $item['alt_tag'],
                            "is_wishlist"         => $is_wishlist,
                            "size_name"             => $size_name,
                            "size_id"               => $size_id,
                            "size_option"           => $size_option,
                            // "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
                            "product_price"         => $product_price,
                            "is_added"              => $is_added,
                            "quantity"              => $quantity,
                            "rowid"                 => $rowid,
                            // "head_code"             => $head_code,
                            "avg_rating"       => $avg_rating['average_rating']??0,
                        "total_review"     => $avg_rating['total_review']??0,
                        );
                    }

                }
            }
        }

        return $resultdata;
    }

    public function category_data($search, $school_id, $branch_id, $board_id = '', $class_id = '', $color = ''){
	    $resultdata     = array();
        if (!is_array($search)) {
            $search = ['0'];
        }

        if(!empty($search) || ($school_id != '' || $branch_id != '' || $board_id != '' || $class_id != '')){
            if(empty($search) || (count($search) == 1 && $search[0] == '0')) {
                $keyword = '';
            } else {
                $keyword = implode(', ',$search);
                $keyword = " AND p.uniform_type_id IN (" . $keyword . ")";
            }

            if($school_id != ''){
                $keyword .= " AND p.school_id = '" . $school_id . "'";
            } 
            
            if($branch_id != ''){
                $keyword .= " AND p.branch_id = '" . $branch_id . "'";
            }

            if($board_id != ''){
                $keyword .= " AND p.board_id = '" . $board_id . "'";
            }

            if($class_id != ''){
                $keyword .= " AND FIND_IN_SET('" . $class_id . "', p.class_id)";
            }

            if($color != ''){
                $colors = explode(',', $color);
                $colors = array_map(function($c) { return $this->db->escape_str(trim($c)); }, $colors);
                $keyword .= " AND p.color IN ('" . implode("','", $colors) . "')";
            }

            $query = $this->db->query("SELECT p.*,pvar.selling_price as selling,pvar.size_id as size,pvar.mrp FROM erp_uniforms as p INNER JOIN erp_uniform_size_prices as pvar ON p.id=pvar.uniform_id WHERE p.status='active' $keyword GROUP BY pvar.uniform_id ORDER BY pvar.id ASC");
            // echo $this->db->last_query(); exit();
            // print_r($query->result_array()); exit();
            
            if (!empty($query)) {
                foreach($query->result_array() as $item){

                    $size_name = '';
                    $size_id = $item['size'];
                    $size_option = $item['size'];
                    if($item['size'] != '' && $item['size'] != NULL) {
                        $size_arr = $this->common_model->getRowById('erp_sizes','name,id',$item['size']);
                        $size_name = $size_arr['name'];
                        $size_option = $size_arr['id'];
                    }

                    $rowid = 0;
                    $flag = FALSE;
                    $dataTmp = $this->cart->contents();

                    $product_id = $item['id'];
                    $category_id = $item['uniform_type_id'];
                    
                    foreach ($dataTmp as $cart) {
                        if ($cart['product_id'] == $item['id']) {
                            $flag  = TRUE;
                            $rowid = $cart['rowid'];
                            break;
                        }
                    }

                    if ($flag) {
                        $is_added = 1;
                        $quantity = $cart['qty'];
                        $rowid = $rowid;
                    }
                    else{
                        $is_added = 0;
                        $quantity = 0;
                        $rowid = 0;
                    }

                    // if($item['you_save_amt']!='0'){
                        $product_price=$item['selling_price'];
                    // } else{
                    //     $product_price=$item['product_mrp'];
                    // }

                    $image = $this->db->select('image_path')->where('uniform_id', $item['id'])->order_by('is_main', 'DESC')->limit(1)->get('erp_uniform_images');

                    if($image->num_rows() > 0){
                        $image = $image->row_array();
                        $vendor_db = $this->load->database('default', TRUE);
                        $vendor_db->limit(1);
                        $vendor_query = $vendor_db->get('erp_clients');
                        $domain = '';
                        if ($vendor_query && $vendor_query->num_rows() > 0) {
                            $vendor = $vendor_query->row_array();
                            $domain = isset($vendor['domain']) ? trim($vendor['domain']) : '';
                        }
                        if (!empty($domain)) {
                            $domain = rtrim($domain, '/');
                            if (!preg_match('/^https?:\/\//', $domain)) {
                                $domain = 'https://' . $domain;
                            }
                            $rel = '/uploads/' . ltrim($image['image_path'], '/');
                            $rel = str_replace('/uploads/uploads/', '/uploads/', $rel);
                            $image = $domain . $rel;
                        } else {
                            $image = base_url() . 'assets/uploads/' . ltrim($image['image_path'], '/');
                        }
                    } else {
                        $image = '';
                    }

                    $is_wishlist = 0;
                    if($this->session->userdata('user_login') == '1'){
                        // $wishlist = $this->db->select('id')->where('product_id', $item['id'])->where('user_id', $this->session->userdata('user_id'))->get('tbl_wishlist');
                        // if($wishlist->num_rows() > 0){
                        //     $is_wishlist = 1;
                        // }
                    }

                    $query_cat = $this->db->query("SELECT name FROM erp_uniform_types WHERE id='$category_id' LIMIT 1")->row_array();
                    // print_r($this->db->last_query()); exit();
                    // $new_slug = $query_cat['slug'];
                    $new_slug = 'uniform';

                    // $avg_rating  = $this->crud_model->get_average_rating($product_id);
                    $avg_rating  = [];

                    // ✅ Fetch Class Names for Badge
                    $class_names = "";
                    if (!empty($item['class_id'])) {
                        $class_ids = explode(',', $item['class_id']);
                        $class_query = $this->db->query("SELECT class_name FROM classes WHERE id IN (" . implode(',', array_map('intval', $class_ids)) . ")");
                        if ($class_query && $class_query->num_rows() > 0) {
                            $class_names = implode(', ', array_column($class_query->result_array(), 'class_name'));
                        }
                    }

                    // ✅ Fetch Board Name for Badge (Optional but good for consistency)
                    $board_name = "";
                    if (!empty($item['board_id'])) {
                        $board_query = $this->db->query("SELECT board_name FROM erp_school_boards WHERE id = ?", [$item['board_id']]);
                        if ($board_query && $board_query->num_rows() > 0) {
                            $board_name = $board_query->row()->board_name;
                        }
                    }

                    if($image!=''){
                        $resultdata[] = array(
                            "id"                    => $item['id'],
                            "category_id"      	    => $item['uniform_type_id'],
                            "category_slug"      	=> $new_slug,
                            "category_name"         => $query_cat['name'],
                            "product_title"      	=> $item['product_name'],
                            "product_slug"          => $item['slug'] ?? '',
                            "featured_image"        => $image,
                            "product_mrp" 		    => $item['mrp'],
                            "selling_price" 		=> $item['selling'],
                            "you_save_amt" 		    => $item['you_save_amt'] ?? 0,
                            "you_save_per" 		    => $item['you_save_per'] ?? 0,
                            "rate_avg" 		        => $item['rate_avg'],
                            "product_desc" 		    => $item['short_description'] ?? '',
                            "is_wishlist"           => $is_wishlist,
                            "size_name"             => $size_name,
                            "size_id"               => $size_id,
                            "size_option"           => $size_option,
                            "product_price"         => $product_price,
                            "is_added"              => $is_added,
                            "quantity"              => $quantity,
                            "rowid"                 => $rowid,
                            "avg_rating"            => $avg_rating['average_rating']??0,
                            "total_review"          => $avg_rating['total_review']??0,
                            "class_names"           => $class_names,
                            "board_name"            => $board_name,
                        );
                    }

                }
            }
        }

        // Get is_payment_required for branch/school - branch takes precedence over school
        $is_payment_required = 1;
        if (!empty($branch_id)) {
            $q = $this->db->query("SELECT COALESCE(is_payment_required, 1) as is_payment_required FROM erp_school_branches WHERE id = '" . $this->db->escape_str($branch_id) . "' LIMIT 1");
            if ($q && $q->num_rows() > 0) {
                $is_payment_required = (int)$q->row()->is_payment_required;
            }
        } elseif (!empty($school_id)) {
            $q = $this->db->query("SELECT COALESCE(is_payment_required, 1) as is_payment_required FROM erp_schools WHERE id = '" . $this->db->escape_str($school_id) . "' LIMIT 1");
            if ($q && $q->num_rows() > 0) {
                $is_payment_required = (int)$q->row()->is_payment_required;
            }
        }

        return array('products' => $resultdata, 'is_payment_required' => $is_payment_required);
    }

    public function search($search){
	    $resultdata     = array();
        if($search != ''){
            $keyword = '';
            $keyword .= " AND (p.name like '%" . $search . "%')";

            $query = $this->db->query("SELECT p.*,pvar.selling_price as selling,pvar.product_mrp as mrp FROM products as p INNER JOIN product_variations as pvar ON p.id=pvar.product_id  WHERE p.status='1' $keyword GROUP BY pvar.product_id ORDER BY pvar.id ASC LIMIT 10");

            if (!empty($query)) {
                foreach($query->result_array() as $item){
                    $rowid=0;
                    $flag = FALSE;
                    $dataTmp = $this->cart->contents();

                    $product_id = $item['id'];
                    $category_id = $item['category_id'];

                    foreach ($dataTmp as $cart) {
                        if ($cart['product_id'] == $item['id']) {
                            $flag  = TRUE;
                            $rowid = $cart['rowid'];
                            break;
                        }
                    }

                    if ($flag) {
                        $is_added = 1;
                        $quantity = $cart['qty'];
                        $rowid = $rowid;
                    }
                    else{
                        $is_added = 0;
                        $quantity = 0;
                        $rowid = 0;
                    }

                    if($item['you_save_amt']!='0'){
                        $product_price=$item['selling_price'];
                    } else{
                        $product_price=$item['product_mrp'];
                    }

                    $image = $this->db->select('image')->where('product_id', $item['id'])->limit(1)->order_by('id', 'ASC')->get('product_images');

                    if($image->num_rows() > 0){
                        $image = $image->row_array();
                        $image = cdn_url() . $image['image'];
                    } else {
                        $image = '';
                    }

                    $is_wishlist = 0;
                    if($this->session->userdata('user_login') == '1'){
                        $wishlist = $this->db->select('id')->where('product_id', $item['id'])->where('user_id', $this->session->userdata('user_id'))->get('tbl_wishlist');
                        if($wishlist->num_rows() > 0){
                            $is_wishlist = 1;
                        }
                    }

                    $query_cat = $this->db->query("SELECT slug FROM categories WHERE id='$category_id' LIMIT 1")->row_array();
                    $new_slug = $query_cat['slug'];

                    if($image!=''){
                        $resultdata[] = array(
                            "id"                    => $item['id'],
                            "category_id"      	    => $item['category_id'],
                            "category_slug"      	=> $new_slug,
                            "category_name"         => $this->common_model->get_category_name($item['category_id']),
                            "product_title"      	=> $item['name'],
                            "product_slug"          => $item['slug'],
                            // "featured_image"        => product_image_url().$item['featured_image'],
                            // "featured_image2"       => product_image_url().$item['featured_image2'],
                            "featured_image"        => $image,
                            "tags" 		    => $item['tags'],
                            "product_mrp" 		    => $item['mrp'],
                            "selling_price" 		=> $item['selling'],
                            "you_save_amt" 		    => $item['you_save_amt'],
                            "you_save_per" 		    => $item['you_save_per'],
                            "rate_avg" 		        => $item['rate_avg'],
                            "product_desc" 		    => $item['short_description'],
                            "offer_id" 	            => $item['offer_id'],
                            "alt_tag" 	            => $item['alt_tag'],
                            "is_wishlist"         => $is_wishlist,
                            // "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
                            "product_price"         => $product_price,
                            "is_added"              => $is_added,
                            "quantity"              => $quantity,
                            "rowid"                 => $rowid,
                            // "head_code"             => $head_code,
                        );
                    }

                }
            }
        }

        return $resultdata;
    }
    
      public function search_uniforms($search) {
        $resultdata = array();
        if ($search != '') {
            $safe_search = $this->db->escape_str($search);
            $query = $this->db->query("
                SELECT p.id, p.product_name, p.slug, p.color, p.school_id, p.branch_id
                FROM erp_uniforms as p
                WHERE p.status = 'active'
                  AND (p.product_name LIKE '%{$safe_search}%' OR p.slug LIKE '%{$safe_search}%')
                ORDER BY p.id ASC
                LIMIT 10
            ");

            if ($query && $query->num_rows() > 0) {
                // Resolve domain for image URLs
                $vendor_db  = $this->load->database('default', TRUE);
                $vendor_db->limit(1);
                $vendor_query = $vendor_db->get('erp_clients');
                $domain = '';
                if ($vendor_query && $vendor_query->num_rows() > 0) {
                    $vendor = $vendor_query->row_array();
                    $domain = isset($vendor['domain']) ? trim($vendor['domain']) : '';
                }
                if (!empty($domain)) {
                    $domain = rtrim($domain, '/');
                    if (!preg_match('/^https?:\/\//', $domain)) {
                        $domain = 'https://' . $domain;
                    }
                }

                foreach ($query->result_array() as $item) {
                    // Fetch main image
                    $img_query = $this->db->select('image_path')
                                          ->where('uniform_id', $item['id'])
                                          ->order_by('is_main', 'DESC')
                                          ->limit(1)
                                          ->get('erp_uniform_images');
                    $image = '';
                    if ($img_query && $img_query->num_rows() > 0) {
                        $img_row = $img_query->row_array();
                        if (!empty($domain)) {
                            $rel = '/uploads/' . ltrim($img_row['image_path'], '/');
                            $rel = str_replace('/uploads/uploads/', '/uploads/', $rel);
                            $image = $domain . $rel;
                        } else {
                            $image = base_url() . 'assets/uploads/' . ltrim($img_row['image_path'], '/');
                        }
                    }

                    $resultdata[] = array(
                        'id'           => $item['id'],
                        'product_name' => $item['product_name'],
                        'slug'         => $item['slug'],
                        'image'        => $image,
                        'color'        => $item['color'],
                    );
                }
            }
        }
        return $resultdata;
    }



    // public function search($search){
    //     $resultdata     = array();
    //     if($search!=''){
    //         $query = $this->db->query("SELECT id,product_title,product_slug,category_id,sub_category_id FROM tbl_product WHERE status='1' and product_title like '%$search%' and category_id<>'8' ORDER BY product_title asc limit 10");
    //         if ($query->num_rows()>0) {
    //             foreach($query->result_array() as $item){
    //                 $id = $item['id'];
    //                 $product_title = $item['product_title'];
    //                 $product_slug = $item['product_slug'];

    //                 $url='product-details/'.$product_slug;

    //                 $resultdata[] = array(
    //                     "value" => $id,
    //                     "label" => $product_title,
    //                     "url"   => $url
    //                 );
    //             }
    //         }
    //     }
    //     else{
    //         $resultdata     = array();
    //     }
    //     return $resultdata;
    // }

    public function get_categories(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id,category_name,category_slug,category_image FROM tbl_category WHERE status='1' ORDER BY id asc");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                        "category_id"               => $item['id'],
                        "category_name"      	    => $item['category_name'],
                        "category_slug"      	    => $item['category_slug'],
                        "category_image"       	    => cat_image_url().$item['category_image'],
                        "products"      	        => $products,
                    );
            }
        }

       return $resultdata;
    }

    public function get_featured_product(){
	    $resultdata     = array();

        $query = $this->db->query("SELECT p.*,pvar.id AS variation_id,pvar.selling_price as selling,pvar.size,pvar.product_mrp as mrp FROM products as p INNER JOIN product_variations as pvar ON p.id=pvar.product_id  WHERE p.status='1' GROUP BY pvar.product_id ORDER BY pvar.id ASC LIMIT 10");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $rowid=0;
                $flag = FALSE;
                $dataTmp = $this->cart->contents();

                $variation_id = $item['variation_id'];

    			$product_id = $item['id'];
				$category_id = $item['category_id'];

                $size_name = '';
                $size_id = $item['size'];
                $size_option = $item['size'];
                if($item['size'] != '' && $item['size'] != NULL) {
                    $size_arr = $this->common_model->getRowById('oc_attribute_values','name,attribute_id',$item['size']);
                    $size_name = $size_arr['name'];
                    $size_option = $size_arr['attribute_id'];
                }

                foreach ($dataTmp as $cart) {
                    if ($cart['product_id'] == $item['id']) {
                        $flag  = TRUE;
                        $rowid = $cart['rowid'];
                        break;
                    }
                }

                if ($flag) {
					$is_added = 1;
					$quantity = $cart['qty'];
					$rowid = $rowid;
                }
                else{
					$is_added = 0;
					$quantity = 0;
					$rowid = 0;
                }

                if($item['you_save_amt']!='0'){
                    $product_price=$item['selling_price'];
    			} else{
    			    $product_price=$item['product_mrp'];
    			}

                $image = $this->db->select('image')->where('product_id', $item['id'])->limit(1)->order_by('is_main', 'asc')->get('product_images');

                if($image->num_rows() > 0){
                    $image = $image->row_array();
                    $image = cdn_url() . $image['image'];
                } else {
                    $image = '';
                }

                $is_wishlist = 0;
                if($this->session->userdata('user_login') == '1'){
                    $wishlist = $this->db->select('id')->where('product_id', $item['id'])->where('user_id', $this->session->userdata('user_id'))->get('tbl_wishlist');
                    if($wishlist->num_rows() > 0){
                        $is_wishlist = 1;
                    }
                }

				$query_cat = $this->db->query("SELECT slug FROM categories WHERE id='$category_id' LIMIT 1")->row_array();
				$new_slug = $query_cat['slug'];


                $avg_rating  = $this->crud_model->get_average_rating($product_id);

				if($image!=''){
					$resultdata[] = array(
						"id"                    => $item['id'],
                        "variation_id"     => $variation_id,
						"category_id"      	    => $item['category_id'],
						"category_slug"      	=> $new_slug,
						"category_name"         => $this->common_model->get_category_name($item['category_id']),
						"product_title"      	=> $item['name'],
						"product_slug"          => $item['slug'],
                        "size_name"             => $size_name,
                        "size_id"               => $size_id,
                        "size_option"           => $size_option,
						// "featured_image"        => product_image_url().$item['featured_image'],
						// "featured_image2"       => product_image_url().$item['featured_image2'],
						"featured_image"        => $image,
						"tags" 		    => $item['tags'],
						"product_mrp" 		    => $item['mrp'],
						"selling_price" 		=> $item['selling'],
						"you_save_amt" 		    => $item['you_save_amt'],
						"you_save_per" 		    => $item['you_save_per'],
						"rate_avg" 		        => $item['rate_avg'],
						"product_desc" 		    => $item['short_description'],
						"offer_id" 	            => $item['offer_id'],
						"alt_tag" 	            => $item['alt_tag'],
						"is_wishlist"         => $is_wishlist,
						// "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
						"product_price"         => $product_price,
						"is_added"              => $is_added,
						"quantity"              => $quantity,
						"rowid"                 => $rowid,
						// "head_code"             => $head_code,
                        "avg_rating"       => $avg_rating['average_rating']??0,
                        "total_review"     => $avg_rating['total_review']??0,
					);
				}

            }
        }
        return $resultdata;
    }



    public function get_featured_on(){
        $resultdata     = array();
        $query = $this->db->query("SELECT image,title,featured_on,featured_link FROM featured_on WHERE is_active='1' ORDER BY id DESC LIMIT 10");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "title"     => $item['title'],
                    "featured_on"     => $item['featured_on'],
                    "featured_link"     => $item['featured_link'],
                    "featured_image"    => featured_on_url().$item['image'],
                    "featured_image2"    => featured_on_url().$item['image'],
                );
            }
        }

      return $resultdata;
    }

    public function get_top_rated_prod(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id,product_title,category_id,product_slug,product_mrp,selling_price,you_save_amt,you_save_per,rate_avg,featured_image,featured_image2,alt_tag FROM tbl_product WHERE status='1' AND is_top_related_product='1' ORDER BY RAND() LIMIT 10");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $rowid=0;
                $flag = FALSE;
                $dataTmp = $this->cart->contents();
    			$product_id = $item['id'];
                foreach ($dataTmp as $cart) {
                    if ($cart['product_id'] == $item['id']) {
                        $flag  = TRUE;
                        $rowid = $cart['rowid'];
                        break;
                    }
                }

                if ($flag) {
                    $is_added = 1;
                    $quantity = $cart['qty'];
                    $rowid = $rowid;
                }
                else{
                    $is_added = 0;
                    $quantity = 0;
                    $rowid = 0;
                }

                if($item['you_save_amt']!='0'){
                    $product_price=$item['selling_price'];
    			} else{
    			    $product_price=$item['product_mrp'];
    			}

    			$featured_image=array();
                $featured_image[]=product_image_url().$item['featured_image'];

                $resultdata[] = array(
                    "id"                => $item['id'],
                    "product_title"     => $item['product_title'],
                    "category_slug"  	=> $this->common_model->get_category_slug($item['category_id']),
                    "product_slug"  	=> $item['product_slug'],
                    "product_mrp"      	=> $item['product_mrp'],
                    "selling_price"     => $item['selling_price'],
                    "you_save_amt"      => $item['you_save_amt'],
                    "you_save_per" 		=> $item['you_save_per'],
                    "rate_avg" 		    => $item['rate_avg'],
                    "cart_image"        => $featured_image,
                    "featured_image"    => product_image_url().$item['featured_image'],
                    "featured_image2"   => product_image_url().$item['featured_image2'],
                    "is_added"          => $is_added,
                    "rowid"             => $rowid,
                    "quantity"          => $quantity,
                    "alt_tag" 		    => $item['alt_tag'],
                );
            }
        }

       return $resultdata;
    }

    public function get_related_product($cat_id,$sub_cat_id,$id){
        $resultdata     = array();

		if($sub_cat_id!='' && $sub_cat_id!=0){
		  $query = $this->db->query("SELECT id,product_title,category_id,product_slug,product_mrp,selling_price,you_save_amt,you_save_per,rate_avg,featured_image,featured_image2 FROM tbl_product WHERE category_id='$cat_id' AND sub_category_id='$sub_cat_id' AND id!='$id' AND status='1' ORDER BY id asc LIMIT 12");

		}
		else{
		  $query = $this->db->query("SELECT id,product_title,category_id,product_slug,product_mrp,selling_price,you_save_amt,you_save_per,rate_avg,featured_image,featured_image2 FROM tbl_product WHERE category_id='$cat_id' AND id!='$id' AND status='1' ORDER BY id asc LIMIT 12");
		}



        if (!empty($query)) {
            foreach($query->result_array() as $item){

                $rowid=0;
                $flag = FALSE;
                $dataTmp = $this->cart->contents();

    			$product_id = $item['id'];

                foreach ($dataTmp as $cart) {
                    if ($cart['product_id'] == $item['id']) {
                        $flag  = TRUE;
                        $rowid = $cart['rowid'];
                        break;
                    }
                }


                if ($flag) {
                 $is_added = 1;
                 $quantity = $cart['qty'];
                 $rowid = $rowid;
                }
                else{
                 $is_added = 0;
                 $quantity = 0;
                 $rowid = 0;
                }

                if($item['you_save_amt']!='0'){
                    $product_price=$item['selling_price'];
    			} else{
    			    $product_price=$item['product_mrp'];
    			}

                $resultdata[] = array(
                        "id"                => $item['id'],
                        "product_title"     => $item['product_title'],
                        "product_slug"  	=> $item['product_slug'],
                        "category_slug"  	=> $this->common_model->get_category_slug($item['category_id']),
                        "product_mrp"      	=> $item['product_mrp'],
                        "selling_price"     => $item['selling_price'],
                        "you_save_amt"      => $item['you_save_amt'],
                        "you_save_per" 		=> $item['you_save_per'],
                        "rate_avg" 		    => $item['rate_avg'],
                        "featured_image"    => product_image_url().$item['featured_image'],
                        "featured_image2"   => product_image_url().$item['featured_image2'],
                        "is_added"              => $is_added,
                        "rowid"                 => $rowid,
                        "quantity"                 => $quantity,
                    );
            }
        }

       return $resultdata;
    }




     public function get_product_deatils($id){
        $resultdata     = array();
        $query = $this->db->query("SELECT id,you_save_amt,you_save_per,selling_price,product_mrp,featured_image,featured_image2,yt_url,product_desc,category_id,sub_category_id,product_title,product_slug,rate_avg,offer_id,sku,packaging_size,packaging_type,short_description,composition,indication,product_does_and_dir,product_features,meta_title,meta_description,meta_keyword,yt_url,alt_tag,head_code,gcr_code,brand,specification FROM tbl_product WHERE product_slug='$id' AND status='1' ORDER BY id asc LIMIT 1");
        if (!empty($query)) {
            $item=$query->row_array();

            $rowid=0;
            $flag = FALSE;
            $dataTmp = $this->cart->contents();

			$product_id = $item['id'];

            foreach ($dataTmp as $cart) {
                if ($cart['product_id'] == $item['id']) {
                    $flag  = TRUE;
                    $rowid = $cart['rowid'];
                    break;
                }
            }


            if ($flag) {
             $is_added = 1;
             $quantity = $cart['qty'];
             $rowid = $rowid;
            }
            else{
             $is_added = 0;
             $quantity = 0;
             $rowid = 0;
            }
            if($item['you_save_amt']!='0'){
                $product_price=$item['selling_price'];
			} else{
			    $product_price=$item['product_mrp'];
			}


			$image_array = array();
			if($item['featured_image'] != ''){
			    $image_array[] = array(
                  "url"    =>  product_image_url().$item['featured_image'],
                  "image"   =>  product_image_url().$item['featured_image'],
                  "class"   => 'prod-img',
                );
			}

			if($item['featured_image2'] != ''){
				 $image_array[] = array(
                  "url"    =>  product_image_url().$item['featured_image2'],
                  "image"   =>  product_image_url().$item['featured_image2'],
                  "class"   => 'prod-img',
                );
			}


			if($item['yt_url'] != '' && $item['yt_url'] != NULL){
				 $video_details = $this->video_model->getVideoDetails($item['yt_url']);
				 $image_array[] = array(
                  "url"    =>  $item['yt_url'],
                  "image"   =>  $video_details['thumbnail'],
                  "class"   => 'yt-img',
                );
			}

            $query_image = $this->db->query("SELECT image_file FROM tbl_product_images WHERE parent_id='$product_id' AND status='1' AND type='product'");
            foreach($query_image->result_array() as $item_image){
                $gallery_img=product_image_url().'gallery/'.$item_image['image_file'];
				$image_array[] = array(
                  "url"     => $gallery_img,
                  "image"   => $gallery_img,
                  "class"   => 'prod-img',
                );
            }
            //$image_array = explode(',',trim($image_array));

            $product_desc=$item['product_desc'];

            //$product_desc.='<br/>-Manufactured By<br/><b>Rajasthan Herbals International</b><br/><a target="_blank" href="https://www.rajasthanherbalsinternational.com/">www.rajasthanherbalsinternational.com</a>';

            $resultdata = array(
                "id"                    => $item['id'],
                "category_id"      	    => $item['category_id'],
                "category_name"         => $this->common_model->get_category_name($item['category_id']),
				"sub_category_id"       => ($item['sub_category_id']!='' ? $item['sub_category_id']:0),
				"sub_category_name"      => $this->common_model->get_sub_category_name($item['sub_category_id']),
                "product_title"      	=> $item['product_title'],
                "product_slug"          => $item['product_slug'],
                "featured_image"        => product_image_url().$item['featured_image'],
                "featured_image2"       => product_image_url().$item['featured_image2'],
                "product_mrp" 		    => $item['product_mrp'],
                "selling_price" 		=> $item['selling_price'],
                "you_save_amt" 		    => $item['you_save_amt'],
                "you_save_per" 		    => $item['you_save_per'],
                "rate_avg" 		        => $item['rate_avg'],
                "offer_id" 	            => $item['offer_id'],
                "product_desc" 	        => $product_desc,
                "sku" 	 			    => $item['sku'],
                "packaging_size" 	    => $item['packaging_size'],
                "packaging_type" 	    => $item['packaging_type'],
                "short_description" 	=> $item['short_description'],
                "composition" 	    	=> $item['composition'],
                "indication" 	    	=> $item['indication'],
                "product_does_and_dir" 	=> $item['product_does_and_dir'],
                "product_features" 	    => $item['product_features'],
                "meta_image" 	    	=> product_image_url().$item['featured_image'],
                "meta_title" 	    	=> $item['meta_title'],
                "meta_description" 	    => $item['meta_description'],
                "meta_keyword" 	    	=> $item['meta_keyword'],
                "yt_url" 	    		=> $item['yt_url'],
                "alt_tag" 	    		=> $item['alt_tag'],
                "head_code" 	    	=> $item['head_code'],
                "gcr_code" 	    		=> $item['gcr_code'],
                "brand" 	    		=> $item['brand'],
                "specification" 	    => $item['specification'],
                "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
                "product_price"         => $product_price,
                "is_added"              => $is_added,
                "quantity"              => $quantity,
                "rowid"                 => $rowid,
                "image_array"           => $image_array,
            );
          }

       return $resultdata;
    }


    public function get_product_list_by_id($cat_id,$sub_cat_id,$per_page, $offset,$type){
        $resultdata     = array();
        $where = '';
        if($type=='special'){
            $where .= "and is_best_seller='1'";
        }
        else if($type=='bestSelling'){
            $where .= "and is_top_related_product='1'";
        }
        else if($sub_cat_id>0){
            $where .= "and category_id='$cat_id' and sub_category_id='$sub_cat_id'";

        }else{
            $where .= "and category_id='$cat_id'";
        }

        $query = $this->db->query("SELECT * FROM tbl_product WHERE status='1' $where ORDER BY RAND() asc LIMIT $offset,$per_page");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $rowid=0;
                $flag = FALSE;
                $dataTmp = $this->cart->contents();

    			$product_id = $item['id'];

                foreach ($dataTmp as $cart) {
                    if ($cart['product_id'] == $item['id']) {
                        $flag  = TRUE;
                        $rowid = $cart['rowid'];
                        break;
                    }
                }


                if ($flag) {
                 $is_added = 1;
                 $quantity = $cart['qty'];
                 $rowid = $rowid;
                }
                else{
                 $is_added = 0;
                 $quantity = 0;
                 $rowid = 0;
                }

                if($item['you_save_amt']!='0'){
                    $product_price=$item['selling_price'];
    			} else{
    			    $product_price=$item['product_mrp'];
    			}

    			$featured_image=array();
                $featured_image[]=product_image_url().$item['featured_image'];

                $resultdata[] = array(
                    "id"                    => $item['id'],
                    "category_id"      	    => $item['category_id'],
                    "category_name"         => $this->common_model->get_category_name($item['category_id']),
                    "product_title"      	=> $item['product_title'],
                    "product_slug"          => $item['product_slug'],
                    "cart_image"        => $featured_image,
                    "featured_image"        => product_image_url().$item['featured_image'],
                    "featured_image2"       => product_image_url().$item['featured_image2'],
                    "product_mrp" 		    => $item['product_mrp'],
                    "selling_price" 		=> $item['selling_price'],
                    "you_save_amt" 		    => $item['you_save_amt'],
                    "you_save_per" 		    => $item['you_save_per'],
                    "rate_avg" 		        => $item['rate_avg'],
                    "product_desc" 		    => $item['short_description'],
                    "offer_id" 	            => $item['offer_id'],
                    "alt_tag" 	            => $item['alt_tag'],
                    "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
                    "product_price"          => $product_price,
                    "is_added"              => $is_added,
                    "quantity"              => $quantity,
                    "rowid"                 => $rowid,
                );
            }
        }

       return $resultdata;
    }

    public function get_product_deatils_by_id($id){
        $resultdata     = array();
        $query = $this->db->query("SELECT * FROM tbl_product WHERE id='$id' AND status='1' ORDER BY id asc LIMIT 1");
        if (!empty($query)) {
            $item=$query->row_array();

            $rowid=0;
            $flag = FALSE;
            //$dataTmp = $this->cart->contents();

			$product_id = $item['id'];

            $is_added = 0;
            $quantity = 100;
            $rowid = 0;

            if($item['you_save_amt']!='0'){
                $product_price=$item['selling_price'];
			} else{
			    $product_price=$item['product_mrp'];
			}

			$image_array = '';
			if($item['featured_image'] != ''){
			    $image_array .= product_image_url().$item['featured_image'];
			}

			if($item['featured_image2'] != ''){
			    $image_array .= ','.product_image_url().$item['featured_image2'];
			}

            $query_image = $this->db->query("SELECT image_file FROM tbl_product_images WHERE parent_id='$product_id' AND status='1' AND type='product'");
            foreach($query_image->result_array() as $item_image){
                $image_array .= ','. product_image_url().'gallery/'.$item_image['image_file'];
            }
            $image_array = explode(',',$image_array);

            $featured_image[]=product_image_url().$item['featured_image'];
            $featured_image[]=product_image_url().$item['featured_image2'];

            $product_rating=array();

            $product_rating = $this->api_model->get_product_review($product_id);

            $avg_rating=0;

            $query2 = $this->db->query("SELECT AVG(rating) AS rating FROM tbl_rating where product_id='$product_id' limit 1");
            if (!empty($query2)) {
                $rat=$query2->row_array();
                $avg_rating = $rat['rating'];
            }
            $product_desc=$item['product_desc'];

            $resultdata = array(
                "id"                    => $item['id'],
                "category_id"      	    => $item['category_id'],
                "category_name"         => $this->common_model->get_category_name($item['category_id']),
                "sub_category_name"         => $this->common_model->get_sub_category_name($item['sub_category_id']),
                "product_title"      	=> $item['product_title'],
                "share_title"          => $item['product_title'],
                "share_msg"          => strip_tags($item['product_desc']),
                "share_link"          => 'https://www.raplgroup.in/product-details/'.$item['product_slug'],
                "cart_image"        => $featured_image,
                "featured_image"        => $featured_image,
                "product_mrp" 		    => $item['product_mrp'],
                "selling_price" 		=> $item['selling_price'],
                "you_save_amt" 		    => $item['you_save_amt'],
                "you_save_per" 		    => $item['you_save_per'],
                "rate_avg" 		        => $item['rate_avg'],
                "offer_id" 	            => $item['offer_id'],
                "product_desc" 	        => $product_desc,
                "product_features" 	    => $item['product_features'],
                "sku" 	    => $item['sku'],
                "packaging_size" 	    => $item['packaging_size'],
                "packaging_type" 	    => $item['packaging_type'],
                "short_description" 	    => $item['short_description'],
                "composition" 	    => $item['composition'],
                "indication" 	    => $item['indication'],
                "product_does_and_dir" 	    => $item['product_does_and_dir'],
                "product_features" 	    => $item['product_features'],
                "is_show_manufactured" 	    => 1,
                "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
                "product_price"         => $product_price,
                "is_added"              => $is_added,
                "quantity"              => $quantity,
                "rowid"                 => $rowid,
                "image_array"           => $image_array,
                "product_rating"        => $product_rating,
                "avg_rating"        => round($avg_rating, 1)
            );
          }

       return $resultdata;
    }

    public function get_product_deatils_($id='46'){
        $resultdata     = array();
        $query = $this->db->query("SELECT * FROM tbl_product WHERE id='$id' AND status='1' ORDER BY id asc LIMIT 1");
        if (!empty($query)) {
            $item=$query->row_array();

            $rowid=0;
            $flag = FALSE;
            $dataTmp = $this->cart->contents();

            foreach ($dataTmp as $cart) {
                if ($cart['product_id'] == $item['id']) {
                    $flag  = TRUE;
                    $rowid = $cart['rowid'];
                    break;
                }
            }


            if ($flag) {
             $is_added = 1;
             $quantity = $cart['qty'];
             $rowid = $rowid;
            }
            else{
             $is_added = 0;
             $quantity = 0;
             $rowid = 0;
            }

            if($item['you_save_amt']!='0'){
                $product_price=$item['selling_price'];
			} else{
			    $product_price=$item['product_mrp'];
			}


            $resultdata = array(
                "id"                    => $item['id'],
                "category_id"      	    => $item['category_id'],
                "category_name"         => $this->common_model->get_category_name($item['category_id']),
                "product_title"      	=> $item['product_title'],
                "product_slug"          => $item['product_slug'],
                "featured_image"        => $item['featured_image'],
                "featured_image2"       => $item['featured_image2'],
                "product_mrp" 		    => $item['product_mrp'],
                "selling_price" 		=> $item['selling_price'],
                "you_save_amt" 		    => $item['you_save_amt'],
                "you_save_per" 		    => $item['you_save_per'],
                "rate_avg" 		        => $item['rate_avg'],
                "offer_id" 	            => $item['offer_id'],
                "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
                "product_price"          => $product_price,
                "is_added"              => $is_added,
                "quantity"              => $quantity,
                "rowid"                 => $rowid,
            );
          }

       return $resultdata;
    }

    public function get_product_details_by_id($product_id,$variationArray) {
        $data=array();
        // $var_product = $this->db->query("SELECT id,category_id,product_slug as slug,product_title as name,is_variation,gst,hsn_code as hsn,sku FROM tbl_product WHERE id='$product_id' LIMIT 1");
        $var_product = $this->db->query("SELECT id,slug,uniform_type_id,product_name as name,'1' as is_variation,gst_percentage as gst,packaging_weight as weight,packaging_length as length,packaging_width as width,packaging_height as height,hsn,isbn as sku,'0' as is_stock,school_id,branch_id FROM erp_uniforms WHERE id='$product_id' LIMIT 1");
        if (!empty($var_product)) {
            $product=$var_product->row();

            $data['product_id']     = $product->id;
            $data['name']           = $product->name;
            $data['slug']           = $product->slug;
            $data['is_variation']   = 1; // Default value since column doesn't exist
            $data['gst']            = $product->gst;
            $data['weight']         = $product->weight;
            $data['length']         = $product->length;
            $data['width']          = $product->width;
            $data['height']         = $product->height;
            $data['category_id']    = 0; // Default value since column doesn't exist
            $data['hsn']            = $product->hsn;
            $data['sku']            = $product->sku;
            $data['is_stock']       = $product->is_stock;
            $data['school_id']      = $product->school_id;
            $data['branch_id']      = $product->branch_id;

            $cat = $this->db->query("SELECT name FROM erp_uniform_types WHERE id = '{$product->uniform_type_id}' LIMIT 1")->row();
            $data['category_name'] = $cat ? $cat->name : '';

		    $sql  = $this->db->query("SELECT id,image_path as image FROM erp_uniform_images WHERE uniform_id='$product_id' ORDER BY is_main DESC LIMIT 1");
			$thumbnail_img = '';
			if($sql->num_rows()>0){
				$gimg=$sql->row_array();
				$image_path = $gimg['image'];
				$thumbnail_img = $image_path; // Store relative path, views will use base_url()
				$data['thumbnail_img']  = $image_path;
			} else{
				$thumbnail_img = 'assets/images/default_img.jpg';
				$data['thumbnail_img']  = '';
			}
            $data['image']  = $thumbnail_img;
        }

        if($product->is_variation==1){
            $code = '';
            $variationArray = (array) $variationArray;
            
			if (!empty($variationArray)) {
                $lstKey = array_key_last($variationArray);
                $code = $variationArray[$lstKey];
				// foreach ($variationArray as $option_id => $choice_id) {
				// 	$option_name = $this->common_model->getNameById('oc_attributes', 'name', $option_id);
				// 	$choice_name = $this->common_model->getNameById('oc_attribute_values', 'name', $choice_id);

				// 	$name .= $option_name . ': ' . $choice_name;
				// 	$code .= $option_id . ':' . $choice_id . '/';

				// 	if ($lstKey != $option_id) {
				// 		$name .= ' / ';
				// 	}
				// }
			}
            

            $var_query = $this->db->query("SELECT id,mrp as product_mrp,selling_price,'0' as disc_per FROM erp_uniform_size_prices WHERE uniform_id='$product_id' AND size_id='$code'  LIMIT 1");
            if (!empty($var_query)) {
               $var=$var_query->row();
               $variation_id=$var->id;
			//    $stock = get_stock_qty_2($product_id, $variation_id);
			   $stock = 99;
               $selling_price = get_selling_price($product_id, $variation_id);

                $var_name = $this->common_model->getNameById('erp_sizes', 'name', $code);

               $data['variation_id']   = $var->id;
               $data['product_mrp']     = $var->product_mrp;
               $data['disc_per']        = $var->disc_per;
               $data['selling_price']  = $selling_price;
               $data['product_price']  = $selling_price;
               $data['stock']          = $stock;
               $data['variation_name'] = "Size: " . $var_name;
            }
        }
        else{
			$var_query = $this->db->query("SELECT id, sku, product_mrp, disc_per, selling_price, stock, code FROM product_variations WHERE product_id='$product_id' LIMIT 1");
			if (!empty($var_query)) {
				$var=$var_query->row();
				$variation_id=$var->id;
				//$sku=$var->sku;

				// $stock = get_stock_qty_2($product_id, $variation_id);
				$stock = 99;
                $selling_price=get_selling_price($product_id,$variation_id);

				$data['variation_id']   = $var->id;
				$data['product_mrp']    = $var->product_mrp;
				$data['disc_per']       = $var->disc_per;
				$data['selling_price']  = $selling_price;
				$data['product_price']  = $selling_price;
				$data['code']           = $var->code;
				$data['stock']          = $stock;
				$data['variation_name'] = $name;
			}

			$var_image = $this->db->query("SELECT image FROM product_images WHERE product_id='$product_id' ORDER BY id ASC LIMIT 1");
			if ($var_image->num_rows()>0) {
				$data['image'] = cdn_url().$var_image->row()->image;
			} else{
				$var_image = $this->db->query("SELECT image FROM product_images WHERE product_id='$product_id' ORDER BY id ASC LIMIT 1");
				if (!empty($var_image)) {
					$data['image'] = cdn_url().$var_image->row()->image;
				} else{
					$data['image'] = cdn_url().'assets/images/default.jpg';
				}
			}
        }

		$data['package_items'] = '';
        return $data;
    }

      public function get_product_by_id($product_id,$variationArray) {
         $data=array();
        $var_product = $this->db->query("SELECT id,slug,product_name as name,'1' as is_variation,gst_percentage as gst,packaging_weight as weight,packaging_length as length,packaging_width as width,packaging_height as height,hsn,isbn as sku,'0' as is_stock FROM erp_uniforms WHERE id='$product_id' LIMIT 1");

        // echo $product_id;
        // exit();

        if (!empty($var_product)) {
            $product=$var_product->row();

            $data['product_id']     = $product->id;
            $data['name']           = $product->name;
            $data['slug']           = $product->slug;
            $data['is_variation']   = 1; // Default value since column doesn't exist
            $data['gst']            = $product->gst;
            $data['weight']         = $product->weight;
            $data['length']         = $product->length;
            $data['breadth']        = $product->width;
            $data['height']         = $product->height;
            $data['category_id']    = 0; // Default value since column doesn't exist
            $data['is_stock']    = $product->is_stock;
            $data['category']    	= '';
            $data['hsn']            = $product->hsn;
            $data['sku']            = $product->sku;

		    $sql  = $this->db->query("SELECT id,image_path as image FROM erp_uniform_images WHERE uniform_id='$product_id' ORDER BY is_main DESC LIMIT 1");
			$thumbnail_img = '';
			if($sql->num_rows()>0){
				$gimg=$sql->row_array();
				$image_path = $gimg['image'];
				$thumbnail_img = $image_path; // Store relative path, views will use base_url()
				$data['thumbnail_img']  = $image_path;
			} else{
				$thumbnail_img = 'assets/images/default_img.jpg';
				$data['thumbnail_img']  = '';
			}
            $data['image']  = $thumbnail_img;
        }

        if($product->is_variation==1){
            $code = '';

            if(!empty($variationArray)) {
				$lstKey = array_key_last($variationArray);
                $code = $variationArray[$lstKey];
				// foreach ($variationArray as $option_id => $choice_id) {
				// 	$option_name=$this->common_model->getNameById('oc_attributes','name',$option_id);
				// 	$choice_name=$this->common_model->getNameById('oc_attribute_values','name',$choice_id);
				// 	$name .= $option_name . ': ' . $choice_name;
				// 	$code .= $option_id . ':' . $choice_id . '/';

				// 	if ($lstKey != $option_id) {
				// 		$name .= ' / ';
				// 	}
				// }
            }

            $var_query = $this->db->query("SELECT id,mrp as product_mrp,selling_price,'0' as disc_per FROM erp_uniform_size_prices WHERE uniform_id='$product_id' AND size_id='$code'  LIMIT 1");
            if (!empty($var_query)) {
               $var=$var_query->row();
               $variation_id=$var->id;
			   $stock = 99;
			//    $stock = get_stock_qty($product_id, $variation_id);
               $selling_price=get_selling_price($product_id,$variation_id);
			//    $product_mrp=get_mrp_price($product_id,$variation_id);

               // echo $stock;

               $data['variation_id']   = $var->id;
               $data['product_mrp']     = $var->product_mrp;
               $data['disc_per']        = $var->disc_per;
               $data['selling_price']  = $selling_price;
               $data['product_price']  = $selling_price;
               $data['price_mrp']     	= $var->product_mrp;
               $data['code']           = '';
               $data['stock']          = $stock;
               $data['variation_name'] = $product->name;

            }
        } else {
			$var_query = $this->db->query("SELECT id,sku,product_mrp,disc_per,selling_price,stock,code,weight,length,width as breadth,height FROM product_variations WHERE product_id='$product_id' LIMIT 1");
			if (!empty($var_query)) {
				$var=$var_query->row();
				$variation_id=$var->id;

				$stock = get_stock_qty($product_id, $variation_id);
                $selling_price=get_selling_price($product_id,$variation_id);

				$data['variation_id']   = $var->id;
				$data['product_mrp']    = $var->product_mrp;
				$data['disc_per']       = $var->disc_per;
				$data['selling_price']  = $selling_price;
				$data['product_price']  = $selling_price;
                $data['price_mrp']     	= $var->product_mrp;
				$data['code']           = $var->code;
				$data['stock']          = $stock;
				$data['variation_name'] = $name;

			   $data['weight']         = $var->weight;
			   $data['length']         = $var->length;
			   $data['breadth']        = $var->breadth;
			   $data['height']         = $var->height;
			}

			$var_image = $this->db->query("SELECT image FROM product_images WHERE product_id='$product_id' LIMIT 1");
			if ($var_image->num_rows()>0) {
				$data['image'] = image_url().$var_image->row()->image;
			} else{
				$var_image = $this->db->query("SELECT image FROM product_images WHERE product_id='$product_id' LIMIT 1");
				if (!empty($var_image)) {
					$data['image'] = cdn_url().$var_image->row()->image;
				} else{
					$data['image'] = cdn_url().'assets/images/default.jpg';
				}
			}
        }

		$data['package_items'] = '';
        return $data;
    }

    public function cart_total() {
	    $count = 0;
	    $total = 0;
        foreach($this->cart->contents() as $items)
        {
            $total = $total+($items['qty']*$items['price']);
            $count++;
        }
        $resultdata= array(
            "total" => $total,
            "count" => $count,
        );
        return $resultdata;
	}

	public function cart_count() {
	    $count = 0;
        foreach($this->cart->contents() as $items)
        {
           $count++;
        }
        return $count;
	}


    public function get_cart_product_details(){
        $resultdata = array();
        $cart_total = 0;

        $cart    = $this->cart->contents();
        $dataTmp = ($cart != '' ? array_values($cart) : array());

         if (!empty($dataTmp)) {
            foreach ($dataTmp as $item) {
                $uid        = $item['id'];
                $product_id = $item['product_id'];
                $rowid      = $item['rowid'];
                $qty        = $item['qty'];

                $order_type        = $item['order_type'];

				$variationArray=json_decode($item['variation'],true);
				$product= $this->crud_model->get_product_by_id($product_id,$variationArray);
				$product_total_price = 0;
				$product_price       = 0;


				if ($product['image'] != '') {
					$image = $product['image'];
				} else {
					$image = image_url() . 'assets/images/default.jpg';
				}

				$variation_id=$product['variation_id'];
				$stock = 99;
				// $stock = get_stock_qty($product_id, $variation_id);

				$show_var='';
				if($item['is_variation'] == 1){
					$show_var= '<p class="mb-1"><small>(' . $item['variation_name'] . ')</small></p>';
				}

				$is_free_item = isset($item['is_free_item']) && $item['is_free_item'] == true;

				if ($is_free_item) {
					// For free item: prices and tax are 0
					$selling_price = 0;
					$product_mrp = isset($item['price_mrp']) ? $item['price_mrp'] : 0;
					$disc_amt = $product_mrp; // whole discount
					$disc_per = 100;
					$product_price = 0;
					$product_total_price = 0;
					$gst_amt = 0;
				} else {
					// Regular price calculation
					$selling_price = get_selling_price($product_id, $variation_id);
					$product_mrp = get_mrp_price($product_id, $variation_id);
					$gdis = cal_dis_array($product_mrp, $selling_price);
					$disc_amt = $gdis['discount_amt'];
					$disc_per = $gdis['discount_per'];
					$product_price = $selling_price;
					$product_total_price = $qty * $product_price;
					$gst_amt = get_gst_amt($product_total_price, $product['gst']);
				}

                $resultdata[] = array(
					"id" => $item['product_id'],
					"product_id" => $item['product_id'],
					"name" => $item['name'],
					"variation_name" => $item['variation_name'],
					"stock" => $stock,
					"order_type" => $item['order_type'],
					"package_id" => $item['package_id'],
					"f_name" => $item['f_name'],
					"m_name" => $item['m_name'],
					"s_name" => $item['s_name'],
					"dob" => $item['dob'],
					"school_id" => isset($item['school_id']) ? $item['school_id'] : null,
					"branch_id" => isset($item['branch_id']) ? $item['branch_id'] : null,
					"package_items" => $product['package_items'],
					"variation_id" => $product['variation_id'],
					"product_title" => $product['name'],
					"product_slug" => $product['slug'],
					"product_mrp" => $product_mrp,
					"disc_per" => $disc_per,
					"disc_amt" => $disc_amt,
					"selling_price" => $selling_price,
					"you_save_amt" => 0,
					"you_save_per" => 0,
					"gst" => $product['gst'],
					"gst_amt" => $gst_amt,
					"image" => $image,
					"product_price" => $product_price,
					"uid" => $uid,
					"quantity" => $qty,
                    "rowid" => $rowid,
                    "product_total_price" => $product_total_price,
                    "is_variation" => $product['is_variation'],
                    "variation" => $item['variation'],
                    "show_var" => $show_var,
                    "is_free_item" => $is_free_item
				);

                // Determine payment requirement and deliver at school - branch takes precedence over school
                $is_payment_required = 1; // Default to required
                $deliver_at_school = 1; // Default to address required
                if ($item['branch_id']) {
                    $query = $this->db->query("SELECT COALESCE(is_payment_required, 1) as is_payment_required, COALESCE(deliver_at_school, 1) as deliver_at_school FROM erp_school_branches WHERE id = '" . $item['branch_id'] . "'");
                    if($query->num_rows() > 0) {
                        $row = $query->row_array();
                        $is_payment_required = (int)$row['is_payment_required'];
                        $deliver_at_school = (int)$row['deliver_at_school'];
                    }
                } elseif ($item['school_id']) {
                    $query = $this->db->query("SELECT COALESCE(is_payment_required, 1) as is_payment_required, COALESCE(deliver_at_school, 1) as deliver_at_school FROM erp_schools WHERE id = '" . $item['school_id'] . "'");
                    if($query->num_rows() > 0) {
                        $row = $query->row_array();
                        $is_payment_required = (int)$row['is_payment_required'];
                        $deliver_at_school = (int)$row['deliver_at_school'];
                    }
                }
                $resultdata[count($resultdata)-1]["is_payment_required"] = $is_payment_required;
                $resultdata[count($resultdata)-1]["deliver_at_school"] = $deliver_at_school;
            }
        }
        return $resultdata;
    }

    /**
     * Check if current cart has any items that require deliver at school (student info instead of address)
     * @return int 1 if any cart item has deliver_at_school=1, else 0
     */
    public function get_order_is_deliver_at_school()
    {
        $cart_details = $this->get_cart_product_details();
        foreach ($cart_details as $cd) {
            if (isset($cd['deliver_at_school']) && (int)$cd['deliver_at_school'] === 1) {
                return 1;
            }
        }
        return 0;
    }

    /**
     * Get order type from cart for tbl_order_details.type_order.
     * Returns 'uniform' if any cart item is uniform, 'bookset' if any is bookset, else 'individual'.
     */
    public function get_order_type_from_cart()
    {
        $cart = $this->cart->contents();
        if (empty($cart)) {
            return 'individual';
        }
        foreach ($cart as $item) {
            $ot = isset($item['order_type']) ? $item['order_type'] : '';
            if ($ot === 'bookset') {
                return 'bookset';
            }
            if ($ot === 'uniform') {
                return 'uniform';
            }
        }
        return 'individual';
    }

    public function get_prod_by_id($product_id,$variationArray) {
        $data=array();
        $var_product = $this->db->query("SELECT id,slug,name,gst,weight,length,width,height,hsn,sku,is_stock FROM products WHERE id='$product_id' LIMIT 1");

        // echo $product_id;
        // exit();

        if (!empty($var_product)) {
            $product=$var_product->row();

            $data['product_id']     = $product->id;
            $data['name']           = $product->name;
            $data['slug']           = $product->slug;
            $data['is_variation']   = 0; // Default value since column doesn't exist
            $data['gst']            = $product->gst;
            $data['weight']         = $product->weight;
            $data['length']         = $product->length;
            $data['width']          = $product->width;
            $data['height']         = $product->height;
            $data['category_id']    = $product->category_id;
            $data['is_stock']    = $product->is_stock;
            $data['category']    	= $this->common_model->getBulkNameIds('categories','name',$product->category_id);
            $data['hsn']            = $product->hsn;
            $data['sku']            = $product->sku;

		    $sql  = $this->db->query("SELECT id,image FROM product_images WHERE product_id='$product_id' ORDER BY is_main DESC LIMIT 1");
			$thumbnail_img = '';
			if($sql->num_rows()>0){
				$gimg=$sql->row_array();
				$thumbnail_img = cdn_url().$gimg['image'];
				$data['thumbnail_img']  = $gimg['image'];
			} else{
				$thumbnail_img = cdn_url() . 'assets/images/default_img.jpg';
				$data['thumbnail_img']  = '';
			}
            $data['image']  = $thumbnail_img;
        }

        if($product->is_variation==1){
            $code = '';
            $lstKey = array_key_last($variationArray);
            if(!empty($variationArray)) {
            foreach ($variationArray as $option_id => $choice_id) {
                $option_name=$this->common_model->getNameById('oc_attributes','name',$option_id);
                $choice_name=$this->common_model->getNameById('oc_attribute_values','name',$choice_id);
                $name .= $option_name . ': ' . $choice_name;
                $code .= $option_id . ':' . $choice_id . '/';

                if ($lstKey != $option_id) {
                    $name .= ' / ';
                }
             }
            }

            $var_query = $this->db->query("SELECT id,sku,product_mrp,selling_price,disc_per,stock,code FROM product_variations WHERE product_id='$product_id' AND code='$code'  LIMIT 1");
            if (!empty($var_query)) {
               $var=$var_query->row();
               $variation_id=$var->id;
			   $stock = get_stock_qty($product_id, $variation_id);
               $selling_price=get_selling_price($product_id,$variation_id);
			   $product_mrp=get_mrp_price($product_id,$variation_id);

               // echo $stock;

               $data['variation_id']   = $var->id;
               $data['product_mrp']     = $var->product_mrp;
               $data['disc_per']        = $var->disc_per;
               $data['selling_price']  = $selling_price;
               $data['product_price']  = $selling_price;
               $data['price_mrp']     	= $var->product_mrp;
               $data['code']           = $var->code;
               $data['stock']          = $stock;
               $data['variation_name'] = $name;
            }
        } else {
			$var_query = $this->db->query("SELECT id,sku,product_mrp,disc_per,selling_price,stock,code FROM product_variations WHERE product_id='$product_id' LIMIT 1");
			if (!empty($var_query)) {
				$var=$var_query->row();
				$variation_id=$var->id;

				$stock = get_stock_qty($product_id, $variation_id);
                $selling_price=get_selling_price($product_id,$variation_id);

				$data['variation_id']   = $var->id;
				$data['product_mrp']    = $var->product_mrp;
				$data['disc_per']       = $var->disc_per;
				$data['selling_price']  = $selling_price;
				$data['product_price']  = $selling_price;
                $data['price_mrp']     	= $var->product_mrp;
				$data['code']           = $var->code;
				$data['stock']          = $stock;
				$data['variation_name'] = $name;
			}

			$var_image = $this->db->query("SELECT image FROM product_images WHERE product_id='$product_id' LIMIT 1");
			if ($var_image->num_rows()>0) {
				$data['image'] = image_url().$var_image->row()->image;
			} else{
				$var_image = $this->db->query("SELECT image FROM product_images WHERE product_id='$product_id' LIMIT 1");
				if (!empty($var_image)) {
					$data['image'] = cdn_url().$var_image->row()->image;
				} else{
					$data['image'] = cdn_url().'assets/images/default.jpg';
				}
			}
        }

		$data['package_items'] = '';
        return $data;
    }

    public function coupon_list() {
        $query = $this->db->query("SELECT * FROM offers WHERE status='1' and is_show='1' ORDER BY id asc");
        return $query->Result();
    }
	
	public function coupon_count() {
		$query = $this->db->query("SELECT COUNT(*) as total FROM offers WHERE status='1' AND is_show='1'");
		return $query->row()->total;
	}

    public function shorter($text, $chars_limit)
    {
        // Check if length is larger than the character limit
        if (strlen($text) > $chars_limit)
        {
            // If so, cut the string at the character limit
            $new_text = substr($text, 0, $chars_limit);
            // Trim off white space
            $new_text = trim($new_text);
            // Add at end of text ...
            return $new_text . "...";
        }
        // If not just return the text as is
        else
        {
        return $text;
        }
    }
	
	public function get_like_products(){
		$resultdata = array();
		$cart = $this->cart->contents();
		$dataTmp = (!empty($cart) ? array_values($cart) : array());

		if (!empty($dataTmp)) {
			$cartProductIds = array_column($dataTmp, 'product_id');
			$cartProductIdsStr = implode(',', $cartProductIds);

			// Get all unique category IDs from products in the cart
			$categoryIds = [];
			foreach ($cartProductIds as $pid) {
				$product = $this->common_model->getRowById('erp_uniforms', 'uniform_type_id AS category_id', $pid);
				if ($product && !in_array($product['category_id'], $categoryIds)) {
					$categoryIds[] = $product['category_id'];
				}
			}

			if (!empty($categoryIds)) {
				$categoryIdsStr = implode(',', $categoryIds);

				// Get similar products from the same categories but NOT in cart
				$query = $this->db->query("
					SELECT p.*, pvar.id AS variation_id, pvar.selling_price as selling, pvar.size_id as size, pvar.mrp
					FROM erp_uniforms as p
					INNER JOIN erp_uniform_size_prices as pvar ON p.id = pvar.uniform_id
					WHERE p.status = 'active'
					AND p.id NOT IN ($cartProductIdsStr)
					GROUP BY pvar.uniform_id
					ORDER BY pvar.id ASC
					LIMIT 6
				"); 

				if (!empty($query)) {
					foreach ($query->result_array() as $item) {
						$variation_id = $item['variation_id'];
						$product_id = $item['id'];
						$category_id = $item['uniform_type_id'];

						// Size Information
						$size_name = '';
						$size_id = $item['size'];
						$size_option = $item['size'];
						if (!empty($item['size'])) {
							$size_arr = $this->common_model->getRowById('erp_sizes', 'name, "2" as attribute_id', $item['size']);
							if ($size_arr) {
								$size_name = 'Size: ' . $size_arr['name'];
								$size_option = $size_arr['attribute_id'];
							}
						}

						// Product Image
						$image = $this->db->select('image_path as image')->where('uniform_id', $product_id)->limit(1)->order_by('is_main', 'asc')->get('erp_uniform_images');
						if ($image->num_rows() > 0) {
							$image_path = $image->row_array()['image'];
							$image = $image_path; // Store relative path, views will use base_url()
						} else {
							$image = '';
						}

						// Wishlist Check
						$is_wishlist = 0;
						
						// Category Slug
						$new_slug = 'uniform';

						// Average Rating
						// $avg_rating = $this->crud_model->get_average_rating($product_id);

						// Price Calculation
						$product_price = ($item['you_save_amt'] != '0') ? $item['selling_price'] : $item['product_mrp'];

						if ($image != '') {
							$resultdata[] = array(
								"id"                => $product_id,
								"variation_id"      => $variation_id,
								"category_id"       => $category_id,
								"category_slug"     => $new_slug,
								"category_name"     => $this->common_model->get_category_name($category_id),
								"product_title"     => $item['name'],
								"product_slug"      => $item['slug'],
								"size_name"         => $size_name,
								"size_id"           => $size_id,
								"size_option"       => $size_option,
								"featured_image"    => $image,
								"tags"              => $item['tags'],
								"product_mrp"       => $item['mrp'],
								"selling_price"     => $item['selling'],
								"you_save_amt"      => $item['you_save_amt'],
								"you_save_per"      => $item['you_save_per'],
								"rate_avg"          => $item['rate_avg'],
								"product_desc"      => $item['short_description'],
								"offer_id"          => $item['offer_id'],
								"alt_tag"           => $item['alt_tag'],
								"is_wishlist"       => $is_wishlist,
								"product_price"     => $product_price,
								"is_added"          => 0, // Since these are not in cart
								"quantity"          => 0,
								"rowid"             => 0,
								"avg_rating"        => 0,
								"total_review"      => 0,
							);
						}
					}
				}
			}
		}
		return $resultdata;
	}

    public function get_press_release(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, url, title, image FROM press_release ORDER BY id DESC limit 10");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"        => $item['id'],
                    "url"       => $item['url'],
                    "title"     => $item['title'],
                    "image"     => cdn_url().$item['image'],
                );
            }
        }

       return $resultdata;
    }

    public function get_all_testimonials(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, name, job_title, description, profile_image, rating FROM testimonials WHERE status='1' ORDER BY id DESC limit 10");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"                    => $item['id'],
                    "name"      	        => $item['name'],
                    "job_title"      	    => $item['job_title'],
                    "rating"      	    => $item['rating'],
                    "description"      	    => $item['description'],
                    "profile_image"         => cdn_url().$item['profile_image'],
                );
            }
        }

       return $resultdata;
    }

    public function get_testimonial(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, name, location, description, testimonial_image FROM tbl_testimonial WHERE status='1' ORDER BY id DESC limit 10");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                        "id"                    => $item['id'],
                        "name"      	        => $item['name'],
                        "location"      	    => $item['location'],
                        "description"      	    => $item['description'],
                        "testimonial_image"     => testimonial_image_url().$item['testimonial_image'],
                    );
            }
        }

       return $resultdata;
    }

    public function get_popup(){
        $resultdata     = array();
        $query = $this->db->query("SELECT image FROM tbl_popup WHERE is_active='1' limit 1");
        if (!empty($query)) {
            $img=$query->row_array();
            $image = (!empty($img) && isset($img['image'])) ? $img['image'] : '';
        }
        else{
            $image='';
        }
        return $image;
    }

    public function get_banner() {
        $resultdata = array();
		
		// First try to get banners from the backend banners table
		// Connect to the main ERP database to fetch vendor banners
		$erp_db = $this->load->database('default', TRUE);
		
		// Get active banners from the backend banners table
		$backend_banners = $erp_db->query("
			SELECT id, banner_image, alt_text, caption, is_active, sort_order 
			FROM banners 
			WHERE is_active = 1 
			ORDER BY sort_order ASC, created_at DESC
		")->result_array();
		
		// If we have backend banners, use those
		if (!empty($backend_banners)) {
			foreach ($backend_banners as $item) {
				// Ensure the banner image path is correctly formatted for frontend access
				$banner_path = $item['banner_image'];
				if (strpos($banner_path, 'http') !== 0 && strpos($banner_path, '//') !== 0) {
					// Prepend base URL if it's a relative path
					if (strpos($banner_path, 'uploads/') === 0) {
						// This is already in the correct format for frontend access
						$image_url = base_url() . '../' . $banner_path;
					} else {
						// Add uploads prefix if not already present
						$image_url = base_url() . '../uploads/' . $banner_path;
					}
				} else {
					$image_url = $banner_path;
				}
				
				$resultdata[] = array(
					"id"        => $item['id'],
					"link"      => "", // No link specified in backend banners
					"alt_text"  => $item['alt_text'] ?? '',
					"caption"   => $item['caption'] ?? '',
					"file"      => $image_url
				);
			}
		} else {
			// Fallback to original method if no backend banners exist
			if ($this->agent->is_mobile()) {
			  $query = $this->db->query("SELECT id,image,link FROM mobile_slider WHERE status='1' ORDER BY item_order ASC limit 6");
			}
			else{
          $query = $this->db->query("SELECT id,image,link FROM slider WHERE status='1' ORDER BY id DESC limit 6");
			}

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"    => $item['id'],
                    "link"  => $item['link'],
                    "file"  => cdn_url().$item['image']
                );
            }
        }
		}
        return $resultdata;
    }

    public function get_schools(){
        $resultdata = array();

        // Connect to the main ERP database to fetch schools and branches
        $erp_db = $this->load->database('default', TRUE);

        // Get all active schools with their images and boards (no limit - show all schools)
        $schools_query = $erp_db->query("SELECT s.id, s.slug, s.school_name, s.address, s.school_description, si.image_path, GROUP_CONCAT(DISTINCT sb.board_name ORDER BY sb.board_name SEPARATOR ', ') as boards FROM erp_schools s LEFT JOIN erp_school_images si ON s.id = si.school_id AND si.is_primary = 1 LEFT JOIN erp_school_boards_mapping sbm ON s.id = sbm.school_id LEFT JOIN erp_school_boards sb ON sbm.board_id = sb.id AND sb.status = 'active' WHERE s.status = 'active' GROUP BY s.id ORDER BY s.created_at DESC");

        if (!empty($schools_query)) {
            foreach($schools_query->result_array() as $item){
               $image_path = !empty($item['image_path'])
                ? ltrim($item['image_path'], '/')
                : '';

                $has_branches = $erp_db->query("SELECT id FROM erp_school_branches WHERE school_id = '" . $item['id'] . "'")->num_rows();

                // Add school data
                $resultdata[] = array(
                    "id"                => $item['id'],
                    "slug"              => $item['slug'],
                    "name"              => $item['school_name'],
                    "description"       => $item['school_description'],
                    "address"           => $item['address'],
                    "image"             => $image_path,
                    "boards"            => $item['boards'],
                    "type"              => "school",
                    "has_branches"      => $has_branches
                );
            }
        }

        return $resultdata;
    }

    public function get_school_by_id($id){
        $resultdata = array();
        $query = $this->db->query("SELECT * FROM erp_schools WHERE id = '" . $id . "'");
        if (!empty($query)) {
            $resultdata = $query->row_array();
        }
        return $resultdata;
    }

    public function get_branch_list($id){
        $resultdata = array();
        $query = $this->db->query("SELECT sb.*, GROUP_CONCAT(DISTINCT sbb.board_name ORDER BY sbb.board_name SEPARATOR ', ') as boards FROM erp_school_branches sb LEFT JOIN erp_school_boards_mapping sbm ON sb.school_id = sbm.school_id LEFT JOIN erp_school_boards sbb ON sbm.board_id = sbb.id AND sbb.status = 'active' WHERE sb.school_id = '" . $id . "' GROUP BY sb.id");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = $item;
            }
        }

        return $resultdata;
    }

   public function get_boards_by_school($school_id, $branch_id = NULL){
       $keywords = " school_id = '" . $this->db->escape_str($school_id) . "'";
       // Currently boards are mapped to schools. If branch-specific boards needed, add branch_id to mapping.
       $query = $this->db->query("SELECT sb.* FROM erp_school_boards sb INNER JOIN erp_school_boards_mapping sbm ON sb.id = sbm.board_id WHERE sbm.school_id = '" . $this->db->escape_str($school_id) . "' AND sb.status = 'active' ORDER BY sb.board_name ASC");
       return $query->result_array();
   }

   public function get_classes_by_uniforms($school_id, $branch_id, $board_id){
       $keyword = " school_id = '" . $this->db->escape_str($school_id) . "'";
       if ($branch_id) {
           $keyword .= " AND branch_id = '" . $this->db->escape_str($branch_id) . "'";
       }
       if ($board_id) {
           $keyword .= " AND board_id = '" . $this->db->escape_str($board_id) . "'";
       }

       $query = $this->db->query("SELECT class_id FROM erp_uniforms WHERE $keyword GROUP BY class_id");
       $class_ids = [];
       foreach ($query->result_array() as $row) {
           if ($row['class_id']) {
               $ids = explode(',', $row['class_id']);
               foreach ($ids as $id) {
                   if ($id && !in_array($id, $class_ids)) {
                       $class_ids[] = $id;
                   }
               }
           }
       }

       if (empty($class_ids)) return [];

       $query = $this->db->query("SELECT * FROM classes WHERE id IN (" . implode(',', $class_ids) . ") ORDER BY class_name ASC");
       return $query->result_array();
   }

    public function get_colors_by_uniforms($school_id, $branch_id, $board_id, $class_id){
        $keyword = " school_id = '" . $this->db->escape_str($school_id) . "'";
        if ($branch_id) {
            $keyword .= " AND branch_id = '" . $this->db->escape_str($branch_id) . "'";
        }
        if ($board_id) {
            $keyword .= " AND board_id = '" . $this->db->escape_str($board_id) . "'";
        }
        if ($class_id) {
            $keyword .= " AND FIND_IN_SET('" . $this->db->escape_str($class_id) . "', class_id)";
        }

        $query = $this->db->query("SELECT color FROM erp_uniforms WHERE $keyword AND color != '' AND color IS NOT NULL GROUP BY color ORDER BY color ASC");
        return $query->result_array();
    }

    public function uniform_type_list($type, $slug, $board_id = '', $class_id = ''){
        $result = array();

        $keywords = "";
        $school_id = "";
        $branch_id = "";
        
        if($type == 'school') {
            $school_id_query = $this->db->query("SELECT id FROM erp_schools WHERE slug = '" . $slug . "'");
           if($school_id_query->num_rows() > 0) {
              $school_id = $school_id_query->row_array()['id'];
              $keywords = " school_id = '" . $school_id . "'";
           } 
        } else if($type == 'branch') {
           $branch_id_query = $this->db->query("SELECT id, school_id FROM erp_school_branches WHERE slug = '" . $slug . "'");
           if($branch_id_query->num_rows() > 0) {
              $row = $branch_id_query->row_array();
              $branch_id = $row['id'];
              $school_id = $row['school_id'];
              $keywords = " branch_id = '" . $branch_id . "'";
           } 
        } 

        if($keywords != "") {
            if ($board_id) {
                $keywords .= " AND board_id = '" . $this->db->escape_str($board_id) . "'";
            }
            if ($class_id) {
                $keywords .= " AND FIND_IN_SET('" . $this->db->escape_str($class_id) . "', class_id)";
            }

            $query = $this->db->query("SELECT uniform_type_id FROM erp_uniforms WHERE " . $keywords . " GROUP BY uniform_type_id");

            if (!empty($query)) {
                $resultdata = array();

                foreach($query->result_array() as $item){
                    $query = $this->db->query("SELECT id, name FROM erp_uniform_types WHERE id = '" . $item['uniform_type_id'] . "'");
                    if($query->num_rows() > 0) {
                        $resultdata[] = $query->row_array();
                    }
                }

                // Get payment requirement and deliver at school status - branch takes precedence over school
                $is_payment_required = 1; // Default to required (show price)
                $deliver_at_school = 1; // Default to address required
                if ($branch_id) {
                    $query = $this->db->query("SELECT COALESCE(is_payment_required, 1) as is_payment_required, COALESCE(deliver_at_school, 1) as deliver_at_school FROM erp_school_branches WHERE id = '" . $branch_id . "'");
                    if($query->num_rows() > 0) {
                        $row = $query->row_array();
                        $is_payment_required = (int)$row['is_payment_required'];
                        $deliver_at_school = (int)$row['deliver_at_school'];
                    }
                } elseif ($school_id) {
                    $query = $this->db->query("SELECT COALESCE(is_payment_required, 1) as is_payment_required, COALESCE(deliver_at_school, 1) as deliver_at_school FROM erp_schools WHERE id = '" . $school_id . "'");
                    if($query->num_rows() > 0) {
                        $row = $query->row_array();
                        $is_payment_required = (int)$row['is_payment_required'];
                        $deliver_at_school = (int)$row['deliver_at_school'];
                    }
                }

                $result = ["school_id" => $school_id, "branch_id" => $branch_id, "types" => $resultdata, "is_payment_required" => $is_payment_required, "deliver_at_school" => $deliver_at_school];
            }
        } 
        
        return $result;
    }

    public function get_vendor_features(){
        $resultdata = array();
        
        // Connect to the main ERP database to fetch feature details
        $erp_db = $this->load->database('default', TRUE);
        
        // Get enabled vendor features from vendor database (including vendor-uploaded image)
        $query = $this->db->query("SELECT vf.feature_id, vf.feature_slug, vf.feature_name, vf.image as vendor_image FROM vendor_features vf WHERE vf.is_enabled = 1 ORDER BY vf.feature_id ASC LIMIT 10");
        
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                // Get additional details from master erp_features table (for description only, not image)
                $feature_query = $erp_db->query("SELECT id, name, slug, description FROM erp_features WHERE id = ? AND is_active = 1 LIMIT 1", array($item['feature_id']));
                
                $image_path = '';
                $description = '';
                
                // Priority: Use vendor-uploaded image from vendor_features table
                if (!empty($item['vendor_image'])) {
                    $image_path = base_url() . '../uploads/vendor_features/' . $item['vendor_image'];
                }
                
                // Get description from master erp_features table
                if ($feature_query && $feature_query->num_rows() > 0) {
                    $feature_data = $feature_query->row_array();
                    if (!empty($feature_data['description'])) {
                        $description = $feature_data['description'];
                    }
                }
                
                $resultdata[] = array(
                    "id"                => $item['feature_id'],
                    "name"              => $item['feature_name'],
                    "slug"              => $item['feature_slug'],
                    "description"       => $description,
                    "image"             => $image_path,
                    "type"              => "feature"
                );
            }
        }
        
        return $resultdata;
    }

    public function get_new_product_launch(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, new_product_image FROM tbl_new_product_launch WHERE status='1' ORDER BY id DESC LIMIT 10");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                        "id"                    => $item['id'],
                        "new_product_image"     => new_product_image_url().$item['new_product_image'],
                    );
            }
        }

       return $resultdata;
    }

    public function get_milestones(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, image FROM tbl_milestones WHERE is_active='1' ORDER BY id DESC");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                        "id"               => $item['id'],
                        "image"     => milestones_image_url().$item['image'],
                    );
            }
        }

       return $resultdata;
    }

    public function get_inspirations(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, name, department, description, image FROM tbl_inspirations WHERE status='1' ORDER BY id DESC");
        if (!empty($query)) {
            $i=1;
            $class = '';
            foreach($query->result_array() as $item){
                if($i % 2 == 0){
                    $class = 'bg1';
                }
                else{
                    $class = 'bg2';
                }
                $resultdata[] = array(
                        "id"          => $item['id'],
                        "name"        => $item['name'],
                        "department"  => $item['department'],
                        "description" => $item['description'],
                        "class" => $class,
                        "image"       => inspirations_image_url().$item['image'],
                    );
                $i++;
            }
        }

       return $resultdata;
    }



    public function get_yt_testimonial(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, link FROM oc_yt_testimonial  order by id DESC LIMIT 4");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"                    => $item['id'],
                    "link"                    => $item['link'],
                );
            }
        }

       return $resultdata;
    }

    public function get_yt_testimonial_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('oc_yt_testimonial');
    }
    public function get_yt_patient_testimonial_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('patients_yt_testimonial');
    }

    public function get_press_release_list_count(){

        $query = $this->db->query("SELECT id FROM oc_press_release")->num_rows();
        return $query;
    }

    public function get_press_release_list($per_page, $offset){
        $resultdata     = array();

        $query = $this->db->query("SELECT * FROM oc_press_release ORDER BY id DESC LIMIT $offset,$per_page");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"                    => $item['id'],
                    "image"      	       =>  press_release_image_url().$item['image'],
                    "title"     		 	=> $item['title'],
                );
            }
        }

       return $resultdata;
    }

	public function get_in_news_list_count(){

        $query = $this->db->query("SELECT id FROM oc_in_news")->num_rows();
        return $query;
    }

	public function get_in_news_list($per_page, $offset){
        $resultdata     = array();
        $query = $this->db->query("SELECT id,title,image,link FROM oc_in_news ORDER BY id DESC LIMIT $offset,$per_page");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"        => $item['id'],
                    "image"     => in_news_image_url().$item['image'],
                    "title" 	=> $item['title'],
                    "link"  	=> $item['link'],
                );
            }
        }
        return $resultdata;
    }

	public function get_bulletins_list_count(){

        $query = $this->db->query("SELECT id FROM oc_videos")->num_rows();
        return $query;
    }

	public function get_bulletins_list($per_page, $offset){
        $resultdata     = array();
        $query = $this->db->query("SELECT id,title,video_url FROM oc_videos ORDER BY id DESC LIMIT $offset,$per_page");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"        => $item['id'],
                    "title" 	=> $item['title'],
                    "video_url" => $item['video_url'],
                );
            }
        }
        return $resultdata;
    }

    public function get_album(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id,slug, name, cover_image, created_at FROM tbl_album ORDER BY id DESC");
        if (!empty($query)) {
            $i=1;
            $class = '';
            foreach($query->result_array() as $item){
                if($item['cover_image']!='' && $item['cover_image']!=NULL){
                     $image=album_image_url().$item['cover_image'];
                }
                else{
                    $image=cdn_url().'assets/images/default-img.jpg';
                }

                $resultdata[] = array(
                        "id"          => $item['id'],
                        "name"        => $item['name'],
                        "slug"        => $item['slug'],
                        "image"       => $image,
                    );
                $i++;
            }
        }
       return $resultdata;
    }


    public function get_album_camp_type_count($id){

        $query = $this->db->query("SELECT a.id, a.name, b.alb_image FROM tbl_album as a INNER JOIN tbl_alb_image_gallery as b ON a.id = b.parent_id WHERE a.slug='$id'")->num_rows();
        return $query;
    }

    public function get_album_camp_type($per_page, $offset, $id){
        $resultdata     = array();

        $query = $this->db->query("SELECT a.id, a.name, b.alb_image FROM tbl_album as a INNER JOIN tbl_alb_image_gallery as b ON a.id = b.parent_id  WHERE a.slug='$id'  order by parent_id desc LIMIT $offset,$per_page");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                if($item['alb_image']!='' && $item['alb_image']!=NULL){
                      $image=alb_image_url().$item['alb_image'];
                }
                else{
                   $image=cdn_url().'assets/images/default-img.jpg';
                }

                $resultdata[] = array(
                    "id"          => $item['id'],
                    "name"        => $item['name'],
                    "image"       => $image,
                );
            }
        }

       return $resultdata;
    }

    public function get_videos_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('oc_video');
    }

    public function get_videos_list_count(){

        $query = $this->db->query("SELECT id FROM oc_video")->num_rows();
        return $query;
    }

    public function get_videos_list($per_page, $offset){
        $resultdata     = array();

        $query = $this->db->query("SELECT * FROM oc_video ORDER BY id desc LIMIT $offset,$per_page");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"                    => $item['id'],
                    "video"     		 	=> $item['video'],
                );
            }
        }

       return $resultdata;
    }





    public function update_stock($order_id){
        /*date_default_timezone_set('Asia/Kolkata');
        $sql = $this->db->query("SELECT id FROM tbl_order_details WHERE id='$order_id' LIMIT 1");
        if ($sql->num_rows()>0) {
           $this->update_invoice_number($order_id);
        }
        $this->generate_invoice($order_id);
        return true;*/
		 return true;
    }


   public function user_invoice_manager($type,$last_invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id){
		 $check_inv_exist = $this->db->query("SELECT user_invoice FROM order_user_invoice WHERE type='$type' AND order_id='$order_id' LIMIT 1");
        if ($check_inv_exist->num_rows() > 0) {
            $row_ = $check_inv_exist->row();
            return $invoice_number = $row_->user_invoice;
        } else {
            $invoice_id_ini  = (int) $last_invoice_id;
            $invoice_id      = $invoice_id_ini + 1;
            $vendor_type     = $vendor_type;
            $new_invoice_id  = $vendor_type . sprintf('%05d', $invoice_id);

			//sql query to check new_invoice_id is already exist in table or not
            $count = $this->db->query("SELECT user_invoice FROM order_user_invoice WHERE type='$type' AND invoice_id='$invoice_id' LIMIT 1")->num_rows();

            if ($count > 0) {
                // if new_invoice_id already exists
                $this->user_invoice_manager($type,$invoice_id, $user_invoice_date, $pre_year, $vendor_id, $vendor_type, $order_id);
            } else {

                $data=array();
				$data['type']          = $type;
				$data['order_id']      = $order_id;
				$data['vendor_id']     = $vendor_id;
				$data['year']		   = $pre_year;
				$data['user_invoice']  = $new_invoice_id;
				$data['invoice_id']    = $invoice_id;
				$data['invoice_date']  = $user_invoice_date;

                $this->common_model->insert($data, 'order_user_invoice');
                return $new_invoice_id;
            }
        }
    }

	 public function get_order_details_by_id($id){
		$query= $this->db->query("SELECT id,user_name,payment_method,user_email,user_phone,order_unique_id,invoice_no,payable_amt,order_date FROM tbl_order_details WHERE id='$id' LIMIT 1");
        return $query->row();
    }


	public function get_orders_details_by_id($id) {
        $resultdata = array();

        $query = $this->db->query("SELECT id,delivery_charge,invoice_no,invoice_date,user_name,user_email,user_phone,order_unique_id,order_address,payable_amt,order_date,payment_id,currency_code,currency,user_id,is_mail_sent,discount,discount_amt,is_invoice,freight_gst_per,freight_charges_excl,freight_gst,is_mail_sent FROM `tbl_order_details` WHERE id='$id' AND (payment_status='success' OR payment_status='cod' OR payment_status='payment_at_school' OR payment_method='payment_at_school') LIMIT 1");
        if (!empty($query)) {
            $item = $query->row_array();
            $order_id      = $item['id'];
            $order_address = $item['order_address'];
            $order_date    = date("d M Y | h:i A", strtotime($item['order_date']));
            $invoice_date  = date("d M Y", strtotime($item['invoice_date']));

            $shipping = $this->db->query("SELECT * FROM `tbl_order_address` WHERE order_id='$order_id' LIMIT 1")->row_array();

            $products = array();
            $query_product = $this->db->query("SELECT id,product_id,product_title,product_sku,product_qty,variation_name,product_mrp,product_price,product_gst,total_gst_amt,total_price,discount_amt,hsn,excl_price,excl_price_total FROM tbl_order_items WHERE order_id='$order_id'");
            $gst_total = 0;
            $total_product_discount = 0;
            foreach ($query_product->result_array() as $row) {
                 $excl_price    = price_format_decimal($row['excl_price']);
                $gst_amt_total =  $row['total_gst_amt'];

				$product_qty=$row['product_qty'];
				$product_mrp=$row['product_mrp']*$product_qty;
				$product_price=$row['product_price']*$product_qty;
                $product_discount = price_format_decimal($product_mrp- $product_price);

                $gst_total += $gst_amt_total;
                $total_product_discount += $product_discount;
                $products[] = array(
                    "product_id" => $row['product_id'],
                    "product_title" => $row['product_title'],
                    "product_qty" => $row['product_qty'],
                    "variation_name" => $row['variation_name'],
                    "product_mrp" => $row['product_mrp'],
                    "product_discount" => $product_discount,
                    "product_price" => $row['product_price'],
                    "product_price_total" => $product_price,
                    "price_total" => price_decimal($row['total_price']),
                    "gst" => $row['product_gst'],
                    "gst_amt" => price_format_decimal($row['total_gst_amt']),
                    "excl_price" => price_format_decimal($excl_price),
                    "excl_price_total" => $row['excl_price_total'],
                    "discounted_price" => 0,
                    "discounted_price_total" => 0,
                    "discount" => 0,
                    "total_discount" => price_format_decimal($row['discount_amt']),
                    "hsn" => $row['hsn'],
                );
            }

             if ($item['delivery_charge'] > 0) {
                $excl_price    = $item['freight_charges_excl'];
                $gst_amt_total = $item['freight_gst'];
                $gst_total += $gst_amt_total;

				$products[] = array(
                    "product_id" => 'freight',
                    "product_title" => 'Freight Charges',
                    "product_qty" => '-',
                    "free_quantity" => '-',
                    "product_price" => $item['delivery_charge'],
                    "hsn_code" => FREIGHT_HSN_CODE,
                    "gst" => $item['freight_gst_per'],
                    "excl_price" => $item['freight_charges_excl'],
                    "excl_price_total" => $item['freight_charges_excl'],
                    "price_total" => $item['delivery_charge'],
                    "discount" => '-',
                    "batch_no" => '-',
                    "expiry_date" =>  '-',
                    "batch_qty" => '-',
                    "free_batch_qty" => '-',
                    "gst_amt" => $item['freight_gst']??0
                );
            }

            $dr_gst_no = 'URP';
            $dr_state_code = ($dr_gst_no != 'URP' ? substr($dr_gst_no, 0, 2) : '-');

            $resultdata = array(
                "total_product_discount" => $total_product_discount,
                "gst_total" => $gst_total,
                "price_total" => $item['payable_amt'],
                "dr_gst_no" => $dr_gst_no,
                "dr_state_code" => $dr_state_code,
                "order_id" => $order_id,
                "order_date" => $order_date,
                "user_name" => $item['user_name'],
                "user_email" => $item['user_email'],
                "user_phone" => $item['user_phone'],
                "order_unique_id" => $item['order_unique_id'],
                "payable_amt" => $item['payable_amt'],
                "payment_id" => $item['payment_id'],
                "invoice_no" => $item['invoice_no'],
                "delivery_charge" => $item['delivery_charge'],
                "currency_code" => $item['currency_code'],
                "currency" => $item['currency'],
                "is_mail_sent" => $item['is_mail_sent'],
                "discount" => $item['discount'],
                "discount_amt" => $item['discount_amt'],
                "is_invoice" => $item['is_invoice'],
                "invoice_date" => $invoice_date,
                "products" => $products,
                "shipping" => $shipping,
            );
        }
        return $resultdata;
    }



	public function get_invoice_orders_details_by_id($id) {
        $resultdata = array();

        $query = $this->db->query("SELECT id,payment_method,delivery_charge,invoice_no,invoice_date,user_name,user_email,user_phone,order_unique_id,order_address,payable_amt,order_date,payment_id,currency_code,currency,user_id,is_mail_sent,discount,discount_amt,is_invoice,freight_gst_per,freight_charges_excl,freight_gst,order_status,wallet_amount FROM `tbl_order_details` WHERE id='$id' AND (payment_status='success' OR payment_status='cod' OR payment_status='payment_at_school' OR payment_method='payment_at_school') LIMIT 1");
        if (!empty($query)) {
            $item = $query->row_array();
            $order_id      = $item['id'];
            $order_address = $item['order_address'];
            $order_date    = date("d M Y | h:i A", strtotime($item['order_date']));
            $invoice_date  = date("d M Y", strtotime($item['invoice_date']));

            $shipping = $this->db->query("SELECT * FROM `tbl_order_address` WHERE order_id='$order_id' LIMIT 1")->row_array();

            $products = array();
            $query_product = $this->db->query("SELECT id,product_id,product_title,product_sku,product_qty,variation_name,product_mrp,product_price,product_gst,total_gst_amt,total_price,discount_amt,hsn,excl_price,excl_price_total FROM tbl_order_items WHERE order_id='$order_id'");
            $gst_total = 0;
            $total_product_discount = 0;
            foreach ($query_product->result_array() as $row) {
                $excl_price    = price_format_decimal($row['excl_price']);
                $gst_amt_total =  $row['total_gst_amt'];

				$product_qty=$row['product_qty'];
				$product_mrp=$row['product_mrp']*$product_qty;
				$product_price=$row['product_price']*$product_qty;
                $product_discount = price_format_decimal($product_mrp- $product_price);

                $gst_total += $gst_amt_total;
                $total_product_discount += $product_discount;

			   $products[] = array(
                    "product_id" => $row['product_id'],
                    "product_title" => $row['product_title'],
                    "product_qty" => $row['product_qty'],
                    "variation_name" => $row['variation_name'],
                    "free_quantity" => 0,
                    "product_price" => $row['product_price'],
                    "product_mrp" => $row['product_mrp'],
                    "hsn_code" => $row['hsn'],
                    "gst" => ($row['product_price']>0 ? $row['product_gst']:0),
                    "excl_price" => $row['excl_price'],
                    "excl_price_total" => $row['excl_price_total'],
                    "price_total" => price_decimal($row['total_price']),
                    "discount" => price_decimal($row['discount_amt']),
                    "batch_no" => '',
                    "expiry_date" =>  '-',
                    "batch_qty" => 0,
                    "free_batch_qty" => 0,
                    "gst_amt" => $gst_amt_total,
                );
            }

            if ($item['delivery_charge'] > 0) {
                $excl_price    = $item['freight_charges_excl'];
                $gst_amt_total = $item['freight_gst'];
                $gst_total += $gst_amt_total;

				$products[] = array(
                    "product_id" => 'freight',
                    "product_title" => 'Freight Charges',
                    "product_qty" => '-',
                    "free_quantity" => '-',
                    "product_price" => $item['delivery_charge'],
                    "hsn_code" => FREIGHT_HSN_CODE,
                    "gst" => $item['freight_gst_per'],
                    "excl_price" => $item['freight_charges_excl'],
                    "excl_price_total" => $item['freight_charges_excl'],
                    "price_total" => $item['price_total'],
                    "discount" => '-',
                    "batch_no" => '-',
                    "expiry_date" =>  '-',
                    "batch_qty" => '-',
                    "free_batch_qty" => '-',
                    "gst_amt" => $item['freight_gst']??0
                );
            }

            $dr_gst_no = 'URP';
            $dr_state_code = ($dr_gst_no != 'URP' ? substr($dr_gst_no, 0, 2) : '-');

            $resultdata = array(
                "total_product_discount" => $total_product_discount,
                "gst_total" => $gst_total,
                "price_total" => price_decimal($item['payable_amt']+$item['wallet_amount']),
                "payment_method" => $item['payment_method'],
                "dr_gst_no" => $dr_gst_no,
                "dr_state_code" => $dr_state_code,
                "order_id" => $order_id,
                "order_date" => $order_date,
                "user_name" => $item['user_name'],
                "user_email" => $item['user_email'],
                "user_phone" => $item['user_phone'],
                "order_unique_id" => $item['order_unique_id'],
                "payable_amt" =>  price_decimal($item['payable_amt']+$item['wallet_amount']),
                "payment_id" => $item['payment_id'],
                "invoice_no" => $item['invoice_no'],
                "delivery_charge" => $item['delivery_charge'],
                "currency_code" => $item['currency_code'],
                "currency" => $item['currency'],
                "is_mail_sent" => $item['is_mail_sent'],
                "discount" => $item['discount'],
                "discount_amt" => $item['discount_amt'],
                "is_invoice" => $item['is_invoice'],
                "order_status" => $item['order_status'],
                "invoice_date" => $invoice_date,
                "products" => $products,
                "inv_products" => $products,
                "shipping" => $shipping,
            );
        }
        return $resultdata;
    }


    public function generate_invoice($order_id){
       	$receipt_no=sprintf('%05d',$order_id);
        $order=$this->get_orders_details_by_id($order_id);
        $this->load->library('pdf');
        $this->load->library('zip');

        $page_data['data'] = $order;
		if($order['payment_method']=='cod'){
          $html_content=$this->load->view('invoice/cod_invoice_bill', $page_data, TRUE);
		}
		else{
         $html_content=$this->load->view('invoice/invoice_bill', $page_data, TRUE);
		}

		$this->pdf->set_paper("A4", "portrait");
        $this->pdf->set_option('isHtml5ParserEnabled', TRUE);
        $this->pdf->load_html($html_content);
    	$this->pdf->render();
        $pdfname = 'invoice_'.$receipt_no.'.pdf';
	  /*  $this->pdf->stream("welcome.pdf", array("Attachment"=>0));
	    exit();*/
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $directory = "uploads/invoice/"."$year/$month/$day/";

        //If the directory doesn't already exists.
        if(!is_dir($directory)){ mkdir($directory, 0755, true);}

        $output = $this->pdf->output();
        $file_url=$directory.$pdfname;
        if(file_put_contents($file_url, $output)){

	   $data = array();
	   $data = array(
			'invoice_url' => $file_url,
		);
       $this->common_model->update($data,$order_id,'tbl_order_details');
      }
     return true;
    }

	public function get_blogs_list() {
		$resultdata = array();
		$query = $this->db->query("SELECT id, slug, name, image, wp_url, date, shrt_desc FROM blogs ORDER BY id DESC LIMIT 5");

		foreach ($query->result_array() as $item) {
			$formattedDate = (new DateTime($item['date']))->format('d M Y');
			$resultdata[] = [
				"id"         => $item['id'],
				"slug"       => $item['slug'],
				"name"       => $item['name'],
				"wp_url"       => $item['wp_url'],
				"shrt_desc"  => $item['shrt_desc'],
				"image"      => cdn_url() . $item['image'] . '?tr=w-300',
				"date"       => $formattedDate,
			];
		}

		return $resultdata;
	}


    public function get_blogs_details_by_slug($slug){
        $resultdata = array();
        $query = $this->db->query("SELECT id,slug,title,image,description,date,meta_title,meta_description,meta_keyword FROM oc_blog WHERE slug='$slug' LIMIT 1");

        $item = $query->row_array();

        $resultdata = array(
            "id"                => $item['id'],
            "slug"              => $item['slug'],
            "title"             => $item['title'],
            "image"             =>  blog_image_url().$item['image'],
            "date"              => $item['date'],
            "description"       => $item['description'],
            "meta_image"        =>  blog_image_url().$item['image'],
            "meta_title"        => $item['meta_title'],
            "meta_description"  => $item['meta_description'],
            "meta_keyword"      => $item['meta_keyword'],
        );

        return $resultdata;
    }

    public function get_related_articles($id){
        $resultdata = array();
        $query = $this->db->query("SELECT id,slug,title,image FROM oc_blog WHERE id!='$id' ORDER BY id DESC");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"    => $item['id'],
                    "slug"  => $item['slug'],
                    "title" => $item['title'],
                    "image" =>  blog_image_url().$item['image'],
                );
            }
        }
        return $resultdata;
    }

	public function get_uniforms_details($slug){
        $resultdata     = array();

		$query = $this->db->query("
			SELECT p.isbn as psku, '0' as is_stock, p.product_description, p.gender, p.isbn as sku, '999' as stock, '0' as out_of_stock, p.id, p.size_chart_id as size_chart,
				   p.product_description as short_description, p.manufacturer_details, p.packer_details, p.customer_details, pvar.size_id as code, pvar.id AS variation_id, '0' as disc_per,
				   pvar.mrp as product_mrp, pvar.selling_price, p.product_name as name,
				   p.meta_title, p.meta_description, p.meta_keywords as meta_keyword, p.school_id, p.branch_id, p.class_id,
				   p.material_id, p.color, p.board_id, p.min_quantity, p.days_to_exchange,
				   p.gst_percentage, p.hsn, p.product_origin, p.isbn,
				   p.uniform_type_id
			FROM erp_uniforms as p
			INNER JOIN erp_uniform_size_prices as pvar ON p.id = pvar.uniform_id
			WHERE p.slug = '$slug' AND p.status = 'active'
			ORDER BY pvar.id ASC
			LIMIT 1
		");

           
        $preloader=base_url().'assets/images/spinner.gif'; 
        if ($query->num_rows()>0) {
            $item=$query->row_array();
            $sku=$item['psku'];

            $id=$item['id'];
			$product_id=$item['id'];
            $variation_id=$item['variation_id'];
			
            $is_stock=$item['is_stock'];
            $out_of_stock=$item['out_of_stock'];
			
            $category_id=isset($item['category_id']) ? $item['category_id'] : null;

            $tags = $item['gender'];
            
            // $color_id = $item['color_id'];
            
            $stock = 1;
			
			if ($item['is_stock'] == '1') {
				$stock = 0;
			} else {
				$stock = ($item['out_of_stock'] == '1') ? 0 : 1;
			} 
			
            if(isset($item['is_variation']) && $item['is_variation']=='1') {
				$sql  = $this->db->query("SELECT id, image_path as image FROM erp_uniform_images WHERE uniform_id='$id' AND variation_id='$variation_id' ORDER BY sort ASC");
            }
            else{
				$sql  = $this->db->query("SELECT id, image_path as image FROM erp_uniform_images WHERE uniform_id='$id'");
            }
            //echo $this->db->last_query();exit();
            $size_chart = $item['size_chart'];
            if($size_chart!='' && $size_chart!=null){
                $size_chart = image_url().$size_chart;
            }else{
                $size_chart = '';
            }

			$product_images = array();	
            $p_image = '';
            if($sql->num_rows()>0){   
				foreach ($sql->result_array() as $gimg) {
					if($gimg['image'] !=''){
						$p_image = 'http://localhost/erp_books_live/assets/uploads/' . $gimg['image'];
					}else{
						$p_image = cdn_url().'assets/images/default_img.jpg';
					}
					$product_images[] = array(	
						"id" 	=> $gimg['id'],
						"image" => $p_image,
					);
				}
            } else{
				$sql  = $this->db->query("SELECT id, image_path as image FROM erp_uniform_images WHERE uniform_id='$id' ORDER BY is_main DESC");
				if($sql->num_rows()>0){
					foreach ($sql->result_array() as $gimg) {
						if($gimg['image'] !=''){
							$p_image = 'http://localhost/erp_books_live/assets/uploads/' . $gimg['image'];
						}else{
							$p_image = cdn_url().'assets/images/default_img.jpg';
						}
						$product_images[] = array(	
							"id" 	=> $gimg['id'],
							"image" => $p_image,
						);
					}
				}
				else{
					$p_image = cdn_url().'assets/images/default_img.jpg';
					$product_images[] = array(	
						"id" 	=> '',
						"image" => $p_image,
					);
				}
            }

            $variation_combinations = array();	
   
            $sql3  = $this->db->query("SELECT * FROM product_variations WHERE product_id='$id'");
            $variations = array();	
            if($sql3->num_rows()>0){   
				$variations=$sql3->result_array();
            }  

            // $query_faq   = $this->db->query("SELECT name,value FROM product_faq WHERE product_id='$id'");
            // $product_faq = $query_faq->result_array();
            $product_faq = [];
            // $query_reels = $this->db->query("SELECT video,id FROM product_reels WHERE product_id='$id'");
            // $reels  = $query_reels->result_array();
            $reels  = [];

            $fst_variation= array();
            if(isset($item['is_variation']) && $item['is_variation']=='1') {
                $variation_code=explode("/", $item['code']);
                $lstKey = array_key_last($variation_code);
                foreach (array_filter($variation_code) as $key_option =>  $combination) {
                    $attribute_id           = explode(":", $combination)[0];
                    $attribute_value_id     = explode(":", $combination)[1];
    	            $fst_variation[]= array(
    	                "id"       => $attribute_id.'_'.$attribute_value_id,
    	                "attribute_id"       => $attribute_id,
    	                "attribute_value_id" => $attribute_value_id,
    	            );
                }
            }

			//base_url().$item['thumbnail_img']
			$default_size_id=0;
			$selection_array = array();	

			$count_size  = $this->db->query("SELECT id FROM erp_uniform_size_prices WHERE uniform_id='$product_id' limit 1")->num_rows();
			
			if ($count_size > 0) {
				$vsql  = $this->db->query("SELECT size_id as size,selling_price,'0' as out_of_stock FROM erp_uniform_size_prices WHERE uniform_id='$product_id' order by id ASC;");
				$v_array = array();
				if ($vsql->num_rows() > 0) {
					foreach ($vsql->result_array() as $vrow) {
						$v_size_id = $vrow['size'];
						$v_name = $this->common_model->getNameById('erp_sizes', 'name', $v_size_id);
						$v_array[] = array(
							"id" => $v_size_id,
							"name" => $v_name,
							"selling_price" => $vrow['selling_price'],
							"out_of_stock" => $vrow['out_of_stock'],
							"values" => array()
						);
						
						if($default_size_id==0){
							$default_size_id = $v_array[0]['id'];
						}
					}
				}
				
				$selection_array[] = array(
					"id" => 2,
					"name" => "Pack Of",
					"values" => $v_array
				);
			}

            $rowid=0;
            $flag = FALSE;
            $dataTmp = $this->cart->contents();
            $product_id = $item['id'];
            
            foreach ($dataTmp as $cart) {
                if ($cart['product_id'] == $item['id']) {
                    $flag  = TRUE;
                    $rowid = $cart['rowid']; 
                    break;
                }
            }
             
            if ($flag) {
                $is_added = 1;
                $quantity = $cart['qty'];
                $rowid = $rowid;
            } else {
                $is_added = 0;
                $quantity = 0;
                $rowid = 0;
            }

			$delivery_on = date('d M', strtotime('now +2 days'));
			$delivery_txt='';

            $is_wishlist = 0;
            // if($this->session->userdata('user_login') == 1){
            //     $user_id = $this->session->userdata('user_id');
            //     $wishlist = $this->db->where('product_id', $item['id'])->where('user_id', $user_id)->get('tbl_wishlist');

            //     if($wishlist->num_rows() > 0){
            //         $is_wishlist = 1;
            //     }
            // }

            $fst_image = $product_images[0]['image'];
			if($fst_image==''){ 
                $fst_image=cdn_url().'assets/images/default_img.jpg';
            }

		    $resultdata = array(
                "id"                    => $item['id'],
                "category_id"           => isset($item['category_id']) ? $item['category_id'] : null,
                "category_name"         => isset($item['category_id']) ? $this->common_model->get_category_name($item['category_id']) : '',
                "selection_array"       => $selection_array,
                "default_size_id"       => $default_size_id,
                "name"                  => $item['name'],
                "is_variation"          => isset($item['is_variation']) ? $item['is_variation'] : '0',
                //"group_id"            => $item['group_id'],
                //"group_data"          => $group_data,
                "meta_title"            => $item['meta_title'],
                "meta_description"      => $item['meta_description'],
                "meta_keyword"          => $item['meta_keyword'],
                "product_mrp"           => $item['product_mrp'],
                //"selling_price"       => $selling_price,
                "selling_price"         => $item['selling_price'],
                "product_desc"          => isset($item['description']) ? $item['description'] : '',
                "inv_type"              => '',
                "min_qty"               => 1,
                "short_desc"            => $item['short_description'],
                "manufacturer_details"  => $item['manufacturer_details'],
                "packer_details"        => $item['packer_details'],
                "customer_details"      => $item['customer_details'],
                "product_faq"           => $product_faq,
                // "product_info"       => $product_info,
                "tags"      => $tags,
                "preloader"      => $preloader,
                "image"          => '',
                "product_images" => $product_images,
                "variation_combinations" => $variation_combinations,
                "variations" => $variations,
                "fst_variation" => $fst_variation,
                "disc_per" => discount_per($item['product_mrp'], $item['selling_price']),
                "size_chart" => $size_chart,
                "sku" => $item['psku'],
                "stock" => $stock,
                "delivery_txt" => $delivery_txt,
                "p_image" => $p_image,
                "reels" => $reels,
                "is_added" => $is_added,
                "quantity" => $quantity,
                "rowid" => $rowid,
                "is_wishlist" => $is_wishlist,
                "fst_image" => $fst_image,
                "is_stock" => $is_stock,
                "school_id" => $item['school_id'],
                "branch_id" => $item['branch_id'],
                "class_id" => isset($item['class_id']) ? $item['class_id'] : '',
                "material_id" => isset($item['material_id']) ? $item['material_id'] : '',
                "color" => isset($item['color']) ? $item['color'] : '',
                "board_id" => isset($item['board_id']) ? $item['board_id'] : '',
                "min_quantity" => isset($item['min_quantity']) ? $item['min_quantity'] : '',
                "days_to_exchange" => isset($item['days_to_exchange']) ? $item['days_to_exchange'] : '',
                "gst_percentage" => isset($item['gst_percentage']) ? $item['gst_percentage'] : '',
                "hsn" => isset($item['hsn']) ? $item['hsn'] : '',
                "product_origin" => isset($item['product_origin']) ? $item['product_origin'] : '',
                "isbn" => isset($item['isbn']) ? $item['isbn'] : '',
                "uniform_type_id" => isset($item['uniform_type_id']) ? $item['uniform_type_id'] : '',
            );

            // Determine payment requirement and deliver at school - branch takes precedence over school
            $is_payment_required = 1; // Default to required
            $deliver_at_school = 1; // Default to address required
            if ($item['branch_id']) {
                $query = $this->db->query("SELECT is_payment_required, COALESCE(deliver_at_school, 1) as deliver_at_school FROM erp_school_branches WHERE id = '" . $item['branch_id'] . "'");
                if($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $is_payment_required = $row['is_payment_required'];
                    $deliver_at_school = $row['deliver_at_school'];
                }
            } elseif ($item['school_id']) {
                $query = $this->db->query("SELECT is_payment_required, COALESCE(deliver_at_school, 1) as deliver_at_school FROM erp_schools WHERE id = '" . $item['school_id'] . "'");
                if($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $is_payment_required = $row['is_payment_required'];
                    $deliver_at_school = $row['deliver_at_school'];
                }
            }
            $resultdata["is_payment_required"] = $is_payment_required;
            $resultdata["deliver_at_school"] = $deliver_at_school;


        }
        return $resultdata;
    } 

	public function get_product_details($slug){
        $resultdata     = array();

        //$query = $this->db->query("SELECT p.sku as psku,p.is_stock,p.description,p.composition,p.indication,p.tags,p.alt_tag,p.head_code,p.gcr_code,p.contains,p.product_does_and_dir,p.category_id,pvar.sku,pvar.stock,pvar.out_of_stock,p.id,p.size_chart,p.short_description,pvar.code,pvar.id AS variation_id,pvar.disc_per,pvar.product_mrp,pvar.selling_price,pvar.name as var_name,p.name,p.is_variation,p.meta_title,p.meta_description,p.meta_keyword  FROM products as p INNER JOIN product_variations as pvar ON p.id=pvar.product_id WHERE p.slug='$slug' AND p.status='1' ORDER BY pvar.id ASC LIMIT 1");
		
		$query = $this->db->query("
			SELECT p.sku as psku, p.is_stock, p.description, p.composition, p.indication, p.tags,
				   p.alt_tag, p.head_code, p.gcr_code, p.contains, p.product_does_and_dir,
				   pvar.sku, pvar.stock, pvar.out_of_stock, p.id, p.size_chart,
				   p.short_description, pvar.code, pvar.id AS variation_id, pvar.disc_per,
				   pvar.product_mrp, pvar.selling_price, pvar.name as var_name, p.name,
				   p.meta_title, p.meta_description, p.meta_keyword
			FROM products as p
			INNER JOIN product_variations as pvar ON p.id = pvar.product_id
			WHERE p.slug = '$slug' AND p.status = '1'
			ORDER BY 
				(CASE WHEN p.is_stock = 0 AND pvar.out_of_stock = 0 THEN 0 ELSE 1 END),
				pvar.id ASC
			LIMIT 1
		");

           
        $preloader=base_url().'assets/images/spinner.gif'; 
        if ($query->num_rows()>0) {
            $item=$query->row_array();
            $sku=$item['psku'];

            $id=$item['id'];
			$product_id=$item['id'];
            $variation_id=$item['variation_id'];
			
            $is_stock=$item['is_stock'];
            $out_of_stock=$item['out_of_stock'];
			
			
			
            $category_id=$item['category_id'];

            $contains = $item['contains'];
            $contains_arr = array();
            if($contains != '' &&  $contains != NULL){
                $contains =  explode(',', $contains);
                foreach($contains as $i => $contain){
                    $single_contain = $this->db->where('id', $contain)->get('contains');
                    if($single_contain->num_rows() > 0){
                        $contains_arr[] = $single_contain->row_array();
                    } 
                }
            }

            $tags = $item['tags'];
            if($tags != '' &&  $tags != NULL){
                $tags =  explode(',', $tags);
                $tag_name = '';
                foreach($tags as $i => $tag){
                    $single = $this->db->where('id', $tag)->get('tags')->row_array();
                    if($i == 0){
                        $tag_name = $single['name'];
                    } else {
                        $tag_name .= ', ' . $single['name'];
                    }
                }
                $tags = $tag_name;
            } else {
                $tags = '';
            }
            // $color_id = $item['color_id'];
            // $contains = $item['contains'];
            
            $stock = 1;
			
			if ($item['is_stock'] == '1') {
				$stock = 0;
			} else {
				$stock = ($item['out_of_stock'] == '1') ? 0 : 1;
			} 
			
            if($item['is_variation']=='1') {
				$sql  = $this->db->query("SELECT id, image FROM product_images WHERE product_id='$id' AND variation_id='$variation_id' ORDER BY sort ASC");
            }
            else{
				$sql  = $this->db->query("SELECT id, image FROM product_images WHERE product_id='$id'");
            }
            //echo $this->db->last_query();exit();
            $size_chart = $item['size_chart'];
            if($size_chart!='' && $size_chart!=null){
                $size_chart = image_url().$size_chart;
            }else{
                $size_chart = '';
            }

            $featured_images = array();	
            $featured  = $this->db->query("SELECT id, image FROM product_featured WHERE product_id='$id' ORDER BY sort ASC");

            if($featured->num_rows() > 0){
                $featured_images = $featured->result_array();
            }

			$product_images = array();	
            $p_image = '';
            if($sql->num_rows()>0){   
				foreach ($sql->result_array() as $gimg) {
					if($gimg['image'] !=''){
						$p_image = cdn_url().$gimg['image'];
					}else{
						$p_image = cdn_url().'assets/images/default_img.jpg';
					}
					$product_images[] = array(	
						"id" 	=> $gimg['id'],
						"image" => $p_image,
					);
				}
            } else{
				$sql  = $this->db->query("SELECT id, image FROM product_images WHERE product_id='$id' ORDER BY is_main DESC");
				if($sql->num_rows()>0){
					foreach ($sql->result_array() as $gimg) {
						if($gimg['image'] !=''){
							$p_image = cdn_url().$gimg['image'];
						}else{
							$p_image = cdn_url().'assets/images/default_img.jpg';
						}
						$product_images[] = array(	
							"id" 	=> $gimg['id'],
							"image" => $p_image,
						);
					}
				}
				else{
					$p_image = cdn_url().'assets/images/default_img.jpg';
					$product_images[] = array(	
						"id" 	=> '',
						"image" => $p_image,
					);
				}
            }

            $variation_combinations = array();	
   
            $sql3  = $this->db->query("SELECT * FROM product_variations WHERE product_id='$id'");
            $variations = array();	
            if($sql3->num_rows()>0){   
				$variations=$sql3->result_array();
            }  

            $query_faq   = $this->db->query("SELECT name,value FROM product_faq WHERE product_id='$id'");
            $product_faq = $query_faq->result_array();
            $query_reels = $this->db->query("SELECT video,id FROM product_reels WHERE product_id='$id'");
            $reels  = $query_reels->result_array();

            $fst_variation= array();
            if($item['is_variation']=='1') {
                $variation_code=explode("/", $item['code']);
                $lstKey = array_key_last($variation_code);
                foreach (array_filter($variation_code) as $key_option =>  $combination) {
                    $attribute_id           = explode(":", $combination)[0];
                    $attribute_value_id     = explode(":", $combination)[1];
    	            $fst_variation[]= array(
    	                "id"       => $attribute_id.'_'.$attribute_value_id,
    	                "attribute_id"       => $attribute_id,
    	                "attribute_value_id" => $attribute_value_id,
    	            );
                }
            }

			//base_url().$item['thumbnail_img']
			$default_size_id=0;
			$selection_array = array();	

			$count_size  = $this->db->query("SELECT id FROM product_variations WHERE product_id='$product_id' and size IS NOT NULL limit 1")->num_rows();
			$count_color  = $this->db->query("SELECT id FROM product_variations WHERE product_id='$product_id' and color IS NOT NULL limit 1")->num_rows();

			if ($count_size > 0) {
				$vsql  = $this->db->query("SELECT size,selling_price,out_of_stock FROM product_variations WHERE product_id='$product_id' GROUP By size order by id ASC;");
				$v_array = array();
				if ($vsql->num_rows() > 0) {
					foreach ($vsql->result_array() as $vrow) {
						$v_size_id = $vrow['size'];
						$v_name = $this->common_model->getNameById('oc_attribute_values', 'name', $v_size_id);
						$v_array[] = array(
							"id" => $v_size_id,
							"name" => $v_name,
							"selling_price" => $vrow['selling_price'],
							"out_of_stock" => $vrow['out_of_stock'],
							"values" => array()
						);
						
						if($default_size_id==0){
							$default_size_id = $v_array[0]['id'];
						}
					}
				}
				
				$selection_array[] = array(
					"id" => 2,
					"name" => "Pack Of",
					"values" => $v_array
				);
			}

			if ($count_color>0) {
				$vtsql  = $this->db->query("SELECT color,selling_price FROM product_variations WHERE product_id='$product_id' GROUP By color order by id ASC");
				$v_carray = array();
				if ($vtsql->num_rows() > 0) {
					foreach ($vtsql->result_array() as $vtrow) {
						$v_color_id = $vtrow['color'];
						$vrow       = $this->db->query("SELECT name,color_type,color_code,color_image FROM oc_attribute_values WHERE id='$v_color_id' limit 1")->row_array();
						$v_carray[] = array(
							"id" => $v_color_id,
							"name" => $vrow['name'],
							"selling_price" => $vtrow['selling_price'],
							"color_type" => $vrow['color_type'],
							"color_code" => $vrow['color_code'],
							"color_image" => image_url().$vrow['color_image'],
						); 
						
						if($default_size_id==0){
							$default_size_id = $v_carray[0]['id'];
						}
					}
				}

				$selection_array[] = array(
					"id" => 1,
					"name" => "Color",
					"values" => $v_carray
				);
			}


            $rowid=0;
            $flag = FALSE;
            $dataTmp = $this->cart->contents();
            $product_id = $item['id'];
            
            foreach ($dataTmp as $cart) {
                if ($cart['product_id'] == $item['id'] && $cart['variation_name'] == $item['var_name']) {
                    $flag  = TRUE;
                    $rowid = $cart['rowid']; 
                    break;
                }
            }
             
            if ($flag) {
                $is_added = 1;
                $quantity = $cart['qty'];
                $rowid = $rowid;
            } else {
                $is_added = 0;
                $quantity = 0;
                $rowid = 0;
            }

			$delivery_on = date('d M', strtotime('now +2 days'));
			$delivery_txt='';

            $is_wishlist = 0;
            if($this->session->userdata('user_login') == 1){
                $user_id = $this->session->userdata('user_id');
                $wishlist = $this->db->where('product_id', $item['id'])->where('user_id', $user_id)->get('tbl_wishlist');

                if($wishlist->num_rows() > 0){
                    $is_wishlist = 1;
                }
            }

            $fst_image = $product_images[0]['image'];
			if($fst_image==''){ 
                $fst_image=cdn_url().'assets/images/default_img.jpg';
            }

		    $resultdata = array(
                "id"               => $item['id'],
                "category_id"      => $item['category_id'],
                "category_name"    => $this->common_model->get_category_name($item['category_id']),
                "selection_array"  => $selection_array,
                "default_size_id"  => $default_size_id,
                "name"             => $item['name'],
                "is_variation"     => $item['is_variation'],
                //"group_id"      => $item['group_id'],
                //"group_data"    => $group_data,
                "meta_title"       => $item['meta_title'],
                "meta_description" => $item['meta_description'],
                "meta_keyword"     => $item['meta_keyword'],
                "product_mrp"      => $item['product_mrp'],
                //"selling_price"  => $selling_price,
                "selling_price"    => $item['selling_price'],
                "product_does_and_dir"    => $item['product_does_and_dir'],
                "product_desc"    => $item['description'],
                "inv_type"        => '',
                "min_qty"         => 1,
                "short_desc"      =>  $item['short_description'],
                "composition"     =>  $item['composition'],
                "indication"      =>  $item['indication'],
                "product_faq"     => $product_faq,
                "contains"        => $contains_arr,
                //"product_info"   => $product_info,
                "tags"      => $tags,
                "preloader"      => $preloader,
                "image"          => '',
                "product_images" => $product_images,
                "featured_images" => $featured_images,
                "variation_combinations" => $variation_combinations,
                "variations" => $variations,
                "fst_variation" => $fst_variation,
                "disc_per" => $item['disc_per'],
                "size_chart" => $size_chart,
                "sku" => $item['psku'],
                "stock" => $stock,
                "delivery_txt" => $delivery_txt,
                "p_image" => $p_image,
                "reels" => $reels,
                "is_added" => $is_added,
                "quantity" => $quantity,
                "rowid" => $rowid,
                "is_wishlist" => $is_wishlist,
                "fst_image" => $fst_image,
                "is_stock" => $is_stock,
                "alt_tag"   => $item['alt_tag'],
                "head_code" => $item['head_code'],
                "gcr_code"  => $item['gcr_code'],
            );


        }
        return $resultdata;
    } 

    public function get_sales_popup_products($id,$category_id){
        $resultdata=array();
		$locations = ['Mumbai', 'Delhi', 'Bengaluru', 'Chennai', 'Kolkata', 'Hyderabad', 'Ahmedabad', 'Pune', 'Jaipur', 'Surat', 'Lucknow', 'Kanpur', 'Nagpur', 'Visakhapatnam', 'Bhopal'];
	    $sql = $this->db->query("SELECT id, name FROM products WHERE is_deleted = '0' AND status = '1' AND lowest_price > 0 AND id!= '$id' GROUP BY id ORDER BY RAND() LIMIT 1");
		if (!empty($sql)) {
			foreach ($sql->result_array() as $item) {
				$id=$item['id'];
				$product_name=$item['name'];
		        $sql=$this->db->query("SELECT id, image FROM product_images WHERE product_id='$id' order by is_main desc LIMIT 1");
				$images = $sql->result_array();
				$product_url = !empty($images[0]) ? cdn_url() . $images[0]['image'] : cdn_url() . 'assets/images/default_img.jpg';

				$location = $locations[array_rand($locations)];
				$random_minutes = rand(1, 60);
				$location_name = "$random_minutes mins ago from $location";

				$resultdata = array(
					"product" 	  => $product_name,
					"product_url"  => $product_url,
					"location"     => $location_name,
				);
			}
		}
        return $resultdata;
    }

    public function get_average_rating($product_id) {
        $resultdata = array(
            'average_rating'    => 0,
            'total_review'      => 0,
            'five_star_review'  => 0,
            'four_star_review'  => 0,
            'three_star_review' => 0,
            'two_star_review'   => 0,
            'one_star_review'   => 0,
            'five_star_percentage' => 0,
            'four_star_percentage' => 0,
            'three_star_percentage' => 0,
            'two_star_percentage'   => 0,
            'one_star_percentage'   => 0,
        );

        // Fetch total reviews and average rating directly from SQL query
        $query = $this->db->query("
            SELECT
                COUNT(*) AS total_review,
                AVG(rating) AS average_rating,
                SUM(rating = 5) AS five_star_review,
                SUM(rating = 4) AS four_star_review,
                SUM(rating = 3) AS three_star_review,
                SUM(rating = 2) AS two_star_review,
                SUM(rating = 1) AS one_star_review
            FROM `tbl_rating`
            WHERE `product_id` = '$product_id'
        ");

        // echo $this->db->last_query();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $total_reviews = $row['total_review'];

            $resultdata = array(
                'average_rating' => round($row['average_rating'] * 2) / 2,
                'total_review'      => $total_reviews,
                'five_star_review'  => $row['five_star_review'],
                'four_star_review'  => $row['four_star_review'],
                'three_star_review' => $row['three_star_review'],
                'two_star_review'   => $row['two_star_review'],
                'one_star_review'   => $row['one_star_review'],
                'five_star_percentage' => $total_reviews > 0 ? round(($row['five_star_review'] / $total_reviews) * 100, 2) : 0,
                'four_star_percentage' => $total_reviews > 0 ? round(($row['four_star_review'] / $total_reviews) * 100, 2) : 0,
                'three_star_percentage' => $total_reviews > 0 ? round(($row['three_star_review'] / $total_reviews) * 100, 2) : 0,
                'two_star_percentage'   => $total_reviews > 0 ? round(($row['two_star_review'] / $total_reviews) * 100, 2) : 0,
                'one_star_percentage'   => $total_reviews > 0 ? round(($row['one_star_review'] / $total_reviews) * 100, 2) : 0,
            );
        }

        return $resultdata;
    }




    public function get_order_number($next_order_id) {
        $order_id_suffix = 100000 + $next_order_id;
        return $order_id_suffix;
    }

    public function generate_invoice_number($order_id){
        date_default_timezone_set('Asia/Kolkata');
        $sql = $this->db->query("SELECT id,user_phone,order_unique_id FROM tbl_order_details WHERE id='$order_id' LIMIT 1");
        if ($sql->num_rows()>0) {
            if($this->update_invoice_number($order_id)){
               $order=$sql->row_array();
               $phone_number=$order['user_phone'];
               $order_no=$order['order_unique_id'];

            //   $message_user = 'Your Order No. '.$order_no.' has been confirmed and will be delivered in 3 to 5 days for local and 5 to 10 days for National Delivery. - Kids Island';
            //   $template     = '1707171740887911858';
            //   $phone        = $phone_number;
            //   $this->auth_model->send_sms($message_user, $phone, $template);
            }

        }
        return true;
    }

    public function get_payment_by_payid($payment_id){
        $this->db->select('id,payment_id,payment_status,payable_amt,invoice_url');
        $this->db->where('payment_id', $payment_id);
        $query = $this->db->get('tbl_order_details');
        return $query->row_array();
    }

	public function get_payment_by_oid($order_unique_id){
        $this->db->select('id,payment_id,payment_status,payment_method,payable_amt,total_amt,invoice_url,user_name,user_phone,user_email');
        $this->db->where('order_unique_id', $order_unique_id);
        $query = $this->db->get('tbl_order_details');
        return $query->row_array();
    }

    public function update_invoice_number($order_id) {
        date_default_timezone_set('Asia/Kolkata');
        $curr_date = date("Y-m-d H:i:s");
        $order_details = $this->get_order_details_by_id($order_id);
        if($order_details->invoice_no==NULL){
            $this->db->trans_start();
            $currentYear = date('Y');
            $calendarYearStart = "$currentYear-01-01";
            $calendarYearEnd = "$currentYear-12-31";

            $query = $this->db->query("SELECT MAX(CAST(SUBSTRING(user_invoice, 8) AS SIGNED)) AS max_serial, invoice_date FROM order_user_invoice WHERE YEAR(invoice_date) = '$currentYear'");

            $row = $query->row_array();
            $maxSerial = $row['max_serial'];

            $newSerial = ($maxSerial !== null) ? $maxSerial + 1 : 1;

            $calendarMonth = date('m');
            $invoiceNumber = "$calendarMonth$currentYear/" . str_pad($newSerial, 2, '0', STR_PAD_LEFT);

            $data_inv = array();
            $data_inv = array(
                'order_id' => $order_id,
                'vendor_id' => NULL,
                'year' => $currentYear,
                'user_invoice' => $invoiceNumber,
                'invoice_id' => $newSerial,
                'invoice_date' => $curr_date
            );

            $this->db->insert('order_user_invoice', $data_inv);
            $affected_rows = $this->db->affected_rows();

            if ($affected_rows > 0) {
                $data = array(
                    'invoice_no' => $invoiceNumber,
                    'invoice_date' => $curr_date
                );

                $this->db->where('id', $order_id);
                $this->db->update('tbl_order_details', $data);

                // Check transaction status
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return $this->update_invoice_number($order_id);
                } else {
                    $this->db->trans_commit();
                    return $invoiceNumber;
                }
            } else {
                $this->db->trans_rollback();
                return $this->update_invoice_number($order_id);
            }

        }
        else{
          $new_invoice_id=$order_details->invoice_no;
        }
        return $new_invoice_id;
    }

    public function get_ajax_product_reviews($filter_data, $id) {
        $login_user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : '0';

        $resultdata = array();
        $sql_filter = '';
        $start = $filter_data['start'];
        $limit = $filter_data['limit'];
        $type = $filter_data['type'];
        $parent_filter = '';

        if (isset($filter_data['keywords']) && $filter_data['keywords'] != ""):
            $keyword  = $filter_data['keywords'];
            $sql_filter .= " AND (p.name like '%" . $keyword . "%')";
        endif;

		if($type!=''){
        $total_count = $this->db->query("SELECT id FROM tbl_rating WHERE  product_id='$id' ORDER BY id desc")->num_rows();
        $query = $this->db->query("SELECT id,cust_name,avatar,rating,rating_desc,rating_date,rating_time FROM tbl_rating WHERE  product_id='$id' ORDER BY rating_date desc LIMIT $start, $limit");
        //echo $this->db->last_query();exit();

        $disp_total_count = $total_count;
        $total_count = $total_count - ($query->num_rows() + $start);
        $preloader = base_url() . 'assets/images/spinner.gif';
        //echo $this->db->last_query();
        if (!empty($query)) {
            foreach ($query->result_array() as $item) {
                $id = $item['id'];
                $review_date = date("d M, Y", strtotime($item['rating_date']));
                if ($item['avatar'] != '') {
                    $avatar = cdn_url() . '/' . $item['avatar'];
                } else {
                    $avatar = cdn_url() . 'assets/images/avatar/user.jpg';
                }

                $review_time = date("Y-m-d H:i:s", strtotime($item['rating_date'] . ' ' . $item['rating_time']));
                $resultdata[] = array(
                    "id"                => $item['id'],
                    "cust_name"         => $item['cust_name'],
                    "avatar"            => $avatar,
                    "rating"            => $item['rating'],
                    "rating_desc"       => $item['rating_desc'],
                    "rating_date"       => $rating_date,
                    "review_time"       => $review_time,
                    "rating_time"       => $item['rating_time'],
                    "preloader"         => $preloader,
                    "total_count"       => $total_count,
                    "disp_total_count"  => $disp_total_count,
                );
            }
         }
		}
        return $resultdata;
    }

    public function get_product_reviews_images($id='') {
        $resultdata = array();
        $query      = $this->db->query("SELECT parent_id,image FROM tbl_rating_images where parent_id='$id' order by id");
        if (!empty($query)) {
            foreach ($query->result_array() as $item) {
                $resultdata[] = array(
                    "image" => $item['image'],
                    "id" => $item['parent_id'],
                );
            }
        }

        return $resultdata;
    }

    public function get_product_faq($id){
        $resultdata = array();
        $query = $this->db->query("SELECT id,name,value FROM oc_product_info WHERE product_id='$id' ORDER BY id DESC");

        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"    => $item['id'],
                    "name"  => $item['name'],
                    "value" => $item['value'],
                );
            }
        }
        return $resultdata;
    }

    public function get_selected_product_variations() {
        $data=array();
        $product_id=$this->input->post('product_id');
        $variationArray=$this->input->post('variation_array');

        $name = '';
        $code = '';
        $lstKey = array_key_last($variationArray);
        // foreach ($variationArray as $option_id => $choice_id) {
        //     $option_name=$this->common_model->getNameById('oc_attributes','name',$option_id);
        //     $choice_name=$this->common_model->getNameById('oc_attribute_values','name',$choice_id);
        //     $name .= $option_name . ': ' . $choice_name;
        //     $code .= $option_id . ':' . $choice_id . '/';

        //     if ($lstKey != $option_id) {
        //         $name .= ' / ';
        //     }
        // }
        $variation_id = 0;
        $data['status'] = 200;
        $data['htmlPrice'] = '';


		$product_id = $this->input->post('product_id');
		$size_id    = $this->input->post('variation'.$product_id.'_2');
		$taper_id   = 0;
		$length_id  = 0;

        $var_query = $this->db->query("SELECT id, mrp as product_mrp, selling_price,'' as code, '0' as disc_per FROM erp_uniform_size_prices WHERE uniform_id='$product_id' and size_id='$size_id' LIMIT 1");
		// echo $var_query->num_rows();exit();
        // echo 1;exit();
        if (!empty($var_query)) {
				$product = $this->common_model->getRowById('erp_uniforms','product_name',$product_id);
                // echo $this->db->last_query();exit();
				// $min_qty = $product['min_quantity'];
			    $min_qty = 1;
                

			   $var=$var_query->row();
			   $variation_id=$var->id;
			   $sku=$var->sku;

				// $stock = get_stock_qty_2($product_id, $variation_id);
				$stock = 1;
			    $selling_price = get_selling_price($product_id,$variation_id);

                // checking cart
                $rowid=0;
                $flag = FALSE;
                $dataTmp = $this->cart->contents();
                $var_product_id = $size_id . $product_id;

                foreach ($dataTmp as $cart) {
                    if ($cart['product_id'] == $product_id && $cart['id'] == $var_product_id) {
                        $flag  = TRUE;
                        $rowid = $cart['rowid'];
                        break;
                    }
                }

                $is_added = $flag ? 1 : 0;
                $quantity = $flag ? $cart['qty'] : 0;
                $rowid = $flag ? $rowid : 0;


				$output_add_cart='';
				$output_add_cart_list='';
                $output_add_cart_bottom='';
				if($stock>0){
                    $uid = $var_product_id;
					$output_add_cart_list='
                    <button type="submit" name="add_to_cart" class="btn mx-0 px-2 add_to_cart btn-secondary qfirst'.$var_product_id.' text-uppercase" style="' . ($is_added == 1 ? 'display:none' : '') . '">Add To Cart</button>
                    <div class="shop-meta qty-btn hidden-xs h-100" style="' . ($is_added == 1 ? '' : 'display:none') . '">
                        <div class="w-100 b_e9bf qsecond' . $uid .'" >
                            <input type="button" value="-" class="addrowdata plu_ic btn--remove-to-cart qtyminus' .$uid . '" field="quantity2" data-productname="' . $product['product_name'] . '" data-productid="' . $uid .'" data-rowid="' . $rowid . '"/>
                            <input type="text" readonly name="quantity2' . $uid .'" value="' . $quantity . '" class="b_g9bf qty-btn-val qty' . $uid . '" />
                            <input type="hidden" class="addrowvalue" readonly id="rowid' . $uid . '" field="rowid" value="' . $rowid . '" />
                            <input type="button" value="+" class="addrowdata plu_ic btn--add-to-cart qtyplus' . $uid . '" field="quantity2" data-productname="' .$product['product_name'] . '" data-productid="' . $var_product_id. '" data-rowid="' . $rowid . '"/>
                        </div>
                    </div>
                   <button type="button" onclick="buyNow()" name="buy_now" value="1" class="btn btn-outline-success buy_btn text-uppercase btn-buy-cart">Buy Now &nbsp;<img src="' . base_url() . 'assets/icons/upi_options.svg" class="sr-checkout-visible"> &nbsp; <i class="fa fa-chevron-right"></i></button>
                    ';

					$output_add_cart_bottom='
                    <button type="button" class="btn btn-secondary px-2 add_to_cart qfirst'.$var_product_id.' text-uppercase" onclick="addToCartProduct();" style="' . ($is_added == 1 ? 'display:none' : '') . '">Add To Cart</button>
                    <div class="shop-meta qty-btn hidden-xs h-100" style="' . ($is_added == 1 ? '' : 'display:none') . '">
                        <div class="w-100 b_e9bf qsecond' . $uid .'" >
                            <input type="button" value="-" class="addrowdata plu_ic btn--remove-to-cart qtyminus' .$uid . '" field="quantity3" data-productname="' . $product['product_name'] . '" data-productid="' . $uid .'" data-rowid="' . $rowid . '"/>
                            <input type="text" readonly name="quantity3' . $uid .'" value="' . $quantity . '" class="b_g9bf qty-btn-val qty' . $uid . '" />
                            <input type="hidden" class="addrowvalue" readonly id="rowid' . $uid . '" field="rowid" value="' . $rowid . '" />
                            <input type="button" value="+" class="addrowdata plu_ic btn--add-to-cart qtyplus' . $uid . '" field="quantity3" data-productname="' . $product['product_name'] . '" data-productid="' . $uid .'" data-rowid="' . $rowid . '"/>
                        </div>
                    </div>';


					$output_add_cart='<div class="note-box product-packege">
					<input  id="min_qty" type="hidden"  name="min_qty" value="'.$min_qty.'">
						<div class="cart_qty qty-box product-qty">
						   <div class="input-group">
							   <button type="button" class="btn qty-left-minus" data-type="minus" data-field="">
									<i class="fa fa-minus ms-0" aria-hidden="true"></i>
								</button>
								<input class="form-control input-number qty-input" id="quantity" type="text" onkeypress="return isNumberKey(event,this)" name="quantity" value="'.$min_qty.'">
								<button type="button" class="btn qty-right-plus" data-type="plus" data-field="">
									<i class="fa fa-plus ms-0"  aria-hidden="true"></i>
								</button>
						   </div>
						</div>
					   <button type="submit" name="add_to_cart" class="btn bg-dark btn-product-cart cart-button cart_btn text-white"><span class="btn-cart-icon"><i class="fa fa-cart-shopping"></i></span> Add To Cart</button>

					<button type="button" onclick="buyNow()" name="buy_now" value="1" class="btn btn-outline-success buy_btn text-uppercase btn-buy-cart">Buy Now &nbsp;<img src="' . base_url() . 'assets/icons/upi_options.svg" class="sr-checkout-visible"> &nbsp; <i class="fa fa-chevron-right"></i></button>
				 </div>';


				}
				else{

                    $output_add_cart_list='<button type="button" name="add_to_cart" class="btn btn-secondary text-uppercase" disabled>Sold Out</button>';

					$output_add_cart_bottom='<button type="button" class="btn btn-secondary text-uppercase" disabled>Sold Out</button>';

					$output_add_cart='<button  class="btn bg-dark text-white mt-3" disabled><span class="btn-cart-icon"><i class="fa fa-cart-shopping"></i></span> Sold Out</button>';
				}

				$lbl_price ='';

				if($selling_price!=$var->product_mrp){
			        $lbl_price= '<span class="theme-color price">'.priceFormatted($var->selling_price).' <span class="strikethrough">'.priceFormatted($var->product_mrp).'</span></span> <span class="discountp">'.(int)discount_per($var->product_mrp, $var->selling_price).'% OFF</span>';
			    }else{
			        $lbl_price ='<span class="theme-color price">'.priceFormatted($selling_price).'</span>';
			    }

			   $data['variation_id'] = $var->id;
			   $data['product_mrp'] =  $var->product_mrp;
			   $data['selling_price'] = $selling_price;
			//    $data['code'] = $var->code;
			   $data['stock'] = $stock;
			//    $data['sku'] = ($var->sku) ? 'SKU CODE : '.$var->sku:'SKU CODE : -';
			   $data['view'] = $this->load->view('frontend/default/cart_updated_items',[],TRUE);
			   $data['output_add_cart'] = $output_add_cart;
			   $data['output_add_cart_list'] = $output_add_cart_list;
			   $data['output_add_cart_bottom'] = $output_add_cart_bottom;
			   $data['lbl_price'] = $lbl_price;

			if($var->product_mrp!= $var->selling_price){
			  $data['htmlPrice'] = '<strong class="price__current"><span class="mrp"></span><span class="money" doubly-currency-inr="41600" doubly-currency="INR">'.priceFormatted($selling_price).'</span>
              </strong>
              <s class="price__was"><span class="money" doubly-currency-inr="55000" doubly-currency="INR">'.priceFormatted($var->product_mrp).'</span>
              </s><span class="rate_off">('.(int)discount_per($var->product_mrp, $var->selling_price).'% off)</span>';

            } else{
                 $data['htmlPrice'] = '<strong class="price__current"><span class="mrp"></span><span class="money" doubly-currency-inr="41600" doubly-currency="INR">'.priceFormatted($selling_price).'</span>
                 </strong>';
			 }
        }

        // $sql  = $this->db->query("SELECT id, image FROM product_images WHERE product_id='$product_id' AND variation_id='$variation_id'");
		$product_images = array();
		// if($sql->num_rows()>0){
		// foreach ($sql->result_array() as $gimg) {
		// 	if($gimg['image']!=''){
		// 		 $product_images[] = array(
		// 		"id" 	=> $gimg['id'],
		// 		"image" => image_url().$gimg['image'],
		// 	   );
		// 	}
		//   }
		// }  else{
		// 	$p_image = image_url().'assets/images/default_img.jpg';
		// 	$product_images[] = array(
		// 		"id" 	=> '',
		// 		"image" => $p_image,
		// 	);
		// }

        $data['variation_images']= $product_images;
        return simple_json_output($data);
    }

    public function random_product(){
        $curr_date = date("Y-m-d");
        $resultdata=array();
        $preloader=base_url().'assets/images/spinner.gif';

		$products = array();
		$sql = $this->db->query("SELECT pvar.sku,p.id,p.slug,p.sku,pvar.code,pvar.id AS variation_id,pvar.product_mrp,pvar.selling_price,p.name,pvar.size FROM products as p INNER JOIN product_variations as pvar ON p.id=pvar.product_id WHERE p.status='1' GROUP BY p.id ORDER BY RAND() LIMIT 1");

		if (!empty($sql)) {
			foreach ($sql->result_array() as $item) {
				$id = $item['id'];
				$product_id=$item['id'];
				$variation_id=$item['variation_id'];

                if($item['you_save_amt']!='0'){
                    $product_price=$item['selling_price'];
    			} else{
    			    $product_price=$item['product_mrp'];
    			}

		        $sql=$this->db->query("SELECT id, image FROM product_images WHERE product_id='$id' order by is_main desc LIMIT 1");

				$images = $sql->result_array();
				$thumbnail_img_1 = !empty($images[0]) ? cdn_url() . $images[0]['image'] : cdn_url() . 'assets/images/default_img.jpg';
				//echo $this->db->last_query();exit();

	        	$default_size_id=0;

				$stock = get_stock_qty($product_id);
				$selling_price=get_selling_price($product_id,$variation_id);
				$product_mrp=get_mrp_price($product_id,$variation_id);
				$disc_per = cal_dis_per($product_mrp,$selling_price);

				$category=$this->common_model->getBulkNameIds('categories','name',$item['category_id']);
                $var_id = $this->common_model->getSizeByID($id);

				$products[] = array(
					"id"             => $item['id'],
					"name"           => $item['name'],
					"category_id"           => $item['category_id'],
					"variation_id"   => $variation_id,
					"var_id"         => $var_id,
                    "category_name"  => $this->common_model->get_category_name($item['category_id']),
					"slug"           => $item['slug'],
					"lowest_price"   => $item['lowest_price'],
					"sku"  			 => $item['sku'],
					"highest_price"  => $item['highest_price'],
					"product_mrp"    => $product_mrp,
					"selling_price"  => $selling_price,
					"disc_per" 		 => (int)$disc_per,
					"is_variation"   => $item['is_variation'],
					"preloader"      => $preloader,
					"image"          => $thumbnail_img_1,
					"category"  => $category,
					"stock" => $stock,
				);
			}
		}

		$resultdata = $products[0];
        return $resultdata;
    }


	public function get_related_products($id, $category_id)
	{
		$resultdata = [];

		// Fetch related products (excluding current product) with one variation
		$this->db->select("p.*, pvar.id AS variation_id, pvar.selling_price AS selling, pvar.size, pvar.product_mrp AS mrp");
		$this->db->from("products p");
		$this->db->join("product_variations pvar", "p.id = pvar.product_id", "inner");
		$this->db->join("products_category pc", "p.id = pc.product_id", "inner");
		$this->db->where("p.status", '1');
		$this->db->where("p.id !=", $id);
		$this->db->where("pc.category_id", $category_id);
		$this->db->group_by("p.id");
		$this->db->order_by("RAND()");
		$this->db->limit(10);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$cart_items = $this->cart->contents();
			$user_id = $this->session->userdata('user_id');
			$is_logged_in = $this->session->userdata('user_login') == '1';

			foreach ($query->result_array() as $item) {
				$product_id = $item['id'];
				$variation_id = $item['variation_id'];

				// --- Size info
				$size_name = '';
				$size_id = $item['size'];
				$size_option = $item['size'];

				if (!empty($size_id)) {
					$size_arr = $this->common_model->getRowById('oc_attribute_values', 'name,attribute_id', $size_id);
					if (!empty($size_arr)) {
						$size_name = $size_arr['name'];
						$size_option = $size_arr['attribute_id'];
					}
				}

				// --- Cart check
				$is_added = 0;
				$quantity = 0;
				$rowid = 0;
				foreach ($cart_items as $cart) {
					if ($cart['product_id'] == $product_id) {
						$is_added = 1;
						$quantity = $cart['qty'];
						$rowid = $cart['rowid'];
						break;
					}
				}

				// --- Price selection
				$product_price = (!empty($item['you_save_amt']) && $item['you_save_amt'] != '0') ? $item['selling'] : $item['mrp'];

				// --- Image
				$image_row = $this->db
					->select('image')
					->where('product_id', $product_id)
					->order_by('id', 'ASC')
					->limit(1)
					->get('product_images')
					->row_array();

				$image_url = !empty($image_row) ? cdn_url() . $image_row['image'] : '';

				if (empty($image_url)) {
					continue; // skip products without image
				}

				// --- Wishlist
				$is_wishlist = 0;
				if ($is_logged_in) {
					$wishlist = $this->db->select('id')
						->where(['product_id' => $product_id, 'user_id' => $user_id])
						->get('tbl_wishlist');

					if ($wishlist->num_rows() > 0) {
						$is_wishlist = 1;
					}
				}

				// --- Category slug
				$cat = $this->db->select('slug')->where('id', $item['category_id'])->limit(1)->get('categories')->row_array();
				$category_slug = $cat['slug'] ?? '';

				// --- Average rating
				$avg_rating = $this->crud_model->get_average_rating($product_id);
				$avg_rating_val = $avg_rating['average_rating'] ?? 0;
				$total_review = $avg_rating['total_review'] ?? 0;

				// --- Final Product Array
				$resultdata[] = [
					"id"               => $product_id,
					"variation_id"     => $variation_id,
					"category_id"      => $item['category_id'],
					"category_slug"    => $category_slug,
					"category_name"    => $this->common_model->get_category_name($item['category_id']),
					"product_title"    => $item['name'],
					"product_slug"     => $item['slug'],
					"size_name"        => $size_name,
					"size_id"          => $size_id,
					"size_option"      => $size_option,
					"featured_image"   => $image_url,
					"tags"             => $item['tags'],
					"product_mrp"      => $item['mrp'],
					"selling_price"    => $item['selling'],
					"you_save_amt"     => $item['you_save_amt'],
					"you_save_per"     => $item['you_save_per'],
					"rate_avg"         => $item['rate_avg'],
					"product_desc"     => $item['short_description'],
					"offer_id"         => $item['offer_id'],
					"alt_tag"          => $item['alt_tag'],
					"is_wishlist"      => $is_wishlist,
					"product_price"    => $product_price,
					"is_added"         => $is_added,
					"quantity"         => $quantity,
					"rowid"            => $rowid,
					"avg_rating"       => $avg_rating_val,
					"total_review"     => $total_review,
				];
			}
		}

		return $resultdata;
	}


	 public function get_product_datails_by_id($id){
        $resultdata     = array();
        $query = $this->db->query("SELECT * FROM tbl_product WHERE id='$id' AND status='1' ORDER BY id asc LIMIT 1");
        if (!empty($query)) {
            $item=$query->row_array();

            $rowid=0;
            $flag = FALSE;
            $dataTmp = $this->cart->contents();

			$product_id = $item['id'];

            foreach ($dataTmp as $cart) {
                if ($cart['product_id'] == $item['id']) {
                    $flag  = TRUE;
                    $rowid = $cart['rowid'];
                    break;
                }
            }


            if ($flag) {
             $is_added = 1;
             $quantity = $cart['qty'];
             $rowid = $rowid;
            }
            else{
             $is_added = 0;
             $quantity = 0;
             $rowid = 0;
            }

            if($item['you_save_amt']!='0'){
                $product_price=$item['selling_price'];
			} else{
			    $product_price=$item['product_mrp'];
			}

			$image_array = array();
			if($item['featured_image'] != ''){
			    $image_array[] = array(
                  "url"    =>  product_image_url().$item['featured_image'],
                  "image"   =>  product_image_url().$item['featured_image'],
                  "class"   => 'prod-img',
                );
			}

			if($item['featured_image2'] != ''){
				 $image_array[] = array(
                  "url"    =>  product_image_url().$item['featured_image2'],
                  "image"   =>  product_image_url().$item['featured_image2'],
                  "class"   => 'prod-img',
                );
			}

			if($item['yt_url'] != '' && $item['yt_url'] != NULL){
				 $video_details = $this->video_model->getVideoDetails($item['yt_url']);
				 $image_array[] = array(
                  "url"    =>  $item['yt_url'],
                  "image"   =>  $video_details['thumbnail'],
                  "class"   => 'yt-img',
                );
			}

            $query_image = $this->db->query("SELECT image_file FROM tbl_product_images WHERE parent_id='$product_id' AND status='1' AND type='product'");
            foreach($query_image->result_array() as $item_image){
                $gallery_img=product_image_url().'gallery/'.$item_image['image_file'];
				$image_array[] = array(
                  "url"     => $gallery_img,
                  "image"   => $gallery_img,
                  "class"   => 'prod-img',
                );
            }
            //$image_array = explode(',',trim($image_array));

            $product_desc=$item['product_desc'];

            //$product_desc.='<br/>-Manufactured By<br/><b>Rajasthan Herbals International</b><br/><a target="_blank" href="https://www.rajasthanherbalsinternational.com/">www.rajasthanherbalsinternational.com</a>';

            $resultdata = array(
                "id"                    => $item['id'],
                "category_id"      	    => $item['category_id'],
                "sub_category_id"       => ($item['sub_category_id']!='' ? $item['sub_category_id']:0),
                "category_name"         => $this->common_model->get_category_name($item['category_id']),
                "sub_category_name"      => $this->common_model->get_sub_category_name($item['sub_category_id']),
                "product_title"      	=> $item['product_title'],
                "product_slug"          => $item['product_slug'],
                "featured_image"        => product_image_url().$item['featured_image'],
                "featured_image2"       => product_image_url().$item['featured_image2'],
                "product_mrp" 		    => $item['product_mrp'],
                "selling_price" 		=> $item['selling_price'],
                "you_save_amt" 		    => $item['you_save_amt'],
                "you_save_per" 		    => $item['you_save_per'],
                "rate_avg" 		        => $item['rate_avg'],
                "offer_id" 	            => $item['offer_id'],
                "product_desc" 	        => $product_desc,
                "product_features" 	    => $item['product_features'],
                "sku" 	    => $item['sku'],
                "packaging_size" 	    => $item['packaging_size'],
                "packaging_type" 	    => $item['packaging_type'],
                "short_description" 	    => $item['short_description'],
                "composition" 	    => $item['composition'],
                "indication" 	    => $item['indication'],
                "product_does_and_dir" 	    => $item['product_does_and_dir'],
                "product_features" 	    => $item['product_features'],
                "meta_title" 	    	=> $item['meta_title'],
                "meta_description" 	    => $item['meta_description'],
                "meta_keyword" 	    	=> $item['meta_keyword'],
                "yt_url" 	    		=> $item['yt_url'],
                "alt_tag" 	    		=> $item['alt_tag'],
                "head_code" 	    	=> $item['head_code'],
                "gcr_code" 	    		=> $item['gcr_code'],
                "offer_name" 	        => $this->common_model->get_offer_name($item['offer_id']),
                "product_price"         => $product_price,
                "is_added"              => $is_added,
                "quantity"              => $quantity,
                "rowid"                 => $rowid,
                "image_array"           => $image_array,
            );
          }

       return $resultdata;
    }

	public function healthcare_distributor($type){
		$resultpost = array(
			"status" => 200
		);

        $data['type']           = $type;
        $data['name']           = html_escape($this->input->post('name'));
        $data['phone']          = html_escape($this->input->post('phone'));
        $data['email']          = html_escape($this->input->post('email'));
        $data['address']        = html_escape($this->input->post('address'));
        $data['created_at']     = date("Y-m-d H:i:s");

        $this->db->insert('healthcare_distributor', $data);
        return simple_json_output($resultpost);
    }

	public function contact_enquiries(){
		$resultpost = array(
			"status" => 200
		);

        $data['contact_name']   = html_escape($this->input->post('name'));
        $data['contact_email']  = html_escape($this->input->post('email'));
        $data['contact_phone']  = html_escape($this->input->post('phone'));
        $data['contact_subject']= html_escape($this->input->post('subject'));
        $data['contact_msg']    = html_escape($this->input->post('message'));
        $data['created_at']     = date("Y-m-d H:i:s");

        $this->db->insert('tbl_contact_list', $data);
        return simple_json_output($resultpost);
    }
       public function get_album_by_slug($id)
    {
        $this->db->select('name,description, meta_title, meta_description, meta_keyword, head_code, gcr_code');
        $this->db->where('slug', $id);
        return $this->db->get('tbl_album');
    }


	 public function get_doctors_testimonial(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id,name,link FROM oc_yt_testimonial ORDER BY id DESC");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"       => $item['id'],
                    "name"     => $item['name'],
                    "link"     => $item['link'],
                );
            }
        }
       return $resultdata;
    }

	 public function get_yt_patients_testimonial(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, name, link FROM patients_yt_testimonial  ORDER BY id DESC");
        if (!empty($query)) {
            foreach($query->result_array() as $item){
                $resultdata[] = array(
                    "id"       => $item['id'],
                    "name"     => $item['name'],
                    "link"     => $item['link'],
                );
            }
        }
       return $resultdata;
    }

	public function get_patients_testimonial(){
        $resultdata     = array();
        $query = $this->db->query("SELECT id, name, location, description, testimonial_image FROM tbl_testimonial WHERE status='1' ORDER BY id DESC");
        if (!empty($query)) {
			$i=1;
            $class = '';
            foreach($query->result_array() as $item){
				if($i % 2 == 0){
                    $class = 'bg1';
                }
                else{
                    $class = 'bg2';
                }
			   $resultdata[] = array(
					"id"                    => $item['id'],
					"name"      	        => $item['name'],
					"location"      	    => $item['location'],
					"description"      	    => $item['description'],
                     "class" 				=> $class,
					"testimonial_image"     => testimonial_image_url().$item['testimonial_image'],
				);
               $i++;
            }
        }
       return $resultdata;
    }

	public function total(){
        $s=0;
        $cart=$this->cart->contents();
        $items=($cart!=''?array_values($cart):array());

       if(!empty($items)){
        foreach($items as $item){
           $s += $item['price'] * $item['qty'];
           $emb_price=0;
		   $em_prod=json_decode($item['embroidery'],true);
		   $emb_price=($em_prod['emb_price']!='' ? $em_prod['emb_price']:0);
           $s += $emb_price;
         }
        }
        return $s;
    }

    public function mrp_total(){
        $s=0;
        $cart=$this->cart->contents();
        $items=($cart!=''?array_values($cart):array());

        // echo json_encode($cart);

       if(!empty($items)){
        foreach($items as $item){
           $s += $item['mrp'] * $item['qty'];
           $emb_price=0;
           $em_prod=json_decode($item['embroidery'],true);
           $emb_price=($em_prod['emb_price']!='' ? $em_prod['emb_price']:0);
           $s += $emb_price;
         }
        }
        return $s;
    }

    public function excl_total(){
       $s=0;
        $cart=$this->cart->contents();
        $items=($cart!=''?array_values($cart):array());

       if(!empty($items)){
        foreach($items as $item){
          $product_total_price=$gst_amt=0;
          $product_total_price=price_format_decimal($item['price'] * $item['qty']);
          $gst_amt=get_gst_amt($product_total_price,$item['gst']);
          $s += ($product_total_price-$gst_amt);

           $emb_price=0;
		   $em_prod=json_decode($item['embroidery'],true);
		   $emb_price=($em_prod['emb_price']!='' ? $em_prod['emb_price']:0);
           $s += $emb_price;
         }
        }
        return $s;
    }

	private function remove_existing_free_items($offer_id) {
        foreach ($this->cart->contents() as $item) {
            if (isset($item['is_free_item']) && $item['is_free_item'] &&
                isset($item['offer_id']) && $item['offer_id'] == $offer_id) {
                $this->cart->remove($item['rowid']);
            }
            if (isset($item['options']['is_free_item']) && $item['options']['is_free_item'] &&
                isset($item['options']['offer_id']) && $item['options']['offer_id'] == $offer_id) {
                $this->cart->remove($item['rowid']);
            }
        }
    }

	private function item_matches_offer($cart_item, $offer) {
        if ((isset($cart_item['is_free_item']) && $cart_item['is_free_item']) ||
            (isset($cart_item['options']['is_free_item']) && $cart_item['options']['is_free_item'])) {
            return false;
        }

        $product_id = $cart_item['product_id'];
        $variation_id = null;

        if (isset($cart_item['variation']) && is_string($cart_item['variation'])) {
            $variation_array = json_decode($cart_item['variation'], true);
            if (is_array($variation_array)) {
                $code_parts = [];
                foreach ($variation_array as $key => $value) {
                    $code_parts[] = $key . ':' . $value;
                }
                $variation_code = implode('/', $code_parts) . '/';

                $this->db->select('id');
                $this->db->from('product_variations');
                $this->db->where('product_id', $product_id);
                $this->db->where('code', $variation_code);
                $result = $this->db->get()->row();

                if ($result) {
                    $variation_id = $result->id;
                }
            }
        }

        //echo $variation_id;exit();

        $item_type_list = is_array($offer->item_type_list) ? $offer->item_type_list : explode(',', $offer->item_type_list);
        $variation_ids = is_array($offer->variation_ids) ? $offer->variation_ids : explode(',', $offer->variation_ids);

        if ($offer->item_type == 'all') {
            return true;
        }

        if ($offer->item_type == 'products') {
            return in_array($product_id, $item_type_list) && ($variation_id && in_array($variation_id, $variation_ids));
        }

        if ($offer->item_type == 'categories') {
            $item_categories = $this->common_model->get_product_categories($cart_item['category_id']);
            $offer_categories = is_array($offer->item_type_list) ? $offer->item_type_list : explode(',', $offer->item_type_list);
            return !empty(array_intersect($item_categories, $offer_categories));
        }
        return false;
    }

	private function add_free_products_to_cart($offer) {
        $free_products = array();
        $variation_ids='';

        if ($offer->offer_value_type != 'free' || $offer->free_quantity <= 0) {
            return $free_products;
        }

        // Get eligible products based on item_type_get
        $eligible_products = array();

        if ($offer->item_type_get == 'all') {
            $eligible_products = $this->common_model->get_random_products($offer->free_quantity);
        } elseif ($offer->item_type_get == 'categories') {
            $category_ids = explode(',', $offer->item_type_list_get);
            $eligible_products = $this->common_model->get_products_by_categories($category_ids, $offer->free_quantity);
        } elseif ($offer->item_type_get == 'products') {
            $product_ids = explode(',', $offer->item_type_list_get);
            $variation_ids = explode(',', $offer->variation_ids_get);


            // Prefer variations if defined
            if (!empty($variation_ids)) {
                $eligible_products = $this->common_model->get_products_by_variation_ids($variation_ids);
            } else {
                $eligible_products = $this->common_model->get_products_by_ids($product_ids, count($product_ids));
            }
        }


        if (empty($eligible_products)) {
            return $free_products;
        }

        // Get complete product data with variations for each eligible product
        foreach ($eligible_products as &$product) {
            // Initialize default parameters for get_product_by_id()
            $variationArray = [2 => null]; // Default variation array
            $perfume_type = null;

            if (!empty($variation_ids)) {
                $cheapest_variation = $this->get_cheapest_variation_by_id($product->variation_id);
                if ($cheapest_variation) {
                    $variationArray = [2 => $cheapest_variation->size];
                }
            }
            else{
                // Get the cheapest variation first
                $cheapest_variation = $this->get_cheapest_variation($product->id);
                if ($cheapest_variation) {
                    $variationArray = [2 => $cheapest_variation->size];
                }
            }


            // Now get full product data with the proper parameters
            $product->full_data = $this->crud_model->get_product_by_id(
                $product->id,
                $variationArray,
                $perfume_type
            );

            //echo json_encode($product); exit();


            if (!empty($variation_ids)) {
                $product->variations = $this->get_product_variations_by_id($product->variation_id);
                $product->lowest_price = $this->get_lowest_product_price_by_id($product->variation_id);
            }
            else{
                $product->variations = $this->get_product_variations($product->id);
                $product->lowest_price = $this->get_lowest_product_price($product->id);
            }
        }


        // Rest of the function remains the same...
        usort($eligible_products, function($a, $b) {
            return $a->lowest_price - $b->lowest_price;
        });

        $product_count = count($eligible_products);
        $base_quantity = floor($offer->free_quantity / $product_count);
        $remaining_quantity = $offer->free_quantity % $product_count;

        //echo json_encode($eligible_products); exit();

        foreach ($eligible_products as $index => $product) {
            $quantity = $base_quantity;

            if ($index < $remaining_quantity) {
                $quantity++;
            }

            if ($quantity <= 0) {
                continue;
            }

            if (!empty($variation_ids)) {
                $cheapest_variation = $this->get_cheapest_variation_by_id($product->variation_id);
                if ($cheapest_variation) {
                    $variationArray = [2 => $cheapest_variation->size];
                }
            }
            else{
                $cheapest_variation = $this->get_cheapest_variation($product->id);
                $variationArray = [2 => $cheapest_variation ? $cheapest_variation->size : null];
            }


            $cart_item = array(
                "id" => 'free_' . $offer->id . '_' . $product->id . '_' . ($cheapest_variation ? $cheapest_variation->id : '0'),
                "order_type" => 'offer',
                "product_id" => $product->id,
                "name" => $product->name,
                "qty" => $quantity,
                "price" => 0,
                "selling_price" => 0,
                "price_mrp" => $cheapest_variation->product_mrp,
                'category_id' => $product->full_data->category_id,
                'gst' => $product->full_data->gst,
                'gst_amt' => 0,
                "is_variation" => $product->full_data->is_variation,
                "image" => $product->image,
                "variation_name" => $cheapest_variation ? $cheapest_variation->name : null,
                'variation' => json_encode($variationArray),
                'warehouse_id' => 1,
                "package_id" => 0,
                "f_name" => '',
                "m_name" => '',
                "s_name" => '',
                "dob" => '',
                "school_id" => '',
                "is_gift" => 1,
                'customization' => json_encode(array()),
                'is_free_item' => true,
                'offer_id' => $offer->id
            );

            $this->cart->insert($cart_item);
            $free_products[] = array(
                'product' => $product,
                'quantity' => $quantity,
                'variation' => $cheapest_variation
            );
        }

        return $free_products;
    }

	 private function get_cheapest_variation($product_id)
    {
        $this->db->select('id, name, size, selling_price, product_mrp');
        $this->db->from('product_variations');
        $this->db->where('product_id', $product_id);
        $this->db->order_by('selling_price', 'ASC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

	private function get_cheapest_variation_by_id($var_id) {
        $this->db->select('id, name, size, selling_price, product_mrp');
        $this->db->from('product_variations');
        $this->db->where('id', $var_id);
        $this->db->order_by('selling_price', 'ASC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

	private function remove_all_free_items() {
        foreach ($this->cart->contents() as $item) {
            if (isset($item['is_free_item']) && $item['is_free_item']) {
                $this->cart->remove($item['rowid']);
            }
        }
    }

	public function recalculate_free_products()
	{
		$coupon = $this->session->userdata('sess_coupon');

		if (!$coupon || !isset($coupon['coupon_id'])) {
			$this->remove_all_free_items();
			$this->session->unset_userdata('sess_coupon');
			return;
		}

		$offer = $this->common_model->selectByids(['id' => $coupon['coupon_id']], 'offers');

		if (empty($offer)) {
			$this->remove_all_free_items();
			$this->session->unset_userdata('sess_coupon');
			return;
		}

		$offer = $offer[0];

		if ($offer->offer_value_type === 'free') {
			$this->remove_existing_free_items($offer->id);
		}

		$cart_items = $this->cart->contents();

		$eligible_items = [];
		$cart_amt = 0;
		$total_qty = 0;

		foreach ($cart_items as $item) {
			// Ignore free items
			if (!empty($item['is_free_item'])) continue;

			$cart_amt += $item['price'] * $item['qty'];

			if ($this->item_matches_offer($item, $offer)) {
				$eligible_items[] = $item;
				$total_qty += $item['qty'];
			}
		}

		// Check if coupon condition is still met
		$requirement_met = false;

		if ($offer->min_type == 'quantity') {
			$requirement_met = ($total_qty >= (float)$offer->min_value);
		} else {
			$eligible_total = 0;
			foreach ($eligible_items as $item) {
				$eligible_total += $item['price'] * $item['qty'];
			}
			$requirement_met = ($eligible_total >= (float)$offer->min_value);
		}

		if ($requirement_met) {
			$discount = 0;
			$discount_type = '';
			$free_products_added = [];

			switch ($offer->offer_value_type) {
				case 'percentage':
					$eligible_total = 0;
					foreach ($eligible_items as $item) {
						$eligible_total += $item['price'] * $item['qty'];
					}
					$discount = ($offer->offer_value / 100) * $eligible_total;
					$discount_type = 'percentage';
					break;

				case 'amount':
					$discount = $offer->offer_value;
					$discount_type = 'amount';
					break;

				case 'free':
					$discount = 0;
					$discount_type = 'free';
					$free_products_added = $this->add_free_products_to_cart($offer);
					break;
			}

			$payable_amt = $cart_amt - $discount;

			// Update session
			$coupon['discount'] = $discount;
			$coupon['discount_type'] = $discount_type;
			$coupon['payable_amt'] = $payable_amt;
			$coupon['free_products'] = $free_products_added;

			$this->session->set_userdata('sess_coupon', $coupon);
		} else {
			// Requirement failed — clean up
			$this->remove_all_free_items();
			$this->session->unset_userdata('sess_coupon');
		}
	}


	private function get_product_variations_by_id($var_id) {
        $this->db->select('*');
        $this->db->from('product_variations');
        $this->db->where('id', $var_id);
        return $this->db->get()->result();
    }

	private function get_lowest_product_price_by_id($var_id) {
        $this->db->select_min('selling_price');
        $this->db->from('product_variations');
        $this->db->where('id', $var_id);
        $result = $this->db->get()->row();
        return $result ? $result->selling_price : 0;
    }

	private function get_product_variations($product_id) {
        $this->db->select('*');
        $this->db->from('product_variations');
        $this->db->where('product_id', $product_id);
        return $this->db->get()->result();
    }

	private function get_lowest_product_price($product_id) {
        $this->db->select_min('selling_price');
        $this->db->from('product_variations');
        $this->db->where('product_id', $product_id);
        $result = $this->db->get()->row();
        return $result ? $result->selling_price : 0;
    }

	public function get_apply_coupon($coupon_id) {
		$user_id = $this->session->userdata('user_id') ?: '0';
		$delivery = 0;

		$final_cart_amt = price_format_decimal($this->total());
		$cart_amt = price_format_decimal($this->excl_total());

		$where = array('id' => $coupon_id, 'status' => 1);
		$offer = $this->common_model->selectByids($where, 'offers');

		if (!empty($offer)) {
			$offer = $offer[0];

			$variation_ids = !empty($offer->variation_ids) ? explode(',', $offer->variation_ids) : [];
			$variation_ids_get = !empty($offer->variation_ids_get) ? explode(',', $offer->variation_ids_get) : [];


			if ($offer->max_per_user >= 0) {
				$user_coupon_count = $this->db
					->where('user_id', $user_id)
					->where('coupon_code', $offer->discount_code)
					->where('payment_status', 'success')
					->count_all_results('tbl_order_details');

				if ($user_coupon_count >= $offer->max_per_user) {
					return array('success' => '0', 'msg' => "You have already used this coupon.");
				}
			}

			// --- Check no_coupon ---
			if ($offer->no_coupon >= 0) {
				$coupon_usage_count = $this->db
					->where('coupon_code', $offer->discount_code)
					->where('payment_status', 'success')
					->count_all_results('tbl_order_details');

				if ($coupon_usage_count >= $offer->no_coupon) {
					return array('success' => '0', 'msg' => "This coupon has reached its maximum usage limit.");
				}
			}

			// --- Check is_new_only ---
			if ((int)$offer->is_new_only === 1) {
				$user_order_exists = $this->db
					->where('user_id', $user_id)
					->where('payment_status', 'success')
					->count_all_results('tbl_order_details');

				if ($user_order_exists > 0) {
					return array('success' => '0', 'msg' => "This coupon is only valid for new customers.");
				}
			}

			// --- Proceed with original offer application ---
			$this->remove_existing_free_items($offer->id);

			$min_requirement_met = false;
			$min_value = (float)$offer->min_value;
			$cart_items = $this->cart->contents();

			$total_quantity = 0;
			$total_amount = 0;
			$eligible_items = [];

			foreach ($cart_items as $item) {
				if ($this->item_matches_offer($item, $offer)) {
					$total_quantity += $item['qty'];
					//$total_amount += ($item['price'] * $item['qty']);
					$line_total = $item['price'] * $item['qty'];
					$gst_rate = isset($item['gst']) ? $item['gst'] : 0;
					$base_price = $line_total / (1 + ($gst_rate / 100));
					$total_amount += $base_price;
					$eligible_items[] = $item;
				}
			}

			if ($offer->min_type == 'quantity') {
				$min_requirement_met = ($total_quantity >= $min_value);
				if (!$min_requirement_met) {
					$eligible_qty = $min_value - $total_quantity;
					$msg = "Please add $eligible_qty more qualifying items to use this coupon";
					return array('success' => '0', 'msg' => $msg);
				}
			} else {
				$min_requirement_met = ($total_amount >= $min_value);
				if (!$min_requirement_met) {
					$eligible_amt = priceFormatted($min_value - $total_amount);
					$msg = "Please add $eligible_amt worth of qualifying items to use this coupon";
					return array('success' => '0', 'msg' => $msg);
				}
			}

			$discount = 0;
			$discount_type = '';
			$free_products_added = array();

			switch ($offer->offer_value_type) {
				case 'percentage':
				$eligible_total = 0;
				foreach ($eligible_items as $item) {
					$line_total = $item['price'] * $item['qty'];
					$gst_rate = isset($item['gst']) ? $item['gst'] : 0;
					$base_price = $line_total / (1 + ($gst_rate / 100));
					$eligible_total += $base_price;
				}
				$discount = ($offer->offer_value / 100) * $eligible_total;
				$discount_type = 'percentage';
				break;
				case 'amount':
					$discount = $offer->offer_value;
					$discount_type = 'amount';
					break;

				case 'free':
					$discount = 0;
					$discount_type = 'free';
					$free_products_added = $this->add_free_products_to_cart($offer);
					break;
			}

			$payable_amt = $cart_amt - $discount;

			if ($offer->is_cashback>0 || $discount > 0 || !empty($free_products_added)) {
				if ($offer->is_cashback > 0) {
					$save_msg = $offer->title;
				} elseif ($offer->offer_value_type == 'free') {
					$save_msg = "You've got free products with this order";
				} else {
					$save_msg = str_replace('###', number_format($discount, 2), "You've saved ### on this order");
				}

				$cart_stats = $this->crud_model->get_cart_stats($discount);
				$payable_amt = price_format_decimal($cart_stats['payable_amt']);
				$disp_payable_amt = priceFormatted($cart_stats['payable_amt']);
				$total_saving = price_format_decimal($cart_stats['saving']);
				$total_saving_disp = priceFormatted($cart_stats['saving']);
				$taxes_total = price_format_decimal($cart_stats['taxes_total']);
				$m_taxes_total = price_format_decimal($cart_stats['m_taxes_total']);
				$taxes_total_disp = priceFormatted($cart_stats['taxes_total']);


				// Calculate initial totals
				$subtotal_incl_gst = 0;
				$total_gst = 0;

				foreach($cart_items as &$item) {
					if(isset($item['is_free_item']) && $item['is_free_item']) {
						$item['gst_amt'] = 0;
						continue;
					}

					$product_total = $item['price'] * $item['qty'];
					$item_gst = $product_total - ($product_total / (1 + ($item['gst']/100)));
					$item['gst_amt'] = $item_gst;

					$subtotal_incl_gst += $product_total;
					$total_gst += $item_gst;
				}

				// Calculate GST-exclusive amount
				$subtotal_excl_gst = $subtotal_incl_gst - $total_gst;

				// Apply discount to GST-exclusive amount
				$price_after_discount = $subtotal_excl_gst - $discount;
				if($price_after_discount < 0) $price_after_discount = 0;

				// Recalculate GST on discounted amount (maintaining same GST ratio)
				$final_gst = 0;
				if($subtotal_excl_gst > 0) {
					$gst_ratio = $total_gst / $subtotal_excl_gst;
					$final_gst = $price_after_discount * $gst_ratio;
				}

				$payable_amt = $price_after_discount + $final_gst + $delivery;

				$response = array(
					'success' => '1',
					'msg' => "Coupon Applied Successfully",
					'disp_discount' => priceFormatted($discount),
					'disp_payable_amt' => priceFormatted($payable_amt + $delivery),
					'coupon_id' => $offer->id,
					'coupon_code' => $offer->discount_code,
					'coupons_msg' => '',
					'discount_type' => $discount_type,
					'discount_value' => $offer->offer_value,
					'you_save_msg' => $save_msg,
					"price" => $cart_amt,
					"payable_amt" => price_format_decimal($payable_amt + $delivery),
					"discount" => $offer->offer_value_type == 'percentage' ? $offer->offer_value : 0,
					"discount_amt" => price_format_decimal($discount),
					"free_products_added" => $free_products_added,
					"total_saving_disp" => $total_saving_disp,
					"total_saving" => $total_saving,
					"taxes_total" => $taxes_total,
					"final_total" => (float)$m_taxes_total+(float)$cart_amt,
					"taxes_total_disp" => $taxes_total_disp,
					'variation_ids' => $variation_ids,
					'variation_ids_get' => $variation_ids_get,
				);
				return $response;
			} else {
				return array('success' => '0', 'msg' => "Coupon Code is Invalid");
			}
		} else {
			return array('success' => '0', 'msg' => "Coupon Code is Invalid");
		}
	}


 public function get_apply_coupon2($coupon_id){
	$user_id=$this->session->userdata('user_id') ? $this->session->userdata('user_id'):'0';
	$delivery="0";

    $cart_amt       = price_format_decimal($this->excl_total());
	$final_cart_amt=price_format_decimal($this->total());
	$total_mrp_total=price_format_decimal($this->mrp_total());

	$where=array('id' => $coupon_id,'status'=>1);
	$total_saving=price_format_decimal($total_mrp_total-$final_cart_amt);
	if($row=$this->common_model->selectByids($where,'tbl_coupon')){
		$row=$row[0];
        $coupon_code = $row->coupon_code;
		$where = array('user_id' => $user_id, 'coupon_id' => $row->id);
		$or_conditions = array('payment_status' => array('success', 'cod'));
		$count_use = $this->common_model->get_count_by_ids($where, 'tbl_order_details', $or_conditions);


		$where2 = array('coupon_id' => $row->id);
		$or_conditions2 = array('payment_status' => array('success', 'cod'));
		$no_coupon = $this->common_model->get_count_by_ids($where2, 'tbl_order_details', $or_conditions2);


		if($row->coupon_limit_use >= $count_use && $row->no_coupon > $no_coupon)  {

		   if($row->coupon_per!='0' || $row->coupon_amt!='0') {
				// for percentage coupons

				if($row->discount_type=='percentge'){
				   $coupon_amt =($row->coupon_per/100) * $cart_amt;
				}
				else{
				   $coupon_amt = $row->coupon_amt;
				}

				if($row->cart_status=='true'){
					if($cart_amt >= $row->coupon_cart_min){
						$payable_amt=$discount=0;
						if($row->max_amt_status=='true' && $row->discount_type=='percentge'){
							// count discount price after coupon apply;
							$discount=$coupon_amt;

							if($discount > $row->coupon_max_amt && $row->coupon_max_amt>0){
								$discount=$row->coupon_max_amt;
								$payable_amt=$cart_amt-$discount;
							}
							else{
								$payable_amt=round($cart_amt - $coupon_amt,2);
							}
						}
						else{
							$discount=$coupon_amt;
							$payable_amt=($cart_amt - $coupon_amt);
						}

						if($discount!=0){
						  $save_msg=str_replace('###',priceFormatted($discount), "YAY! You've saved  ### on this order");
						}

					if($discount==0){
						$response=array('success' => '0','msg' => "Invalid coupon code!");
					}
					else{
						$disp_discount=priceFormatted($discount);
						$payable_amountt=price_format_decimal($final_cart_amt-$discount)+number_format((float)$delivery, 2, '.', '');
						$disp_payable_amt=priceFormatted($payable_amountt);
						$total_saving_disp=priceFormatted($total_saving+$discount);
						$total_saving=price_format_decimal($total_saving+$discount);

					   $response=array('success' => '1','msg' => "Coupon Applied Successfully",'disp_discount' => $disp_discount,'disp_payable_amt' => $disp_payable_amt,'coupon_id' => $row->id,'coupon_code' => $coupon_code,'coupons_msg'=>'','you_save_msg' =>$save_msg, "price" => $cart_amt, "payable_amt" => price_format_decimal($final_cart_amt-$discount)+number_format((float)$delivery, 2, '.', ''),"discount" => $row->coupon_per,"discount_amt" => price_format_decimal($discount),"total_saving_disp" => $total_saving_disp,"total_saving" => $total_saving);

					}
				   }
				  else{
					 $eligible_amt=priceFormatted($row->coupon_cart_min-$cart_amt);
					 $response=array('success' => '0','msg' => "Please add items worth $eligible_amt or more to be eligible for this coupon!");
				  }
				}
				else{

					$payable_amt=$discount=0;

					if($row->max_amt_status=='true'){
						// count discount price after coupon apply;
						$discount=sprintf("%.2f", $coupon_amt);
						$discount_new=sprintf("%.2f", ((43/100) * 28.61));

						if($discount > $row->coupon_max_amt && $row->coupon_max_amt>0){
							$discount=$row->coupon_max_amt;
							$payable_amt=number_format((float)($cart_amt-$discount), 2, '.', '')+number_format((float)$delivery, 2, '.', '');
						}
						else{
							$payable_amt=number_format((float)($cart_amt - $coupon_amt), 2, '.', '')+number_format((float)$delivery, 2, '.', '');
						}
					}
					else{
						$discount=sprintf("%.2f", $coupon_amt);
						$payable_amt=number_format((float)$coupon_amt, 2, '.', '')+number_format((float)$delivery, 2, '.', '');
					}

					if($discount!=0){
						$save_msg=str_replace('###', priceFormatted($discount), "YAY! You've saved  ### on this order");
					}

					if($discount==0){
						$response=array('success' => '0','msg' => "Invalid coupon code!");
					}
					else{
						$total_saving_disp=priceFormatted($total_saving+$discount);
						$total_saving=price_format_decimal($total_saving+$discount);

						$response=array('success' => '1','msg' =>"Coupon Applied Successfully",'coupon_id' => $row->id,'coupon_code' => $coupon_code,'coupons_msg'=>'','you_save_msg' =>$save_msg, "price" => $cart_amt, "payable_amt" => price_format_decimal($final_cart_amt-$discount)+number_format((float)$delivery, 2, '.', ''),"discount" => $row->coupon_per,"discount_amt" => price_format_decimal($discount),"total_saving_disp" => $total_saving_disp,"total_saving" => $total_saving);

					}
				}
			}
			else{
				$response=array('success' => '0','msg' => "Coupon Code is Invalid");

			}


		}
		else{
			$response=array('success' => '0','msg' => "Limit for using this coupon is over",'count_use'=>$count_use ,'no_coupon'=>$no_coupon);
		}
	}
	else{
		$response=array('success' => '0','msg' => "Coupon Code is Invalid");
	}
  return $response;
 }

	public function get_cart_stats($discount_total) {
		$saving = 0;
		$taxes_total = 0;
        $m_taxes_total = 0;
		$final_total_excl = 0;
		$total_selling_price = 0;
		$total_product_mrp = 0;

		$cart_items_ = $this->crud_model->get_cart_product_details();
		$total_items = count($cart_items_);
		$discount_per_product = $total_items > 0 ? $discount_total / $total_items : 0;
		$final_total = 0;

		if (!empty($cart_items_)) {
			foreach ($cart_items_ as $cart_item) {
				$product_mrp = $cart_item['selling_price'];
				$product_qty = $cart_item['quantity'];

				$total_price_incl_gst = price_format_decimal($product_qty * $product_mrp);
				$gst_rate_decimal = $cart_item['gst'] / 100;
				$total_price_excl_gst = $total_price_incl_gst / (1 + $gst_rate_decimal);

				$discounted_price_excl_gst = max($total_price_excl_gst - $discount_per_product, 0);
				$gst_amt = $discounted_price_excl_gst * $gst_rate_decimal;

				$taxes_total += (float)$gst_amt;

				$gst_amt2 = $total_price_excl_gst * $gst_rate_decimal;
				$m_taxes_total += (float)$gst_amt2;

				$final_total_excl += $discounted_price_excl_gst;
				$final_total = price_format_decimal($final_total + ($discounted_price_excl_gst + $gst_amt));

				$saving += $cart_item['disc_amt'] * $product_qty;
				$total_selling_price += $product_mrp * $product_qty;
				$total_product_mrp += $cart_item['product_mrp'] * $product_qty;
			}
		}

		$payable_amt = price_format_decimal(round($final_total,2));

		return array(
			'discounted_price_excl_gst' => $final_total_excl,
			'payable_amt' => $payable_amt,
			'saving' => $saving+$discount_total,
			'total_selling_price' => $total_selling_price,
			'total_product_mrp' => $total_product_mrp,
			'taxes_total' => $taxes_total,
			'm_taxes_total' => $m_taxes_total,
		);
	}

	public function update_direct_order($data_transaction){
        $order_unique_id = $data_transaction["order_unique_id"];
        $order  = $this->common_model->getRowByWhereId('tbl_order_details','id,user_name,user_phone,order_unique_id,payable_amt,total_amt',array('order_unique_id'=>$order_unique_id));

        if ($data_transaction['payment_status'] == 'success') {
			  $data = array();
			  $data = array(
					'payment_status' => $data_transaction["payment_status"],
					'payment_id'     => $data_transaction["payment_id"],
					'remark'         => $data_transaction["remark"],
					'payment_method' => $data_transaction['payment_method']
			   );
			   $this->db->where('id', $order['id']);
			   $this->db->update('tbl_order_details', $data);
			   $this->crud_model->generate_og_invoice_number($order['id']);


			   /*wati_feedback - WhatsApp order confirmation removed
				 $sender_mobile=$order['user_phone'];
				 $user_name=$order['user_name'];
				 $order_number='#'.$order['order_unique_id'];
				 $total_price=indian_price($order['total_amt']);
				 $track_url="my-orders";

				 $template_name="herbal_order_placed";
				 $sender_mobile=$sender_mobile;
				 $wati_parameters = array();
				 $wati_parameters[] = array(
					'name' => "name",
					'value' => $user_name,
				  );
				  $wati_parameters[] = array(
					'name' => "order_number",
					'value' => $order_number,
				  );
				  $wati_parameters[] = array(
					'name' => "total_price",
					'value' => $total_price,
				  );

				  $wati_array = array();
				  $wati_array = array(
					'template_name'  => $template_name,
					'broadcast_name' => $template_name,
					'parameters' 	 => $wati_parameters,
				  );

				 $this->auth_model->send_wati_sms($sender_mobile,$wati_array);
				 wati_feedback*/
          }
		  else{
			$data = array();
			$data = array(
			   'payment_status' => $data_transaction["payment_status"],
			   'payment_id'     => $data_transaction["payment_id"],
			   'remark'         => $data_transaction["remark"],
               'payment_method' => $data_transaction['payment_method']
			);
			$this->db->where('id', $order['id']);
			$this->db->update('tbl_order_details', $data);
        }
        return true;
    }

	public function update_cashfree_order($data_transaction){
        $order_unique_id = $data_transaction["order_unique_id"];
        $order  = $this->common_model->getRowByWhereId('tbl_order_details','id,user_name,user_phone,order_unique_id,payable_amt,total_amt',array('order_unique_id'=>$order_unique_id));

        if ($data_transaction['payment_status'] == 'success') {
			  $data = array();
			  $data = array(
					'payment_status' => $data_transaction["payment_status"],
					'payment_id'     => $data_transaction["payment_id"],
					'remark'         => $data_transaction["remark"],
					'payment_method' => $data_transaction['payment_method']
			   );
			   $this->db->where('id', $order['id']);
			   $this->db->update('tbl_order_details', $data);
			   $this->crud_model->generate_og_invoice_number($order['id']);


			   /*wati_feedback - WhatsApp order confirmation removed
				 $sender_mobile=$order['user_phone'];
				 $user_name=$order['user_name'];
				 $order_number='#'.$order['order_unique_id'];
				 $total_price=indian_price($order['total_amt']);
				 $track_url="my-orders";

				 $template_name="herbal_order_placed";
				 $sender_mobile=$sender_mobile;
				 $wati_parameters = array();
				 $wati_parameters[] = array(
					'name' => "name",
					'value' => $user_name,
				  );
				  $wati_parameters[] = array(
					'name' => "order_number",
					'value' => $order_number,
				  );
				  $wati_parameters[] = array(
					'name' => "total_price",
					'value' => $total_price,
				  );

				  $wati_array = array();
				  $wati_array = array(
					'template_name'  => $template_name,
					'broadcast_name' => $template_name,
					'parameters' 	 => $wati_parameters,
				  );

				 $this->auth_model->send_wati_sms($sender_mobile,$wati_array);
				 wati_feedback*/
				/*wati_feedback*/

          }
		  elseif ($data_transaction['payment_status'] == 'cod') {
			  $data = array();
			  $data = array(
					'payment_status' => $data_transaction["payment_status"],
					'payment_id'     => $data_transaction["payment_id"],
					'remark'         => $data_transaction["remark"],
					'payment_method' => $data_transaction['payment_method']
			   );
			   $this->db->where('id', $order['id']);
			   $this->db->update('tbl_order_details', $data);
			   $this->crud_model->generate_og_invoice_number($order['id']);
		  }
		  elseif ($data_transaction['payment_status'] == 'payment_at_school') {
			  $data = array();
			  $data = array(
					'payment_status' => $data_transaction["payment_status"],
					'payment_id'     => $data_transaction["payment_id"],
					'remark'         => $data_transaction["remark"],
					'payment_method' => $data_transaction['payment_method']
			   );
			   $this->db->where('id', $order['id']);
			   $this->db->update('tbl_order_details', $data);
			   $this->crud_model->generate_og_invoice_number($order['id']);

			    /*wati_feedback*/
				//  $sender_mobile=$order['user_phone'];
				//  $user_name=$order['user_name'];
				//  $order_number='#'.$order['order_unique_id'];
				//  $total_price=indian_price($order['total_amt']);
				//  $track_url="my-orders";

				//  $template_name="herbal_order_placed";
				//  $sender_mobile=$sender_mobile;
				//  $wati_parameters = array();
				//  $wati_parameters[] = array(
				// 	'name' => "name",
				// 	'value' => $user_name,
				//   );
				//   $wati_parameters[] = array(
				// 	'name' => "order_number",
				// 	'value' => $order_number,
				//   );
				//   $wati_parameters[] = array(
				// 	'name' => "total_price",
				// 	'value' => $total_price,
				//   );

				//   $wati_array = array();
				//   $wati_array = array(
				// 	'template_name'  => $template_name,
				// 	'broadcast_name' => $template_name,
				// 	'parameters' 	 => $wati_parameters,
				//   );

				//  $this->auth_model->send_wati_sms($sender_mobile,$wati_array);
				/*wati_feedback*/
          }
		  else{
			$data = array();
			$data = array(
			   'payment_status' => $data_transaction["payment_status"],
			   'payment_id'     => $data_transaction["payment_id"],
			   'remark'         => $data_transaction["remark"],
               'payment_method' => $data_transaction['payment_method']
			);
			$this->db->where('id', $order['id']);
			$this->db->update('tbl_order_details', $data);
        }
        return true;
    }

    public function generate_og_invoice_number($order_id) {
        date_default_timezone_set('Asia/Kolkata');
        $curr_date = date("Y-m-d H:i:s");
        $currentYear = date('Y');

        $order_details = $this->common_model->getRowByWhereId('tbl_order_details', 'id,invoice_no,order_date', array('id' => $order_id));

        if ($order_details['invoice_no'] == NULL) {
            $order_date = date('Y-m-d', strtotime($order_details['order_date']));
            $inv_date = date('Y-m-d H:i:s', strtotime($order_details['order_date']));

			$delivery_day=date('m',strtotime($order_date. ' + 0 day'));
			$d_year=date('Y',strtotime($order_date. ' + 0 day'));
			if ($delivery_day>= 4) {
				$pre_year = (date($d_year)+1);
			}
			else {
				$pre_year = (date($d_year));
			}


            // Determine financial year based on order date
            $orderYear = date('Y', strtotime($order_date));
            $financialYearStart = date('Y-04-01', strtotime("$orderYear-04-01"));
            $financialYearEnd = date('Y-03-31', strtotime("$financialYearStart +1 year"));

            if ($order_date < $financialYearStart) {
                $orderYear--;
                $financialYearStart = date('Y-04-01', strtotime("$orderYear-04-01"));
                $financialYearEnd = date('Y-03-31', strtotime("$financialYearStart +1 year"));
            }

            // Fetch maximum invoice serial number for the financial year
            $query = $this->db->query("SELECT MAX(CAST(invoice_id AS SIGNED)) AS max_serial FROM order_user_invoice WHERE DATE(invoice_date) >= '$financialYearStart' AND DATE(invoice_date) <= '$financialYearEnd' FOR UPDATE");
            $row = $query->row_array();
            $maxSerial = $row['max_serial'];
            $newSerial = ($maxSerial !== null) ? (int) $maxSerial + 1 : 1;
            $invoice_id = $newSerial;

            // Construct invoice number
            $vendor_pre = 'MW';
            $YearStart = date('y', strtotime($financialYearStart));
            $YearEnd = date('y', strtotime($financialYearEnd));

			$vendor_type    = $vendor_pre.'/'.$YearStart.'-'.$YearEnd.'/';
			$invoiceNumber  = $vendor_type . $invoice_id;

            try {
                $check = $this->db->query("SELECT id FROM tbl_order_details WHERE id='$order_id' AND invoice_no='$invoiceNumber' FOR UPDATE");

                if ($check->num_rows() == 0) {
                    $data_inv = array();
                    $data_inv = array(
                        'order_id' => $order_id,
                        'vendor_id' => NULL,
                        'year' => $pre_year,
                        'user_invoice' => $invoiceNumber,
                        'invoice_id' => $newSerial,
                        'invoice_date' => $inv_date
                    );

                    $this->db->insert('order_user_invoice', $data_inv);
                    $affected_rows = $this->db->affected_rows();

                    if ($affected_rows > 0) {
                        $data = array();
                        $data = array(
                            'invoice_no' => $invoiceNumber,
                            'invoice_date' => $inv_date
                        );

                        $this->db->where('id', $order_id);
                        $this->db->update('tbl_order_details', $data);

                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            return $this->generate_og_invoice_number($order_id);
                        } else {
                            $this->db->trans_commit();
                            return $invoiceNumber;
                        }
                    } else {
                        $this->db->trans_rollback();
                        return $this->generate_og_invoice_number($order_id);
                    }
                } else {
                    return $this->generate_og_invoice_number($order_id);
                }
            } catch (Exception $e) {
                if ($e->getCode() == 1062 && $retry_count < 3) {
                    return $this->generate_og_invoice_number($order_id);
                } else {
                    throw new Exception('Duplicate invoice Number issue, try again');
                }
            }
        } else {
            return $order_details['invoice_no'];
        }
    }

	public function get_order_unique_id(){
        // Generate 9-digit order ID: ymd (6 digits) + last 3 digits of timestamp
        // Format: yymmdd + last 3 digits of seconds/microseconds
        $date_part = date('ymd'); // 6 digits: year(2) + month(2) + day(2)
        $time_part = substr(date('His'), -3); // Last 3 digits of time (HHmmss)
        $final_code = $date_part . $time_part; // Total: 9 digits
        return $final_code;
    }


    public function subscribe_newsletter(){
        $this->db->trans_start();

        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[newsletter_enquiry.email]', array(
           'valid_email' => 'Please enter a valid email address.',
           'is_unique' => 'This email address is subscribed with us.'
        ));

       if ($this->form_validation->run() == FALSE){
            $errors = array(
               'email' 	=> form_error('email')
           );
           $errors_ = array_map('strip_tags', array_filter($errors));
           $allErrors = implode('<br> ', $errors_);

           $resultpost = array(
               "status" => 400,
               "message" => $allErrors,
               "errors" => $errors,
           );
       } else {
           $curr_date  = date("Y-m-d H:i:s");
           $ip_address = $this->input->ip_address();
           $data=array();
           $data['email']         = clean_and_escape($this->input->post('email'));
           $data['ip_address']    = $ip_address;
           $data['created_at']    = date("Y-m-d H:i:s");
           if($this->db->insert('newsletter_enquiry', $data)){
             $this->db->trans_complete();
               $url = $this->agent->referrer();
               $resultpost = array(
                   "status" => 200,
                   "message" => 'Thank you for subscribing!',
                   "url" =>$url,
               );
           }
           else{
               $resultpost = array(
                   "status" => 400,
                   "message" => 'There is some issue while adding',
               );

           }
       }

       if ($this->db->trans_status() === FALSE) {
           $resultpost = array(
               "status" => 400,
               "message" => 'There is some issue while adding',
           );
           $this->db->trans_rollback();
       } else {
           $this->db->trans_commit();
       }

       return simple_json_output($resultpost);
   }



    public function send_order_confirmation_mail($order_id) {
        $order = $this->crud_model->get_orders_details_by_id($order_id);
		if($order['is_mail_sent']==0){
			$email=$order['user_email'];
			$subject="Your Order #".$order['order_unique_id']." Has Been Placed Successfully";
			$attachment='';
			$page_data['data'] = $order;
			$message = $this->load->view('emails/order_summary',$page_data,TRUE);
			
			$mail_sent = FALSE;
			
			// Prefer vendor Notifications SMTP if configured (falls back to existing mail sender)
			$vendor_id = NULL;
			$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
			if (strpos($http_host, ':') !== false) {
				$http_host = substr($http_host, 0, strpos($http_host, ':'));
			}
			if (!empty($http_host) && strpos($http_host, 'localhost') === false && strpos($http_host, '127.0.0.1') === false) {
				$this->load->model('Erp_client_model');
				$vendor = $this->Erp_client_model->getClientByDomain($http_host);
				if ($vendor && isset($vendor['id'])) {
					$vendor_id = (int)$vendor['id'];
				}
			}
			
			if ($vendor_id) {
				$this->load->library('Notification_sender');
				$vendor_domain = '';
				if (!empty($vendor['domain'])) {
					$vendor_domain = trim((string)$vendor['domain'], " \t\n\r\0\x0B./");
				}
				// Build a richer vars set so templates can render items/shipping/student/school details.
				$vars = [
					'order_id' => (int)$order_id,
					'order_unique_id' => $order['order_unique_id'] ?? '',
					'order_date' => $order['order_date'] ?? '',
					'payment_method' => $order['payment_method'] ?? '',
					'payment_status' => $order['payment_status'] ?? '',

					// Common aliases (match template token names)
					'user_name' => $order['user_name'] ?? ($order['user_fullname'] ?? ''),
					'user_email' => $email,
					'user_phone' => $order['user_mobile'] ?? ($order['user_phone'] ?? ''),

					'customer_name' => $order['user_name'] ?? ($order['user_fullname'] ?? ''),
					'email_to' => $email,
					'mobile' => $order['user_mobile'] ?? ($order['user_phone'] ?? ''),

					'payable_amt' => $order['payable_amt'] ?? ($order['total_amt'] ?? ''),
					'order_amount' => $order['payable_amt'] ?? ($order['total_amt'] ?? ''),
					'total_amt' => $order['total_amt'] ?? '',
					'delivery_charge' => $order['delivery_charge'] ?? '',
					'discount_amt' => $order['discount_amt'] ?? ($order['discount_amt'] ?? ''),
					'currency_code' => $order['currency_code'] ?? ($order['currency'] ?? ''),
					'invoice_no' => $order['invoice_no'] ?? '',

					'subject_default' => $subject,
				];

				// Shipping vars from get_orders_details_by_id()
				$ship = isset($order['shipping']) && is_array($order['shipping']) ? $order['shipping'] : [];
				$vars['shipping_name'] = (string)($ship['name'] ?? ($vars['user_name'] ?? ''));
				$vars['shipping_phone'] = (string)($ship['mobile_no'] ?? ($vars['user_phone'] ?? ''));
				$vars['shipping_address'] = (string)($ship['address'] ?? '');
				$vars['shipping_city'] = (string)($ship['city'] ?? '');
				$vars['shipping_state'] = (string)($ship['state'] ?? '');
				$vars['shipping_pincode'] = (string)($ship['pincode'] ?? '');

				// Build order_items HTML from tbl_order_items (ensures size/qty/image are included)
				$this->load->helper('common_helper');
				$order_items_html = '';
				$total_qty = 0;
				$subtotal = 0;
				$items = $this->db->select('product_id, product_title, product_qty, product_price, total_price, variation_name, thumbnail_img, order_type, f_name, grade, school_id, branch_id, grade_id, board_id')
					->from('tbl_order_items')->where('order_id', (int)$order_id)->order_by('id', 'ASC')->get()->result_array();
				foreach ($items as $it) {
					$qty = (int)($it['product_qty'] ?? 1);
					if ($qty <= 0) $qty = 1;
					$total_qty += $qty;

					$unit_price = (float)($it['product_price'] ?? 0);
					$row_total = (float)($it['total_price'] ?? 0);
					if ($row_total <= 0 && $unit_price > 0) $row_total = $unit_price * $qty;
					$subtotal += $row_total;

					$name = (string)($it['product_title'] ?? '');
					$size = (string)($it['variation_name'] ?? '');

					$img_url = '';
					$thumb = (string)($it['thumbnail_img'] ?? '');
					if ($thumb !== '') {
						// Always use vendor storefront domain for email images.
						if (stripos($thumb, 'http://') === 0 || stripos($thumb, 'https://') === 0) {
							$u = @parse_url($thumb);
							$path = isset($u['path']) ? $u['path'] : '';
							$query = isset($u['query']) ? ('?' . $u['query']) : '';
							$img_url = ($vendor_domain !== '' ? ('https://' . $vendor_domain) : '') . $path . $query;
						} else {
							$img_url = ($vendor_domain !== '' ? ('https://' . $vendor_domain . '/') : rtrim(base_url(), '/') . '/') . ltrim($thumb, '/');
						}
					} else {
						$pid = (int)($it['product_id'] ?? 0);
						if ($pid > 0 && $this->db->table_exists('product_images')) {
							$img = $this->db->select('image')->from('product_images')->where('product_id', $pid)->order_by('is_main', 'DESC')->order_by('id', 'ASC')->limit(1)->get()->row_array();
							if (!empty($img['image'])) $img_url = ($vendor_domain !== '' ? ('https://' . $vendor_domain . '/') : rtrim(base_url(), '/') . '/') . ltrim($img['image'], '/');
						}
					}
					// Gmail commonly blocks mixed-content http images; prefer https when possible.
					if (stripos($img_url, 'http://') === 0) $img_url = 'https://' . substr($img_url, 7);

					$img_cell = '';
					if ($img_url !== '') {
						$img_cell = '<img src="' . htmlspecialchars($img_url) . '" width="48" height="48" style="display:block;object-fit:cover;border:1px solid #e5e7eb;" alt="">';
					}

					$order_items_html .= '<tr>'
						. '<td style="padding:8px;border-bottom:1px solid #f1f5f9;">'
						. '<table cellpadding="0" cellspacing="0" border="0"><tr>'
						. '<td style="padding-right:10px;vertical-align:top;">' . $img_cell . '</td>'
						. '<td style="vertical-align:top;"><div style="font-weight:600;">' . htmlspecialchars($name) . '</div></td>'
						. '</tr></table>'
						. '</td>'
						. '<td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . htmlspecialchars($size) . '</td>'
						. '<td align="center" style="padding:8px;border-bottom:1px solid #f1f5f9;">' . (int)$qty . '</td>'
						. '<td align="right" style="padding:8px;border-bottom:1px solid #f1f5f9;">' . htmlspecialchars((string)$row_total) . '</td>'
						. '</tr>';
				}
				$vars['order_items'] = $order_items_html;
				$vars['total_qty'] = $total_qty;
				$vars['subtotal'] = $subtotal;

				// School/Board/Grade/Child
				$vars['school_name'] = '';
				$vars['board_name'] = '';
				$vars['grade_name'] = '';
				$vars['child_name'] = '';
				$vars['child_class'] = '';
				$vars['child_section'] = '';
				$school_id_for_board = 0;

				// children_data may exist on tbl_order_details
				$od = $this->db->select('children_data')->from('tbl_order_details')->where('id', (int)$order_id)->limit(1)->get()->row_array();
				if (!empty($od['children_data'])) {
					$parsed = json_decode((string)$od['children_data'], true);
					if (is_array($parsed) && !empty($parsed) && is_array($parsed[0])) {
						$vars['child_name'] = (string)($parsed[0]['name'] ?? ($parsed[0]['childName'] ?? ''));
						$vars['child_class'] = (string)($parsed[0]['grade'] ?? ($parsed[0]['class'] ?? ''));
						$vars['child_section'] = (string)($parsed[0]['section'] ?? '');
					}
				}

				// Fallbacks from items (uniform/bookset)
				foreach ($items as $it) {
					if ($vars['child_name'] === '' && !empty($it['f_name'])) {
						$vars['child_name'] = trim((string)$it['f_name']);
						$vars['child_class'] = (string)($it['grade'] ?? '');
					}
					if ($vars['school_name'] === '') {
						$branch_id = (int)($it['branch_id'] ?? 0);
						$school_id = (int)($it['school_id'] ?? 0);
						if ($school_id_for_board <= 0 && $school_id > 0) $school_id_for_board = $school_id;
						if ($branch_id > 0 && $this->db->table_exists('erp_school_branches')) {
							$br = $this->db->select('sb.branch_name, s.school_name')
								->from('erp_school_branches sb')
								->join('erp_schools s', 's.id = sb.school_id', 'left')
								->where('sb.id', $branch_id)->limit(1)->get()->row_array();
							if (!empty($br['school_name'])) $vars['school_name'] = (string)$br['school_name'];
						} elseif ($school_id > 0 && $this->db->table_exists('erp_schools')) {
							$s = $this->db->select('school_name')->from('erp_schools')->where('id', $school_id)->limit(1)->get()->row_array();
							if (!empty($s['school_name'])) $vars['school_name'] = (string)$s['school_name'];
						}
					}
					if ($vars['child_name'] !== '' && $vars['school_name'] !== '') break;
				}

				// If board_name is still missing but school_id exists, pick first board mapped to school.
				if ($vars['board_name'] === '' && $school_id_for_board > 0 && $this->db->table_exists('erp_school_boards_mapping') && $this->db->table_exists('erp_school_boards')) {
					$b = $this->db->select('sb.board_name')
						->from('erp_school_boards_mapping sbm')
						->join('erp_school_boards sb', 'sb.id = sbm.board_id', 'left')
						->where('sbm.school_id', $school_id_for_board)
						->limit(1)->get()->row_array();
					if (!empty($b['board_name'])) $vars['board_name'] = (string)$b['board_name'];
				}

				$eventRes = $this->notification_sender->sendEvent($vendor_id, 'order_placed', $vars);
				$mail_sent = !empty($eventRes['results']['email']['success']);

				// If no master template mapping exists for email, fallback to vendor SMTP sendEmail
				if (!$mail_sent) {
					$res = $this->notification_sender->sendEmail($vendor_id, $email, $subject, $message);
					$mail_sent = !empty($res['success']);
				}
			}
			
			if (!$mail_sent) {
				$mail_sent = (bool)$this->auth_model->sent_mail_attach($message,$email,$subject,$attachment);
			}
			
			if($mail_sent){
			   $data = array();
			   $data = array(
					'is_mail_sent' => 1,
					'is_mail_date' => date('Y-m-d H:i:s'),
				);
				$this->db->where('id',$order_id);
				$this->db->update('tbl_order_details',$data);
			}
		}

    }

    public function get_order($order_no){
        $this->db->select('*');
        $this->db->where('order_unique_id', $order_no);
        $this->db->limit(1);
        $query = $this->db->get('tbl_order_details')->row_array();


        $dataLayer = [
            'event' => 'transaction',
            'ecommerce' => [
                'purchase' => [
                    'actionField' => [
                        'id' => $query['payment_id'],
                        'revenue' => $query['total_amt'],
                        'tax' => $query['gst_total'],
                        'shipping' => $query['delivery_charge'],
                        'coupon' => $query['discount_amt'],
                    ],
                ]
            ]
        ];


        $items = [];
        $items_arr = $this->db->where('order_id', $query['id'])->get('tbl_order_items')->result_array();

        foreach($items_arr as $arr) {
            $cat_id = explode(',', $arr['category_id']);

            $category = '';
            foreach($cat_id as $i => $cat) {
                $catArr = $this->common_model->getRowById('categories','name',$cat);
                if (is_array($catArr) && isset($catArr['name'])) {
                    $category = ($i == 0) ? $catArr['name'] : ', ' . $catArr['name'];
                } else {
                    // If getRowById returns a string (the name directly) or invalid data
                    $catName = is_array($catArr) ? (isset($catArr['name']) ? $catArr['name'] : '') : (is_string($catArr) ? $catArr : '');
                    $category = ($i == 0) ? $catName : ', ' . $catName;
                }
            }

            $items[] = array(
                'id' => $arr['product_id'],
                'name' => $arr['product_title'],
                'brand' => 'Rajasthan Aushdhalaya',
                'category' => $category,
                'price' => $arr['product_price'],
                'quantity' => $arr['product_qty'],
            );
        }

        $dataLayer['ecommerce']['purchase']['products'] = $items;
        return $dataLayer;

    }


	public function get_faq_list(){

        $query = $this->db->query("SELECT id,question,answer FROM faq WHERE status='1' ORDER BY id ASC");
        if (!empty($query)) {
            foreach ($query->result_array() as $row) {
			$resultdata[] = array(
				"id"   		=> $row['id'],
				"question" 	=> $row['question'],
				"answer" 	=> $row['answer'],
			);
          }
        }
        return $resultdata;
    }



    public function appointment_enquiries() {
        $resultpost = array("status" => 400);
        $required_fields = array('date', 'time', 'name', 'phone');
        foreach ($required_fields as $field) {
            if (empty($this->input->post($field))) {
                $resultpost['message'] = "$field is required";
                return simple_json_output($resultpost);
            }
        }

        if (empty($this->input->post('condition'))) {
            $resultpost['message'] = "At least one condition is required";
            return simple_json_output($resultpost);
        }

        if (!preg_match('/^[0-9]{10}$/', $this->input->post('phone'))) {
            $resultpost['message'] = "Enter a valid mobile number";
            return simple_json_output($resultpost);
        }

        $data = array(
            'condition' => implode(',', $this->input->post('condition')),
            'date' => html_escape($this->input->post('date')),
            'time' => html_escape($this->input->post('time')),
            'name' => html_escape($this->input->post('name')),
            'location' => html_escape($this->input->post('location')),
            'phone' => html_escape($this->input->post('phone')),
            'created_at' => date("Y-m-d H:i:s")
        );

        if ($this->db->insert('temp_booking', $data)) {
            $resultpost['status'] = 200;
            $resultpost['message'] = "Appointment enquiry submitted successfully";
        } else {
            $resultpost['message'] = "Database error occurred";
        }

        return simple_json_output($resultpost);
    }

    public function update_cashfree_wallet_order($data_transaction, $user_id){
      $current_date = date('Y-m-d H:i:s');
          $order_unique_id = $data_transaction["order_unique_id"];
          $order  = $this->common_model->getRowByWhereId('wallet_transactions','id,user_name,user_phone,order_unique_id,amount',array('order_unique_id'=>$order_unique_id));

          if ($data_transaction['payment_status'] == 'success') {
  			  $data = array();
  			  $data = array(
  					'payment_status' => 'success',
					'is_complete' => '1',
  					'payment_id'     => $data_transaction["payment_id"],
  			   ); 
  			   $this->db->where('id', $order['id']);
  			   $this->db->update('wallet_transactions', $data); 

          $amount = $order['amount'];
          $wallet = $this->db->query("SELECT id, balance FROM wallets WHERE user_id = '$user_id' LIMIT 1")->row_array();
          if ($wallet) {
              $new_balance = $wallet['balance'] + $amount;
              $this->db->where('user_id', $user_id);
              $this->db->update('wallets', array(
                  'balance'    => $new_balance,
                  'updated_at' => $current_date
              ));
          } else {
              $this->db->insert('wallets', array(
                  'user_id'    => $user_id,
                  'balance'    => $amount,
                  'updated_at' => $current_date
              ));
          }

            }
          return true;
      }

      public function get_wallet_balance($user_id)
      {
          $credit_sum = $this->db->query("
              SELECT SUM(amount) as total
              FROM wallet_transactions
              WHERE user_id = ?
                  AND type = 'credit'
                  AND payment_status = 'success'
                  AND is_complete = 1
                  AND is_expired = 0
                  AND (expiry_date IS NULL OR expiry_date > CONVERT_TZ(NOW(), '+00:00', '+05:30'))
          ", [$user_id])->row()->total;

          $debit_sum = $this->db->query("
              SELECT SUM(amount) as total
              FROM wallet_transactions
              WHERE user_id = ?
                  AND type = 'debit'
                  AND payment_status = 'success'
                  AND is_complete = 1
          ", [$user_id])->row()->total;

          // echo $this->db->last_query();

          $balance = round(($credit_sum ?? 0) - ($debit_sum ?? 0), 2);

          return $balance;
      }

      public function get_wallet_transactions($user_id)
      {
          $transaction_list = array();

          $query_transaction = $this->db->query("
              SELECT id, wallet_id, type, amount, description, order_id, order_unique_id, created_at, expiry_date, is_expired
              FROM wallet_transactions
              WHERE user_id = '$user_id' AND payment_status = 'success' AND is_complete = 1
              ORDER BY id DESC
          ");

          foreach ($query_transaction->result_array() as $item) {
              $is_expired = 0;
              $expiry_label = '';
              $background_color = '#ffffff';

              if ($item['type'] === 'credit' && !empty($item['expiry_date'])) {
                  $expiry_date_obj = new DateTime($item['expiry_date'], new DateTimeZone('Asia/Kolkata'));
                  $expiry_date_obj->setTime(23, 59); // end of the day

                  $formatted_expiry = $expiry_date_obj->format("d M 'y \a\\t h:i a");

                  if ((int)$item['is_expired'] === 1 || $expiry_date_obj->getTimestamp() < time()) {
                      $expiry_label = 'Expired on ' . $formatted_expiry;
                      $is_expired = 1;
                      $background_color = '#edf2f7';
                  } else {
                      $expiry_label = 'Expiry ' . $formatted_expiry;
                      $is_expired = 0;
                      $background_color = '#ffffff';
                  }
              }

              $transaction_list[] = array(
                  'id' => $item['id'],
                  'wallet_id' => $item['wallet_id'],
                  'type' => $item['type'],
                  'amount' => $item['amount'],
                  'description' => $item['description'],
                  'order_id' => ($item['order_id']) ? $item['order_id'] : '',
                  'order_unique_id' => ($item['type'] === 'debit') ? $item['order_unique_id'] : '',
                  'is_redirect' => ($item['type'] === 'debit') ? 1 : 0,
                  'url' => base_url() . 'my-order/' . $item['order_unique_id'],
                  'font_color' => ($item['type'] === 'debit') ? 'black' : 'green',
                  'created_at' => date('d M, Y h:i:s a', strtotime($item['created_at'])),
                  'is_expired' => $is_expired,
                  'background_color' => $background_color,
                  'expiry_label' => $expiry_label,
              );
          }

          return $transaction_list;
      }

      public function get_grades() 
      {
        $grades = $this->db->select('id, name')->from('erp_textbook_grades')->where('status', 'active')->order_by('name', 'ASC')->get()->result_array();
        return $grades;
      }


}
