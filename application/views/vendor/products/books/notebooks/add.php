<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('products/notebooks'); ?>"><i class="isax isax-arrow-left me-2"></i>Add New Notebook</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(base_url('products/notebooks/add'), array('id' => 'notebook-form')); ?>
<!-- Images Card (Outside Main Card) -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3  mb-3">Images</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Images (Size: 440px * 530px) <span class="text-danger">*</span></label>
							<input type="file" name="images[]" id="images" class="form-control" form="notebook-form" accept="image/*" multiple required>
							<small class="text-muted fs-13">You can select multiple images. Recommended size: 440px × 530px. Drag images to reorder. Click "Set as Main" to choose the main image.</small>
							<div id="image-preview" class="mt-3 image-sortable-container"></div>
							<input type="hidden" name="image_order" id="image_order" value="">
							<input type="hidden" name="main_image_index" id="main_image_index" value="0">
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
										<?php if (!empty($types)): ?>
											<?php foreach ($types as $type): ?>
												<option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
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
												<option value="<?php echo $brand['id']; ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
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
								<input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo set_value('product_name'); ?>" required>
								<?php echo form_error('product_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">ISBN/Bar Code No./SKU</label>
								<input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo set_value('isbn'); ?>">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Size</label>
								<input type="text" name="size" id="size" class="form-control" value="<?php echo set_value('size'); ?>" placeholder="e.g., A4, A5">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Binding Type</label>
								<select name="binding_type" id="binding_type" class="select">
									<option value="">Select Binding Type</option>
									<option value="center_binding" <?php echo set_select('binding_type', 'center_binding'); ?>>Center Binding</option>
									<option value="perfect_binding" <?php echo set_select('binding_type', 'perfect_binding'); ?>>Perfect Binding</option>
									<option value="spiral_binding" <?php echo set_select('binding_type', 'spiral_binding'); ?>>Spiral Binding</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">No. Of Pages</label>
								<input type="number" name="no_of_pages" id="no_of_pages" class="form-control" value="<?php echo set_value('no_of_pages'); ?>" min="1">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Min Quantity <span class="text-danger">*</span></label>
								<input type="number" name="min_quantity" id="min_quantity" class="form-control" value="<?php echo set_value('min_quantity', 1); ?>" min="1" required>
								<?php echo form_error('min_quantity', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Days To Exchange</label>
								<input type="number" name="days_to_exchange" id="days_to_exchange" class="form-control" value="<?php echo set_value('days_to_exchange'); ?>" min="0">
							</div>
						</div>
					</div>
					
					<!-- Description Fields -->
					<div class="row gx-3">
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Pointers / Highlights</label>
								<textarea name="pointers" id="pointers" class="form-control ckeditor" rows="5"><?php echo set_value('pointers'); ?></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Product Description <span class="text-danger">*</span></label>
								<textarea name="product_description" id="product_description" class="form-control ckeditor" rows="5" required><?php echo set_value('product_description'); ?></textarea>
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
							<input type="number" name="packaging_length" id="packaging_length" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_length'); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_width'); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_height'); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control" form="notebook-form" value="<?php echo set_value('packaging_weight'); ?>" step="0.01" min="0">
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
							<input type="number" name="gst_percentage" id="gst_percentage" class="form-control" form="notebook-form" value="<?php echo set_value('gst_percentage', 0); ?>" step="0.01" min="0" max="100" required>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="notebook-form" value="<?php echo set_value('hsn'); ?>">
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
							<input type="text" name="product_code" id="product_code" class="form-control" form="notebook-form" value="<?php echo set_value('product_code'); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">SKU /Product Code</label>
							<input type="text" name="sku" id="sku" class="form-control" form="notebook-form" value="<?php echo set_value('sku'); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">MRP <span class="text-danger">*</span></label>
							<input type="number" name="mrp" id="mrp" class="form-control" form="notebook-form" value="<?php echo set_value('mrp'); ?>" step="0.01" min="0" required>
							<small class="text-danger" id="mrp_error" style="display:none;">MRP must be higher than Selling Price</small>
							<?php echo form_error('mrp', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Selling Price <span class="text-danger">*</span></label>
							<input type="number" name="selling_price" id="selling_price" class="form-control" form="notebook-form" value="<?php echo set_value('selling_price'); ?>" step="0.01" min="0" required>
							<small class="text-danger" id="selling_price_error" style="display:none;">Selling Price must be lower than MRP</small>
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
							<input type="text" name="meta_title" id="meta_title" class="form-control" form="notebook-form" value="<?php echo set_value('meta_title'); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="notebook-form" rows="3"><?php echo set_value('meta_keywords'); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control" form="notebook-form" rows="3"><?php echo set_value('meta_description'); ?></textarea>
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
								<input class="form-check-input" type="checkbox" name="is_individual" id="is_individual" value="1" form="notebook-form">
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
								<input class="form-check-input" type="checkbox" name="is_set" id="is_set" value="1" form="notebook-form">
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
		<a href="<?php echo base_url('products/notebooks'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="notebook-form" class="btn btn-primary" onclick="return validatePrice();">Create Notebook</button>
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
</style>

<script src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>
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
	
	// Image preview is now handled by image-sortable.js
});

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
	
	fetch('<?php echo base_url('products/notebooks/add_type'); ?>', {
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
	
	fetch('<?php echo base_url('products/notebooks/add_brand'); ?>', {
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

// Price validation function
function validatePrice() {
	var mrp = parseFloat(document.getElementById('mrp').value) || 0;
	var sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
	var mrpError = document.getElementById('mrp_error');
	var sellingPriceError = document.getElementById('selling_price_error');
	var mrpInput = document.getElementById('mrp');
	var sellingPriceInput = document.getElementById('selling_price');
	
	// Hide errors initially
	if (mrpError) mrpError.style.display = 'none';
	if (sellingPriceError) sellingPriceError.style.display = 'none';
	if (mrpInput) mrpInput.classList.remove('is-invalid');
	if (sellingPriceInput) sellingPriceInput.classList.remove('is-invalid');
	
	// Validate only if both values are entered
	if (mrp > 0 && sellingPrice > 0) {
		if (mrp <= sellingPrice) {
			if (mrpError) mrpError.style.display = 'block';
			if (sellingPriceError) sellingPriceError.style.display = 'block';
			if (mrpInput) mrpInput.classList.add('is-invalid');
			if (sellingPriceInput) sellingPriceInput.classList.add('is-invalid');
			alert('MRP must be higher than Selling Price');
			return false;
		}
	}
	
	return true;
}

// Add event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
	var mrpInput = document.getElementById('mrp');
	var sellingPriceInput = document.getElementById('selling_price');
	
	if (mrpInput && sellingPriceInput) {
		mrpInput.addEventListener('input', validatePrice);
		mrpInput.addEventListener('blur', validatePrice);
		sellingPriceInput.addEventListener('input', validatePrice);
		sellingPriceInput.addEventListener('blur', validatePrice);
	}
});
</script>
