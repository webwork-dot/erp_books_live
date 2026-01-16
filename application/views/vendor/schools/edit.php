<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0 fs-14">Edit School</h6>
	</div>
	<div>
		<a href="<?php echo base_url('schools'); ?>" class="btn btn-outline-secondary btn-sm">
			<i class="isax isax-arrow-left me-1"></i>Back
		</a>
	</div>
</div>
<!-- End Header -->

<?php echo form_open_multipart(base_url('schools/edit/' . $school['id']), array('id' => 'schoolForm')); ?>

<div class="row">
	<!-- School Information -->
	<div class="col-md-12">
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">School Information</h6>
			</div>
			<div class="card-body p-2">
				<div class="row g-2">
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">School Name <span class="text-danger">*</span></label>
						<input type="text" name="school_name" class="form-control form-control-sm" value="<?php echo set_value('school_name', $school['school_name']); ?>" required>
						<?php echo form_error('school_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">School Board <span class="text-danger">*</span></label>
						<div class="d-flex gap-1 align-items-start">
							<div class="flex-grow-1">
								<select name="school_board[]" id="school_board" class="form-select form-select-sm" multiple required>
									<?php 
									// Get selected board IDs for this school
									$selected_board_ids = array();
									if (isset($school['board_ids'])) {
										$selected_board_ids = is_array($school['board_ids']) ? $school['board_ids'] : explode(',', $school['board_ids']);
									}
									?>
									<?php if (!empty($boards)): ?>
										<?php foreach ($boards as $board): ?>
											<option value="<?php echo $board['id']; ?>" <?php echo (in_array($board['id'], $selected_board_ids)) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($board['board_name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<small class="text-muted fs-12">Multiple boards</small>
								<?php echo form_error('school_board', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
							</div>
							<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBoardModal" style="padding: 4px 8px;">
								<i class="isax isax-add"></i>
							</button>
						</div>
					</div>
					
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Total Strength</label>
						<input type="number" name="total_strength" class="form-control form-control-sm" value="<?php echo set_value('total_strength', $school['total_strength']); ?>" min="0">
						<?php echo form_error('total_strength', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Affiliation No.</label>
						<input type="text" name="affiliation_no" class="form-control form-control-sm" value="<?php echo set_value('affiliation_no', $school['affiliation_no']); ?>">
						<?php echo form_error('affiliation_no', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Status <span class="text-danger">*</span></label>
						<select name="status" class="form-select form-select-sm" required>
							<option value="active" <?php echo set_select('status', 'active', $school['status'] == 'active'); ?>>Active</option>
							<option value="inactive" <?php echo set_select('status', 'inactive', $school['status'] == 'inactive'); ?>>Inactive</option>
							<option value="suspended" <?php echo set_select('status', 'suspended', $school['status'] == 'suspended'); ?>>Suspended</option>
						</select>
						<?php echo form_error('status', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-12 mb-2">
						<label class="form-label fs-13 mb-1">School Description</label>
						<textarea name="school_description" class="form-control form-control-sm" rows="2"><?php echo set_value('school_description', $school['school_description']); ?></textarea>
						<?php echo form_error('school_description', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Address Information -->
	<div class="col-md-12">
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Address Information</h6>
			</div>
			<div class="card-body p-2">
				<div class="row g-2">
					<div class="col-md-12 mb-2">
						<label class="form-label fs-13 mb-1">Address <span class="text-danger">*</span></label>
						<textarea name="address" class="form-control form-control-sm" rows="2" required><?php echo set_value('address', $school['address']); ?></textarea>
						<?php echo form_error('address', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-4 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">State <span class="text-danger">*</span></label>
						<select name="state_id" id="state_id" class="form-select form-select-sm" required onchange="loadCities(this.value)">
							<option value="">Select State</option>
							<?php foreach ($states as $state): ?>
								<option value="<?php echo $state['id']; ?>" <?php echo set_select('state_id', $state['id'], $school['state_id'] == $state['id']); ?>>
									<?php echo htmlspecialchars($state['name']); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('state_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-4 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">City <span class="text-danger">*</span></label>
						<select name="city_id" id="city_id" class="form-select form-select-sm" required>
							<option value="">Select City</option>
							<?php foreach ($cities as $city): ?>
								<option value="<?php echo $city['id']; ?>" <?php echo set_select('city_id', $city['id'], $school['city_id'] == $city['id']); ?>>
									<?php echo htmlspecialchars($city['name']); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('city_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-4 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Pincode <span class="text-danger">*</span></label>
						<input type="text" name="pincode" class="form-control form-control-sm" value="<?php echo set_value('pincode', $school['pincode']); ?>" pattern="[0-9]{6}" maxlength="10" required>
						<?php echo form_error('pincode', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Admin Login Details -->
	<div class="col-md-12">
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Admin Login Details</h6>
			</div>
			<div class="card-body p-2">
				<div class="row g-2">
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Admin Name <span class="text-danger">*</span></label>
						<input type="text" name="admin_name" class="form-control form-control-sm" value="<?php echo set_value('admin_name', $school['admin_name']); ?>" required>
						<?php echo form_error('admin_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Admin Phone <span class="text-danger">*</span></label>
						<input type="text" name="admin_phone" class="form-control form-control-sm" value="<?php echo set_value('admin_phone', $school['admin_phone']); ?>" required>
						<?php echo form_error('admin_phone', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Admin Email <span class="text-danger">*</span></label>
						<input type="email" name="admin_email" class="form-control form-control-sm" value="<?php echo set_value('admin_email', $school['admin_email']); ?>" required>
						<?php echo form_error('admin_email', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-xl-3 col-lg-4 col-md-6 mb-2">
						<label class="form-label fs-13 mb-1">Admin Password</label>
						<div class="position-relative">
							<input type="password" name="admin_password" id="admin_password" class="form-control form-control-sm" minlength="6" placeholder="Leave blank to keep current">
							<span class="position-absolute end-0 top-50 translate-middle-y pe-2" style="cursor: pointer;" onclick="togglePassword('admin_password')">
								<i class="isax isax-eye fs-14" id="admin_password-eye"></i>
							</span>
						</div>
						<small class="text-muted fs-12">Leave blank to keep current</small>
						<?php echo form_error('admin_password', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- School Images -->
	<div class="col-md-12">
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">School Images</h6>
			</div>
			<div class="card-body p-2">
				<?php if (!empty($school_images)): ?>
					<div class="row g-2 mb-2">
						<?php foreach ($school_images as $image): ?>
							<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-2">
								<div class="position-relative">
									<img src="<?php echo get_vendor_domain_url().'/'.$image['image_path']; ?>" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
									<?php if ($image['is_primary']): ?>
										<span class="badge badge-success position-absolute top-0 start-0 m-1 fs-12">Primary</span>
									<?php endif; ?>
									<a href="<?php echo base_url('schools/delete_image/' . $image['id']); ?>" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="return confirm('Are you sure you want to delete this image?');">
										<i class="isax isax-trash fs-14"></i>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				
				<div class="mb-2">
					<label class="form-label fs-13 mb-1">Upload More Images</label>
					<input type="file" name="school_images[]" class="form-control form-control-sm" multiple accept="image/*">
					<small class="text-muted fs-12">Multiple images: JPG, PNG, GIF, WEBP (Max 5MB each)</small>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Submit Buttons -->
	<div class="col-md-12 mb-4 mt-2">
		<div class="d-flex gap-2">
			<button type="submit" class="btn btn-primary btn-sm">
				<i class="isax isax-tick-circle me-1"></i>Update School
			</button>
			<a href="<?php echo base_url('schools'); ?>" class="btn btn-outline-secondary btn-sm">
				<i class="isax isax-close-circle me-1"></i>Cancel
			</a>
		</div>
	</div>
</div>

<?php echo form_close(); ?>

<!-- Add Board Modal -->
<div class="modal fade" id="addBoardModal" tabindex="-1" aria-labelledby="addBoardModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addBoardModalLabel">Add New Board</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addBoardForm">
					<div class="mb-3">
						<label class="form-label">Board Name <span class="text-danger">*</span></label>
						<input type="text" name="board_name" id="board_name" class="form-control" required>
						<div id="board_name_error" class="text-danger fs-13 mt-1" style="display: none;"></div>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="board_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="saveBoard()">Add Board</button>
			</div>
		</div>
	</div>
</div>

<script>
// Initialize Select2 for multiple board selection
$(document).ready(function () {
	if ($.fn.select2) {
		$('#school_board').select2({
			width: '100%'
		});
	} else {
		console.error('Select2 not loaded');
	}
});

// Load cities function (called from inline onchange)
var currentCityId = <?php echo $school['city_id']; ?>;
var currentStateId = <?php echo $school['state_id']; ?>;

function loadCities(stateId) {
	console.log('loadCities function called with stateId:', stateId);
	var citySelect = $('#city_id');
	
	if (!stateId) {
		citySelect.html('<option value="">Select City</option>').prop('disabled', true);
		return;
	}
	
	// Show loading
	citySelect.html('<option value="">Loading cities...</option>').prop('disabled', true);
	
	// Fetch cities via AJAX
	var url = '<?php echo base_url('schools/get_cities?state_id='); ?>' + stateId;
	console.log('Fetching cities from:', url);
	
	$.ajax({
		url: url,
		method: 'GET',
		dataType: 'json',
		success: function(data) {
			console.log('Cities loaded:', data);
			citySelect.html('<option value="">Select City</option>');
			if (data && data.length > 0) {
				$.each(data, function(index, city) {
					var option = $('<option>', {
						value: city.id,
						text: city.name
					});
					// Preserve current city selection if state matches
					if (city.id == currentCityId && stateId == currentStateId) {
						option.prop('selected', true);
					}
					citySelect.append(option);
				});
			}
			citySelect.prop('disabled', false);
		},
		error: function(xhr, status, error) {
			console.error('Error loading cities:', error);
			console.error('Response:', xhr.responseText);
			citySelect.html('<option value="">Error loading cities</option>').prop('disabled', false);
		}
	});
}

// Also set up jQuery event handler as backup
$(document).ready(function() {
	$('#state_id').on('change', function() {
		loadCities($(this).val());
	});
});

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

// Save new board
function saveBoard() {
	var boardName = document.getElementById('board_name').value.trim();
	var boardDescription = document.getElementById('board_description').value.trim();
	var errorDiv = document.getElementById('board_name_error');
	
	// Reset error
	errorDiv.style.display = 'none';
	errorDiv.textContent = '';
	
	if (!boardName) {
		errorDiv.textContent = 'Board name is required.';
		errorDiv.style.display = 'block';
		return;
	}
	
	// AJAX call to add board
	fetch('<?php echo base_url('schools/add_board'); ?>', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: 'board_name=' + encodeURIComponent(boardName) + '&description=' + encodeURIComponent(boardDescription) + '&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>'
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add new option to Select2 dropdown
			$('#school_board')
				.append(new Option(data.board.board_name, data.board.id, true, true))
				.trigger('change');
			
			// Close modal and reset form
			var modal = bootstrap.Modal.getInstance(document.getElementById('addBoardModal'));
			modal.hide();
			document.getElementById('addBoardForm').reset();
			
		// Show success message with SweetAlert
		Swal.fire({
			title: 'Success!',
			text: 'Board added successfully!',
			icon: 'success',
			confirmButtonColor: '#7539ff',
			timer: 2000,
			timerProgressBar: true
		});
	} else {
		errorDiv.textContent = data.message || 'Failed to add board.';
		errorDiv.style.display = 'block';
	}
})
.catch(error => {
	console.error('Error:', error);
	errorDiv.textContent = 'An error occurred. Please try again.';
	errorDiv.style.display = 'block';
});
}

</script>



