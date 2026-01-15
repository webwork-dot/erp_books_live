<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Orders Management</h6>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Statistics Cards -->
<div class="row mb-4">
	<div class="col-md-2 col-sm-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<span class="avatar avatar-44 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 me-2">
						<i class="isax isax-shopping-cart fs-20"></i>
					</span>
					<div>
						<p class="mb-1 text-truncate">Total Orders</p>
						<h6 class="fs-16 fw-semibold mb-0 text-truncate">
							<?php echo isset($statistics['total']) ? $statistics['total'] : 0; ?>
						</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2 col-sm-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<span class="avatar avatar-44 avatar-rounded bg-warning-subtle text-warning flex-shrink-0 me-2">
						<i class="isax isax-clock fs-20"></i>
					</span>
					<div>
						<p class="mb-1 text-truncate">Payment Pending</p>
						<h6 class="fs-16 fw-semibold mb-0 text-truncate">
							<?php echo isset($statistics['payment_pending']) ? $statistics['payment_pending'] : 0; ?>
						</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2 col-sm-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<span class="avatar avatar-44 avatar-rounded bg-danger-subtle text-danger flex-shrink-0 me-2">
						<i class="isax isax-close-circle fs-20"></i>
					</span>
					<div>
						<p class="mb-1 text-truncate">Payment Failed</p>
						<h6 class="fs-16 fw-semibold mb-0 text-truncate">
							<?php echo isset($statistics['payment_failed']) ? $statistics['payment_failed'] : 0; ?>
						</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2 col-sm-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success flex-shrink-0 me-2">
						<i class="isax isax-tick-circle fs-20"></i>
					</span>
					<div>
						<p class="mb-1 text-truncate">Payment Success</p>
						<h6 class="fs-16 fw-semibold mb-0 text-truncate">
							<?php echo isset($statistics['payment_success']) ? $statistics['payment_success'] : 0; ?>
						</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2 col-sm-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success flex-shrink-0 me-2">
						<i class="isax isax-truck-fast fs-20"></i>
					</span>
					<div>
						<p class="mb-1 text-truncate">Delivered</p>
						<h6 class="fs-16 fw-semibold mb-0 text-truncate">
							<?php echo isset($statistics['delivered']) ? $statistics['delivered'] : 0; ?>
						</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2 col-sm-6 d-flex">
		<div class="card flex-fill">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<span class="avatar avatar-44 avatar-rounded bg-danger-subtle text-danger flex-shrink-0 me-2">
						<i class="isax isax-close-square fs-20"></i>
					</span>
					<div>
						<p class="mb-1 text-truncate">Cancelled</p>
						<h6 class="fs-16 fw-semibold mb-0 text-truncate">
							<?php echo isset($statistics['cancelled']) ? $statistics['cancelled'] : 0; ?>
						</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Filter Tabs -->
<style>
	.orders-tabs-wrapper {
		background: #ffffff;
		border-bottom: 1px solid #dee2e6;
		margin-bottom: 1.5rem;
		padding: 0;
	}
	.orders-tabs {
		border-bottom: 1px solid #dee2e6;
		margin-bottom: 0;
		padding-left: 0;
		padding-right: 0;
	}
	.orders-tabs .nav-link {
		color: #6c757d;
		font-weight: 500;
		font-size: 0.875rem;
		padding: 0.75rem 1.25rem;
		border: 1px solid transparent;
		border-top-left-radius: 0.375rem;
		border-top-right-radius: 0.375rem;
		border-bottom: none;
		margin-bottom: -1px;
		transition: all 0.2s ease;
		background: transparent;
		position: relative;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		white-space: nowrap;
	}
	.orders-tabs .nav-link:hover {
		color: #495057;
		background: #f8f9fa;
		border-color: #dee2e6 #dee2e6 transparent;
	}
	.orders-tabs .nav-link.active {
		color: rgb(255, 255, 255);
		background: #3550dc;
		border-color: #dee2e6 #dee2e6 #ffffff;
		font-weight: 600;
		z-index: 1;
	}
	.orders-tabs .nav-link.active::after {
		content: '';
		position: absolute;
		bottom: -1px;
		left: 0;
		right: 0;
		height: 1px;
		background: #ffffff;
	}
	.orders-tabs .nav-link i {
		font-size: 1em;
	}
	.orders-tabs .nav-link.active i {
		color: #ffffff;
	}
	.orders-tabs .nav-item {
		margin-bottom: 0;
	}
	.badge-sm {
		font-size: 0.7rem;
		padding: 0.25rem 0.4rem;
		font-weight: 500;
		width: 80px;
		display: inline-block;
		text-align: center;
		box-sizing: border-box;
	}
	.table th:nth-child(1),
	.table td:nth-child(1) {
		width: 100px;
		padding: 0.5rem 0.4rem;
		font-size: 0.7rem;
		white-space: nowrap;
	}
	.table th:nth-child(7),
	.table th:nth-child(8),
	.table td:nth-child(7),
	.table td:nth-child(8) {
		width: 100px;
		padding: 0.5rem 0.25rem;
		text-align: center;
		white-space: nowrap;
	}

	.table tbody tr td {
    padding: 12px 7px 10px 7px !important;
}
</style>
<div class="orders-tabs-wrapper">
	<ul class="nav nav-tabs orders-tabs" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (!isset($filters['payment_status']) && !isset($filters['order_status'])) ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders' : 'orders'); ?>">
				<i class="isax isax-shopping-cart"></i>
				<span>All Orders</span>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (isset($filters['payment_status']) && $filters['payment_status'] == 'pending') ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders?payment_status=pending' : 'orders?payment_status=pending'); ?>">
				<i class="isax isax-clock"></i>
				<span>Payment Pending</span>
				<?php if (isset($statistics['payment_pending']) && $statistics['payment_pending'] > 0): ?>
					<span class="badge bg-warning ms-1"><?php echo $statistics['payment_pending']; ?></span>
				<?php endif; ?>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (isset($filters['payment_status']) && $filters['payment_status'] == 'failed') ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders?payment_status=failed' : 'orders?payment_status=failed'); ?>">
				<i class="isax isax-close-circle"></i>
				<span>Payment Failed</span>
				<?php if (isset($statistics['payment_failed']) && $statistics['payment_failed'] > 0): ?>
					<span class="badge bg-danger ms-1"><?php echo $statistics['payment_failed']; ?></span>
				<?php endif; ?>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (isset($filters['payment_status']) && $filters['payment_status'] == 'success') ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders?payment_status=success' : 'orders?payment_status=success'); ?>">
				<i class="isax isax-tick-circle"></i>
				<span>Payment Success</span>
				<?php if (isset($statistics['payment_success']) && $statistics['payment_success'] > 0): ?>
					<span class="badge bg-success ms-1"><?php echo $statistics['payment_success']; ?></span>
				<?php endif; ?>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (isset($filters['order_status']) && $filters['order_status'] == 'delivered') ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders?order_status=delivered' : 'orders?order_status=delivered'); ?>">
				<i class="isax isax-truck-fast"></i>
				<span>Delivered</span>
				<?php if (isset($statistics['delivered']) && $statistics['delivered'] > 0): ?>
					<span class="badge bg-success ms-1"><?php echo $statistics['delivered']; ?></span>
				<?php endif; ?>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (isset($filters['order_status']) && $filters['order_status'] == 'cancelled') ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders?order_status=cancelled' : 'orders?order_status=cancelled'); ?>">
				<i class="isax isax-close-square"></i>
				<span>Cancelled</span>
				<?php if (isset($statistics['cancelled']) && $statistics['cancelled'] > 0): ?>
					<span class="badge bg-danger ms-1"><?php echo $statistics['cancelled']; ?></span>
				<?php endif; ?>
			</a>
		</li>
	</ul>
</div>

<!-- Search Filter -->
<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders' : 'orders'); ?>" class="row gx-3">
			<?php if (isset($filters['payment_status'])): ?>
				<input type="hidden" name="payment_status" value="<?php echo htmlspecialchars($filters['payment_status']); ?>">
			<?php endif; ?>
			<?php if (isset($filters['order_status'])): ?>
				<input type="hidden" name="order_status" value="<?php echo htmlspecialchars($filters['order_status']); ?>">
			<?php endif; ?>
			<div class="col-lg-10 col-md-8">
				<div class="mb-0">
					<label class="form-label">Search Orders</label>
					<input type="text" name="search" class="form-control" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="School Name, Customer Name, Email...">
				</div>
			</div>
			<div class="col-lg-2 col-md-4">
				<div class="mb-0">
					<label class="form-label">&nbsp;</label>
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-primary flex-fill">Search</button>
						<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders' : 'orders'); ?>" class="btn btn-outline-secondary">Clear</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Orders List Table -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($orders)): ?>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Order Date</th>
							<th>School</th>
							<th>Customer Name</th>
							<th>Customer Email</th>
							<th>Customer Address</th>
							<th>Amount</th>
							<th style="width: 100px;">Payment Status</th>
							<th style="width: 100px;">Order Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($orders as $order): ?>
							<tr class="order-row" data-order-id="<?php echo $order['id']; ?>" style="cursor: pointer;">
								<td>
									<?php echo date('d/m/Y', strtotime($order['order_date'])); ?>
								</td>
								<td>
									<?php 
									$schoolParts = array();
									// School name first
									if (!empty($order['school_name'])) {
										$schoolParts[] = '<span class="fw-semibold">' . htmlspecialchars($order['school_name']) . '</span>';
									}
									// Branch name
									if (!empty($order['branch_name'])) {
										$schoolParts[] = '<span class="text-muted">' . htmlspecialchars($order['branch_name']) . '</span>';
									}
									// Board
									if (!empty($order['board_name'])) {
										$schoolParts[] = '<span class="text-muted">' . htmlspecialchars($order['board_name']) . '</span>';
									}
									// Grade
									if (!empty($order['grade_name'])) {
										$schoolParts[] = '<span class="text-muted">' . htmlspecialchars($order['grade_name']) . '</span>';
									}
									
									echo implode(' <span class="text-muted">-</span> ', $schoolParts);
									?>
								</td>
								<td>
									<?php echo !empty($order['customer_name']) ? htmlspecialchars($order['customer_name']) : '<span class="text-muted">-</span>'; ?>
								</td>
								<td>
									<?php if (!empty($order['customer_email'])): ?>
										<a href="mailto:<?php echo htmlspecialchars($order['customer_email']); ?>"><?php echo htmlspecialchars($order['customer_email']); ?></a>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!empty($order['customer_address'])): ?>
										<?php echo htmlspecialchars($order['customer_address']); ?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong>
								</td>
								<td>
									<?php
									$payment_badge_class = '';
									$payment_badge_text = '';
									switch($order['payment_status']) {
										case 'pending':
											$payment_badge_class = 'bg-warning';
											$payment_badge_text = 'Pending';
											break;
										case 'failed':
											$payment_badge_class = 'bg-danger';
											$payment_badge_text = 'Failed';
											break;
										case 'success':
											$payment_badge_class = 'bg-success';
											$payment_badge_text = 'Paid';
											break;
									}
									?>
									<span class="badge badge-sm <?php echo $payment_badge_class; ?>"><?php echo $payment_badge_text; ?></span>
								</td>
								<td>
									<?php
									$order_badge_class = '';
									$order_badge_text = '';
									switch($order['order_status']) {
										case 'pending':
											$order_badge_class = 'bg-secondary';
											$order_badge_text = 'Pending';
											break;
										case 'processing':
											$order_badge_class = 'bg-info';
											$order_badge_text = 'Processing';
											break;
										case 'delivered':
											$order_badge_class = 'bg-success';
											$order_badge_text = 'Delivered';
											break;
										case 'cancelled':
											$order_badge_class = 'bg-danger';
											$order_badge_text = 'Cancelled';
											break;
									}
									?>
									<span class="badge badge-sm <?php echo $order_badge_class; ?>"><?php echo $order_badge_text; ?></span>
								</td>
								<td class="text-end">
									<button type="button" class="btn btn-sm btn-outline-primary view-order-details" data-order-id="<?php echo $order['id']; ?>" onclick="event.stopPropagation();">
										<i class="isax isax-eye"></i> View
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php else: ?>
			<div class="text-center py-5">
				<i class="isax isax-shopping-cart fs-48 text-muted mb-3"></i>
				<h5 class="text-muted">No orders found</h5>
				<p class="text-muted">There are no orders matching your filters.</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- Pagination -->
<?php if (isset($total_pages) && $total_pages > 1): ?>
	<nav aria-label="Orders pagination" class="mt-4">
		<ul class="pagination justify-content-center">
			<?php
			$base_url = base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/orders' : 'orders');
			$query_params = array();
			if (isset($filters['payment_status'])) $query_params[] = 'payment_status=' . urlencode($filters['payment_status']);
			if (isset($filters['order_status'])) $query_params[] = 'order_status=' . urlencode($filters['order_status']);
			if (isset($filters['search'])) $query_params[] = 'search=' . urlencode($filters['search']);
			$query_string = !empty($query_params) ? '?' . implode('&', $query_params) : '';
			?>
			<li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
				<a class="page-link" href="<?php echo $base_url . $query_string . ($current_page > 1 ? ($query_string ? '&' : '?') . 'page=' . ($current_page - 1) : ''); ?>">Previous</a>
			</li>
			<?php for ($i = 1; $i <= $total_pages; $i++): ?>
				<?php if ($i == 1 || $i == $total_pages || ($i >= $current_page - 2 && $i <= $current_page + 2)): ?>
					<li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
						<a class="page-link" href="<?php echo $base_url . $query_string . ($query_string ? '&' : '?') . 'page=' . $i; ?>"><?php echo $i; ?></a>
					</li>
				<?php elseif ($i == $current_page - 3 || $i == $current_page + 3): ?>
					<li class="page-item disabled">
						<span class="page-link">...</span>
					</li>
				<?php endif; ?>
			<?php endfor; ?>
			<li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
				<a class="page-link" href="<?php echo $base_url . $query_string . ($query_string ? '&' : '?') . 'page=' . ($current_page + 1); ?>">Next</a>
			</li>
		</ul>
	</nav>
<?php endif; ?>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="orderDetailsContent">
				<div class="text-center py-5">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
					<p class="mt-3 text-muted">Loading order details...</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Handle view order details button clicks
	document.querySelectorAll('.view-order-details').forEach(function(button) {
		button.addEventListener('click', function() {
			var orderId = this.getAttribute('data-order-id');
			loadOrderDetails(orderId);
		});
	});
	
	// Handle order row clicks
	document.querySelectorAll('.order-row').forEach(function(row) {
		row.addEventListener('click', function(e) {
			// Don't trigger if clicking the view button or any button
			if (!e.target.closest('.view-order-details') && !e.target.closest('button')) {
				var orderId = this.getAttribute('data-order-id');
				loadOrderDetails(orderId);
			}
		});
	});
	
	function loadOrderDetails(orderId) {
		var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
		var contentDiv = document.getElementById('orderDetailsContent');
		
		// Show modal with loading state
		contentDiv.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3 text-muted">Loading order details...</p></div>';
		modal.show();
		
		// Fetch order details
		fetch('<?php echo base_url(isset($current_vendor["domain"]) ? $current_vendor["domain"] . "/orders/get_order_details/" : "orders/get_order_details/"); ?>' + orderId)
			.then(response => response.json())
			.then(data => {
				if (data.success && data.order) {
					displayOrderDetails(data.order);
				} else {
					contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load order details. Please try again.</div>';
				}
			})
			.catch(error => {
				console.error('Error:', error);
				contentDiv.innerHTML = '<div class="alert alert-danger">An error occurred while loading order details.</div>';
			});
	}
	
	function displayOrderDetails(order) {
		var content = '<div class="order-details">';
		
		// Order Header
		content += '<div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">';
		content += '<div>';
		content += '<h5 class="mb-1">' + escapeHtml(order.order_number) + '</h5>';
		content += '<p class="text-muted mb-0 small">Order Date: ' + formatDate(order.order_date) + '</p>';
		content += '</div>';
		content += '<div class="text-end">';
		
		// Payment Status Badge
		var paymentBadgeClass = '';
		var paymentBadgeText = '';
		switch(order.payment_status) {
			case 'pending':
				paymentBadgeClass = 'bg-warning';
				paymentBadgeText = 'Payment Pending';
				break;
			case 'failed':
				paymentBadgeClass = 'bg-danger';
				paymentBadgeText = 'Payment Failed';
				break;
			case 'success':
				paymentBadgeClass = 'bg-success';
				paymentBadgeText = 'Payment Success';
				break;
		}
		content += '<span class="badge ' + paymentBadgeClass + ' mb-2">' + paymentBadgeText + '</span><br>';
		
		// Order Status Badge
		var orderBadgeClass = '';
		var orderBadgeText = '';
		switch(order.order_status) {
			case 'pending':
				orderBadgeClass = 'bg-secondary';
				orderBadgeText = 'Pending';
				break;
			case 'processing':
				orderBadgeClass = 'bg-info';
				orderBadgeText = 'Processing';
				break;
			case 'delivered':
				orderBadgeClass = 'bg-success';
				orderBadgeText = 'Delivered';
				break;
			case 'cancelled':
				orderBadgeClass = 'bg-danger';
				orderBadgeText = 'Cancelled';
				break;
		}
		content += '<span class="badge ' + orderBadgeClass + '">' + orderBadgeText + '</span>';
		content += '</div>';
		content += '</div>';
		
		// Customer Information
		if (order.customer_name || order.customer_email || order.customer_address) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3">Customer Information</h6>';
			content += '<div class="row g-3">';
			if (order.customer_name) {
				content += '<div class="col-md-6"><strong>Name:</strong><br>' + escapeHtml(order.customer_name) + '</div>';
			}
			if (order.customer_email) {
				content += '<div class="col-md-6"><strong>Email:</strong><br><a href="mailto:' + escapeHtml(order.customer_email) + '">' + escapeHtml(order.customer_email) + '</a></div>';
			}
			if (order.customer_address) {
				content += '<div class="col-12"><strong>Address:</strong><br>' + escapeHtml(order.customer_address) + '</div>';
			}
			content += '</div>';
			content += '</div>';
		}
		
		// School Information
		content += '<div class="mb-4">';
		content += '<h6 class="mb-3">School Information</h6>';
		content += '<div class="row g-3">';
		content += '<div class="col-md-6"><strong>School Name:</strong><br>' + escapeHtml(order.school_name) + '</div>';
		if (order.grade_name) {
			content += '<div class="col-md-6"><strong>Grade:</strong><br>' + escapeHtml(order.grade_name) + '</div>';
		}
		if (order.board_name) {
			content += '<div class="col-md-6"><strong>Board:</strong><br>' + escapeHtml(order.board_name) + '</div>';
		}
		if (order.school_address) {
			content += '<div class="col-12"><strong>Address:</strong><br>' + escapeHtml(order.school_address) + '</div>';
		}
		if (order.school_phone) {
			content += '<div class="col-md-6"><strong>Phone:</strong><br>' + escapeHtml(order.school_phone) + '</div>';
		}
		if (order.school_email) {
			content += '<div class="col-md-6"><strong>Email:</strong><br>' + escapeHtml(order.school_email) + '</div>';
		}
		content += '</div>';
		content += '</div>';
		
		// Delivery Information
		if (order.delivery_address || order.delivery_city || order.delivery_state || order.delivery_pincode || order.delivery_phone) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3">Delivery Information</h6>';
			content += '<div class="row g-3">';
			if (order.delivery_address) {
				content += '<div class="col-12"><strong>Address:</strong><br>' + escapeHtml(order.delivery_address) + '</div>';
			}
			if (order.delivery_city || order.delivery_state || order.delivery_pincode) {
				var deliveryLocation = [];
				if (order.delivery_city) deliveryLocation.push(order.delivery_city);
				if (order.delivery_state) deliveryLocation.push(order.delivery_state);
				if (order.delivery_pincode) deliveryLocation.push(order.delivery_pincode);
				content += '<div class="col-md-6"><strong>Location:</strong><br>' + escapeHtml(deliveryLocation.join(', ')) + '</div>';
			}
			if (order.delivery_phone) {
				content += '<div class="col-md-6"><strong>Phone:</strong><br>' + escapeHtml(order.delivery_phone) + '</div>';
			}
			content += '</div>';
			content += '</div>';
		}
		
		// Order Items
		if (order.items && order.items.length > 0) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3">Order Items</h6>';
			content += '<div class="table-responsive">';
			content += '<table class="table table-bordered">';
			content += '<thead><tr><th>Product</th><th>SKU</th><th>Quantity</th><th>Unit Price</th><th>Total</th></tr></thead>';
			content += '<tbody>';
			order.items.forEach(function(item) {
				content += '<tr>';
				content += '<td>' + escapeHtml(item.product_name) + (item.display_name ? '<br><small class="text-muted">' + escapeHtml(item.display_name) + '</small>' : '') + '</td>';
				content += '<td>' + (item.sku ? escapeHtml(item.sku) : '-') + '</td>';
				content += '<td>' + item.quantity + '</td>';
				content += '<td>₹' + parseFloat(item.unit_price).toFixed(2) + '</td>';
				content += '<td>₹' + parseFloat(item.total).toFixed(2) + '</td>';
				content += '</tr>';
			});
			content += '</tbody>';
			content += '</table>';
			content += '</div>';
			content += '</div>';
		}
		
		// Payment Information
		content += '<div class="mb-4">';
		content += '<h6 class="mb-3">Payment Information</h6>';
		content += '<div class="row g-3">';
		if (order.payment_method) {
			content += '<div class="col-md-6"><strong>Payment Method:</strong><br>' + escapeHtml(order.payment_method) + '</div>';
		}
		if (order.payment_date) {
			content += '<div class="col-md-6"><strong>Payment Date:</strong><br>' + formatDate(order.payment_date) + '</div>';
		}
		content += '</div>';
		content += '</div>';
		
		// Order Summary
		content += '<div class="mb-4">';
		content += '<h6 class="mb-3">Order Summary</h6>';
		content += '<div class="row g-3">';
		content += '<div class="col-md-6"><strong>Subtotal:</strong></div>';
		content += '<div class="col-md-6 text-end">₹' + parseFloat(order.subtotal).toFixed(2) + '</div>';
		if (order.discount_amount > 0) {
			content += '<div class="col-md-6"><strong>Discount:</strong></div>';
			content += '<div class="col-md-6 text-end">-₹' + parseFloat(order.discount_amount).toFixed(2) + '</div>';
		}
		if (order.tax_amount > 0) {
			content += '<div class="col-md-6"><strong>Tax:</strong></div>';
			content += '<div class="col-md-6 text-end">₹' + parseFloat(order.tax_amount).toFixed(2) + '</div>';
		}
		content += '<div class="col-md-6"><strong>Total Amount:</strong></div>';
		content += '<div class="col-md-6 text-end"><h5 class="mb-0">₹' + parseFloat(order.total_amount).toFixed(2) + '</h5></div>';
		content += '</div>';
		content += '</div>';
		
		// Additional Information
		if (order.notes) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3">Notes</h6>';
			content += '<p class="mb-0">' + escapeHtml(order.notes) + '</p>';
			content += '</div>';
		}
		
		if (order.cancelled_at) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3 text-danger">Cancellation Information</h6>';
			content += '<div class="row g-3">';
			content += '<div class="col-md-6"><strong>Cancelled At:</strong><br>' + formatDate(order.cancelled_at) + '</div>';
			if (order.cancellation_reason) {
				content += '<div class="col-12"><strong>Reason:</strong><br>' + escapeHtml(order.cancellation_reason) + '</div>';
			}
			content += '</div>';
			content += '</div>';
		}
		
		if (order.delivered_at) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3 text-success">Delivery Information</h6>';
			content += '<div class="row g-3">';
			content += '<div class="col-md-6"><strong>Delivered At:</strong><br>' + formatDate(order.delivered_at) + '</div>';
			content += '</div>';
			content += '</div>';
		}
		
		content += '</div>';
		
		document.getElementById('orderDetailsContent').innerHTML = content;
	}
	
	function escapeHtml(text) {
		if (!text) return '';
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};
		return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
	}
	
	function formatDate(dateString) {
		if (!dateString) return '-';
		var date = new Date(dateString);
		var options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
		return date.toLocaleDateString('en-US', options);
	}
});
</script>

