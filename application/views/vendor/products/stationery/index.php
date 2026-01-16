<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Stationery</h6>
	</div>
	<div>
		<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery/add' : 'products/stationery/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add"></i> Add New Stationery
		</a>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery' : 'products/stationery'); ?>">
			<div class="row gx-3">
				<div class="col-lg-4 col-md-6">
					<div class="mb-3">
						<label class="form-label">Search</label>
						<input type="text" name="search" class="form-control" value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="Product Name, ISBN, SKU...">
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="mb-3">
						<label class="form-label">Category</label>
						<select name="category_id" class="select">
							<option value="">All Categories</option>
							<?php if (!empty($categories)): ?>
								<?php foreach ($categories as $category): ?>
									<option value="<?php echo $category['id']; ?>" <?php echo (isset($filters['category_id']) && $filters['category_id'] == $category['id']) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($category['name']); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="mb-3">
						<label class="form-label">Status</label>
						<select name="status" class="select">
							<option value="">All Status</option>
							<option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
							<option value="inactive" <?php echo (isset($filters['status']) && $filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
						</select>
					</div>
				</div>
				<div class="col-lg-2 col-md-6">
					<div class="mb-3">
						<label class="form-label">&nbsp;</label>
						<button type="submit" class="btn btn-primary w-100">Filter</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Stationery List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($stationery_list)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Stationery: <strong><?php echo $total_stationery; ?></strong></p>
			</div>
		<?php endif; ?>
		<table class="table">
			<thead>
				<tr>
					<th>SR No.</th>
					<th>Product Name</th>
					<th>Category</th>
					<th>SKU</th>
					<th>MRP</th>
					<th>Selling Price</th>
					<th>Status</th>
					<th class="text-end">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($stationery_list)): ?>
					<?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($stationery_list as $stationery): ?>
						<tr>
							<td><?php echo $sr_no++; ?></td>
							<td><?php echo htmlspecialchars($stationery['product_name']); ?></td>
							<td><?php echo htmlspecialchars($stationery['category_name']); ?></td>
							<td><?php echo htmlspecialchars($stationery['sku'] ? $stationery['sku'] : '-'); ?></td>
							<td><?php echo $stationery['mrp'] ? '₹' . number_format($stationery['mrp'], 2) : '-'; ?></td>
							<td><?php echo $stationery['selling_price'] ? '₹' . number_format($stationery['selling_price'], 2) : '-'; ?></td>
							<td>
								<span class="badge <?php echo $stationery['status'] == 'active' ? 'badge-success' : 'badge-danger'; ?>">
									<?php echo ucfirst($stationery['status']); ?>
								</span>
							</td>
							<td class="text-end">
								<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery/edit/' . $stationery['id'] : 'products/stationery/edit/' . $stationery['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
									<i class="isax isax-edit"></i>
								</a>
								<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery/delete/' . $stationery['id'] : 'products/stationery/delete/' . $stationery['id']); ?>" onclick="return confirm('Are you sure you want to delete this stationery?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
									<i class="isax isax-trash"></i>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="8" class="text-center text-muted">No stationery found</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		
		<?php if (!empty($stationery_list)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery?' . http_build_query(array_merge($filters, array('page' => $current_page - 1))) : 'products/stationery?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
										<a class="page-link" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery?' . http_build_query(array_merge($filters, array('page' => $i))) : 'products/stationery?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
									</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery?' . http_build_query(array_merge($filters, array('page' => $current_page + 1))) : 'products/stationery?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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

