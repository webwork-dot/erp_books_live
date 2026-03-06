<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>User Concerns</h6>
		<p class="text-muted mb-0 small">Customer concerns from frontend – handle with customer information</p>
	</div>
</div>
<!-- End Header -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<?php echo form_open(base_url('concerns'), array('method' => 'get')); ?>
		<div class="row g-3">
			<div class="col-md-3">
				<label class="form-label">Search</label>
				<input type="text" name="search" class="form-control" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="Message, order ID, customer...">
			</div>
			<div class="col-md-2">
				<label class="form-label">Type</label>
				<select name="concern_type" class="form-select">
					<option value="">All Types</option>
					<option value="order_status" <?php echo (isset($filters['concern_type']) && $filters['concern_type'] == 'order_status') ? 'selected' : ''; ?>>Order Status</option>
					<option value="delivery" <?php echo (isset($filters['concern_type']) && $filters['concern_type'] == 'delivery') ? 'selected' : ''; ?>>Delivery</option>
					<option value="product" <?php echo (isset($filters['concern_type']) && $filters['concern_type'] == 'product') ? 'selected' : ''; ?>>Product</option>
					<option value="payment" <?php echo (isset($filters['concern_type']) && $filters['concern_type'] == 'payment') ? 'selected' : ''; ?>>Payment</option>
					<option value="other" <?php echo (isset($filters['concern_type']) && $filters['concern_type'] == 'other') ? 'selected' : ''; ?>>Other</option>
				</select>
			</div>
			<div class="col-md-2">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="">All Status</option>
					<option value="pending" <?php echo (isset($filters['status']) && $filters['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
					<option value="in_progress" <?php echo (isset($filters['status']) && $filters['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
					<option value="resolved" <?php echo (isset($filters['status']) && $filters['status'] == 'resolved') ? 'selected' : ''; ?>>Resolved</option>
				</select>
			</div>
			<div class="col-md-3 d-flex align-items-end">
				<button type="submit" class="btn btn-primary me-2">
					<i class="isax isax-search-normal me-1"></i>Filter
				</button>
				<a href="<?php echo base_url('concerns'); ?>" class="btn btn-outline-secondary">
					<i class="isax isax-refresh me-1"></i>Reset
				</a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<!-- Concerns List -->
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Type</th>
						<th>Message</th>
						<th>Customer</th>
						<th>Contact</th>
						<th>Order</th>
						<th>Status</th>
						<th>Date</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($concerns)): ?>
						<?php $sr = (($current_page - 1) * $per_page) + 1; foreach ($concerns as $c): ?>
							<?php
								$type = isset($c['concern_type']) ? ucfirst(str_replace('_', ' ', $c['concern_type'])) : 'Other';
								$msg = isset($c['message']) ? $c['message'] : '';
								$customer_name = !empty($c['firm_name']) ? $c['firm_name'] : (isset($c['username']) ? $c['username'] : 'N/A');
								$phone = !empty($c['phone_number']) ? (isset($c['dial_code']) ? $c['dial_code'] . ' ' : '') . $c['phone_number'] : '-';
								$contact_pref = isset($c['contact_preference']) ? ucfirst($c['contact_preference']) : '-';
								$order_id = !empty($c['order_id']) && $c['order_id'] > 0 ? $c['order_id'] : null;
								$order_unique_id = !empty($c['order_unique_id']) ? $c['order_unique_id'] : null;
								$status_val = isset($c['status']) ? $c['status'] : 'pending';
							?>
							<tr>
								<td><?php echo $sr++; ?></td>
								<td><span class="badge bg-light text-dark"><?php echo htmlspecialchars($type); ?></span></td>
								<td>
									<?php if (strlen($msg) > 80): ?>
										<?php echo htmlspecialchars(substr($msg, 0, 80)) . '...'; ?>
									<?php else: ?>
										<?php echo htmlspecialchars($msg); ?>
									<?php endif; ?>
								</td>
								<td>
									<?php echo htmlspecialchars($customer_name); ?>
									<?php if (!empty($c['email'])): ?>
										<br><small class="text-muted"><?php echo htmlspecialchars($c['email']); ?></small>
									<?php endif; ?>
								</td>
								<td>
									<?php echo htmlspecialchars($phone); ?>
									<br><small class="text-muted"><?php echo $contact_pref; ?></small>
								</td>
								<td>
									<?php if ($order_unique_id): ?>
										<a href="<?php echo base_url('orders/view/' . $order_unique_id); ?>" target="_blank">#<?php echo htmlspecialchars($order_unique_id); ?></a>
									<?php elseif ($order_id): ?>
										<span class="text-muted">#<?php echo htmlspecialchars($order_id); ?></span>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php
									$badge = 'secondary';
									if ($status_val == 'pending') $badge = 'warning';
									elseif ($status_val == 'in_progress') $badge = 'info';
									elseif ($status_val == 'resolved') $badge = 'success';
									?>
									<span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst(str_replace('_', ' ', $status_val)); ?></span>
								</td>
								<td><?php echo isset($c['created_at']) ? date('d M Y, H:i', strtotime($c['created_at'])) : '-'; ?></td>
								<td class="text-end">
									<button type="button" class="btn btn-sm btn-outline-primary view-concern" data-id="<?php echo (int)$c['id']; ?>" data-bs-toggle="tooltip" title="View & Handle">
										<i class="isax isax-eye"></i>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="9" class="text-center text-muted py-4">
								<i class="isax isax-message-question fs-48 mb-2"></i>
								<p class="mb-0">No concerns found.</p>
								<small>Concerns submitted from the frontend will appear here.</small>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<?php if ($total_pages > 1): ?>
			<nav aria-label="Page navigation" class="mt-4">
				<ul class="pagination justify-content-center">
					<?php if ($current_page > 1): ?>
						<li class="page-item">
							<a class="page-link" href="<?php echo base_url('concerns?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">
								<i class="isax isax-arrow-left-2"></i> Previous
							</a>
						</li>
					<?php else: ?>
						<li class="page-item disabled"><span class="page-link"><i class="isax isax-arrow-left-2"></i> Previous</span></li>
					<?php endif; ?>
					<?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
						<li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
							<a class="page-link" href="<?php echo base_url('concerns?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
						</li>
					<?php endfor; ?>
					<?php if ($current_page < $total_pages): ?>
						<li class="page-item">
							<a class="page-link" href="<?php echo base_url('concerns?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">
								Next <i class="isax isax-arrow-right-2"></i>
							</a>
						</li>
					<?php else: ?>
						<li class="page-item disabled"><span class="page-link">Next <i class="isax isax-arrow-right-2"></i></span></li>
					<?php endif; ?>
				</ul>
			</nav>
			<div class="text-center text-muted small">
				Showing <?php echo (($current_page - 1) * $per_page) + 1; ?> to <?php echo min($current_page * $per_page, $total); ?> of <?php echo $total; ?> concerns
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- View Concern Modal -->
<div class="modal fade" id="concernModal" tabindex="-1" aria-labelledby="concernModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="concernModalLabel">Concern Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="concernModalBody">
				<div class="text-center py-4"><i class="isax isax-loading-1 isax-spin"></i> Loading...</div>
			</div>
			<div class="modal-footer">
				<div class="d-flex flex-wrap gap-2 align-items-center w-100">
					<select class="form-select form-select-sm" id="concernStatusSelect" style="width: auto;">
						<option value="pending">Pending</option>
						<option value="in_progress">In Progress</option>
						<option value="resolved">Resolved</option>
					</select>
					<button type="button" class="btn btn-primary btn-sm" id="btnUpdateStatus">Update Status</button>
					<button type="button" class="btn btn-outline-secondary btn-sm" id="btnSaveResponse">Save Response</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var currentConcernId = null;
	var concernModal = document.getElementById('concernModal');
	var concernModalBody = document.getElementById('concernModalBody');
	var concernStatusSelect = document.getElementById('concernStatusSelect');
	var btnUpdateStatus = document.getElementById('btnUpdateStatus');
	var btnSaveResponse = document.getElementById('btnSaveResponse');

	document.querySelectorAll('.view-concern').forEach(function(btn) {
		btn.addEventListener('click', function() {
			var id = this.getAttribute('data-id');
			currentConcernId = id;
			concernModalBody.innerHTML = '<div class="text-center py-4"><i class="isax isax-loading-1"></i> Loading...</div>';
			var modal = new bootstrap.Modal(concernModal);
			modal.show();
			fetch('<?php echo base_url("concerns/get_concern/"); ?>' + id)
				.then(function(r) { return r.json(); })
				.then(function(res) {
					if (res.success && res.data) {
						var d = res.data;
						var type = (d.concern_type || 'other').replace(/_/g, ' ');
						type = type.charAt(0).toUpperCase() + type.slice(1);
						var msg = d.message || '';
						var name = d.firm_name || d.username || 'N/A';
						var email = d.email || '-';
						var phone = (d.phone_number ? (d.dial_code || '') + ' ' + d.phone_number : '-');
						var contactPref = d.contact_preference ? d.contact_preference.charAt(0).toUpperCase() + d.contact_preference.slice(1) : '-';
						var orderLink = (d.order_unique_id) ? '<a href="<?php echo base_url("orders/view/"); ?>' + escapeHtml(d.order_unique_id) + '" target="_blank">Order #' + escapeHtml(d.order_unique_id) + '</a>' : ((d.order_id && d.order_id > 0) ? 'Order #' + d.order_id : '-');
						var adminResp = d.admin_response || '';
						concernStatusSelect.value = d.status || 'pending';
						if (typeof document.getElementById('adminResponseText') !== 'undefined' && document.getElementById('adminResponseText')) {
							document.getElementById('adminResponseText').value = adminResp;
						}
						concernModalBody.innerHTML =
							'<div class="mb-3"><strong>Type:</strong> ' + escapeHtml(type) + '</div>' +
							'<div class="mb-3"><strong>Message:</strong><p class="mb-0 mt-1">' + escapeHtml(msg) + '</p></div>' +
							'<div class="mb-3"><strong>Contact preference:</strong> ' + escapeHtml(contactPref) + '</div>' +
							(orderLink !== '-' ? '<div class="mb-3"><strong>Order:</strong> ' + orderLink + '</div>' : '') +
							'<hr><h6 class="mb-2">Customer Information</h6>' +
							'<p class="mb-1"><strong>Name:</strong> ' + escapeHtml(name) + '</p>' +
							'<p class="mb-1"><strong>Email:</strong> ' + escapeHtml(email) + '</p>' +
							'<p class="mb-2"><strong>Phone:</strong> ' + escapeHtml(phone) + '</p>' +
							(d.user_id ? '<p class="mb-3"><a href="<?php echo base_url("customers/list"); ?>?search=' + encodeURIComponent(email !== '-' ? email : name) + '" class="btn btn-sm btn-outline-primary" target="_blank">View Customer</a></p>' : '') +
							'<hr><h6 class="mb-2">Your Response</h6>' +
							'<textarea id="adminResponseText" class="form-control" rows="3" placeholder="Add your response to the customer...">' + escapeHtml(adminResp) + '</textarea>';
					} else {
						concernModalBody.innerHTML = '<p class="text-danger">Could not load concern.</p>';
					}
				})
				.catch(function() {
					concernModalBody.innerHTML = '<p class="text-danger">Error loading concern.</p>';
				});
		});
	});

	btnUpdateStatus.addEventListener('click', function() {
		if (!currentConcernId) return;
		btnUpdateStatus.disabled = true;
		var fd = new FormData();
		fd.append('id', currentConcernId);
		fd.append('status', concernStatusSelect.value);
		fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo htmlspecialchars($this->security->get_csrf_hash()); ?>');
		fetch('<?php echo base_url("concerns/update_status"); ?>', {
			method: 'POST',
			body: fd
		})
		.then(function(r) { return r.json(); })
		.then(function(res) {
			btnUpdateStatus.disabled = false;
			if (res.success) {
				location.reload();
			} else {
				alert(res.message || 'Update failed');
			}
		})
		.catch(function() {
			btnUpdateStatus.disabled = false;
			alert('Request failed');
		});
	});

	document.addEventListener('click', function(e) {
		if (e.target && e.target.id === 'btnSaveResponse') {
			e.preventDefault();
			var ta = document.getElementById('adminResponseText');
			if (!currentConcernId || !ta) return;
			btnSaveResponse.disabled = true;
			var fd = new FormData();
			fd.append('id', currentConcernId);
			fd.append('admin_response', ta.value);
			fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo htmlspecialchars($this->security->get_csrf_hash()); ?>');
			fetch('<?php echo base_url("concerns/update_admin_response"); ?>', {
				method: 'POST',
				body: fd
			})
			.then(function(r) { return r.json(); })
			.then(function(res) {
				btnSaveResponse.disabled = false;
				if (res.success) {
					alert('Response saved');
				} else {
					alert(res.message || 'Save failed');
				}
			})
			.catch(function() {
				btnSaveResponse.disabled = false;
				alert('Request failed');
			});
		}
	}, true);

	function escapeHtml(s) {
		if (!s) return '';
		var div = document.createElement('div');
		div.textContent = s;
		return div.innerHTML;
	}
});
</script>
