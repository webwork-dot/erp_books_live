<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage School Branches</h6>
	</div>
	<div>
		<a href="<?php echo base_url($vendor_domain . '/branches/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add me-1"></i>Add New Branch
		</a>
	</div>
</div>
<!-- End Header -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<?php echo form_open($vendor_domain . '/branches', array('method' => 'get')); ?>
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
				<a href="<?php echo base_url($vendor_domain . '/branches'); ?>" class="btn btn-outline-secondary">
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
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>SR No.</th>
						<th>School Name</th>
						<th>Branch Name</th>
						<th>Address</th>
						<th>Location</th>
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
									<span class="badge badge-<?php echo $branch['status'] == 'active' ? 'success' : 'danger'; ?>">
										<?php echo ucfirst($branch['status']); ?>
									</span>
								</td>
								<td><?php echo date('d M Y', strtotime($branch['created_at'])); ?></td>
								<td class="text-end">
									<a href="<?php echo base_url($vendor_domain . '/branches/edit/' . $branch['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
									<a href="<?php echo base_url($vendor_domain . '/branches/delete/' . $branch['id']); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this branch?');">
										<i class="isax isax-trash"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="8" class="text-center text-muted py-4">
								<i class="isax isax-building fs-48 mb-2"></i>
								<p>No branches found. <a href="<?php echo base_url($vendor_domain . '/branches/add'); ?>">Add your first branch</a></p>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<?php if (!empty($branches)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<p class="text-muted mb-0">Total Branches: <strong><?php echo $total_branches; ?></strong></p>
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url($vendor_domain . '/branches?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
										<a class="page-link" href="<?php echo base_url($vendor_domain . '/branches?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
									</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url($vendor_domain . '/branches?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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

