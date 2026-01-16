<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('products/notebooks' : 'products/notebooks'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit Notebook</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(base_url('products/notebooks/edit/' . $notebook['id']), array('id' => 'notebook-form')); ?>
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
							<input type="file" name="images[]" id="images" class="form-control" form="notebook-form" accept="image/*" multiple>
							<small class="text-muted fs-13">You can select multiple images. Recommended size: 440px × 530px. Drag images to reorder. Click "Set as Main" to choose the main image. Leave empty to keep existing images.</small>
							<div id="image-preview" class="mt-3 image-sortable-container" style="min-height: 50px;">
								<?php if (isset($notebook_images) && !empty($notebook_images)): ?>
									<?php 
									$main_image_id = null;
									foreach ($notebook_images as $img): 
										if (isset($img['is_main']) && $img['is_main'] == 1) {
											$main_image_id = $img['id'];
										}
									endforeach;
									?>
									<?php foreach ($notebook_images as $img): 
										$image_url = base_url('assets/uploads/' . ltrim($img['image_path'], '/'));
									?>
										<div class="image-preview-item existing-image" data-image-id="<?php echo $img['id']; ?>" style="position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top; width: 120px;">
											<img src="<?php echo $image_url; ?>" alt="Notebook Image" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px 4px 0 0; display: block;" onerror="console.error('Image failed to load:', this.src); this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>';">
											<div class="image-buttons" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.75); display: flex; gap: 2px; padding: 3px; border-radius: 0 0 4px 4px; z-index: 10;">
												<button type="button" class="btn btn-sm set-main-existing-btn" data-image-id="<?php echo $img['id']; ?>" style="font-size: 10px; padding: 3px 6px; flex: 1; line-height: 1.2; border: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; <?php echo ($img['id'] == $main_image_id) ? 'background: #28a745; color: #fff;' : 'background: #007bff; color: #fff;'; ?>">
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
				<h2 class=" border-bottom pb-3  mb-3">Details</h2>
				
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
								<label class="form-label">Type <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="types[]" id="types" class="select select2-multiple" multiple required>
										<?php 
										$notebook_types = isset($notebook_types) ? $notebook_types : array();
										$selected_type_ids = array();
										foreach ($notebook_types as $notebook_type) {
											$selected_type_ids[] = $notebook_type['type_id'];
										}
										if (!empty($types)): ?>
											<?php foreach ($types as $type): ?>
												<option value="<?php echo $type['id']; ?>" <?php echo (in_array($type['id'], $selected_type_ids)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('types', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
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
												<option value="<?php echo $brand['id']; ?>" <?php echo (isset($notebook) && $notebook['brand_id'] == $brand['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($brand['name']); ?></option>
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
								<label class="form-label">Product Name/Display Name <span class="text-danger">*</span></label>
								<input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo set_value('product_name', isset($notebook) ? $notebook['product_name'] : ''); ?>" required>
								<?php echo form_error('product_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">ISBN/Bar Code No./SKU</label>
								<input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo set_value('isbn', isset($notebook) ? $notebook['isbn'] : ''); ?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Size</label>
								<input type="text" name="size" id="size" class="form-control" value="<?php echo set_value('size', isset($notebook) ? $notebook['size'] : ''); ?>" placeholder="e.g., A4, A5">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Binding Type</label>
								<select name="binding_type" id="binding_type" class="select">
									<option value="">Select Binding Type</option>
									<option value="center_binding" <?php echo (isset($notebook) && $notebook['binding_type'] == 'center_binding') ? 'selected' : set_select('binding_type', 'center_binding'); ?>>Center Binding</option>
									<option value="perfect_binding" <?php echo (isset($notebook) && $notebook['binding_type'] == 'perfect_binding') ? 'selected' : set_select('binding_type', 'perfect_binding'); ?>>Perfect Binding</option>
									<option value="spiral_binding" <?php echo (isset($notebook) && $notebook['binding_type'] == 'spiral_binding') ? 'selected' : set_select('binding_type', 'spiral_binding'); ?>>Spiral Binding</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">No. Of Pages</label>
								<input type="number" name="no_of_pages" id="no_of_pages" class="form-control" value="<?php echo set_value('no_of_pages', isset($notebook) ? $notebook['no_of_pages'] : ''); ?>" min="1">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Min Quantity <span class="text-danger">*</span></label>
								<input type="number" name="min_quantity" id="min_quantity" class="form-control" value="<?php echo set_value('min_quantity', isset($notebook) ? $notebook['min_quantity'] : 1); ?>" min="1" required>
								<?php echo form_error('min_quantity', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Days To Exchange</label>
								<input type="number" name="days_to_exchange" id="days_to_exchange" class="form-control" value="<?php echo set_value('days_to_exchange', isset($notebook) ? $notebook['days_to_exchange'] : ''); ?>" min="0">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Status</label>
								<select name="status" id="status" class="select">
									<option value="active" <?php echo (isset($notebook) && $notebook['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
									<option value="inactive" <?php echo (isset($notebook) && $notebook['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
								</select>
							</div>
						</div>
					</div>
					
					<!-- Description Fields -->
					<div class="row gx-3">
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Pointers / Highlights</label>
								<textarea name="pointers" id="pointers" class="form-control ckeditor" rows="5"><?php echo set_value('pointers', isset($notebook) ? $notebook['pointers'] : ''); ?></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Product Description <span class="text-danger">*</span></label>
								<textarea name="product_description" id="product_description" class="form-control ckeditor" rows="5" required><?php echo set_value('product_description', isset($notebook) ? $notebook['product_description'] : ''); ?></textarea>
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
							<input type="number" name="packaging_length" id="packaging_length" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_length', isset($notebook) ? $notebook['packaging_length'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_width', isset($notebook) ? $notebook['packaging_width'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_height', isset($notebook) ? $notebook['packaging_height'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_weight', isset($notebook) ? $notebook['packaging_weight'] : ''); ?>" step="0.01" min="0">
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
							<input type="number" name="gst_percentage" id="gst_percentage" class="form-control" form="notebook-form" value="<?php echo set_value('gst_percentage', isset($notebook) ? $notebook['gst_percentage'] : 0); ?>" step="0.01" min="0" max="100" required>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="notebook-form" value="<?php echo set_value('hsn', isset($notebook) ? $notebook['hsn'] : ''); ?>">
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
							<label class="form-label">Product Code (For control of school set)</label>
							<input type="text" name="product_code" id="product_code" class="form-control" form="notebook-form" value="<?php echo set_value('product_code', isset($notebook) ? $notebook['product_code'] : ''); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">SKU /Product Code</label>
							<input type="text" name="sku" id="sku" class="form-control" form="notebook-form" value="<?php echo set_value('sku', isset($notebook) ? $notebook['sku'] : ''); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">MRP <span class="text-danger">*</span></label>
							<input type="number" name="mrp" id="mrp" class="form-control" form="notebook-form" value="<?php echo set_value('mrp', isset($notebook) ? $notebook['mrp'] : ''); ?>" step="0.01" min="0" required>
							<?php echo form_error('mrp', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Selling Price <span class="text-danger">*</span></label>
							<input type="number" name="selling_price" id="selling_price" class="form-control" form="notebook-form" value="<?php echo set_value('selling_price', isset($notebook) ? $notebook['selling_price'] : ''); ?>" step="0.01" min="0" required>
							<?php echo form_error('selling_price', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Meta Details Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Meta Details</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Title</label>
							<input type="text" name="meta_title" id="meta_title" class="form-control" form="notebook-form" value="<?php echo set_value('meta_title', isset($notebook) ? $notebook['meta_title'] : ''); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="notebook-form" rows="3"><?php echo set_value('meta_keywords', isset($notebook) ? $notebook['meta_keywords'] : ''); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control" form="notebook-form" rows="3"><?php echo set_value('meta_description', isset($notebook) ? $notebook['meta_description'] : ''); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Product Type Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Product Type</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" name="is_individual" id="is_individual" value="1" form="notebook-form" <?php echo (isset($notebook['is_individual']) && $notebook['is_individual'] == 1) ? 'checked' : ''; ?>>
								<label class="form-check-label" for="is_individual">
									Is Individual Product
								</label>
							</div>
							<small class="text-muted">Check this if this is an individual product that can be sold separately</small>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" name="is_set" id="is_set" value="1" form="notebook-form" <?php echo (isset($notebook['is_set']) && $notebook['is_set'] == 1) ? 'checked' : ''; ?>>
								<label class="form-check-label" for="is_set">
									Is Set Product
								</label>
							</div>
							<small class="text-muted">Check this if this is a notebook set (collection of notebooks sold together)</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="border-top my-3 pt-3">
	<div class="d-flex align-items-center justify-content-end gap-2">
		<a href="<?php echo base_url('products/notebooks' : 'products/notebooks'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="notebook-form" class="btn btn-primary">Update Notebook</button>
	</div>
</div>
<?php echo form_close(); ?>

<!-- Add Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addTypeModalLabel">Add Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addTypeForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="type_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="type_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addType()">Add Type</button>
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

<style>
.input-group {
	flex-wrap: nowrap !important;
}

.card .card-body {
    padding: 1rem !important;
}

/* Ensure image preview container displays items correctly */
#image-preview {
	display: block;
	width: 100%;
}

#image-preview .image-preview-item {
	display: inline-block;
	vertical-align: top;
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

// Wait for jQuery and Select2 to be loaded
(function() {
	function waitForJQuery(callback) {
		if (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.select2 !== 'undefined') {
			callback();
		} else {
			setTimeout(function() { waitForJQuery(callback); }, 100);
		}
	}
	
	waitForJQuery(function() {
		var $ = window.jQuery;
		
$(document).ready(function() {
	// Function to initialize Select2 for multiple selects
	function initMultipleSelect2() {
		if (typeof $ !== 'undefined' && $.fn.select2) {
			// Check if Select2 is already initialized and destroy if needed
			$('#types').each(function() {
				if ($(this).hasClass('select2-hidden-accessible')) {
					$(this).select2('destroy');
				}
			});
			
			// Initialize with multiple selection support
			$('#types').select2({
				width: '100%',
				placeholder: 'Select options...',
				allowClear: true,
				multiple: true,
				minimumResultsForSearch: 0,
				theme: 'bootstrap-5'
			});
		} else {
			// If Select2 is not ready, try again after a short delay
			setTimeout(initMultipleSelect2, 100);
		}
	}
	
	// Wait a bit for script.js to finish initializing, then reinitialize our selects
	setTimeout(initMultipleSelect2, 800);
	
	// Image preview for newly selected images
	var imageInput = document.getElementById('images');
	if (imageInput) {
		imageInput.addEventListener('change', function(e) {
			var preview = document.getElementById('image-preview');
			// Don't clear existing previews, just add new ones
			
			for (var i = 0; i < e.target.files.length; i++) {
				var file = e.target.files[i];
				if (file.type.startsWith('image/')) {
					var reader = new FileReader();
					reader.onload = (function(file) {
						return function(e) {
							var imgContainer = document.createElement('div');
							imgContainer.className = 'position-relative';
							imgContainer.style.cssText = 'width: 100px; height: 120px; margin: 5px; display: inline-block;';
							
							var img = document.createElement('img');
							img.src = e.target.result;
							img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 4px;';
							img.alt = 'New Image Preview';
							
							var removeBtn = document.createElement('button');
							removeBtn.type = 'button';
							removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
							removeBtn.style.cssText = 'margin: 2px; padding: 2px 6px;';
							removeBtn.innerHTML = '<i class="isax isax-close-circle"></i>';
							removeBtn.onclick = function() {
								imgContainer.remove();
								// Remove file from input
								var dt = new DataTransfer();
								var files = imageInput.files;
								for (var j = 0; j < files.length; j++) {
									if (files[j] !== file) {
										dt.items.add(files[j]);
									}
								}
								imageInput.files = dt.files;
							};
							
							imgContainer.appendChild(img);
							imgContainer.appendChild(removeBtn);
							preview.appendChild(imgContainer);
						};
					})(file);
					reader.readAsDataURL(file);
				}
			}
		});
	}
});
	});
})();

function addType() {
	var name = document.getElementById('type_name').value;
	var description = document.getElementById('type_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url('products/notebooks/add_type' : 'products/notebooks/add_type'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add to Select2 dropdown
			var $select = $('#types');
			var option = new Option(data.name, data.id, true, true);
			$select.append(option).trigger('change');
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addTypeForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('type_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add type');
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
	
	fetch('<?php echo base_url('products/notebooks/add_brand' : 'products/notebooks/add_brand'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
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

// Handle existing images - Set as Main and Drag & Drop
(function() {
	function initEditFormHandlers() {
		
	const deletedImageIds = [];
	const deletedInput = document.getElementById('deleted_image_ids');
	const mainImageInput = document.getElementById('main_image_id');
		const orderInput = document.getElementById('image_order');
		const container = document.getElementById('image-preview');
		
		if (!container) return;
		
		let draggedElement = null;
		let isProcessingDrop = false;
	
	// Set main image for existing images
		const setMainButtons = container.querySelectorAll('.set-main-existing-btn');
		
		setMainButtons.forEach(function(btn, index) {
			// Remove any existing listeners to prevent duplicates
			const newBtn = btn.cloneNode(true);
			btn.parentNode.replaceChild(newBtn, btn);
			
			newBtn.addEventListener('click', function(e) {
			e.preventDefault();
				e.stopPropagation();
			const imageId = this.getAttribute('data-image-id');
			
			// Update main image input
			if (mainImageInput) {
				mainImageInput.value = imageId;
			}
			
			// Update all buttons
			document.querySelectorAll('.set-main-existing-btn').forEach(function(b) {
					const btnImageId = b.getAttribute('data-image-id');
					if (btnImageId == imageId) {
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
			// Remove any existing listeners to prevent duplicates
			const newBtn = btn.cloneNode(true);
			btn.parentNode.replaceChild(newBtn, btn);
			
			newBtn.addEventListener('click', function(e) {
			e.preventDefault();
				e.stopPropagation();
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
					
					// Update order
					updateImageOrder();
			}
		});
	});
	
		// Make existing images draggable with full drag & drop support
		const existingImages = container.querySelectorAll('.existing-image');
		
		existingImages.forEach(function(img, index) {
		img.draggable = true;
			
		img.addEventListener('dragstart', function(e) {
				draggedElement = this;
			this.style.opacity = '0.5';
			e.dataTransfer.effectAllowed = 'move';
				e.dataTransfer.setData('text/html', this.outerHTML);
			});
			
			img.addEventListener('dragover', function(e) {
				if (e.preventDefault) {
					e.preventDefault();
				}
				e.dataTransfer.dropEffect = 'move';
				if (this !== draggedElement && this.classList.contains('existing-image')) {
					this.style.border = '2px dashed #007bff';
				}
				return false;
			});
			
			img.addEventListener('dragleave', function(e) {
				this.style.border = '';
			});
			
			img.addEventListener('drop', function(e) {
				// Prevent default to allow drop
				if (e.preventDefault) {
					e.preventDefault();
				}
				if (e.stopPropagation) {
					e.stopPropagation();
				}
				
				// Prevent duplicate drops
				if (isProcessingDrop) {
					return false;
				}
				
				if (draggedElement !== this && this.classList.contains('existing-image')) {
					isProcessingDrop = true;
					
					const targetParent = this.parentNode;
					
					// Get current indices before move
					const allItemsBefore = Array.from(container.querySelectorAll('.existing-image'));
					const draggedIndex = allItemsBefore.indexOf(draggedElement);
					const targetIndex = allItemsBefore.indexOf(this);
					
					if (draggedIndex !== -1 && targetIndex !== -1 && draggedIndex !== targetIndex) {
						// insertBefore automatically moves the element if it's already in the DOM
						if (draggedIndex < targetIndex) {
							// Moving forward: insert after target (before target's next sibling)
							const targetNext = this.nextSibling;
							if (targetNext) {
								targetParent.insertBefore(draggedElement, targetNext);
							} else {
								// Target is last, append to end
								targetParent.appendChild(draggedElement);
							}
						} else {
							// Moving backward: insert before target
							targetParent.insertBefore(draggedElement, this);
						}
						
						// Force a reflow to ensure visual update
						void container.offsetHeight;
						
						// Update order after DOM change
						updateImageOrder();
					}
					
					isProcessingDrop = false;
				}
				
				this.style.border = '';
				return false;
			});
			
		img.addEventListener('dragend', function() {
			this.style.opacity = '1';
				const allItems = container.querySelectorAll('.existing-image');
				allItems.forEach(function(item) {
					item.style.border = '';
		});
				draggedElement = null;
	});
		});
		
		// Update image order in hidden input
		function updateImageOrder() {
			if (orderInput && container) {
				const allItems = Array.from(container.querySelectorAll('.existing-image'));
				const order = allItems.map(function(item) {
					return item.getAttribute('data-image-id');
				}).join(',');
				orderInput.value = order;
			}
		}
		
		// Initialize order on load
		updateImageOrder();
	}
	
	// Try to initialize immediately if DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			setTimeout(initEditFormHandlers, 300);
		});
	} else {
		// DOM already loaded, but wait a bit for images to render
		setTimeout(initEditFormHandlers, 300);
	}
	
	// Also try to initialize after a longer delay in case images load slowly
	setTimeout(function() {
		initEditFormHandlers();
	}, 1000);
})();


function deleteImage(imageId) {
	if (!confirm('Are you sure you want to delete this image?')) {
		return;
	}
	
	fetch('<?php echo base_url('products/notebooks/delete_image/' : 'products/notebooks/delete_image/'); ?>' + imageId, {
		method: 'POST',
		headers: {
			'X-Requested-With': 'XMLHttpRequest'
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Remove the image from preview without reloading
			var imageElement = document.querySelector('[data-image-id="' + imageId + '"]');
			if (imageElement) {
				imageElement.remove();
			}
			// If no images left, show message
			var preview = document.getElementById('image-preview');
			if (preview && preview.children.length === 0) {
				preview.innerHTML = '<p class="text-muted">No images</p>';
			}
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

