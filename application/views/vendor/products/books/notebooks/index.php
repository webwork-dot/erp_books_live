<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Notebooks</h6>
	</div>
	<div>
		<a href="<?php echo base_url('products/notebooks/add' : 'products/notebooks/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add"></i> Add New Notebook
		</a>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?php echo base_url('products/notebooks' : 'products/notebooks'); ?>">
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
							<a href="<?php echo base_url('products/notebooks' : 'products/notebooks'); ?>" class="btn btn-outline-secondary">Clear</a>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Accordion for Additional Filters -->
			<div class="accordion" id="filterAccordion">
				<div class="accordion-item">
					<h2 class="accordion-header" id="headingFilters">
						<button class="accordion-button <?php echo (isset($filters['brand_id']) || isset($filters['type_id']) || isset($filters['binding_type'])) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="<?php echo (isset($filters['brand_id']) || isset($filters['type_id']) || isset($filters['binding_type'])) ? 'true' : 'false'; ?>" aria-controls="collapseFilters">
							<i class="isax isax-filter me-2"></i> Additional Filters
						</button>
					</h2>
					<div id="collapseFilters" class="accordion-collapse collapse <?php echo (isset($filters['brand_id']) || isset($filters['type_id']) || isset($filters['binding_type'])) ? 'show' : ''; ?>" aria-labelledby="headingFilters" data-bs-parent="#filterAccordion">
						<div class="accordion-body">
							<div class="row gx-3">
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Brand</label>
										<select name="brand_id" class="select">
											<option value="">All Brands</option>
											<?php if (!empty($brands)): ?>
												<?php foreach ($brands as $brand): ?>
													<option value="<?php echo $brand['id']; ?>" <?php echo (isset($filters['brand_id']) && $filters['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($brand['name']); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Type</label>
										<select name="type_id" class="select">
											<option value="">All Types</option>
											<?php if (!empty($types)): ?>
												<?php foreach ($types as $type): ?>
													<option value="<?php echo $type['id']; ?>" <?php echo (isset($filters['type_id']) && $filters['type_id'] == $type['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($type['name']); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Binding Type</label>
										<select name="binding_type" class="select">
											<option value="">All Binding Types</option>
											<option value="center_binding" <?php echo (isset($filters['binding_type']) && $filters['binding_type'] == 'center_binding') ? 'selected' : ''; ?>>Center Binding</option>
											<option value="perfect_binding" <?php echo (isset($filters['binding_type']) && $filters['binding_type'] == 'perfect_binding') ? 'selected' : ''; ?>>Perfect Binding</option>
											<option value="spiral_binding" <?php echo (isset($filters['binding_type']) && $filters['binding_type'] == 'spiral_binding') ? 'selected' : ''; ?>>Spiral Binding</option>
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

<!-- Notebook List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($notebooks)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Notebooks: <strong><?php echo $total_notebooks; ?></strong></p>
			</div>
		<?php endif; ?>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product Name</th>
						<th>Brand</th>
						<th>Type</th>
						<th>Size</th>
						<th>Binding Type</th>
						<th>No. Of Pages</th>
						<th>ISBN/SKU</th>
						<th>MRP</th>
						<th>Selling Price</th>
						<th>GST %</th>
						<th>Status</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($notebook_list)): ?>
						<?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($notebook_list as $notebook): ?>
							<tr>
								<td>
									<?php if (!empty($notebook['thumbnail'])): ?>
										<?php
										$stored_path = trim($notebook['thumbnail']);
										$image_url = '';
										
										// Determine image URL based on stored path format
										if (strpos($stored_path, 'assets/uploads/') !== false) {
											$image_url = base_url($stored_path);
										} elseif (strpos($stored_path, 'vendors/') === 0) {
											$image_url = base_url('assets/uploads/' . $stored_path);
										} else {
											$image_url = base_url('assets/uploads/' . ltrim($stored_path, '/'));
										}
										?>
										<img src="<?php echo $image_url; ?>" alt="Notebook" style="width: 50px; height: 60px; object-fit: cover; border-radius: 4px;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>';">
									<?php else: ?>
										<div style="width: 50px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
											<i class="isax isax-image" style="font-size: 20px; color: #999;"></i>
										</div>
									<?php endif; ?>
								</td>
								<td>
									<strong><?php echo htmlspecialchars($notebook['product_name']); ?></strong>
									<?php if (!empty($notebook['sku'])): ?>
										<br><small class="text-muted">SKU: <?php echo htmlspecialchars($notebook['sku']); ?></small>
									<?php endif; ?>
								</td>
								<td><?php echo htmlspecialchars($notebook['brand_name'] ? $notebook['brand_name'] : '-'); ?></td>
								<td>
									<?php if (!empty($notebook['type_names'])): ?>
										<?php echo htmlspecialchars($notebook['type_names']); ?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td><?php echo htmlspecialchars($notebook['size'] ? $notebook['size'] : '-'); ?></td>
								<td>
									<?php if (!empty($notebook['binding_type'])): ?>
										<?php 
										$binding_types = array(
											'center_binding' => 'Center Binding',
											'perfect_binding' => 'Perfect Binding',
											'spiral_binding' => 'Spiral Binding'
										);
										echo isset($binding_types[$notebook['binding_type']]) ? $binding_types[$notebook['binding_type']] : ucfirst(str_replace('_', ' ', $notebook['binding_type']));
										?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td><?php echo $notebook['no_of_pages'] ? $notebook['no_of_pages'] : '-'; ?></td>
								<td>
									<?php if (!empty($notebook['isbn'])): ?>
										<small><?php echo htmlspecialchars($notebook['isbn']); ?></small>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td><?php echo $notebook['mrp'] ? '₹' . number_format($notebook['mrp'], 2) : '-'; ?></td>
								<td><strong><?php echo $notebook['selling_price'] ? '₹' . number_format($notebook['selling_price'], 2) : '-'; ?></strong></td>
								<td><?php echo $notebook['gst_percentage'] ? number_format($notebook['gst_percentage'], 2) . '%' : '-'; ?></td>
								<td>
									<span class="badge <?php echo $notebook['status'] == 'active' ? 'badge-success' : 'badge-danger'; ?>">
										<?php echo ucfirst($notebook['status']); ?>
									</span>
								</td>
								<td class="text-end">
									<a href="<?php echo base_url('products/notebooks/edit/' . $notebook['id'] : 'products/notebooks/edit/' . $notebook['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
									<a href="<?php echo base_url('products/notebooks/delete/' . $notebook['id'] : 'products/notebooks/delete/' . $notebook['id']); ?>" onclick="return confirm('Are you sure you want to delete this notebook?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
										<i class="isax isax-trash"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="13" class="text-center text-muted">No notebooks found</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<?php if (!empty($notebook_list)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/notebooks?' . http_build_query(array_merge($filters, array('page' => $current_page - 1))) : 'products/notebooks?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
										<a class="page-link" href="<?php echo base_url('products/notebooks?' . http_build_query(array_merge($filters, array('page' => $i))) : 'products/notebooks?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
									</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/notebooks?' . http_build_query(array_merge($filters, array('page' => $current_page + 1))) : 'products/notebooks?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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

