<div class="vendor-dashboard">

<style>
.order-counter-card {
	transition: all 0.3s ease;
}
.order-counter-card:hover {
	transform: translateY(-2px);
	box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.order-counter-card a {
	text-decoration: none;
	color: inherit;
}
</style>

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

<!-- Orders Section (universal for all product types) -->
<div style="border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-shopping-bag me-2"></i>Orders
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<a href="<?php echo base_url('orders/pending'); ?>" class="text-decoration-none">
					<div class="card border-primary order-counter-card" style="cursor: pointer;">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between">
								<div class="flex-grow-1">
									<p class="mb-0 text-truncate text-gray-9 fs-12">New Order</p>
									<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($individual_orders['new_order']) ? number_format($individual_orders['new_order']) : 0; ?></h6>
								</div>
								<span class="avatar avatar-32 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 ms-2">
									<i class="isax isax-add-circle fs-16"></i>
								</span>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<a href="<?php echo base_url('orders/processing'); ?>" class="text-decoration-none">
					<div class="card border-info order-counter-card" style="cursor: pointer;">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between">
								<div class="flex-grow-1">
									<p class="mb-0 text-truncate text-gray-9 fs-12">Processing</p>
									<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($individual_orders['processing']) ? number_format($individual_orders['processing']) : 0; ?></h6>
								</div>
								<span class="avatar avatar-32 avatar-rounded bg-info-subtle text-info-emphasis flex-shrink-0 ms-2">
									<i class="isax isax-refresh-2 fs-16"></i>
								</span>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<a href="<?php echo base_url('orders/ready_for_ship'); ?>" class="text-decoration-none">
					<div class="card border-warning order-counter-card" style="cursor: pointer;">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between">
								<div class="flex-grow-1">
									<p class="mb-0 text-truncate text-gray-9 fs-12">Ready For Shipment</p>
									<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($individual_orders['ready_for_ship']) ? number_format($individual_orders['ready_for_ship']) : 0; ?></h6>
								</div>
								<span class="avatar avatar-32 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 ms-2">
									<i class="isax isax-box-tick fs-16"></i>
								</span>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<a href="<?php echo base_url('orders/out_for_delivery'); ?>" class="text-decoration-none">
					<div class="card border-secondary order-counter-card" style="cursor: pointer;">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between">
								<div class="flex-grow-1">
									<p class="mb-0 text-truncate text-gray-9 fs-12">Out For Delivery</p>
									<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($individual_orders['out_for_delivery']) ? number_format($individual_orders['out_for_delivery']) : 0; ?></h6>
								</div>
								<span class="avatar avatar-32 avatar-rounded bg-secondary-subtle text-secondary-emphasis flex-shrink-0 ms-2">
									<i class="isax isax-truck-fast fs-16"></i>
								</span>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
				<a href="<?php echo base_url('orders/delivered'); ?>" class="text-decoration-none">
					<div class="card border-success order-counter-card" style="cursor: pointer;">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between">
								<div class="flex-grow-1">
									<p class="mb-0 text-truncate text-gray-9 fs-12">Delivered</p>
									<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($individual_orders['delivered']) ? number_format($individual_orders['delivered']) : 0; ?></h6>
								</div>
								<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
									<i class="isax isax-tick-circle fs-16"></i>
								</span>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>

<!-- Products Section (aggregated across all product types) -->
<div style="border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-bag-2 me-2"></i>Products
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(33.33% - 0.4rem); max-width: calc(33.33% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12">Active Products</p>
								<h6 class="fs-16 fw-semibold mb-0">
									<?php echo isset($product_totals['active']) ? number_format($product_totals['active']) : 0; ?>
								</h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-tick-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(33.33% - 0.4rem); max-width: calc(33.33% - 0.4rem);">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12">Inactive Product</p>
								<h6 class="fs-16 fw-semibold mb-0">
									<?php echo isset($product_totals['inactive']) ? number_format($product_totals['inactive']) : 0; ?>
								</h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-close-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(33.33% - 0.4rem); max-width: calc(33.33% - 0.4rem);">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12">Out Of Stock</p>
								<h6 class="fs-16 fw-semibold mb-0">
									<?php echo isset($product_totals['out_of_stock']) ? number_format($product_totals['out_of_stock']) : 0; ?>
								</h6>
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

<!-- School / Bookset Section -->
<div style="border-bottom: 1px solid #e0e0e0;padding-bottom: 10px;">
	<div class="card-header py-2">
		<h6 class="mb-0 fs-14">
			<i class="isax isax-building me-2"></i>School / Bookset
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-2">
			<div class="col-xl" style="flex: 0 0 calc(25% - 0.4rem); max-width: calc(25% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12">Active Schools</p>
								<h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($schools['active']) ? number_format($schools['active']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-tick-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(25% - 0.4rem); max-width: calc(25% - 0.4rem); width: calc(25% - 0.4rem);">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12">Inactive Schools</p>
								<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($schools['inactive']) ? number_format($schools['inactive']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-close-circle fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(25% - 0.4rem); max-width: calc(25% - 0.4rem);">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12">Active Bookset</p>
								<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($booksets['active']) ? number_format($booksets['active']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-book5 fs-16"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl" style="flex: 0 0 calc(25% - 0.4rem); max-width: calc(25% - 0.4rem);">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center justify-content-between">
							<div class="flex-grow-1">
								<p class="mb-0 text-truncate text-gray-9 fs-12">Inactive Bookset</p>
								<h6 class="fs-16 fw-semibold mb-0"><?php echo isset($booksets['inactive']) ? number_format($booksets['inactive']) : 0; ?></h6>
							</div>
							<span class="avatar avatar-32 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 ms-2">
								<i class="isax isax-book5 fs-16"></i>
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
