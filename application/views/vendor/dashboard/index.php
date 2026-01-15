<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Dashboard</h6>
	</div>
	<div>
		<span class="badge badge-<?php echo $current_vendor['status'] == 'active' ? 'success' : ($current_vendor['status'] == 'suspended' ? 'warning' : 'danger'); ?>">
			<?php echo ucfirst($current_vendor['status']); ?>
		</span>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Welcome Section -->
<div class="bg-primary rounded welcome-wrap position-relative mb-3">
	<div class="row">
		<div class="col-lg-8 col-md-9 col-sm-7">
			<div>
				<h5 class="text-white mb-1">Welcome, <?php echo isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'Vendor'; ?></h5>
				<p class="text-white mb-3">Welcome to Your Vendor Dashboard</p>
				<div class="d-flex align-items-center flex-wrap gap-3">
					<p class="d-flex align-items-center fs-13 text-white mb-0">
						<i class="isax isax-calendar5 me-1"></i><?php echo date('l, d M Y'); ?>
					</p>
					<p class="d-flex align-items-center fs-13 text-white mb-0">
						<i class="isax isax-clock5 me-1"></i><?php echo date('h:i A'); ?>
					</p>
					<?php if (isset($account_age_days) && $account_age_days > 0): ?>
						<p class="d-flex align-items-center fs-13 text-white mb-0">
							<i class="isax isax-calendar-tick me-1"></i>Member for <?php echo $account_age_months > 0 ? $account_age_months . ' month' . ($account_age_months > 1 ? 's' : '') : $account_age_days . ' day' . ($account_age_days > 1 ? 's' : ''); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-3 col-sm-5">
			<div class="position-absolute end-0 top-50 translate-middle-y p-2 d-none d-sm-block">
				<img src="<?php echo base_url('assets/template/img/icons/dashboard.svg'); ?>" alt="img">
			</div>
		</div>
	</div>
</div>

<!-- Individual Orders Section -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">
			<i class="isax isax-shopping-bag me-2"></i>Individual Orders
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-4 col-sm-6">
				<div class="card border-primary">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 me-2">
								<i class="isax isax-add-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">New Order</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_orders['new_order']) ? number_format($individual_orders['new_order']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-info">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-info-subtle text-info-emphasis flex-shrink-0 me-2">
								<i class="isax isax-refresh-2 fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Processing</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_orders['processing']) ? number_format($individual_orders['processing']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 me-2">
								<i class="isax isax-box-tick fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Ready For Ship</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_orders['ready_for_ship']) ? number_format($individual_orders['ready_for_ship']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-secondary">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-secondary-subtle text-secondary-emphasis flex-shrink-0 me-2">
								<i class="isax isax-truck-fast fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Out For Delivery</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_orders['out_for_delivery']) ? number_format($individual_orders['out_for_delivery']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 me-2">
								<i class="isax isax-tick-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Delivered</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_orders['delivered']) ? number_format($individual_orders['delivered']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Individual Products Section -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">
			<i class="isax isax-bag-2 me-2"></i>Individual Products
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-4 col-sm-6">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 me-2">
								<i class="isax isax-tick-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Active Products</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_products['active']) ? number_format($individual_products['active']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 me-2">
								<i class="isax isax-close-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Inactive Product</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_products['inactive']) ? number_format($individual_products['inactive']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 me-2">
								<i class="isax isax-box-remove fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Out Of Stock</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($individual_products['out_of_stock']) ? number_format($individual_products['out_of_stock']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Uniform Orders Section -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">
			<i class="isax isax-shopping-bag me-2"></i>Uniform Orders
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-4 col-sm-6">
				<div class="card border-primary">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 me-2">
								<i class="isax isax-add-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">New Order</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_orders['new_order']) ? number_format($uniform_orders['new_order']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-info">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-info-subtle text-info-emphasis flex-shrink-0 me-2">
								<i class="isax isax-refresh-2 fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Processing</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_orders['processing']) ? number_format($uniform_orders['processing']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 me-2">
								<i class="isax isax-box-tick fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Ready For Ship</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_orders['ready_for_ship']) ? number_format($uniform_orders['ready_for_ship']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-secondary">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-secondary-subtle text-secondary-emphasis flex-shrink-0 me-2">
								<i class="isax isax-truck-fast fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Out For Delivery</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_orders['out_for_delivery']) ? number_format($uniform_orders['out_for_delivery']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 me-2">
								<i class="isax isax-tick-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Delivered</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_orders['delivered']) ? number_format($uniform_orders['delivered']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Uniform Products Section -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">
			<i class="isax isax-bag-2 me-2"></i>Uniform Products
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-4 col-sm-6">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 me-2">
								<i class="isax isax-tick-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Active Products</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_products['active']) ? number_format($uniform_products['active']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 me-2">
								<i class="isax isax-close-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Inactive Product</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_products['inactive']) ? number_format($uniform_products['inactive']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="card border-warning">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 me-2">
								<i class="isax isax-box-remove fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Out Of Stock</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($uniform_products['out_of_stock']) ? number_format($uniform_products['out_of_stock']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- School Section -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">
			<i class="isax isax-building me-2"></i>School
		</h6>
	</div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-6 col-sm-6">
				<div class="card border-success">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 me-2">
								<i class="isax isax-tick-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Active School</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($schools['active']) ? number_format($schools['active']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6">
				<div class="card border-danger">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<span class="avatar avatar-44 avatar-rounded bg-danger-subtle text-danger-emphasis flex-shrink-0 me-2">
								<i class="isax isax-close-circle fs-20"></i>
							</span>
							<div class="flex-grow-1">
								<p class="mb-1 text-truncate text-gray-9 fs-13">Inactive School</p>
								<h6 class="fs-18 fw-semibold mb-0"><?php echo isset($schools['inactive']) ? number_format($schools['inactive']) : 0; ?></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
