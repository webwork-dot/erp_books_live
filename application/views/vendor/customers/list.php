<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Customers</h6>
	</div>
</div>
<!-- End Header -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<?php echo form_open(base_url('customers/list'), array('method' => 'get')); ?>
		<div class="row g-3">
			<div class="col-md-4">
				<label class="form-label">Search</label>
				<input type="text" name="search" class="form-control" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="Username, firm name, email, phone...">
			</div>
			<div class="col-md-3">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="">All Status</option>
					<option value="1" <?php echo (isset($filters['status']) && $filters['status'] == '1') ? 'selected' : ''; ?>>Active</option>
					<option value="0" <?php echo (isset($filters['status']) && $filters['status'] == '0') ? 'selected' : ''; ?>>Inactive</option>
				</select>
			</div>
			<div class="col-md-3 d-flex align-items-end">
				<button type="submit" class="btn btn-primary me-2">
					<i class="isax isax-search-normal me-1"></i>Filter
				</button>
				<a href="<?php echo base_url('customers/list'); ?>" class="btn btn-outline-secondary">
					<i class="isax isax-refresh me-1"></i>Reset
				</a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<!-- Customers List -->
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>SR No.</th>
						<th>Username</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Status</th>
						<th>Created</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($customers)): ?>
						<?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($customers as $customer): ?>
							<tr>
								<td><?php echo $sr_no++; ?></td>
								
								<td>
									<strong><?php echo htmlspecialchars($customer['username'] ? $customer['username'] : 'N/A'); ?></strong>
								</td>
								<td>
									<?php echo htmlspecialchars($customer['email']); ?>
									
								</td>
								<td>
									<?php if ($customer['phone_number']): ?>
										<?php echo htmlspecialchars($customer['dial_code'] . ' ' . $customer['phone_number']); ?>
									<?php else: ?>
										<span class="text-muted">N/A</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($customer['status'] == 1): ?>
										<span class="badge badge-success">Active</span>
									<?php else: ?>
										<span class="badge badge-danger">Inactive</span>
									<?php endif; ?>
									<?php if (isset($customer['banned']) && $customer['banned'] == 1): ?>
										<br><small class="text-danger"><i class="isax isax-danger"></i> Banned</small>
									<?php endif; ?>
								</td>
								<td><?php echo date('d M Y', strtotime($customer['created_at'])); ?></td>
								<td class="text-end">
									<a href="#" class="btn btn-sm btn-outline-primary view-customer-details" data-customer-id="<?php echo $customer['id']; ?>" data-bs-toggle="tooltip" title="View Details">
										<i class="isax isax-eye"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="10" class="text-center text-muted py-4">
								<i class="isax isax-profile-2user5 fs-48 mb-2"></i>
								<p>No customers found.</p>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<!-- Pagination -->
		<?php if ($total_pages > 1): ?>
			<nav aria-label="Page navigation">
				<ul class="pagination justify-content-center mt-4">
					<?php if ($current_page > 1): ?>
						<li class="page-item">
							<a class="page-link" href="<?php echo base_url('customers/list?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">
								<i class="isax isax-arrow-left-2"></i> Previous
							</a>
						</li>
					<?php else: ?>
						<li class="page-item disabled">
							<span class="page-link"><i class="isax isax-arrow-left-2"></i> Previous</span>
						</li>
					<?php endif; ?>
					
					<?php
					$start_page = max(1, $current_page - 2);
					$end_page = min($total_pages, $current_page + 2);
					
					for ($i = $start_page; $i <= $end_page; $i++):
					?>
						<li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
							<a class="page-link" href="<?php echo base_url('customers/list?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>">
								<?php echo $i; ?>
							</a>
						</li>
					<?php endfor; ?>
					
					<?php if ($current_page < $total_pages): ?>
						<li class="page-item">
							<a class="page-link" href="<?php echo base_url('customers/list?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">
								Next <i class="isax isax-arrow-right-2"></i>
							</a>
						</li>
					<?php else: ?>
						<li class="page-item disabled">
							<span class="page-link">Next <i class="isax isax-arrow-right-2"></i></span>
						</li>
					<?php endif; ?>
				</ul>
			</nav>
			<div class="text-center text-muted mt-2">
				Showing <?php echo (($current_page - 1) * $per_page) + 1; ?> to <?php echo min($current_page * $per_page, $total_customers); ?> of <?php echo $total_customers; ?> customers
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- Customer Details Modal -->
<div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-labelledby="customerDetailsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="customerDetailsModalLabel">Customer Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="customerDetailsContent">
				<div class="text-center py-5">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
					<p class="mt-3 text-muted">Loading customer details...</p>
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
	// Handle view customer details button clicks
	document.querySelectorAll('.view-customer-details').forEach(function(button) {
		button.addEventListener('click', function(e) {
			e.preventDefault();
			var customerId = this.getAttribute('data-customer-id');
			loadCustomerDetails(customerId);
		});
	});
	
	function loadCustomerDetails(customerId) {
		var modal = new bootstrap.Modal(document.getElementById('customerDetailsModal'));
		var contentDiv = document.getElementById('customerDetailsContent');
		
		// Show modal with loading state
		contentDiv.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-3 text-muted">Loading customer details...</p></div>';
		modal.show();
		
		// Fetch customer details
		fetch('<?php echo base_url("customers/get_customer_details/"); ?>' + customerId)
			.then(response => response.json())
			.then(data => {
				if (data.success && data.customer) {
					displayCustomerDetails(data.customer);
				} else {
					contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load customer details. Please try again.</div>';
				}
			})
			.catch(error => {
				console.error('Error:', error);
				contentDiv.innerHTML = '<div class="alert alert-danger">An error occurred while loading customer details.</div>';
			});
	}
	
	function displayCustomerDetails(customer) {
		var content = '';

		
		// Customer Information Card - Compact
		content += '<div class="card mb-4">';
		content += '<div class="row g-3">';
		content += '<div class="col-md-3">';
		content += '<div class="d-flex align-items-center">';
		content += '<i class="isax isax-user me-2 text-primary"></i>';
		content += '<div style="display: flex; align-items: center;"><strong>Name: </strong><span>' + escapeHtml(customer.username || 'N/A') + '</span></div>';
		content += '</div>';
		content += '</div>';
		content += '<div class="col-md-3">';
		content += '<div class="d-flex align-items-center">';
		content += '<i class="isax isax-call me-2 text-primary"></i>';
		content += '<div style="display: flex; align-items: center;"><strong>Phone: </strong><span>' + escapeHtml((customer.dial_code || '') + ' ' + (customer.phone_number || 'N/A')) + '</span></div>';
		content += '</div>';
		content += '</div>';
		content += '<div class="col-md-3">';
		content += '<div class="d-flex align-items-center">';
		content += '<i class="isax isax-sms me-2 text-primary"></i>';
		content += '<div style="display: flex; align-items: center;"><strong>Email: </strong><span>' + escapeHtml(customer.email || 'N/A');
		if (customer.email_status == 1) {
			content += ' <span class="badge bg-success">Verified</span>';
		}
		content += '</span></div>';
		content += '</div>';
		content += '</div>';
		content += '<div class="col-md-3">';
		content += '<div class="d-flex align-items-center">';
		content += '<i class="isax isax-calendar me-2 text-primary"></i>';
		content += '<div style="display: flex; align-items: center;"><strong>Member Since: </strong><span>' + formatDate(customer.created_at) + '</span></div>';
		content += '</div>';
		content += '</div>';
		content += '</div>';
		content += '</div>';
		
		// Addresses Section - 3 per row
		content += '<div class="mb-4">';
		content += '<h6 class="mb-3"><i class="isax isax-location me-2"></i>Addresses (' + (customer.addresses ? customer.addresses.length : 0) + ')</h6>';
		if (customer.addresses && customer.addresses.length > 0) {
			content += '<div class="row g-3">';
			customer.addresses.forEach(function(address, index) {
				content += '<div class="col-md-4">';
				content += '<div class="border rounded p-3 h-100">';
				content += '<div class="d-flex justify-content-between align-items-start mb-2">';
				content += '<h6 class="mb-0"><i class="isax isax-location me-2 text-primary"></i>' + escapeHtml(address.name || 'Address ' + (index + 1)) + '</h6>';
				content += '</div>';
				content += '<div class="d-flex align-items-center mb-2">';
				content += '<i class="isax isax-call me-2 text-primary"></i>';
				content += '<div class="flex-fill">';
				content += '<strong>Phone:</strong> ';
				content += '<small>' + escapeHtml((address.dial_code || '') + ' ' + (address.phone || 'N/A')) + '</small>';
				content += '</div>';
				content += '</div>';
				if (address.alternate_phone) {
					content += '<div class="d-flex align-items-center mb-2">';
					content += '<i class="isax isax-call me-2 text-muted"></i>';
					content += '<div class="flex-fill">';
					content += '<strong>Alt Phone:</strong> ';
					content += '<small class="text-muted">' + escapeHtml((address.alternate_dial_code || '') + ' ' + address.alternate_phone) + '</small>';
					content += '</div>';
					content += '</div>';
				}
				if (address.email) {
					content += '<div class="d-flex align-items-center mb-2">';
					content += '<i class="isax isax-sms me-2 text-primary"></i>';
					content += '<div class="flex-fill">';
					content += '<strong>Email:</strong> ';
					content += '<small>' + escapeHtml(address.email) + '</small>';
					content += '</div>';
					content += '</div>';
				}
				content += '<div class="d-flex align-items-start mb-2">';
				content += '<i class="isax isax-location me-2 text-primary mt-1"></i>';
				content += '<div class="flex-fill">';
				content += '<strong>Address:</strong><br>';
				content += '<small>';
				if (address.building_name) {
					content += escapeHtml(address.building_name) + ', ';
				}
				if (address.flat_house_no) {
					content += escapeHtml(address.flat_house_no) + ', ';
				}
				content += escapeHtml(address.address || '');
				if (address.area || address.city || address.state || address.pincode) {
					content += '<br>';
				}
				if (address.area) {
					content += escapeHtml(address.area) + ', ';
				}
				content += escapeHtml((address.city || '') + (address.city && address.state ? ', ' : '') + (address.state || '') + (address.pincode ? ' - ' + address.pincode : ''));
				if (address.country_name) {
					content += ', ' + escapeHtml(address.country_name);
				}
				content += '</small>';
				content += '</div>';
				content += '</div>';
				if (address.landmark) {
					content += '<div class="d-flex align-items-center mb-2">';
					content += '<i class="isax isax-location me-2 text-muted"></i>';
					content += '<div class="flex-fill">';
					content += '<strong>Landmark:</strong> ';
					content += '<small class="text-muted">' + escapeHtml(address.landmark) + '</small>';
					content += '</div>';
					content += '</div>';
				}
				content += '</div>';
				content += '</div>';
			});
			content += '</div>';
		} else {
			content += '<p class="text-muted text-center py-3"><i class="isax isax-location me-2"></i>No addresses found.</p>';
		}
		content += '</div>';
		
		// Orders Section - Combined from both tables
		var totalOrders = (customer.orders ? customer.orders.length : 0) + (customer.erp_orders ? customer.erp_orders.length : 0);
		content += '<div class="card">';
		content += '<div class="card-header">';
		content += '<h6 class="mb-0"><i class="isax isax-shopping-cart me-2"></i>Order History (' + totalOrders + ')</h6>';
		content += '</div>';
		content += '<div class="card-body">';
		
		// Orders from tbl_order_details
		if (customer.orders && customer.orders.length > 0) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3 text-muted">Orders from tbl_order_details</h6>';
			content += '<div class="table-responsive">';
			content += '<table class="table table-bordered table-hover">';
			content += '<thead class="table-light">';
			content += '<tr>';
			content += '<th>Order ID</th>';
			content += '<th>Order Date</th>';
			content += '<th>User Name</th>';
			content += '<th>User Email</th>';
			content += '<th>User Phone</th>';
			content += '<th>Payment Method</th>';
			content += '<th>Payment Status</th>';
			content += '<th>Order Status</th>';
			content += '<th>Subtotal</th>';
			content += '<th>Discount</th>';
			content += '<th>Delivery Charge</th>';
			content += '<th>GST</th>';
			content += '<th>Total Amount</th>';
			content += '<th>Processing Date</th>';
			content += '<th>Delivery Date</th>';
			content += '<th>Invoice No</th>';
			content += '</tr>';
			content += '</thead>';
			content += '<tbody>';
			customer.orders.forEach(function(order) {
				content += '<tr>';
				content += '<td>' + escapeHtml(order.order_unique_id || order.id) + '</td>';
				content += '<td>' + formatDate(order.order_date) + '</td>';
				content += '<td>' + escapeHtml(order.user_name || 'N/A') + '</td>';
				content += '<td>' + escapeHtml(order.user_email || 'N/A') + '</td>';
				content += '<td>' + escapeHtml((order.dial_code || '') + ' ' + (order.user_phone || 'N/A')) + '</td>';
				content += '<td>' + escapeHtml(order.payment_method || 'N/A') + '</td>';
				content += '<td><span class="badge bg-' + getPaymentStatusColor(order.payment_status) + '">' + escapeHtml(order.payment_status || 'N/A') + '</span></td>';
				content += '<td><span class="badge bg-' + getOrderStatusColor(order.order_status) + '">' + getOrderStatusText(order.order_status) + '</span></td>';
				content += '<td>₹' + parseFloat(order.new_payable_amt || order.total_amt || 0).toFixed(2) + '</td>';
				content += '<td>₹' + parseFloat(order.discount_amt || 0).toFixed(2) + '</td>';
				content += '<td>₹' + parseFloat(order.delivery_charge || order.freight_charges_excl || 0).toFixed(2) + '</td>';
				content += '<td>₹' + parseFloat(order.gst_total || 0).toFixed(2) + '</td>';
				content += '<td><strong>₹' + parseFloat(order.payable_amt || 0).toFixed(2) + '</strong></td>';
				content += '<td>' + (order.processing_date ? formatDate(order.processing_date) : 'N/A') + '</td>';
				content += '<td>' + (order.delivery_date ? formatDate(order.delivery_date) : 'N/A') + '</td>';
				content += '<td>' + escapeHtml(order.invoice_no || 'N/A') + '</td>';
				content += '</tr>';
			});
			content += '</tbody>';
			content += '</table>';
			content += '</div>';
			content += '</div>';
		}
		
		// Orders from erp_orders
		if (customer.erp_orders && customer.erp_orders.length > 0) {
			content += '<div class="mb-4">';
			content += '<h6 class="mb-3 text-muted">Orders from erp_orders</h6>';
			content += '<div class="table-responsive">';
			content += '<table class="table table-bordered table-hover">';
			content += '<thead class="table-light">';
			content += '<tr>';
			content += '<th>Order Number</th>';
			content += '<th>Order Date</th>';
			content += '<th>School</th>';
			content += '<th>Customer Name</th>';
			content += '<th>Customer Email</th>';
			content += '<th>Payment Method</th>';
			content += '<th>Payment Status</th>';
			content += '<th>Order Status</th>';
			content += '<th>Subtotal</th>';
			content += '<th>Tax</th>';
			content += '<th>Discount</th>';
			content += '<th>Total Amount</th>';
			content += '<th>Delivery Date</th>';
			content += '<th>Items</th>';
			content += '</tr>';
			content += '</thead>';
			content += '<tbody>';
			customer.erp_orders.forEach(function(order) {
				content += '<tr>';
				content += '<td><strong>' + escapeHtml(order.order_number || 'N/A') + '</strong></td>';
				content += '<td>' + formatDate(order.order_date) + '</td>';
				content += '<td>' + escapeHtml(order.school_name || 'N/A');
				if (order.branch_name) {
					content += '<br><small class="text-muted">' + escapeHtml(order.branch_name) + '</small>';
				}
				content += '</td>';
				content += '<td>' + escapeHtml(order.customer_name || 'N/A') + '</td>';
				content += '<td>' + escapeHtml(order.customer_email || 'N/A') + '</td>';
				content += '<td>' + escapeHtml(order.payment_method || 'N/A') + '</td>';
				content += '<td><span class="badge bg-' + getPaymentStatusColor(order.payment_status) + '">' + escapeHtml(order.payment_status || 'N/A') + '</span></td>';
				content += '<td><span class="badge bg-' + getOrderStatusColor(order.order_status) + '">' + escapeHtml(order.order_status || 'N/A') + '</span></td>';
				content += '<td>₹' + parseFloat(order.subtotal || 0).toFixed(2) + '</td>';
				content += '<td>₹' + parseFloat(order.tax_amount || 0).toFixed(2) + '</td>';
				content += '<td>₹' + parseFloat(order.discount_amount || 0).toFixed(2) + '</td>';
				content += '<td><strong>₹' + parseFloat(order.total_amount || 0).toFixed(2) + '</strong></td>';
				content += '<td>' + (order.delivery_date ? formatDate(order.delivery_date) : 'N/A') + '</td>';
				content += '<td>';
				if (order.items && order.items.length > 0) {
					content += '<small>';
					order.items.forEach(function(item, idx) {
						if (idx > 0) content += '<br>';
						content += escapeHtml(item.product_name || 'N/A') + ' (x' + item.quantity + ')';
					});
					content += '</small>';
				} else {
					content += 'N/A';
				}
				content += '</td>';
				content += '</tr>';
			});
			content += '</tbody>';
			content += '</table>';
			content += '</div>';
			content += '</div>';
		}
		
		if (totalOrders === 0) {
			content += '<p class="text-muted text-center py-4"><i class="isax isax-shopping-cart me-2"></i>No orders found.</p>';
		}
		
		content += '</div>';
		content += '</div>';
		
		document.getElementById('customerDetailsContent').innerHTML = content;
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
		if (!dateString) return 'N/A';
		var date = new Date(dateString);
		return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
	}
	
	function getPaymentStatusColor(status) {
		switch(status) {
			case 'success': return 'success';
			case 'pending': return 'warning';
			case 'failed': return 'danger';
			default: return 'secondary';
		}
	}
	
	function getOrderStatusColor(status) {
		if (typeof status === 'number') {
			switch(status) {
				case -1: return 'danger';
				case 0: return 'secondary';
				case 1: return 'info';
				case 2: return 'success';
				default: return 'secondary';
			}
		}
		switch(status) {
			case 'pending': return 'secondary';
			case 'processing': return 'info';
			case 'delivered': return 'success';
			case 'cancelled': return 'danger';
			default: return 'secondary';
		}
	}
	
	function getOrderStatusText(status) {
		if (typeof status === 'number') {
			switch(status) {
				case -1: return 'Cancelled';
				case 0: return 'Pending';
				case 1: return 'Processing';
				case 2: return 'Delivered';
				default: return 'Unknown';
			}
		}
		return status || 'Unknown';
	}
});
</script>

