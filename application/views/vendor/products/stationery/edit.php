<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery' : 'products/stationery'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit Stationery</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(base_url('products/stationery/edit/' . $stationery['id']), array('id' => 'stationery-form')); ?>
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Images</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Images (Size: 440px * 530px)</label>
							<input type="file" name="images[]" id="images" class="form-control" form="stationery-form" accept="image/*" multiple>
							<small class="text-muted fs-13">You can select multiple images. Recommended size: 440px × 530px. Drag images to reorder. Click "Set as Main" to choose the main image. Leave empty to keep existing images.</small>
							<div id="image-preview" class="mt-3 image-sortable-container">
								<?php if (isset($stationery_images) && !empty($stationery_images)): ?>
									<?php 
									$main_image_id = null;
									foreach ($stationery_images as $img): 
										if (isset($img['is_main']) && $img['is_main'] == 1) {
											$main_image_id = $img['id'];
										}
									endforeach;
									?>
									<?php foreach ($stationery_images as $img): ?>
										<div class="image-preview-item existing-image" data-image-id="<?php echo $img['id']; ?>" style="position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top;">
											<img src="<?php echo base_url('assets/uploads/' . ltrim($img['image_path'], '/')); ?>" alt="Stationery Image" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px 4px 0 0; display: block;">
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
				<h2 class=" border-bottom pb-3 mb-3">Details</h2>
				
				<?php if (validation_errors()): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Please fix the following errors:</strong>
						<ul class="mb-0 mt-2">
							<?php echo validation_errors('<li>', '</li>'); ?>
						</ul>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>
				
					<!-- Basic Information -->
					<div class="row gx-3">
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Category <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="category_id" id="category_id" class="select" required>
										<option value="">Select Category</option>
										<?php if (!empty($categories)): ?>
											<?php foreach ($categories as $category): ?>
												<option value="<?php echo $category['id']; ?>" <?php echo ($stationery['category_id'] == $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('category_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Product Name/Display Name <span class="text-danger">*</span></label>
								<input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo set_value('product_name', $stationery['product_name']); ?>" required>
								<?php echo form_error('product_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Brand <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="brand_id" id="brand_id" class="select" required>
										<option value="">Select Brand</option>
										<?php if (!empty($brands)): ?>
											<?php foreach ($brands as $brand): ?>
												<option value="<?php echo $brand['id']; ?>" <?php echo (isset($stationery['brand_id']) && $stationery['brand_id'] == $brand['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($brand['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('brand_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Colour <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="colour_id" id="colour_id" class="select" required>
										<option value="">Select Colour</option>
										<?php if (!empty($colours)): ?>
											<?php foreach ($colours as $colour): ?>
												<option value="<?php echo $colour['id']; ?>" <?php echo (isset($stationery['colour_id']) && $stationery['colour_id'] == $colour['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($colour['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addColourModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('colour_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">ISBN/Bar Code No./SKU</label>
								<input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo set_value('isbn', $stationery['isbn']); ?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">SKU /Product Code</label>
								<input type="text" name="sku" id="sku" class="form-control" value="<?php echo set_value('sku', $stationery['sku']); ?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Product Code (For control of school set)</label>
								<input type="text" name="product_code" id="product_code" class="form-control" value="<?php echo set_value('product_code', $stationery['product_code']); ?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Min Quantity <span class="text-danger">*</span></label>
								<input type="number" name="min_quantity" id="min_quantity" class="form-control" value="<?php echo set_value('min_quantity', $stationery['min_quantity']); ?>" min="1" required>
								<?php echo form_error('min_quantity', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Days To Exchange</label>
								<input type="number" name="days_to_exchange" id="days_to_exchange" class="form-control" value="<?php echo set_value('days_to_exchange', $stationery['days_to_exchange']); ?>" min="0">
							</div>
						</div>
					</div>
					
					<!-- Description Fields -->
					<div class="row gx-3">
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Pointers / Highlights</label>
								<textarea name="pointers" id="pointers" class="form-control ckeditor" rows="5"><?php echo set_value('pointers', $stationery['pointers']); ?></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Product Description <span class="text-danger">*</span></label>
								<textarea name="product_description" id="product_description" class="form-control ckeditor" rows="5" required><?php echo set_value('product_description', $stationery['product_description']); ?></textarea>
								<?php echo form_error('product_description', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
					</div>
				
			</div>
		</div>
	</div>
</div>

<!-- Packaging Details Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Packaging Size</h2>
				<div class="row gx-3">
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Length (in cm)</label>
							<input type="number" name="packaging_length" id="packaging_length" class="form-control" form="stationery-form" value="<?php echo set_value('packaging_length', $stationery['packaging_length']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control" form="stationery-form" value="<?php echo set_value('packaging_width', $stationery['packaging_width']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control" form="stationery-form" value="<?php echo set_value('packaging_height', $stationery['packaging_height']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control" form="stationery-form" value="<?php echo set_value('packaging_weight', $stationery['packaging_weight']); ?>" step="0.01" min="0">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Tax Details Card (Outside Main Card) -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Tax</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">GST (%) <span class="text-danger">*</span></label>
							<input type="number" name="gst_percentage" id="gst_percentage" class="form-control" form="stationery-form" value="<?php echo set_value('gst_percentage', $stationery['gst_percentage']); ?>" step="0.01" min="0" max="100" required>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Select GST</label>
							<select name="gst_type" id="gst_type" class="select" form="stationery-form">
								<option value="">Select GST Type</option>
								<option value="igst" <?php echo ($stationery['gst_type'] == 'igst') ? 'selected' : ''; ?>>IGST</option>
								<option value="cgst_sgst" <?php echo ($stationery['gst_type'] == 'cgst_sgst') ? 'selected' : ''; ?>>CGST + SGST</option>
							</select>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="stationery-form" value="<?php echo set_value('hsn', $stationery['hsn']); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Price Details Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Price</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">MRP <span class="text-danger">*</span></label>
							<input type="number" name="mrp" id="mrp" class="form-control" form="stationery-form" value="<?php echo set_value('mrp', $stationery['mrp']); ?>" step="0.01" min="0" required>
							<?php echo form_error('mrp', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Selling Price <span class="text-danger">*</span></label>
							<input type="number" name="selling_price" id="selling_price" class="form-control" form="stationery-form" value="<?php echo set_value('selling_price', $stationery['selling_price']); ?>" step="0.01" min="0" required>
							<?php echo form_error('selling_price', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Meta Details and Status Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Meta Details</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Title</label>
							<input type="text" name="meta_title" id="meta_title" class="form-control" form="stationery-form" value="<?php echo set_value('meta_title', $stationery['meta_title']); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="stationery-form" rows="3"><?php echo set_value('meta_keywords', $stationery['meta_keywords']); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control" form="stationery-form" rows="3"><?php echo set_value('meta_description', $stationery['meta_description']); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="border-top my-3 pt-3">
	<div class="d-flex align-items-center justify-content-end gap-2">
		<a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery' : 'products/stationery'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="stationery-form" class="btn btn-primary">Update Stationery</button>
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

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addBrandForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="brand_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="brand_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addBrand()">Add Brand</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Colour Modal -->
<div class="modal fade" id="addColourModal" tabindex="-1" aria-labelledby="addColourModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addColourModalLabel">Add Colour</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addColourForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="colour_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="colour_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addColour()">Add Colour</button>
			</div>
		</div>
	</div>
</div>

<style>
.input-group {
	flex-wrap: nowrap !important;
}

.card .card-body {
    padding: 0 !important;
}

</style>

<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
<script src="<?php echo base_url('assets/js/image-sortable.js'); ?>"></script>
<script>
// Initialize CKEditor after page loads
window.addEventListener('load', function() {
	function initCKEditor() {
		if (typeof CKEDITOR !== 'undefined') {
			// Destroy existing instances if any
			if (CKEDITOR.instances['product_description']) {
				CKEDITOR.instances['product_description'].destroy();
			}
			if (CKEDITOR.instances['pointers']) {
				CKEDITOR.instances['pointers'].destroy();
			}
			
			// Initialize CKEditor instances
			var productDesc = document.getElementById('product_description');
			var pointers = document.getElementById('pointers');
			
			if (productDesc) {
				CKEDITOR.replace('product_description');
			}
			if (pointers) {
				CKEDITOR.replace('pointers');
			}
		} else {
			// If CKEDITOR is not loaded yet, wait and try again
			setTimeout(initCKEditor, 100);
		}
	}
	
	// Wait a bit for everything to be ready
	setTimeout(initCKEditor, 300);
});

document.addEventListener('DOMContentLoaded', function() {
	// Image preview
	document.getElementById('images').addEventListener('change', function(e) {
		var preview = document.getElementById('image-preview');
		preview.innerHTML = '';
		
		for (var i = 0; i < e.target.files.length; i++) {
			var file = e.target.files[i];
			if (file.type.startsWith('image/')) {
				var reader = new FileReader();
				reader.onload = function(e) {
					var img = document.createElement('img');
					img.src = e.target.result;
					img.style.width = '100px';
					img.style.height = '120px';
					img.style.objectFit = 'cover';
					img.style.borderRadius = '4px';
					img.style.margin = '5px';
					preview.appendChild(img);
				};
				reader.readAsDataURL(file);
			}
		}
	});
});


function addCategory() {
	var name = document.getElementById('category_name').value;
	var description = document.getElementById('category_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery/add_category' : 'products/stationery/add_category'); ?>', {
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
			
			// Reinitialize Select2 if needed
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addCategoryForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('category_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add category');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

function addBrand() {
	var name = document.getElementById('brand_name').value;
	var description = document.getElementById('brand_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery/add_brand' : 'products/stationery/add_brand'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add to brand select
			var select = document.getElementById('brand_id');
			var $select = $('#brand_id');
			
			// Check if Select2 is initialized
			if ($select.length && $select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			var option = document.createElement('option');
			option.value = data.id;
			option.textContent = data.name;
			option.selected = true;
			select.appendChild(option);
			
			// Reinitialize Select2 if needed
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addBrandForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('brand_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add brand');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

function addColour() {
	var name = document.getElementById('colour_name').value;
	var description = document.getElementById('colour_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery/add_colour' : 'products/stationery/add_colour'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add to colour select
			var select = document.getElementById('colour_id');
			var $select = $('#colour_id');
			
			// Check if Select2 is initialized
			if ($select.length && $select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			var option = document.createElement('option');
			option.value = data.id;
			option.textContent = data.name;
			option.selected = true;
			select.appendChild(option);
			
			// Reinitialize Select2 if needed
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addColourForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('colour_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add colour');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

// Handle existing images - Set as Main
(function() {
	function initEditFormHandlers() {
		const deletedImageIds = [];
		const deletedInput = document.getElementById('deleted_image_ids');
		const mainImageInput = document.getElementById('main_image_id');
		
		// Set main image for existing images
		document.querySelectorAll('.set-main-existing-btn').forEach(function(btn) {
			btn.addEventListener('click', function(e) {
			e.preventDefault();
			const imageId = this.getAttribute('data-image-id');
			
			// Update main image input
			if (mainImageInput) {
				mainImageInput.value = imageId;
			}
			
			// Update all buttons
			document.querySelectorAll('.set-main-existing-btn').forEach(function(b) {
				if (b.getAttribute('data-image-id') == imageId) {
					b.textContent = 'Main';
					b.style.background = '#28a745';
					b.style.color = '#fff';
				} else {
					b.textContent = 'Set Main';
					b.style.background = '#007bff';
					b.style.color = '#fff';
				}
			});
		});
	});
	
	// Remove existing image
	document.querySelectorAll('.remove-existing-image-btn').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			const imageId = this.getAttribute('data-image-id');
			
			if (confirm('Are you sure you want to remove this image?')) {
				// Add to deleted list
				if (deletedImageIds.indexOf(imageId) === -1) {
					deletedImageIds.push(imageId);
				}
				
				// Update hidden input
				if (deletedInput) {
					deletedInput.value = deletedImageIds.join(',');
				}
				
				// Hide the image
				const imageItem = this.closest('.image-preview-item');
				if (imageItem) {
					imageItem.style.display = 'none';
				}
			}
		});
	});
	
	// Make existing images draggable
	const existingImages = document.querySelectorAll('.existing-image');
	existingImages.forEach(function(img) {
		img.draggable = true;
		img.addEventListener('dragstart', function(e) {
			this.style.opacity = '0.5';
			e.dataTransfer.effectAllowed = 'move';
		});
		img.addEventListener('dragend', function() {
			this.style.opacity = '1';
		});
	});
	}
	
	// Try to initialize immediately if DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initEditFormHandlers);
	} else {
		// DOM already loaded, but wait a bit for images to render
		setTimeout(initEditFormHandlers, 200);
	}
})();

function deleteImage(imageId) {
	if (!confirm('Are you sure you want to delete this image?')) {
		return;
	}
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/stationery/delete_image/' : 'products/stationery/delete_image/'); ?>' + imageId, {
		method: 'POST',
		headers: {
			'X-Requested-With': 'XMLHttpRequest'
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			location.reload();
		} else {
			alert(data.message || 'Failed to delete image');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}
</script>

