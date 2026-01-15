<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Add New School</h6>
	</div>
	<div>
		<a href="<?php echo base_url($vendor_domain . '/schools'); ?>" class="btn btn-outline-secondary">
			<i class="isax isax-arrow-left me-1"></i>Back to List
		</a>
	</div>
</div>
<!-- End Header -->

<?php echo form_open_multipart($vendor_domain . '/schools/add', array('id' => 'schoolForm')); ?>

<div class="row">
	<!-- School Information -->
	<div class="col-md-12">
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">School Information</h6>
			</div>
			<div class="card-body" id="school-info-section">
				<div class="row">
					
					
					<div class="col-md-6 mb-3" id="parent_school_container" style="display: none;">
						<label class="form-label">Parent School <span class="text-danger">*</span></label>
						<select name="parent_school_id" id="parent_school_id" class="form-select">
							<option value="" selected disabled>Select Parent School</option>
							<?php if (!empty($parent_schools)): ?>
								<?php foreach ($parent_schools as $school): ?>
									<option value="<?php echo $school['id']; ?>" <?php echo set_select('parent_school_id', $school['id']); ?>>
										<?php echo htmlspecialchars($school['school_name']); ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<?php echo form_error('parent_school_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-6 mb-3">
						<label class="form-label" id="name_label">School Name <span class="text-danger">*</span></label>
						<input type="text" name="school_name" id="school_name" class="form-control" value="<?php echo set_value('school_name'); ?>" required>
						<?php echo form_error('school_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-6 mb-3">
						<label class="form-label">School Board <span class="text-danger">*</span></label>
						<div class="d-flex gap-2 align-items-start">
							<div class="flex-grow-1">
								<select name="school_board[]" id="school_board" class="form-select select2" multiple required>
									<option value="">Select Board(s)</option>
									<?php if (!empty($boards)): ?>
										<?php foreach ($boards as $board): ?>
											<option value="<?php echo $board['id']; ?>" <?php echo set_select('school_board[]', $board['id']); ?>>
												<?php echo htmlspecialchars($board['board_name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<small class="text-muted">You can select multiple boards</small>
								<?php echo form_error('school_board', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
							<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addBoardModal" style="padding: 6px;">
								<i class="isax isax-add me-1"></i>Add Board
							</button>
						</div>
					</div>
					
					<div class="col-md-6 mb-3">
						<label class="form-label">Total School Strength</label>
						<input type="number" name="total_strength" class="form-control" value="<?php echo set_value('total_strength'); ?>" min="0">
						<?php echo form_error('total_strength', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-6 mb-3">
						<label class="form-label">Affiliation No.</label>
						<input type="text" name="affiliation_no" class="form-control" value="<?php echo set_value('affiliation_no'); ?>">
						<?php echo form_error('affiliation_no', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-12 mb-3">
						<label class="form-label">School Description <span class="text-danger">*</span></label>
						<textarea name="school_description" class="form-control" rows="4" required><?php echo set_value('school_description'); ?></textarea>
						<?php echo form_error('school_description', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Address Information -->
	<div class="col-md-12">
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Address Information</h6>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12 mb-3">
						<label class="form-label">Address <span class="text-danger">*</span></label>
						<textarea name="address" class="form-control" rows="3" required><?php echo set_value('address'); ?></textarea>
						<?php echo form_error('address', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">State <span class="text-danger">*</span></label>
						<select name="state_id" id="state_id" class="form-select" required onchange="loadCities(this.value)">
							<option value="">Select State</option>
							<?php foreach ($states as $state): ?>
								<option value="<?php echo $state['id']; ?>" <?php echo set_select('state_id', $state['id']); ?>>
									<?php echo htmlspecialchars($state['name']); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?php echo form_error('state_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">City <span class="text-danger">*</span></label>
						<select name="city_id" id="city_id" class="form-select" required>
							<option value="">Select State First</option>
						</select>
						<?php echo form_error('city_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">Pincode <span class="text-danger">*</span></label>
						<input type="text" name="pincode" class="form-control" value="<?php echo set_value('pincode'); ?>" pattern="[0-9]{6}" maxlength="10" required>
						<?php echo form_error('pincode', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Admin Login Details -->
	<div class="col-md-12" id="admin-section">
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Admin Login Details</h6>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4 mb-3">
						<label class="form-label">Admin Name <span class="text-danger">*</span></label>
						<input type="text" name="admin_name" class="form-control" value="<?php echo set_value('admin_name'); ?>" required>
						<?php echo form_error('admin_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">Admin Phone <span class="text-danger">*</span></label>
						<input type="text" name="admin_phone" class="form-control" value="<?php echo set_value('admin_phone'); ?>" required>
						<?php echo form_error('admin_phone', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-4 mb-3">
						<label class="form-label">Admin Email <span class="text-danger">*</span></label>
						<input type="email" name="admin_email" class="form-control" value="<?php echo set_value('admin_email'); ?>" required>
						<?php echo form_error('admin_email', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
					
					<div class="col-md-12 mb-3">
						<label class="form-label">Admin Password <span class="text-danger">*</span></label>
						<div class="position-relative">
							<input type="password" name="admin_password" id="admin_password" class="form-control" required minlength="6">
							<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('admin_password')">
								<i class="isax isax-eye" id="admin_password-eye"></i>
							</span>
						</div>
						<small class="text-muted">Minimum 6 characters</small>
						<?php echo form_error('admin_password', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- School Images -->
	<div class="col-md-12" id="images-section">
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">School Images</h6>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Upload Images</label>
					<input type="file" name="school_images[]" class="form-control" multiple accept="image/*">
					<small class="text-muted">You can select multiple images. Supported formats: JPG, PNG, GIF, WEBP (Max 5MB each)</small>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Submit Buttons -->
	<div class="col-md-12">
		<div class="d-flex gap-2">
			<button type="submit" class="btn btn-primary">
				<i class="isax isax-tick-circle me-1"></i>Create School
			</button>
			<a href="<?php echo base_url($vendor_domain . '/schools'); ?>" class="btn btn-outline-secondary">
				<i class="isax isax-close-circle me-1"></i>Cancel
			</a>
		</div>
	</div>
</div>

<?php echo form_close(); ?>

<!-- Add Board Modal -->
<div class="modal fade" id="addBoardModal" tabindex="-1" aria-labelledby="addBoardModalLabel" aria-hidden="true">
	<div class="modal-dialog">
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
			theme: 'bootstrap-5',
			placeholder: 'Select Board(s)',
			allowClear: true,
			width: '100%'
		});
	} else {
		console.error('Select2 not loaded');
	}
	
	// Handle branch checkbox toggle
	$('#is_branch').on('change', function() {
		var isBranch = $(this).is(':checked');
		var parentContainer = $('#parent_school_container');
		var schoolBoardRow = $('#school-info-section').find('.row').eq(1); // Second row contains board field
		var schoolDescriptionRow = $('#school-info-section').find('.row').eq(2); // Third row contains description
		var adminSection = $('#admin-section');
		var imagesSection = $('#images-section');
		
		if (isBranch) {
			parentContainer.show();
			$('#parent_school_id').prop('required', true);
			// Update label
			$('#name_label').html('Branch Name <span class="text-danger">*</span>');
			// Hide school-specific fields
			schoolBoardRow.hide();
			schoolDescriptionRow.hide();
			adminSection.hide();
			imagesSection.hide();
			// Remove required from school-specific fields
			$('#school_board').prop('required', false);
			$('textarea[name="school_description"]').prop('required', false);
			$('input[name="admin_name"]').prop('required', false);
			$('input[name="admin_phone"]').prop('required', false);
			$('input[name="admin_email"]').prop('required', false);
			$('input[name="admin_password"]').prop('required', false);
		} else {
			parentContainer.hide();
			$('#parent_school_id').prop('required', false);
			// Update label
			$('#name_label').html('School Name <span class="text-danger">*</span>');
			// Show school-specific fields
			schoolBoardRow.show();
			schoolDescriptionRow.show();
			adminSection.show();
			imagesSection.show();
			// Add required back to school-specific fields
			$('#school_board').prop('required', true);
			$('textarea[name="school_description"]').prop('required', true);
			$('input[name="admin_name"]').prop('required', true);
			$('input[name="admin_phone"]').prop('required', true);
			$('input[name="admin_email"]').prop('required', true);
			$('input[name="admin_password"]').prop('required', true);
		}
	});
	
	// Trigger on page load if checkbox is already checked
	if ($('#is_branch').is(':checked')) {
		$('#is_branch').trigger('change');
	}
	
	// Load cities when state changes
	console.log('Setting up city loading handler...');
	var stateSelect = $('#state_id');
	var citySelect = $('#city_id');
	
	if (stateSelect.length === 0) {
		console.error('State select element not found!');
	} else {
		console.log('State select element found:', stateSelect.length);
	}
	
	if (citySelect.length === 0) {
		console.error('City select element not found!');
	} else {
		console.log('City select element found:', citySelect.length);
	}
	
	// Handle state change to load cities
	stateSelect.on('change', function() {
		console.log('State changed! Value:', $(this).val());
		var stateId = $(this).val();
		
		if (stateId) {
			// Show loading
			citySelect.html('<option value="">Loading cities...</option>').prop('disabled', true);
			
			// Fetch cities via AJAX (using GET to avoid CSRF issues)
			var url = '<?php echo base_url($vendor_domain . '/schools/get_cities?state_id='); ?>' + stateId;
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
							citySelect.append($('<option>', {
								value: city.id,
								text: city.name
							}));
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
		} else {
			citySelect.html('<option value="">Select City</option>').prop('disabled', true);
		}
	});
	
	console.log('City loading handler attached');
});

// Load cities function (called from inline onchange)
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
	var url = '<?php echo base_url($vendor_domain . '/schools/get_cities?state_id='); ?>' + stateId;
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
					citySelect.append($('<option>', {
						value: city.id,
						text: city.name
					}));
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
	fetch('<?php echo base_url($vendor_domain . '/schools/add_board'); ?>', {
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
			
			// Show success message
			alert('Board added successfully!');
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


