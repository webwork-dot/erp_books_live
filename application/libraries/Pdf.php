<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  

// Load dompdf from frontend folder
$dompdf_path = FCPATH . 'book_erp_frontend/application/libraries/dompdf/autoload.inc.php';
if (file_exists($dompdf_path))
{
	require_once $dompdf_path;
}
else
{
	// Try alternative path
	$dompdf_path = APPPATH . '../book_erp_frontend/application/libraries/dompdf/autoload.inc.php';
	if (file_exists($dompdf_path))
	{
		require_once $dompdf_path;
	}
}

use Dompdf\Dompdf;

class Pdf extends Dompdf
{
	public function __construct()
	{
		 parent::__construct();
	} 
}

?>




