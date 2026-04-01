<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**

*

*/

class Api_model extends CI_Model
{
    private $res_setting = null;

    public function __construct()
    {
      $CI =& get_instance();

      $CI->load->model('Setting_model', 'settings');

      $this->res_setting = $CI->settings->get_details();

    }

    // Today's deal products
    public function todays_deals($limit='', $start='', $brands='',$order_by=''){

      $curr_date=strtotime(date('d-m-Y'));

      $where= array('product.today_deal_date' => $curr_date,'product.status' => 1);

      $this->db->select('product.*');
      $this->db->select('cat.category_name');
      $this->db->select('sub_cat.sub_category_name');
      $this->db->from('tbl_product product');
      $this->db->where($where); 
      $this->db->join('tbl_category cat','cat.id = product.category_id','LEFT');
      $this->db->join('tbl_sub_category sub_cat','sub_cat.id = product.sub_category_id','LEFT');

      if($limit!=0 OR $limit!=''){
        $this->db->limit($limit, $start);
      }

      if($order_by !='' ){
        if(strcmp($order_by, 'low-high')==0){
          $this->db->order_by("product.selling_price", "ASC");
        }
        else if(strcmp($order_by, 'high-low')==0){
          $this->db->order_by("product.selling_price", "DESC");
        }
        else if(strcmp($order_by, 'top')==0){
          $this->db->order_by("product.total_sale", "DESC");
        }
        else if(strcmp($order_by, 'newest')==0){
          $this->db->order_by("product.id", "DESC");
        }

      }
      
      // echo $this->db->last_query();
      return $this->db->get()->result();

    }

    // banner wise products list
    public function products_by_banner($banner_id, $limit='', $start='', $brands='',$min='', $max='',$order_by=''){

      $this->db->select('product_ids');
      $this->db->from('tbl_banner'); 
      $this->db->where_in('id', $banner_id);
      $res=$this->db->get()->result();

      $ids=explode(',', $res[0]->product_ids);

      $this->db->select('product.*');
      $this->db->from('tbl_product product'); 

      if($min!='' && $max!=''){
        $this->db->where('product.`selling_price` BETWEEN '.$min.' AND '.$max);
      }

      if($brands!=''){
        $brand_ids=explode(',', $brands);
        $this->db->where_in('product.brand_id', $brand_ids);
      }

      $this->db->where_in('id', $ids);

      if($limit!=0 OR $limit!=''){
        $this->db->limit($limit, $start);
      }

      if($order_by !='' ){
        if(strcmp($order_by, 'low-high')==0){
          $this->db->order_by("product.selling_price", "ASC");
        }
        else if(strcmp($order_by, 'high-low')==0){
          $this->db->order_by("product.selling_price", "DESC");
        }
        else if(strcmp($order_by, 'top')==0){
          $this->db->order_by("product.total_sale", "DESC");
        }
        else if(strcmp($order_by, 'newest')==0){
          $this->db->order_by("product.id", "DESC");
        }

      }
      else{
        $this->db->order_by("product.id", "DESC");
      }

      return $this->db->get()->result();
    }

    // offer wise products list
    public function products_by_offer($offer_id, $limit='', $start='', $brands='',$min='', $max='',$order_by=''){

      $where = array('product.offer_id ' => $offer_id , 'product.status ' => '1');

      $this->db->select('product.*');
      $this->db->from('tbl_product product'); 
      $this->db->where($where);
      if($brands!=''){
        $ids=explode(',', $brands);
        $this->db->where_in('product.brand_id', $ids);
      }

      if($min!='' && $max!=''){
        $this->db->where('product.`selling_price` BETWEEN '.$min.' AND '.$max);
      }

      if($limit!=0 OR $limit!=''){
        $this->db->limit($limit, $start);
      }

      if($order_by !='' ){
        if(strcmp($order_by, 'low-high')==0){
          $this->db->order_by("product.selling_price", "ASC");
        }
        else if(strcmp($order_by, 'high-low')==0){
          $this->db->order_by("product.selling_price", "DESC");
        }
        else if(strcmp($order_by, 'top')==0){
          $this->db->order_by("product.total_sale", "DESC");
        }
        else if(strcmp($order_by, 'newest')==0){
          $this->db->order_by("product.id", "DESC");
        }

      }
      else{
        $this->db->order_by("product.id", "DESC");
      }

      return $this->db->get()->result();
    }

    // brand wise products list
    public function products_by_brand($brand_id){

      $where = array('product.brand_id ' => $brand_id , 'product.status ' => '1', 'brand.status ' => '1');

      $this->db->select('product.*');
      $this->db->select('brand.brand_name');
      $this->db->from('tbl_product product');
      $this->db->where($where); 
      $this->db->join('tbl_brands brand','brand.id = product.brand_id','LEFT');
      return $this->db->get()->result();
    }


    // categry wise products list
    public function productList_cat_sub($cat_id, $sub_cat_id=0, $limit='', $start='', $brands='',$min='', $max='',$order_by=''){

      if($sub_cat_id==0 || $sub_cat_id==''){
        $where = array('product.category_id ' => $cat_id , 'product.status ' => '1', 'cat.status ' => '1');
      }
      else{
        $where = array('product.category_id ' => $cat_id,'product.sub_category_id ' => $sub_cat_id , 'product.status ' => '1', 'cat.status ' => '1', 'sub_cat.status ' => '1');
      }

      $this->db->select('product.*');
      $this->db->select('cat.category_name');
      $this->db->select('sub_cat.sub_category_name');
      $this->db->from('tbl_product product');
      $this->db->join('tbl_category cat','cat.id = product.category_id','LEFT');
      $this->db->join('tbl_sub_category sub_cat','sub_cat.id = product.sub_category_id','LEFT');
      
      if($brands!=''){
        $ids=explode(',', $brands);
        $this->db->where_in('product.brand_id', $ids);
      }

      $this->db->where($where); 

      if($min!='' && $max!=''){
        $this->db->where('product.`selling_price` BETWEEN '.$min.' AND '.$max);
      }

      

      if($limit!=0){
        $this->db->limit($limit, $start);
      }

      if($order_by !='' ){
        if(strcmp($order_by, 'low-high')==0){
          $this->db->order_by("product.selling_price", "ASC");
        }
        else if(strcmp($order_by, 'high-low')==0){
          $this->db->order_by("product.selling_price", "DESC");
        }
        else if(strcmp($order_by, 'top')==0){
          $this->db->order_by("product.total_sale", "DESC");
        }
        else if(strcmp($order_by, 'newest')==0){
          $this->db->order_by("product.id", "DESC");
        }

      }
      else{
        $this->db->order_by("product.id", "DESC");
      }

      // echo $this->db->last_query();

      return $this->db->get()->result();
    }

    // product search
    public function product_search($keyword, $limit='', $start='',$order_by=''){

      $where=array('product.status' => '1','cat.status' => '1','sub_cat.status' => '1');


      $this->db->select('product.*');
      $this->db->select('cat.category_name');
      $this->db->select('sub_cat.sub_category_name');
      $this->db->from('tbl_product product');
      $this->db->join('tbl_category cat','cat.id = product.category_id','LEFT');
      $this->db->join('tbl_sub_category sub_cat','sub_cat.id = product.sub_category_id','LEFT');
      $this->db->where($where);
      
      $this->db->group_start();
      $this->db->like('product.product_title',$keyword);
      $this->db->or_like('product.product_slug',$keyword);
      $this->db->or_like('product.color',$keyword);
      $this->db->or_like('cat.category_name',$keyword);
      $this->db->or_like('sub_cat.sub_category_name',$keyword);
      $this->db->group_end();
      

      if($limit!=0){
        $this->db->limit($limit, $start);
      }

      if($order_by !='' ){
        if(strcmp($order_by, 'low-high')==0){
          $this->db->order_by("product.selling_price", "ASC");
        }
        else if(strcmp($order_by, 'high-low')==0){
          $this->db->order_by("product.selling_price", "DESC");
        }
        else if(strcmp($order_by, 'top')==0){
          $this->db->order_by("product.total_sale", "DESC");
        }
        else if(strcmp($order_by, 'newest')==0){
          $this->db->order_by("product.id", "DESC");
        }

      }
      else{
        $this->db->order_by("product.id", "DESC");
      }

      // echo $this->db->last_query();

      return $this->db->get()->result();

    }

	
    // categry list
    public function category_list(){

      $this->db->select('*');
      $this->db->from('tbl_category'); 
      $this->db->where('status', '1'); 
      $this->db->order_by($this->res_setting->api_cat_order_by, $this->res_setting->api_cat_post_order_by);
      return $this->db->get()->result();
    }

    //brands list
    public function brand_list(){

      $this->db->select('*');
      $this->db->from('tbl_brands'); 
      $this->db->where('status', '1');
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->result();
    }

    // offers list
    public function offers_list(){

      $this->db->select('*');
      $this->db->from('tbl_offers'); 
      $this->db->where('status', '1'); 
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->result();
    }

    // banner list
    public function banner_list(){

      $this->db->select('*');
      $this->db->from('tbl_banner'); 
      $this->db->where('status', '1'); 
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->result();
    }

    // coupon list
    public function coupon_list(){

      $this->db->select('*');
      $this->db->from('tbl_coupon'); 
      $this->db->where('status', '1'); 
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->result();
    }

    // product list
    public function product_list(){

      $this->db->select('*');
      $this->db->from('tbl_product'); 
      $this->db->where('status', '1'); 
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->result();
    }


    // my order list

    public function get_my_orders($user_id){
       $query_staff    = $this->db->query("SELECT * FROM tbl_order_details WHERE user_id='$user_id' AND order_status!='-1' AND payment_status!='pending' order by id desc");
      return $query_staff->result();
      
    }

    // my single order
    
    public function tbl_status_title($status_id){

      $where = array('id' => $status_id);

      $this->db->select('*');
      $this->db->from('tbl_status_title');
      $this->db->where($where);
      $this->db->order_by('title', 'ASC');
      return $this->db->get()->row();

    }
    
    public function tbl_product_img($product_id){

      $where = array('id' => $product_id);
      $this->db->select('featured_image');
      $this->db->from('tbl_product');
      $this->db->where($where);
      return $this->db->get()->row();

    }

    public function get_order($order_unique_id){

      $where = array('order_unique_id' => $order_unique_id,);

      $this->db->select('*');
      $this->db->from('tbl_order_details');
      $this->db->join('tbl_order_items','tbl_order_details.id = tbl_order_items.order_id','LEFT');
      $this->db->where($where);
      $this->db->order_by('tbl_order_items.id', 'DESC');
      return $this->db->get()->result();

    }

    // my order's products list

    public function get_order_product($order_id, $product_id){

      $where = array('order_id' => $order_id, 'product_id' => $product_id);

      $this->db->select('*');
      $this->db->from('tbl_order_details');
      $this->db->join('tbl_order_items','tbl_order_details.id = tbl_order_items.order_id','LEFT');
      $this->db->where($where);
      $this->db->order_by('tbl_order_items.id', 'DESC');
      return $this->db->get()->result();

      
    }


    public function get_wishlist($user_id){

        $where = array('user_id' => $user_id);

        $this->db->select('wishlist.*');
        $this->db->select('product.product_title,product.product_slug, product.featured_image, product.product_mrp, product.selling_price, product.you_save_amt, product.delivery_charge,product.max_unit_buy');
        $this->db->from('tbl_wishlist wishlist');
        $this->db->join('tbl_product product','wishlist.product_id = product.id','LEFT');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    // get product review
    public function get_product_review($product_id, $sort='', $limit='', $start=''){

        $where = array('rating.product_id' => $product_id);

        $this->db->select('rating.*');
        $this->db->select('product.product_title');
        $this->db->select('user.user_name');
        $this->db->from('tbl_rating rating');
        $this->db->join('tbl_product product','rating.product_id = product.id','LEFT');
        $this->db->join('tbl_users user','rating.user_id = user.id','LEFT');
        $this->db->where($where);
        if($limit!='' OR $limit!=0){
          $this->db->limit($limit, $start);
        }
        if($sort!=''){
          switch ($sort) {
            case 'oldest':
                  $this->db->order_by('rating.id', 'ASC');
              break;
            case 'newest':
                  $this->db->order_by('rating.id', 'DESC');
              break;
            case 'negative':
                  $this->db->order_by('rating.rating', 'ASC');
              break;
            case 'positive':
                  $this->db->order_by('rating.rating', 'DESC');
              break;
            
            default:
              # code...
              break;
          }
        }
        else{
          $this->db->order_by('rating.id', 'DESC');
        }
        $query = $this->db->get();

        // echo $this->db->last_query();

        return $query->result();
    }


    // get product filters

    function productsFilters($ids,$table, $limit='', $start='',$min='', $max='',$brands='', $order_by=''){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where_in('id', $ids);
        
        if($min!='' && $max!=''){
          $this->db->where('`selling_price` BETWEEN '.$min.' AND '.$max);
        }
        if($brands!=''){
            $ids=explode(',', $brands);
            $this->db->where_in('brand_id', $ids);
        }
        if($limit!=0){
          $this->db->limit($limit, $start);
        }

        if($order_by !='' ){
          if(strcmp($order_by, 'low-high')==0){
            $this->db->order_by("selling_price", "ASC");
          }
          else if(strcmp($order_by, 'high-low')==0){
            $this->db->order_by("selling_price", "DESC");
          }
          else if(strcmp($order_by, 'top')==0){
            $this->db->order_by("total_sale", "DESC");
          }
          else if(strcmp($order_by, 'newest')==0){
            $this->db->order_by("id", "DESC");
          }

        }
        else{
          $this->db->order_by("id", "DESC");
        }

        $query = $this->db->get();
        
        // echo $this->db->last_query();

        return $row=$query->result();
    }

    function catproductsByPriceFilter($ids,$table, $limit='', $start='',$min='', $max='',$brands=''){
      
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where_in('id', $ids);
        if($min!='' && $max!=''){

          $this->db->where("selling_price BETWEEN $min AND $max");

          // $this->db->where('selling_price >=', $min); 
          // $this->db->where('selling_price <=', $max);
        }
        if($brands!=''){
            $ids=explode(',', $brands);
            $this->db->where_in('brand_id', $ids);
        }
        if($limit!=0){
          $this->db->limit($limit, $start);
        }

        $query = $this->db->get();
        
        // echo $this->db->last_query();

        return $row=$query->result();


        if($sub_cat_id==0 || $sub_cat_id==''){
          $where = array('product.category_id ' => $cat_id , 'product.status ' => '1', 'cat.status ' => '1');
        }
        else{
          $where = array('product.category_id ' => $cat_id,'product.sub_category_id ' => $sub_cat_id , 'product.status ' => '1', 'cat.status ' => '1', 'sub_cat.status ' => '1');
        }

        $this->db->select('product.*');
        $this->db->select('cat.category_name');
        $this->db->select('sub_cat.sub_category_name');
        $this->db->from('tbl_product product');
        $this->db->where($where); 
        if($brands!=''){
          $ids=explode(',', $brands);
          $this->db->where_in('product.brand_id', $ids);
        }
        if($limit!=0){
          $this->db->limit($limit, $start);
        }
        $this->db->join('tbl_category cat','cat.id = product.category_id','LEFT');
        $this->db->join('tbl_sub_category sub_cat','sub_cat.id = product.sub_category_id','LEFT');
        
        // echo $this->db->last_query();

        return $this->db->get()->result();

    }



    // get app details
    public function app_details(){
    	
      $this->db->select('tbl_settings.*');
      $this->db->from('tbl_settings');
      $this->db->where('tbl_settings.id', '1'); 
      $this->db->limit(1);
      $res=$this->db->get()->result();
      return $res[0];
    }

    // get app details
    public function get_unseen_orders($limit=0){
      
      $this->db->select('order.*, user.`user_name`');
      $this->db->from('tbl_order_details order');
      $this->db->join('tbl_users user','order.user_id = user.id','LEFT');
      $this->db->where(array('is_seen' => '0', 'order_status <>' => '-1')); 
      // $this->db->where(array('order_status <>' => '-1')); 
      if($limit!=0)
        $this->db->limit($limit);
      $query = $this->db->get();
      return $row=$query->result();
    }

     // get smtp setting
    public function smtp_settings(){
      
      $this->db->select('*');
      $this->db->from('tbl_smtp_settings');
      $this->db->where('tbl_smtp_settings.id', '1'); 
      $this->db->limit(1);
      $res=$this->db->get()->result();
      return $res[0];
    }


    // get product review
    public function get_refund_data($order_id=0){

        $where = array('refund.gateway !=' => 'cod');

        $this->db->select('refund.*');
        $this->db->select('user.user_name, user.user_email');
        $this->db->select('bank.bank_holder_name, bank.bank_holder_phone, bank.bank_holder_email, bank.account_no, bank.account_type, bank.bank_ifsc, bank.bank_name');
        $this->db->from('tbl_refund refund');
        $this->db->join('tbl_users user','refund.user_id = user.id','LEFT');
        $this->db->join('tbl_bank_details bank','refund.bank_id = bank.id','LEFT');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    public function top_selling_products($falg=false, $limit='', $start='', $keyword=''){

      $where=array('product.total_sale <> '=> '0');

      if(!$falg){

        $this->db->select('product.`product_title`, product.`total_sale`');
        $this->db->from('tbl_product product');
        $this->db->where($where);
        $this->db->order_by('product.total_sale', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        
        return $query->result();
        
      }
      else{
        
        $this->db->select('product.*');
        $this->db->select('cat.category_name');
        $this->db->select('sub_cat.sub_category_name');
        $this->db->from('tbl_product product');
        $this->db->join('tbl_category cat','cat.id = product.category_id','LEFT');
        $this->db->join('tbl_sub_category sub_cat','sub_cat.id = product.sub_category_id','LEFT');
        $this->db->where($where);
        if($limit!=''){
          $this->db->limit($limit, $start);
        }
        if($keyword!=''){
          $this->db->like('product.product_title',$keyword);
          $this->db->or_like('cat.category_name',$keyword);
          $this->db->or_like('sub_cat.sub_category_name',$keyword);
        }

        return $this->db->get()->result();

      }

    }

     public function todays_orders(){


      $where=array("order.order_status <> "=> "-1", "DATE_FORMAT(FROM_UNIXTIME(order.order_date), '%d-%m-%Y') =" => date('d-m-Y'));

      $this->db->select('order.order_unique_id, order.payable_amt, order.order_status, address.name');
      $this->db->from('tbl_order_details order');
      $this->db->join('tbl_addresses address','order.order_address= address.`id`','LEFT');
      $this->db->where($where);
      $this->db->order_by('order.id', 'DESC');
      $this->db->limit(10);
      $query = $this->db->get();

      return $query->result();

    }
}