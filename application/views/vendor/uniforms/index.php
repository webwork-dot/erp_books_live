<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Uniforms</h6>
	</div>
	<div>
		<a href="<?php echo base_url('products/uniforms/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add"></i> Add New Uniform
		</a>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		
		<form method="get" action="<?php echo base_url('products/uniforms'); ?>">
			<?php if (!empty($uniforms)): ?>
				<div class="mb-3">
					<p class="text-muted mb-0">Total Uniforms: <strong><?php echo $total_uniforms; ?></strong></p>
				</div>
			<?php endif; ?>


			<!-- Search on Top -->
			<div class="row gx-3 mb-3">
				<div class="col-lg-8 col-md-8">
					<div class="mb-3">
						<label class="form-label">Search</label>
						<input type="text" name="search" class="form-control" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="Product Name, ISBN, SKU...">
					</div>
				</div>
				<div class="col-lg-2 col-md-4">
					<div class="mb-3">
						<label class="form-label">Status</label>
						<select name="status" class="select">
							<option value="">All Status</option>
							<option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
							<option value="inactive" <?php echo (isset($filters['status']) && $filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
						</select>
					</div>
				</div>
				<div class="col-lg-2 col-md-4">
					<div class="mb-3">
						<label class="form-label">&nbsp;</label>
						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-primary flex-fill">Filter</button>
							<a href="<?php echo base_url('products/uniforms'); ?>" class="btn btn-outline-secondary">Clear</a>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Accordion for Additional Filters -->
			<div class="accordion" id="filterAccordion">
				<div class="accordion-item">
					<h2 class="accordion-header" id="headingFilters">
						<button class="accordion-button <?php echo (isset($filters['school_id']) || isset($filters['uniform_type_id']) || isset($filters['board_id']) || isset($filters['material_id']) || isset($filters['gender']) || isset($filters['branch_id'])) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="<?php echo (isset($filters['school_id']) || isset($filters['uniform_type_id']) || isset($filters['board_id']) || isset($filters['material_id']) || isset($filters['gender']) || isset($filters['branch_id'])) ? 'true' : 'false'; ?>" aria-controls="collapseFilters">
							<i class="isax isax-filter me-2"></i> Additional Filters
						</button>
					</h2>
					<div id="collapseFilters" class="accordion-collapse collapse <?php echo (isset($filters['school_id']) || isset($filters['uniform_type_id']) || isset($filters['board_id']) || isset($filters['material_id']) || isset($filters['gender']) || isset($filters['branch_id'])) ? 'show' : ''; ?>" aria-labelledby="headingFilters" data-bs-parent="#filterAccordion">
						<div class="accordion-body">
							<div class="row gx-3">
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">School</label>
										<select name="school_id" class="select">
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
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Uniform Type</label>
										<select name="uniform_type_id" class="select">
											<option value="">All Types</option>
											<?php if (!empty($uniform_types)): ?>
												<?php foreach ($uniform_types as $type): ?>
													<option value="<?php echo $type['id']; ?>" <?php echo (isset($filters['uniform_type_id']) && $filters['uniform_type_id'] == $type['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($type['name']); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Board</label>
										<select name="board_id" class="select">
											<option value="">All Boards</option>
											<?php if (!empty($boards)): ?>
												<?php foreach ($boards as $board): ?>
													<option value="<?php echo $board['id']; ?>" <?php echo (isset($filters['board_id']) && $filters['board_id'] == $board['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($board['board_name']); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Material</label>
										<select name="material_id" class="select">
											<option value="">All Materials</option>
											<?php if (!empty($materials)): ?>
												<?php foreach ($materials as $material): ?>
													<option value="<?php echo $material['id']; ?>" <?php echo (isset($filters['material_id']) && $filters['material_id'] == $material['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($material['name']); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Gender</label>
										<select name="gender" class="select">
											<option value="">All Genders</option>
											<option value="male" <?php echo (isset($filters['gender']) && $filters['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
											<option value="female" <?php echo (isset($filters['gender']) && $filters['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
											<option value="unisex" <?php echo (isset($filters['gender']) && $filters['gender'] == 'unisex') ? 'selected' : ''; ?>>Unisex</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Branch</label>
										<select name="branch_id" class="select">
											<option value="">All Branches</option>
											<?php if (!empty($branches)): ?>
												<?php foreach ($branches as $branch): ?>
													<option value="<?php echo $branch['id']; ?>" <?php echo (isset($filters['branch_id']) && $filters['branch_id'] == $branch['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($branch['branch_name']); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Uniforms List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($uniforms)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Uniforms: <strong><?php echo $total_uniforms; ?></strong></p>
			</div>
		<?php endif; ?>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product Name</th>
						<th>Uniform Type</th>
						<th>School</th>
						<th>Branch</th>
						<th>Board</th>
						<th>Product Type</th>
						<th>Gender</th>
						<th>MRP</th>
						<th>Selling Price</th>
						<th>GST %</th>
						<th>Status</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($uniforms)): ?>
						<?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($uniforms as $uniform): ?>
							<tr>
								<td>
									<?php if (!empty($uniform['thumbnail'])): ?>
										<?php 
										// Handle both old and new image path formats
										$stored_path = trim($uniform['thumbnail']);
										
										// Build array of possible paths to try
										$possible_paths = array();
										
										// If path already includes assets/uploads/, use as is
										if (strpos($stored_path, 'assets/uploads/') !== false) {
											$possible_paths[] = $stored_path;
										}
										// New format: vendors/{vendor_id}/uniforms/images/{filename}
										elseif (strpos($stored_path, 'vendors/') === 0) {
											$possible_paths[] = 'assets/uploads/' . $stored_path;
										}
										// Old format 1: uploads/uniforms/{vendor_id}/{filename}
										elseif (strpos($stored_path, 'uploads/uniforms/') === 0) {
											$possible_paths[] = 'assets/' . $stored_path;
											$possible_paths[] = $stored_path; // Also try root level
										}
										// Old format 2: uniforms/{vendor_id}/{filename} (without uploads/ prefix)
										elseif (strpos($stored_path, 'uniforms/') === 0 && strpos($stored_path, 'uploads/') === false) {
											// Try root level uploads/ first (where old files likely are)
											$possible_paths[] = 'uploads/' . $stored_path;
											// Then try assets/uploads/
											$possible_paths[] = 'assets/uploads/' . $stored_path;
										}
										// Old format: uploads/... (any other uploads path)
										elseif (strpos($stored_path, 'uploads/') === 0) {
											$possible_paths[] = $stored_path; // Root level
											$possible_paths[] = 'assets/' . $stored_path; // Assets level
										}
										// If path doesn't start with any known prefix
										else {
											$possible_paths[] = 'uploads/' . ltrim($stored_path, '/');
											$possible_paths[] = 'assets/uploads/' . ltrim($stored_path, '/');
										}
										
										// Use first path as primary, others as fallbacks
										$image_url = base_url($possible_paths[0]);
										$fallback_urls = array_slice($possible_paths, 1);
										?>
										<img src="<?php echo $image_url; ?>" 
											<?php if (!empty($fallback_urls)): ?>data-fallbacks="<?php echo htmlspecialchars(json_encode(array_map(function($p) { return base_url($p); }, $fallback_urls))); ?>"<?php endif; ?>
											alt="Uniform" 
											style="width: 50px; height: 60px; object-fit: cover; border-radius: 4px;" 
											onerror="(function(img) {
												var fallbacks = img.getAttribute('data-fallbacks');
												if (fallbacks) {
													try {
														var urls = JSON.parse(fallbacks);
														var currentIndex = parseInt(img.getAttribute('data-fallback-index') || '0');
														if (currentIndex < urls.length) {
															img.setAttribute('data-fallback-index', currentIndex + 1);
															img.src = urls[currentIndex];
															return;
														}
													} catch(e) {}
												}
												img.style.display = 'none';
												var placeholder = document.createElement('div');
												placeholder.style.cssText = 'width: 50px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;';
												var icon = document.createElement('i');
												icon.className = 'isax isax-image';
												icon.style.cssText = 'font-size: 20px; color: #999;';
												placeholder.appendChild(icon);
												img.parentNode.appendChild(placeholder);
											})(this)">
									<?php else: ?>
										<div style="width: 50px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
											<i class="isax isax-image" style="font-size: 20px; color: #999;"></i>
										</div>
									<?php endif; ?>
								</td>
								<td>
									<strong><?php echo htmlspecialchars($uniform['product_name']); ?></strong>
									<?php if (!empty($uniform['isbn'])): ?>
										<br><small class="text-muted">ISBN/SKU: <?php echo htmlspecialchars($uniform['isbn']); ?></small>
									<?php endif; ?>
								</td>
								<td><?php echo htmlspecialchars($uniform['uniform_type_name'] ? $uniform['uniform_type_name'] : '-'); ?></td>
								<td><?php echo htmlspecialchars($uniform['school_name'] ? $uniform['school_name'] : '-'); ?></td>
								<td><?php echo htmlspecialchars($uniform['branch_name'] ? $uniform['branch_name'] : '-'); ?></td>
								<td><?php echo htmlspecialchars($uniform['board_name'] ? $uniform['board_name'] : '-'); ?></td>
								<td>
									<?php
										$types = [];

										if (!empty($uniform['is_individual']) && $uniform['is_individual'] == 1) {
											$types[] = 'Individual';
										}

										if (!empty($uniform['is_set']) && $uniform['is_set'] == 1) {
											$types[] = 'Set';
										}

										echo !empty($types) ? implode(', ', $types) : '-';
									?>
								</td>
								<td>
									<span class="badge badge-info"><?php echo ucfirst($uniform['gender']); ?></span>
								</td>
								<td>
									<?php if (!empty($uniform['size_prices'])): ?>
										<?php if ($uniform['min_mrp'] == $uniform['max_mrp']): ?>
											₹<?php echo number_format($uniform['min_mrp'], 2); ?>
										<?php else: ?>
											₹<?php echo number_format($uniform['min_mrp'], 2); ?> - ₹<?php echo number_format($uniform['max_mrp'], 2); ?>
										<?php endif; ?>
									<?php elseif (!empty($uniform['price'])): ?>
										₹<?php echo number_format($uniform['price'], 2); ?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!empty($uniform['size_prices'])): ?>
										<?php if ($uniform['min_selling_price'] == $uniform['max_selling_price']): ?>
											₹<?php echo number_format($uniform['min_selling_price'], 2); ?>
										<?php else: ?>
											₹<?php echo number_format($uniform['min_selling_price'], 2); ?> - ₹<?php echo number_format($uniform['max_selling_price'], 2); ?>
										<?php endif; ?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!empty($uniform['gst_percentage'])): ?>
										<?php echo number_format($uniform['gst_percentage'], 2); ?>%
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<div class="form-check form-switch">
										<input class="form-check-input"
											type="checkbox"
											id="status-switch-<?php echo $uniform['id']; ?>"
											<?php echo $uniform['status'] === 'active' ? 'checked' : ''; ?>
											onchange="toggleUniformStatus(
												<?php echo $uniform['id']; ?>,
												this.checked ? 'inactive' : 'active'
											)">
									</div>
								</td>

								<td class="text-end">
									<a href="<?php echo base_url('products/uniforms/edit/' . $uniform['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
									<a href="<?php echo base_url('products/uniforms/delete/' . $uniform['id']); ?>" onclick="return confirm('Are you sure you want to delete this uniform?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
										<i class="isax isax-trash"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="13" class="text-center text-muted">No uniforms found</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<?php if (!empty($uniforms)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/uniforms?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
									<a class="page-link" href="<?php echo base_url('products/uniforms?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
								</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/uniforms?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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
function toggleUniformStatus(uniformId, currentStatus)
{
	const newStatus = (currentStatus === 'active') ? 'inactive' : 'active';

	$.ajax({
		url: "<?php echo base_url('products/uniforms/toggle_status'); ?>/" + uniformId,
		type: "POST",
		dataType: "json",
		data: {
			status: newStatus,
			<?php echo $this->security->get_csrf_token_name(); ?>:
			"<?php echo $this->security->get_csrf_hash(); ?>"
		},
		success: function(response) {
			if (response.status !== 'success') {
				alert(response.message);
				// revert toggle
				$('#status-switch-' + uniformId).prop('checked', currentStatus === 'active');
			}
		},
		error: function() {
			alert('Something went wrong');
			$('#status-switch-' + uniformId).prop('checked', currentStatus === 'active');
		}
	});
}
</script>

