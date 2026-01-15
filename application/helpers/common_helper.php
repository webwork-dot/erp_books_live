<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* CodeIgniter
*
* An open source application development framework for PHP 5.1.6 or newer
*
* @package		CodeIgniter
* @author		ExpressionEngine Dev Team
* @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
* @license		http://codeigniter.com/user_guide/license.html
* @link		http://codeigniter.com
* @since		Version 1.0
* @filesource
*/

if ( ! function_exists('slugify'))
{
    function slugify($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        //$text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
        return 'n-a';
        return $text;
    }
}

if ( ! function_exists('format_amount'))
{
	function format_amount($amount) {
		return rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');
	}
}




if (!function_exists('cal_dis_array')) {
    function cal_dis_array($original_price, $discounted_price) {
        if ($original_price <= 0) {
            return 0;
        }
		$discount=$discount_percentage_final=0;
        $discount = $original_price - $discounted_price;
        $discount_percentage = ($discount / $original_price) * 100;
        $discount_percentage_final= round($discount_percentage, 2);
		$dis_array = array(
			'discount_amt' => $discount,
			'discount_per' => $discount_percentage_final,
		);
		return $dis_array;
    }
}

if (!function_exists('image_url')) {
	function image_url()
	{
		return 'https://rajasthanherbal.com/';
	}
}

if (!function_exists('capitalize')) {
    function capitalize($text) {
        $words = preg_split('/\s+/', $text);
        $capitalizedWords = array();
        foreach ($words as $word) {
            $capitalizedWords[] = ucfirst($word);
        }
        $capitalizedText = implode(' ', $capitalizedWords);
        return $capitalizedText;
    }
}

if (!function_exists('get_yt_thumbnail')) {
    function get_yt_thumbnail($url) {
        $value = explode("v=", $url);
        $videoId = $value[1];
        $thumbnail = "https://img.youtube.com/vi/" . $videoId . "/hqdefault.jpg";
        
        $headers = get_headers($thumbnail);
        if (strpos($headers[0], '200') !== false) {
            return $thumbnail;
        } else {
            return base_url() . 'assets/icon/default_yt.jpg';
        }
    }
}

if (!function_exists('get_stock_qty')) {
    function get_stock_qty($product_id) {
        $CI =& get_instance();

        $query = $CI->db->select('is_stock')
            ->from('products')
            ->where('id', $product_id)
            ->get();
        
        if ($query->num_rows() > 0) {
			if($query->row()->is_stock == 0){
				return 100000;
			} else {
				return 100000;
			}
        }
    }
}

if ( ! function_exists('get_video_extension'))
{
    // Checks if a video is youtube, vimeo or any other
    function get_video_extension($url) {
        if (strpos($url, '.mp4') > 0) {
            return 'mp4';
        } elseif (strpos($url, '.webm') > 0) {
            return 'webm';
        } else {
            return 'unknown';
        }
    }
}

if ( ! function_exists('ellipsis'))
{
    // Checks if a video is youtube, vimeo or any other
    function ellipsis($long_string, $max_character = 30) {
        $short_string = strlen($long_string) > $max_character ? substr($long_string, 0, $max_character)."..." : $long_string;
        return $short_string;
    }
}

// Human readable time
if ( ! function_exists('readable_time_for_humans')){
    function readable_time_for_humans($duration) {
        if ($duration) {
            $duration_array = explode(':', $duration);
            $hour   = $duration_array[0];
            $minute = $duration_array[1];
            $second = $duration_array[2];
            if ($hour > 0) {
                $duration = $hour.' '.get_phrase('hr').' '.$minute.' '.get_phrase('min');
            }elseif ($minute > 0) {
                if ($second > 0) {
                    $duration = ($minute+1).' '.get_phrase('min');
                }else{
                    $duration = $minute.' '.get_phrase('min');
                }
            }elseif ($second > 0){
                $duration = $second.' '.get_phrase('sec');
            }else {
                $duration = '00:00';
            }
        }else {
            $duration = '00:00';
        }
        return $duration;
    }
}

if ( ! function_exists('trimmer'))
{
    function trimmer($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
        return 'n-a';
        return $text;
    }
}

// RANDOM NUMBER GENERATOR FOR ELSEWHERE
if (! function_exists('random')) {
  function random($length_of_string) {
    // String of all alphanumeric character
    $str_result = '0123456789';

    // Shufle the $str_result and returns substring
    // of specified length
    return substr(str_shuffle($str_result), 0, $length_of_string);
  }
}

//generate unique id
if (!function_exists('generate_unique_id')) {
	function generate_unique_id()
	{
		$id = uniqid("", TRUE);
		return str_replace(".", "-", $id);
	}
}

//generate short unique id
if (!function_exists('generate_short_unique_id')) {
	function generate_short_unique_id()
	{
		$id = uniqid("", TRUE);
		return str_replace(".", "-", $id);
	}
}

//generate order number
if (!function_exists('generate_transaction_number')) {
	function generate_transaction_number()
	{
		$transaction_number = uniqid("", TRUE);
		return str_replace(".", "-", $transaction_number);
	}
}


if ( ! function_exists('get_phrase'))
{
    function get_phrase($phrase = '') {
        $key = strtolower(preg_replace('/\s+/', '_', $phrase));
        $langArray[$key] = ucwords(str_replace('_', ' ', $key));
        return $langArray[$key];
    }
}


	if(!function_exists('get_ref_no')){
		function get_ref_no()
		{
        	 
		$ci =& get_instance();
		return $ci->crud_model->get_ref_no();
	
		}
	}	




if (!function_exists('get_time_difference')) {
 function get_time_difference($timestamp){  
  date_default_timezone_set("Asia/Kolkata");         
  $time_ago        = strtotime($timestamp);
  $current_time    = time();
  $time_difference = $current_time - $time_ago;
  $seconds         = $time_difference;
  
  $minutes = round($seconds / 60); // value 60 is seconds  
  $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
  $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
  $weeks   = round($seconds / 604800); // 7*24*60*60;  
  $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
  $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
                
  if ($seconds <= 60){
    return "Just Now";
  } else if ($minutes <= 60){
    if ($minutes == 1){
      return "one minute ago";
    } else {
      return "$minutes minutes ago";
    }

  } else if ($hours <= 24){
    if ($hours == 1){
      return "an hour ago";
    } else {
      return "$hours hrs ago";
    }
  } else if ($days <= 7){
    if ($days == 1){
      return "yesterday";
    } else {
      return "$days days ago";
    }
  } else if ($weeks <= 4.3){
    if ($weeks == 1){
      return "a week ago";
    } else {
      return "$weeks weeks ago";
    }
  } else if ($months <= 12){
    if ($months == 1){
      return "a month ago";
    } else {
      return "$months months ago";
    }
  } else {    
    if ($years == 1){
      return "one year ago";
    } else {
      return "$years years ago";
    }
  }
}

 
}


if (!function_exists('initials')) {
 function initials($name){  
     $name=strtoupper($name);
    //prefixes that needs to be removed from the name
    $remove = ['.', 'MRS', 'MISS', 'MS', 'MASTER', 'DR', 'MR'];
    $nameWithoutPrefix=str_replace($remove," ",$name);

  $words = explode(" ", $nameWithoutPrefix);

//this will give you the first word of the $words array , which is the first name
 $firtsName = reset($words); 

//this will give you the last word of the $words array , which is the last name
 $lastName  = end($words);

 $f1= substr($firtsName,0,1); // this will echo the first letter of your first name
 $f2= substr($lastName ,0,1); // this will echo the first letter of your last name
 
 return $f1.$f2;
 }
}



	if(!function_exists('get_unread_pending_approval')){
		function get_unread_pending_approval()
		{
        	 
		$ci =& get_instance();
		return $ci->crud_model->get_unread_pending_approval();
	
		}
	}	
	
	

if (!function_exists('rupees_word')) {
function rupees_word($number) {
    $number = abs($number);
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
    if($decimal<20){ $paise = ($decimal) ? "And  " . ($words[$decimal - $decimal]) ." " .($words[$decimal])." Paise" : '';  }
    else{  $paise = ($decimal) ? "And  " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])." Paise" : '';   }
    return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
}
} 
  
if (!function_exists('admin_url')) {
	function admin_url()
	{
		return base_url() . "admin/";
	}
}
  
if (!function_exists('store_url')) {
	function store_url()
	{
		return base_url() . "store/";
	}
}

if (!function_exists('prod_image_url')) {
	function prod_image_url()
	{
		return base_url() . "assets/images/products/";
	}
}

if (!function_exists('cat_image_url')) {
	function cat_image_url()
	{
		return base_url() . "assets/images/category/";
	}
}

if (!function_exists('blog_image_url')) {
	function blog_image_url()
	{
		return base_url() . "assets/images/blog/";
	}
}


if (!function_exists('currentUrl')) {
function currentUrl( $trim_query_string = false ) {
    $pageURL = (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') ? "https://" : "https://";
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    if( ! $trim_query_string ) {
        return $pageURL;
    } else {
        $url = explode( '?', $pageURL );
        return $url[0];
    }
}
}

if (!function_exists('canonicalUrl')) {
    function canonicalUrl($trim_query_string = false) {
        // Get the current protocol (http or https)
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

        // Get the current domain name
        $domain = $_SERVER['HTTP_HOST'];

        // Get the current URI
        $uri = $_SERVER['REQUEST_URI'];

        // Construct the canonical URL
        $canonicalUrl = $protocol . $domain . $uri;

        // Optionally, trim query strings
        if ($trim_query_string) {
            $canonicalUrl = strtok($canonicalUrl, '?');
        }

        // Output the canonical URL
        return $canonicalUrl;
    }
}


if ( ! function_exists('getExtension'))
{
    function getExtension($str) {
         $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        
        $l   = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }
}


if (!function_exists('main_url')) {
	function main_url()
	{
		return "https://rajasthanherbal.com/";
	}
}

if (!function_exists('getDatesFromRange')) {
function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
    // Declare an empty array 
    $array = array(); 
      
    // Variable that store the date interval 
    // of period 1 day 
    $interval = new DateInterval('P1D'); 
  
    $realEnd = new DateTime($end); 
    $realEnd->add($interval); 
  
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
  
    // Use loop to store date into array 
    foreach($period as $date) {                  
        $array[] = $date->format($format);  
    } 
  
    // Return the array elements 
    return $array; 
} 
} 
  

//delete file from server
if (!function_exists('delete_file_from_server')) {
	function delete_file_from_server($path)
	{
		$full_path = FCPATH . $path;
		if (strlen($path) > 15 && file_exists($full_path)) {
			@unlink($full_path);
		}
	}
}

//generate slug
if (!function_exists('str_slug')) {
	function str_slug($text)
	{
	    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        //$text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
        return 'n-a';
        return $text;
	}
}

if ( ! function_exists('trans'))
{
    function trans($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        //$text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
        return 'n-a';
        return $text;
    }
}

//check auth
if (!function_exists('auth_check')) {
	function auth_check()
	{
		// Get a reference to the controller object
		$ci =& get_instance();
		return $ci->auth_model->is_logged_in();
	}
}

//check auth
if (!function_exists('limit_text')) {
	function limit_text($string,$len)
	{

	$r =$string;
	$data=(strlen($r) > $len) ? substr($r,0,$len-2).'...' : $r;
	return $data;
	
	}
}

if (!function_exists('number_format_short')) {
function number_format_short($n, $precision = 1 ) {
    
       if($n >= 10000000) $n = (price_format_decimal($n/10000000)) . ' Cr';
		else if($n >= 100000) $n = (price_format_decimal($n/100000)) . ' L';
		else if($n >= 1000) $n = (price_format_decimal($n/1000)) . ' K';
		else if($n == '') $n =  ' 0';
		return $n;
 }
}


if (!function_exists('price_format_decimal')) {
	function price_format_decimal($price)
	{
		return number_format($price, 2, ".", "");
	}
}

if (!function_exists('discount_per')) {
	function discount_per($orgprice,$saleprice)
	{
		 $percent = price_format_decimal((($orgprice - $saleprice)*100) /$orgprice);
	    return	$percent;
	}
}


if (!function_exists('display_rating')) {
	function display_rating($rating){
	  if($rating!='' && $rating!=NULL){
       $output='<div class="fr-can-rating">'; 
       for($count=1; $count<=5; $count++) {
       if($count <= $rating)
       {
        $color = 'filled';
       }
       else
       {
        $color = '';
       }
       $output .= '<i class="fa fa-star '.$color.' "></i>';
       }
      $output .='</div>';	 
	 }
	 else{
	  $output='';   
	 }
	 return $output;
   }
}


if (!function_exists('get_category')) {
	function get_category($id)
	{
		$ci =& get_instance();
		 return $ci->crud_model->get_category_list($id);
		
	}
}

if (!function_exists('clean')) {
function clean($string = false ) {
   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
}
}




if (!function_exists('testimonial_image_url')) {
	function testimonial_image_url()
	{
		return base_url()."assets/images/testimonial/";
	}
}

if (!function_exists('product_image_url')) {
	function product_image_url()
	{
		return base_url()."uploads/products/";
	}
}
if (!function_exists('featured_on_url')) {
	function featured_on_url()
	{
		return base_url()."assets/images/featured-on/";
	}
}

if (!function_exists('product_gallery_url')) {
	function product_gallery_url()
	{
		return base_url()."uploads/";
	}
}

if (!function_exists('product_image_url')) {
	function product_image_url()
	{
		return base_url()."uploads/";
	}
}

if (!function_exists('banner_image_url')) {
	function banner_image_url()
	{
		return base_url()."assets/images/banner/";
	}
}


if (!function_exists('new_product_image_url')) {
	function new_product_image_url()
	{
		return base_url()."assets/images/new_launch_product/";
	}
}

if (!function_exists('album_gallery_url')) {
	function album_gallery_url()
	{
		return base_url()."assets/images/medical_camp/gallery/";
	}
}

if (!function_exists('milestones_image_url')) {
	function milestones_image_url()
	{
		return base_url()."assets/images/milestones/";
	}
}


if (!function_exists('inspirations_image_url')) {
	function inspirations_image_url()
	{
		return base_url()."assets/images/inspirations/";
	}
}

if (!function_exists('press_release_image_url')) {
	function press_release_image_url()
	{
		return base_url()."assets/images/press-release/";
	}
}

if (!function_exists('in_news_image_url')) {
	function in_news_image_url()
	{
		return base_url()."assets/images/in-news/";
	}
}

if (!function_exists('album_image_url')) {
	function album_image_url()
	{
		return base_url() . "assets/images/album/";
	}
}


if (!function_exists('price_decimal')) {
	function price_decimal($price){
	     if(is_float($price)){
	        	return number_format($price, 2, ".", ""); 
	     }
	     else{
	       	return number_format($price, 2, ".", ""); 
	     }
	
	}
}

if (!function_exists('price_decimal_three')) {
	function price_decimal_three($price)
	{
		return number_format($price, 3, ".", "");
		//return bcdiv($price, 1, 2);
	}
}

if (!function_exists('get_per_total')) {
function get_per_total($amount,$percent) {
   $tcs_total = ($amount*$percent)/100;
   return price_decimal_three($tcs_total);
      
}
}
  
if (!function_exists('get_discount_total')) {
function get_discount_total($amount,$percent) {
   $tcs_total = ($amount*$percent)/100;
   return price_decimal_three($tcs_total);
      
}
}

if (!function_exists('isValidPhoneNumber')) {
  function isValidPhoneNumber($phone_number){
        return preg_match('/^[0-9]{10}+$/', trim($phone_number));
    }
}
  
if (!function_exists('get_round_off')) {
function get_round_off($grand_total) {
        $decimalPart = $grand_total - floor($grand_total);
		$round_of=0;
		$final_total=0;
        if ($decimalPart >= 0.50) {
            $ceilValue = ceil($grand_total);
            $decimalPart_ = $ceilValue - $grand_total;
            $round_of = number_format($decimalPart_, 2);

            $final_total = $grand_total + $decimalPart_;
        } else {
            $round_of = number_format($decimalPart, 2);
            $final_total = $grand_total - $decimalPart;
			$round_of=-$round_of;
        }

        // Now you can pass $round_of and $final_total to your view
        $data = array(
            'round_of' => $round_of,
            'final_total' => $final_total
        );	
	
     return $data;
      
}
}

if (!function_exists('clean_address')) {
function clean_address($string = false ) {
    
     // Using str_replace() function 
      // to replace the word 
      $res = str_replace( array( '\'', '"','*','"',';', '<', '>' ), '', $string);
      return $res;
}
}

if (!function_exists('get_percentage')) {
function get_percentage($Orgprice,$SalePrice) {
    $percent = (($Orgprice - $SalePrice)*100) /$Orgprice;
      return round($percent);
}
}

if (!function_exists('get_gst_amt')) {
function get_gst_amt($amount,$percent) {
   $gst_amount = $amount-($amount*(100/(100+$percent)));
   return price_format_decimal($gst_amount);
      
}
}
if (!function_exists('excl_gst_amt')) {
function excl_gst_amt($amount,$percent) {
   $gst_amount = $amount-($amount*(100/(100+$percent)));
   $final_amount = $amount-$gst_amount;
   return price_format_decimal($final_amount);
      
}
}


//price formatted
if (!function_exists('priceFormatted')) {
    function priceFormatted($price, $convertCurrency = false){
        //convert currency
            $rate = 1;
            $selectedCurrency = getSelectedCurrency();
            if (isset($selectedCurrency) && isset($selectedCurrency['exchange_rate'])) {
                $rate = $selectedCurrency['exchange_rate'];
                $price = $price * $rate;
            }
        
        $decPoint = '.';
        $thousandsSep = ',';
     
        if (!empty($price)) {
            if (filter_var($price, FILTER_VALIDATE_INT) !== false) {
                $price = number_format($price, 0, $decPoint, $thousandsSep);
            } else {
                $price = number_format($price, 2, $decPoint, $thousandsSep);
            }
        }
        return priceCurrencyFormat($price, $selectedCurrency);
    }
}

//price currency format
if (!function_exists('priceCurrencyFormat')) {
    function priceCurrencyFormat($price, $selectedCurrency)  {
        
        if (!empty($selectedCurrency)) {
            $currency = $selectedCurrency;
            $space = '';
            if ($currency['symbol_direction'] == 'left') {
                $price = '<span>' . $currency['symbol'] . '</span>' . $space . $price;
            } else {
                $price = $price . $space . '<span>' . $currency['symbol'] . '</span>';
            }
        }
        return $price;
    }
}

if (!function_exists('get_selling_price')) { 
    function get_selling_price($product_id, $variation_id) {
      $CI =& get_instance();
      $CI->load->database(); 
  
    //   $price_id = get_cookie('del_city_id');
    //   $del_city_id = get_cookie('del_city_id');
    //   $del_state_id = get_cookie('del_state_id');
  
      $sql_filter = " AND (state_id = 0 AND city_id = 0)";
    //   if (get_cookie('del_city_id')) {
    //       $sql_filter = " AND (price_id ='$price_id')";
    //   }
  
      $sql = "SELECT selling_price FROM product_variations WHERE id = ? AND product_id = ?";
      $query = $CI->db->query($sql, array($variation_id, $product_id));
      $selling_price = ($query->num_rows() > 0) ? $query->row()->selling_price : 0;
  
      $price = $selling_price;
      return $price;
    }
  }

if (!function_exists('get_mrp_price')) { 
    function get_mrp_price($product_id, $variation_id) {
        $CI =& get_instance();
        $CI->load->database(); 

        $sql = "SELECT product_mrp FROM product_variations WHERE id = ? AND product_id = ?";
        $query = $CI->db->query($sql, array($variation_id, $product_id));
        $product_mrp = ($query->num_rows() > 0) ? round($query->row()->product_mrp) : 0;

        $price = $product_mrp;
        return $price;
    }
}

if (!function_exists('cal_dis_per')) {
    function cal_dis_per($original_price, $discounted_price) {
        if ($original_price <= 0) {
            return 0;
        }
        $discount = $original_price - $discounted_price;
        $discount_percentage = ($discount / $original_price) * 100;
        return round($discount_percentage, 2);
    }
}

if (!function_exists('searchaArray')) {
    function searchaArray($products, $field, $value){
       foreach($products as $key => $product)
       {
          if ( $product[$field] === $value )
             return $key;
       }
       return false;
    }
}

//get selected currency
if (!function_exists('getSelectedCurrency')) {
    function getSelectedCurrency()   {
      	$ci = get_instance();  
        $currency_id=($ci->session->userdata('currency_id') ? $ci->session->userdata('currency_id'): '1');
        // $currency_id='1';
        $get_cur=$ci->common_model->getRowByWhereId('currencies','id,country,code,symbol,exchange_rate,symbol_direction',array('id'=>$currency_id));
        $currency=array(
          'id'               => $get_cur['id'], 
          'code'             => $get_cur['code'], 
          'country'          => $get_cur['country'], 
          'exchange_rate'    => $get_cur['exchange_rate'], 
          'symbol'           => $get_cur['symbol'],  
          'symbol_direction' => $get_cur['symbol_direction'],  
        );
        return $currency;
    }
}

if ( ! function_exists('image_title')){
    function image_title($imageName) {
        $imageNameWithoutExt = pathinfo($imageName, PATHINFO_FILENAME);
        $imageNameWithoutExt = preg_replace('~[^\\pL\d]+~u', ' ', $imageNameWithoutExt);			
        $imageNameWithoutExt = ucwords($imageNameWithoutExt);
			
        return $imageNameWithoutExt;
    }
}

if (!function_exists('isMobileDevice')) {
	function isMobileDevice(){
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

}

function star_rating($rating){

    $rating_round = round($rating * 2) / 2;

    if ($rating_round <= 0.5 && $rating_round > 0) {
        return '<span class="star split-50-50"></span><span class="star gray"></span><span class="star gray"></span><span class="star gray"></span><span class="star gray"></span>';
    }

    if ($rating_round <= 1 && $rating_round > 0.5) {
        return '<span class="star"></span><span class="star gray"></span><span class="star gray"></span><span class="star gray"></span><span class="star gray"></span>';
    }

    if ($rating_round <= 1.5 && $rating_round > 1) {
         return '<span class="star"></span><span class="star split-50-50"></span><span class="star gray"></span><span class="star gray"></span><span class="star gray"></span>';
    }

    if ($rating_round <= 2 && $rating_round > 1.5) {
        return '<span class="star"></span><span class="star"></span><span class="star gray"></span><span class="star gray"></span><span class="star gray"></span>';
    }

    if ($rating_round <= 2.5 && $rating_round > 2) {
         return '<span class="star"></span><span class="star"></span><span class="star split-50-50"></span><span class="star gray"></span><span class="star gray"></span>';
    }

    if ($rating_round <= 3 && $rating_round > 2.5) {
        return '<span class="star"></span><span class="star"></span><span class="star"></span><span class="star gray"></span><span class="star gray"></span>';
    }
    if ($rating_round <= 3.5 && $rating_round > 3) {
        return '<span class="star"></span><span class="star"></span><span class="star"></span><span class="star split-50-50"></span><span class="star gray"></span>';
    }
    if ($rating_round <= 4 && $rating_round > 3.5) {
       return '<span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span class="star gray"></span>';
    }
    if ($rating_round <= 4.5 && $rating_round > 4) {
       return '<span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span class="star split-50-50"></span>';
    }
    if ($rating_round <= 5 && $rating_round > 4.5) {
       return '<span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span>';
    }
    
}


if (!function_exists('clean_and_escape')) {
  function clean_and_escape($str){
        $CI =& get_instance();
        $CI->load->helper('security');

        // Remove white spaces and escape the string
        $cleaned_str = html_escape(trim($str));

        return $cleaned_str;
    }
}

if (!function_exists('getClientIp')) {
    function getClientIp() {
	   $ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
    }
}

if (!function_exists('get_city_ip')) {
    function get_city_ip() {
        /*$CI =& get_instance();
        $ip = getClientIp();

        $response = file_get_contents("http://ip-api.com/json/{$ip}");
        $locationData = json_decode($response);

        if ($locationData && $locationData->status === 'success') {
            return $locationData->city; 
        } else {
            return 'Mumbai';
        }*/
    }
}


if (!function_exists('price_currency_format')) {
    function price_currency_format($price, $selectedCurrency)  {
        $currency = $selectedCurrency;
        $space = '';
        $price_final = '<span>' .  $currency   . '</span>' . $space . $price;
        return $price_final;
    }
}

if (! function_exists('remove_js')) {
    function remove_js($description = '') {
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $description);
    }
}

if (!function_exists('isValidPhoneNumber')) {
    function isValidPhoneNumber($phone_number) {
        $cleaned_number = preg_replace('/\s+/', '', trim($phone_number));
        return preg_match('/^[0-9]{10}$/', $cleaned_number);
    }
}


if (!function_exists('priceDb')) {
    function priceDb($price, $convertCurrency = false){
        //convert currency
            $rate = 1;
            $selectedCurrency = getSelectedCurrency();
            if (isset($selectedCurrency) && isset($selectedCurrency['exchange_rate'])) {
                $rate = $selectedCurrency['exchange_rate'];
                $price = $price * $rate;
            }
        
        $decPoint = '.';
        $thousandsSep = '';
     
        if (!empty($price)) {
            if (filter_var($price, FILTER_VALIDATE_INT) !== false) {
                $price = number_format($price, 0, $decPoint, $thousandsSep);
            } else {
                $price = number_format($price, 2, $decPoint, $thousandsSep);
            }
        }
        return $price;
    }
}


if (!function_exists('indian_price')) {
    function indian_price($price){
          // Check if the price is negative
        $is_negative = $price < 0;

        // Remove the negative sign temporarily
        $price = number_format($price, 2, '.', '');
        $price = abs((double)$price);

        $decimal_part = '';

        // Separate the decimal part if it exists
        if (strpos($price, '.') !== false) {
            list($price, $decimal_part) = explode('.', $price);
        }

        $explrestunits = "";
        if (strlen($price) > 3) {
            $lastthree = substr($price, strlen($price) - 3, strlen($price));
            $restunits = substr($price, 0, strlen($price) - 3);
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
            $expunit = str_split($restunits, 2);

            for ($i = 0; $i < sizeof($expunit); $i++) {
                if ($i == 0) {
                    $explrestunits .= (int)$expunit[$i] . ","; // Convert first value to integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = '₹' . $explrestunits . $lastthree;
        } else {
            $thecash = '₹' . $price;
        }

        // Add the decimal part back
        if ($decimal_part != '') {
            $thecash .= '.' . $decimal_part;
        }

        // Add the negative sign back if needed
        if ($is_negative) {
            $thecash = '-' . $thecash;
        }

        return $thecash;
    }
}


if (!function_exists('blogs_url')) {
	function blogs_url()
	{
		return "https://blog.raplgroup.in/";
	}
}

if (!function_exists('alb_image_url')) {
	function alb_image_url()
	{
		return "https://rajasthanherbal.com/assets/images/medical_camp/gallery/";
	}
}

if (!function_exists('cdn_url')) {
	function cdn_url()
	{
		return 'https://rajasthanherbal.com/';
	}
}
 
if (!function_exists('timespan')) {
    function timespan($datetime) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => 'yr',
            'm' => 'mo',
            'w' => 'wk',
            'd' => 'd',
            'h' => 'hr',
            'i' => 'min',
        ];
        
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . $v;
            } else {
                unset($string[$k]);
            }
        }
        
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

if (!function_exists('get_dynamic_logo')) {
    function get_dynamic_logo($type = 'default') {
        // Get vendor site settings
        $vendor_settings = get_vendor_site_settings();

        // If vendor has a custom logo set, use it
        if (!empty($vendor_settings['logo_path']) && file_exists(FCPATH . $vendor_settings['logo_path'])) {
            return base_url($vendor_settings['logo_path']);
        }

        // Fallback to default logos based on type
        $default_logos = [
            'header' => 'logo.png',
            'modal' => 'logo-white.png',
            'default' => 'logo.png'
        ];

        $logo_name = isset($default_logos[$type]) ? $default_logos[$type] : $default_logos['default'];
        return base_url() . 'assets/images/' . $logo_name;
    }
}
/**
 * Get vendor site settings based on current domain or vendor parameter
 *
 * @return array|null Vendor settings array or null if not found
 */
if (!function_exists('get_vendor_site_settings')) {
    function get_vendor_site_settings()
    {
        $CI =& get_instance();

        // Try to get vendor from URL parameter first
        $vendor_domain = $CI->input->get('erp_clients');

        // If no vendor parameter, try to determine from domain
        if (!$vendor_domain) {
            $host = $_SERVER['HTTP_HOST'];

            // Remove www. if present
            $host = preg_replace('/^www\./', '', $host);

            // Check if it's a subdomain (vendor.domain.com)
            if (strpos($host, '.') !== false) {
                $parts = explode('.', $host);
                if (count($parts) > 2) {
                    // It's a subdomain, get the subdomain part
                    $vendor_domain = $parts[0];
                } else {
                    // It's the main domain, use default
                    $vendor_domain = 'default';
                }
            } else {
                $vendor_domain = 'default';
            }
        }

        // Default settings
        $default_settings = array(
            'logo_path' => 'assets/images/logo.png',
            'favicon_path' => 'assets/images/favicon.png',
            'primary_color' => '#116B31',
            'secondary_color' => '#ffffff',
            'accent_color' => '#28a745',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#f8f9fa',
            'text_primary_color' => '#333333',
            'text_secondary_color' => '#666666',
            'link_color' => '#116B31',
            'link_hover_color' => '#0d5a26',
            'button_primary_bg' => '#116B31',
            'button_primary_text' => '#ffffff',
            'button_secondary_bg' => '#6c757d',
            'button_secondary_text' => '#ffffff',
            'modal_bg_gradient_start' => '#116B31',
            'modal_bg_gradient_end' => '#28a745',
            'modal_button_bg' => '#ffffff',
            'modal_button_text' => '#116B31',
            'since_text' => 'SINCE 1952',
            'is_active' => 1
        );

        try {
            // Connect to master database to get vendor settings
            $master_db = $CI->load->database('master', TRUE);

            // Find vendor by domain
            $master_db->where('domain', $vendor_domain);
            $master_db->where('status', 1); // Only active vendors
            $vendor_query = $master_db->get('erp_clients');

            if ($vendor_query->num_rows() > 0) {
                $vendor = $vendor_query->row_array();
                $vendor_id = $vendor['id'];

                // Get vendor site settings
                $master_db->where('vendor_id', $vendor_id);
                $master_db->where('is_active', 1);
                $settings_query = $master_db->get('vendor_site_settings');

                if ($settings_query->num_rows() > 0) {
                    $settings = $settings_query->row_array();

                    // Merge with defaults to ensure all keys exist
                    return array_merge($default_settings, $settings);
                }
            }
        } catch (Exception $e) {
            // If database connection fails, continue with defaults
            log_message('error', 'Failed to load vendor settings: ' . $e->getMessage());
        }

        // Return default settings if no vendor found or database error
        // Check if we have URL parameters for testing
        $test_logo = $CI->input->get('logo');
        $test_title = $CI->input->get('title');

        // For now, use the uploaded logo path if it exists (now copied to frontend)
        // Try to find the latest uploaded logo
        $logo_dir = FCPATH . 'uploads/vendors_logos/logos/';
        if (is_dir($logo_dir)) {
            $logo_files = glob($logo_dir . 'vendor_18_*.png');
            if (!empty($logo_files)) {
                // Get the most recent logo file
                usort($logo_files, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                $latest_logo = basename($logo_files[0]);
                $uploaded_logo = 'uploads/vendors_logos/logos/' . $latest_logo;
                if (file_exists(FCPATH . $uploaded_logo)) {
                    $default_settings['logo_path'] = $uploaded_logo;
                }
            }
        }

        if ($test_logo) {
            $default_settings['logo_path'] = $test_logo;
        }
        if ($test_title) {
            $default_settings['site_title'] = $test_title;
            $default_settings['meta_title'] = $test_title;
        }

        return $default_settings;
    }
}

/**
 * Generate vendor URL (subdomain-based)
 * 
 * @param string $path Path to append (e.g., 'dashboard', 'products/add')
 * @param string|null $vendor_domain Optional vendor base domain (if not provided, uses current HTTP_HOST or session)
 * @param string $subdomain Subdomain prefix (default: 'master')
 * @return string Full URL with subdomain
 */
if (!function_exists('vendor_url')) {
    function vendor_url($path = '', $vendor_domain = null, $subdomain = 'master') {
        $CI =& get_instance();
        
        // Get vendor base domain
        if (!$vendor_domain) {
            // Try HTTP_HOST first (domain-based routing)
            $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            if (strpos($http_host, ':') !== false) {
                $http_host = substr($http_host, 0, strpos($http_host, ':'));
            }
            
            // Check if HTTP_HOST is a vendor domain (not localhost/admin)
            if (!empty($http_host) && 
                strpos($http_host, 'localhost') === false && 
                strpos($http_host, '127.0.0.1') === false &&
                strpos($http_host, 'erp-admin') === false) {
                
                // Extract base domain from subdomain if needed
                if (strpos($http_host, '.') !== false) {
                    $parts = explode('.', $http_host);
                    if (count($parts) >= 2) {
                        // Remove subdomain (first part) to get base domain
                        array_shift($parts);
                        $vendor_domain = implode('.', $parts);
                    } else {
                        $vendor_domain = $http_host;
                    }
                } else {
                    $vendor_domain = $http_host;
                }
            } else {
                // Fallback to session vendor domain
                if (property_exists($CI, 'session') && is_object($CI->session)) {
                    $vendor_domain = $CI->session->userdata('vendor_domain');
                    // Extract base domain if session has subdomain
                    if ($vendor_domain && strpos($vendor_domain, '.') !== false) {
                        $parts = explode('.', $vendor_domain);
                        if (count($parts) >= 2 && $parts[0] === $subdomain) {
                            array_shift($parts);
                            $vendor_domain = implode('.', $parts);
                        }
                    }
                }
            }
        }
        
        if ($vendor_domain) {
            // Check if we're on localhost - use path-based URLs for local development
            $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            if (strpos($http_host, ':') !== false) {
                $http_host = substr($http_host, 0, strpos($http_host, ':'));
            }
            
            $is_localhost = (strpos($http_host, 'localhost') !== false || 
                            strpos($http_host, '127.0.0.1') !== false ||
                            empty($http_host));
            
            if ($is_localhost) {
                // Use path-based URL for localhost (e.g., localhost/erp_books_live/varitty.in/dashboard)
                $path = ltrim($path, '/');
                return base_url($vendor_domain . ($path ? '/' . $path : ''));
            } else {
                // Generate subdomain URL for production (e.g., master.varitty.in/dashboard)
                $full_domain = $subdomain . '.' . $vendor_domain;
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $path = ltrim($path, '/');
                return $protocol . '://' . $full_domain . ($path ? '/' . $path : '');
            }
        } else {
            // Fallback to path-based URL (for backward compatibility)
            $path = ltrim($path, '/');
            return base_url($path);
        }
    }
}

/**
 * Generate dynamic CSS for vendor colors
 *
 * @param array $settings Vendor settings array
 * @return string CSS string
 */
if (!function_exists('generate_vendor_css')) {
    function generate_vendor_css($settings)
    {
        // Validate settings array
        if (!is_array($settings)) {
            $settings = array();
        }

        $css = "<style>\n";

        // Helper function to validate hex color
        $validate_hex = function($color, $default) {
            if (empty($color) || !is_string($color)) {
                return $default;
            }
            $color = trim($color);
            return (preg_match('/^#[a-fA-F0-9]{6}$/', $color)) ? $color : $default;
        };

        // Primary color variables
        $css .= ":root {\n";
        $css .= "  --primary-color: " . $validate_hex($settings['primary_color'] ?? null, '#116B31') . ";\n";
        $css .= "  --secondary-color: " . $validate_hex($settings['secondary_color'] ?? null, '#ffffff') . ";\n";
        $css .= "  --accent-color: " . $validate_hex($settings['accent_color'] ?? null, '#28a745') . ";\n";
        $css .= "  --header-bg: " . $validate_hex($settings['header_bg_color'] ?? null, '#ffffff') . ";\n";
        $css .= "  --text-primary: " . $validate_hex($settings['text_primary_color'] ?? null, '#333333') . ";\n";
        $css .= "  --text-secondary: " . $validate_hex($settings['text_secondary_color'] ?? null, '#666666') . ";\n";
        $css .= "  --link-color: " . $validate_hex($settings['link_color'] ?? null, '#116B31') . ";\n";
        $css .= "  --link-hover: " . $validate_hex($settings['link_hover_color'] ?? null, '#0d5a26') . ";\n";
        $css .= "  --button-primary-bg: " . $validate_hex($settings['button_primary_bg'] ?? null, '#116B31') . ";\n";
        $css .= "  --button-primary-text: " . $validate_hex($settings['button_primary_text'] ?? null, '#ffffff') . ";\n";
        $css .= "}\n\n";

        // CSS rules moved to static CSS file

        // Custom CSS if provided
        if (!empty($settings['custom_css']) && is_string($settings['custom_css'])) {
            $css .= "\n/* Custom Vendor CSS */\n";
            // Basic sanitization - remove any script tags or dangerous content
            $custom_css = strip_tags($settings['custom_css']);
            // Additional validation - ensure it doesn't contain JavaScript-like syntax
            $custom_css = preg_replace('/javascript\s*:/i', '', $custom_css);
            $custom_css = preg_replace('/expression\s*\(/i', '', $custom_css);
            $css .= $custom_css . "\n";
        }

        $css .= "</style>\n";

        return $css;
    }
}

// ------------------------------------------------------------------------
/* End of file common_helper.php */
/* Location: ./system/helpers/common.php */
