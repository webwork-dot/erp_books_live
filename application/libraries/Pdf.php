<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  

// Check if mbstring extension is available (required by dompdf)
if (!extension_loaded('mbstring')) {
	// mbstring is not loaded, create fallback class
	$dompdf_loaded = false;
} else {
	// Load dompdf from application libraries folder (root installation)
	$dompdf_path = APPPATH . 'libraries/dompdf/autoload.inc.php';
	$dompdf_loaded = false;

	// Try to load from application libraries first (primary location)
	if (file_exists($dompdf_path)) {
		require_once $dompdf_path;
		$dompdf_loaded = true;
	}
}

// If not found in application libraries, try Composer autoload as fallback
if (!$dompdf_loaded) {
	$composer_autoload = FCPATH . 'vendor/autoload.php';
	if (file_exists($composer_autoload)) {
		require_once $composer_autoload;
		// Check if Dompdf class exists after loading composer autoload
		if (class_exists('Dompdf\Dompdf')) {
			$dompdf_loaded = true;
		}
	}
}

// Define Pdf class - extend Dompdf if available, otherwise create fallback
if (class_exists('Dompdf\Dompdf')) {
	// Use fully qualified class name to avoid use statement issues
	class Pdf extends \Dompdf\Dompdf
	{
		public function __construct()
		{
			parent::__construct();
		} 
	}
} else {
	// Fallback class if dompdf is not available
	class Pdf
	{
		public function __construct()
		{
			// Don't show error in constructor as it may be called before CI is ready
			log_message('error', 'Dompdf library not found. PDF generation will fail.');
		}
		
		public function set_paper($size, $orientation) { 
			if (!extension_loaded('mbstring')) {
				show_error('The mbstring PHP extension is required for PDF generation. Please enable mbstring extension in your PHP configuration.', 500);
			} else {
				show_error('Dompdf library not found. Please ensure dompdf is installed in application/libraries/dompdf/ or via Composer.', 500);
			}
			return $this; 
		}
		public function load_html($html) { return $this; }
		public function render() { return $this; }
		public function output() { 
			if (!extension_loaded('mbstring')) {
				show_error('The mbstring PHP extension is required for PDF generation. Please enable mbstring extension in your PHP configuration.', 500);
			} else {
				show_error('Dompdf library not found. Please ensure dompdf is installed in application/libraries/dompdf/ or via Composer.', 500);
			}
			return ''; 
		}
		public function stream($filename, $options = array()) { 
			if (!extension_loaded('mbstring')) {
				show_error('The mbstring PHP extension is required for PDF generation. Please enable mbstring extension in your PHP configuration.', 500);
			} else {
				show_error('Dompdf library not found. Please ensure dompdf is installed in application/libraries/dompdf/ or via Composer.', 500);
			}
			return $this; 
		}
		public function set_option($option, $value) { return $this; }
	}
}

?>








