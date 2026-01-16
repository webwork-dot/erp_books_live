<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Textbooks</h6>
	</div>
	<div>
		<a href="<?php echo base_url('products/textbook/add' : 'products/textbook/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add"></i> Add New Textbook
		</a>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?php echo base_url('products/textbook' : 'products/textbook'); ?>">
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
							<a href="<?php echo base_url('products/textbook' : 'products/textbook'); ?>" class="btn btn-outline-secondary">Clear</a>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Accordion for Additional Filters -->
			<div class="accordion" id="filterAccordion">
				<div class="accordion-item">
					<h2 class="accordion-header" id="headingFilters">
						<button class="accordion-button <?php echo (isset($filters['publisher_id']) || isset($filters['board_id']) || isset($filters['type_id']) || isset($filters['grade_id']) || isset($filters['age_id'])) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="<?php echo (isset($filters['publisher_id']) || isset($filters['board_id']) || isset($filters['type_id']) || isset($filters['grade_id']) || isset($filters['age_id'])) ? 'true' : 'false'; ?>" aria-controls="collapseFilters">
							<i class="isax isax-filter me-2"></i> Additional Filters
						</button>
					</h2>
					<div id="collapseFilters" class="accordion-collapse collapse <?php echo (isset($filters['publisher_id']) || isset($filters['board_id']) || isset($filters['type_id']) || isset($filters['grade_id']) || isset($filters['age_id'])) ? 'show' : ''; ?>" aria-labelledby="headingFilters" data-bs-parent="#filterAccordion">
						<div class="accordion-body">
							<div class="row gx-3">
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Publisher</label>
										<select name="publisher_id" class="select">
											<option value="">All Publishers</option>
											<?php if (!empty($publishers)): ?>
												<?php foreach ($publishers as $publisher): ?>
													<option value="<?php echo $publisher['id']; ?>" <?php echo (isset($filters['publisher_id']) && $filters['publisher_id'] == $publisher['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($publisher['name']); ?>
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
								<div class="col-lg-3 col-md-6">
									<div class="mb-3">
										<label class="form-label">Age</label>
										<select name="age_id" class="select">
											<option value="">All Ages</option>
											<?php if (!empty($ages)): ?>
												<?php foreach ($ages as $age): ?>
													<option value="<?php echo $age['id']; ?>" <?php echo (isset($filters['age_id']) && $filters['age_id'] == $age['id']) ? 'selected' : ''; ?>>
														<?php echo htmlspecialchars($age['name']); ?>
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

<!-- Textbook List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($textbooks)): ?>
			<div class="mb-3">
				<p class="text-muted mb-0">Total Textbooks: <strong><?php echo $total_textbooks; ?></strong></p>
			</div>
		<?php endif; ?>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Image</th>
						<th>Product Name</th>
						<th>Publisher</th>
						<th>Board</th>
						<th>Type</th>
						<th>Grade/Age</th>
						<th>Subject</th>
						<th>ISBN/SKU</th>
						<th>MRP</th>
						<th>Selling Price</th>
						<th>GST %</th>
						<th>Status</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($textbook_list)): ?>
						<?php foreach ($textbook_list as $textbook): ?>
							<tr>
								<td>
									<?php if (!empty($textbook['thumbnail'])): ?>
										<img src="<?php echo base_url($textbook['thumbnail']); ?>" alt="Textbook" style="width: 50px; height: 60px; object-fit: cover; border-radius: 4px;" onerror="this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>'">
									<?php else: ?>
										<div style="width: 50px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
											<i class="isax isax-image" style="font-size: 20px; color: #999;"></i>
										</div>
									<?php endif; ?>
								</td>
								<td>
									<strong><?php echo htmlspecialchars($textbook['product_name']); ?></strong>
									<?php if (!empty($textbook['sku'])): ?>
										<br><small class="text-muted">SKU: <?php echo htmlspecialchars($textbook['sku']); ?></small>
									<?php endif; ?>
								</td>
								<td><?php echo htmlspecialchars($textbook['publisher_name'] ? $textbook['publisher_name'] : '-'); ?></td>
								<td><?php echo htmlspecialchars($textbook['board_name'] ? $textbook['board_name'] : '-'); ?></td>
								<td>
									<?php if (!empty($textbook['types'])): ?>
										<?php 
										$type_names = array();
										foreach ($textbook['types'] as $type) {
											$type_names[] = htmlspecialchars($type['name']);
										}
										echo implode(', ', $type_names);
										?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($textbook['grade_age_type'] == 'grade' && !empty($textbook['grades'])): ?>
										<span class="badge badge-info">Grade: </span>
										<?php 
										$grade_names = array();
										foreach ($textbook['grades'] as $grade) {
											$grade_names[] = htmlspecialchars($grade['name']);
										}
										echo implode(', ', $grade_names);
										?>
									<?php elseif ($textbook['grade_age_type'] == 'age' && !empty($textbook['ages'])): ?>
										<span class="badge badge-info">Age: </span>
										<?php 
										$age_names = array();
										foreach ($textbook['ages'] as $age) {
											$age_names[] = htmlspecialchars($age['name']);
										}
										echo implode(', ', $age_names);
										?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!empty($textbook['subjects'])): ?>
										<?php 
										$subject_names = array();
										foreach ($textbook['subjects'] as $subject) {
											$subject_names[] = htmlspecialchars($subject['name']);
										}
										echo implode(', ', $subject_names);
										?>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!empty($textbook['isbn'])): ?>
										<small><?php echo htmlspecialchars($textbook['isbn']); ?></small>
									<?php else: ?>
										<span class="text-muted">-</span>
									<?php endif; ?>
								</td>
								<td><?php echo $textbook['mrp'] ? '₹' . number_format($textbook['mrp'], 2) : '-'; ?></td>
								<td><strong><?php echo $textbook['selling_price'] ? '₹' . number_format($textbook['selling_price'], 2) : '-'; ?></strong></td>
								<td><?php echo $textbook['gst_percentage'] ? number_format($textbook['gst_percentage'], 2) . '%' : '-'; ?></td>
								<td>
									<span class="badge <?php echo $textbook['status'] == 'active' ? 'badge-success' : 'badge-danger'; ?>">
										<?php echo ucfirst($textbook['status']); ?>
									</span>
								</td>
								<td class="text-end">
									<a href="<?php echo base_url('products/textbook/edit/' . $textbook['id'] : 'products/textbook/edit/' . $textbook['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</a>
									<a href="<?php echo base_url('products/textbook/delete/' . $textbook['id'] : 'products/textbook/delete/' . $textbook['id']); ?>" onclick="return confirm('Are you sure you want to delete this textbook?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
										<i class="isax isax-trash"></i>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="13" class="text-center text-muted">No textbooks found</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<?php if (!empty($textbook_list)): ?>
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<?php if ($total_pages > 1): ?>
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm mb-0">
							<?php if ($current_page > 1): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/textbook?' . http_build_query(array_merge($filters, array('page' => $current_page - 1))) : 'products/textbook?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
										<a class="page-link" href="<?php echo base_url('products/textbook?' . http_build_query(array_merge($filters, array('page' => $i))) : 'products/textbook?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
									</li>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ($current_page < $total_pages): ?>
								<li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/textbook?' . http_build_query(array_merge($filters, array('page' => $current_page + 1))) : 'products/textbook?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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


