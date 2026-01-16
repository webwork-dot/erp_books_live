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
<title>404 Page Not Found</title>
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
	<div id="container">
		<h1><?php echo isset($heading) ? $heading : '404 Page Not Found'; ?></h1>
		<?php echo isset($message) ? $message : '<p>The page you requested was not found.</p>'; ?>
		<div class="links">
			<?php if (function_exists('base_url')): ?>
				<a href="<?php echo base_url(); ?>" class="btn btn-primary">Go to Home</a>
				<a href="<?php echo site_url('erp-admin/auth/login'); ?>" class="btn btn-secondary">Super Admin</a>
			<?php else: ?>
				<a href="/" class="btn btn-primary">Go to Home</a>
				<a href="/auth/login" class="btn btn-secondary">Super Admin</a>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
