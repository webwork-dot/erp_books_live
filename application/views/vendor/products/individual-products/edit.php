<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/individual-products' : 'products/individual-products'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit Individual Product</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(base_url('products/individual-products/update/' . (isset($product['id']) ? $product['id'] : '')), array('id' => 'individual-product-form')); ?>
<!-- Images Card (Outside Main Card) -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3  mb-3">Images</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Images (Size: 440px * 530px)</label>
							<input type="file" name="images[]" id="images" class="form-control" form="individual-product-form" accept="image/*" multiple>
							<small class="text-muted fs-13">You can select multiple images. Recommended size: 440px × 530px. Drag images to reorder. Click "Set as Main" to choose the main image. Leave empty to keep existing images.</small>
							<div id="image-preview" class="mt-3 image-sortable-container">
								<?php if (isset($product_images) && !empty($product_images)): ?>
									<?php 
									$main_image_id = null;
									foreach ($product_images as $img): 
										if (isset($img['is_main']) && $img['is_main'] == 1) {
											$main_image_id = $img['id'];
										}
									endforeach;
									?>
									<?php foreach ($product_images as $img): ?>
										<?php
										// Handle different path formats
										$image_path = $img['image_path'];
										if (strpos($image_path, 'assets/uploads/') === 0) {
											$image_url = $image_path;
										} elseif (strpos($image_path, 'vendors/') === 0) {
											$image_url = 'assets/uploads/' . $image_path;
										} else {
											$image_url = 'assets/uploads/' . ltrim($image_path, '/');
										}
										?>
										<div class="image-preview-item existing-image" data-image-id="<?php echo $img['id']; ?>" style="position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top;">
											<img src="<?php echo base_url($image_url); ?>" alt="Product Image" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px 4px 0 0; display: block;">
											<div class="image-buttons" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.75); display: flex; gap: 2px; padding: 3px; border-radius: 0 0 4px 4px;">
												<button type="button" class="btn btn-sm set-main-existing-btn" data-image-id="<?php echo $img['id']; ?>" style="font-size: 10px; padding: 3px 6px; flex: 1; line-height: 1.2; border: none; white-space: nowrap; <?php echo ($img['id'] == $main_image_id) ? 'background: #28a745; color: #fff;' : 'background: #007bff; color: #fff;'; ?>">
													<?php echo ($img['id'] == $main_image_id) ? 'Main' : 'Set Main'; ?>
												</button>
												<button type="button" class="btn btn-sm btn-danger remove-existing-image-btn" data-image-id="<?php echo $img['id']; ?>" style="font-size: 10px; padding: 3px 6px; flex: 0 0 auto; line-height: 1.2; border: none; min-width: 28px;">
													<i class="isax isax-trash" style="font-size: 11px;"></i>
												</button>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
							<input type="hidden" name="image_order" id="image_order" value="">
							<input type="hidden" name="main_image_id" id="main_image_id" value="<?php echo isset($main_image_id) ? $main_image_id : ''; ?>">
							<input type="hidden" name="deleted_image_ids" id="deleted_image_ids" value="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class="border-bottom pb-2 mb-2">Variation Selection</h2>
				
				<?php if (validation_errors()): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Please fix the following errors:</strong>
						<ul class="mb-0 mt-2">
							<?php echo validation_errors('<li>', '</li>'); ?>
						</ul>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>
				
				<div class="row gx-2">
					<div class="col-lg-12">
						<div class="mb-2">
							<label class="form-label small">Product Variations</label>
							<div class="input-group">
								<select name="variation_type_ids[]" id="variation_type_ids" class="select" multiple>
									<?php if (!empty($variation_types)): ?>
										<?php foreach ($variation_types as $type): ?>
											<option value="<?php echo $type['id']; ?>" <?php echo (isset($selected_variation_type_ids) && in_array($type['id'], $selected_variation_type_ids)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type['name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/variations' : 'products/variations'); ?>" class="btn btn-outline-primary btn-sm" target="_blank" style="padding: 0.4rem 0.8rem;">
									<i class="isax isax-setting-2"></i> Manage
								</a>
							</div>
							<small class="text-muted fs-12">Select variation types (e.g., Size, Color, Material). Leave empty for no variations. <a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/variations' : 'products/variations'); ?>" target="_blank">Manage variation types</a></small>
						</div>
					</div>
				</div>
				<div id="variation_info_section" style="display: none; margin-top: 10px;">
					<div class="alert alert-info">
						<strong>Selected Variations:</strong>
						<div id="selected_variations_info"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class=" border-bottom pb-3  mb-3">Details</h2>
				
				<!-- Basic Information -->
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Category <span class="text-danger">*</span></label>
							<div class="input-group">
							<select name="category_id" id="category_id" class="select" required>
								<option value="">Select Category</option>
								<?php if (!empty($parent_categories)): ?>
									<?php foreach ($parent_categories as $parent): ?>
										<option value="<?php echo $parent['id']; ?>" <?php echo set_select('category_id', $parent['id'], (isset($selected_category_id) && $selected_category_id == $parent['id'])); ?>><?php echo htmlspecialchars($parent['name']); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
								<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="padding: 0.4rem 1rem;">
									<i class="isax isax-add"></i> Add
								</button>
							</div>
							<small class="text-muted fs-13">Select a main category.</small>
							<?php echo form_error('category_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Subcategory</label>
							<div class="input-group">
							<select name="subcategory_id" id="subcategory_id" class="select">
								<option value="">Select Subcategory (Optional)</option>
								<?php if (!empty($subcategories)): ?>
									<?php foreach ($subcategories as $sub): ?>
										<option value="<?php echo $sub['id']; ?>" <?php echo set_select('subcategory_id', $sub['id'], (isset($selected_subcategory_id) && $selected_subcategory_id == $sub['id'])); ?>><?php echo htmlspecialchars($sub['name']); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
								<button type="button" class="btn btn-outline-primary" id="addSubcategoryBtn" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal" style="padding: 0.4rem 1rem;" disabled>
									<i class="isax isax-add"></i> Add
								</button>
							</div>
							<small class="text-muted fs-13">Select a subcategory (optional).</small>
							<?php echo form_error('subcategory_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Product Name/Display Name <span class="text-danger">*</span></label>
							<input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo set_value('product_name', isset($product['product_name']) ? $product['product_name'] : ''); ?>" required>
							<?php echo form_error('product_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Display Name</label>
							<input type="text" name="display_name" id="display_name" class="form-control" value="<?php echo set_value('display_name', isset($product['display_name']) ? $product['display_name'] : ''); ?>">
							<small class="text-muted fs-13">Optional display name for the product</small>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">ISBN / Bar Code No. / SKU No.</label>
							<input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo set_value('isbn', isset($product['isbn']) ? $product['isbn'] : ''); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Bar Code</label>
							<input type="text" name="barcode" id="barcode" class="form-control" value="<?php echo set_value('barcode', isset($product['barcode']) ? $product['barcode'] : ''); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">SKU</label>
							<input type="text" name="sku" id="sku" class="form-control" value="<?php echo set_value('sku', isset($product['sku']) ? $product['sku'] : ''); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Min Quantity <span class="text-danger">*</span></label>
							<input type="number" name="min_quantity" id="min_quantity" class="form-control" value="<?php echo set_value('min_quantity', isset($product['min_quantity']) ? $product['min_quantity'] : 1); ?>" min="1" required>
							<?php echo form_error('min_quantity', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Days To Exchange</label>
							<input type="number" name="days_to_exchange" id="days_to_exchange" class="form-control" value="<?php echo set_value('days_to_exchange', isset($product['days_to_exchange']) ? $product['days_to_exchange'] : ''); ?>" min="0">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Product Origin <span class="text-danger">*</span></label>
							<input type="text" name="product_origin" id="product_origin" class="form-control" value="<?php echo set_value('product_origin', isset($product['product_origin']) ? $product['product_origin'] : ''); ?>" required>
							<?php echo form_error('product_origin', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
				</div>
				
				<!-- Description Fields -->
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Product Description <span class="text-danger">*</span></label>
							<textarea name="product_description" id="product_description" class="form-control ckeditor" rows="5" required><?php echo set_value('product_description', isset($product['product_description']) ? $product['product_description'] : ''); ?></textarea>
							<?php echo form_error('product_description', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Packaging Details Card -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Packaging Size</h2>
				<div class="row gx-3">
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Length (in cm)</label>
							<input type="number" name="packaging_length" id="packaging_length" class="form-control" form="individual-product-form" value="<?php echo set_value('packaging_length', isset($product['packaging_length']) ? $product['packaging_length'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control" form="individual-product-form" value="<?php echo set_value('packaging_width', isset($product['packaging_width']) ? $product['packaging_width'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control" form="individual-product-form" value="<?php echo set_value('packaging_height', isset($product['packaging_height']) ? $product['packaging_height'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control" form="individual-product-form" value="<?php echo set_value('packaging_weight', isset($product['packaging_weight']) ? $product['packaging_weight'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Tax Details Card -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Tax</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">GST (%) <span class="text-danger">*</span></label>
							<input type="number" name="gst_percentage" id="gst_percentage" class="form-control" form="individual-product-form" value="<?php echo set_value('gst_percentage', isset($product['gst_percentage']) ? $product['gst_percentage'] : 0); ?>" step="0.01" min="0" max="100" required>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="individual-product-form" value="<?php echo set_value('hsn', isset($product['hsn']) ? $product['hsn'] : ''); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Price Details Card (shown only when no variations) -->
<div id="price_section" class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Price</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">MRP <span class="text-danger">*</span></label>
							<input type="number" name="mrp" id="mrp" class="form-control" form="individual-product-form" value="<?php echo set_value('mrp', isset($product['mrp']) ? $product['mrp'] : ''); ?>" step="0.01" min="0" required>
							<?php echo form_error('mrp', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Selling Price <span class="text-danger">*</span></label>
							<input type="number" name="selling_price" id="selling_price" class="form-control" form="individual-product-form" value="<?php echo set_value('selling_price', isset($product['selling_price']) ? $product['selling_price'] : ''); ?>" step="0.01" min="0" max="" required>
							<small class="text-muted fs-12">Must be less than MRP</small>
							<?php echo form_error('selling_price', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							<div id="selling_price_error" class="text-danger fs-13 mt-1" style="display: none;">Selling price must be less than MRP</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Variation Pricing Section -->
<div id="variation_pricing_section" style="display: none;">
	<div class="row">
		<div class="col-12">
			<div class="card mb-3">
				<div class="card-body">
					<h2 class=" border-bottom pb-3 mb-3">Variation Pricing</h2>
					<p class="text-muted">Set pricing for each variation combination. All combinations are automatically generated based on your selected variation types.</p>
					<div id="pricing_table_container"></div>
					<input type="hidden" name="variation_combinations" id="variation_combinations" value="">
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Meta Details Card -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Meta Details</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Title</label>
							<input type="text" name="meta_title" id="meta_title" class="form-control" form="individual-product-form" value="<?php echo set_value('meta_title', isset($product['meta_title']) ? $product['meta_title'] : ''); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="individual-product-form" rows="3"><?php echo set_value('meta_keywords', isset($product['meta_keywords']) ? $product['meta_keywords'] : ''); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control" form="individual-product-form" rows="3"><?php echo set_value('meta_description', isset($product['meta_description']) ? $product['meta_description'] : ''); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="border-top my-3 pt-3">
	<div class="d-flex align-items-center justify-content-end gap-2">
		<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/individual-products' : 'products/individual-products'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="individual-product-form" class="btn btn-primary">Update Product</button>
	</div>
</div>
<?php echo form_close(); ?>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addCategoryForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="category_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="category_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addCategory()">Add Category</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Subcategory Modal -->
<div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-labelledby="addSubcategoryModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addSubcategoryModalLabel">Add Subcategory</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addSubcategoryForm">
					<div class="mb-3">
						<label class="form-label">Parent Category</label>
						<select name="parent_id" id="subcategory_parent_id" class="select" required>
							<option value="">Select Parent Category</option>
							<?php if (!empty($parent_categories)): ?>
								<?php foreach ($parent_categories as $parent): ?>
									<option value="<?php echo $parent['id']; ?>"><?php echo htmlspecialchars($parent['name']); ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<small class="text-muted">Select the parent category for this subcategory.</small>
					</div>
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="subcategory_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="subcategory_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addSubcategory()">Add Subcategory</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Color Modal -->
<div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addColorModalLabel">Add Color</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addColorForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="color_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Color Code (Hex)</label>
						<input type="color" name="color_code" id="color_code" class="form-control form-control-color" value="#000000">
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="color_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addColor()">Add Color</button>
			</div>
		</div>
	</div>
</div>

<style>
.input-group {
	flex-wrap: nowrap !important;
}

.card .card-body {
    padding: 1rem !important;
}


#pricing_table_container .card {
	margin-bottom: 0;
}

#pricing_table_container .card-body {
	padding: 0.5rem;
}

#pricing_table_container .form-label.small {
	font-size: 0.75rem;
	margin-bottom: 0.25rem;
}

#pricing_table_container .form-control-sm {
	font-size: 0.875rem;
	padding: 0.25rem 0.5rem;
}

#pricing_table_container table {
	width: 100%;
	font-size: 0.875rem;
}

#pricing_table_container th,
#pricing_table_container td {
	padding: 0.4rem 0.5rem;
	text-align: left;
	border: 1px solid #dee2e6;
}

#pricing_table_container th {
	background-color: #f8f9fa;
	font-weight: 600;
	font-size: 0.8rem;
}

#pricing_table_container input[type="number"] {
	width: 100%;
	padding: 0.25rem 0.5rem;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	font-size: 0.875rem;
}

#pricing_table_container .table-responsive {
	margin-bottom: 0;
}
</style>

<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<script src="<?php echo base_url('assets/js/image-sortable.js'); ?>"></script>
<script>
// Initialize CKEditor
window.addEventListener('load', function() {
	function initCKEditor() {
		if (typeof CKEDITOR !== 'undefined') {
			if (CKEDITOR.instances['product_description']) {
				CKEDITOR.instances['product_description'].destroy();
			}
			
			var productDesc = document.getElementById('product_description');
			if (productDesc) {
				CKEDITOR.replace('product_description');
			}
		} else {
			setTimeout(initCKEditor, 100);
		}
	}
	setTimeout(initCKEditor, 300);
});

// Store variation combinations data
var variationCombinationsData = [];

// Handle variation type selection change
function handleVariationTypeChange() {
	var selectedTypes = [];
	if (typeof $ !== 'undefined' && $('#variation_type_ids').hasClass('select2-hidden-accessible')) {
		selectedTypes = $('#variation_type_ids').val() || [];
	} else {
		var select = document.getElementById('variation_type_ids');
		selectedTypes = Array.from(select.selectedOptions).map(opt => opt.value);
	}
	
	var priceSection = document.getElementById('price_section');
	var variationPricingSection = document.getElementById('variation_pricing_section');
	var variationInfoSection = document.getElementById('variation_info_section');
	var mrpField = document.getElementById('mrp');
	var sellingPriceField = document.getElementById('selling_price');
	
	// Add real-time validation for MRP and selling price
	if (mrpField && sellingPriceField) {
		function validatePrices() {
			var mrp = parseFloat(mrpField.value) || 0;
			var sellingPrice = parseFloat(sellingPriceField.value) || 0;
			var errorDiv = document.getElementById('selling_price_error');
			
			if (mrp > 0 && sellingPrice > 0 && sellingPrice >= mrp) {
				if (errorDiv) {
					errorDiv.style.display = 'block';
				}
				if (sellingPriceField) {
					sellingPriceField.setCustomValidity('Selling price must be less than MRP');
					sellingPriceField.classList.add('is-invalid');
				}
				if (mrpField) {
					mrpField.setAttribute('max', sellingPrice - 0.01);
				}
			} else {
				if (errorDiv) {
					errorDiv.style.display = 'none';
				}
				if (sellingPriceField) {
					sellingPriceField.setCustomValidity('');
					sellingPriceField.classList.remove('is-invalid');
				}
				if (mrpField && mrp > 0) {
					sellingPriceField.setAttribute('max', mrp - 0.01);
				}
			}
		}
		
		mrpField.addEventListener('input', validatePrices);
		mrpField.addEventListener('change', validatePrices);
		sellingPriceField.addEventListener('input', validatePrices);
		sellingPriceField.addEventListener('change', validatePrices);
		
		// Set initial max attribute
		if (mrpField.value) {
			var mrp = parseFloat(mrpField.value) || 0;
			if (mrp > 0) {
				sellingPriceField.setAttribute('max', mrp - 0.01);
			}
		}
	}
	
	if (selectedTypes.length === 0) {
		// No variations - show base price
		priceSection.style.display = 'block';
		variationPricingSection.style.display = 'none';
		variationInfoSection.style.display = 'none';
		if (mrpField) mrpField.setAttribute('required', 'required');
		if (sellingPriceField) sellingPriceField.setAttribute('required', 'required');
		variationCombinationsData = [];
		document.getElementById('variation_combinations').value = '';
	} else {
		// Has variations - hide base price, show variation pricing
		priceSection.style.display = 'none';
		variationPricingSection.style.display = 'block';
		variationInfoSection.style.display = 'block';
		if (mrpField) mrpField.removeAttribute('required');
		if (sellingPriceField) sellingPriceField.removeAttribute('required');
		
		// Show selected variation info
		updateVariationInfo(selectedTypes);
		
		// Generate combinations
		generateVariationCombinations(selectedTypes);
	}
}

// Update variation info display
function updateVariationInfo(selectedTypeIds) {
	if (!selectedTypeIds || selectedTypeIds.length === 0) {
		document.getElementById('selected_variations_info').innerHTML = '';
		return;
	}
	
	var infoHtml = '<ul class="mb-0">';
	var variationTypes = <?php echo json_encode($variation_types); ?>;
	
	selectedTypeIds.forEach(function(typeId) {
		var type = variationTypes.find(t => t.id == typeId);
		if (type) {
			infoHtml += '<li><strong>' + type.name + '</strong></li>';
		}
	});
	infoHtml += '</ul>';
	
	document.getElementById('selected_variations_info').innerHTML = infoHtml;
}

// Generate variation combinations via AJAX
function generateVariationCombinations(typeIds) {
	if (!typeIds || typeIds.length === 0) {
		document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">Please select at least one variation type.</p>';
		return;
	}
	
	document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">Generating combinations...</p>';
	
	var formData = new FormData();
	typeIds.forEach(function(id) {
		formData.append('type_ids[]', id);
	});
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/variations/generate_combinations" : "products/variations/generate_combinations"); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success' && data.combinations && data.combinations.length > 0) {
			variationCombinationsData = data.combinations;
			renderPricingMatrix(data.combinations);
		} else {
			document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">No combinations found. Please ensure each variation type has values.</p>';
		}
	})
	.catch(error => {
		console.error('Error:', error);
		document.getElementById('pricing_table_container').innerHTML = '<p class="text-danger">Error generating combinations.</p>';
	});
}

// Render pricing matrix
function renderPricingMatrix(combinations) {
	if (!combinations || combinations.length === 0) {
		document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">No combinations to display.</p>';
		return;
	}
	
	var html = '<div class="table-responsive"><table class="table table-bordered table-sm">';
	html += '<thead class="table-light"><tr>';
	
	// Get unique variation types from first combination
	var types = combinations[0].values.map(v => ({ id: v.type_id, name: v.type_name }));
	types.forEach(function(type) {
		html += '<th>' + type.name + '</th>';
	});
	html += '<th>MRP</th><th>Selling Price</th><th>Stock (Optional)</th><th>SKU (Optional)</th>';
	html += '</tr></thead><tbody>';
	
	combinations.forEach(function(combo, index) {
		html += '<tr data-combo-key="' + combo.key + '">';
		
		// Display values
		combo.values.forEach(function(value) {
			html += '<td><strong>' + value.value_name + '</strong></td>';
		});
		
		// Pricing inputs
		html += '<td><input type="number" class="form-control form-control-sm combo-mrp" data-index="' + index + '" step="0.01" min="0" required></td>';
		html += '<td><input type="number" class="form-control form-control-sm combo-selling-price" data-index="' + index + '" step="0.01" min="0" required></td>';
		html += '<td><input type="number" class="form-control form-control-sm combo-stock" data-index="' + index + '" min="0"></td>';
		html += '<td><input type="text" class="form-control form-control-sm combo-sku" data-index="' + index + '"></td>';
		
		html += '</tr>';
	});
	
	html += '</tbody></table></div>';
	html += '<div class="mt-3"><button type="button" id="bulk-set-prices-btn" class="btn btn-sm btn-outline-primary">Bulk Set Prices</button></div>';
	
	document.getElementById('pricing_table_container').innerHTML = html;
}

// Collect variation pricing data before form submit
function collectVariationPricing() {
	if (variationCombinationsData.length === 0) {
		document.getElementById('variation_combinations').value = '';
		return;
	}
	
	var pricingData = [];
	variationCombinationsData.forEach(function(combo, index) {
		var mrpInput = document.querySelector('.combo-mrp[data-index="' + index + '"]');
		var sellingPriceInput = document.querySelector('.combo-selling-price[data-index="' + index + '"]');
		var stockInput = document.querySelector('.combo-stock[data-index="' + index + '"]');
		var skuInput = document.querySelector('.combo-sku[data-index="' + index + '"]');
		
		pricingData.push({
			key: combo.key,
			values: combo.values,
			data: combo.data,
			mrp: mrpInput ? parseFloat(mrpInput.value) || 0 : 0,
			selling_price: sellingPriceInput ? parseFloat(sellingPriceInput.value) || 0 : 0,
			stock_quantity: stockInput ? parseInt(stockInput.value) || null : null,
			sku: skuInput ? skuInput.value.trim() || null : null
		});
	});
	
	document.getElementById('variation_combinations').value = JSON.stringify(pricingData);
}

// Bulk set prices function
function bulkSetPrices() {
	Swal.fire({
		title: 'Bulk Set Prices',
		html: '<div class="text-start">' +
			'<div class="mb-3">' +
			'<label class="form-label">MRP</label>' +
			'<input type="number" id="swal-mrp" class="swal2-input" step="0.01" min="0" placeholder="Enter MRP">' +
			'</div>' +
			'<div class="mb-3">' +
			'<label class="form-label">Selling Price</label>' +
			'<input type="number" id="swal-selling-price" class="swal2-input" step="0.01" min="0" placeholder="Enter Selling Price">' +
			'</div>' +
			'<small class="text-muted">Selling price must be less than MRP</small>' +
			'</div>',
		showCancelButton: true,
		confirmButtonText: 'Apply',
		cancelButtonText: 'Cancel',
		preConfirm: () => {
			var mrp = parseFloat(document.getElementById('swal-mrp').value) || 0;
			var sellingPrice = parseFloat(document.getElementById('swal-selling-price').value) || 0;
			
			if (!mrp || mrp <= 0) {
				Swal.showValidationMessage('Please enter a valid MRP');
				return false;
			}
			
			if (!sellingPrice || sellingPrice <= 0) {
				Swal.showValidationMessage('Please enter a valid Selling Price');
				return false;
			}
			
			if (sellingPrice >= mrp) {
				Swal.showValidationMessage('Selling price must be less than MRP');
				return false;
			}
			
			return { mrp: mrp, sellingPrice: sellingPrice };
		}
	}).then((result) => {
		if (result.isConfirmed && result.value) {
			document.querySelectorAll('.combo-mrp').forEach(function(input) {
				input.value = result.value.mrp;
			});
			document.querySelectorAll('.combo-selling-price').forEach(function(input) {
				input.value = result.value.sellingPrice;
			});
			
			Swal.fire({
				icon: 'success',
				title: 'Prices Updated',
				text: 'Prices have been applied to all combinations.',
				timer: 1500,
				showConfirmButton: false
			});
		}
	});
}

// Load size pricing table
function loadSizePricing() {
	var sizeChartId = document.getElementById('size_chart_id').value;
	if (!sizeChartId) {
		document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">Please select a size chart first.</p>';
		return;
	}
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/individual-products/get_sizes" : "products/individual-products/get_sizes"); ?>?size_chart_id=' + sizeChartId)
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success' && data.sizes.length > 0) {
				var html = '<div class="row gx-2">';
				data.sizes.forEach(function(size, index) {
					if (index % 3 === 0 && index > 0) {
						html += '</div><div class="row gx-2 mt-2">';
					}
					html += '<div class="col-md-4">';
					html += '<div class="card border">';
					html += '<div class="card-body p-2">';
					html += '<label class="form-label small mb-1"><strong>' + size.name + '</strong></label>';
					html += '<input type="hidden" name="size_prices[' + size.id + '][size_id]" value="' + size.id + '">';
					html += '<div class="mb-2">';
					html += '<label class="form-label small">MRP</label>';
					html += '<input type="number" name="size_prices[' + size.id + '][mrp]" class="form-control form-control-sm" step="0.01" min="0" required>';
					html += '</div>';
					html += '<div class="mb-0">';
					html += '<label class="form-label small">Selling Price</label>';
					html += '<input type="number" name="size_prices[' + size.id + '][selling_price]" class="form-control form-control-sm" step="0.01" min="0" required>';
					html += '</div>';
					html += '</div></div></div>';
				});
				html += '</div>';
				document.getElementById('pricing_table_container').innerHTML = html;
			} else {
				document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">No sizes found for this size chart.</p>';
			}
		})
		.catch(error => {
			console.error('Error:', error);
			document.getElementById('pricing_table_container').innerHTML = '<p class="text-danger">Error loading sizes.</p>';
		});
}

// Load color pricing table
function loadColorPricing() {
	var colorSelect = document.getElementById('color_ids');
	// Get selected values from Select2 if available, otherwise use native select
	var selectedColors = [];
	if (typeof $ !== 'undefined' && $('#color_ids').hasClass('select2-hidden-accessible')) {
		var selectedValues = $('#color_ids').val();
		if (selectedValues && selectedValues.length > 0) {
			selectedValues.forEach(function(value) {
				var option = colorSelect.querySelector('option[value="' + value + '"]');
				if (option) {
					selectedColors.push(option);
				}
			});
		}
	} else {
		selectedColors = Array.from(colorSelect.selectedOptions);
	}
	
	if (selectedColors.length === 0) {
		document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">Please select at least one color.</p>';
		return;
	}
	
	var html = '<div class="row gx-2">';
	selectedColors.forEach(function(option, index) {
		if (index % 3 === 0 && index > 0) {
			html += '</div><div class="row gx-2 mt-2">';
		}
		html += '<div class="col-md-4">';
		html += '<div class="card border">';
		html += '<div class="card-body p-2">';
		html += '<label class="form-label small mb-1"><strong>' + option.text + '</strong></label>';
		html += '<input type="hidden" name="color_prices[' + option.value + '][color_id]" value="' + option.value + '">';
		html += '<div class="mb-2">';
		html += '<label class="form-label small">MRP</label>';
		html += '<input type="number" name="color_prices[' + option.value + '][mrp]" class="form-control form-control-sm" step="0.01" min="0" required>';
		html += '</div>';
		html += '<div class="mb-0">';
		html += '<label class="form-label small">Selling Price</label>';
		html += '<input type="number" name="color_prices[' + option.value + '][selling_price]" class="form-control form-control-sm" step="0.01" min="0" required>';
		html += '</div>';
		html += '</div></div></div>';
	});
	html += '</div>';
	document.getElementById('pricing_table_container').innerHTML = html;
}

// Load size-color pricing table
function loadSizeColorPricing() {
	var sizeChartId = document.getElementById('size_chart_id').value;
	var colorSelect = document.getElementById('color_ids');
	// Get selected values from Select2 if available, otherwise use native select
	var selectedColors = [];
	if (typeof $ !== 'undefined' && $('#color_ids').hasClass('select2-hidden-accessible')) {
		var selectedValues = $('#color_ids').val();
		if (selectedValues && selectedValues.length > 0) {
			selectedValues.forEach(function(value) {
				var option = colorSelect.querySelector('option[value="' + value + '"]');
				if (option) {
					selectedColors.push(option);
				}
			});
		}
	} else {
		selectedColors = Array.from(colorSelect.selectedOptions);
	}
	
	if (!sizeChartId) {
		document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">Please select a size chart first.</p>';
		return;
	}
	
	if (selectedColors.length === 0) {
		document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">Please select at least one color.</p>';
		return;
	}
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/individual-products/get_sizes" : "products/individual-products/get_sizes"); ?>?size_chart_id=' + sizeChartId)
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success' && data.sizes.length > 0) {
				var html = '';
				var colorsPerRow = 5; // Show 5 colors per row
				var colorChunks = [];
				
				// Split colors into chunks of 5-6
				for (var i = 0; i < selectedColors.length; i += colorsPerRow) {
					colorChunks.push(selectedColors.slice(i, i + colorsPerRow));
				}
				
				// Generate table for each color chunk
				colorChunks.forEach(function(colorChunk, chunkIndex) {
					if (chunkIndex > 0) {
						html += '<div class="mt-4"><hr class="my-3"></div>';
					}
					
					html += '<div class="table-responsive">';
					html += '<table class="table table-bordered table-sm mb-0">';
					html += '<thead class="table-light">';
					html += '<tr><th rowspan="2" class="align-middle">Size</th>';
					colorChunk.forEach(function(option) {
						html += '<th colspan="2" class="text-center">' + option.text + '</th>';
					});
					html += '</tr>';
					html += '<tr>';
					colorChunk.forEach(function() {
						html += '<th class="text-center small">MRP</th><th class="text-center small">Selling Price</th>';
					});
					html += '</tr>';
					html += '</thead>';
					html += '<tbody>';
					
					data.sizes.forEach(function(size) {
						html += '<tr>';
						html += '<td><strong>' + size.name + '</strong></td>';
						colorChunk.forEach(function(option) {
							html += '<td><input type="number" name="size_prices[' + size.id + '][colors][' + option.value + '][mrp]" class="form-control form-control-sm" step="0.01" min="0" required></td>';
							html += '<td><input type="number" name="size_prices[' + size.id + '][colors][' + option.value + '][selling_price]" class="form-control form-control-sm" step="0.01" min="0" required></td>';
						});
						html += '</tr>';
					});
					
					html += '</tbody></table></div>';
				});
				
				document.getElementById('pricing_table_container').innerHTML = html;
			} else {
				document.getElementById('pricing_table_container').innerHTML = '<p class="text-muted">No sizes found for this size chart.</p>';
			}
		})
		.catch(error => {
			console.error('Error:', error);
			document.getElementById('pricing_table_container').innerHTML = '<p class="text-danger">Error loading sizes.</p>';
		});
}

// Add category function
// Load subcategories when category is selected
function loadSubcategories(categoryId) {
	var subcategorySelect = document.getElementById('subcategory_id');
	var $subcategorySelect = $('#subcategory_id');
	var addSubcategoryBtn = document.getElementById('addSubcategoryBtn');
	
	if (!categoryId) {
		// Clear subcategories and disable button
		if ($subcategorySelect.length && $subcategorySelect.hasClass('select2-hidden-accessible')) {
			$subcategorySelect.select2('destroy');
		}
		subcategorySelect.innerHTML = '<option value="">Select Subcategory (Optional)</option>';
		if ($subcategorySelect.hasClass('select')) {
			$subcategorySelect.select2();
		}
		if (addSubcategoryBtn) {
			addSubcategoryBtn.disabled = true;
		}
		return;
	}
	
	// Enable add button
	if (addSubcategoryBtn) {
		addSubcategoryBtn.disabled = false;
	}
	
	// Set parent category in subcategory modal
	var subcategoryParentSelect = document.getElementById('subcategory_parent_id');
	if (subcategoryParentSelect) {
		subcategoryParentSelect.value = categoryId;
		if ($('#subcategory_parent_id').hasClass('select2-hidden-accessible')) {
			$('#subcategory_parent_id').val(categoryId).trigger('change');
		}
	}
	
	// Fetch subcategories
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/individual-products/get_subcategories" : "products/individual-products/get_subcategories"); ?>?parent_id=' + categoryId)
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				// Destroy Select2 if initialized
				if ($subcategorySelect.length && $subcategorySelect.hasClass('select2-hidden-accessible')) {
					$subcategorySelect.select2('destroy');
				}
				
				// Clear and populate subcategories
				subcategorySelect.innerHTML = '<option value="">Select Subcategory (Optional)</option>';
				if (data.subcategories && data.subcategories.length > 0) {
					data.subcategories.forEach(function(sub) {
						var option = document.createElement('option');
						option.value = sub.id;
						option.textContent = sub.name;
						subcategorySelect.appendChild(option);
					});
				}
				
				// Reinitialize Select2
				if ($subcategorySelect.hasClass('select')) {
					$subcategorySelect.select2();
				}
			}
		})
		.catch(error => {
			console.error('Error loading subcategories:', error);
		});
}

function addCategory() {
	var name = document.getElementById('category_name').value;
	var description = document.getElementById('category_description').value;
	
	if (!name) {
		showError('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	// No parent_id - this is always a main category
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/individual-products/add_category" : "products/individual-products/add_category"); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			var select = document.getElementById('category_id');
			var $select = $('#category_id');
			
			// Check if Select2 is initialized
			if ($select.length && $select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			var option = document.createElement('option');
			option.value = data.id;
			option.textContent = data.name;
			option.selected = true;
			select.appendChild(option);
			
			// Reinitialize Select2 (global script will handle it)
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			// Update subcategory modal parent dropdown
			var subcategoryParentSelect = document.getElementById('subcategory_parent_id');
			var $subcategoryParentSelect = $('#subcategory_parent_id');
			if ($subcategoryParentSelect.length && $subcategoryParentSelect.hasClass('select2-hidden-accessible')) {
				$subcategoryParentSelect.select2('destroy');
			}
			var parentOption = document.createElement('option');
			parentOption.value = data.id;
			parentOption.textContent = data.name;
			subcategoryParentSelect.appendChild(parentOption);
			if ($subcategoryParentSelect.hasClass('select')) {
				$subcategoryParentSelect.select2();
			}
			
			// Load subcategories for the newly added category
			loadSubcategories(data.id);
			
			document.getElementById('addCategoryForm').reset();
			var nameInput = document.getElementById('category_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			showError(data.message || 'Failed to add category');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showError('An error occurred');
	});
}

function addSubcategory() {
	var name = document.getElementById('subcategory_name').value;
	var description = document.getElementById('subcategory_description').value;
	var parentId = document.getElementById('subcategory_parent_id').value;
	
	if (!name) {
		showError('Please enter a name');
		return;
	}
	
	if (!parentId) {
		showError('Please select a parent category');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	formData.append('parent_id', parentId);
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/individual-products/add_category" : "products/individual-products/add_category"); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			var select = document.getElementById('subcategory_id');
			var $select = $('#subcategory_id');
			
			// Check if Select2 is initialized
			if ($select.length && $select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			var option = document.createElement('option');
			option.value = data.id;
			option.textContent = data.name;
			option.selected = true;
			select.appendChild(option);
			
			// Reinitialize Select2 (global script will handle it)
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			document.getElementById('addSubcategoryForm').reset();
			// Reset parent to current category selection
			var categoryId = document.getElementById('category_id').value;
			if (categoryId) {
				var subcategoryParentSelect = document.getElementById('subcategory_parent_id');
				subcategoryParentSelect.value = categoryId;
				if ($('#subcategory_parent_id').hasClass('select2-hidden-accessible')) {
					$('#subcategory_parent_id').val(categoryId).trigger('change');
				}
			}
			
			var nameInput = document.getElementById('subcategory_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			showError(data.message || 'Failed to add subcategory');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showError('An error occurred');
	});
}

// Add color function
function addColor() {
	var name = document.getElementById('color_name').value;
	var colorCode = document.getElementById('color_code').value;
	var description = document.getElementById('color_description').value;
	
	if (!name) {
		showError('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('color_code', colorCode);
	formData.append('description', description);
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/individual-products/add_color" : "products/individual-products/add_color"); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			var select = document.getElementById('color_ids');
			var $select = $('#color_ids');
			
			// Check if Select2 is initialized
			if ($select.length && $select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			var option = document.createElement('option');
			option.value = data.id;
			option.textContent = data.name;
			option.selected = true;
			select.appendChild(option);
			
			// Reinitialize Select2 (global script will handle it)
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			document.getElementById('addColorForm').reset();
			document.getElementById('color_code').value = '#000000';
			
			// Reload pricing if needed
			var variationType = document.getElementById('variation_type').value;
			if (variationType === 'color') {
				loadColorPricing();
			} else if (variationType === 'size_color') {
				loadSizeColorPricing();
			}
			
			var nameInput = document.getElementById('color_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			showError(data.message || 'Failed to add color');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showError('An error occurred');
	});
}

// Update image order function
function updateImageOrder() {
	var orderInput = document.getElementById('image_order');
	if (!orderInput) return;
	
	var imageItems = document.querySelectorAll('#image-preview .image-preview-item');
	var imageIds = Array.from(imageItems).map(function(item) {
		// Get data-image-id for existing images, or a placeholder for new ones
		return item.getAttribute('data-image-id') || 'new-' + Array.from(item.parentNode.children).indexOf(item);
	}).filter(function(id) {
		// Filter out any null or empty IDs
		return id && id !== '';
	});
	
	orderInput.value = imageIds.join(',');
}

// Handle existing image deletion and main image setting (event delegation at document level)
document.addEventListener('click', function(e) {
	// Handle delete existing image buttons
	if (e.target.closest('.remove-existing-image-btn')) {
		var btn = e.target.closest('.remove-existing-image-btn');
		var imageId = btn.getAttribute('data-image-id');
		var imageItem = btn.closest('.image-preview-item');
		
		if (imageId && imageItem) {
			// Add to deleted images list
			var deletedInput = document.getElementById('deleted_image_ids');
			if (deletedInput) {
				var deletedIds = deletedInput.value ? deletedInput.value.split(',') : [];
				if (deletedIds.indexOf(imageId) === -1) {
					deletedIds.push(imageId);
					deletedInput.value = deletedIds.join(',');
				}
			}
			
			// Remove from DOM
			imageItem.remove();
			updateImageOrder();
		}
	}
	
	// Handle set main image buttons
	if (e.target.closest('.set-main-existing-btn')) {
		e.preventDefault();
		var btn = e.target.closest('.set-main-existing-btn');
		var imageId = btn.getAttribute('data-image-id');
		
		if (imageId) {
			// Update hidden input
			var mainImageInput = document.getElementById('main_image_id');
			if (mainImageInput) {
				mainImageInput.value = imageId;
			}
			
			// Update all button styles
			document.querySelectorAll('.set-main-existing-btn').forEach(function(b) {
				b.style.background = '#007bff';
				b.style.color = '#fff';
				b.textContent = 'Set Main';
			});
			
			// Update clicked button
			btn.style.background = '#28a745';
			btn.style.color = '#fff';
			btn.textContent = 'Main';
		}
	}
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
	// Wait for global Select2 initialization from script.js
	// Then attach event handlers
	function attachEventHandlers() {
		if (typeof $ !== 'undefined' && $.fn.select2) {
			// Attach change events (works with Select2)
			$('#variation_type').on('change', function() {
				handleVariationTypeChange();
			});
			
			$('#size_chart_id').on('change', function() {
				handleSizeChartChange();
			});
			
			$('#color_ids').on('change', function() {
				handleColorChange();
			});
			
			// Load subcategories when category is selected
			$('#category_id').on('change', function() {
				var categoryId = $(this).val();
				loadSubcategories(categoryId);
			});
			
			// Set parent category when subcategory modal opens
			$('#addSubcategoryModal').on('show.bs.modal', function() {
				var categoryId = $('#category_id').val();
				if (categoryId) {
					var subcategoryParentSelect = document.getElementById('subcategory_parent_id');
					if (subcategoryParentSelect) {
						subcategoryParentSelect.value = categoryId;
						if ($('#subcategory_parent_id').hasClass('select2-hidden-accessible')) {
							$('#subcategory_parent_id').val(categoryId).trigger('change');
						}
					}
				}
			});
			
			// Handle variation type selection change
			$('#variation_type_ids').on('change', function() {
				handleVariationTypeChange();
			});
			
			// Use event delegation for bulk set prices button (works even when table is dynamically recreated)
			$(document).on('click', '#bulk-set-prices-btn', function(e) {
				e.preventDefault();
				bulkSetPrices();
			});
			
			// Initialize existing variation combinations if they exist
			<?php if (isset($product_combinations) && !empty($product_combinations)): ?>
			variationCombinationsData = <?php echo json_encode($product_combinations); ?>;
			if (variationCombinationsData.length > 0) {
				// Show variation pricing section
				document.getElementById('price_section').style.display = 'none';
				document.getElementById('variation_pricing_section').style.display = 'block';
				document.getElementById('variation_info_section').style.display = 'block';
				
				// Update variation info display
				var selectedTypeIds = $('#variation_type_ids').val() || [];
				updateVariationInfo(selectedTypeIds);
				
				// Render pricing matrix with existing data
				renderPricingMatrix(variationCombinationsData);
				
				// Populate pricing inputs with existing values
				setTimeout(function() {
					variationCombinationsData.forEach(function(combo, index) {
						var mrpInput = document.querySelector('.combo-mrp[data-index="' + index + '"]');
						var sellingPriceInput = document.querySelector('.combo-selling-price[data-index="' + index + '"]');
						var stockInput = document.querySelector('.combo-stock[data-index="' + index + '"]');
						var skuInput = document.querySelector('.combo-sku[data-index="' + index + '"]');
						
						if (mrpInput) mrpInput.value = combo.mrp || '';
						if (sellingPriceInput) sellingPriceInput.value = combo.selling_price || '';
						if (stockInput && combo.stock_quantity !== null) stockInput.value = combo.stock_quantity || '';
						if (skuInput && combo.sku) skuInput.value = combo.sku || '';
					});
				}, 200);
			} else {
				// No existing combinations, trigger variation type change to set initial state
				setTimeout(function() {
					handleVariationTypeChange();
				}, 300);
			}
			<?php else: ?>
			// No existing combinations, trigger variation type change to set initial state
			setTimeout(function() {
				handleVariationTypeChange();
			}, 300);
			<?php endif; ?>
			
			// Load subcategories if category is already selected
			<?php if (isset($selected_category_id) && $selected_category_id): ?>
			setTimeout(function() {
				loadSubcategories(<?php echo $selected_category_id; ?>);
			}, 500);
			<?php endif; ?>
			
			// Collect variation pricing before form submit and validate prices
			$('form#individual-product-form').on('submit', function(e) {
				// Validate base prices if no variations
				var hasVariations = $('#variation_type_ids').val() && $('#variation_type_ids').val().length > 0;
				if (!hasVariations) {
					var mrp = parseFloat($('#mrp').val()) || 0;
					var sellingPrice = parseFloat($('#selling_price').val()) || 0;
					
					if (sellingPrice >= mrp) {
						e.preventDefault();
						Swal.fire({
							icon: 'error',
							title: 'Validation Error',
							text: 'Selling price must be less than MRP.',
							confirmButtonText: 'OK'
						});
						return false;
					}
				}
				
				// Validate variation combinations if variations exist
				if (hasVariations) {
					var hasError = false;
					document.querySelectorAll('.combo-mrp').forEach(function(mrpInput) {
						var index = mrpInput.getAttribute('data-index');
						var mrp = parseFloat(mrpInput.value) || 0;
						var sellingPriceInput = document.querySelector('.combo-selling-price[data-index="' + index + '"]');
						var sellingPrice = sellingPriceInput ? (parseFloat(sellingPriceInput.value) || 0) : 0;
						
						if (sellingPrice >= mrp) {
							hasError = true;
						}
					});
					
					if (hasError) {
						e.preventDefault();
						Swal.fire({
							icon: 'error',
							title: 'Validation Error',
							text: 'Selling price must be less than MRP for all variation combinations.',
							confirmButtonText: 'OK'
						});
						return false;
					}
				}
				
				collectVariationPricing();
				
				// Update image order before submission
				updateImageOrder();
			});
		} else {
			setTimeout(attachEventHandlers, 100);
		}
	}
	
	// Wait for global script.js to initialize Select2 first
	setTimeout(attachEventHandlers, 500);
	
	// Image preview for edit forms - image-sortable.js skips initialization when existing images are present
	// So we need custom handler for new file uploads in edit forms
	var imageInput = document.getElementById('images');
	if (imageInput) {
		// Check if this is an edit form (has existing images)
		var preview = document.getElementById('image-preview');
		var hasExistingImages = preview && preview.querySelectorAll('.existing-image').length > 0;
		
		if (hasExistingImages) {
			// Edit form - add custom handler for new uploads only
			imageInput.addEventListener('change', function(e) {
				if (!e.target.files || e.target.files.length === 0) return;
				
				var preview = document.getElementById('image-preview');
				if (!preview) return;
				
				// Remove any previously added new preview images (not existing ones)
				var newPreviews = preview.querySelectorAll('.image-preview-item:not(.existing-image)');
				newPreviews.forEach(function(item) {
					item.remove();
				});
				
				// Process new files
				for (var i = 0; i < e.target.files.length; i++) {
					var file = e.target.files[i];
					if (file.type.startsWith('image/')) {
						(function(file) {
							var reader = new FileReader();
							reader.onload = function(e) {
								var wrapper = document.createElement('div');
								wrapper.className = 'image-preview-item';
								wrapper.style.cssText = 'position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top;';
								
								var img = document.createElement('img');
								img.src = e.target.result;
								img.style.cssText = 'width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px; display: block;';
								
								wrapper.appendChild(img);
								preview.appendChild(wrapper);
							};
							reader.readAsDataURL(file);
						})(file);
					}
				}
			});
		}
		// If no existing images, image-sortable.js will handle it
	}
	
	// Make existing images draggable and sortable
	var preview = document.getElementById('image-preview');
	var draggedElement = null;
	
	if (preview) {
		preview.addEventListener('dragstart', function(e) {
			if (e.target.closest('.image-preview-item')) {
				draggedElement = e.target.closest('.image-preview-item');
				draggedElement.style.opacity = '0.5';
				e.dataTransfer.effectAllowed = 'move';
			}
		});
		
		preview.addEventListener('dragend', function(e) {
			if (e.target.closest('.image-preview-item')) {
				var item = e.target.closest('.image-preview-item');
				item.style.opacity = '1';
				draggedElement = null;
				updateImageOrder();
			}
		});
		
		preview.addEventListener('dragover', function(e) {
			e.preventDefault();
			var afterElement = getDragAfterElement(preview, e.clientY);
			var currentDraggable = document.querySelector('.dragging');
			
			if (afterElement == null) {
				if (draggedElement && draggedElement !== currentDraggable) {
					preview.appendChild(draggedElement);
				}
			} else {
				if (draggedElement && draggedElement !== afterElement) {
					preview.insertBefore(draggedElement, afterElement);
				}
			}
			
			preview.querySelectorAll('.image-preview-item').forEach(function(img) {
				img.style.border = '';
			});
			if (afterElement) {
				afterElement.style.border = '2px dashed #007bff';
			}
		});
		
		preview.addEventListener('drop', function(e) {
			e.preventDefault();
			preview.querySelectorAll('.image-preview-item').forEach(function(img) {
				img.style.border = '';
			});
			updateImageOrder();
		});
		
		function getDragAfterElement(container, y) {
			var draggableElements = Array.from(container.querySelectorAll('.image-preview-item:not(.dragging)'));
			
			return draggableElements.reduce(function(closest, child) {
				var box = child.getBoundingClientRect();
				var offset = y - box.top - box.height / 2;
				if (offset < 0 && offset > closest.offset) {
					return { offset: offset, element: child };
				} else {
					return closest;
				}
			}, { offset: Number.NEGATIVE_INFINITY }).element;
		}
	}
	
	// Make existing images draggable
	if (preview) {
		preview.querySelectorAll('.image-preview-item').forEach(function(item) {
			item.draggable = true;
		});
	}
	
	// Call updateImageOrder on initial load
	updateImageOrder();
});
</script>

