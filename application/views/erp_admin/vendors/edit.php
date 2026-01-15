<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('erp-admin/vendors'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit Vendor</a></h6>
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
				
				<?php echo form_open_multipart('erp-admin/vendors/edit/' . $vendor['id']); ?>
					<div class="row gx-3">
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Vendor Name <span class="text-danger">*</span></label>
								<input type="text" name="name" id="name" class="form-control" value="<?php echo set_value('name', $vendor['name']); ?>" required>
								<?php echo form_error('name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Domain <span class="text-danger">*</span></label>
								<input type="text" name="domain" id="domain" class="form-control" value="<?php echo set_value('domain', $vendor['domain']); ?>" required>
								<small class="text-muted fs-13">Used in URL: /<?php echo htmlspecialchars($vendor['domain']); ?></small>
								<?php echo form_error('domain', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Username <span class="text-danger">*</span></label>
								<input type="text" name="username" id="username" class="form-control" value="<?php echo set_value('username', isset($vendor['username']) ? $vendor['username'] : ''); ?>" required>
								<small class="text-muted fs-13">Vendor login username</small>
								<?php echo form_error('username', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Password</label>
								<div class="position-relative">
									<input type="password" name="password" id="password" class="form-control" value="">
									<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('password')">
										<i class="isax isax-eye" id="password-eye"></i>
									</span>
								</div>
								<small class="text-muted fs-13">Leave blank to keep current password</small>
								<?php echo form_error('password', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Status <span class="text-danger">*</span></label>
								<select name="status" id="status" class="select" required>
									<option value="">Select Status</option>
									<option value="active" <?php echo set_select('status', 'active', $vendor['status'] == 'active'); ?>>Active</option>
									<option value="suspended" <?php echo set_select('status', 'suspended', $vendor['status'] == 'suspended'); ?>>Suspended</option>
									<option value="inactive" <?php echo set_select('status', 'inactive', $vendor['status'] == 'inactive'); ?>>Inactive</option>
								</select>
								<?php echo form_error('status', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Sidebar Color</label>
								<?php 
								// Get current color value - if it's a predefined theme, convert to hex, otherwise use as-is
								$current_color = isset($vendor['sidebar_color']) ? $vendor['sidebar_color'] : '#7539ff';
								$theme_to_hex = array(
									'sidebarbg1' => '#7539ff',
									'sidebarbg2' => '#3550DC',
									'sidebarbg3' => '#22C55E',
									'sidebarbg4' => '#F59E0B',
									'sidebarbg5' => '#DC2626',
									'sidebarbg6' => '#1F2937'
								);
								if (isset($theme_to_hex[$current_color])) {
									$current_color = $theme_to_hex[$current_color];
								}
								// If it's not a hex code, default to purple
								if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $current_color)) {
									$current_color = '#7539ff';
								}
								$display_color = set_value('sidebar_color', $current_color);
								?>
								<div class="d-flex align-items-center gap-2">
									<input type="color" id="sidebar_color_picker" class="form-control form-control-color" value="<?php echo htmlspecialchars($display_color); ?>" style="width: 60px; height: 40px; cursor: pointer;">
									<input type="text" name="sidebar_color" id="sidebar_color" class="form-control" value="<?php echo htmlspecialchars($display_color); ?>" placeholder="#7539ff" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
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
									<label class="form-label">Upload Logo</label>
									<input type="file" name="logo" id="logo" class="form-control" accept="image/*">
									<small class="text-muted fs-13">Recommended: PNG, JPG, or SVG. Max size: 2MB. Logo will be displayed in sidebar and header.</small>
									<?php echo form_error('logo', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<?php if (isset($vendor['logo']) && !empty($vendor['logo']) && file_exists(FCPATH . $vendor['logo'])): ?>
									<div class="mb-3">
										<label class="form-label">Current Logo</label>
										<div class="d-flex align-items-center gap-3">
											<img src="<?php echo base_url($vendor['logo']); ?>" alt="Vendor Logo" style="max-width: 150px; max-height: 80px; object-fit: contain; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
											<div>
												<small class="text-muted d-block">Current logo</small>
												<label class="form-check mt-2">
													<input type="checkbox" name="remove_logo" value="1" class="form-check-input">
													<span class="form-check-label">Remove logo</span>
												</label>
											</div>
										</div>
									</div>
								<?php else: ?>
									<div class="mb-3">
										<label class="form-label">Current Logo</label>
										<div class="text-muted fs-13">No logo uploaded. Default logo will be used.</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<!-- Features Section -->
					<div class="border-top my-3 pt-3">
						<h6 class="mb-3">Features</h6>
						<div class="row">
							<?php if (!empty($all_features)): ?>
								<?php 
								// Get enabled feature IDs (ensure integers for consistent comparison)
								$enabled_feature_ids = array();
								if (!empty($vendor_features)) {
									foreach ($vendor_features as $vf) {
										if (isset($vf['is_enabled']) && $vf['is_enabled'] == 1) {
											$enabled_feature_ids[] = (int)$vf['id'];
										}
									}
								}
								?>
								<?php foreach ($all_features as $feature): ?>
									<?php 
									$feature_id = (int)$feature['id'];
									$has_subcategories = isset($all_subcategories[$feature_id]) && !empty($all_subcategories[$feature_id]);
									$is_enabled = in_array($feature_id, $enabled_feature_ids, true);
									$enabled_subcat_ids = isset($subcategory_map[$feature_id]) ? $subcategory_map[$feature_id] : array();
									?>
									<div class="col-md-6 col-lg-4 mb-3">
										<div class="form-check">
											<input type="checkbox" name="features[<?php echo $feature_id; ?>]" value="1" id="feature_<?php echo $feature_id; ?>" class="form-check-input feature-checkbox" data-feature-id="<?php echo $feature_id; ?>" <?php echo $is_enabled ? 'checked' : ''; ?>>
											<label class="form-check-label" for="feature_<?php echo $feature_id; ?>">
												<strong><?php echo htmlspecialchars($feature['name']); ?></strong>
												<?php if (!empty($feature['description'])): ?>
													<br><small class="text-muted"><?php echo htmlspecialchars($feature['description']); ?></small>
												<?php endif; ?>
											</label>
										</div>
										
										<?php if ($has_subcategories): ?>
											<div class="subcategories-container ms-4 mt-2" id="subcategories_<?php echo $feature_id; ?>" style="display: <?php echo $is_enabled ? 'block' : 'none'; ?>;">
												<small class="text-muted d-block mb-2">Sub-categories:</small>
												<?php foreach ($all_subcategories[$feature_id] as $subcat): ?>
													<div class="form-check form-check-sm">
														<input type="checkbox" name="subcategories[<?php echo $feature_id; ?>][]" value="<?php echo $subcat['id']; ?>" id="subcat_<?php echo $feature_id; ?>_<?php echo $subcat['id']; ?>" class="form-check-input" <?php echo in_array((int)$subcat['id'], $enabled_subcat_ids, true) ? 'checked' : ''; ?>>
														<label class="form-check-label" for="subcat_<?php echo $feature_id; ?>_<?php echo $subcat['id']; ?>">
															<small><?php echo htmlspecialchars($subcat['name']); ?></small>
														</label>
													</div>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="col-12">
									<p class="text-muted">No features available.</p>
								</div>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="border-top my-3 pt-3">
						<div class="d-flex align-items-center justify-content-end gap-2">
							<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-outline">Cancel</a>
							<button type="submit" class="btn btn-primary">Save Changes</button>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Handle main feature checkbox changes to show/hide subcategories
	var featureCheckboxes = document.querySelectorAll('.feature-checkbox');
	featureCheckboxes.forEach(function(checkbox) {
		checkbox.addEventListener('change', function() {
			var featureId = this.getAttribute('data-feature-id');
			var subcategoriesContainer = document.getElementById('subcategories_' + featureId);
			
			if (subcategoriesContainer) {
				if (this.checked) {
					subcategoriesContainer.style.display = 'block';
				} else {
					subcategoriesContainer.style.display = 'none';
					// Uncheck all subcategories when main feature is unchecked
					var subcatCheckboxes = subcategoriesContainer.querySelectorAll('input[type="checkbox"]');
					subcatCheckboxes.forEach(function(subcat) {
						subcat.checked = false;
					});
				}
			}
		});
	});
	
	// Color picker sync
	var colorPicker = document.getElementById('sidebar_color_picker');
	var colorInput = document.getElementById('sidebar_color');
	
	if (colorPicker && colorInput) {
		colorPicker.addEventListener('input', function() {
			colorInput.value = this.value.toUpperCase();
		});
		
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
