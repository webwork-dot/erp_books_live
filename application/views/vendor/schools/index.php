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
									<button type="button" class="btn btn-sm btn-outline-info view-school-btn" data-school-id="<?php echo $school['id']; ?>" data-bs-toggle="tooltip" title="View Details">
										<i class="isax isax-eye"></i>
									</button>
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

<!-- School Details Modal -->
<div class="modal fade" id="schoolDetailsModal" tabindex="-1" aria-labelledby="schoolDetailsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title mb-0" id="schoolDetailsModalLabel">School Details</h6>
				<button type="button" class="btn-close btn-close-sm" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-2" id="schoolDetailsContent" style="max-height: 70vh; overflow-y: auto;">
				<div class="text-center py-4">
					<div class="spinner-border spinner-border-sm text-primary" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
					<p class="mt-2 text-muted small">Loading school details...</p>
				</div>
			</div>
			<div class="modal-footer py-2">
				<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<style>
.badge-sm {
	font-size: 0.7rem;
	padding: 0.25em 0.5em;
}
</style>

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

	// School Details Modal
	const schoolDetailsModal = new bootstrap.Modal(document.getElementById('schoolDetailsModal'));
	
	document.querySelectorAll('.view-school-btn').forEach(function(btn) {
		btn.addEventListener('click', function() {
			const schoolId = this.getAttribute('data-school-id');
			loadSchoolDetails(schoolId);
		});
	});

	function loadSchoolDetails(schoolId) {
		const modalContent = document.getElementById('schoolDetailsContent');
		modalContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Loading school details...</p></div>';
		
		schoolDetailsModal.show();
		
		fetch('<?php echo base_url('schools/get_school_details/'); ?>' + schoolId)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					displaySchoolDetails(data.school, data.branches);
				} else {
					modalContent.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Failed to load school details') + '</div>';
				}
			})
			.catch(error => {
				console.error('Error:', error);
				modalContent.innerHTML = '<div class="alert alert-danger">An error occurred while loading school details. Please try again.</div>';
			});
	}

	function displaySchoolDetails(school, branches) {
		const modalContent = document.getElementById('schoolDetailsContent');
		const modalTitle = document.getElementById('schoolDetailsModalLabel');
		
		modalTitle.textContent = school.school_name || 'School Details';
		
		let html = '<div class="row g-2">';
		
		// School Image (compact)
		if (school.images && school.images.length > 0) {
			const primaryImage = school.images.find(img => img.is_primary == 1) || school.images[0];
			const imageUrl = primaryImage.image_path.startsWith('http') ? primaryImage.image_path : '<?php echo get_vendor_domain_url(); ?>/' + primaryImage.image_path;
			html += '<div class="col-md-12 mb-2 text-center">';
			html += '<img src="' + imageUrl + '" alt="' + (school.school_name || 'School') + '" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;" onerror="this.onerror=null; this.src=\'<?php echo base_url('assets/template/img/placeholder-image.png'); ?>\'">';
			html += '</div>';
		}
		
		// School Basic Information (compact)
		html += '<div class="col-md-6 mb-2">';
		html += '<h6 class="text-primary mb-2 small"><i class="isax isax-building me-1"></i>Basic Info</h6>';
		html += '<table class="table table-sm table-borderless mb-0" style="font-size: 0.875rem;">';
		html += '<tr><td class="fw-bold p-1" style="width: 35%;">Name:</td><td class="p-1">' + (school.school_name || '-') + '</td></tr>';
		html += '<tr><td class="fw-bold p-1">Affiliation:</td><td class="p-1">' + (school.affiliation_no || '-') + '</td></tr>';
		html += '<tr><td class="fw-bold p-1">Strength:</td><td class="p-1">' + (school.total_strength || '-') + '</td></tr>';
		html += '<tr><td class="fw-bold p-1">Status:</td><td class="p-1"><span class="badge bg-' + (school.status == 'active' ? 'success' : 'secondary') + ' badge-sm">' + (school.status ? school.status.charAt(0).toUpperCase() + school.status.slice(1) : '-') + '</span></td></tr>';
		html += '<tr><td class="fw-bold p-1">Payment:</td><td class="p-1"><span class="badge bg-' + (school.is_block_payment == 1 ? 'danger' : 'success') + ' badge-sm">' + (school.is_block_payment == 1 ? 'Blocked' : 'Allowed') + '</span></td></tr>';
		html += '<tr><td class="fw-bold p-1">National:</td><td class="p-1"><span class="badge bg-' + (school.is_national_block == 1 ? 'danger' : 'success') + ' badge-sm">' + (school.is_national_block == 1 ? 'Blocked' : 'Allowed') + '</span></td></tr>';
		html += '</table>';
		html += '</div>';
		
		// School Address & Contact (compact)
		html += '<div class="col-md-6 mb-2">';
		html += '<h6 class="text-primary mb-2 small"><i class="isax isax-location me-1"></i>Contact</h6>';
		html += '<table class="table table-sm table-borderless mb-0" style="font-size: 0.875rem;">';
		html += '<tr><td class="fw-bold p-1" style="width: 35%;">Address:</td><td class="p-1">' + (school.address || '-') + '</td></tr>';
		html += '<tr><td class="fw-bold p-1">Location:</td><td class="p-1">' + (school.city_name || '-') + ', ' + (school.state_name || '-') + ' ' + (school.pincode || '') + '</td></tr>';
		html += '<tr><td class="fw-bold p-1">Admin:</td><td class="p-1">' + (school.admin_name || '-') + '</td></tr>';
		html += '<tr><td class="fw-bold p-1">Phone:</td><td class="p-1">' + (school.admin_phone || '-') + '</td></tr>';
		html += '<tr><td class="fw-bold p-1">Email:</td><td class="p-1">' + (school.admin_email || '-') + '</td></tr>';
		html += '</table>';
		html += '</div>';
		
		// School Boards (compact)
		if (school.boards && school.boards.length > 0) {
			html += '<div class="col-md-12 mb-2">';
			html += '<h6 class="text-primary mb-1 small"><i class="isax isax-book me-1"></i>Boards</h6>';
			html += '<div class="d-flex flex-wrap gap-1">';
			school.boards.forEach(function(board) {
				html += '<span class="badge bg-info badge-sm">' + (board.board_name || '') + '</span>';
			});
			html += '</div>';
			html += '</div>';
		}
		
		// School Description (compact, only if exists)
		if (school.school_description) {
			html += '<div class="col-md-12 mb-2">';
			html += '<h6 class="text-primary mb-1 small"><i class="isax isax-document-text me-1"></i>Description</h6>';
			html += '<p class="text-muted small mb-0">' + school.school_description + '</p>';
			html += '</div>';
		}
		
		// Branches Section (compact with boards)
		html += '<div class="col-md-12 mb-2">';
		html += '<h6 class="text-primary mb-2 small"><i class="isax isax-building-3 me-1"></i>Branches (' + (branches ? branches.length : 0) + ')</h6>';
		
		if (branches && branches.length > 0) {
			html += '<div class="table-responsive">';
			html += '<table class="table table-sm table-hover mb-0" style="font-size: 0.8rem;">';
			html += '<thead class="table-light">';
			html += '<tr>';
			html += '<th style="padding: 0.4rem;">Branch</th>';
			html += '<th style="padding: 0.4rem;">Location</th>';
			html += '<th style="padding: 0.4rem;">Boards</th>';
			html += '<th style="padding: 0.4rem;">Status</th>';
			html += '</tr>';
			html += '</thead>';
			html += '<tbody>';
			branches.forEach(function(branch) {
				html += '<tr>';
				html += '<td style="padding: 0.4rem;"><strong>' + (branch.branch_name || '-') + '</strong>';
				if (branch.address) {
					html += '<br><small class="text-muted">' + branch.address + '</small>';
				}
				html += '</td>';
				html += '<td style="padding: 0.4rem;">' + (branch.city_name || '-') + ', ' + (branch.state_name || '-');
				if (branch.pincode) {
					html += '<br><small class="text-muted">' + branch.pincode + '</small>';
				}
				html += '</td>';
				html += '<td style="padding: 0.4rem;">';
				if (branch.boards && branch.boards.length > 0) {
					branch.boards.forEach(function(board) {
						html += '<span class="badge bg-info badge-sm me-1 mb-1" style="font-size: 0.7rem;">' + (board.board_name || '') + '</span>';
					});
				} else {
					html += '<span class="text-muted small">-</span>';
				}
				html += '</td>';
				html += '<td style="padding: 0.4rem;"><span class="badge bg-' + (branch.status == 'active' ? 'success' : 'secondary') + ' badge-sm">' + (branch.status ? branch.status.charAt(0).toUpperCase() + branch.status.slice(1) : '-') + '</span></td>';
				html += '</tr>';
			});
			html += '</tbody>';
			html += '</table>';
			html += '</div>';
		} else {
			html += '<div class="alert alert-info mb-0 py-2" style="font-size: 0.875rem;">No branches found for this school.</div>';
		}
		html += '</div>';
		
		html += '</div>';
		
		modalContent.innerHTML = html;
	}
});
</script>

