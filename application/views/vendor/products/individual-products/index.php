<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Individual Products</h6>
	</div>
	<div>
		<a href="<?php echo base_url('products/individual-products/add'); ?>" class="btn btn-primary">
			<i class="isax isax-add"></i> Add New Product
		</a>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Filters -->
<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?php echo base_url('products/individual-products'); ?>">
			<div class="row gx-3">
				<div class="col-lg-4 col-md-6">
					<div class="mb-3">
						<label class="form-label">Search</label>
						<input type="text" name="search" class="form-control" value="<?php echo (isset($filters) && isset($filters['search'])) ? htmlspecialchars($filters['search']) : ''; ?>" placeholder="Product Name, ISBN, SKU...">
					</div>
				</div>
				<div class="col-lg-2 col-md-6">
					<div class="mb-3">
						<label class="form-label">Product Type</label>
						<select name="product_type" class="select">
							<option value="">All Types</option>
							<option value="textbook" <?php echo (isset($filters) && isset($filters['product_type']) && $filters['product_type'] == 'textbook') ? 'selected' : ''; ?>>Textbook</option>
							<option value="notebook" <?php echo (isset($filters) && isset($filters['product_type']) && $filters['product_type'] == 'notebook') ? 'selected' : ''; ?>>Notebook</option>
							<option value="stationery" <?php echo (isset($filters) && isset($filters['product_type']) && $filters['product_type'] == 'stationery') ? 'selected' : ''; ?>>Stationery</option>
							<option value="uniform" <?php echo (isset($filters) && isset($filters['product_type']) && $filters['product_type'] == 'uniform') ? 'selected' : ''; ?>>Uniform</option>
							<option value="individual" <?php echo (isset($filters) && isset($filters['product_type']) && $filters['product_type'] == 'individual') ? 'selected' : ''; ?>>Individual Product</option>
						</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="mb-3">
						<label class="form-label">Category</label>
						<select name="category_id" class="select">
							<option value="">All Categories</option>
							<?php 
							if (isset($categories) && !empty($categories)): 
								foreach ($categories as $cat): 
							?>
								<option value="<?php echo $cat['id']; ?>" <?php echo (isset($filters) && isset($filters['category_id']) && $filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars($cat['name']); ?>
								</option>
							<?php 
								endforeach; 
							endif; 
							?>
						</select>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="mb-3">
						<label class="form-label">Status</label>
						<select name="status" class="select">
							<option value="">All Status</option>
							<option value="active" <?php echo (isset($filters) && isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
							<option value="inactive" <?php echo (isset($filters) && isset($filters['status']) && $filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
						</select>
					</div>
				</div>
				<div class="col-lg-2 col-md-6">
					<div class="mb-3">
						<label class="form-label">&nbsp;</label>
						<div class="d-flex gap-2">
							<button type="submit" class="btn btn-primary flex-fill">Filter</button>
							<a href="<?php echo base_url('products/individual-products'); ?>" class="btn btn-outline-secondary">Clear</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Individual Products List -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($products)): ?>
            <div class="mb-3">
                <p class="text-muted mb-0">Total Individual Products: <strong><?php echo $total_products; ?></strong></p>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Variations</th>
                        <th>SKU/ISBN</th>
                        <th>MRP</th>
                        <th>Selling Price</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products_list)): ?>
                        <?php foreach ($products_list as $product): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="<?php echo base_url($product['image']); ?>" alt="Product" style="width: 60px; height: 70px; object-fit: cover; border-radius: 4px;" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="width: 60px; height: 70px; background: #f0f0f0; border-radius: 4px; display: none; align-items: center; justify-content: center;">
                                            <i class="isax isax-image" style="font-size: 24px; color: #999;"></i>
                                        </div>
                                    <?php else: ?>
                                        <div style="width: 60px; height: 70px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <i class="isax isax-image" style="font-size: 24px; color: #999;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                                    <?php if (isset($product['display_name']) && !empty($product['display_name']) && $product['display_name'] != $product['product_name']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($product['display_name']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?php echo ucfirst($product['product_type']); ?></span>
                                </td>
                                <td>
                                    <?php if (isset($product['category_display']) && !empty($product['category_display'])): ?>
                                        <small><?php echo htmlspecialchars($product['category_display']); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($product['has_variations']) && $product['has_variations']): ?>
                                        <div>
                                            <?php if (!empty($product['variation_types_display'])): ?>
                                                <small class="text-primary"><strong><?php echo htmlspecialchars($product['variation_types_display']); ?></strong></small>
                                                <br>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <i class="isax isax-menu-1"></i> <?php echo isset($product['variation_combinations_count']) ? $product['variation_combinations_count'] : 0; ?> combinations
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($product['sku'])): ?>
                                        <small><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></small>
                                        <?php if (!empty($product['isbn'])): ?><br><?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($product['isbn'])): ?>
                                        <small><strong>ISBN:</strong> <?php echo htmlspecialchars($product['isbn']); ?></small>
                                    <?php endif; ?>
                                    <?php if (empty($product['sku']) && empty($product['isbn'])): ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($product['has_variations']) && $product['has_variations'] && isset($product['min_mrp']) && isset($product['max_mrp'])): ?>
                                        <?php if ($product['min_mrp'] == $product['max_mrp']): ?>
                                            ₹<?php echo number_format($product['min_mrp'], 2); ?>
                                        <?php else: ?>
                                            ₹<?php echo number_format($product['min_mrp'], 2); ?> - ₹<?php echo number_format($product['max_mrp'], 2); ?>
                                        <?php endif; ?>
                                    <?php elseif (isset($product['mrp']) && $product['mrp'] > 0): ?>
                                        ₹<?php echo number_format($product['mrp'], 2); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong>
                                        <?php if (isset($product['has_variations']) && $product['has_variations'] && isset($product['min_selling_price']) && isset($product['max_selling_price'])): ?>
                                            <?php if ($product['min_selling_price'] == $product['max_selling_price']): ?>
                                                ₹<?php echo number_format($product['min_selling_price'], 2); ?>
                                            <?php else: ?>
                                                ₹<?php echo number_format($product['min_selling_price'], 2); ?> - ₹<?php echo number_format($product['max_selling_price'], 2); ?>
                                            <?php endif; ?>
                                        <?php elseif (isset($product['selling_price']) && $product['selling_price'] > 0): ?>
                                            ₹<?php echo number_format($product['selling_price'], 2); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge <?php echo (isset($product['status']) && $product['status'] == 'active') ? 'badge-success' : 'badge-danger'; ?>">
                                        <?php echo isset($product['status']) ? ucfirst($product['status']) : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <?php 
                                    $edit_url = '';
                                    switch($product['product_type']) {
										case 'textbook':
											$edit_url = base_url('products/textbook/edit/' . $product['id']);
                                            break;
										case 'notebook':
											$edit_url = base_url('products/notebook/edit/' . $product['id']);
                                            break;
										case 'stationery':
											$edit_url = base_url('products/stationery/edit/' . $product['id']);
                                            break;
										case 'uniform':
											$edit_url = base_url('products/uniforms/edit/' . $product['id']);
                                            break;
										case 'individual':
											$edit_url = base_url('products/individual-products/edit/' . $product['id']);
                                            break;
                                    }
                                    ?>
                                    <?php if (!empty($edit_url)): ?>
                                        <a href="<?php echo $edit_url; ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                            <i class="isax isax-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php 
                                    $delete_url = '';
                                    switch($product['product_type']) {
										case 'textbook':
											$delete_url = base_url('products/textbook/delete/' . $product['id']);
                                            break;
										case 'notebook':
											$delete_url = base_url('products/notebook/delete/' . $product['id']);
                                            break;
										case 'stationery':
											$delete_url = base_url('products/stationery/delete/' . $product['id']);
                                            break;
										case 'uniform':
											$delete_url = base_url('products/uniforms/delete/' . $product['id']);
                                            break;
										case 'individual':
											$delete_url = base_url('products/individual-products/delete/' . $product['id']);
                                            break;
                                    }
                                    ?>
                                    <?php if (!empty($delete_url)): ?>
                                        <a href="<?php echo $delete_url; ?>" onclick="event.preventDefault(); confirmDelete('<?php echo $delete_url; ?>', 'Are you sure you want to delete this product?'); return false;" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                            <i class="isax isax-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">No individual products found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (!empty($products_list)): ?>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/individual-products?' . http_build_query(array_merge((isset($filters) ? $filters : array()), array('page' => $current_page - 1)))); ?>">Previous</a>
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
										<a class="page-link" href="<?php echo base_url('products/individual-products?' . http_build_query(array_merge((isset($filters) ? $filters : array()), array('page' => $i)))); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
									<a class="page-link" href="<?php echo base_url('products/individual-products?' . http_build_query(array_merge((isset($filters) ? $filters : array()), array('page' => $current_page + 1)))); ?>">Next</a>
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
// Helper functions for SweetAlert
function showError(message) {
	if (typeof Swal !== 'undefined') {
		Swal.fire({
			icon: 'error',
			title: 'Error',
			text: message,
			confirmButtonColor: '#dc3545'
		});
	} else {
		alert('Error: ' + message);
	}
}

function confirmAction(message, title, confirmText, cancelText) {
	if (typeof Swal !== 'undefined') {
		return Swal.fire({
			title: title || 'Confirm',
			text: message,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: confirmText || 'Yes',
			cancelButtonText: cancelText || 'Cancel',
			confirmButtonColor: '#dc3545',
			cancelButtonColor: '#6c757d'
		}).then((result) => {
			return result.isConfirmed;
		});
	} else {
		return Promise.resolve(confirm(message));
	}
}

function confirmDelete(url, message) {
	confirmAction(message || 'Are you sure you want to delete this item?', 'Confirm Delete', 'Yes, Delete', 'Cancel').then(function(confirmed) {
		if (confirmed) {
			window.location.href = url;
		}
	});
}
</script>
