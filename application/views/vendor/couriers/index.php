<style>
	.courier-name-link {
		text-decoration: underline; 
		color: var(--vendor-primary) !important;
	}
.courier-name-link:hover { text-decoration: underline; color: var(--bs-primary) !important; }
</style>
<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Couriers</h6>
	</div>
	<div>
		<a href="<?php echo base_url('couriers/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add me-1"></i>Add New Courier
		</a>
	</div>
</div>
<!-- End Header -->

<!-- Couriers List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($couriers)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Couriers: <strong><?php echo count($couriers); ?></strong></p>
			</div>
		<?php endif; ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>SR No.</th>
						<th>Courier Name</th>
						<th>Tracking Link</th>
						<th>Status</th>
						<th>Created</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($couriers)): ?>
						<?php $sr_no = 1; foreach ($couriers as $courier): ?>
							<tr>
								<td><?php echo $sr_no++; ?></td>
								<td>
									<a href="#" class="courier-name-link text-dark fw-bold" data-courier-id="<?php echo (int)$courier['id']; ?>" data-courier-name="<?php echo htmlspecialchars($courier['courier_name']); ?>"><?php echo htmlspecialchars($courier['courier_name']); ?></a>
								</td>
								<td>
									<?php if (!empty($courier['tracking_link'])): ?>
										<a href="<?php echo htmlspecialchars($courier['tracking_link']); ?>" target="_blank" rel="noopener" class="text-primary">
											<?php echo strlen($courier['tracking_link']) > 40 ? substr($courier['tracking_link'], 0, 40) . '...' : htmlspecialchars($courier['tracking_link']); ?>
										</a>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input status-toggle border-primary" type="checkbox"
											data-courier-id="<?php echo $courier['id']; ?>"
											<?php echo ($courier['status'] == 1) ? 'checked' : ''; ?>>
									</div>
								</td>
								<td><?php echo date('d M Y', strtotime($courier['created_at'])); ?></td>
								<td class="text-end">
									<a href="<?php echo base_url('couriers/edit/' . $courier['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
									<a href="<?php echo base_url('couriers/delete/' . $courier['id']); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this courier?');">
										<i class="isax isax-trash"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="6" class="text-center text-muted py-4">
								<i class="isax isax-truck fs-48 mb-2 d-block"></i>
								<p>No couriers found. <a href="<?php echo base_url('couriers/add'); ?>">Add your first courier</a></p>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Courier Orders Modal -->
<div class="modal fade" id="courierOrdersModal" tabindex="-1" aria-labelledby="courierOrdersModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="courierOrdersModalLabel">Orders</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="courierOrdersModalBody">
				<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Courier name click - load orders modal
	document.querySelectorAll('.courier-name-link').forEach(function(link) {
		link.addEventListener('click', function(e) {
			e.preventDefault();
			var courierId = this.getAttribute('data-courier-id');
			var courierName = this.getAttribute('data-courier-name');
			var modal = document.getElementById('courierOrdersModal');
			var body = document.getElementById('courierOrdersModalBody');
			var titleEl = document.getElementById('courierOrdersModalLabel');

			titleEl.textContent = 'Orders - ' + courierName;
			body.innerHTML = '<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>';

			var bsModal = typeof bootstrap !== 'undefined' && bootstrap.Modal ? new bootstrap.Modal(modal) : null;
			if (bsModal) bsModal.show();
			else $(modal).modal('show');

			fetch('<?php echo base_url('couriers/get_courier_orders/'); ?>' + courierId)
				.then(function(r) { return r.text(); })
				.then(function(html) { body.innerHTML = html; })
				.catch(function() { body.innerHTML = '<p class="text-danger">Failed to load orders.</p>'; });
		});
	});

	// Status Toggle
	document.querySelectorAll('.status-toggle').forEach(function(toggle) {
		toggle.addEventListener('change', function() {
			const courierId = this.getAttribute('data-courier-id');

			fetch('<?php echo base_url('couriers/toggle_status/'); ?>' + courierId, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: '<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>'
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					// Optional: show toast/notification
				} else {
					this.checked = !this.checked;
					alert(data.message || 'Failed to update status');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				this.checked = !this.checked;
				alert('An error occurred. Please try again.');
			});
		});
	});
});
</script>
