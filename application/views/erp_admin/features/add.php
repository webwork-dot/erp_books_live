<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('erp-admin/features'); ?>"><i class="isax isax-arrow-left me-2"></i>Add New Feature</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h6 class="mb-3">Basic Details</h6>
				<?php 
				$CI =& get_instance();
				// Use relative path so form_open can properly detect it as internal URL and add CSRF token
				echo form_open('erp-admin/features/add');
				// Explicitly add CSRF token to ensure it's included
				if ($CI->config->item('csrf_protection') === TRUE) {
					echo form_hidden($CI->security->get_csrf_token_name(), $CI->security->get_csrf_hash());
				}
				?>
					<div class="row gx-3">
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Feature Name <span class="text-danger">*</span></label>
								<input type="text" name="name" id="name" class="form-control" value="<?php echo set_value('name'); ?>" required>
								<?php echo form_error('name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Slug <span class="text-danger">*</span></label>
								<input type="text" name="slug" id="slug" class="form-control" value="<?php echo set_value('slug'); ?>" required>
								<small class="text-muted fs-13">Auto-generated from feature name. You can edit it if needed.</small>
								<div id="slug-status" class="mt-1"></div>
								<?php echo form_error('slug', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Description</label>
								<textarea name="description" id="description" class="form-control" rows="4"><?php echo set_value('description'); ?></textarea>
								<?php echo form_error('description', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Parent Category</label>
								<select name="parent_id" id="parent_id" class="select">
									<option value="">Main Category (No Parent)</option>
									<?php if (!empty($main_categories)): ?>
										<?php foreach ($main_categories as $category): ?>
											<option value="<?php echo $category['id']; ?>" <?php echo set_select('parent_id', $category['id']); ?>>
												<?php echo htmlspecialchars($category['name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<small class="text-muted">Leave empty for main category, or select a parent to create a sub-category</small>
								<?php echo form_error('parent_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Is Active <span class="text-danger">*</span></label>
								<select name="is_active" id="is_active" class="select" required>
									<option value="">Select Status</option>
									<option value="1" <?php echo set_select('is_active', '1', TRUE); ?>>Active</option>
									<option value="0" <?php echo set_select('is_active', '0'); ?>>Inactive</option>
								</select>
								<?php echo form_error('is_active', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Is School Feature</label>
								<select name="is_school" id="is_school" class="select">
									<option value="0" <?php echo set_select('is_school', '0', TRUE); ?>>No</option>
									<option value="1" <?php echo set_select('is_school', '1'); ?>>Yes</option>
								</select>
								<?php echo form_error('is_school', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="mb-3">
								<label class="form-label">Variations</label>
								<select name="has_variations" id="has_variations" class="select">
									<option value="0" <?php echo set_select('has_variations', '0', TRUE); ?>>No</option>
									<option value="1" <?php echo set_select('has_variations', '1'); ?>>Yes</option>
								</select>
								<?php echo form_error('has_variations', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="mb-3">
								<label class="form-label">Size</label>
								<select name="has_size" id="has_size" class="select">
									<option value="0" <?php echo set_select('has_size', '0', TRUE); ?>>No</option>
									<option value="1" <?php echo set_select('has_size', '1'); ?>>Yes</option>
								</select>
								<?php echo form_error('has_size', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="mb-3">
								<label class="form-label">Colour</label>
								<select name="has_colour" id="has_colour" class="select">
									<option value="0" <?php echo set_select('has_colour', '0', TRUE); ?>>No</option>
									<option value="1" <?php echo set_select('has_colour', '1'); ?>>Yes</option>
								</select>
								<?php echo form_error('has_colour', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
					</div>
					<div class="border-top my-3 pt-3">
						<div class="d-flex align-items-center justify-content-end gap-2">
							<a href="<?php echo base_url('erp-admin/features'); ?>" class="btn btn-outline">Cancel</a>
							<button type="submit" class="btn btn-primary">Create Feature</button>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var nameInput = document.getElementById('name');
	var slugInput = document.getElementById('slug');
	var slugStatus = document.getElementById('slug-status');
	var slugManuallyEdited = false;
	var checkSlugTimeout = null;
	
	// Function to generate slug from text
	function generateSlug(text) {
		return text
			.toLowerCase()
			.trim()
			.replace(/[^\w\s-]/g, '') // Remove special characters
			.replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
			.replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
	}
	
	// Auto-generate slug from name
	nameInput.addEventListener('input', function() {
		if (!slugManuallyEdited) {
			var slug = generateSlug(this.value);
			slugInput.value = slug;
			if (slug.length > 0) {
				checkSlugUniqueness(slug);
			}
		}
	});
	
	// Track manual slug edits
	slugInput.addEventListener('input', function() {
		slugManuallyEdited = true;
		if (this.value.length > 0) {
			checkSlugUniqueness(this.value);
		} else {
			slugStatus.innerHTML = '';
		}
	});
	
	// Check slug uniqueness via AJAX
	function checkSlugUniqueness(slug) {
		// Clear previous timeout
		if (checkSlugTimeout) {
			clearTimeout(checkSlugTimeout);
		}
		
		// Debounce: wait 500ms after user stops typing
		checkSlugTimeout = setTimeout(function() {
			if (slug.length === 0) {
				slugStatus.innerHTML = '';
				return;
			}
			
			slugStatus.innerHTML = '<small class="text-muted"><i class="isax isax-loading-1 isax-spin"></i> Checking...</small>';
			
			var xhr = new XMLHttpRequest();
			xhr.open('GET', '<?php echo base_url('erp-admin/features/check_slug/'); ?>' + encodeURIComponent(slug), true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState === 4) {
					if (xhr.status === 200) {
						try {
							var response = JSON.parse(xhr.responseText);
							if (response.available) {
								slugStatus.innerHTML = '<small class="text-success"><i class="isax isax-tick-circle"></i> Available</small>';
								slugInput.classList.remove('is-invalid');
								slugInput.classList.add('is-valid');
							} else {
								// Generate unique slug
								var uniqueSlug = response.suggested_slug || slug + '-' + Math.floor(Math.random() * 1000);
								slugStatus.innerHTML = '<small class="text-warning"><i class="isax isax-warning-2"></i> Not available. Suggested: <strong>' + uniqueSlug + '</strong></small>';
								slugInput.classList.remove('is-valid');
								slugInput.classList.add('is-invalid');
								// Auto-update slug if it was auto-generated
								if (!slugManuallyEdited) {
									slugInput.value = uniqueSlug;
									checkSlugUniqueness(uniqueSlug);
								}
							}
						} catch(e) {
							slugStatus.innerHTML = '';
						}
					} else {
						slugStatus.innerHTML = '';
					}
				}
			};
			xhr.send();
		}, 500);
	}
	
	// Reset manual edit flag when form is reset
	document.querySelector('form').addEventListener('reset', function() {
		slugManuallyEdited = false;
		slugStatus.innerHTML = '';
		slugInput.classList.remove('is-valid', 'is-invalid');
	});
});
</script>
