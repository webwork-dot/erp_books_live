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
<title><?php echo isset($heading) ? $heading : 'An Error Occurred'; ?></title>
<link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/theme.css">
<style type="text/css">
body {
	margin: 40px;
	font-family: var(--font-primary);
	color: var(--text-primary);
	background: var(--bg-app);
}

#container {
	margin: 10px auto;
	max-width: 600px;
	border: 1px solid var(--border-light);
	box-shadow: var(--shadow);
	background: var(--bg-card);
	border-radius: var(--radius-xl);
	padding: 2rem;
}

h1 {
	color: var(--text-primary);
	background-color: transparent;
	border-bottom: 1px solid var(--border-light);
	font-size: var(--text-h1);
	font-weight: var(--fw-bold);
	margin: 0 0 1rem 0;
	padding: 0 0 1rem 0;
	font-family: var(--font-primary);
}

code {
	font-family: 'Courier New', Courier, monospace;
	font-size: var(--text-small);
	background-color: var(--bg-app);
	border: 1px solid var(--border-light);
	color: var(--text-primary);
	display: block;
	margin: 1rem 0;
	padding: 0.75rem;
	border-radius: var(--radius);
}

p {
	margin: 1rem 0;
	font-size: var(--text-body);
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
	// Check if this is a database error
	$is_db_error = false;
	$message_text = '';
	if (isset($message)) {
		if (is_array($message)) {
			$message_text = implode(' ', $message);
		} else {
			$message_text = $message;
		}
		$is_db_error = (stripos($message_text, 'Unknown database') !== false || 
		                stripos($message_text, 'database') !== false);
	}
	
	// Get vendor domain from URL if available
	$vendor_domain = '';
	$logout_url = 'auth/logout';
	if (isset($_SERVER['REQUEST_URI'])) {
		$uri = $_SERVER['REQUEST_URI'];
		// Extract vendor domain from URL (first segment after /books_erp/)
		if (preg_match('#/books_erp/([a-zA-Z0-9_\-]+)/#', $uri, $matches)) {
			$vendor_domain = $matches[1];
			$reserved = array('erp-admin', 'api', 'frontend', 'vendor', 'Vendor', 'auth', 'client-admin', 'school-admin');
			if (!in_array($vendor_domain, $reserved)) {
				$logout_url = $vendor_domain . '/logout';
			}
		}
	}
	?>
	<div id="container">
		<h1><?php echo isset($heading) ? $heading : 'An Error Occurred'; ?></h1>
		<?php echo isset($message) ? $message : '<p>An unexpected error occurred.</p>'; ?>
		<div class="links">
			<?php if (function_exists('base_url')): ?>
				<?php if ($is_db_error): ?>
					<a href="<?php echo base_url($logout_url); ?>" class="btn btn-primary">Go to Home (Logout)</a>
				<?php else: ?>
					<a href="<?php echo base_url(); ?>" class="btn btn-primary">Go to Home</a>
				<?php endif; ?>
				<a href="<?php echo site_url('erp-admin/auth/login'); ?>" class="btn btn-secondary">Super Admin</a>
			<?php else: ?>
				<?php if ($is_db_error): ?>
					<a href="/books_erp/<?php echo $logout_url; ?>" class="btn btn-primary">Go to Home (Logout)</a>
				<?php else: ?>
					<a href="/books_erp/" class="btn btn-primary">Go to Home</a>
				<?php endif; ?>
				<a href="/books_erp/erp-admin/auth/login" class="btn btn-secondary">Super Admin</a>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
