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
    'relative_dir' => 'uploads/schools/images/',
    'allowed_types'=> ['jpg','jpeg','png','gif','webp'],
    'max_size'     => 5120
];
