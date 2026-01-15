<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($title) ? $title . ' | ' : ''; ?><?php echo isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'Vendor'; ?> Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Vendor Dashboard">
	<meta name="keywords" content="vendor, dashboard, erp">
	<meta name="author" content="ERP Team">

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/template/img/favicon.png'); ?>">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('assets/template/img/apple-touch-icon.png'); ?>">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/bootstrap.min.css'); ?>">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/tabler-icons/tabler-icons.min.css'); ?>">

	<!-- Fontawesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/simplebar/simplebar.min.css'); ?>">

	<!-- Select2 CSS (CDN) -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
	<!-- Select2 Bootstrap-5 Theme -->
	<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

	<!-- Iconsax CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/iconsax.css'); ?>">

	<!-- Main CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/style.css'); ?>">

	<!-- Custom Theme CSS (Override template styles) -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">
	
	<!-- Vendor Sidebar Color Customization -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/vendor-sidebar-colors.css'); ?>">
	
	<!-- Vendor Logo Fix - Responsive Logo Sizing -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/vendor-logo-fix.css'); ?>">
	
	<!-- jQuery (must be loaded early for sidebar and other scripts) -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<?php
	// Handle sidebar color - support both hex codes and predefined themes
	$sidebar_color = isset($current_vendor['sidebar_color']) && !empty($current_vendor['sidebar_color']) ? $current_vendor['sidebar_color'] : 'sidebarbg1';
	$is_hex_color = preg_match('/^#[0-9A-Fa-f]{6}$/', $sidebar_color);
	$body_attr = '';
	$inline_style = '';
	
	if ($is_hex_color) {
		// For hex codes, use a custom data attribute and apply via CSS variables
		$body_attr = 'data-sidebarbg="custom" data-custom-color="' . htmlspecialchars($sidebar_color) . '"';
		
		// Generate darker and lighter shades for theme consistency
		$hex = str_replace('#', '', $sidebar_color);
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
		
		// Calculate darker shade (reduce brightness by 20%)
		$dark_r = max(0, floor($r * 0.8));
		$dark_g = max(0, floor($g * 0.8));
		$dark_b = max(0, floor($b * 0.8));
		$dark_color = sprintf('#%02x%02x%02x', $dark_r, $dark_g, $dark_b);
		
		// Calculate lighter shade (increase brightness, blend with white)
		$light_r = min(255, floor($r + (255 - $r) * 0.85));
		$light_g = min(255, floor($g + (255 - $g) * 0.85));
		$light_b = min(255, floor($b + (255 - $b) * 0.85));
		$light_color = sprintf('#%02x%02x%02x', $light_r, $light_g, $light_b);
		
		$inline_style = '<style>
[data-sidebarbg="custom"] {
	--vendor-primary: ' . htmlspecialchars($sidebar_color) . ';
	--vendor-primary-dark: ' . htmlspecialchars($dark_color) . ';
	--vendor-primary-light: ' . htmlspecialchars($light_color) . ';
	--vendor-sidebar-bg: ' . htmlspecialchars($sidebar_color) . ';
	--vendor-sidebar-text: #ffffff;
	--vendor-sidebar-hover: rgba(255, 255, 255, 0.1);
	--vendor-sidebar-active: rgba(255, 255, 255, 0.15);
}
[data-sidebarbg="custom"] #two-col-sidebar {
	background-image: none !important;
	background-color: var(--vendor-sidebar-bg) !important;
}
[data-sidebarbg="custom"] #two-col-sidebar::before {
	display: none !important;
}
[data-sidebarbg="custom"] #two-col-sidebar .twocol-mini,
[data-sidebarbg="custom"] #two-col-sidebar .sidebar {
	background: var(--vendor-sidebar-bg) !important;
	background-image: none !important;
}
[data-sidebarbg="custom"] .sidebar .sidebar-menu,
[data-sidebarbg="custom"] .sidebar .sidebar-menu a,
[data-sidebarbg="custom"] .sidebar .sidebar-menu span,
[data-sidebarbg="custom"] .sidebar .sidebar-menu li {
	color: var(--vendor-sidebar-text) !important;
}
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li.menu-title {
	color: var(--vendor-sidebar-text) !important;
}
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li > a,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li ul li a {
	color: var(--vendor-sidebar-text) !important;
}
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li ul li a:hover,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul li .submenu > a.subdrop,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li > a:hover {
	background: var(--vendor-sidebar-hover) !important;
	color: var(--vendor-sidebar-text) !important;
}
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li ul > li.active a,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li.active > a {
	background: var(--vendor-sidebar-active) !important;
	color: var(--vendor-sidebar-text) !important;
}
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li ul li a i,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li ul li a svg,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li > a i,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li > a svg {
	color: var(--vendor-sidebar-text) !important;
}
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li > a span,
[data-sidebarbg="custom"] .sidebar .sidebar-menu > ul > li ul li a span {
	color: var(--vendor-sidebar-text) !important;
}
[data-sidebarbg="custom"] .btn-primary {
	background-color: var(--vendor-primary) !important;
	border-color: var(--vendor-primary) !important;
}
[data-sidebarbg="custom"] .btn-primary:hover {
	background-color: var(--vendor-primary-dark) !important;
	border-color: var(--vendor-primary-dark) !important;
}
[data-sidebarbg="custom"] .badge-primary,
[data-sidebarbg="custom"] .badge-info {
	background-color: var(--vendor-primary) !important;
}
[data-sidebarbg="custom"] .card-header {
	border-bottom-color: var(--vendor-primary-light) !important;
}
[data-sidebarbg="custom"] .welcome-banner,
[data-sidebarbg="custom"] [class*="banner"] {
	background: linear-gradient(135deg, var(--vendor-primary) 0%, var(--vendor-primary-dark) 100%) !important;
}
</style>';
	} else {
		// For predefined themes, use the standard data attribute
		$body_attr = 'data-sidebarbg="' . htmlspecialchars($sidebar_color) . '"';
	}
	?>
	<?php echo $inline_style; ?>

</head>

<body <?php echo $body_attr; ?>>

	<!-- Begin Wrapper -->
	<div class="main-wrapper">		

		<!-- Topbar Start -->
		<div class="header">						
			<div class="main-header">
				
				<!-- Logo -->
				<div class="header-left">
					<?php 
					// Use vendor logo if available, otherwise use default
					$logo_url = (isset($current_vendor['logo']) && !empty($current_vendor['logo']) && file_exists(FCPATH . $current_vendor['logo'])) 
						? base_url($current_vendor['logo']) 
						: base_url('assets/images/logo.png');
					$logo_fallback = base_url('assets/template/img/logo.svg');
					$logo_white_fallback = base_url('assets/template/img/logo-white.svg');
					?>
					<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>" class="logo">
						<img src="<?php echo $logo_url; ?>" alt="Logo" onerror="this.src='<?php echo $logo_fallback; ?>'">
					</a>
					<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>" class="dark-logo">
						<img src="<?php echo $logo_url; ?>" alt="Logo" onerror="this.src='<?php echo $logo_white_fallback; ?>'">
					</a>
					<div class="logo"><?php echo isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'Vendor'; ?></div>
				</div>

				<!-- Sidebar Menu Toggle Button -->
				<a id="mobile_btn" class="mobile_btn" href="#sidebar">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>

				<div class="header-user">
					<div class="nav user-menu nav-list">
						<div class="me-auto d-flex align-items-center" id="header-search">

							<!-- Breadcrumb -->
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb breadcrumb-divide mb-0">
									<li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>"><i class="isax isax-home-2 me-1"></i>Home</a></li>
									<?php if (isset($breadcrumb) && is_array($breadcrumb)): ?>
										<?php foreach ($breadcrumb as $item): ?>
											<li class="breadcrumb-item <?php echo isset($item['active']) && $item['active'] ? 'active' : ''; ?>" <?php echo isset($item['active']) && $item['active'] ? 'aria-current="page"' : ''; ?>>
												<?php if (isset($item['active']) && $item['active']): ?>
													<?php echo htmlspecialchars($item['label']); ?>
												<?php else: ?>
													<a href="<?php echo isset($item['url']) ? base_url($item['url']) : '#'; ?>"><?php echo htmlspecialchars($item['label']); ?></a>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									<?php endif; ?>
								</ol>
							</nav>

						</div>

						<div class="d-flex align-items-center">

							<!-- User Dropdown -->
							<div class="dropdown profile-dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
									<span class="avatar avatar-md online">
										<img src="<?php echo base_url('assets/template/img/profiles/avatar-01.jpg'); ?>" alt="Img" class="img-fluid rounded-circle">
									</span>
									<span class="ms-2 d-none d-md-block">
										<span class="d-block fw-semibold"><?php echo isset($current_vendor['username']) ? htmlspecialchars($current_vendor['username']) : 'Vendor'; ?></span>
										<span class="d-block text-gray-9 fs-13"><?php echo isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'Vendor'; ?></span>
									</span>
								</a>
								<div class="dropdown-menu dropdown-menu-end p-2">
									<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/logout' : 'logout'); ?>">
										<i class="isax isax-logout me-2"></i>Sign Out
									</a>
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
		<!-- Topbar End -->

