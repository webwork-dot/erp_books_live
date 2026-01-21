<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>School Details</h6>
	</div>
	<div>
		<a href="<?php echo base_url('schools'); ?>" class="btn btn-outline-secondary">
			<i class="isax isax-arrow-left me-1"></i>Back to Schools
		</a>
		<a href="<?php echo base_url('schools/edit/' . $school['id']); ?>" class="btn btn-primary">
			<i class="isax isax-edit me-1"></i>Edit School
		</a>
	</div>
</div>
<!-- End Header -->

<!-- School Information -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">School Information</h6>
	</div>
	<div class="card-body">
		<div class="row">
			<!-- School Image -->
			<?php if (!empty($school['images'])): ?>
				<div class="col-md-3 mb-3">
					<?php
					$primary_image = null;
					foreach ($school['images'] as $img) {
						if ($img['is_primary'] == 1) {
							$primary_image = $img;
							break;
						}
					}
					if (!$primary_image && !empty($school['images'])) {
						$primary_image = $school['images'][0];
					}
					if ($primary_image):
						$image_path = trim($primary_image['image_path']);
						if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
							$image_url = $image_path;
						} else {
							$image_url = get_vendor_domain_url() . '/uploads/schools/' . $image_path;
						}
					?>
						<img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($school['school_name']); ?>" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>'">
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<!-- School Details -->
			<div class="<?php echo !empty($school['images']) ? 'col-md-9' : 'col-md-12'; ?>">
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label text-muted">School Name</label>
						<p class="mb-0"><strong><?php echo htmlspecialchars($school['school_name']); ?></strong></p>
					</div>
					<?php if ($school['affiliation_no']): ?>
						<div class="col-md-6">
							<label class="form-label text-muted">Affiliation Number</label>
							<p class="mb-0"><?php echo htmlspecialchars($school['affiliation_no']); ?></p>
						</div>
					<?php endif; ?>
					
					<?php if (!empty($school['boards'])): ?>
						<div class="col-md-6">
							<label class="form-label text-muted">School Boards</label>
							<p class="mb-0">
								<?php 
								$board_names = array();
								foreach ($school['boards'] as $board) {
									$board_names[] = htmlspecialchars($board['board_name']);
								}
								echo implode(', ', $board_names);
								?>
							</p>
						</div>
					<?php endif; ?>
					
					<?php if ($school['total_strength']): ?>
						<div class="col-md-6">
							<label class="form-label text-muted">Total Strength</label>
							<p class="mb-0"><?php echo number_format($school['total_strength']); ?> students</p>
						</div>
					<?php endif; ?>
					
					<div class="col-md-6">
						<label class="form-label text-muted">Status</label>
						<p class="mb-0">
							<span class="badge bg-<?php echo $school['status'] == 'active' ? 'success' : ($school['status'] == 'suspended' ? 'warning' : 'danger'); ?>">
								<?php echo ucfirst($school['status']); ?>
							</span>
						</p>
					</div>
					
					<div class="col-md-6">
						<label class="form-label text-muted">Payment Block</label>
						<p class="mb-0">
							<span class="badge bg-<?php echo (isset($school['is_block_payment']) && $school['is_block_payment'] == 1) ? 'danger' : 'success'; ?>">
								<?php echo (isset($school['is_block_payment']) && $school['is_block_payment'] == 1) ? 'Blocked' : 'Active'; ?>
							</span>
						</p>
					</div>
					
					<div class="col-md-6">
						<label class="form-label text-muted">National Delivery Block</label>
						<p class="mb-0">
							<span class="badge bg-<?php echo (isset($school['is_national_block']) && $school['is_national_block'] == 1) ? 'danger' : 'success'; ?>">
								<?php echo (isset($school['is_national_block']) && $school['is_national_block'] == 1) ? 'Blocked' : 'Active'; ?>
							</span>
						</p>
					</div>
					
					<?php if ($school['school_description']): ?>
						<div class="col-md-12">
							<label class="form-label text-muted">Description</label>
							<p class="mb-0"><?php echo nl2br(htmlspecialchars($school['school_description'])); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Address Information -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">Address Information</h6>
	</div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-12">
				<label class="form-label text-muted">Address</label>
				<p class="mb-0"><?php echo htmlspecialchars($school['address']); ?></p>
			</div>
			<div class="col-md-4">
				<label class="form-label text-muted">City</label>
				<p class="mb-0"><?php echo htmlspecialchars($school['city_name']); ?></p>
			</div>
			<div class="col-md-4">
				<label class="form-label text-muted">State</label>
				<p class="mb-0"><?php echo htmlspecialchars($school['state_name']); ?></p>
			</div>
			<div class="col-md-4">
				<label class="form-label text-muted">Pincode</label>
				<p class="mb-0"><?php echo htmlspecialchars($school['pincode']); ?></p>
			</div>
		</div>
	</div>
</div>

<!-- Admin Information -->
<div class="card mb-3">
	<div class="card-header">
		<h6 class="mb-0">Admin Information</h6>
	</div>
	<div class="card-body">
		<div class="row g-3">
			<div class="col-md-4">
				<label class="form-label text-muted">Admin Name</label>
				<p class="mb-0"><?php echo htmlspecialchars($school['admin_name']); ?></p>
			</div>
			<div class="col-md-4">
				<label class="form-label text-muted">Admin Phone</label>
				<p class="mb-0"><?php echo htmlspecialchars($school['admin_phone']); ?></p>
			</div>
			<div class="col-md-4">
				<label class="form-label text-muted">Admin Email</label>
				<p class="mb-0"><?php echo htmlspecialchars($school['admin_email']); ?></p>
			</div>
		</div>
	</div>
</div>

<!-- School Branches -->
<div class="card">
	<div class="card-header d-flex justify-content-between align-items-center">
		<h6 class="mb-0">School Branches (<?php echo count($branches); ?>)</h6>
		<a href="<?php echo base_url('branches?school_id=' . $school['id']); ?>" class="btn btn-sm btn-primary">
			<i class="isax isax-add me-1"></i>Add Branch
		</a>
	</div>
	<div class="card-body">
		<?php if (!empty($branches)): ?>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>SR No.</th>
							<th>Branch Name</th>
							<th>Address</th>
							<th>City</th>
							<th>State</th>
							<th>Pincode</th>
							<th>Status</th>
							<th>Created</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $sr_no = 1; foreach ($branches as $branch): ?>
							<tr>
								<td><?php echo $sr_no++; ?></td>
								<td><strong><?php echo htmlspecialchars($branch['branch_name']); ?></strong></td>
								<td><?php echo htmlspecialchars($branch['address']); ?></td>
								<td><?php echo htmlspecialchars($branch['city_name']); ?></td>
								<td><?php echo htmlspecialchars($branch['state_name']); ?></td>
								<td><?php echo htmlspecialchars($branch['pincode']); ?></td>
								<td>
									<span class="badge bg-<?php echo $branch['status'] == 'active' ? 'success' : 'danger'; ?>">
										<?php echo ucfirst($branch['status']); ?>
									</span>
								</td>
								<td><?php echo date('d M Y', strtotime($branch['created_at'])); ?></td>
								<td class="text-end">
									<a href="<?php echo base_url('branches/edit/' . $branch['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php else: ?>
			<div class="text-center text-muted py-4">
				<i class="isax isax-building fs-48 mb-2"></i>
				<p>No branches found for this school.</p>
				<a href="<?php echo base_url('branches?school_id=' . $school['id']); ?>" class="btn btn-primary">
					<i class="isax isax-add me-1"></i>Add First Branch
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>

