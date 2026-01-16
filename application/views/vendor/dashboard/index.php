<div class="vendor-dashboard">

<!-- Welcome Section -->
<?php
// Set timezone to IST
date_default_timezone_set('Asia/Kolkata');
$ist_date = date('l, d M Y');
$ist_time = date('h:i A');
$vendor_name = isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'Vendor';
?>
<div class="card mb-2">
	<div class="card-body">
		<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
			<div class="d-flex align-items-center flex-wrap gap-3">
				<span class="text-muted">Welcome,</span>
				<span class="fw-semibold"><?php echo $vendor_name; ?></span>
			</div>
			<div class="d-flex align-items-center flex-wrap gap-3">
				<span class="d-flex align-items-center text-muted fs-13">
					<i class="isax isax-calendar5 me-1"></i><?php echo $ist_date; ?>
				</span>
				<span class="d-flex align-items-center text-muted fs-13">
					<i class="isax isax-clock5 me-1"></i><?php echo $ist_time; ?> IST
				</span>
			</div>
		</div>
	</div>
</div>

<!-- Individual Orders Section -->
<div style="border-bottom: 1px solid #e0e0e0;padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-shopping-bag me-2"></i>Individual Orders
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<div class="card border-primary">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">New Order</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_orders['new_order']) ? number_format($individual_orders['new_order']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 ms-2">
								<i class="isax isax-add-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-info">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Processing</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_orders['processing']) ? number_format($individual_orders['processing']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-info-subtle text-info-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-refresh-2 fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Ready For Ship</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_orders['ready_for_ship']) ? number_format($individual_orders['ready_for_ship']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-box-tick fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-secondary">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Out For Delivery</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_orders['out_for_delivery']) ? number_format($individual_orders['out_for_delivery']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-secondary-subtle text-secondary-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-truck-fast fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Delivered</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_orders['delivered']) ? number_format($individual_orders['delivered']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-tick-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Individual Products Section -->
<div style="border-bottom: 1px solid #e0e0e0;padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-bag-2 me-2"></i>Individual Products
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Active Products</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_products['active']) ? number_format($individual_products['active']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-tick-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Inactive Product</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_products['inactive']) ? number_format($individual_products['inactive']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-close-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Out Of Stock</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($individual_products['out_of_stock']) ? number_format($individual_products['out_of_stock']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-box-remove fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
// Dynamic Feature-based Cards
if (isset($feature_stats) && !empty($feature_stats)):
	foreach ($feature_stats as $feature_slug => $feature_data):
		$feature_name = isset($feature_data['name']) ? $feature_data['name'] : ucfirst($feature_slug);
		$feature_orders = isset($feature_data['orders']) ? $feature_data['orders'] : array();
		$feature_products = isset($feature_data['products']) ? $feature_data['products'] : array();
		
		// Feature Orders Section
		if (!empty($feature_orders)):
?>
<!-- <?php echo $feature_name; ?> Orders Section -->
<div style="border-bottom: 1px solid #e0e0e0;padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-shopping-bag me-2"></i><?php echo $feature_name; ?> Orders
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<div class="card border-primary">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">New Order</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_orders['new_order']) ? number_format($feature_orders['new_order']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 ms-2">
								<i class="isax isax-add-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-info">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Processing</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_orders['processing']) ? number_format($feature_orders['processing']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-info-subtle text-info-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-refresh-2 fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Ready For Ship</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_orders['ready_for_ship']) ? number_format($feature_orders['ready_for_ship']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-box-tick fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-secondary">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Out For Delivery</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_orders['out_for_delivery']) ? number_format($feature_orders['out_for_delivery']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-secondary-subtle text-secondary-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-truck-fast fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Delivered</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_orders['delivered']) ? number_format($feature_orders['delivered']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-tick-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
		endif;
		
		// Feature Products Section
		if (!empty($feature_products)):
?>
<!-- <?php echo $feature_name; ?> Products Section -->
<div style="border-bottom: 1px solid #e0e0e0;padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-bag-2 me-2"></i><?php echo $feature_name; ?> Products
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Active Products</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_products['active']) ? number_format($feature_products['active']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-tick-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Inactive Product</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_products['inactive']) ? number_format($feature_products['inactive']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-close-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Out Of Stock</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($feature_products['out_of_stock']) ? number_format($feature_products['out_of_stock']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-box-remove fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
		endif;
	endforeach;
endif;
?>

<!-- School Section -->
<div style="border-bottom: 1px solid #e0e0e0;padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-building me-2"></i>School
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Active School</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($schools['active']) ? number_format($schools['active']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-tick-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Inactive School</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($schools['inactive']) ? number_format($schools['inactive']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-close-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- End vendor-dashboard wrapper -->
