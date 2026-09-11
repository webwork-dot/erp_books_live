<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Calculate base URL - use config if available, otherwise use relative path
if (function_exists('base_url')) {
    $base_url = base_url();
} elseif (function_exists('config_item') && config_item('base_url')) {
    $base_url = rtrim(config_item('base_url'), '/') . '/';
} else {
    // Fallback: use relative path
    $base_url = '/books-erp/erp-system/';
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo isset($heading) ? $heading : 'Database Error'; ?></title>
<link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/theme.css">
<style type="text/css">
body {
	margin: 40px;
	font-family: var(--font-primary, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif);
	color: var(--text-primary, #333);
	background: var(--bg-app, #f8f9fa);
}

#container {
	margin: 10px auto;
	max-width: 600px;
	border: 1px solid var(--border-light, #dee2e6);
	box-shadow: var(--shadow, 0 0.5rem 1rem rgba(0, 0, 0, 0.15));
	background: var(--bg-card, #fff);
	border-radius: var(--radius-xl, 0.5rem);
	padding: 2rem;
}

h1 {
	color: var(--text-primary, #333);
	background-color: transparent;
	border-bottom: 1px solid var(--border-light, #dee2e6);
	font-size: var(--text-h1, 1.5rem);
	font-weight: var(--fw-bold, 600);
	margin: 0 0 1rem 0;
	padding: 0 0 1rem 0;
}

code {
	font-family: 'Courier New', Courier, monospace;
	font-size: var(--text-small, 0.875rem);
	background-color: var(--bg-app, #f8f9fa);
	border: 1px solid var(--border-light, #dee2e6);
	color: var(--text-primary, #333);
	display: block;
	margin: 1rem 0;
	padding: 0.75rem;
	border-radius: var(--radius, 0.25rem);
}

p {
	margin: 1rem 0;
	font-size: var(--text-body, 1rem);
}

.links {
	margin-top: 1.5rem;
	display: flex;
	gap: 0.75rem;
}
</style>
</head>
<body>
	<?php
	// Get vendor domain from URL if available
	$vendor_domain = '';
	$logout_url = 'auth/logout';
	if (isset($_SERVER['REQUEST_URI'])) {
		$uri = $_SERVER['REQUEST_URI'];
		// Extract vendor domain from URL (first segment after /)
		if (preg_match('#/([a-zA-Z0-9_\-]+)/#', $uri, $matches)) {
			$vendor_domain = $matches[1];
			$reserved = array('erp-admin', 'api', 'frontend', 'vendor', 'Vendor', 'auth', 'client-admin', 'school-admin');
			if (!in_array($vendor_domain, $reserved)) {
				$logout_url = $vendor_domain . '/logout';
			}
		}
	}
	?>
	<div id="container">
		<h1><?php echo isset($heading) ? $heading : 'Database Error'; ?></h1>
		<?php echo isset($message) ? $message : '<p>A database error occurred.</p>'; ?>
		<div class="links">
			<?php if (function_exists('base_url')): ?>
				<a href="<?php echo base_url($logout_url); ?>" class="btn btn-primary">Go to Home (Logout)</a>
				<a href="<?php echo site_url('erp-admin/auth/login'); ?>" class="btn btn-secondary">Super Admin</a>
			<?php else: ?>
				<a href="/<?php echo $logout_url; ?>" class="btn btn-primary">Go to Home (Logout)</a>
				<a href="/auth/login" class="btn btn-secondary">Super Admin</a>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
