<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
  <div>
    <h6><a href="<?php echo base_url('erp-admin/vendors'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit Vendor</a>
    </h6>
  </div>
</div>
<!-- End Breadcrumb -->

<?php echo form_open_multipart('erp-admin/vendors/edit/' . $vendor['id']); ?>
<div id="emailTemplatesDeletedWrap"></div>

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

.notif-sidebar .list-group-item {
  border: 1px solid transparent;
  border-radius: 0.75rem;
  padding: 0.8rem 0.9rem;
  color: #495057;
  font-weight: 600;
  background: #ffffff;
  box-shadow: 0 1px 0 rgba(16, 24, 40, 0.04);
  display: flex;
  align-items: center;
  gap: 0.65rem;
  transition: all 0.15s ease;
}
.notif-sidebar .list-group-item.active {
  background: #3550dc;
  color: #ffffff;
  box-shadow: 0 10px 25px rgba(53, 80, 220, 0.25);
}
.notif-sidebar .list-group-item:hover {
  background: #f8f9ff;
  border-color: rgba(53, 80, 220, 0.18);
}
.notif-sidebar .list-group-item.active:hover {
  background: #3550dc;
  border-color: transparent;
}
.notif-shell {
  background: linear-gradient(180deg, rgba(53, 80, 220, 0.06) 0%, rgba(53, 80, 220, 0.00) 100%);
  border: 1px solid #eef2ff;
  border-radius: 1rem;
}
.notif-shell .notif-left {
  border-right: 1px solid #eef2ff;
  background: rgba(255,255,255,0.65);
  border-top-left-radius: 1rem;
  border-bottom-left-radius: 1rem;
}
.notif-shell .notif-right {
  background: #ffffff;
  border-top-right-radius: 1rem;
  border-bottom-right-radius: 1rem;
}
.notif-kicker {
  font-size: 0.8rem;
  color: #6c757d;
}
.notif-title {
  font-weight: 700;
  letter-spacing: -0.01em;
}
.notif-icon {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(53, 80, 220, 0.10);
  color: #3550dc;
}
.list-group-item.active .notif-icon {
  background: rgba(255,255,255,0.18);
  color: #ffffff;
}
.notif-panel-card {
  border: 1px solid #eef2ff;
  border-radius: 0.9rem;
  padding: 1rem;
}
.notif-panel-card .form-label {
  font-weight: 600;
}
.notif-panel-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
}
.notif-switch {
  background: #f8f9fa;
  border: 1px solid #eef2ff;
  padding: 0.35rem 0.6rem;
  border-radius: 999px;
}
.notif-table {
  border: 1px solid #eef2ff;
  border-radius: 0.85rem;
  overflow: hidden;
}
.notif-table table {
  margin-bottom: 0;
}
.notif-table thead th {
  background: #f8f9ff;
  font-size: 0.8rem;
  color: #495057;
  border-bottom: 1px solid #eef2ff;
}
.notif-table tbody td {
  border-top: 1px solid #f1f3ff;
}
.kv-table {
  border: 1px dashed #dfe3ff;
  border-radius: 0.75rem;
  padding: 0.5rem;
  background: #fbfbff;
}
.kv-row {
  display: grid;
  grid-template-columns: 1fr 1fr auto;
  gap: 0.5rem;
  align-items: center;
  margin-bottom: 0.5rem;
}
.kv-row:last-child {
  margin-bottom: 0;
}
.kv-row input {
  font-size: 0.85rem;
}
.kv-actions {
  display: flex;
  justify-content: flex-end;
}
.kv-hint {
  font-size: 0.8rem;
  color: #6c757d;
}
.notif-panel {
  display: none;
}
.notif-panel.active {
  display: block;
}
</style>

<div class="vendor-edit-tabs-wrapper">
  <ul class="nav nav-tabs vendor-edit-tabs" id="vendorEditTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" href="#basic-info" role="tab"
        aria-controls="basic-info" aria-selected="true">
        <i class="isax isax-information"></i>
        <span>Basic Information</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="branding-tab" data-bs-toggle="tab" href="#branding" role="tab" aria-controls="branding"
        aria-selected="false">
        <i class="isax isax-gallery"></i>
        <span>Logo & Branding</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="features-tab" data-bs-toggle="tab" href="#features" role="tab" aria-controls="features"
        aria-selected="false">
        <i class="isax isax-setting-2"></i>
        <span>Features & Permissions</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab" aria-controls="payment"
        aria-selected="false">
        <i class="isax isax-card"></i>
        <span>Payment Gateway</span>
      </a>
    </li>  

	<li class="nav-item" role="presentation">
      <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#shipping" role="tab" aria-controls="payment"
        aria-selected="false">
        <i class="isax isax-bus"></i>
        <span>Shipping</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="seo-tab" data-bs-toggle="tab" href="#seo" role="tab" aria-controls="seo"
        aria-selected="false">
        <i class="isax isax-search-normal"></i>
        <span>SEO & Meta</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="notifications-tab" data-bs-toggle="tab" href="#notifications" role="tab" aria-controls="notifications"
        aria-selected="false">
        <i class="isax isax-notification"></i>
        <span>Notifications</span>
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
            <div class="row gx-3">
              <div class="col-lg-6 col-md-6">
                <div class="mb-3">
                  <label class="form-label">Vendor Name <span class="text-danger">*</span></label>
                  <input type="text" name="name" id="name" class="form-control"
                    value="<?php echo set_value('name', $vendor['name']); ?>" required>
                  <?php echo form_error('name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>
              </div>
              <div class="col-lg-6 col-md-6">
                <div class="mb-3">
                  <label class="form-label">Domain <span class="text-danger">*</span></label>
                  <input type="text" name="domain" id="domain" class="form-control"
                    value="<?php echo set_value('domain', $vendor['domain']); ?>" placeholder="varitty.in" required>
                  <small class="text-muted fs-13">Domain name (e.g., varitty.in or vendor-name)</small>
                  <?php echo form_error('domain', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>
              </div>
              <div class="col-lg-6 col-md-6">
                <div class="mb-3">
                  <label class="form-label">Username <span class="text-danger">*</span></label>
                  <input type="text" name="username" id="username" class="form-control"
                    value="<?php echo set_value('username', isset($vendor['username']) ? $vendor['username'] : ''); ?>"
                    required>
                  <small class="text-muted fs-13">Vendor login username</small>
                  <?php echo form_error('username', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>
              </div>
              <div class="col-lg-6 col-md-6">
                <div class="mb-3">
                  <label class="form-label">Password</label>
                  <div class="position-relative">
                    <input type="password" name="password" id="password" class="form-control" value="">
                    <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;"
                      onclick="togglePassword('password')">
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
                        <?php if (!empty($vendor['logo'])): ?>
                        <div class="mb-3">
                          <label class="form-label fw-medium">Current Logo</label>
                          <div class="d-flex align-items-start gap-3">
                            <div class="border rounded p-2 bg-white">
                              <img src="<?php echo 'https://' . $vendor['domain'] . '/' . ltrim($vendor['logo'], '/'); ?>" alt="Vendor Logo" style="max-width: 120px; max-height: 60px; object-fit: contain;">
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
                            <p class="text-muted fs-13 mt-2 mb-0">No logo uploaded<br><small>Default logo will be
                                used</small></p>
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
                          <input type="color" id="sidebar_color_picker"
                            class="form-control form-control-color border-0 p-1"
                            value="<?php echo htmlspecialchars($display_color); ?>"
                            style="width: 50px; height: 40px; cursor: pointer; border-radius: 6px;">
                          <input type="text" name="sidebar_color" id="sidebar_color" class="form-control"
                            value="<?php echo htmlspecialchars($display_color); ?>" placeholder="#7539ff"
                            pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" style="font-family: monospace;">
                        </div>
                      </div>
                      <small class="text-muted fs-13">Choose a hex color for the sidebar theme</small>
                      <?php echo form_error('sidebar_color', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                    </div>

                    <!-- Color Preview -->
                    <div class="mt-3">
                      <label class="form-label fw-medium mb-2">Preview</label>
                      <div class="d-flex align-items-center gap-2">
                        <div class="sidebar-preview border rounded"
                          style="width: 40px; height: 60px; background: <?php echo htmlspecialchars($display_color); ?>; border-radius: 4px 0 0 4px;">
                        </div>
                        <div class="flex-grow-1">
                          <small class="text-muted">Sidebar appearance</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

               <!-- Favicon Upload -->
              <div class="row gx-4 mb-4"> 
                <div class="col-12">
                  <div class="card border-0 bg-light">
                    <div class="card-body p-4">
                      <h6 class="mb-3">
                        <i class="isax isax-gallery me-2 text-primary"></i>
                        Favicon
                      </h6>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label fw-medium">Upload Favicon</label>
                            <input type="file" name="favicon" id="favicon" class="form-control" accept="image/png">
                            <small class="text-muted fs-13">Recommended: ICO, PNG (16x16 or 32x32). Max size: 500KB</small>
                            <?php echo form_error('favicon', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <?php if (!empty($vendor['favicon'])): ?>
                          <div class="mb-3">
                            <label class="form-label fw-medium">Current Favicon</label>
                            <div class="d-flex align-items-start gap-3">
                              <div class="border rounded p-2 bg-white">
                                <img 
                                  src="<?php echo 'https://' . $vendor['domain'] . '/' . ltrim($vendor['favicon'], '/'); ?>" 
                                  alt="Favicon"
                                  style="max-width: 32px; max-height: 32px; object-fit: contain;"
                                >
                              </div>
                              <div class="flex-grow-1">
                                <small class="text-muted d-block">Current favicon</small>
                                <label class="form-check mt-2">
                                  <input type="checkbox" name="remove_favicon" value="1" class="form-check-input">
                                  <span class="form-check-label fs-13">Remove current favicon</span>
                                </label>
                              </div>
                            </div>
                          </div>
                          <?php else: ?>
                          <div class="mb-3">
                            <label class="form-label fw-medium">Current Favicon</label>
                            <div class="text-center py-4 border rounded bg-white">
                              <i class="isax isax-gallery text-muted" style="font-size: 24px;"></i>
                              <p class="text-muted fs-13 mt-2 mb-0">No favicon uploaded<br><small>Default favicon will be used</small></p>
                            </div>
                          </div>
                          <?php endif; ?>
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
                    <input type="checkbox" name="features[<?php echo $feature_id; ?>]" value="1"
                      id="feature_<?php echo $feature_id; ?>" class="form-check-input feature-checkbox"
                      data-feature-id="<?php echo $feature_id; ?>" <?php echo $is_enabled ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-medium small" for="feature_<?php echo $feature_id; ?>">
                      <?php echo htmlspecialchars($feature['name']); ?>
                    </label>
                  </div>

                  <?php if ($has_subcategories): ?>
                  <div class="subcategories-container ms-3 mt-2" id="subcategories_<?php echo $feature_id; ?>"
                    style="display: <?php echo $is_enabled ? 'block' : 'none'; ?>;">
                    <small class="text-muted d-block mb-1 fw-medium" style="font-size: 11px;">Sub-features:</small>
                    <?php foreach ($all_subcategories[$feature_id] as $subcat): ?>
                    <div class="form-check form-check-sm mb-1">
                      <input type="checkbox" name="subcategories[<?php echo $feature_id; ?>][]"
                        value="<?php echo $subcat['id']; ?>"
                        id="subcat_<?php echo $feature_id; ?>_<?php echo $subcat['id']; ?>" class="form-check-input"
                        <?php echo in_array((int)$subcat['id'], $enabled_subcat_ids, true) ? 'checked' : ''; ?>>
                      <label class="form-check-label"
                        for="subcat_<?php echo $feature_id; ?>_<?php echo $subcat['id']; ?>" style="font-size: 11px;">
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
                  <input class="form-check-input payment-gateway-radio" type="radio" name="payment_gateway"
                    id="gateway_razorpay" value="razorpay"
                    <?php echo ($current_gateway == 'razorpay') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="gateway_razorpay">Razorpay</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input payment-gateway-radio" type="radio" name="payment_gateway"
                    id="gateway_ccavenue" value="ccavenue"
                    <?php echo ($current_gateway == 'ccavenue') ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="gateway_ccavenue">CCAvenue</label>
                </div>
              </div>
              <?php echo form_error('payment_gateway', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
            </div>

            <!-- Razorpay Configuration -->
            <div class="razorpay-config border-top pt-3 mt-3" id="razorpay_config"
              style="display: <?php echo ($current_gateway == 'razorpay') ? 'block' : 'none'; ?>;">
              <div class="row gx-3">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Key ID <span class="text-danger">*</span></label>
                    <input type="text" name="razorpay_key_id" id="razorpay_key_id" class="form-control"
                      value="<?php echo set_value('razorpay_key_id', $razorpay_key_id); ?>"
                      placeholder="rzp_live_xxxxxxxxxxxxx">
                    <?php echo form_error('razorpay_key_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Key Secret <span class="text-danger">*</span></label>
                    <div class="position-relative">
                      <input type="password" name="razorpay_key_secret" id="razorpay_key_secret" class="form-control"
                        value="<?php echo set_value('razorpay_key_secret', $razorpay_key_secret); ?>"
                        placeholder="Enter Key Secret">
                      <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;"
                        onclick="togglePassword('razorpay_key_secret')">
                        <i class="isax isax-eye" id="razorpay_key_secret-eye"></i>
                      </span>
                    </div>
                    <?php echo form_error('razorpay_key_secret', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
              </div>
            </div>

            <!-- CCAvenue Configuration -->
            <div class="ccavenue-config border-top pt-3 mt-3" id="ccavenue_config"
              style="display: <?php echo ($current_gateway == 'ccavenue') ? 'block' : 'none'; ?>;">
              <div class="row gx-3">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">Merchant ID <span class="text-danger">*</span></label>
                    <input type="text" name="ccavenue_merchant_id" id="ccavenue_merchant_id" class="form-control"
                      value="<?php echo set_value('ccavenue_merchant_id', $ccavenue_merchant_id); ?>"
                      placeholder="Enter Merchant ID">
                    <?php echo form_error('ccavenue_merchant_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">Access Code <span class="text-danger">*</span></label>
                    <input type="text" name="ccavenue_access_code" id="ccavenue_access_code" class="form-control"
                      value="<?php echo set_value('ccavenue_access_code', $ccavenue_access_code); ?>"
                      placeholder="Enter Access Code">
                    <?php echo form_error('ccavenue_access_code', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">Working Key <span class="text-danger">*</span></label>
                    <div class="position-relative">
                      <input type="password" name="ccavenue_working_key" id="ccavenue_working_key" class="form-control"
                        value="<?php echo set_value('ccavenue_working_key', $ccavenue_working_key); ?>"
                        placeholder="Enter Working Key">
                      <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;"
                        onclick="togglePassword('ccavenue_working_key')">
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
                      <input type="password" name="zepto_mail_api_key" id="zepto_mail_api_key" class="form-control"
                        value="<?php echo set_value('zepto_mail_api_key', $zepto_mail_api_key); ?>"
                        placeholder="Enter Zepto Mail API Key">
                      <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;"
                        onclick="togglePassword('zepto_mail_api_key')">
                        <i class="isax isax-eye" id="zepto_mail_api_key-eye"></i>
                      </span>
                    </div>
                    <?php echo form_error('zepto_mail_api_key', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">From Email <span class="text-danger">*</span></label>
                    <input type="email" name="zepto_mail_from_email" id="zepto_mail_from_email" class="form-control"
                      value="<?php echo set_value('zepto_mail_from_email', $zepto_mail_from_email); ?>"
                      placeholder="noreply@example.com">
                    <?php echo form_error('zepto_mail_from_email', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label">From Name</label>
                    <input type="text" name="zepto_mail_from_name" id="zepto_mail_from_name" class="form-control"
                      value="<?php echo set_value('zepto_mail_from_name', $zepto_mail_from_name); ?>"
                      placeholder="Your Company Name">
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
                      <input type="password" name="firebase_api_key" id="firebase_api_key" class="form-control"
                        value="<?php echo set_value('firebase_api_key', $firebase_api_key); ?>"
                        placeholder="Enter Firebase API Key">
                      <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;"
                        onclick="togglePassword('firebase_api_key')">
                        <i class="isax isax-eye" id="firebase_api_key-eye"></i>
                      </span>
                    </div>
                    <?php echo form_error('firebase_api_key', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Auth Domain <span class="text-danger">*</span></label>
                    <input type="text" name="firebase_auth_domain" id="firebase_auth_domain" class="form-control"
                      value="<?php echo set_value('firebase_auth_domain', $firebase_auth_domain); ?>"
                      placeholder="project-id.firebaseapp.com">
                    <?php echo form_error('firebase_auth_domain', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Project ID <span class="text-danger">*</span></label>
                    <input type="text" name="firebase_project_id" id="firebase_project_id" class="form-control"
                      value="<?php echo set_value('firebase_project_id', $firebase_project_id); ?>"
                      placeholder="Enter Project ID">
                    <?php echo form_error('firebase_project_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Storage Bucket <span class="text-danger">*</span></label>
                    <input type="text" name="firebase_storage_bucket" id="firebase_storage_bucket" class="form-control"
                      value="<?php echo set_value('firebase_storage_bucket', $firebase_storage_bucket); ?>"
                      placeholder="project-id.appspot.com">
                    <?php echo form_error('firebase_storage_bucket', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Messaging Sender ID <span class="text-danger">*</span></label>
                    <input type="text" name="firebase_messaging_sender_id" id="firebase_messaging_sender_id"
                      class="form-control"
                      value="<?php echo set_value('firebase_messaging_sender_id', $firebase_messaging_sender_id); ?>"
                      placeholder="Enter Messaging Sender ID">
                    <?php echo form_error('firebase_messaging_sender_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">App ID <span class="text-danger">*</span></label>
                    <input type="text" name="firebase_app_id" id="firebase_app_id" class="form-control"
                      value="<?php echo set_value('firebase_app_id', $firebase_app_id); ?>"
                      placeholder="1:123456789:web:abcdef">
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


  <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="payment-tab">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h6 class="mb-3">Shipping Gateway</h6>
			
<?php
$current_shipping = !empty($vendor['shipping_providers'])
    ? explode(',', $vendor['shipping_providers'])
    : [];

$provider_data = isset($provider_data) ? $provider_data : [];

/* Shiprocket */
$shiprocket = isset($provider_data['shiprocket']) ? $provider_data['shiprocket'] : [];

/* Bigship */
$bigship = isset($provider_data['bigship']) ? $provider_data['bigship'] : [];

/* Velocity */
$velocity = isset($provider_data['velocity']) ? $provider_data['velocity'] : [];
?>

<!-- Shipping Providers Selection -->
<div class="border-top pt-3 mt-3">
  <h6 class="mb-3">Shipping Providers</h6>
<div class="d-flex gap-4 flex-wrap">

  <div class="form-check">
    <input class="form-check-input shipping-provider-checkbox"
           type="checkbox"
           name="shipping_providers[]"
           value="shiprocket"
           id="shiprocket"
           <?php echo in_array('shiprocket', $current_shipping) ? 'checked' : ''; ?>>
    <label class="form-check-label" for="shiprocket">Shiprocket</label>
  </div>

  <div class="form-check">
    <input class="form-check-input shipping-provider-checkbox"
           type="checkbox"
           name="shipping_providers[]"
           value="bigship"
           id="bigship"
           <?php echo in_array('bigship', $current_shipping) ? 'checked' : ''; ?>>
    <label class="form-check-label" for="bigship">Bigship</label>
  </div>

  <div class="form-check">
    <input class="form-check-input shipping-provider-checkbox"
           type="checkbox"
           name="shipping_providers[]"
           value="velocity"
           id="velocity"
           <?php echo in_array('velocity', $current_shipping) ? 'checked' : ''; ?>>
    <label class="form-check-label" for="velocity">Velocity</label>
  </div>

</div>
</div>

<div id="shiprocket_config" class="shipping-config border-top pt-3 mt-3"
     style="display: <?php echo in_array('shiprocket', $current_shipping) ? 'block' : 'none'; ?>;">

  <h6 class="mb-3">Shiprocket Configuration</h6>

  <div class="row">
    <div class="col-md-4">
      <label>Name <span class="text-danger required-star">*</span></label>
      <input type="text" name="shiprocket_name"
        class="form-control shiprocket-required"
        value="<?php echo set_value('shiprocket_name', $shiprocket['name'] ?? ''); ?>">
    </div>

    <div class="col-md-4">
      <label>Email <span class="text-danger required-star">*</span></label>
      <input type="email" name="shiprocket_email"
        class="form-control shiprocket-required"
        value="<?php echo set_value('shiprocket_email', $shiprocket['email'] ?? ''); ?>">
    </div>

    <div class="col-md-4">
      <label>Password <span class="text-danger required-star">*</span></label>
      <input type="password" name="shiprocket_password"
        class="form-control shiprocket-required"
        value="<?php echo set_value('shiprocket_password', $shiprocket['password'] ?? ''); ?>">
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-6">
      <label>Pickup Location Name</label>
      <input type="text" name="shiprocket_pickup_location"
        class="form-control"
        placeholder="Optional - name of pickup in Shiprocket dashboard"
        value="<?php echo set_value('shiprocket_pickup_location', $shiprocket['pickup_name'] ?? ''); ?>">
      <small class="text-muted">Leave empty to use default from Shiprocket</small>
    </div>
  </div>

</div>
<div id="bigship_config" class="shipping-config border-top pt-3 mt-3"
     style="display: <?php echo in_array('bigship', $current_shipping) ? 'block' : 'none'; ?>;">

  <h6 class="mb-3">Bigship Configuration</h6>

  <div class="row">
    <div class="col-md-3">
      <label>User Name <span class="text-danger required-star">*</span></label>
      <input type="text" name="bigship_user_name"
        class="form-control bigship-required"
        value="<?php echo set_value('bigship_user_name', $bigship['name'] ?? ''); ?>">
    </div>  

	<div class="col-md-3">
      <label>Email <span class="text-danger required-star">*</span></label>
      <input type="text" name="bigship_email"
        class="form-control bigship-required"
        value="<?php echo set_value('bigship_email', $bigship['email'] ?? ''); ?>">
    </div>


    <div class="col-md-3">
      <label>Password <span class="text-danger required-star">*</span></label>
      <input type="text" name="bigship_password"
        class="form-control bigship-required"
        value="<?php echo set_value('bigship_password', $bigship['password'] ?? ''); ?>">
    </div>

    <div class="col-md-3">
      <label>Access Key <span class="text-danger required-star">*</span></label>
      <input type="text" name="bigship_access_key"
        class="form-control bigship-required"
        value="<?php echo set_value('bigship_access_key', $bigship['company_id'] ?? ''); ?>">
    </div>
  </div>

</div>

<div id="velocity_config" class="shipping-config border-top pt-3 mt-3"
     style="display: <?php echo in_array('velocity', $current_shipping) ? 'block' : 'none'; ?>;">

  <h6 class="mb-3">Velocity Configuration</h6>

  <div class="row"> 
  <div class="col-md-3">
      <label>CustomerName <span class="text-danger required-star">*</span></label>
      <input type="text" name="velocity_username"
        class="form-control velocity-required"
        value="<?php echo set_value('velocity_username', $velocity['name'] ?? ''); ?>">
    </div>
	
    <div class="col-md-3">
      <label>Username <span class="text-danger required-star">*</span></label>
      <input type="text" name="velocity_email"
        class="form-control velocity-required"
        value="<?php echo set_value('velocity_email', $velocity['email'] ?? ''); ?>">
    </div>

    <div class="col-md-3">
      <label>Password <span class="text-danger required-star">*</span></label>
      <input type="text" name="velocity_password"
        class="form-control velocity-required"
        value="<?php echo set_value('velocity_password', $velocity['password'] ?? ''); ?>">
    </div>

    <div class="col-md-3">
      <label>Account No <span class="text-danger required-star">*</span></label>
      <input type="text" name="velocity_accno"
        class="form-control velocity-required"
        value="<?php echo set_value('velocity_accno', $velocity['company_id'] ?? ''); ?>">
    </div>

    <div class="col-md-3">
      <label>Secret Code <span class="text-danger required-star">*</span></label>
      <input type="text" name="velocity_secret_code"
        class="form-control velocity-required"
        value="<?php echo set_value('velocity_secret_code', $velocity['channel_id'] ?? ''); ?>">
    </div>
  </div>
   

<h6 class="mb-3 mt-4">Pickup Details</h6>

<div class="row g-3">

  <div class="col-md-4">
    <label>Pickup Name <span class="text-danger required-star d-none">*</span></label>
    <input type="text" name="velocity_pickup_name"
      class="form-control velocity-required"
      value="<?php echo set_value('velocity_pickup_name', $velocity['pickup_name'] ?? ''); ?>">
  </div>

  <div class="col-md-4">
    <label>Pickup Email <span class="text-danger required-star d-none">*</span></label>
<input type="email"
       name="velocity_pickup_emailid"
       class="form-control velocity-required"
       maxlength="150"
       pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
       value="<?php echo set_value('velocity_pickup_emailid', $velocity['pickup_emailid'] ?? ''); ?>">
  </div>

  <div class="col-md-4">
    <label>Pickup Phone <span class="text-danger required-star d-none">*</span></label>
	<input type="text"
       name="velocity_pickup_phoneno"
       class="form-control velocity-required" 
	   onkeypress="return isNumberKey(event,this)" minlength="10" maxlength="10" inputmode="numeric"
       value="<?php echo set_value('velocity_pickup_phoneno', $velocity['pickup_phoneno'] ?? ''); ?>"
       required>
<small class="text-muted">10 digit mobile starting with 6-9</small>
  </div>

  <div class="col-md-4">
    <label>Alternate Phone</label>
	<input type="text"
       name="velocity_pickup_alt_phoneno"
       class="form-control" onkeypress="return isNumberKey(event,this)" minlength="10" maxlength="10" inputmode="numeric"
       value="<?php echo set_value('velocity_pickup_alt_phoneno', $velocity['pickup_alt_phoneno'] ?? ''); ?>">
  </div>

  <div class="col-md-4">
    <label>Pickup City <span class="text-danger required-star d-none">*</span></label>
    <input type="text" name="velocity_pickup_city"
      class="form-control velocity-required"
      value="<?php echo set_value('velocity_pickup_city', $velocity['pickup_city'] ?? ''); ?>">
  </div>
 
  
  <div class="col-md-4">
	  <label> Pickup State <span class="text-danger required-star d-none">*</span> </label>

	  <select name="velocity_pickup_state" class="form-select velocity-required">
		<option value="">Select State</option>
		<?php 
		  $selected_state = set_value('velocity_pickup_state',$velocity['pickup_state'] ?? '');
		  if (!empty($states)):
		  foreach ($states as $state):
		  $state_name = $state['name'];  ?>
			<option value="<?php echo htmlspecialchars($state_name); ?>" <?php echo ($selected_state == $state_name) ? 'selected' : ''; ?>><?php echo htmlspecialchars($state_name); ?> </option>
		 <?php endforeach; endif;?>
	  </select>
	</div>

  <div class="col-md-6">
    <label>Pickup Address <span class="text-danger required-star d-none">*</span></label>
    <textarea name="velocity_pickup_address"
      class="form-control velocity-required"
      rows="2"><?php echo set_value('velocity_pickup_address', $velocity['pickup_address'] ?? ''); ?></textarea>
  </div>

  <div class="col-md-6">
    <label>Landmark <span class="text-danger required-star d-none">*</span></label>
    <input type="text" name="velocity_pickup_landmark"
      class="form-control velocity-required"
      value="<?php echo set_value('velocity_pickup_landmark', $velocity['pickup_landmark'] ?? ''); ?>">
  </div>

  <div class="col-md-4">
    <label>Pincode <span class="text-danger required-star d-none">*</span></label>
<input type="text"
       name="velocity_pickup_pincode"
       class="form-control velocity-required"
		minlength="6" maxlength="6" onkeypress="return isNumberKey(event,this)"   
       inputmode="numeric"
       value="<?php echo set_value('velocity_pickup_pincode', $velocity['pickup_pincode'] ?? ''); ?>"
       required>
<small class="text-muted">6 digit Indian pincode</small>
  </div>

</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll('.shipping-provider-checkbox');

    function toggleProvider(provider, isChecked) {

        const configDiv = document.getElementById(provider + '_config');

        if (configDiv) {
            configDiv.style.display = isChecked ? 'block' : 'none';
        }

        // Toggle required fields
        const requiredFields = document.querySelectorAll('.' + provider + '-required');
        const stars = document.querySelectorAll('#' + provider + '_config .required-star');

        requiredFields.forEach(function (field) {
            if (isChecked) {
                field.setAttribute('required', 'required');
            } else {
                field.removeAttribute('required');
            }
        });

        // Toggle red stars
        stars.forEach(function (star) {
            if (isChecked) {
                star.classList.remove('d-none');
            } else {
                star.classList.add('d-none');
            }
        });
    }

    // ON LOAD — apply for already checked providers
    checkboxes.forEach(function (checkbox) {
        toggleProvider(checkbox.value, checkbox.checked);
    });

    // ON CHANGE
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            toggleProvider(this.value, this.checked);
        });
    });

});
</script>


          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- SEO & Meta Tab -->
  <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h6 class="mb-4">SEO & Meta Information</h6>

            <?php
            $site_title = isset($vendor['site_title']) ? $vendor['site_title'] : '';
            $meta_description = isset($vendor['meta_description']) ? $vendor['meta_description'] : '';
            $meta_keywords = isset($vendor['meta_keywords']) ? $vendor['meta_keywords'] : '';
            ?>

            <!-- Site Title -->
            <div class="mb-4">
              <label class="form-label fw-medium">Site Title</label>
              <input type="text" name="site_title" id="site_title" class="form-control"
                value="<?php echo set_value('site_title', $site_title); ?>"
                placeholder="Enter site title (e.g., Vendor Name - Online Store)">
              <small class="text-muted fs-13">This will be used as the page title in browser tabs and search results</small>
              <?php echo form_error('site_title', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
            </div>

            <!-- Meta Description -->
            <div class="mb-4">
              <label class="form-label fw-medium">Meta Description</label>
              <textarea name="meta_description" id="meta_description" class="form-control" rows="3"
                placeholder="Enter meta description (recommended: 150-160 characters)"><?php echo set_value('meta_description', $meta_description); ?></textarea>
              <small class="text-muted fs-13">Brief description of your site for search engines (recommended: 150-160 characters)</small>
              <div class="mt-1">
                <span class="badge badge-sm" id="meta_desc_count">0</span> characters
              </div>
              <?php echo form_error('meta_description', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
            </div>

            <!-- Meta Keywords -->
            <div class="mb-4">
              <label class="form-label fw-medium">Meta Keywords</label>
              <textarea name="meta_keywords" id="meta_keywords" class="form-control" rows="2"
                placeholder="Enter keywords separated by commas (e.g., books, stationery, uniforms)"><?php echo set_value('meta_keywords', $meta_keywords); ?></textarea>
              <small class="text-muted fs-13">Comma-separated keywords relevant to your site</small>
              <?php echo form_error('meta_keywords', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Notifications Tab -->
  <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
    <?php
      $notif = isset($notification_settings) && is_array($notification_settings) ? $notification_settings : [];
      $email_templates = isset($email_templates) && is_array($email_templates) ? $email_templates : [];
      $wa_templates = isset($whatsapp_templates) && is_array($whatsapp_templates) ? $whatsapp_templates : [];
      $notification_events = isset($notification_events) && is_array($notification_events) ? $notification_events : [];

      $email_enabled = !empty($notif['email_enabled']);
      $wa_enabled = !empty($notif['whatsapp_enabled']);
      $sms_enabled = !empty($notif['sms_enabled']);

      $event_options = [['value' => '', 'label' => '-- Select Type --']];
      foreach ($notification_events as $ev) {
        $event_options[] = [
          'value' => (string)($ev['event_key'] ?? ''),
          'label' => (string)($ev['title'] ?? $ev['event_key'] ?? '')
        ];
      }
    ?>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
              <div>
                <div class="notif-kicker">Vendor communication settings</div>
                <div class="notif-title">Notifications</div>
                <small class="text-muted">Configure Email (SMTP), WhatsApp templates, and SMS gateway for this vendor.</small>
              </div>
            </div>

            <div class="notif-shell">
              <div class="row g-0">
                <div class="col-lg-3 notif-left p-3">
                  <div class="notif-sidebar">
                    <div class="list-group" id="notifChannelList">
                      <button type="button" class="list-group-item list-group-item-action active" data-channel="email">
                        <span class="notif-icon"><i class="isax isax-sms"></i></span>
                        <span>
                          <div class="fw-semibold">Email</div>
                          <div class="small opacity-75">SMTP settings</div>
                        </span>
                      </button>
                      <button type="button" class="list-group-item list-group-item-action" data-channel="whatsapp">
                        <span class="notif-icon"><i class="isax isax-message-text"></i></span>
                        <span>
                          <div class="fw-semibold">WhatsApp</div>
                          <div class="small opacity-75">Templates + API</div>
                        </span>
                      </button>
                      <button type="button" class="list-group-item list-group-item-action" data-channel="sms">
                        <span class="notif-icon"><i class="isax isax-mobile"></i></span>
                        <span>
                          <div class="fw-semibold">SMS</div>
                          <div class="small opacity-75">Gateway config</div>
                        </span>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="col-lg-9 notif-right p-3 p-md-4">
                <!-- Email panel -->
                <div class="notif-panel active" id="notif_email">
                  <div class="notif-panel-card">
                  <div class="notif-panel-head mb-2">
                    <div>
                      <h6 class="mb-0">Email (SMTP)</h6>
                      <small class="text-muted">Used for order confirmation, alerts, and transactional emails.</small>
                    </div>
                    <div class="notif-switch">
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input notif-toggle" type="checkbox" id="email_enabled" name="notif[email_enabled]" value="1"
                        <?php echo set_checkbox('notif[email_enabled]', '1', $email_enabled); ?>>
                      <label class="form-check-label" for="email_enabled">Enabled</label>
                    </div>
                    </div>
                  </div>

                  <div class="border-top pt-3 mt-2 notif-section" data-toggle-id="email_enabled"
                    style="display: <?php echo $email_enabled ? 'block' : 'none'; ?>;">
                    <div class="card mb-3" style="border: 1px solid #e9ecef;">
                      <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                          <div>
                            <h6 class="mb-0">Email Templates</h6>
                            <small class="text-muted">Select a Type (event) and write Subject/HTML with tokens like <code>{{order_unique_id}}</code>.</small>
                          </div>
                          <button type="button" class="btn btn-outline-secondary btn-sm" id="emailAddRow">Add Template</button>
                        </div>

                        <?php $email_rows = !empty($email_templates) ? $email_templates : [[]]; ?>
                        <div class="table-responsive">
                          <table class="table table-sm align-middle">
                            <thead>
                              <tr>
                                <th style="min-width: 220px;">Type (Event)</th>
                                <th style="min-width: 120px;">Audience</th>
                                <th style="min-width: 240px;">To (Vendor)</th>
                                <th style="min-width: 240px;">CC</th>
                                <th style="min-width: 260px;">Subject</th>
                                <th style="min-width: 360px;">HTML</th>
                                <th style="width: 80px;">Active</th>
                                <th style="width: 80px;"></th>
                              </tr>
                            </thead>
                            <tbody id="emailTemplateRows">
                              <?php foreach ($email_rows as $idx => $t): ?>
                                <tr>
                                  <td>
                                    <?php
                                      $sel = isset($_POST['email_templates'][$idx]['event_key'])
                                        ? (string)$_POST['email_templates'][$idx]['event_key']
                                        : (string)($t['event_key'] ?? '');
                                    ?>
                                    <select class="form-select form-select-sm" name="email_templates[<?php echo $idx; ?>][event_key]">
                                      <?php foreach ($event_options as $opt): ?>
                                        <option value="<?php echo htmlspecialchars($opt['value']); ?>" <?php echo ($sel !== '' && $sel === $opt['value']) ? 'selected' : ''; ?>>
                                          <?php echo htmlspecialchars($opt['label']); ?>
                                        </option>
                                      <?php endforeach; ?>
                                    </select>
                                  </td>
                                  <td>
                                    <?php
                                      $aud = isset($_POST['email_templates'][$idx]['audience'])
                                        ? strtolower((string)$_POST['email_templates'][$idx]['audience'])
                                        : strtolower((string)($t['audience'] ?? 'user'));
                                      if ($aud !== 'vendor') $aud = 'user';
                                    ?>
                                    <select class="form-select form-select-sm email-audience" name="email_templates[<?php echo $idx; ?>][audience]">
                                      <option value="user" <?php echo ($aud === 'user') ? 'selected' : ''; ?>>User</option>
                                      <option value="vendor" <?php echo ($aud === 'vendor') ? 'selected' : ''; ?>>Vendor</option>
                                    </select>
                                  </td>
                                  <td>
                                    <input type="text" class="form-control form-control-sm email-to-vendor" name="email_templates[<?php echo $idx; ?>][to_emails]"
                                      value="<?php echo set_value('email_templates['.$idx.'][to_emails]', $t['to_emails'] ?? ''); ?>"
                                      placeholder="vendor@example.com, ops@example.com">
                                    <small class="text-muted">Used only for Audience=Vendor.</small>
                                  </td>
                                  <td>
                                    <input type="text" class="form-control form-control-sm" name="email_templates[<?php echo $idx; ?>][cc_emails]"
                                      value="<?php echo set_value('email_templates['.$idx.'][cc_emails]', $t['cc_emails'] ?? ''); ?>"
                                      placeholder="cc1@example.com, cc2@example.com">
                                  </td>
                                  <td>
                                    <input type="text" class="form-control form-control-sm" name="email_templates[<?php echo $idx; ?>][email_subject]"
                                      value="<?php echo set_value('email_templates['.$idx.'][email_subject]', $t['email_subject'] ?? ''); ?>" placeholder="Your order {{order_unique_id}} placed">
                                  </td>
                                  <td>
                                    <textarea class="form-control form-control-sm" rows="2" name="email_templates[<?php echo $idx; ?>][email_html]"
                                      placeholder="<p>Hello {{customer_name}}</p>"><?php echo set_value('email_templates['.$idx.'][email_html]', $t['email_html'] ?? ''); ?></textarea>
                                  </td>
                                  <td class="text-center">
                                    <?php $active = isset($t['is_active']) ? (int)$t['is_active'] : 1; ?>
                                    <input type="checkbox" class="form-check-input" name="email_templates[<?php echo $idx; ?>][is_active]" value="1"
                                      <?php echo set_checkbox('email_templates['.$idx.'][is_active]', '1', $active === 1); ?>>
                                  </td>
                                  <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger email-remove-row">Remove</button>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="row gx-3">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">SMTP Host <span class="text-danger">*</span></label>
                          <input type="text" class="form-control notif-required-email" name="notif[email_smtp_host]"
                            value="<?php echo set_value('notif[email_smtp_host]', $notif['email_smtp_host'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">SMTP Port <span class="text-danger">*</span></label>
                          <input type="number" class="form-control notif-required-email" name="notif[email_smtp_port]"
                            value="<?php echo set_value('notif[email_smtp_port]', $notif['email_smtp_port'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="mb-3">
                          <label class="form-label">Crypto</label>
                          <?php $crypto = set_value('notif[email_smtp_crypto]', $notif['email_smtp_crypto'] ?? ''); ?>
                          <select class="form-select" name="notif[email_smtp_crypto]">
                            <option value="" <?php echo ($crypto === '' ? 'selected' : ''); ?>>None</option>
                            <option value="tls" <?php echo ($crypto === 'tls' ? 'selected' : ''); ?>>TLS</option>
                            <option value="ssl" <?php echo ($crypto === 'ssl' ? 'selected' : ''); ?>>SSL</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">SMTP Username <span class="text-danger">*</span></label>
                          <input type="text" class="form-control notif-required-email" name="notif[email_smtp_user]"
                            value="<?php echo set_value('notif[email_smtp_user]', $notif['email_smtp_user'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">SMTP Password <span class="text-danger">*</span></label>
                          <div class="position-relative">
                            <input type="password" class="form-control notif-required-email" name="notif[email_smtp_pass]" id="notif_email_smtp_pass"
                              value="<?php echo set_value('notif[email_smtp_pass]', $notif['email_smtp_pass'] ?? ''); ?>">
                            <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor:pointer;"
                              onclick="togglePassword('notif_email_smtp_pass')">
                              <i class="isax isax-eye" id="notif_email_smtp_pass-eye"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">From Name</label>
                          <input type="text" class="form-control" name="notif[email_from_name]"
                            value="<?php echo set_value('notif[email_from_name]', $notif['email_from_name'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">From Email</label>
                          <input type="email" class="form-control" name="notif[email_from_email]"
                            value="<?php echo set_value('notif[email_from_email]', $notif['email_from_email'] ?? ''); ?>">
                        </div>
                      </div>
                    </div>

                    <div class="border-top pt-3 mt-2">
                      <div class="row gx-3 align-items-end">
                        <div class="col-md-8">
                          <label class="form-label">Test Recipient Email</label>
                          <input type="email" class="form-control" name="notif_test[email_to]" value="">
                        </div>
                        <div class="col-md-4">
                          <button type="button" class="btn btn-primary w-100 notif-test-btn" data-test="email">
                            Send Test Email
                          </button>
                        </div>
                      </div>
                      <small class="text-muted d-block mt-2">Test sending uses saved settings for this vendor.</small>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- WhatsApp panel -->
                <div class="notif-panel" id="notif_whatsapp">
                  <div class="notif-panel-card">
                  <div class="notif-panel-head mb-2">
                    <div>
                      <h6 class="mb-0">WhatsApp</h6>
                      <small class="text-muted">Provider-agnostic endpoint with multiple templates.</small>
                    </div>
                    <div class="notif-switch">
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input notif-toggle" type="checkbox" id="whatsapp_enabled" name="notif[whatsapp_enabled]" value="1"
                        <?php echo set_checkbox('notif[whatsapp_enabled]', '1', $wa_enabled); ?>>
                      <label class="form-check-label" for="whatsapp_enabled">Enabled</label>
                    </div>
                    </div>
                  </div>

                  <div class="border-top pt-3 mt-2 notif-section" data-toggle-id="whatsapp_enabled"
                    style="display: <?php echo $wa_enabled ? 'block' : 'none'; ?>;">
                    <div class="row gx-3">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">Provider Name</label>
                          <input type="text" class="form-control" name="notif[whatsapp_provider_name]"
                            value="<?php echo set_value('notif[whatsapp_provider_name]', $notif['whatsapp_provider_name'] ?? ''); ?>"
                            placeholder="Meta / WATI / Custom">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">HTTP Method</label>
                          <?php $wa_method = strtoupper((string)set_value('notif[whatsapp_http_method]', $notif['whatsapp_http_method'] ?? 'POST')); ?>
                          <select class="form-select" name="notif[whatsapp_http_method]">
                            <option value="POST" <?php echo ($wa_method === 'POST' ? 'selected' : ''); ?>>POST</option>
                            <option value="GET" <?php echo ($wa_method === 'GET' ? 'selected' : ''); ?>>GET</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="mb-3">
                          <label class="form-label">Endpoint URL <span class="text-danger">*</span></label>
                          <input type="text" class="form-control notif-required-whatsapp" name="notif[whatsapp_endpoint_url]"
                            value="<?php echo set_value('notif[whatsapp_endpoint_url]', $notif['whatsapp_endpoint_url'] ?? ''); ?>"
                            placeholder="https://api.provider.com/send">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">Headers JSON (optional)</label>
                          <textarea class="form-control" rows="5" name="notif[whatsapp_headers_json]"
                            placeholder='{\"Authorization\":\"Bearer ...\"}'><?php echo set_value('notif[whatsapp_headers_json]', isset($notif['whatsapp_headers_json']) ? json_encode($notif['whatsapp_headers_json']) : ''); ?></textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">Default Params (optional)</label>
                          <?php
                            // set_value() is unreliable for array field names like notif[...]
                            if (isset($_POST['notif']) && is_array($_POST['notif']) && array_key_exists('whatsapp_default_params_json', $_POST['notif'])) {
                              $wa_defaults_json = (string)$_POST['notif']['whatsapp_default_params_json'];
                            } else {
                              $wa_defaults_json = isset($notif['whatsapp_default_params_json']) ? json_encode($notif['whatsapp_default_params_json']) : '';
                            }
                          ?>
                          <input type="hidden" name="notif[whatsapp_default_params_json]" id="wa_default_params_json" value="<?php echo htmlspecialchars($wa_defaults_json); ?>">
                          <div class="kv-table" id="waDefaultParams" data-json="<?php echo htmlspecialchars($wa_defaults_json); ?>">
                            <div class="kv-hint mb-2">Add parameters like <code>to</code>, <code>template</code>, <code>token</code>. You can use tokens like <code>{{mobile}}</code> or <code>{{template_name}}</code>.</div>
                            <div class="kv-actions mb-2">
                              <button type="button" class="btn btn-sm btn-outline-secondary" data-kv-add="waDefaultParams">Add Param</button>
                            </div>
                            <div class="kv-rows"></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="border-top pt-3 mt-2">
                      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <h6 class="mb-0">Templates</h6>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="waAddRow">Add Template</button>
                      </div>

                      <div class="notif-table">
                        <div class="table-responsive">
                        <table class="table table-sm align-middle">
                          <thead>
                            <tr>
                              <th style="min-width: 170px;">Type (Event)</th>
                              <th style="min-width: 140px;">Template Key</th>
                              <th style="min-width: 200px;">Template Name</th>
                              <th style="min-width: 120px;">Language</th>
                              <th style="min-width: 260px;">Params</th>
                              <th style="width: 80px;">Active</th>
                              <th style="width: 80px;"></th>
                            </tr>
                          </thead>
                          <tbody id="waTemplateRows">
                            <?php
                              $rows = !empty($wa_templates) ? $wa_templates : [[]];
                              foreach ($rows as $idx => $t):
                                $pm = '';
                                if (isset($t['param_map_json']) && is_array($t['param_map_json'])) {
                                  $pm = json_encode($t['param_map_json']);
                                } elseif (isset($t['param_map_json']) && is_string($t['param_map_json'])) {
                                  $pm = $t['param_map_json'];
                                }
                            ?>
                            <tr>
                              <td>
                                <?php
                                  $sel = isset($_POST['wa_templates'][$idx]['event_key'])
                                    ? (string)$_POST['wa_templates'][$idx]['event_key']
                                    : (string)($t['event_key'] ?? '');
                                ?>
                                <select class="form-select form-select-sm" name="wa_templates[<?php echo $idx; ?>][event_key]">
                                  <?php foreach ($event_options as $opt): ?>
                                    <option value="<?php echo htmlspecialchars($opt['value']); ?>" <?php echo ($sel !== '' && $sel === $opt['value']) ? 'selected' : ''; ?>>
                                      <?php echo htmlspecialchars($opt['label']); ?>
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                              </td>
                              <td>
                                <input type="text" class="form-control form-control-sm" name="wa_templates[<?php echo $idx; ?>][template_key]"
                                  value="<?php echo set_value('wa_templates['.$idx.'][template_key]', $t['template_key'] ?? ''); ?>" placeholder="order_placed">
                              </td>
                              <td>
                                <input type="text" class="form-control form-control-sm" name="wa_templates[<?php echo $idx; ?>][template_name]"
                                  value="<?php echo set_value('wa_templates['.$idx.'][template_name]', $t['template_name'] ?? ''); ?>" placeholder="provider_template_name">
                              </td>
                              <td>
                                <input type="text" class="form-control form-control-sm" name="wa_templates[<?php echo $idx; ?>][language]"
                                  value="<?php echo set_value('wa_templates['.$idx.'][language]', $t['language'] ?? ''); ?>" placeholder="en">
                              </td>
                              <td>
                                <?php
                                  // set_value() is unreliable for nested array field names; prefer POST when present.
                                  if (isset($_POST['wa_templates'][$idx]['param_map_json'])) {
                                    $pm_val = (string)$_POST['wa_templates'][$idx]['param_map_json'];
                                  } else {
                                    $pm_val = $pm;
                                  }
                                ?>
                                <input type="hidden" name="wa_templates[<?php echo $idx; ?>][param_map_json]" class="wa_param_map_json" value="<?php echo htmlspecialchars($pm_val); ?>">
                                <div class="kv-table waParamMap" data-json="<?php echo htmlspecialchars($pm_val); ?>">
                                  <div class="kv-actions mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary wa-add-param">Add</button>
                                  </div>
                                  <div class="kv-rows"></div>
                                </div>
                              </td>
                              <td class="text-center">
                                <?php $active = isset($t['is_active']) ? (int)$t['is_active'] : 1; ?>
                                <input type="checkbox" class="form-check-input" name="wa_templates[<?php echo $idx; ?>][is_active]" value="1"
                                  <?php echo set_checkbox('wa_templates['.$idx.'][is_active]', '1', $active === 1); ?>>
                              </td>
                              <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger wa-remove-row">Remove</button>
                              </td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                        </div>
                      </div>

                      <div class="border-top pt-3 mt-3">
                        <div class="row gx-3 align-items-end">
                          <div class="col-md-4">
                            <label class="form-label">Test Mobile</label>
                            <input type="text" class="form-control" name="notif_test[wa_mobile]" value="" placeholder="9999999999">
                          </div>
                          <div class="col-md-5">
                            <label class="form-label">Template Key</label>
                            <input type="text" class="form-control" name="notif_test[wa_template_key]" value="" placeholder="order_placed">
                          </div>
                          <div class="col-md-3">
                            <button type="button" class="btn btn-primary w-100 notif-test-btn" data-test="whatsapp">
                              Send Test WhatsApp
                            </button>
                          </div>
                        </div>
                        <small class="text-muted d-block mt-2">Test sending uses saved settings + template by key.</small>
                      </div>
                    </div>
                  </div>
                  </div>
                </div>

                <!-- SMS panel -->
                <div class="notif-panel" id="notif_sms">
                  <div class="notif-panel-card">
                  <div class="notif-panel-head mb-2">
                    <div>
                      <h6 class="mb-0">SMS</h6>
                      <small class="text-muted">Configure SMS gateway endpoint and parameters.</small>
                    </div>
                    <div class="notif-switch">
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input notif-toggle" type="checkbox" id="sms_enabled" name="notif[sms_enabled]" value="1"
                        <?php echo set_checkbox('notif[sms_enabled]', '1', $sms_enabled); ?>>
                      <label class="form-check-label" for="sms_enabled">Enabled</label>
                    </div>
                    </div>
                  </div>

                  <div class="border-top pt-3 mt-2 notif-section" data-toggle-id="sms_enabled"
                    style="display: <?php echo $sms_enabled ? 'block' : 'none'; ?>;">
                    <div class="row gx-3">
                      <div class="col-12">
                        <div class="alert alert-light border mb-3">
                          <div class="fw-semibold mb-1">Add params (easy)</div>
                          <small class="text-muted">Add your provider params like <code>user</code>, <code>pass</code>, <code>sender</code>, <code>phone</code>, <code>text</code>. You can use <code>{{mobile}}</code> and <code>{{message}}</code>.</small>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">Provider Name</label>
                          <input type="text" class="form-control" name="notif[sms_provider_name]"
                            value="<?php echo set_value('notif[sms_provider_name]', $notif['sms_provider_name'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">HTTP Method</label>
                          <?php $sms_method = strtoupper((string)set_value('notif[sms_http_method]', $notif['sms_http_method'] ?? 'GET')); ?>
                          <select class="form-select" name="notif[sms_http_method]">
                            <option value="GET" <?php echo ($sms_method === 'GET' ? 'selected' : ''); ?>>GET</option>
                            <option value="POST" <?php echo ($sms_method === 'POST' ? 'selected' : ''); ?>>POST</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="mb-3">
                          <label class="form-label">Endpoint URL <span class="text-danger">*</span></label>
                          <input type="text" class="form-control notif-required-sms" name="notif[sms_endpoint_url]"
                            value="<?php echo set_value('notif[sms_endpoint_url]', $notif['sms_endpoint_url'] ?? ''); ?>"
                            placeholder="https://sms.provider.com/api/send">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">Headers JSON (optional)</label>
                          <textarea class="form-control" rows="5" name="notif[sms_headers_json]"
                            placeholder='{\"Authorization\":\"Bearer ...\"}'><?php echo set_value('notif[sms_headers_json]', isset($notif['sms_headers_json']) ? json_encode($notif['sms_headers_json']) : ''); ?></textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label">Default Params (optional)</label>
                          <?php
                            // set_value() is unreliable for array field names like notif[...]
                            if (isset($_POST['notif']) && is_array($_POST['notif']) && array_key_exists('sms_default_params_json', $_POST['notif'])) {
                              $sms_defaults_json = (string)$_POST['notif']['sms_default_params_json'];
                            } else {
                              $sms_defaults_json = isset($notif['sms_default_params_json']) ? json_encode($notif['sms_default_params_json']) : '';
                            }
                          ?>
                          <input type="hidden" name="notif[sms_default_params_json]" id="sms_default_params_json" value="<?php echo htmlspecialchars($sms_defaults_json); ?>">
                          <div class="kv-table" id="smsDefaultParams" data-json="<?php echo htmlspecialchars($sms_defaults_json); ?>">
                            <div class="kv-hint mb-2">Add parameters like <code>phone</code>, <code>text</code>, <code>sender</code>. You can use tokens like <code>{{mobile}}</code> and <code>{{message}}</code>.</div>
                            <div class="kv-actions mb-2">
                              <button type="button" class="btn btn-sm btn-outline-secondary" data-kv-add="smsDefaultParams">Add Param</button>
                            </div>
                            <div class="kv-rows"></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="border-top pt-3 mt-2">
                      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <h6 class="mb-0">SMS Templates</h6>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="smsAddRow">Add Template</button>
                      </div>

                      <?php $sms_rows = isset($sms_templates) && is_array($sms_templates) && !empty($sms_templates) ? $sms_templates : [[]]; ?>
                      <div class="notif-table">
                        <div class="table-responsive">
                          <table class="table table-sm align-middle">
                            <thead>
                              <tr>
                                <th style="min-width: 170px;">Type (Event)</th>
                                <th style="min-width: 160px;">Template Key</th>
                                <th style="min-width: 420px;">Message Template</th>
                                <th style="width: 80px;">Active</th>
                                <th style="width: 80px;"></th>
                              </tr>
                            </thead>
                            <tbody id="smsTemplateRows">
                              <?php foreach ($sms_rows as $idx => $t): ?>
                              <tr>
                                <td>
                                  <?php
                                    $sel = isset($_POST['sms_templates'][$idx]['event_key'])
                                      ? (string)$_POST['sms_templates'][$idx]['event_key']
                                      : (string)($t['event_key'] ?? '');
                                  ?>
                                  <select class="form-select form-select-sm" name="sms_templates[<?php echo $idx; ?>][event_key]">
                                    <?php foreach ($event_options as $opt): ?>
                                      <option value="<?php echo htmlspecialchars($opt['value']); ?>" <?php echo ($sel !== '' && $sel === $opt['value']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($opt['label']); ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>
                                </td>
                                <td>
                                  <input type="text" class="form-control form-control-sm" name="sms_templates[<?php echo $idx; ?>][template_key]"
                                    value="<?php echo set_value('sms_templates['.$idx.'][template_key]', $t['template_key'] ?? ''); ?>" placeholder="otp_login">
                                </td>
                                <td>
                                  <textarea class="form-control form-control-sm" rows="2" name="sms_templates[<?php echo $idx; ?>][message_template]"
                                    placeholder="Your OTP is {{otp}} VARITTY"><?php echo set_value('sms_templates['.$idx.'][message_template]', $t['message_template'] ?? ''); ?></textarea>
                                  <small class="text-muted">Use tokens like <code>{{otp}}</code>, <code>{{order_id}}</code>.</small>
                                </td>
                                <td class="text-center">
                                  <?php $active = isset($t['is_active']) ? (int)$t['is_active'] : 1; ?>
                                  <input type="checkbox" class="form-check-input" name="sms_templates[<?php echo $idx; ?>][is_active]" value="1"
                                    <?php echo set_checkbox('sms_templates['.$idx.'][is_active]', '1', $active === 1); ?>>
                                </td>
                                <td class="text-end">
                                  <button type="button" class="btn btn-sm btn-outline-danger sms-remove-row">Remove</button>
                                </td>
                              </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="border-top pt-3 mt-2">
                      <div class="row gx-3 align-items-end">
                        <div class="col-md-4">
                          <label class="form-label">Test Mobile</label>
                          <input type="text" class="form-control" name="notif_test[sms_mobile]" value="" placeholder="9999999999">
                        </div>
                        <div class="col-md-5">
                          <label class="form-label">Template Key</label>
                          <input type="text" class="form-control" name="notif_test[sms_template_key]" value="" placeholder="otp_login">
                        </div>
                        <div class="col-md-3">
                          <button type="button" class="btn btn-primary w-100 notif-test-btn" data-test="sms">
                            Send Test SMS
                          </button>
                        </div>
                      </div>
                      <div class="mt-3">
                        <label class="form-label">Variables (optional)</label>
                        <input type="hidden" name="notif_test[sms_vars_json]" id="sms_test_vars_json" value="">
                        <div class="kv-table" id="smsTestVars" data-json="">
                          <div class="kv-hint mb-2">Add variables like <code>otp</code> = <code>123456</code>. They will replace tokens in the template.</div>
                          <div class="kv-actions mb-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-kv-add="smsTestVars">Add Var</button>
                          </div>
                          <div class="kv-rows"></div>
                        </div>
                      </div>
                      <small class="text-muted d-block mt-2">Test sending uses saved settings for this vendor.</small>
                    </div>
                  </div>
                  </div>
                </div>

                <div class="alert alert-info mt-3 mb-0" id="notifTestResult" style="display:none;"></div>
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

  // Meta Description Character Counter
  var metaDescTextarea = document.getElementById('meta_description');
  var metaDescCount = document.getElementById('meta_desc_count');
  if (metaDescTextarea && metaDescCount) {
    function updateMetaDescCount() {
      var length = metaDescTextarea.value.length;
      metaDescCount.textContent = length;
      if (length > 160) {
        metaDescCount.classList.remove('badge-primary');
        metaDescCount.classList.add('badge-warning');
      } else if (length >= 150) {
        metaDescCount.classList.remove('badge-warning');
        metaDescCount.classList.add('badge-success');
      } else {
        metaDescCount.classList.remove('badge-warning', 'badge-success');
        metaDescCount.classList.add('badge-primary');
      }
    }
    metaDescTextarea.addEventListener('input', updateMetaDescCount);
    updateMetaDescCount(); // Initialize count
  }

  // Notifications - channel switcher
  var channelButtons = document.querySelectorAll('#notifChannelList [data-channel]');
  var panels = {
    email: document.getElementById('notif_email'),
    whatsapp: document.getElementById('notif_whatsapp'),
    sms: document.getElementById('notif_sms')
  };
  channelButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      channelButtons.forEach(function(b) { b.classList.remove('active'); });
      this.classList.add('active');
      var ch = this.getAttribute('data-channel');
      Object.keys(panels).forEach(function(k) {
        if (panels[k]) panels[k].classList.remove('active');
      });
      if (panels[ch]) panels[ch].classList.add('active');
    });
  });

  // Notifications - toggle sections by switch
  function toggleNotifSection(toggleId) {
    var toggle = document.getElementById(toggleId);
    var section = document.querySelector('.notif-section[data-toggle-id=\"' + toggleId + '\"]');
    if (!toggle || !section) return;
    section.style.display = toggle.checked ? 'block' : 'none';

    var cls = '';
    if (toggleId === 'email_enabled') cls = '.notif-required-email';
    if (toggleId === 'whatsapp_enabled') cls = '.notif-required-whatsapp';
    if (toggleId === 'sms_enabled') cls = '.notif-required-sms';

    if (cls) {
      var requiredFields = section.querySelectorAll(cls);
      requiredFields.forEach(function(field) {
        if (toggle.checked) {
          field.setAttribute('required', 'required');
        } else {
          field.removeAttribute('required');
        }
      });
    }
  }

  ['email_enabled','whatsapp_enabled','sms_enabled'].forEach(function(id) {
    var t = document.getElementById(id);
    if (t) {
      toggleNotifSection(id);
      t.addEventListener('change', function() { toggleNotifSection(id); });
    }
  });

  // WhatsApp templates add/remove rows (simple client-side)
  var waAddRowBtn = document.getElementById('waAddRow');
  var waRows = document.getElementById('waTemplateRows');
  function waNextIndex() {
    if (!waRows) return 0;
    var inputs = waRows.querySelectorAll('input[name^=\"wa_templates[\"]');
    var max = -1;
    inputs.forEach(function(i) {
      var m = i.name.match(/^wa_templates\\[(\\d+)\\]/);
      if (m && m[1]) max = Math.max(max, parseInt(m[1], 10));
    });
    return max + 1;
  }

  function waBindRemoveButtons() {
    if (!waRows) return;
    waRows.querySelectorAll('.wa-remove-row').forEach(function(btn) {
      btn.onclick = function() {
        var tr = this.closest('tr');
        if (tr) tr.remove();
      };
    });
  }
  waBindRemoveButtons();

  if (waAddRowBtn && waRows) {
    waAddRowBtn.addEventListener('click', function() {
      var idx = waNextIndex();
      var tr = document.createElement('tr');
      tr.innerHTML = ''
        + '<td><?php echo str_replace("\n", "", addslashes("<select class=\"form-select form-select-sm\" name=\"wa_templates[__IDX__][event_key]\">".implode("", array_map(function($o){ return "<option value=\\\"".htmlspecialchars($o["value"], ENT_QUOTES)."\\\">".htmlspecialchars($o["label"], ENT_QUOTES)."</option>"; }, $event_options))."</select>")); ?>'.replace('__IDX__', idx) + '</td>'
        + '<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"wa_templates[' + idx + '][template_key]\" placeholder=\"order_placed\"></td>'
        + '<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"wa_templates[' + idx + '][template_name]\" placeholder=\"provider_template_name\"></td>'
        + '<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"wa_templates[' + idx + '][language]\" placeholder=\"en\"></td>'
        + '<td>'
        +   '<input type=\"hidden\" name=\"wa_templates[' + idx + '][param_map_json]\" class=\"wa_param_map_json\" value=\"\">'
        +   '<div class=\"kv-table waParamMap\" data-json=\"\">'
        +     '<div class=\"kv-actions mb-2\"><button type=\"button\" class=\"btn btn-sm btn-outline-secondary wa-add-param\">Add</button></div>'
        +     '<div class=\"kv-rows\"></div>'
        +   '</div>'
        + '</td>'
        + '<td class=\"text-center\"><input type=\"checkbox\" class=\"form-check-input\" name=\"wa_templates[' + idx + '][is_active]\" value=\"1\" checked></td>'
        + '<td class=\"text-end\"><button type=\"button\" class=\"btn btn-sm btn-outline-danger wa-remove-row\">Remove</button></td>';
      waRows.appendChild(tr);
      waBindRemoveButtons();
    });
  }

  // Test buttons - wired to AJAX endpoints (added in controller/routes)
  var testBtns = document.querySelectorAll('.notif-test-btn');
  var resultBox = document.getElementById('notifTestResult');
  function showNotifResult(type, message, ok) {
    if (!resultBox) return;
    resultBox.style.display = 'block';
    resultBox.classList.remove('alert-success', 'alert-danger', 'alert-info');
    resultBox.classList.add(ok ? 'alert-success' : 'alert-danger');
    resultBox.textContent = message || (ok ? (type + ' sent') : (type + ' failed'));
  }

  function postJson(url, data) {
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: new URLSearchParams(data).toString()
    }).then(function(r) { return r.json(); });
  }

  testBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      var type = this.getAttribute('data-test');
      var base = '<?php echo base_url('erp-admin/vendors'); ?>';
      var vendorId = '<?php echo (int)$vendor['id']; ?>';

      if (type === 'email') {
        var to = document.querySelector('input[name=\"notif_test[email_to]\"]')?.value || '';
        postJson(base + '/test_email/' + vendorId, { email_to: to })
          .then(function(res) { showNotifResult('Email', res.message, res.status === 'success'); })
          .catch(function() { showNotifResult('Email', 'Request failed', false); });
      }
      if (type === 'whatsapp') {
        var mobile = document.querySelector('input[name=\"notif_test[wa_mobile]\"]')?.value || '';
        var key = document.querySelector('input[name=\"notif_test[wa_template_key]\"]')?.value || '';
        postJson(base + '/test_whatsapp/' + vendorId, { mobile: mobile, template_key: key })
          .then(function(res) {
            var msg = res.message || '';
            if (res.http_code) {
              msg += (msg ? ' | ' : '') + 'HTTP: ' + res.http_code;
            }
            if (res.response) {
              msg += (msg ? ' | ' : '') + 'Gateway: ' + String(res.response).trim();
            }
            showNotifResult('WhatsApp', msg || 'Done', res.status === 'success');
          })
          .catch(function() { showNotifResult('WhatsApp', 'Request failed', false); });
      }
      if (type === 'sms') {
        var sm = document.querySelector('input[name=\"notif_test[sms_mobile]\"]')?.value || '';
        var key = document.querySelector('input[name=\"notif_test[sms_template_key]\"]')?.value || '';
        var varsJson = document.getElementById('sms_test_vars_json')?.value || '';
        postJson(base + '/test_sms/' + vendorId, { mobile: sm, template_key: key, vars_json: varsJson })
          .then(function(res) {
            var msg = res.message || '';
            if (res.otp) {
              msg += (msg ? ' | ' : '') + 'OTP: ' + res.otp;
            }
            if (res.response) {
              msg += (msg ? ' | ' : '') + 'Gateway: ' + String(res.response).trim();
            }
            showNotifResult('SMS', msg || 'Done', res.status === 'success');
          })
          .catch(function() { showNotifResult('SMS', 'Request failed', false); });
      }
    });
  });

  // Key/Value UI helpers (stored as JSON in hidden inputs)
  function safeJsonParse(str) {
    try {
      if (!str) return {};
      var obj = JSON.parse(str);
      // If it was double-encoded (e.g. "\"{...}\""), parse again.
      if (typeof obj === 'string') {
        var s = obj.trim();
        if ((s.startsWith('{') && s.endsWith('}')) || (s.startsWith('[') && s.endsWith(']'))) {
          obj = JSON.parse(s);
        }
      }
      if (obj && typeof obj === 'object' && !Array.isArray(obj)) return obj;
      return {};
    } catch (e) {
      return {};
    }
  }

  function renderKvTable(container, hiddenInput) {
    if (!container) return;
    var rowsWrap = container.querySelector('.kv-rows');
    if (!rowsWrap) return;
    rowsWrap.innerHTML = '';

    var obj = safeJsonParse(container.getAttribute('data-json') || (hiddenInput ? hiddenInput.value : ''));
    var keys = Object.keys(obj || {});
    if (keys.length === 0) {
      addKvRow(rowsWrap, '', '');
    } else {
      keys.forEach(function(k) {
        addKvRow(rowsWrap, k, (obj[k] !== null && obj[k] !== undefined) ? String(obj[k]) : '');
      });
    }

    syncKvToHidden(rowsWrap, hiddenInput, container);
  }

  function addKvRow(rowsWrap, k, v) {
    var row = document.createElement('div');
    row.className = 'kv-row';
    row.innerHTML =
      '<input type=\"text\" class=\"form-control form-control-sm kv-key\" placeholder=\"key\" value=\"' + escapeHtml(k) + '\">' +
      '<input type=\"text\" class=\"form-control form-control-sm kv-val\" placeholder=\"value\" value=\"' + escapeHtml(v) + '\">' +
      '<button type=\"button\" class=\"btn btn-sm btn-outline-danger kv-del\">Remove</button>';
    rowsWrap.appendChild(row);
  }

  function escapeHtml(s) {
    return String(s || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/\"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function syncKvToHidden(rowsWrap, hiddenInput, container) {
    if (!hiddenInput) return;
    var obj = {};
    rowsWrap.querySelectorAll('.kv-row').forEach(function(r) {
      var key = (r.querySelector('.kv-key')?.value || '').trim();
      var val = (r.querySelector('.kv-val')?.value || '').trim();
      if (key !== '') obj[key] = val;
    });
    var json = Object.keys(obj).length ? JSON.stringify(obj) : '';
    hiddenInput.value = json;
    if (container) container.setAttribute('data-json', json);
  }

  function bindKvTable(containerId, hiddenId) {
    var container = document.getElementById(containerId);
    var hidden = document.getElementById(hiddenId);
    if (!container || !hidden) return;
    renderKvTable(container, hidden);

    container.addEventListener('click', function(e) {
      var addBtn = e.target.closest('[data-kv-add]');
      if (addBtn) {
        var rowsWrap = container.querySelector('.kv-rows');
        if (!rowsWrap) return;
        addKvRow(rowsWrap, '', '');
        syncKvToHidden(rowsWrap, hidden, container);
        return;
      }
      if (e.target.classList.contains('kv-del')) {
        var row = e.target.closest('.kv-row');
        var rowsWrap2 = container.querySelector('.kv-rows');
        if (row) row.remove();
        if (rowsWrap2) syncKvToHidden(rowsWrap2, hidden, container);
      }
    });

    container.addEventListener('input', function() {
      var rowsWrap3 = container.querySelector('.kv-rows');
      if (rowsWrap3) syncKvToHidden(rowsWrap3, hidden, container);
    });
  }

  bindKvTable('waDefaultParams', 'wa_default_params_json');
  bindKvTable('smsDefaultParams', 'sms_default_params_json');
  bindKvTable('smsTestVars', 'sms_test_vars_json');

  // SMS templates add/remove
  var smsAddRowBtn = document.getElementById('smsAddRow');
  var smsRows = document.getElementById('smsTemplateRows');
  function smsNextIndex() {
    if (!smsRows) return 0;
    var inputs = smsRows.querySelectorAll('input[name^=\"sms_templates[\"]');
    var max = -1;
    inputs.forEach(function(i) {
      var m = i.name.match(/^sms_templates\\[(\\d+)\\]/);
      if (m && m[1]) max = Math.max(max, parseInt(m[1], 10));
    });
    return max + 1;
  }
  function smsBindRemove() {
    if (!smsRows) return;
    smsRows.querySelectorAll('.sms-remove-row').forEach(function(btn) {
      btn.onclick = function() {
        var tr = this.closest('tr');
        if (tr) tr.remove();
      };
    });
  }
  smsBindRemove();
  if (smsAddRowBtn && smsRows) {
    smsAddRowBtn.addEventListener('click', function() {
      var idx = smsNextIndex();
      var tr = document.createElement('tr');
      tr.innerHTML = ''
        + '<td><?php echo str_replace("\n", "", addslashes("<select class=\"form-select form-select-sm\" name=\"sms_templates[__IDX__][event_key]\">".implode("", array_map(function($o){ return "<option value=\\\"".htmlspecialchars($o["value"], ENT_QUOTES)."\\\">".htmlspecialchars($o["label"], ENT_QUOTES)."</option>"; }, $event_options))."</select>")); ?>'.replace('__IDX__', idx) + '</td>'
        + '<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"sms_templates[' + idx + '][template_key]\" placeholder=\"otp_login\"></td>'
        + '<td><textarea class=\"form-control form-control-sm\" rows=\"2\" name=\"sms_templates[' + idx + '][message_template]\" placeholder=\"Your OTP is {{otp}} VARITTY\"></textarea><small class=\"text-muted\">Use tokens like <code>{{otp}}</code>.</small></td>'
        + '<td class=\"text-center\"><input type=\"checkbox\" class=\"form-check-input\" name=\"sms_templates[' + idx + '][is_active]\" value=\"1\" checked></td>'
        + '<td class=\"text-end\"><button type=\"button\" class=\"btn btn-sm btn-outline-danger sms-remove-row\">Remove</button></td>';
      smsRows.appendChild(tr);
      smsBindRemove();
    });
  }

  // Email templates add/remove
  var emailAddRowBtn = document.getElementById('emailAddRow');
  var emailRows = document.getElementById('emailTemplateRows');
  var emailDeletedWrap = document.getElementById('emailTemplatesDeletedWrap');
  function emailNextIndex() {
    if (!emailRows) return 0;
    var inputs = emailRows.querySelectorAll('input[name^=\"email_templates[\"]');
    var max = -1;
    inputs.forEach(function(i) {
      var m = i.name.match(/^email_templates\\[(\\d+)\\]/);
      if (m && m[1]) max = Math.max(max, parseInt(m[1], 10));
    });
    return max + 1;
  }
  function emailBindRemove() {
    if (!emailRows) return;
    emailRows.querySelectorAll('.email-remove-row').forEach(function(btn) {
      btn.onclick = function() {
        var tr = this.closest('tr');
        if (tr && emailDeletedWrap) {
          var sel = tr.querySelector('select[name^="email_templates["][name$="[event_key]"]');
          var eventKey = sel ? (sel.value || '').trim() : '';
          var audSel = tr.querySelector('select[name^="email_templates["][name$="[audience]"]');
          var audience = audSel ? (audSel.value || '').trim().toLowerCase() : 'user';
          if (audience !== 'vendor') audience = 'user';
          if (eventKey !== '') {
            // IMPORTANT: keep event_key + audience paired in ONE array item.
            // Otherwise PHP parses them as separate array elements and deletion becomes unsafe.
            var hid = document.createElement('input');
            hid.type = 'hidden';
            hid.name = 'email_templates_deleted[]';
            hid.value = eventKey + '|' + audience;
            emailDeletedWrap.appendChild(hid);
          }
        }
        if (tr) tr.remove();
      };
    });
  }
  emailBindRemove();
  if (emailAddRowBtn && emailRows) {
    emailAddRowBtn.addEventListener('click', function() {
      var idx = emailNextIndex();
      var tr = document.createElement('tr');
      tr.innerHTML = ''
        + '<td><?php echo str_replace("\n", "", addslashes("<select class=\"form-select form-select-sm\" name=\"email_templates[__IDX__][event_key]\">".implode("", array_map(function($o){ return "<option value=\\\"".htmlspecialchars($o["value"], ENT_QUOTES)."\\\">".htmlspecialchars($o["label"], ENT_QUOTES)."</option>"; }, $event_options))."</select>")); ?>'.replace('__IDX__', idx) + '</td>'
        + '<td><select class=\"form-select form-select-sm email-audience\" name=\"email_templates[' + idx + '][audience]\"><option value=\"user\" selected>User</option><option value=\"vendor\">Vendor</option></select></td>'
        + '<td><input type=\"text\" class=\"form-control form-control-sm email-to-vendor\" name=\"email_templates[' + idx + '][to_emails]\" placeholder=\"vendor@example.com, ops@example.com\"><small class=\"text-muted\">Used only for Audience=Vendor.</small></td>'
        + '<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"email_templates[' + idx + '][cc_emails]\" placeholder=\"cc1@example.com, cc2@example.com\"></td>'
        + '<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"email_templates[' + idx + '][email_subject]\" placeholder=\"Your order {{order_unique_id}} placed\"></td>'
        + '<td><textarea class=\"form-control form-control-sm\" rows=\"2\" name=\"email_templates[' + idx + '][email_html]\" placeholder=\"<p>Hello {{customer_name}}</p>\"></textarea></td>'
        + '<td class=\"text-center\"><input type=\"checkbox\" class=\"form-check-input\" name=\"email_templates[' + idx + '][is_active]\" value=\"1\" checked></td>'
        + '<td class=\"text-end\"><button type=\"button\" class=\"btn btn-sm btn-outline-danger email-remove-row\">Remove</button></td>';
      emailRows.appendChild(tr);
      emailBindRemove();
    });
  }

  // Toggle vendor To field visibility based on audience
  document.addEventListener('change', function(e){
    if (!e.target || !e.target.classList || !e.target.classList.contains('email-audience')) return;
    var tr = e.target.closest('tr');
    if (!tr) return;
    var toBox = tr.querySelector('.email-to-vendor');
    if (!toBox) return;
    var isVendor = (String(e.target.value || '').toLowerCase() === 'vendor');
    toBox.disabled = !isVendor;
    if (!isVendor) {
      toBox.value = toBox.value; // keep value but disable
    }
  });

  // Initialize state on load
  document.querySelectorAll('.email-audience').forEach(function(sel){
    var tr = sel.closest('tr');
    if (!tr) return;
    var toBox = tr.querySelector('.email-to-vendor');
    if (!toBox) return;
    var isVendor = (String(sel.value || '').toLowerCase() === 'vendor');
    toBox.disabled = !isVendor;
  });

  // WhatsApp template params per-row
  function initWaParamMaps() {
    document.querySelectorAll('.waParamMap').forEach(function(box) {
      var hidden = box.parentElement.querySelector('input.wa_param_map_json');
      if (!hidden) return;
      box.innerHTML = box.innerHTML || '';
      var rowsWrap = box.querySelector('.kv-rows');
      if (!rowsWrap) return;

      // render
      var obj = safeJsonParse(box.getAttribute('data-json') || hidden.value);
      rowsWrap.innerHTML = '';
      var keys = Object.keys(obj || {});
      if (keys.length === 0) {
        addKvRow(rowsWrap, '', '');
      } else {
        keys.forEach(function(k) {
          addKvRow(rowsWrap, k, (obj[k] !== null && obj[k] !== undefined) ? String(obj[k]) : '');
        });
      }
      syncKvToHidden(rowsWrap, hidden, box);

      // bind add/remove/input
      var addBtn = box.querySelector('.wa-add-param');
      if (addBtn) {
        addBtn.onclick = function() {
          addKvRow(rowsWrap, '', '');
          syncKvToHidden(rowsWrap, hidden, box);
        };
      }
      box.addEventListener('click', function(e) {
        if (e.target.classList.contains('kv-del')) {
          var row = e.target.closest('.kv-row');
          if (row) row.remove();
          syncKvToHidden(rowsWrap, hidden, box);
        }
      });
      box.addEventListener('input', function() {
        syncKvToHidden(rowsWrap, hidden, box);
      });
    });
  }
  initWaParamMaps();

  // Re-init template param maps on row add
  if (waAddRowBtn && waRows) {
    waAddRowBtn.addEventListener('click', function() {
      setTimeout(initWaParamMaps, 0);
    });
  }

  // Ensure KV tables are synced before submit
  var mainForm = document.querySelector('form');
  if (mainForm) {
    mainForm.addEventListener('submit', function() {
      // Do NOT re-render here (can overwrite user inputs). Just sync current UI -> hidden JSON.
      var waC = document.getElementById('waDefaultParams');
      var waH = document.getElementById('wa_default_params_json');
      if (waC && waH) {
        var waRowsWrap = waC.querySelector('.kv-rows');
        if (waRowsWrap) syncKvToHidden(waRowsWrap, waH, waC);
      }

      var smsC = document.getElementById('smsDefaultParams');
      var smsH = document.getElementById('sms_default_params_json');
      if (smsC && smsH) {
        var smsRowsWrap = smsC.querySelector('.kv-rows');
        if (smsRowsWrap) syncKvToHidden(smsRowsWrap, smsH, smsC);
      }

      // Sync per-template param maps
      document.querySelectorAll('.waParamMap').forEach(function(box) {
        var hidden = box.parentElement.querySelector('input.wa_param_map_json');
        var rowsWrap = box.querySelector('.kv-rows');
        if (hidden && rowsWrap) syncKvToHidden(rowsWrap, hidden, box);
      });
    });
  }
});
</script>
