<?php
	$item = (isset($item) && is_array($item)) ? $item : array();
	$is_edit = !empty($item['id']);
	$action = $is_edit ? base_url('purchase-in/warehouses/edit/' . (int)$item['id']) : base_url('purchase-in/warehouses/add');
	$v = function($key, $default = '') use ($item, $is_edit) {
		if ($is_edit && isset($item[$key]) && $item[$key] !== null && $item[$key] !== '') {
			return set_value($key, $item[$key]);
		}
		return set_value($key, $default);
	};
	$selected_companies = $this->input->post('company_ids');
	if (!is_array($selected_companies)) {
		$selected_companies = ($is_edit && !empty($item['company_ids'])) ? $item['company_ids'] : array();
	}
	$selected_companies = array_map('intval', $selected_companies);
	$status_val = $is_edit ? (string)(int)(isset($item['status']) ? $item['status'] : 1) : '1';
	if ($this->input->post('status') !== NULL) {
		$status_val = (string)$this->input->post('status');
	}
?>
<style>
.pi-form .pi-section-title { color: var(--vendor-primary, #6f42c1); font-size: 0.95rem; font-weight: 650; margin-bottom: 0.85rem; }
.pi-form .form-label { font-size: 0.8rem; font-weight: 600; margin-bottom: 0.25rem; }
.pi-form .card { border: 1px solid #e6ebf2; border-radius: 10px; }
.pi-form textarea.form-control { min-height: 70px; }
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0"><?php echo $is_edit ? 'Edit Warehouse' : 'Add Warehouse'; ?></h6>
		<small class="text-muted">Warehouse Information</small>
	</div>
	<a href="<?php echo base_url('purchase-in/warehouses'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
</div>

<?php echo form_open($action, array('class' => 'pi-form', 'autocomplete' => 'off')); ?>
<div class="row g-3">
	<div class="col-lg-6">
		<div class="card h-100">
			<div class="card-body">
				<div class="pi-section-title"><i class="isax isax-house me-1"></i>Warehouse Information</div>
				<div class="mb-2">
					<label class="form-label">Select Companies <span class="text-danger">*</span></label>
					<select name="company_ids[]" id="company_ids" class="form-select form-select-sm" multiple required>
						<?php foreach ($companies as $company): ?>
							<option value="<?php echo (int)$company['id']; ?>" <?php echo in_array((int)$company['id'], $selected_companies, TRUE) ? 'selected' : ''; ?>>
								<?php echo htmlspecialchars((string)$company['company_name']); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<small class="text-muted">Select one or more companies for this warehouse</small>
					<?php echo form_error('company_ids[]', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
				</div>
				<div class="mb-2">
					<label class="form-label">Warehouse Name <span class="text-danger">*</span></label>
					<input type="text" name="warehouse_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('warehouse_name')); ?>" required>
					<?php echo form_error('warehouse_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
				</div>
				<div class="mb-2">
					<label class="form-label">Address 1</label>
					<textarea name="address_1" class="form-control form-control-sm" rows="2"><?php echo htmlspecialchars((string)$v('address_1')); ?></textarea>
				</div>
				<div class="mb-2">
					<label class="form-label">Address 2</label>
					<textarea name="address_2" class="form-control form-control-sm" rows="2"><?php echo htmlspecialchars((string)$v('address_2')); ?></textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Address 3</label>
					<textarea name="address_3" class="form-control form-control-sm" rows="2"><?php echo htmlspecialchars((string)$v('address_3')); ?></textarea>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card h-100">
			<div class="card-body">
				<div class="pi-section-title"><i class="isax isax-location me-1"></i>Location Information</div>
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
				<div class="mb-2">
					<label class="form-label">Pincode</label>
					<input type="text" name="pincode" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$v('pincode')); ?>" maxlength="10">
				</div>
				<div class="form-check mt-2">
					<input type="hidden" name="status" value="0">
					<input class="form-check-input" type="checkbox" name="status" id="warehouse_status" value="1" <?php echo ($status_val === '1') ? 'checked' : ''; ?>>
					<label class="form-check-label" for="warehouse_status">Active</label>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
	<a href="<?php echo base_url('purchase-in/warehouses'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
	<button type="submit" class="btn btn-primary btn-sm"><i class="isax isax-save-2 me-1"></i>Save Warehouse</button>
</div>
<?php echo form_close(); ?>

<?php $this->load->view('vendor/purchase_in/_location_script'); ?>
<script>
$(document).ready(function() {
	if ($.fn.select2) {
		$('#company_ids').select2({
			theme: 'bootstrap-5',
			width: '100%',
			placeholder: 'Choose Companies...',
			allowClear: true
		});
	}
});
</script>
