<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0 fs-14">Add New Vendor</h6>
	</div>
	<div>
		<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-outline-secondary btn-sm">
			<i class="isax isax-arrow-left me-1"></i>Back
		</a>
	</div>
</div>
<!-- End Header -->

<div class="row">
	<div class="col-12">
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Basic Details</h6>
			</div>
			<div class="card-body p-2">
				
				<?php if (validation_errors()): ?>
					<div class="alert alert-danger alert-dismissible fade show py-2 mb-2" role="alert">
						<strong class="fs-13">Please fix the following errors:</strong>
						<ul class="mb-0 mt-1 fs-13">
							<?php echo validation_errors('<li>', '</li>'); ?>
						</ul>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>
				
				<?php echo form_open_multipart('erp-admin/vendors/add'); ?>
					<div class="row g-2">
						<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
							<label class="form-label fs-13 mb-1">Vendor Name <span class="text-danger">*</span></label>
							<input type="text" name="name" id="name" class="form-control form-control-sm" value="<?php echo set_value('name'); ?>" required>
							<?php echo form_error('name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
							<label class="form-label fs-13 mb-1">Domain <span class="text-danger">*</span></label>
							<input type="text" name="domain" id="domain" class="form-control form-control-sm" value="<?php echo set_value('domain'); ?>" placeholder="varitty.in" required>
							<small class="text-muted fs-12">e.g., varitty.in</small>
							<?php echo form_error('domain', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
							<label class="form-label fs-13 mb-1">Username <span class="text-danger">*</span></label>
							<input type="text" name="username" id="username" class="form-control form-control-sm" value="<?php echo set_value('username'); ?>" required>
							<small class="text-muted fs-12">Login username</small>
							<?php echo form_error('username', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
							<label class="form-label fs-13 mb-1">Password <span class="text-danger">*</span></label>
							<div class="position-relative">
								<input type="password" name="password" id="password" class="form-control form-control-sm" value="<?php echo set_value('password'); ?>" required>
								<span class="position-absolute end-0 top-50 translate-middle-y pe-2" style="cursor: pointer;" onclick="togglePassword('password')">
									<i class="isax isax-eye fs-14" id="password-eye"></i>
								</span>
							</div>
							<small class="text-muted fs-12">Login password</small>
							<?php echo form_error('password', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
							<label class="form-label fs-13 mb-1">Sidebar Color</label>
							<div class="d-flex align-items-center gap-1">
								<input type="color" id="sidebar_color_picker" class="form-control form-control-color" value="<?php echo set_value('sidebar_color', '#7539ff'); ?>" style="width: 50px; height: 32px; cursor: pointer;">
								<input type="text" name="sidebar_color" id="sidebar_color" class="form-control form-control-sm" value="<?php echo set_value('sidebar_color', '#7539ff'); ?>" placeholder="#7539ff" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
							</div>
							<small class="text-muted fs-12">Hex color code</small>
							<?php echo form_error('sidebar_color', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					
			</div>
		</div>
		
		<!-- Logo Upload Section -->
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Vendor Logo</h6>
			</div>
			<div class="card-body p-2">
				<div class="row g-2">
					<div class="col-xl-6 col-lg-8 col-md-12 mb-2">
						<label class="form-label fs-13 mb-1">Upload Logo (Optional)</label>
						<input type="file" name="logo" id="logo" class="form-control form-control-sm" accept="image/*">
						<small class="text-muted fs-12">PNG, JPG, or SVG. Max size: 2MB</small>
						<?php echo form_error('logo', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Submit Buttons -->
		<div class="card mb-2">
			<div class="card-body p-2">
				<div class="d-flex align-items-center justify-content-end gap-2">
					<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
					<button type="submit" class="btn btn-primary btn-sm">
						<i class="isax isax-tick-circle me-1"></i>Create Vendor
					</button>
				</div>
			</div>
		</div>
			<?php echo form_close(); ?>
	</div>
</div>

<script>
// Toggle password visibility
function togglePassword(inputId) {
	var input = document.getElementById(inputId);
	var eyeIcon = document.getElementById(inputId + '-eye');
	
	if (input.type === 'password') {
		input.type = 'text';
		eyeIcon.classList.remove('isax-eye');
		eyeIcon.classList.add('isax-eye-slash');
	} else {
		input.type = 'password';
		eyeIcon.classList.remove('isax-eye-slash');
		eyeIcon.classList.add('isax-eye');
	}
}

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
