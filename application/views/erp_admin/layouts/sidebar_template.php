		<!-- Sidenav Menu Start -->
		<div class="two-col-sidebar" id="two-col-sidebar">
			<div class="sidebar" id="sidebar-two">

				<!-- Start Logo -->
				<div class="sidebar-logo" style="background-color: #ffffff;">
					<a href="<?php echo base_url('erp-admin/dashboard'); ?>" class="logo logo-normal">
						<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" onerror="this.src='<?php echo base_url('assets/template/img/logo.svg'); ?>'">
					</a>
					<a href="<?php echo base_url('erp-admin/dashboard'); ?>" class="logo-small">
						<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" onerror="this.src='<?php echo base_url('assets/template/img/logo-small.svg'); ?>'">
					</a>
					<a href="<?php echo base_url('erp-admin/dashboard'); ?>" class="dark-logo">
						<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" onerror="this.src='<?php echo base_url('assets/template/img/logo-white.svg'); ?>'">
					</a>
					<a href="<?php echo base_url('erp-admin/dashboard'); ?>" class="dark-small">
						<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" onerror="this.src='<?php echo base_url('assets/template/img/logo-small-white.svg'); ?>'">
					</a>
					
					<!-- Sidebar Hover Menu Toggle Button -->
					<a id="toggle_btn" href="javascript:void(0);">
						<i class="isax isax-menu-1"></i>
					</a>
				</div>
				<!-- End Logo -->
						
				<!-- Search -->
				<div class="sidebar-search">
					<div class="input-icon-end position-relative">
						<input type="text" class="form-control" placeholder="Search">
						<span class="input-icon-addon">
							<i class="isax isax-search-normal"></i>
						</span>
					</div>
				</div>
				<!-- /Search -->

				<!--- Sidenav Menu -->
				<div class="sidebar-inner" data-simplebar>
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li class="menu-title"><span>Main</span></li>
							<li>
								<ul>
									<li class="submenu">
										<a href="javascript:void(0);" class="<?php echo (uri_string() == 'erp-admin/dashboard' || uri_string() == 'erp-admin') ? 'active subdrop' : ''; ?>">
											<i class="isax isax-element-45"></i><span>Dashboard</span>
											<span class="menu-arrow"></span>
										</a>
										<ul>
											<li><a href="<?php echo base_url('erp-admin/dashboard'); ?>" class="<?php echo (uri_string() == 'erp-admin/dashboard' || uri_string() == 'erp-admin') ? 'active' : ''; ?>">Admin Dashboard</a></li>
										</ul>
									</li>
									<li>
										<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="<?php echo (strpos(uri_string(), 'erp-admin/vendors') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-shapes5"></i><span>Vendors</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('erp-admin/features'); ?>" class="<?php echo (strpos(uri_string(), 'erp-admin/features') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-category-25"></i><span>Features</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('erp-admin/users'); ?>" class="<?php echo (strpos(uri_string(), 'erp-admin/users') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-profile-2user5"></i><span>Users</span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
						<div class="sidebar-footer">
                            <ul class="menu-list">
                                <li>
                                    <a href="<?php echo base_url('erp-admin/account-settings'); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Settings"><i class="isax isax-setting-25"></i></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Documentation"><i class="isax isax-document-normal4"></i></a>						
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Changelog"><i class="isax isax-cloud-change5"></i></a>						
                                </li>
                                <li>
                                    <a href="<?php echo base_url('erp-admin/auth/logout'); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Logout"><i class="isax isax-login-15"></i></a>				
                                </li>
                            </ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Sidenav Menu End -->

