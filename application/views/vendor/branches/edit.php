<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Edit Branch</h6>
	</div>
	<div>
		<a href="<?php echo base_url('branches'); ?>" class="btn btn-outline-secondary">
			<i class="isax isax-arrow-left me-1"></i>Back to List
		</a>
	</div>
</div>
<!-- End Header -->

<?php echo form_open(base_url('branches/edit/' . $branch['id']), array('id' => 'branchForm')); ?>

<div class="row">
	<!-- Branch Information -->
	<div class="col-md-12">
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Branch Information</h6>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label">Select School <span class="text-danger">*</span></label>
						<select name="school_id" id="school_id" class="form-select" required>
							<option value="">Select School</option>
							<?php if (!empty($schools)): ?>
								<?php foreach ($schools as $school): ?>
									<option value="<?php echo $school['id']; ?>" <?php echo ($branch['school_id'] == $school['id']) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($school['school_name']); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<?php echo form_error('school_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-6 mb-3">
						<label class="form-label">Branch Name <span class="text-danger">*</span></label>
						<input type="text" name="branch_name" class="form-control" value="<?php echo set_value('branch_name', $branch['branch_name']); ?>" required>
						<?php echo form_error('branch_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-12 mb-3">
						<label class="form-label">Address <span class="text-danger">*</span></label>
						<textarea name="address" class="form-control" rows="3" required><?php echo set_value('address', $branch['address']); ?></textarea>
						<?php echo form_error('address', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">State <span class="text-danger">*</span></label>
						<select name="state_id" id="state_id" class="form-select" required>
							<option value="">Select State</option>
							<?php if (!empty($states)): ?>
								<?php foreach ($states as $state): ?>
									<option value="<?php echo $state['id']; ?>" <?php echo ($branch['state_id'] == $state['id']) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($state['name']); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<?php echo form_error('state_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">City <span class="text-danger">*</span></label>
						<select name="city_id" id="city_id" class="form-select" required>
							<option value="">Select City</option>
							<?php if (!empty($cities)): ?>
								<?php foreach ($cities as $city): ?>
									<option value="<?php echo $city['id']; ?>" <?php echo ($branch['city_id'] == $city['id']) ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($city['name']); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<?php echo form_error('city_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">Pincode <span class="text-danger">*</span></label>
						<input type="text" name="pincode" class="form-control" value="<?php echo set_value('pincode', $branch['pincode']); ?>" maxlength="10" required>
						<?php echo form_error('pincode', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-6 mb-3">
						<label class="form-label">Status <span class="text-danger">*</span></label>
						<select name="status" class="form-select" required>
							<option value="active" <?php echo ($branch['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
							<option value="inactive" <?php echo ($branch['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
						</select>
						<?php echo form_error('status', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Form Actions -->
<div class="d-flex justify-content-end gap-2">
	<a href="<?php echo base_url('branches'); ?>" class="btn btn-outline-secondary">Cancel</a>
	<button type="submit" class="btn btn-primary">
		<i class="isax isax-save me-1"></i>Update Branch
	</button>
</div>

<?php echo form_close(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var currentStateId = <?php echo $branch['state_id']; ?>;
	var currentCityId = <?php echo $branch['city_id']; ?>;
	
	// Handle state change to load cities
	document.getElementById('state_id').addEventListener('change', function() {
		var stateId = this.value;
		var citySelect = document.getElementById('city_id');
		
		if (stateId) {
			// Show loading
			citySelect.innerHTML = '<option value="">Loading cities...</option>';
			citySelect.disabled = true;
			
			// Fetch cities via AJAX (using GET to avoid CSRF issues)
			fetch('<?php echo base_url('branches/get_cities?state_id='); ?>' + stateId, {
				method: 'GET',
				headers: {
					'Content-Type': 'application/json',
				}
			})
			.then(response => response.json())
			.then(data => {
				citySelect.innerHTML = '<option value="">Select City</option>';
				if (data && data.length > 0) {
					data.forEach(function(city) {
						var option = document.createElement('option');
						option.value = city.id;
						option.textContent = city.name;
						if (city.id == currentCityId && stateId == currentStateId) {
							option.selected = true;
						}
						citySelect.appendChild(option);
					});
				}
				citySelect.disabled = false;
			})
			.catch(error => {
				console.error('Error:', error);
				citySelect.innerHTML = '<option value="">Error loading cities</option>';
				citySelect.disabled = false;
			});
		} else {
			citySelect.innerHTML = '<option value="">Select City</option>';
			citySelect.disabled = true;
		}
	});
	
	// Load cities on page load if state is selected
	if (currentStateId) {
		document.getElementById('state_id').dispatchEvent(new Event('change'));
	}
});
</script>

