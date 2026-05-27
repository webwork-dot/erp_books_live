<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage School Branches</h6>
	</div>
	<div>
		<a href="<?php echo base_url('branches/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add me-1"></i>Add New Branch
		</a>
	</div>
</div>
<!-- End Header -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<?php echo form_open(base_url('branches'), array('method' => 'get')); ?>
		<div class="row g-3">
			<div class="col-md-3">
				<label class="form-label">Search</label>
				<input type="text" name="search" class="form-control" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="Branch name, address...">
			</div>
			<div class="col-md-3">
				<label class="form-label">School</label>
				<select name="school_id" class="form-select">
					<option value="">All Schools</option>
					<?php if (!empty($schools)): ?>
						<?php foreach ($schools as $school): ?>
							<option value="<?php echo $school['id']; ?>" <?php echo (isset($filters['school_id']) && $filters['school_id'] == $school['id']) ? 'selected' : ''; ?>>
								<?php echo htmlspecialchars($school['school_name']); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="col-md-2">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="">All Status</option>
					<option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
					<option value="inactive" <?php echo (isset($filters['status']) && $filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
				</select>
			</div>
			<div class="col-md-4 d-flex align-items-end">
				<button type="submit" class="btn btn-primary me-2">
					<i class="isax isax-search-normal me-1"></i>Filter
				</button>
				<a href="<?php echo base_url('branches'); ?>" class="btn btn-outline-secondary">
					<i class="isax isax-refresh me-1"></i>Reset
				</a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<!-- Branches List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($branches)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Branches: <strong><?php echo $total_branches; ?></strong></p>
			</div>
		<?php endif; ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>SR No.</th>
						<th>School Name</th>
						<th>Branch Name</th>
						<th>Address</th>
						<th>Location</th>
						<th>Payment Required?</th>
						<th>Deliver at School?</th>
						<th>Status</th>
						<th>Created</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($branches)): ?>
						<?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($branches as $branch): ?>
							<tr>
								<td><?php echo $sr_no++; ?></td>
								<td>
									<strong><?php echo htmlspecialchars($branch['school_name']); ?></strong>
								</td>
								<td>
									<strong><?php echo htmlspecialchars($branch['branch_name']); ?></strong>
								</td>
								<td>
									<?php echo htmlspecialchars($branch['address']); ?>
								</td>
								<td>
									<?php echo htmlspecialchars($branch['city_name']); ?>, <?php echo htmlspecialchars($branch['state_name']); ?>
									<br><small class="text-muted"><?php echo htmlspecialchars($branch['pincode']); ?></small>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input payment-required-toggle border-primary" type="checkbox"
											data-branch-id="<?php echo $branch['id']; ?>"
											<?php echo (isset($branch['is_payment_required']) && $branch['is_payment_required'] == 1) ? 'checked' : ''; ?>>
									</div>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input deliver-at-school-toggle border-primary" type="checkbox"
											data-branch-id="<?php echo $branch['id']; ?>"
											<?php echo (!isset($branch['deliver_at_school']) || $branch['deliver_at_school'] == 1) ? 'checked' : ''; ?>>
									</div>
								</td>
								<td>
									<span class="badge badge-<?php echo $branch['status'] == 'active' ? 'success' : 'danger'; ?>">
										<?php echo ucfirst($branch['status']); ?>
									</span>
								</td>
								<td><?php echo date('d M Y', strtotime($branch['created_at'])); ?></td>
								<td class="text-end">
									<button class="btn btn-sm btn-outline-warning qr-branch-btn" data-branch-id="<?php echo $branch['id']; ?>" data-bs-toggle="tooltip" title="Download QR Code">
										<i class="isax isax-scan-barcode"></i>
									</button>
									<a href="<?php echo base_url('branches/edit/' . $branch['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
									<a href="<?php echo base_url('branches/delete/' . $branch['id']); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this branch?');">
										<i class="isax isax-trash"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="9" class="text-center text-muted py-4">
								<i class="isax isax-building fs-48 mb-2"></i>
								<p>No branches found. <a href="<?php echo base_url('branches/add'); ?>">Add your first branch</a></p>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<?php if (!empty($branches)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('branches?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
										<a class="page-link" href="<?php echo base_url('branches?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
									</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('branches?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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
	// Payment Required Toggle for Branches
	document.querySelectorAll('.payment-required-toggle').forEach(function(toggle) {
		toggle.addEventListener('change', function() {
			const branchId = this.getAttribute('data-branch-id');
			const isRequired = this.checked ? 1 : 0;

			fetch('<?php echo base_url('branches/toggle_payment_required'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'branch_id=' + branchId + '&status=' + isRequired
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					console.log(data.message);
				} else {
					this.checked = !this.checked;
					alert(data.message || 'Failed to update payment required status');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				this.checked = !this.checked;
				alert('An error occurred. Please try again.');
			});
		});
	});

	// Deliver at School Toggle for Branches
	document.querySelectorAll('.deliver-at-school-toggle').forEach(function(toggle) {
		toggle.addEventListener('change', function() {
			const branchId = this.getAttribute('data-branch-id');
			const isEnabled = this.checked ? 1 : 0;

			fetch('<?php echo base_url('branches/toggle_deliver_at_school'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'branch_id=' + branchId + '&status=' + isEnabled
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					console.log(data.message);
				} else {
					this.checked = !this.checked;
					alert(data.message || 'Failed to update deliver at school status');
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

<!-- Branch QR Code Modal -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header py-2">
				<h6 class="modal-title mb-0" id="qrCodeModalLabel">Download QR Codes</h6>
				<button type="button" class="btn-close btn-close-sm" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body py-3" id="qrCodeContent" style="max-height: 70vh; overflow-y: auto;">
				<!-- Dynamic content loaded via JS -->
			</div>
			<div class="modal-footer py-2">
				<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<style>
.qr-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
	gap: 1.5rem;
	justify-items: center;
	margin-top: 1rem;
}
.qr-card {
	background: #fff;
	border: 1px solid #e2e8f0;
	border-radius: 12px;
	padding: 1rem;
	text-align: center;
	box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.02);
	transition: all 0.2s ease-in-out;
	width: 100%;
	max-width: 240px;
}
.qr-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
	border-color: #cbd5e1;
}
.qr-placeholder {
	width: 100%;
	height: 140px;
	margin-bottom: 0.75rem;
	border-radius: 8px;
	border: 1px solid #e2e8f0;
	background: #f8fafc;
	display: flex;
	align-items: center;
	justify-content: center;
}
.qr-placeholder i {
	font-size: 2.8rem;
	color: #64748b;
}
.qr-card-title {
	font-size: 0.9rem;
	font-weight: 600;
	color: #1e293b;
	margin-bottom: 0.25rem;
	line-height: 1.25;
}
.qr-card-subtitle {
	font-size: 0.75rem;
	color: #64748b;
	margin-bottom: 0.75rem;
}
.qr-card .btn-download {
	width: 100%;
	font-weight: 500;
	font-size: 0.8rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// QR Code Modal loading
	const qrCodeModal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
	
	document.querySelectorAll('.qr-branch-btn').forEach(function(btn) {
		btn.addEventListener('click', function() {
			const branchId = this.getAttribute('data-branch-id');
			loadBranchQrCodes(branchId);
		});
	});

	function loadBranchQrCodes(branchId) {
		fetch('<?php echo base_url('branches/get_branch_details/'); ?>' + branchId)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					const branch = data.branch;
					const boards = branch.boards || [];
					
					if (boards.length <= 1) {
						// Single board: download directly!
						const boardId = boards.length === 1 ? boards[0].id : '';
						const downloadUrl = '<?php echo base_url('schools/download_branch_qr/'); ?>' + branch.id + (boardId ? '/' + boardId : '');
						window.location.href = downloadUrl;
					} else {
						// Multi-board: show modal
						const modalContent = document.getElementById('qrCodeContent');
						modalContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Loading options...</p></div>';
						qrCodeModal.show();
						displayBranchQrCodes(branch);
					}
				} else {
					alert(data.message || 'Failed to load branch details');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('An error occurred while loading. Please try again.');
			});
	}

	function displayBranchQrCodes(branch) {
		const modalContent = document.getElementById('qrCodeContent');
		const modalTitle = document.getElementById('qrCodeModalLabel');
		
		modalTitle.textContent = 'Download QR Codes - ' + (branch.branch_name || 'Branch');
		
		let html = '';
		html += '<h6 class="text-primary border-bottom pb-2 mb-3"><i class="isax isax-building-3 me-2"></i>Branch QR Codes</h6>';
		html += '<div class="qr-grid">';
		
		if (branch.boards && branch.boards.length > 0) {
			branch.boards.forEach(function(board) {
				const downloadUrl = '<?php echo base_url('schools/download_branch_qr/'); ?>' + branch.id + '/' + board.id;
				html += '<div class="qr-card">';
				html += '  <div class="qr-placeholder"><i class="isax isax-scan-barcode"></i></div>';
				html += '  <div class="qr-card-title text-truncate">' + branch.branch_name + '</div>';
				html += '  <div class="qr-card-subtitle">' + board.board_name + '</div>';
				html += '  <a href="' + downloadUrl + '" class="btn btn-sm btn-primary btn-download"><i class="isax isax-import me-1"></i>Download QR</a>';
				html += '</div>';
			});
		} else {
			const downloadUrl = '<?php echo base_url('schools/download_branch_qr/'); ?>' + branch.id;
			html += '<div class="qr-card">';
			html += '  <div class="qr-placeholder"><i class="isax isax-scan-barcode"></i></div>';
			html += '  <div class="qr-card-title text-truncate">' + branch.branch_name + '</div>';
			html += '  <div class="qr-card-subtitle">Storefront</div>';
			html += '  <a href="' + downloadUrl + '" class="btn btn-sm btn-primary btn-download"><i class="isax isax-import me-1"></i>Download QR</a>';
			html += '</div>';
		}
		
		html += '</div>';
		modalContent.innerHTML = html;
	}
});
</script>

