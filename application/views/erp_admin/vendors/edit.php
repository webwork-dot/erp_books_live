<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('erp-admin/vendors'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit Vendor</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Vendor Edit Tabs -->
<style>
	.vendor-edit-tabs-wrapper {
		background: #ffffff;
		border-bottom: 1px solid #dee2e6;
		margin-bottom: 1.5rem;
		padding: 0;
	}
	.vendor-edit-tabs {
		border-bottom: 1px solid #dee2e6;
		margin-bottom: 0;
		padding-left: 0;
		padding-right: 0;
	}
	.vendor-edit-tabs .nav-link {
		color: #6c757d;
		font-weight: 500;
		font-size: 0.875rem;
		padding: 0.75rem 1.25rem;
		border: 1px solid transparent;
		border-top-left-radius: 0.375rem;
		border-top-right-radius: 0.375rem;
		border-bottom: none;
		margin-bottom: -1px;
		transition: all 0.2s ease;
		background: transparent;
		position: relative;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		white-space: nowrap;
	}
	.vendor-edit-tabs .nav-link:hover {
		color: #495057;
		background: #f8f9fa;
		border-color: #dee2e6 #dee2e6 transparent;
	}
	.vendor-edit-tabs .nav-link.active {
		color: rgb(255, 255, 255);
		background: #3550dc;
		border-color: #dee2e6 #dee2e6 #ffffff;
		font-weight: 600;
		z-index: 1;
	}
	.vendor-edit-tabs .nav-link.active::after {
		content: '';
		position: absolute;
		bottom: -1px;
		left: 0;
		right: 0;
		height: 1px;
		background: #ffffff;
	}
	.vendor-edit-tabs .nav-link i {
		font-size: 1em;
	}
	.vendor-edit-tabs .nav-link.active i {
		color: #ffffff;
	}
	.vendor-edit-tabs .nav-item {
		margin-bottom: 0;
	}
	.sidebar-preview {
		position: relative;
		transition: background-color 0.3s ease;
	}
	.sidebar-preview::before {
		content: '';
		position: absolute;
		top: 8px;
		left: 8px;
		width: 16px;
		height: 3px;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 2px;
	}
	.sidebar-preview::after {
		content: '';
		position: absolute;
		bottom: 8px;
		left: 8px;
		width: 20px;
		height: 3px;
		background: rgba(255, 255, 255, 0.6);
		border-radius: 2px;
	}
</style>

<div class="vendor-edit-tabs-wrapper">
	<ul class="nav nav-tabs vendor-edit-tabs" id="vendorEditTabs" role="tablist">
		<li class="nav-item" role="presentation">
			<a class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" href="#basic-info" role="tab" aria-controls="basic-info" aria-selected="true">
				<i class="isax isax-information"></i>
				<span>Basic Information</span>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" id="branding-tab" data-bs-toggle="tab" href="#branding" role="tab" aria-controls="branding" aria-selected="false">
				<i class="isax isax-gallery"></i>
				<span>Logo & Branding</span>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" id="features-tab" data-bs-toggle="tab" href="#features" role="tab" aria-controls="features" aria-selected="false">
				<i class="isax isax-setting-2"></i>
				<span>Features & Permissions</span>
			</a>
		</li>
		<li class="nav-item" role="presentation">
			<a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="false">
				<i class="isax isax-card"></i>
				<span>Payment Gateway</span>
			</a>
		</li>
	</ul>
</div>

<div class="tab-content" id="vendorEditTabContent">
	<!-- Basic Information Tab -->
	<div class="tab-pane fade show active" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
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
								<input type="text" name="domain" id="domain" class="form-control" value="<?php echo set_value('domain', $vendor['domain']); ?>" placeholder="varitty.in" required>
								<small class="text-muted fs-13">Domain name (e.g., varitty.in or vendor-name)</small>
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
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Branding Tab -->
	<div class="tab-pane fade" id="branding" role="tabpanel" aria-labelledby="branding-tab">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<h6 class="mb-4">Logo & Branding</h6>

						<!-- Logo Section -->
						<div class="row gx-4 mb-4">
							<div class="col-lg-8 col-md-7">
								<div class="card border-0 bg-light">
									<div class="card-body p-4">
										<h6 class="mb-3">
											<i class="isax isax-gallery me-2 text-primary"></i>
											Vendor Logo
										</h6>
										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label class="form-label fw-medium">Upload New Logo</label>
													<input type="file" name="logo" id="logo" class="form-control" accept="image/*">
													<small class="text-muted fs-13">Recommended: PNG, JPG, or SVG. Max size: 2MB</small>
													<?php echo form_error('logo', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
												</div>
											</div>
											<div class="col-md-6">
												<?php if (isset($vendor['logo']) && !empty($vendor['logo']) && file_exists(FCPATH . $vendor['logo'])): ?>
													<div class="mb-3">
														<label class="form-label fw-medium">Current Logo</label>
														<div class="d-flex align-items-start gap-3">
															<div class="border rounded p-2 bg-white">
																<img src="<?php echo base_url($vendor['logo']); ?>" alt="Vendor Logo" style="max-width: 120px; max-height: 60px; object-fit: contain;">
															</div>
															<div class="flex-grow-1">
																<small class="text-muted d-block">Current logo</small>
																<label class="form-check mt-2">
																	<input type="checkbox" name="remove_logo" value="1" class="form-check-input">
																	<span class="form-check-label fs-13">Remove current logo</span>
																</label>
															</div>
														</div>
													</div>
												<?php else: ?>
													<div class="mb-3">
														<label class="form-label fw-medium">Current Logo</label>
														<div class="text-center py-4 border rounded bg-white">
															<i class="isax isax-gallery text-muted" style="font-size: 32px;"></i>
															<p class="text-muted fs-13 mt-2 mb-0">No logo uploaded<br><small>Default logo will be used</small></p>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Sidebar Color Section -->
							<div class="col-lg-4 col-md-5">
								<div class="card border-0 bg-light">
									<div class="card-body p-4">
										<h6 class="mb-3">
											<i class="isax isax-color-swatch me-2 text-primary"></i>
											Sidebar Theme
										</h6>
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
										<div class="mb-3">
											<label class="form-label fw-medium">Sidebar Color</label>
											<div class="d-flex align-items-center gap-3 mb-2">
												<div class="d-flex align-items-center gap-2 flex-grow-1">
													<input type="color" id="sidebar_color_picker" class="form-control form-control-color border-0 p-1" value="<?php echo htmlspecialchars($display_color); ?>" style="width: 50px; height: 40px; cursor: pointer; border-radius: 6px;">
													<input type="text" name="sidebar_color" id="sidebar_color" class="form-control" value="<?php echo htmlspecialchars($display_color); ?>" placeholder="#7539ff" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" style="font-family: monospace;">
												</div>
											</div>
											<small class="text-muted fs-13">Choose a hex color for the sidebar theme</small>
											<?php echo form_error('sidebar_color', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
										</div>

										<!-- Color Preview -->
										<div class="mt-3">
											<label class="form-label fw-medium mb-2">Preview</label>
											<div class="d-flex align-items-center gap-2">
												<div class="sidebar-preview border rounded" style="width: 40px; height: 60px; background: <?php echo htmlspecialchars($display_color); ?>; border-radius: 4px 0 0 4px;"></div>
												<div class="flex-grow-1">
													<small class="text-muted">Sidebar appearance</small>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Features Tab -->
	<div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<h6 class="mb-3">Features & Permissions</h6>

						<!-- Features Section -->
						<div class="row g-2">
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
									<div class="col-lg-4 col-md-6 mb-2">
										<div class="border rounded p-2 bg-light">
											<div class="form-check mb-1">
												<input type="checkbox" name="features[<?php echo $feature_id; ?>]" value="1" id="feature_<?php echo $feature_id; ?>" class="form-check-input feature-checkbox" data-feature-id="<?php echo $feature_id; ?>" <?php echo $is_enabled ? 'checked' : ''; ?>>
												<label class="form-check-label fw-medium small" for="feature_<?php echo $feature_id; ?>">
													<?php echo htmlspecialchars($feature['name']); ?>
												</label>
											</div>

											<?php if ($has_subcategories): ?>
												<div class="subcategories-container ms-3 mt-2" id="subcategories_<?php echo $feature_id; ?>" style="display: <?php echo $is_enabled ? 'block' : 'none'; ?>;">
													<small class="text-muted d-block mb-1 fw-medium" style="font-size: 11px;">Sub-features:</small>
													<?php foreach ($all_subcategories[$feature_id] as $subcat): ?>
														<div class="form-check form-check-sm mb-1">
															<input type="checkbox" name="subcategories[<?php echo $feature_id; ?>][]" value="<?php echo $subcat['id']; ?>" id="subcat_<?php echo $feature_id; ?>_<?php echo $subcat['id']; ?>" class="form-check-input" <?php echo in_array((int)$subcat['id'], $enabled_subcat_ids, true) ? 'checked' : ''; ?>>
															<label class="form-check-label" for="subcat_<?php echo $feature_id; ?>_<?php echo $subcat['id']; ?>" style="font-size: 11px;">
																<?php echo htmlspecialchars($subcat['name']); ?>
															</label>
														</div>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="col-12">
									<div class="text-center py-4 border rounded bg-light">
										<i class="isax isax-setting-2 text-muted" style="font-size: 32px;"></i>
										<p class="text-muted mt-2 mb-0 small">No features available</p>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Payment Gateway Tab -->
	<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<h6 class="mb-3">Payment Gateway</h6>

						<?php
						$current_gateway = isset($vendor['payment_gateway']) ? $vendor['payment_gateway'] : '';
						$razorpay_key_id = isset($vendor['razorpay_key_id']) ? $vendor['razorpay_key_id'] : '';
						$razorpay_key_secret = isset($vendor['razorpay_key_secret']) ? $vendor['razorpay_key_secret'] : '';
						$ccavenue_merchant_id = isset($vendor['ccavenue_merchant_id']) ? $vendor['ccavenue_merchant_id'] : '';
						$ccavenue_access_code = isset($vendor['ccavenue_access_code']) ? $vendor['ccavenue_access_code'] : '';
						$ccavenue_working_key = isset($vendor['ccavenue_working_key']) ? $vendor['ccavenue_working_key'] : '';
						$zepto_mail_api_key = isset($vendor['zepto_mail_api_key']) ? $vendor['zepto_mail_api_key'] : '';
						$zepto_mail_from_email = isset($vendor['zepto_mail_from_email']) ? $vendor['zepto_mail_from_email'] : '';
						$zepto_mail_from_name = isset($vendor['zepto_mail_from_name']) ? $vendor['zepto_mail_from_name'] : '';
						$firebase_api_key = isset($vendor['firebase_api_key']) ? $vendor['firebase_api_key'] : '';
						$firebase_auth_domain = isset($vendor['firebase_auth_domain']) ? $vendor['firebase_auth_domain'] : '';
						$firebase_project_id = isset($vendor['firebase_project_id']) ? $vendor['firebase_project_id'] : '';
						$firebase_storage_bucket = isset($vendor['firebase_storage_bucket']) ? $vendor['firebase_storage_bucket'] : '';
						$firebase_messaging_sender_id = isset($vendor['firebase_messaging_sender_id']) ? $vendor['firebase_messaging_sender_id'] : '';
						$firebase_app_id = isset($vendor['firebase_app_id']) ? $vendor['firebase_app_id'] : '';
						?>

						<!-- Payment Gateway Selection -->
						<div class="mb-3">
							<label class="form-label">Payment Gateway Provider</label>
							<div class="d-flex gap-4">
								<div class="form-check">
									<input class="form-check-input payment-gateway-radio" type="radio" name="payment_gateway" id="gateway_razorpay" value="razorpay" <?php echo ($current_gateway == 'razorpay') ? 'checked' : ''; ?>>
									<label class="form-check-label" for="gateway_razorpay">Razorpay</label>
								</div>
								<div class="form-check">
									<input class="form-check-input payment-gateway-radio" type="radio" name="payment_gateway" id="gateway_ccavenue" value="ccavenue" <?php echo ($current_gateway == 'ccavenue') ? 'checked' : ''; ?>>
									<label class="form-check-label" for="gateway_ccavenue">CCAvenue</label>
								</div>
							</div>
							<?php echo form_error('payment_gateway', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>

						<!-- Razorpay Configuration -->
						<div class="razorpay-config border-top pt-3 mt-3" id="razorpay_config" style="display: <?php echo ($current_gateway == 'razorpay') ? 'block' : 'none'; ?>;">
							<div class="row gx-3">
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">Key ID <span class="text-danger">*</span></label>
										<input type="text" name="razorpay_key_id" id="razorpay_key_id" class="form-control" value="<?php echo set_value('razorpay_key_id', $razorpay_key_id); ?>" placeholder="rzp_live_xxxxxxxxxxxxx">
										<?php echo form_error('razorpay_key_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">Key Secret <span class="text-danger">*</span></label>
										<div class="position-relative">
											<input type="password" name="razorpay_key_secret" id="razorpay_key_secret" class="form-control" value="<?php echo set_value('razorpay_key_secret', $razorpay_key_secret); ?>" placeholder="Enter Key Secret">
											<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('razorpay_key_secret')">
												<i class="isax isax-eye" id="razorpay_key_secret-eye"></i>
											</span>
										</div>
										<?php echo form_error('razorpay_key_secret', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
							</div>
						</div>

						<!-- CCAvenue Configuration -->
						<div class="ccavenue-config border-top pt-3 mt-3" id="ccavenue_config" style="display: <?php echo ($current_gateway == 'ccavenue') ? 'block' : 'none'; ?>;">
							<div class="row gx-3">
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">Merchant ID <span class="text-danger">*</span></label>
										<input type="text" name="ccavenue_merchant_id" id="ccavenue_merchant_id" class="form-control" value="<?php echo set_value('ccavenue_merchant_id', $ccavenue_merchant_id); ?>" placeholder="Enter Merchant ID">
										<?php echo form_error('ccavenue_merchant_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">Access Code <span class="text-danger">*</span></label>
										<input type="text" name="ccavenue_access_code" id="ccavenue_access_code" class="form-control" value="<?php echo set_value('ccavenue_access_code', $ccavenue_access_code); ?>" placeholder="Enter Access Code">
										<?php echo form_error('ccavenue_access_code', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">Working Key <span class="text-danger">*</span></label>
										<div class="position-relative">
											<input type="password" name="ccavenue_working_key" id="ccavenue_working_key" class="form-control" value="<?php echo set_value('ccavenue_working_key', $ccavenue_working_key); ?>" placeholder="Enter Working Key">
											<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('ccavenue_working_key')">
												<i class="isax isax-eye" id="ccavenue_working_key-eye"></i>
											</span>
										</div>
										<?php echo form_error('ccavenue_working_key', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
							</div>
						</div>

						<!-- Email Configuration (Zepto Mail) -->
						<div class="border-top pt-3 mt-3">
							<h6 class="mb-3">Email Configuration (Zepto Mail)</h6>
							<div class="row gx-3">
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">API Key <span class="text-danger">*</span></label>
										<div class="position-relative">
											<input type="password" name="zepto_mail_api_key" id="zepto_mail_api_key" class="form-control" value="<?php echo set_value('zepto_mail_api_key', $zepto_mail_api_key); ?>" placeholder="Enter Zepto Mail API Key">
											<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('zepto_mail_api_key')">
												<i class="isax isax-eye" id="zepto_mail_api_key-eye"></i>
											</span>
										</div>
										<?php echo form_error('zepto_mail_api_key', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">From Email <span class="text-danger">*</span></label>
										<input type="email" name="zepto_mail_from_email" id="zepto_mail_from_email" class="form-control" value="<?php echo set_value('zepto_mail_from_email', $zepto_mail_from_email); ?>" placeholder="noreply@example.com">
										<?php echo form_error('zepto_mail_from_email', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="mb-3">
										<label class="form-label">From Name</label>
										<input type="text" name="zepto_mail_from_name" id="zepto_mail_from_name" class="form-control" value="<?php echo set_value('zepto_mail_from_name', $zepto_mail_from_name); ?>" placeholder="Your Company Name">
										<?php echo form_error('zepto_mail_from_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
							</div>
						</div>

						<!-- Firebase Configuration -->
						<div class="border-top pt-3 mt-3">
							<h6 class="mb-3">Firebase Configuration</h6>
							<div class="row gx-3">
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">API Key <span class="text-danger">*</span></label>
										<div class="position-relative">
											<input type="password" name="firebase_api_key" id="firebase_api_key" class="form-control" value="<?php echo set_value('firebase_api_key', $firebase_api_key); ?>" placeholder="Enter Firebase API Key">
											<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('firebase_api_key')">
												<i class="isax isax-eye" id="firebase_api_key-eye"></i>
											</span>
										</div>
										<?php echo form_error('firebase_api_key', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">Auth Domain <span class="text-danger">*</span></label>
										<input type="text" name="firebase_auth_domain" id="firebase_auth_domain" class="form-control" value="<?php echo set_value('firebase_auth_domain', $firebase_auth_domain); ?>" placeholder="project-id.firebaseapp.com">
										<?php echo form_error('firebase_auth_domain', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">Project ID <span class="text-danger">*</span></label>
										<input type="text" name="firebase_project_id" id="firebase_project_id" class="form-control" value="<?php echo set_value('firebase_project_id', $firebase_project_id); ?>" placeholder="Enter Project ID">
										<?php echo form_error('firebase_project_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">Storage Bucket <span class="text-danger">*</span></label>
										<input type="text" name="firebase_storage_bucket" id="firebase_storage_bucket" class="form-control" value="<?php echo set_value('firebase_storage_bucket', $firebase_storage_bucket); ?>" placeholder="project-id.appspot.com">
										<?php echo form_error('firebase_storage_bucket', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">Messaging Sender ID <span class="text-danger">*</span></label>
										<input type="text" name="firebase_messaging_sender_id" id="firebase_messaging_sender_id" class="form-control" value="<?php echo set_value('firebase_messaging_sender_id', $firebase_messaging_sender_id); ?>" placeholder="Enter Messaging Sender ID">
										<?php echo form_error('firebase_messaging_sender_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3">
										<label class="form-label">App ID <span class="text-danger">*</span></label>
										<input type="text" name="firebase_app_id" id="firebase_app_id" class="form-control" value="<?php echo set_value('firebase_app_id', $firebase_app_id); ?>" placeholder="1:123456789:web:abcdef">
										<?php echo form_error('firebase_app_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Form Actions -->
<div class="card mt-4">
	<div class="card-body">
		<div class="d-flex align-items-center justify-content-end gap-2">
			<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-outline">Cancel</a>
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>
</div>

<?php echo form_close(); ?>
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
	
	// Color picker sync and preview update
	var colorPicker = document.getElementById('sidebar_color_picker');
	var colorInput = document.getElementById('sidebar_color');
	var sidebarPreview = document.querySelector('.sidebar-preview');

	function updateSidebarPreview(color) {
		if (sidebarPreview) {
			sidebarPreview.style.background = color;
		}
	}

	if (colorPicker && colorInput) {
		colorPicker.addEventListener('input', function() {
			var color = this.value.toUpperCase();
			colorInput.value = color;
			updateSidebarPreview(color);
		});

		colorInput.addEventListener('input', function() {
			var hex = this.value.trim();
			// Ensure it starts with #
			if (hex && !hex.startsWith('#')) {
				hex = '#' + hex;
			}
			// Validate hex format
			if (/^#[0-9A-Fa-f]{6}$/.test(hex)) {
				var color = hex.toUpperCase();
				colorPicker.value = color;
				this.value = color;
				updateSidebarPreview(color);
			}
		});

		// Initialize preview with current color
		updateSidebarPreview(colorPicker.value);

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

	// Payment Gateway Selection Handler
	var paymentGatewayRadios = document.querySelectorAll('.payment-gateway-radio');
	var razorpayConfig = document.getElementById('razorpay_config');
	var ccavenueConfig = document.getElementById('ccavenue_config');

	paymentGatewayRadios.forEach(function(radio) {
		radio.addEventListener('change', function() {
			if (this.value === 'razorpay') {
				if (razorpayConfig) razorpayConfig.style.display = 'block';
				if (ccavenueConfig) ccavenueConfig.style.display = 'none';
			} else if (this.value === 'ccavenue') {
				if (razorpayConfig) razorpayConfig.style.display = 'none';
				if (ccavenueConfig) ccavenueConfig.style.display = 'block';
			} else {
				if (razorpayConfig) razorpayConfig.style.display = 'none';
				if (ccavenueConfig) ccavenueConfig.style.display = 'none';
			}
		});
	});
});
</script>
