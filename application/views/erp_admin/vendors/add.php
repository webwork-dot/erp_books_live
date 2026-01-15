<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('erp-admin/vendors'); ?>"><i class="isax isax-arrow-left me-2"></i>Add New Vendor</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h6 class="mb-3">Basic Details</h6>
				
				<?php if (validation_errors()): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Please fix the following errors:</strong>
						<ul class="mb-0 mt-2">
							<?php echo validation_errors('<li>', '</li>'); ?>
						</ul>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>
				
				<?php echo form_open_multipart('erp-admin/vendors/add'); ?>
					<div class="row gx-3">
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Vendor Name <span class="text-danger">*</span></label>
								<input type="text" name="name" id="name" class="form-control" value="<?php echo set_value('name'); ?>" required>
								<?php echo form_error('name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Domain <span class="text-danger">*</span></label>
								<input type="text" name="domain" id="domain" class="form-control" value="<?php echo set_value('domain'); ?>" placeholder="vendor-name" required>
								<small class="text-muted fs-13">Used in URL: /vendor-name</small>
								<?php echo form_error('domain', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Username <span class="text-danger">*</span></label>
								<input type="text" name="username" id="username" class="form-control" value="<?php echo set_value('username'); ?>" required>
								<small class="text-muted fs-13">Vendor login username</small>
								<?php echo form_error('username', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Password <span class="text-danger">*</span></label>
								<div class="position-relative">
									<input type="password" name="password" id="password" class="form-control" value="<?php echo set_value('password'); ?>" required>
									<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('password')">
										<i class="isax isax-eye" id="password-eye"></i>
									</span>
								</div>
								<small class="text-muted fs-13">Vendor login password</small>
								<?php echo form_error('password', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Status <span class="text-danger">*</span></label>
								<select name="status" id="status" class="select" required>
									<option value="">Select Status</option>
									<option value="active" <?php echo set_select('status', 'active', TRUE); ?>>Active</option>
									<option value="suspended" <?php echo set_select('status', 'suspended'); ?>>Suspended</option>
									<option value="inactive" <?php echo set_select('status', 'inactive'); ?>>Inactive</option>
								</select>
								<?php echo form_error('status', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Sidebar Color</label>
								<div class="d-flex align-items-center gap-2">
									<input type="color" id="sidebar_color_picker" class="form-control form-control-color" value="<?php echo set_value('sidebar_color', '#7539ff'); ?>" style="width: 60px; height: 40px; cursor: pointer;">
									<input type="text" name="sidebar_color" id="sidebar_color" class="form-control" value="<?php echo set_value('sidebar_color', '#7539ff'); ?>" placeholder="#7539ff" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
								</div>
								<small class="text-muted fs-13">Enter a hex color code (e.g., #7539ff) for the sidebar color theme</small>
								<?php echo form_error('sidebar_color', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
					</div>
					
					<!-- Logo Upload Section -->
					<div class="border-top my-3 pt-3">
						<h6 class="mb-3">Vendor Logo</h6>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="mb-3">
									<label class="form-label">Upload Logo (Optional)</label>
									<input type="file" name="logo" id="logo" class="form-control" accept="image/*">
									<small class="text-muted fs-13">Recommended: PNG, JPG, or SVG. Max size: 2MB. Logo will be displayed in sidebar and header.</small>
									<?php echo form_error('logo', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="border-top my-3 pt-3">
						<div class="d-flex align-items-center justify-content-end gap-2">
							<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-outline">Cancel</a>
							<button type="submit" class="btn btn-primary">Create Vendor</button>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var colorPicker = document.getElementById('sidebar_color_picker');
	var colorInput = document.getElementById('sidebar_color');
	
	// Sync color picker to text input
	if (colorPicker && colorInput) {
		colorPicker.addEventListener('input', function() {
			colorInput.value = this.value.toUpperCase();
		});
		
		// Sync text input to color picker
		colorInput.addEventListener('input', function() {
			var hex = this.value.trim();
			// Ensure it starts with #
			if (hex && !hex.startsWith('#')) {
				hex = '#' + hex;
			}
			// Validate hex format
			if (/^#[0-9A-Fa-f]{6}$/.test(hex)) {
				colorPicker.value = hex.toUpperCase();
				this.value = hex.toUpperCase();
			}
		});
		
		// On form submit, validate hex format
		var form = colorInput.closest('form');
		if (form) {
			form.addEventListener('submit', function(e) {
				var hexValue = colorInput.value.trim();
				// Ensure it starts with #
				if (hexValue && !hexValue.startsWith('#')) {
					hexValue = '#' + hexValue;
				}
				// Validate hex format before submit
				if (hexValue && /^#[0-9A-Fa-f]{6}$/.test(hexValue)) {
					colorInput.value = hexValue.toUpperCase();
					colorPicker.value = hexValue.toUpperCase();
				} else if (hexValue) {
					// If invalid, show error
					alert('Please enter a valid hex color code (e.g., #7539ff)');
					e.preventDefault();
					return false;
				}
			});
		}
	}
});
</script>
