<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Schools</h6>
	</div>
	<div>
		<a href="<?php echo base_url('schools/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add me-1"></i>Add New School
		</a>
	</div>
</div>
<!-- End Header -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<?php echo form_open(base_url('schools'), array('method' => 'get')); ?>
		<div class="row g-3">
			<div class="col-md-4">
				<label class="form-label">Search</label>
				<input type="text" name="search" class="form-control" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="School name, board, email...">
			</div>
			<div class="col-md-3">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="">All Status</option>
					<option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
					<option value="inactive" <?php echo (isset($filters['status']) && $filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
					<option value="suspended" <?php echo (isset($filters['status']) && $filters['status'] == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
				</select>
			</div>
			<div class="col-md-3 d-flex align-items-end">
				<button type="submit" class="btn btn-primary me-2">
					<i class="isax isax-search-normal me-1"></i>Filter
				</button>
				<a href="<?php echo base_url('schools'); ?>" class="btn btn-outline-secondary">
					<i class="isax isax-refresh me-1"></i>Reset
				</a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<!-- Schools List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($schools)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Schools: <strong><?php echo $total_schools; ?></strong></p>
			</div>
		<?php endif; ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>SR No.</th>
						<th>School Image</th>
						<th>School Name</th>
						<th>Board</th>
						<th>Payment Block?</th>
						<th>National Delivery Block?</th>
						<th>Status</th>
						<th>Created</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($schools)): ?>
						<?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($schools as $school): ?>
							<tr>
								<td><?php echo $sr_no++; ?></td>
								<td>
									<?php if (!empty($school['thumbnail'])): ?>
										<?php
										$stored_path = trim($school['thumbnail']);
										if (strpos($stored_path, 'http://') === 0 || strpos($stored_path, 'https://') === 0) {
											$image_url = $stored_path;
										} else {
											$image_url = get_vendor_domain_url().'/' . $stored_path;
										}
										?>
										<img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($school['school_name']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>'">
									<?php else: ?>
										<div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
											<i class="isax isax-building" style="font-size: 24px; color: #999;"></i>
										</div>
									<?php endif; ?>
								</td>
								<td>
									<strong><?php echo htmlspecialchars($school['school_name']); ?></strong>
									<?php if ($school['affiliation_no']): ?>
										<br><small class="text-muted">Affiliation: <?php echo htmlspecialchars($school['affiliation_no']); ?></small>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!empty($school['school_board_names'])): ?>
										<?php echo htmlspecialchars($school['school_board_names']); ?>
									<?php else: ?>
										<span class="text-muted">No boards assigned</span>
									<?php endif; ?>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input payment-block-toggle border-primary" type="checkbox" 
											data-school-id="<?php echo $school['id']; ?>"
											<?php echo (isset($school['is_block_payment']) && $school['is_block_payment'] == 1) ? 'checked' : ''; ?>>
									</div>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input national-block-toggle border-primary" type="checkbox" 
											data-school-id="<?php echo $school['id']; ?>"
											<?php echo (isset($school['is_national_block']) && $school['is_national_block'] == 1) ? 'checked' : ''; ?>>
									</div>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input status-toggle border-primary" type="checkbox" 
											data-school-id="<?php echo $school['id']; ?>"
											<?php echo ($school['status'] == 'active') ? 'checked' : ''; ?>>
									</div>
								</td>
								<td><?php echo date('d M Y', strtotime($school['created_at'])); ?></td>
								<td class="text-end">
									<a href="<?php echo base_url('schools/edit/' . $school['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
									<a href="<?php echo base_url('schools/delete/' . $school['id']); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this school?');">
										<i class="isax isax-trash"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="9" class="text-center text-muted py-4">
								<i class="isax isax-school fs-48 mb-2"></i>
								<p>No schools found. <a href="<?php echo base_url('schools/add'); ?>">Add your first school</a></p>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<?php if (!empty($schools)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('schools?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
								</li>
							<?php else: ?>
								<li class="page-item disabled">
									<span class="page-link">Previous</span>
								</li>
							<?php endif; ?>
							
							<?php for ($i = 1; $i <= $total_pages; $i++): ?>
								<?php if ($i == $current_page): ?>
									<li class="page-item active">
										<span class="page-link"><?php echo $i; ?></span>
									</li>
								<?php else: ?>
									<li class="page-item">
										<a class="page-link" href="<?php echo base_url('schools?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
									</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('schools?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
								</li>
							<?php else: ?>
								<li class="page-item disabled">
									<span class="page-link">Next</span>
								</li>
							<?php endif; ?>
						</ul>
					</nav>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Payment Block Toggle
	document.querySelectorAll('.payment-block-toggle').forEach(function(toggle) {
		toggle.addEventListener('change', function() {
			const schoolId = this.getAttribute('data-school-id');
			const isBlocked = this.checked ? 1 : 0;
			
			fetch('<?php echo base_url('schools/toggle_payment_block'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'school_id=' + schoolId + '&status=' + isBlocked
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					// Show success message if needed
					console.log(data.message);
				} else {
					// Revert toggle on error
					this.checked = !this.checked;
					alert(data.message || 'Failed to update payment block status');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				this.checked = !this.checked;
				alert('An error occurred. Please try again.');
			});
		});
	});

	// National Delivery Block Toggle
	document.querySelectorAll('.national-block-toggle').forEach(function(toggle) {
		toggle.addEventListener('change', function() {
			const schoolId = this.getAttribute('data-school-id');
			const isBlocked = this.checked ? 1 : 0;
			
			fetch('<?php echo base_url('schools/toggle_national_block'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'school_id=' + schoolId + '&status=' + isBlocked
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					// Show success message if needed
					console.log(data.message);
				} else {
					// Revert toggle on error
					this.checked = !this.checked;
					alert(data.message || 'Failed to update national delivery block status');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				this.checked = !this.checked;
				alert('An error occurred. Please try again.');
			});
		});
	});

	// Status Toggle
	document.querySelectorAll('.status-toggle').forEach(function(toggle) {
		toggle.addEventListener('change', function() {
			const schoolId = this.getAttribute('data-school-id');
			const status = this.checked ? 'active' : 'inactive';
			
			fetch('<?php echo base_url('schools/toggle_status'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'school_id=' + schoolId + '&status=' + status
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					// Show success message if needed
					console.log(data.message);
				} else {
					// Revert toggle on error
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

