<?php
// application/config/upload.php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['uniform_upload'] = [
    'base_root'    => '/www/webwork/',
    'relative_dir' => 'uploads/uniforms/images/',
    'allowed_types'=> ['jpg','jpeg','png','gif','webp'],
    'max_size'     => 5120
];


$config['school_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/schools/images/',
    'allowed_types'=> ['jpg','jpeg','png','gif','webp'],
    'max_size'     => 5120
];

$config['individual_product_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/individual-products/images/',
    'allowed_types'=> ['jpg','jpeg','png','gif','webp'],
    'max_size'     => 5120
];

$config['notebook_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/notebooks/images/',
    'allowed_types'=> ['jpg','jpeg','png','gif','webp'],
    'max_size'     => 5120
];

$config['textbook_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/textbooks/images/',
    'allowed_types'=> ['jpg','jpeg','png','gif','webp'],
    'max_size'     => 5120
];

$config['vendor_logo_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/vendors_logos/logos/',
    'allowed_types'=> ['gif','jpg','jpeg','png','svg','ico'],
    'max_size'     => 2048
];

$config['vendor_favicon_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/vendors_logos/favicons/',
    'allowed_types'=> ['png'], 
    'max_size'     => 512      
];

$config['shipping_label_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/shipping_labels/',
    'allowed_types'=> ['pdf'],
    'max_size'     => 10240
];

$config['picqer_barcode_upload'] = [
    'base_root'    => '/www/webwork/',
    'root_path'    => '/www/webwork/',
    'relative_dir' => 'uploads/vendor_picqer_barcode/',
    'allowed_types'=> ['png'],
    'max_size'     => 5120
];
?>