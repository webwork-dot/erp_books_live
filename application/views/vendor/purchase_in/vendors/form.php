<?php
	$item = (isset($item) && is_array($item)) ? $item : array();
	$is_edit = !empty($item['id']);
	$action = $is_edit ? base_url('purchase-in/vendors/edit/' . (int)$item['id']) : base_url('purchase-in/vendors/add');
	$v = function($key, $default = '') use ($item, $is_edit) {
		if ($is_edit && isset($item[$key]) && $item[$key] !== null && $item[$key] !== '') {
			return set_value($key, $item[$key]);
		}
		return set_value($key, $default);
	};
?>
<style>
.pi-form .pi-section-title { color: var(--vendor-primary, #6f42c1); font-size: 0.95rem; font-weight: 650; margin-bottom: 0.85rem; }
.pi-form .form-label { font-size: 0.8rem; font-weight: 600; margin-bottom: 0.25rem; }
.pi-form .card { border: 1px solid #e6ebf2; border-radius: 10px; }
.pi-form textarea.form-control { min-height: 78px; }
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0"><?php echo $is_edit ? 'Edit Vendor' : 'Add Vendor'; ?></h6>
		<small class="text-muted">Vendor Information</small>
	</div>
	<a href="<?php echo base_url('purchase-in/vendors'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
</div>

<?php echo form_open($action, array('class' => 'pi-form', 'autocomplete' => 'off')); ?>
<div class="row g-3">
	<div class="col-lg-6">
		<div class="card h-100">
			<div class="card-body">
				<div class="pi-section-title"><i class="isax isax-profile-2user me-1"></i>Vendor Information</div>
				<div class="mb-2">
					<label class="form-label">Vendor Name <span class="text-danger">*</span></label>
					<input type="text" name="vendor_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('vendor_name')); ?>" required>
					<?php echo form_error('vendor_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
				</div>
				<div class="mb-2">
					<label class="form-label">Contact Person Name</label>
					<input type="text" name="contact_person_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('contact_person_name')); ?>">
				</div>
				<div class="mb-2">
					<label class="form-label">Contact Person Number</label>
					<input type="text" name="contact_person_number" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('contact_person_number')); ?>">
				</div>
				<div class="mb-2">
					<label class="form-label">Email</label>
					<input type="email" name="email" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('email')); ?>">
					<?php echo form_error('email', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
				</div>
				<div class="mb-2">
					<label class="form-label">GST Name</label>
					<input type="text" name="gst_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('gst_name')); ?>">
				</div>
				<div class="mb-2">
					<label class="form-label">GST No.</label>
					<input type="text" name="gst_no" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('gst_no')); ?>">
				</div>
				<div class="mb-0">
					<label class="form-label">FSSAI No.</label>
					<input type="text" name="fssai_no" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('fssai_no')); ?>">
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card h-100">
			<div class="card-body">
				<div class="pi-section-title"><i class="isax isax-location me-1"></i>Address Information</div>
				<div class="mb-2">
					<label class="form-label">Address 1</label>
					<textarea name="address_1" class="form-control form-control-sm" rows="3"><?php echo htmlspecialchars((string)$v('address_1')); ?></textarea>
				</div>
				<div class="mb-2">
					<label class="form-label">Address 2</label>
					<textarea name="address_2" class="form-control form-control-sm" rows="3"><?php echo htmlspecialchars((string)$v('address_2')); ?></textarea>
				</div>
				<div class="row g-2 mb-2">
					<div class="col-md-6">
						<label class="form-label">State</label>
						<select name="state_id" id="state_id" class="form-select form-select-sm" onchange="loadPurchaseCities(this.value)">
							<option value="">Select State</option>
							<?php foreach ($states as $state): ?>
								<option value="<?php echo (int)$state['id']; ?>" <?php echo ((string)$v('state_id') === (string)$state['id']) ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars((string)$state['name']); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label">City</label>
						<select name="city_id" id="city_id" class="form-select form-select-sm">
							<option value="">Select City</option>
							<?php foreach ($cities as $city): ?>
								<option value="<?php echo (int)$city['id']; ?>" <?php echo ((string)$v('city_id') === (string)$city['id']) ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars((string)$city['name']); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="row g-2">
					<div class="col-md-6">
						<label class="form-label">Pincode</label>
						<input type="text" name="pincode" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('pincode')); ?>" maxlength="10">
					</div>
					<div class="col-md-6">
						<label class="form-label">State Code</label>
						<input type="text" name="state_code" id="state_code" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('state_code')); ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
	<a href="<?php echo base_url('purchase-in/vendors'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
	<button type="submit" class="btn btn-primary btn-sm"><i class="isax isax-save-2 me-1"></i>Save Vendor</button>
</div>
<?php echo form_close(); ?>

<?php $this->load->view('vendor/purchase_in/_location_script'); ?>
