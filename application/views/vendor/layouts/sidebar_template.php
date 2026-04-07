		<!-- Sidenav Menu Start -->
		<?php $this->load->helper('common'); ?>
		<div class="two-col-sidebar" id="two-col-sidebar">
			<div class="sidebar" id="sidebar-two">

				<style>
				/* Sidebar section titles - clear, uppercase labels */
				#sidebar-two .sidebar-menu .menu-title {
					font-size: 0.7rem;
					font-weight: 600;
					letter-spacing: 0.05em;
					text-transform: uppercase;
					color: rgba(255,255,255,0.6);
					margin-top: 0.5rem;
				}
				#sidebar-two .sidebar-menu .menu-title:first-child { margin-top: 0; }
				#sidebar-two .sidebar-menu li a span { text-transform: none; }
				</style>

				<!-- Start Logo -->
				<div class="sidebar-logo" style="background-color:#ffffff;width:200px;">
					<?php
						$this->load->helper('common');
						$logo_url = get_simple_vendor_logo_url();
					?>

					<a href="<?= base_url('dashboard'); ?>" class="logo">
						<img
							src="<?= $logo_url; ?>"
							alt="Logo"
							style="max-width:100%; height:auto;"
							onerror="this.src='<?= base_url('assets/images/logo.png'); ?>'"
						>
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
							<?php
							$current_uri = uri_string();
							$is_orders_section = (strpos($current_uri, 'orders') !== false || (isset($current_page) && in_array($current_page, ['Orders', 'Order Details'])));
							$is_schools_section = (strpos($current_uri, 'schools') !== false || strpos($current_uri, 'branches') !== false || strpos($current_uri, 'boards') !== false);

							$orders_parent_active = $is_orders_section ? 'active subdrop' : '';
							$orders_all_active = ($current_uri === 'orders' || $current_uri === 'orders/index') ? 'active' : '';
							$orders_pending_active = strpos($current_uri, 'orders/pending') !== false ? 'active' : '';
							$orders_processing_active = strpos($current_uri, 'orders/processing') !== false ? 'active' : '';
							$orders_ready_active = strpos($current_uri, 'orders/ready_for_shipment') !== false ? 'active' : '';
							$orders_ofd_active = strpos($current_uri, 'orders/out_for_delivery') !== false ? 'active' : '';
							$orders_delivered_active = strpos($current_uri, 'orders/delivered') !== false ? 'active' : '';
							$orders_return_active = strpos($current_uri, 'orders/return') !== false ? 'active' : '';
							$orders_cancelled_active = strpos($current_uri, 'orders/cancelled-orders') !== false ? 'active' : '';

							$schools_parent_active = $is_schools_section ? 'active subdrop' : '';
							$schools_all_active = ($current_uri === 'schools' || $current_uri === 'schools/index') ? 'active' : '';
							$schools_branches_active = strpos($current_uri, 'branches') !== false ? 'active' : '';
							$schools_boards_active = strpos($current_uri, 'schools/boards') !== false ? 'active' : '';
							$schools_classes_active = (strpos($current_uri, 'schools/classes') !== false || strpos($current_uri, 'classes') !== false) ? 'active' : '';
							?>

							<li class="menu-title"><span>Overview</span></li>
							<li>
								<ul>
									<li>
										<a href="<?php echo base_url('dashboard'); ?>" class="<?php echo (strpos($current_uri, 'dashboard') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-element-45"></i><span>Dashboard</span>
										</a>
									</li>
								</ul>
							</li>

							<li class="menu-title"><span>Orders</span></li>
							<li>
								<ul>
									<li class="submenu">
										<a href="javascript:void(0);" class="<?php echo $orders_parent_active; ?>">
											<i class="isax isax-shopping-cart"></i><span>Orders</span>
											<span class="menu-arrow"></span>
										</a>
										<ul>
											<li><a href="<?php echo base_url('orders'); ?>" class="<?php echo $orders_all_active; ?>">All Orders</a></li>
											<li><a href="<?php echo base_url('orders/pending'); ?>" class="<?php echo $orders_pending_active; ?>">New Order</a></li>
											<li><a href="<?php echo base_url('orders/processing'); ?>" class="<?php echo $orders_processing_active; ?>">Processing</a></li>
											<li><a href="<?php echo base_url('orders/ready_for_shipment'); ?>" class="<?php echo $orders_ready_active; ?>">Ready for Shipment</a></li>
											<li><a href="<?php echo base_url('orders/out_for_delivery'); ?>" class="<?php echo $orders_ofd_active; ?>">Out for Delivery</a></li>
											<li><a href="<?php echo base_url('orders/delivered'); ?>" class="<?php echo $orders_delivered_active; ?>">Delivered</a></li>
											<li><a href="<?php echo base_url('orders/return'); ?>" class="<?php echo $orders_return_active; ?>">Return / Refund</a></li>
											<li><a href="<?php echo base_url('orders/cancelled-orders'); ?>" class="<?php echo $orders_cancelled_active; ?>">Cancelled</a></li>
										</ul>
									</li>
								</ul>
							</li>

							<li class="menu-title"><span>Customer & Support</span></li>
							<li>
								<ul>
									<li>
										<a href="<?php echo base_url('customers'); ?>" class="<?php echo (strpos($current_uri, 'customers') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-profile-2user5"></i><span>Customers</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('concerns'); ?>" class="<?php echo (strpos($current_uri, 'concerns') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-message-question"></i><span>Customer Concerns</span>
										</a>
									</li>
								</ul>
							</li>

							<li class="menu-title"><span>Operations</span></li>
							<li>
								<ul>
									<li class="submenu">
										<a href="javascript:void(0);" class="<?php echo $schools_parent_active; ?>">
											<i class="isax isax-building-4"></i><span>Schools</span>
											<span class="menu-arrow"></span>
										</a>
										<ul>
											<li><a href="<?php echo base_url('schools'); ?>" class="<?php echo $schools_all_active; ?>">All Schools</a></li>
											<li><a href="<?php echo base_url('branches'); ?>" class="<?php echo $schools_branches_active; ?>">Branches</a></li>
											<li><a href="<?php echo base_url('schools/boards'); ?>" class="<?php echo $schools_boards_active; ?>">Boards</a></li>
											<li><a href="<?php echo base_url('classes'); ?>" class="<?php echo $schools_classes_active; ?>">Classes</a></li>
										</ul>
									</li>
									<li>
										<a href="<?php echo base_url('couriers'); ?>" class="<?php echo (strpos($current_uri, 'couriers') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-truck"></i><span>Couriers</span>
										</a>
									</li>
								</ul>
							</li>

							<?php
							$pos_feature_enabled = false;
							if (isset($enabled_features) && is_array($enabled_features)) {
								foreach ($enabled_features as $feature_row) {
									$feature_slug = '';
									if (isset($feature_row['slug'])) {
										$feature_slug = strtolower(trim((string)$feature_row['slug']));
									} elseif (isset($feature_row['feature_slug'])) {
										$feature_slug = strtolower(trim((string)$feature_row['feature_slug']));
									}

									if ($feature_slug === 'pos') {
										$pos_feature_enabled = true;
										break;
									}
								}
							}
							?>
							<?php if ($pos_feature_enabled): ?>
							<li class="menu-title"><span>POS</span></li>
							<li>
								<ul>
									<li>
										<a href="<?php echo base_url('pos-agents'); ?>" class="<?php echo (strpos($current_uri, 'pos-agents') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-profile-2user"></i><span>POS Agents</span>
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('pos-school-qr'); ?>" class="<?php echo (strpos($current_uri, 'pos-school-qr') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-shop"></i><span>School UPI QR</span>
										</a>
									</li>
								</ul>
							</li>
							<?php endif; ?>
							<?php 
							$total_products_count = isset($total_products_count) ? $total_products_count : 0;
							$has_features = isset($enabled_features) && !empty($enabled_features);
							$show_products = ($total_products_count > 0 || $has_features);
							?>
							<?php if ($show_products): ?>
							<li class="menu-title"><span>Catalog</span></li>
							<li>
								<ul>
									<?php if (isset($enabled_features) && !empty($enabled_features)): ?>
									<?php foreach ($enabled_features as $feature): ?>
										<?php 
										// Skip Individual products - hidden but not removed
										if (strtolower($feature['slug']) == 'individual-products' || 
											strtolower($feature['slug']) == 'individual_products' ||
											strtolower($feature['slug']) == 'pos' ||
											stripos($feature['name'], 'Individual products') !== false ||
											stripos($feature['name'], 'Individual Products') !== false) {
											continue;
										}
										
										$has_subcategories = isset($enabled_subcategories[$feature['id']]) && !empty($enabled_subcategories[$feature['id']]);
										$is_active_parent = (strpos(uri_string(), 'products/' . $feature['slug']) !== false);
										
										// For uniforms and stationery, use specific routes
										$feature_url = '';
										if ($feature['slug'] == 'uniforms') {
											$feature_url = base_url('products/uniforms');
										} elseif ($feature['slug'] == 'stationery') {
											$feature_url = base_url('products/stationery');
										} else {
											$feature_url = base_url('products/' . $feature['slug']);
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
															<a href="<?php echo base_url('products/' . $feature['slug'] . '/' . $subcat['slug']); ?>" class="<?php echo (strpos(uri_string(), 'products/' . $feature['slug'] . '/' . $subcat['slug']) !== false) ? 'active' : ''; ?>">
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
											<?php if (strtolower(trim((string)$feature['slug'])) === 'uniforms'): ?>
												<li>
													<a href="<?php echo base_url('size-charts'); ?>" class="<?php echo (strpos($current_uri, 'size-charts') !== false || strpos($current_uri, 'size_charts') !== false) ? 'active' : ''; ?>">
														<i class="isax isax-ruler"></i><span>Size Charts</span>
													</a>
												</li>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
								</ul>
							</li>
							<?php endif; ?>

							<li class="menu-title"><span>Analytics</span></li>
							<li>
								<ul>
									<li>
										<a href="<?php echo base_url('reports'); ?>" class="<?php echo (strpos($current_uri, 'reports') !== false) ? 'active' : ''; ?>">
											<i class="isax isax-chart-2"></i><span>Reports</span>
										</a>
									</li>
								</ul>
							</li>
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
                                <a href="<?php echo base_url('logout'); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Logout"><i class="isax isax-login-15"></i></a>
                            </li>
                        </ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Sidenav Menu End -->

