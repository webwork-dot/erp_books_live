		<!-- Sidenav Menu Start -->
		<div class="two-col-sidebar" id="two-col-sidebar">
			<div class="sidebar" id="sidebar-two">

				<!-- Start Logo -->
				<div class="sidebar-logo" style="background-color: #ffffff;">
					<?php 
					// Only show logo if vendor has a logo, otherwise leave empty
					$has_logo = (isset($current_vendor['logo']) && !empty($current_vendor['logo']) && file_exists(FCPATH . $current_vendor['logo']));
					$logo_url = $has_logo ? base_url($current_vendor['logo']) : '';
					?>
					<?php if ($has_logo): ?>
					<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>" class="logo logo-normal">
						<img src="<?php echo $logo_url; ?>" alt="Logo">
					</a>
					<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>" class="logo-small">
						<img src="<?php echo $logo_url; ?>" alt="Logo">
					</a>
					<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>" class="dark-logo">
						<img src="<?php echo $logo_url; ?>" alt="Logo">
					</a>
					<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>" class="dark-small">
						<img src="<?php echo $logo_url; ?>" alt="Logo">
					</a>
					<?php endif; ?>
					
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
				<div class="sidebar-inner" data-simplebar style="display: flex; flex-direction: column; height: 100%;">
					<div id="sidebar-menu" class="sidebar-menu" style="flex: 1; overflow-y: auto;">
						<ul>
							<li class="menu-title"><span>Main</span></li>
							<li>
								<ul>
									<li>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/dashboard' : 'dashboard'); ?>" class="<?php echo (strpos(uri_string(), 'dashboard') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-element-45"></i><span>Dashboard</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/profile' : 'profile'); ?>" class="<?php echo (strpos(uri_string(), 'profile') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-profile-2user5"></i><span>Profile</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/settings' : 'settings'); ?>" class="<?php echo (strpos(uri_string(), 'settings') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-setting-2"></i><span>Settings</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/site-settings' : 'site-settings'); ?>" class="<?php echo (strpos(uri_string(), 'site-settings') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-monitor"></i><span>Live Site Settings</span>
										</a>
									</li>
								</ul>
							</li>
							<li class="menu-title"><span>Management</span></li>
							<li>
								<ul>
									<li class="submenu">
										<a href="javascript:void(0);" class="<?php echo (strpos(uri_string(), 'schools') !== false || strpos(uri_string(), 'boards') !== false || strpos(uri_string(), 'branches') !== false) ? 'active subdrop' : ''; ?>">
											<i class="isax isax-building-4"></i><span>Schools</span>
											<span class="menu-arrow"></span>
										</a>
										<ul>
											<li>
												<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/schools' : 'schools'); ?>" class="<?php echo (strpos(uri_string(), 'schools') !== false && strpos(uri_string(), 'boards') === false && strpos(uri_string(), 'branches') === false) ? 'active' : ''; ?>">
													All Schools
												</a>
											</li>
											<li>
												<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/branches' : 'branches'); ?>" class="<?php echo (strpos(uri_string(), 'branches') !== false) ? 'active' : ''; ?>">
													Branches
												</a>
											</li>
											<li>
												<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/schools/boards' : 'schools/boards'); ?>" class="<?php echo (strpos(uri_string(), 'boards') !== false) ? 'active' : ''; ?>">
													Boards
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders' : 'orders'); ?>" class="<?php echo (strpos(uri_string(), 'orders') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-shopping-cart"></i><span>Orders</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/customers/list' : 'customers/list'); ?>" class="<?php echo (strpos(uri_string(), 'customers') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-profile-2user5"></i><span>Customers</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/features' : 'features'); ?>" class="<?php echo (strpos(uri_string(), 'features') !== false && strpos(uri_string(), 'products/') === false) ? 'active' : ''; ?>">
											<i class="isax isax-image"></i><span>Feature Images</span>
										</a>
									</li>
								</ul>
							</li>
							<?php 
							// Show Products section only if vendor has products OR has features assigned
							$total_products_count = isset($total_products_count) ? $total_products_count : 0;
							$has_features = isset($enabled_features) && !empty($enabled_features);
							$show_products = ($total_products_count > 0 || $has_features);
							
							// Debug: Log feature count
							if (function_exists('log_message')) {
								log_message('debug', 'Sidebar - enabled_features isset: ' . (isset($enabled_features) ? 'YES' : 'NO') . ', count: ' . (isset($enabled_features) ? count($enabled_features) : 0) . ', has_features: ' . ($has_features ? 'YES' : 'NO'));
							}
							?>
							<?php if ($show_products): ?>
							<li class="menu-title"><span>Products</span></li>
							<li>
								<ul>
									<?php if (isset($enabled_features) && !empty($enabled_features)): ?>
									<?php foreach ($enabled_features as $feature): ?>
										<?php 
										$has_subcategories = isset($enabled_subcategories[$feature['id']]) && !empty($enabled_subcategories[$feature['id']]);
										$is_active_parent = (strpos(uri_string(), 'products/' . $feature['slug']) !== false);
										
										// For uniforms and stationery, use specific routes
										$feature_url = '';
										if ($feature['slug'] == 'uniforms') {
											$feature_url = base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/uniforms' : 'products/uniforms');
										} elseif ($feature['slug'] == 'stationery') {
											$feature_url = base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery' : 'products/stationery');
										} else {
											$feature_url = base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/' . $feature['slug'] : 'products/' . $feature['slug']);
										}
										?>
										<?php if ($has_subcategories): ?>
											<li class="submenu">
												<a href="<?php echo $feature_url; ?>" class="<?php echo $is_active_parent ? 'active subdrop' : ''; ?>">
													<i class="isax isax-box"></i><span><?php echo htmlspecialchars($feature['name']); ?></span>
													<span class="menu-arrow" onclick="event.stopPropagation(); event.preventDefault(); toggleSubmenu(this);" style="cursor: pointer; pointer-events: auto;"></span>
												</a>
												<ul>
													<?php foreach ($enabled_subcategories[$feature['id']] as $subcat): ?>
														<li>
															<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/' . $feature['slug'] . '/' . $subcat['slug'] : 'products/' . $feature['slug'] . '/' . $subcat['slug']); ?>" class="<?php echo (strpos(uri_string(), 'products/' . $feature['slug'] . '/' . $subcat['slug']) !== false) ? 'active' : ''; ?>">
																<?php echo htmlspecialchars($subcat['name']); ?>
															</a>
														</li>
													<?php endforeach; ?>
												</ul>
											</li>
										<?php else: ?>
											<li>
												<a href="<?php echo $feature_url; ?>" class="<?php echo $is_active_parent ? 'active' : ''; ?>">
													<i class="isax isax-box"></i><span><?php echo htmlspecialchars($feature['name']); ?></span>
												</a>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
								</ul>
							</li>
							<?php endif; ?>
						</ul>
						<script>
						function toggleSubmenu(arrowElement) {
							var $arrow = $(arrowElement);
							var $link = $arrow.parent('a');
							var $submenu = $link.next('ul');
							
							if ($link.hasClass('subdrop')) {
								$link.removeClass('subdrop');
								$submenu.slideUp(350);
							} else {
								// Close other submenus
								$('.sidebar-menu a.subdrop').removeClass('subdrop');
								$('.sidebar-menu ul').not($submenu).slideUp(250);
								
								// Open this submenu
								$link.addClass('subdrop');
								$submenu.slideDown(350);
							}
						}
						
						// Handle clicks on submenu links - allow navigation but toggle dropdown on arrow
						$(document).ready(function() {
							$('.sidebar-menu li.submenu > a').on('click', function(e) {
								// If clicking the arrow, toggle dropdown
								if ($(e.target).hasClass('menu-arrow') || $(e.target).closest('.menu-arrow').length > 0) {
									e.preventDefault();
									e.stopPropagation();
									return false;
								}
								// Otherwise, allow normal navigation
							});
							
							// Auto-expand submenu if active
							$('.sidebar-menu li.submenu a.active').each(function() {
								var $link = $(this);
								if (!$link.hasClass('subdrop')) {
									$link.addClass('subdrop');
									$link.next('ul').slideDown(350);
								}
							});
						});
						</script>
					</div>
					<div class="sidebar-footer" style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                        <ul class="menu-list">
                            <li>
                                <a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/logout' : 'logout'); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Logout"><i class="isax isax-login-15"></i></a>
                            </li>
                        </ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Sidenav Menu End -->

