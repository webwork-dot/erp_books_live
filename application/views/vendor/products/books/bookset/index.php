<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Booksets</h6>
	</div>
	<?php if (isset($active_tab) && $active_tab == 'with_product'): ?>
		<div>
			<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset/package/add_with_products' : 'products/bookset/package/add_with_products'); ?>" class="btn btn-primary">
				<i class="isax isax-add"></i> Add Bookset with Products
			</a>
		</div>
	<?php elseif (isset($active_tab) && $active_tab == 'without_product'): ?>
		<div>
			<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset/package/add_without_products' : 'products/bookset/package/add_without_products'); ?>" class="btn btn-primary">
				<i class="isax isax-add"></i> Add Bookset without Products
			</a>
		</div>
	<?php endif; ?>
</div>
<!-- End Breadcrumb -->

<!-- Tabs -->
<style>
	.bookset-tabs-wrapper {
		background: #ffffff;
		border-bottom: 1px solid #dee2e6;
		margin-bottom: 1.5rem;
		padding: 0;
	}
	.bookset-tabs {
		border-bottom: 1px solid #dee2e6;
		margin-bottom: 0;
		padding-left: 0;
		padding-right: 0;
	}
	.bookset-tabs .nav-link {
		color: #6c757d;
		font-weight: 500;
		font-size: 0.875rem;
		padding: 0.75rem 1.25rem;
		border: 1px solid transparent;
		border-top-left-radius: 0.375rem;
		border-top-right-radius: 0.375rem;
		border-bottom: none;
		margin-bottom: -1px;
		transition: all 0.2s ease;
		background: transparent;
		position: relative;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		white-space: nowrap;
	}
	.bookset-tabs .nav-link:hover {
		color: #495057;
		background: #f8f9fa;
		border-color: #dee2e6 #dee2e6 transparent;
	}
	.bookset-tabs .nav-link.active {
		color:rgb(255, 255, 255);
		background: #3550dc;
		border-color: #dee2e6 #dee2e6 #ffffff;
		font-weight: 600;
		z-index: 1;
	}
	.bookset-tabs .nav-link.active::after {
		content: '';
		position: absolute;
		bottom: -1px;
		left: 0;
		right: 0;
		height: 1px;
		background: #ffffff;
	}
	.bookset-tabs .nav-link i {
		font-size: 1em;
	}
	.bookset-tabs .nav-link.active i {
		color: #ffffff;
	}
	.bookset-tabs .nav-item {
		margin-bottom: 0;
	}
</style>
<div class="bookset-tabs-wrapper">
	<ul class="nav nav-tabs bookset-tabs" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (isset($active_tab) && $active_tab == 'with_product') ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset?tab=with_product' : 'products/bookset?tab=with_product'); ?>">
				<i class="isax isax-box-1"></i>
				<span>Bookset with Product</span>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link <?php echo (isset($active_tab) && $active_tab == 'without_product') ? 'active' : ''; ?>" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset?tab=without_product' : 'products/bookset?tab=without_product'); ?>">
				<i class="isax isax-box"></i>
				<span>Bookset without Product</span>
			</a>
		</li>
	</ul>
</div>

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset' : 'products/bookset'); ?>">
			<input type="hidden" name="tab" value="<?php echo isset($active_tab) ? htmlspecialchars($active_tab) : 'with_product'; ?>">
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
							<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset?tab=' . (isset($active_tab) ? $active_tab : 'with_product') : 'products/bookset?tab=' . (isset($active_tab) ? $active_tab : 'with_product')); ?>" class="btn btn-outline-secondary">Clear</a>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Accordion for Additional Filters -->
			<div class="accordion" id="filterAccordion">
				<div class="accordion-item">
					<h2 class="accordion-header" id="headingFilters">
						<button class="accordion-button <?php echo (isset($filters['type']) || isset($filters['school_id']) || isset($filters['board_id']) || isset($filters['grade_id'])) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="<?php echo (isset($filters['type']) || isset($filters['school_id']) || isset($filters['board_id']) || isset($filters['grade_id'])) ? 'true' : 'false'; ?>" aria-controls="collapseFilters">
							<i class="isax isax-filter me-2"></i> Additional Filters
						</button>
					</h2>
					<div id="collapseFilters" class="accordion-collapse collapse <?php echo (isset($filters['type']) || isset($filters['school_id']) || isset($filters['board_id']) || isset($filters['grade_id'])) ? 'show' : ''; ?>" aria-labelledby="headingFilters" data-bs-parent="#filterAccordion">
						<div class="accordion-body">
							<div class="row gx-3">
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Type</label>
										<select name="type" class="select">
											<option value="">All Types</option>
											<?php if (!empty($types)): ?>
												<?php foreach ($types as $type): ?>
													<option value="<?php echo $type['id']; ?>" <?php echo (isset($filters['type']) && $filters['type'] == $type['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($type['name']); ?>
													</option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
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
										<label class="form-label">Grade</label>
										<select name="grade_id" class="select">
											<option value="">All Grades</option>
											<?php if (!empty($grades)): ?>
												<?php foreach ($grades as $grade): ?>
													<option value="<?php echo $grade['id']; ?>" <?php echo (isset($filters['grade_id']) && $filters['grade_id'] == $grade['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($grade['name']); ?>
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

<!-- Bookset List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($bookset_list)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Booksets: <strong><?php echo $total_booksets; ?></strong></p>
			</div>
		<?php endif; ?>
		<?php if (!empty($bookset_list)): ?>
			<?php foreach ($bookset_list as $bookset): ?>
				<?php 
				$is_bookset = isset($bookset['type']) && $bookset['type'] == 'bookset';
				$bookset_id = $bookset['id'];
				$bookset_name = $is_bookset ? ($bookset['bookset_name'] ?: '-') : $bookset['package_name'];
				?>
				<div class="bookset-card mb-3 border rounded" style="background: #fff;">
					<div class="bookset-header p-3 border-bottom" style="background: linear-gradient(to right, rgba(0,123,255,0.05), transparent); cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#bookset_<?php echo $bookset_id; ?>" aria-expanded="false">
						<div class="row align-items-center">
							<!-- School with Image -->
							<div class="col-md-2">
								<div class="d-flex align-items-center gap-2">
									<?php if (!empty($bookset['thumbnail'])): ?>
										<?php
										$stored_path = trim($bookset['thumbnail']);
										if (strpos($stored_path, 'http://') === 0 || strpos($stored_path, 'https://') === 0) {
											$image_url = $stored_path;
										} else {
											$image_url = base_url('uploads/schools/' . $stored_path);
										}
										?>
										<img src="<?php echo $image_url; ?>" alt="School" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>'">
									<?php else: ?>
										<div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
											<i class="isax isax-building" style="font-size: 24px; color: #999;"></i>
										</div>
									<?php endif; ?>
									<div>
										<small class="text-muted d-block">School</small>
										<strong><?php echo htmlspecialchars($bookset['school_name'] ?: '-'); ?></strong>
									</div>
								</div>
							</div>
							<!-- Board -->
							<div class="col-md-2">
								<small class="text-muted d-block">Board</small>
								<strong><?php echo htmlspecialchars($bookset['board_name'] ?: '-'); ?></strong>
							</div>
							<!-- Grade -->
							<div class="col-md-2">
								<small class="text-muted d-block">Grade</small>
								<strong><?php echo htmlspecialchars($bookset['grade_name'] ?: '-'); ?></strong>
							</div>
							<!-- Content -->
							<div class="col-md-4">
								<small class="text-muted d-block">Content</small>
								<div>
									<?php if ($is_bookset): ?>
										<small class="text">
											<?php echo $bookset['package_count'] ?? 0; ?> Package(s) • <?php echo $bookset['product_count'] ?? 0; ?> Product(s)
										</small>
									<?php else: ?>
										<small class="text">
											<?php echo $bookset['product_count'] ?? 0; ?> Product(s)
										</small>
									<?php endif; ?>
								</div>
							</div>
							<!-- Action -->
							<div class="col-md-2 text-end">
								<div class="d-flex align-items-center justify-content-end gap-2">
									<i class="isax isax-arrow-down-2" id="icon_<?php echo $bookset_id; ?>"></i>
									<?php if ($is_bookset): ?>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset/edit/' . $bookset_id : 'products/bookset/edit/' . $bookset_id); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit" onclick="event.stopPropagation();">
											<i class="isax isax-edit"></i>
										</a>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset/delete/' . $bookset_id : 'products/bookset/delete/' . $bookset_id); ?>" onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this bookset? This will also delete all associated packages and products.');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
											<i class="isax isax-trash"></i>
										</a>
									<?php else: ?>
										<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset/package/delete/' . $bookset['id'] : 'products/bookset/package/delete/' . $bookset['id']); ?>" onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this package?');" class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="tooltip" title="Delete">
											<i class="isax isax-trash"></i>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					
					<div id="bookset_<?php echo $bookset_id; ?>" class="collapse">
						<div class="p-3">
							<?php if ($is_bookset && !empty($bookset['packages'])): ?>
								<?php foreach ($bookset['packages'] as $package): ?>
									<div class="package-item mb-3 p-3 border rounded" style="background: #f8f9fa;">
										<div class="d-flex justify-content-between align-items-center mb-2">
											<h6 class="mb-0">
												<i class="isax isax-box-1 me-2"></i>
												<?php echo htmlspecialchars($package['package_name']); ?>
												<?php if (!empty($package['category'])): ?>
													<span class="badge bg-primary ms-2">
														<?php echo ucfirst($package['category']); ?>
													</span>
												<?php endif; ?>
											</h6>
											<div>
												<span class="badge bg-info me-2">
													<?php echo ucfirst($package['is_it']); ?>
												</span>
												<span class="badge bg-secondary">
													Weight: <?php echo number_format($package['package_weight'], 2); ?> gm
												</span>
											</div>
										</div>
										
										<?php if (!empty($package['products'])): ?>
											<div class="table-responsive mt-2">
												<table class="table table-sm table-bordered mb-0">
													<thead class="table-light">
														<tr>
															<th style="width: 5%;">#</th>
															<th style="width: 35%;">Product Name</th>
															<th style="width: 30%;">Display Name</th>
															<th style="width: 15%;">Quantity</th>
															<th style="width: 15%;">Discounted MRP</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($package['products'] as $idx => $product): ?>
															<tr>
																<td><?php echo $idx + 1; ?></td>
																<td>
																	<?php 
																	// Get product name from the actual product table
																	$product_name = $product['display_name'];
																	echo htmlspecialchars($product_name);
																	?>
																</td>
																<td><?php echo htmlspecialchars($product['display_name']); ?></td>
																<td class="text-center"><?php echo $product['quantity']; ?></td>
																<td>₹<?php echo number_format($product['discounted_mrp'], 2); ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										<?php else: ?>
											<p class="text-muted mb-0"><small>No products in this package</small></p>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							<?php elseif (!$is_bookset && !empty($bookset['packages'])): ?>
								<?php $package = $bookset['packages'][0]; ?>
								<div class="package-item p-3 border rounded" style="background: #f8f9fa;">
									<div class="d-flex justify-content-between align-items-center mb-2">
										<h6 class="mb-0">
											<i class="isax isax-box-1 me-2"></i>
											<?php echo htmlspecialchars($package['package_name']); ?>
											<?php if (!empty($bookset['category'])): ?>
												<span class="badge bg-primary ms-2">
													<?php echo ucfirst($bookset['category']); ?>
												</span>
											<?php endif; ?>
										</h6>
										<div>
											<span class="badge bg-info me-2">
												<?php echo ucfirst($bookset['is_it'] ?? 'N/A'); ?>
											</span>
											<span class="badge bg-secondary">
												Weight: <?php echo number_format($bookset['package_weight'] ?? 0, 2); ?> gm
											</span>
										</div>
									</div>
									
									<?php if (!empty($package['products'])): ?>
										<div class="table-responsive mt-2">
											<table class="table table-sm table-bordered mb-0">
												<thead class="table-light">
													<tr>
														<th style="width: 5%;">#</th>
														<th style="width: 35%;">Product Name</th>
														<th style="width: 30%;">Display Name</th>
														<th style="width: 15%;">Quantity</th>
														<th style="width: 15%;">Discounted MRP</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($package['products'] as $idx => $product): ?>
														<tr>
															<td><?php echo $idx + 1; ?></td>
															<td><?php echo htmlspecialchars($product['display_name']); ?></td>
															<td><?php echo htmlspecialchars($product['display_name']); ?></td>
															<td class="text-center"><?php echo $product['quantity']; ?></td>
															<td>₹<?php echo number_format($product['discounted_mrp'], 2); ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									<?php else: ?>
										<p class="text-muted mb-0"><small>No products in this package</small></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="text-center text-muted py-5">
				<i class="isax isax-box" style="font-size: 48px; color: #ddd;"></i>
				<p class="mt-3">No booksets found</p>
			</div>
		<?php endif; ?>
	</div>
		
		<?php if (!empty($bookset_list)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset?' . http_build_query(array_merge($filters, array('page' => $current_page - 1, 'tab' => isset($active_tab) ? $active_tab : 'with_product'))) : 'products/bookset?' . http_build_query(array_merge($filters, array('page' => $current_page - 1, 'tab' => isset($active_tab) ? $active_tab : 'with_product')))); ?>">Previous</a>
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
										<a class="page-link" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset?' . http_build_query(array_merge($filters, array('page' => $i, 'tab' => isset($active_tab) ? $active_tab : 'with_product'))) : 'products/bookset?' . http_build_query(array_merge($filters, array('page' => $i, 'tab' => isset($active_tab) ? $active_tab : 'with_product')))); ?>"><?php echo $i; ?></a>
									</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset?' . http_build_query(array_merge($filters, array('page' => $current_page + 1, 'tab' => isset($active_tab) ? $active_tab : 'with_product'))) : 'products/bookset?' . http_build_query(array_merge($filters, array('page' => $current_page + 1, 'tab' => isset($active_tab) ? $active_tab : 'with_product')))); ?>">Next</a>
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
	// Handle collapse icon rotation
	var collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
	collapseElements.forEach(function(element) {
		element.addEventListener('click', function() {
			var targetId = this.getAttribute('data-bs-target');
			var iconId = targetId.replace('#bookset_', '');
			var icon = document.querySelector('#icon_' + iconId);
			if (icon) {
				setTimeout(function() {
					var targetElement = document.querySelector(targetId);
					if (targetElement) {
						var isExpanded = targetElement.classList.contains('show');
						if (isExpanded) {
							icon.classList.remove('isax-arrow-down-2');
							icon.classList.add('isax-arrow-up-2');
						} else {
							icon.classList.remove('isax-arrow-up-2');
							icon.classList.add('isax-arrow-down-2');
						}
					}
				}, 100);
			}
		});
	});
});
</script>
