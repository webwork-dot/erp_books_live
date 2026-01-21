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

		// Calculate hover color (slightly darker than primary)
		$hover_r = max(0, floor($r * 0.9));
		$hover_g = max(0, floor($g * 0.9));
		$hover_b = max(0, floor($b * 0.9));
		$hover_color = sprintf('#%02x%02x%02x', $hover_r, $hover_g, $hover_b);

		// Calculate transparent color (light tint of the primary color)
		$transparent_r = min(255, floor($r + (255 - $r) * 0.95));
		$transparent_g = min(255, floor($g + (255 - $g) * 0.95));
		$transparent_b = min(255, floor($b + (255 - $b) * 0.95));
		$transparent_color = sprintf('#%02x%02x%02x', $transparent_r, $transparent_g, $transparent_b);

		$inline_style = '<style>
[data-sidebarbg="custom"] {
	--vendor-primary: ' . htmlspecialchars($sidebar_color) . ';
	--vendor-primary-dark: ' . htmlspecialchars($dark_color) . ';
	--vendor-primary-light: ' . htmlspecialchars($light_color) . ';
	--vendor-primary-hover: ' . htmlspecialchars($hover_color) . ';
	--vendor-primary-transparent: ' . htmlspecialchars($transparent_color) . ';
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
					<a href="<?php echo base_url('dashboard'); ?>" class="logo">
						<img src="<?php echo $logo_url; ?>" alt="Logo" onerror="this.src='<?php echo $logo_fallback; ?>'">
					</a>
					<a href="<?php echo base_url('dashboard'); ?>" class="dark-logo">
						<img src="<?php echo $logo_url; ?>" alt="Logo" onerror="this.src='<?php echo $logo_white_fallback; ?>'">
					</a>
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
									<?php 
									$vendor_domain = isset($current_vendor['domain']) ? $current_vendor['domain'] : '';
									// Extract base domain if it's a subdomain
									if (!empty($vendor_domain) && strpos($vendor_domain, '.') !== false) {
										$parts = explode('.', $vendor_domain);
										if (count($parts) >= 2 && $parts[0] === 'master') {
											array_shift($parts);
											$vendor_domain = implode('.', $parts);
										}
									}
									$this->load->helper('common');
									$home_url = base_url('dashboard');
									?>
									<li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo $home_url; ?>"><i class="isax isax-home-2 me-1"></i>Home</a></li>
									<?php if (isset($breadcrumb) && is_array($breadcrumb)): ?>
										<?php foreach ($breadcrumb as $item): ?>
											<li class="breadcrumb-item <?php echo isset($item['active']) && $item['active'] ? 'active' : ''; ?>" <?php echo isset($item['active']) && $item['active'] ? 'aria-current="page"' : ''; ?>>
												<?php if (isset($item['active']) && $item['active']): ?>
													<?php echo htmlspecialchars($item['label']); ?>
												<?php else: ?>
													<?php 
													$item_url = '#';
													if (isset($item['url']) && !empty($item['url'])) {
														// If URL already contains http/https, use as is
														if (strpos($item['url'], 'http') === 0) {
															$item_url = $item['url'];
														} else {
															// Use base_url for all URLs
															$item_url = base_url($item['url']);
														}
													}
													?>
													<a href="<?php echo $item_url; ?>"><?php echo htmlspecialchars($item['label']); ?></a>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									<?php else: ?>
										<li class="breadcrumb-item active" aria-current="page"><?php echo isset($title) ? htmlspecialchars($title) : 'Dashboard'; ?></li>
									<?php endif; ?>
								</ol>
							</nav>

						</div>

						<div class="d-flex align-items-center">

							<!-- Search -->
							<div class="input-icon-end position-relative me-2">
								<?php 
								$this->load->helper('common');
								$search_url = base_url('search');
								?>
								<form method="get" action="<?php echo $search_url; ?>" class="d-flex">
									<input type="text" name="q" class="form-control" placeholder="Search..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" style="min-width: 200px;">
									<span class="input-icon-addon">
										<i class="isax isax-search-normal"></i>
									</span>
								</form>
							</div>
							<!-- /Search -->

							<!-- User Dropdown -->
							<div class="dropdown profile-dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
									<?php 
									// Get vendor logo or use default user icon
									$user_logo = '';
									if (isset($current_vendor['logo']) && !empty($current_vendor['logo']) && file_exists(FCPATH . $current_vendor['logo'])) {
										$user_logo = base_url($current_vendor['logo']);
									}
									?>
									<?php if (!empty($user_logo)): ?>
										<span class="avatar avatar-md online">
											<img src="<?php echo $user_logo; ?>" alt="User" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: contain;">
										</span>
									<?php else: ?>
									<span class="avatar avatar-md online">
										<svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-fluid rounded-circle" style="color: #6c757d;">
											<path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="currentColor"/>
											<path d="M12.0002 14.5C6.99016 14.5 2.91016 17.86 2.91016 22C2.91016 22.28 3.13016 22.5 3.41016 22.5H20.5902C20.8702 22.5 21.0902 22.28 21.0902 22C21.0902 17.86 17.0102 14.5 12.0002 14.5Z" fill="currentColor"/>
										</svg>
									</span>
									<?php endif; ?>
									<span class="ms-2 d-none d-md-block">
										<span class="d-block fw-semibold"><?php echo isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'Vendor'; ?></span>
										<span class="d-block text-gray-9 fs-13"><?php echo isset($current_vendor['email']) ? htmlspecialchars($current_vendor['email']) : ''; ?></span>
									</span>
								</a>
								<div class="dropdown-menu dropdown-menu-end p-2">
									<?php 
									$logout_url = base_url('logout');
									?>
									<a class="dropdown-item d-flex align-items-center" href="<?php echo $logout_url; ?>">
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

