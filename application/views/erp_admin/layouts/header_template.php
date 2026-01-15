<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($title) ? $title . ' | ' : ''; ?>ERP Admin - Multi-Tenant System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Multi-Tenant School Ecommerce ERP System">
	<meta name="keywords" content="erp, multi-tenant, school, ecommerce, management">
	<meta name="author" content="ERP Team">

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/template/img/favicon.png'); ?>">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('assets/template/img/apple-touch-icon.png'); ?>">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/bootstrap.min.css'); ?>">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/tabler-icons/tabler-icons.min.css'); ?>">

	<!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/daterangepicker/daterangepicker.css'); ?>">

	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/bootstrap-datetimepicker.min.css'); ?>">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/fontawesome/css/fontawesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/fontawesome/css/all.min.css'); ?>">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/simplebar/simplebar.min.css'); ?>">

	<!-- Select2 CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/plugins/select2/css/select2.min.css'); ?>">

	<!-- Iconsax CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/iconsax.css'); ?>">

	<!-- Main CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/style.css'); ?>">

	<!-- Custom Theme CSS (Override template styles) -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">

</head>

<body>

	<!-- Begin Wrapper -->
	<div class="main-wrapper">		

		<!-- Topbar Start -->
		<div class="header">						
			<div class="main-header">
				
				<!-- Logo - Removed from header -->
				<div class="header-left" style="display: none !important;">
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
									<li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo base_url('erp-admin/dashboard'); ?>"><i class="isax isax-home-2 me-1"></i>Home</a></li>
									<?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
										<?php foreach ($breadcrumb as $item): ?>
											<li class="breadcrumb-item <?php echo isset($item['active']) && $item['active'] ? 'active' : ''; ?>" <?php echo isset($item['active']) && $item['active'] ? 'aria-current="page"' : ''; ?>>
												<?php if (isset($item['url']) && !isset($item['active'])): ?>
													<a href="<?php echo base_url($item['url']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
												<?php else: ?>
													<?php echo htmlspecialchars($item['label']); ?>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									<?php else: ?>
										<li class="breadcrumb-item active" aria-current="page"><?php echo isset($title) ? $title : 'Dashboard'; ?></li>
									<?php endif; ?>
								</ol>
							</nav>	

						</div>
	
						<div class="d-flex align-items-center">	

							<!-- Search -->
							<div class="input-icon-end position-relative me-2">
								<input type="text" class="form-control" placeholder="Search">
								<span class="input-icon-addon">
									<i class="isax isax-search-normal"></i>
								</span>
							</div>
							<!-- /Search -->

							<!-- Notification -->
							<div class="notification_item me-2">
								<a href="#" class="btn btn-menubar position-relative" id="notification_popup" data-bs-toggle="dropdown" data-bs-auto-close="outside">
									<i class="isax isax-notification-bing5"></i>
									<span class="position-absolute badge bg-success border border-white"></span>
								</a>
								<div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">
							
									<div class="p-2 border-bottom">
										<div class="row align-items-center">
											<div class="col">
												<h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
											</div>
											<div class="col-auto">
												<div class="dropdown">
													<a href="#" class="dropdown-toggle drop-arrow-none link-dark" data-bs-toggle="dropdown" data-bs-offset="0,15" aria-expanded="false">
														<i class="isax isax-setting-2 fs-16 text-body align-middle"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-end">
														<a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-bell-check me-1"></i>Mark as Read</a>
														<a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-trash me-1"></i>Delete All</a>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<!-- Notification Dropdown -->
									<div class="notification-body position-relative z-2 rounded-0" data-simplebar>
										<div class="p-3 text-center">
											<p class="text-muted mb-0">No new notifications</p>
										</div>
									</div>
									
									<!-- View All-->
									<div class="p-2 rounded-bottom border-top text-center">
										<a href="javascript:void(0);" class="text-center fw-medium fs-14 mb-0">
											View All
										</a>
									</div>
									
								</div>
							</div>

							<!-- User Dropdown -->
							<div class="dropdown profile-dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown"  data-bs-auto-close="outside">
									<span class="avatar online">
										<img src="<?php echo base_url('assets/template/img/profiles/avatar-01.jpg'); ?>" alt="Img" class="img-fluid rounded-circle">
									</span>
								</a>
								<div class="dropdown-menu p-2">
									<div class="d-flex align-items-center bg-light rounded-1 p-2 mb-2">
										<span class="avatar avatar-lg me-2">
											<img src="<?php echo base_url('assets/template/img/profiles/avatar-01.jpg'); ?>" alt="img" class="rounded-circle" >
										</span>
										<div>
											<h6 class="fs-14 fw-medium mb-1"><?php echo isset($current_user['username']) ? htmlspecialchars($current_user['username']) : 'User'; ?></h6>
											<p class="fs-13">Administrator</p>
										</div>
									</div>

									<!-- Item-->
									<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('erp-admin/account-settings'); ?>">
										<i class="isax isax-profile-circle me-2"></i>Profile Settings
									</a>

									<!-- Item-->
									<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('erp-admin/reports'); ?>">
										<i class="isax isax-document-text me-2"></i>Reports
									</a>

									<hr class="dropdown-divider my-2">

									<!-- Item-->
									<a class="dropdown-item logout d-flex align-items-center" href="<?php echo base_url('erp-admin/auth/logout'); ?>">
										<i class="isax isax-logout me-2"></i>Sign Out
									</a>
								</div>
							</div>

						</div>
					</div>
				</div>

				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu profile-dropdown">
					<a href="javascript:void(0);" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown"  data-bs-auto-close="outside">
						<span class="avatar avatar-md online">
							<img src="<?php echo base_url('assets/template/img/profiles/avatar-01.jpg'); ?>" alt="Img" class="img-fluid rounded-circle">
						</span>
					</a>
					<div class="dropdown-menu p-2 mt-0">
						<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('erp-admin/profile'); ?>">
							<i class="isax isax-profile-circle me-2"></i>Profile Settings
						</a>
						<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('erp-admin/reports'); ?>">
							<i class="isax isax-document-text me-2"></i>Reports
						</a>
						<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('erp-admin/account-settings'); ?>">
							<i class="isax isax-setting me-2"></i>Settings
						</a>
						<a class="dropdown-item logout d-flex align-items-center" href="<?php echo base_url('erp-admin/auth/logout'); ?>">
							<i class="isax isax-logout me-2"></i>Signout
						</a>
					</div>
				</div>
				<!-- /Mobile Menu -->

			</div>
		</div>
		<!-- Topbar End -->

