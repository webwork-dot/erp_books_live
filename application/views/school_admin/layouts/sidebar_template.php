<?php $this->load->helper('common'); ?>
<div class="two-col-sidebar" id="two-col-sidebar">
	<div class="sidebar" id="sidebar-two">
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

		<div class="sidebar-search">
			<div class="input-icon-end position-relative">
				<input type="text" class="form-control" placeholder="Search">
				<span class="input-icon-addon">
					<i class="isax isax-search-normal"></i>
				</span>
			</div>
		</div>

		<div class="sidebar-inner" data-simplebar style="display: flex; flex-direction: column; height: 100%;">
			<div id="sidebar-menu" class="sidebar-menu" style="flex: 1; overflow-y: auto;">
				<ul>
					<li class="menu-title"><span>Main</span></li>
					<li>
						<ul>
							<li>
								<a href="<?php echo base_url('school-admin/dashboard'); ?>" class="<?php echo (strpos(uri_string(), 'school-admin/dashboard') !== false || uri_string() == 'school-admin') ? 'active' : ''; ?>">
									<i class="isax isax-element-45"></i><span>Dashboard</span>
								</a>
							</li>
						</ul>
					</li>
					<li class="menu-title"><span>Management</span></li>
					<li>
						<ul>
							<?php
								$current_uri = uri_string();
								$is_orders_section = (strpos($current_uri, 'orders') !== false);
								$orders_parent_active = $is_orders_section ? 'active subdrop' : '';
								$orders_all_active = ($current_uri === 'orders' || $current_uri === 'orders/index') ? 'active' : '';
								$orders_pending_active = strpos($current_uri, 'orders/pending') !== false ? 'active' : '';
								$orders_processing_active = strpos($current_uri, 'orders/processing') !== false ? 'active' : '';
								$orders_out_for_delivery_active = strpos($current_uri, 'orders/out_for_delivery') !== false ? 'active' : '';
								$orders_delivered_active = strpos($current_uri, 'orders/delivered') !== false ? 'active' : '';
								$orders_return_active = strpos($current_uri, 'orders/return') !== false ? 'active' : '';
								$orders_cancelled_active = strpos($current_uri, 'orders/cancelled-orders') !== false ? 'active' : '';
							?>
							<li class="submenu">
								<a href="javascript:void(0);" class="<?php echo $orders_parent_active; ?>">
									<i class="isax isax-shopping-cart"></i><span>Orders</span>
									<span class="menu-arrow"></span>
								</a>
								<ul>
									<li>
										<a href="<?php echo base_url('school-admin/orders'); ?>" class="<?php echo $orders_all_active; ?>">
											All Orders
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('school-admin/orders/pending'); ?>" class="<?php echo $orders_pending_active; ?>">
											Pending Orders
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('school-admin/orders/processing'); ?>" class="<?php echo $orders_processing_active; ?>">
											Processing
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('school-admin/orders/out_for_delivery'); ?>" class="<?php echo $orders_out_for_delivery_active; ?>">
											Out for Delivery
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('school-admin/orders/delivered'); ?>" class="<?php echo $orders_delivered_active; ?>">
											Delivered
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('school-admin/orders/return'); ?>" class="<?php echo $orders_return_active; ?>">
											Return/Refund
										</a>
									</li>
									<li>
										<a href="<?php echo base_url('school-admin/orders/cancelled-orders'); ?>" class="<?php echo $orders_cancelled_active; ?>">
											Cancelled Orders
										</a>
									</li>
								</ul>
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
						$('.sidebar-menu a.subdrop').removeClass('subdrop');
						$('.sidebar-menu ul').not($submenu).slideUp(250);
						$link.addClass('subdrop');
						$submenu.slideDown(350);
					}
				}
				
				$(document).ready(function() {
					$('.sidebar-menu li.submenu > a').on('click', function(e) {
						if ($(e.target).hasClass('menu-arrow') || $(e.target).closest('.menu-arrow').length > 0) {
							e.preventDefault();
							e.stopPropagation();
							return false;
						}
					});
					
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
						<a href="<?php echo base_url('school-admin/auth/logout'); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Logout"><i class="isax isax-login-15"></i></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
