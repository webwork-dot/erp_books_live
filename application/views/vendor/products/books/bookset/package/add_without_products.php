<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor['domain'] . '/products/bookset?tab=without_product' : 'products/bookset?tab=without_product'); ?>"><i class="isax isax-arrow-left me-2"></i>Add Bookset without Products</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->

<style>
	.packages-container {
		max-height: 70vh;
		overflow-y: auto;
		padding-right: 10px;
	}
	.package-card {
		transition: all 0.3s ease;
		box-shadow: 0 2px 4px rgba(0,0,0,0.1);
	}
	
	.package-card .card-header {
		font-size: 0.9rem;
		font-weight: 600;
	}
	.package-card .form-label {
		font-size: 0.85rem;
		margin-bottom: 0.25rem;
	}
	.package-status-badge {
		font-size: 0.75rem;
		padding: 0.25rem 0.5rem;
	}
	/* Scrollbar styling for packages container */
	.packages-container::-webkit-scrollbar {
		width: 6px;
	}
	.packages-container::-webkit-scrollbar-track {
		background: #f1f1f1;
		border-radius: 10px;
	}
	.packages-container::-webkit-scrollbar-thumb {
		background: #888;
		border-radius: 10px;
	}
	.packages-container::-webkit-scrollbar-thumb:hover {
		background: #555;
	}
	/* Validation error styling */
	.is-invalid {
		border-color: #dc3545 !important;
	}
	.invalid-feedback {
		display: block;
		width: 100%;
		margin-top: 0.25rem;
		font-size: 0.875rem;
		color: #dc3545;
	}
	.select2-container--default .select2-selection.is-invalid {
		border-color: #dc3545 !important;
	}
</style>

<?php echo form_open(isset($current_vendor['domain']) ? base_url($current_vendor['domain'] . '/products/bookset/package/add_without_products') : base_url('products/bookset/package/add_without_products'), array('id' => 'bookset-without-products-form')); ?>

<div class="container-fluid px-0">
<!-- Bookset Basic Information -->
<div class="row mb-3">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class="border-bottom pb-3 mb-3">Bookset Information</h2>
					
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
						<div class="col-lg-4 col-md-6">
							<div class="mb-3">
								<label class="form-label">School <span class="text-danger">*</span></label>
								<select name="school_id" id="school_id" class="select" required>
									<option value="">Select School</option>
									<?php if (!empty($schools)): ?>
										<?php foreach ($schools as $school): ?>
											<option value="<?php echo $school['id']; ?>"><?php echo htmlspecialchars($school['school_name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<?php echo form_error('school_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="mb-3">
								<label class="form-label">Board <span class="text-danger">*</span></label>
								<select name="board_id" id="board_id" class="select" required>
									<option value="">Select School First</option>
								</select>
								<?php echo form_error('board_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="mb-3">
								<label class="form-label">Grade <span class="text-danger">*</span></label>
								<select name="grade_id" id="grade_id" class="select" required>
									<option value="">Select Grade</option>
									<?php if (!empty($grades)): ?>
										<?php foreach ($grades as $grade): ?>
											<option value="<?php echo $grade['id']; ?>"><?php echo htmlspecialchars($grade['name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<?php echo form_error('grade_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add Packages -->
<div class="row mb-3">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-4">
					<h2 class="mb-0">Add Packages</h2>
					<button type="button" class="btn btn-primary btn-sm" id="add_package_btn">
						<i class="isax isax-add"></i> Add Package
					</button>
				</div>
					
				<div id="packages_area" class="packages-container">
					<!-- Packages will be added here dynamically -->
				</div>
				<div class="mt-3">
					<small class="text-muted">Click "Add Package" button above to add your first package</small>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Final Bookset Details -->
<div class="row mb-3">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class="border-bottom pb-3 mb-3">Final Bookset Details</h2>
					
					<div class="row gx-3">
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Mandatory Packages <span class="text-danger" id="mandatory_packages_required_indicator" style="display: none;">*</span></label>
								<div class="input-group">
									<input type="number" name="mandatory_packages" id="mandatory_packages" class="form-control" min="0" style="max-width: 80px;" value="0">
									<span class="input-group-text bg-transparent border-start-0">is mandatory out of <strong id="mandatory_optional_total_text" class="mx-1">0</strong> mandatory + optional packages</span>
								</div>
								<small class="text-muted">Enter the number of mandatory packages required (only applicable when there are mandatory+optional packages)</small>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Status <span class="text-danger">*</span></label>
								<select name="status" id="status" class="select" required>
									<option value="active">Active</option>
									<option value="inactive">Inactive</option>
								</select>
							</div>
						</div>
					</div>
					
					<!-- Hidden inputs for packages data -->
					<input type="hidden" name="packages_data" id="packages_data" value="">
					
				<div class="row mt-4">
					<div class="col-12 text-end">
						<button type="submit" class="btn btn-primary">
							<i class="isax isax-save"></i> Save Bookset
						</button>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<?php echo form_close(); ?>

<script>
(function() {
	function initScript() {
		if (typeof window.jQuery === 'undefined') {
			setTimeout(initScript, 100);
			return;
		}
		
		var $ = window.jQuery;
		
		// Declare variables first
		var packages = [];
		var packageCounter = 0;
		
		// Load boards when school is selected
		function loadBoards(schoolId) {
			var $boardSelect = $('#board_id');
			
			if (!schoolId) {
				if ($boardSelect.length && $boardSelect.hasClass('select2-hidden-accessible')) {
					$boardSelect.select2('destroy');
				}
				$boardSelect.empty().append('<option value="">Select School First</option>');
				if ($boardSelect.hasClass('select')) {
					$boardSelect.select2();
				}
				return;
			}
			
			$.ajax({
				url: '<?php echo base_url(isset($current_vendor["domain"]) ? $current_vendor["domain"] . "/products/bookset/package/get_boards" : "products/bookset/package/get_boards"); ?>',
				type: 'GET',
				data: {
					school_id: schoolId
				},
				dataType: 'json',
				success: function(response) {
					if ($boardSelect.length && $boardSelect.hasClass('select2-hidden-accessible')) {
						$boardSelect.select2('destroy');
					}
					
					if (response.status === 'success' && response.boards && response.boards.length > 0) {
						$boardSelect.empty().append('<option value="">Select Board</option>');
						response.boards.forEach(function(board) {
							$boardSelect.append('<option value="' + board.id + '">' + board.board_name + '</option>');
						});
					} else {
						$boardSelect.empty().append('<option value="">No boards available</option>');
					}
					
					if ($boardSelect.hasClass('select')) {
						$boardSelect.select2();
					}
				},
				error: function() {
					if ($boardSelect.length && $boardSelect.hasClass('select2-hidden-accessible')) {
						$boardSelect.select2('destroy');
					}
					$boardSelect.empty().append('<option value="">Error loading boards</option>');
					if ($boardSelect.hasClass('select')) {
						$boardSelect.select2();
					}
				}
			});
		}
		
		// Bind school change event
		$('#school_id').off('change').on('change', function() {
			var schoolId = $(this).val();
			if (schoolId) {
				loadBoards(schoolId);
			} else {
				$('#board_id').empty().append('<option value="">Select School First</option>');
				if ($('#board_id').hasClass('select2-hidden-accessible')) {
					$('#board_id').select2('destroy');
				}
				if ($('#board_id').hasClass('select')) {
					$('#board_id').select2();
				}
			}
		});
		
		
		// Add new package
		window.addPackage = function() {
			packageCounter++;
			var packageIndex = packages.length;
			
			var packageHtml = `
				<div class="package-card mb-3 border border-danger rounded" id="package_${packageCounter}" data-package-status="incomplete">
					<div class="card-header bg-danger bg-opacity-10 border-bottom border-danger d-flex justify-content-between align-items-center py-2">
						<div class="d-flex align-items-center gap-2">
							<span class="badge bg-danger rounded-pill">Package ${packageIndex + 1}</span>
							<span class="package-status-badge badge bg-secondary" data-package-id="${packageCounter}">Incomplete</span>
						</div>
						<button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removePackage(${packageCounter})" title="Remove Package">
							<i class="isax isax-trash"></i>
						</button>
					</div>
					<div class="card-body p-3">
						<div class="row g-2 mb-3">
							<div class="col-md-3 col-sm-6">
								<label class="form-label small fw-semibold mb-1">Package Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control form-control-sm package-name-input" data-package-id="${packageCounter}" placeholder="Enter package name" required>
							</div>
							<div class="col-md-2 col-sm-6">
								<label class="form-label small fw-semibold mb-1">Package Price (₹) <span class="text-danger">*</span></label>
								<input type="number" class="form-control form-control-sm package-price-input" data-package-id="${packageCounter}" step="0.01" min="0" placeholder="0.00" required>
							</div>
							<div class="col-md-2 col-sm-6">
								<label class="form-label small fw-semibold mb-1">Offer Price (₹) <span class="text-danger">*</span></label>
								<input type="number" class="form-control form-control-sm package-offer-price-input" data-package-id="${packageCounter}" step="0.01" min="0" placeholder="0.00" required>
							</div>
							<div class="col-md-2 col-sm-6">
								<label class="form-label small fw-semibold mb-1">GST (%) <span class="text-danger">*</span></label>
								<input type="number" class="form-control form-control-sm package-gst-input" data-package-id="${packageCounter}" step="0.01" min="0" max="100" placeholder="0.00" required>
							</div>
							<div class="col-md-3 col-sm-6">
								<label class="form-label small fw-semibold mb-1">HSN</label>
								<input type="text" class="form-control form-control-sm package-hsn-input" data-package-id="${packageCounter}" placeholder="HSN code">
							</div>
						</div>
						<div class="row g-2 mb-3">
							<div class="col-md-6 col-sm-6">
								<label class="form-label small fw-semibold mb-1">Is It? <span class="text-danger">*</span></label>
								<select class="form-select form-select-sm package-isit-select" data-package-id="${packageCounter}" required>
									<option value="mandatory">Mandatory</option>
									<option value="optional">Optional</option>
									<option value="mandatory+optional">Mandatory + Optional</option>
								</select>
							</div>
							<div class="col-md-6 col-sm-6">
								<label class="form-label small fw-semibold mb-1">Weight (gm) <span class="text-danger">*</span></label>
								<input type="number" class="form-control form-control-sm package-weight-input" data-package-id="${packageCounter}" step="0.01" min="0" placeholder="0.00" required>
							</div>
						</div>
						<div class="row g-2 mb-3">
							<div class="col-md-12 col-sm-12">
								<label class="form-label small fw-semibold mb-1">Note</label>
								<input type="text" class="form-control form-control-sm package-note-input" data-package-id="${packageCounter}" placeholder="Optional note">
							</div>
						</div>
					</div>
				</div>
			`;
			
			$('#packages_area').append(packageHtml);
			
			// Initialize Select2 for the new package dropdowns
			$('.package-isit-select[data-package-id="' + packageCounter + '"]').select2();
			
			// Initialize package object
			packages.push({
				id: packageCounter,
				package_name: '',
				package_price: '',
				package_offer_price: '',
				gst: '',
				hsn: '',
				is_it: 'mandatory',
				weight: '',
				note: ''
			});
			
			// Bind events
			bindPackageEvents(packageCounter);
			
			// Update package status initially
			updatePackageStatus(packageCounter);
			
			// Update summary
			updatePackageSummary();
		};
		
		// Remove package
		window.removePackage = function(packageId) {
			if (packages.length <= 1) {
				alert('At least one package is required');
				return;
			}
			
			if (confirm('Are you sure you want to remove this package?')) {
				$('#package_' + packageId).fadeOut(300, function() {
					$(this).remove();
					packages = packages.filter(function(pkg) {
						return pkg.id !== packageId;
					});
					
					// Renumber packages
					renumberPackages();
					updatePackageSummary();
				});
			}
		};
		
		// Renumber packages
		function renumberPackages() {
			$('.package-card').each(function(index) {
				var $badge = $(this).find('.badge:first');
				$badge.text('Package ' + (index + 1));
			});
		}
		
		// Update package status badge
		function updatePackageStatus(packageId) {
			var packageObj = packages.find(function(p) { return p.id === packageId; });
			var $badge = $('.package-status-badge[data-package-id="' + packageId + '"]');
			
			if (!packageObj) {
				$badge.removeClass('bg-success bg-warning').addClass('bg-secondary').text('Incomplete');
				// Update card color to red
				var $card = $('#package_' + packageId);
				$card.removeClass('border-success border-warning').addClass('border-danger');
				$card.attr('data-package-status', 'incomplete');
				$card.find('.card-header').removeClass('bg-success bg-opacity-10 bg-warning bg-opacity-10 border-success border-warning').addClass('bg-danger bg-opacity-10 border-danger');
				$card.find('.badge:first').removeClass('bg-success bg-warning').addClass('bg-danger');
				return;
			}
			
			var packageNameCheck = !!packageObj.package_name && packageObj.package_name.trim() !== '';
			var packagePriceCheck = !!packageObj.package_price && packageObj.package_price !== '' && parseFloat(packageObj.package_price) >= 0;
			var offerPriceCheck = !!packageObj.package_offer_price && packageObj.package_offer_price !== '' && parseFloat(packageObj.package_offer_price) >= 0;
			var gstCheck = !!packageObj.gst && packageObj.gst !== '' && parseFloat(packageObj.gst) >= 0 && parseFloat(packageObj.gst) <= 100;
			var isItCheck = !!packageObj.is_it;
			var weightParsed = parseFloat(packageObj.weight);
			var weightCheck = !!packageObj.weight && packageObj.weight !== '' && weightParsed > 0 && !isNaN(weightParsed);
			
			var isValid = packageNameCheck && packagePriceCheck && offerPriceCheck && gstCheck && isItCheck && weightCheck;
			
			if (isValid) {
				$badge.removeClass('bg-secondary bg-warning').addClass('bg-success').text('Complete');
				// Update card color to green
				var $card = $('#package_' + packageId);
				$card.removeClass('border-danger border-warning').addClass('border-success');
				$card.attr('data-package-status', 'complete');
				$card.find('.card-header').removeClass('bg-danger bg-opacity-10 bg-warning bg-opacity-10 border-danger border-warning').addClass('bg-success bg-opacity-10 border-success');
				$card.find('.badge:first').removeClass('bg-danger bg-warning').addClass('bg-success');
			} else {
				$badge.removeClass('bg-success bg-warning').addClass('bg-secondary').text('Incomplete');
				// Update card color to red
				var $card = $('#package_' + packageId);
				$card.removeClass('border-success border-warning').addClass('border-danger');
				$card.attr('data-package-status', 'incomplete');
				$card.find('.card-header').removeClass('bg-success bg-opacity-10 bg-warning bg-opacity-10 border-success border-warning').addClass('bg-danger bg-opacity-10 border-danger');
				$card.find('.badge:first').removeClass('bg-success bg-warning').addClass('bg-danger');
			}
		}
		
		// Clear validation errors for a field
		function clearFieldError($field) {
			$field.removeClass('is-invalid');
			$field.next('.invalid-feedback').remove();
			$field.closest('.mb-3').find('.invalid-feedback').remove();
			if ($field.hasClass('select2-hidden-accessible')) {
				$field.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
			}
			$field.parent().find('.invalid-feedback').remove();
		}
		
		// Bind package events
		function bindPackageEvents(packageId) {
			// Package name
			$('.package-name-input[data-package-id="' + packageId + '"]').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.package_name = $(this).val();
					updatePackageStatus(packageId);
				}
			});
			
			// Package price
			$('.package-price-input[data-package-id="' + packageId + '"]').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.package_price = $(this).val();
					updatePackageStatus(packageId);
				}
			});
			
			// Package offer price
			$('.package-offer-price-input[data-package-id="' + packageId + '"]').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.package_offer_price = $(this).val();
					updatePackageStatus(packageId);
				}
			});
			
			// GST
			$('.package-gst-input[data-package-id="' + packageId + '"]').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.gst = $(this).val();
					updatePackageStatus(packageId);
				}
			});
			
			// HSN
			$('.package-hsn-input[data-package-id="' + packageId + '"]').on('input', function() {
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.hsn = $(this).val();
				}
			});
			
			// Is It
			$('.package-isit-select[data-package-id="' + packageId + '"]').on('change', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.is_it = $(this).val();
					updatePackageStatus(packageId);
					updatePackageSummary();
				}
			});
			
			// Weight
			$('.package-weight-input[data-package-id="' + packageId + '"]').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.weight = $(this).val();
					updatePackageStatus(packageId);
				}
			});
			
			// Note
			$('.package-note-input[data-package-id="' + packageId + '"]').on('input', function() {
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.note = $(this).val();
				}
			});
		}
		
		// Update package summary
		function updatePackageSummary() {
			var total = packages.length;
			var mandatory = packages.filter(function(p) { return p.is_it === 'mandatory'; }).length;
			var optional = packages.filter(function(p) { return p.is_it === 'optional'; }).length;
			var mandatoryOptional = packages.filter(function(p) { return p.is_it === 'mandatory+optional'; }).length;
			
			var mandatoryOptionalTotal = mandatoryOptional;
			
			$('#mandatory_optional_total_text').text(mandatoryOptionalTotal);
			$('#mandatory_packages').attr('max', mandatoryOptionalTotal);
			
			// Make mandatory_packages required only if there are mandatory+optional packages
			var $mandatoryPackagesInput = $('#mandatory_packages');
			var $requiredIndicator = $('#mandatory_packages_required_indicator');
			
			if (mandatoryOptionalTotal > 0) {
				$mandatoryPackagesInput.attr('required', 'required');
				$requiredIndicator.show();
			} else {
				$mandatoryPackagesInput.removeAttr('required');
				$requiredIndicator.hide();
				$mandatoryPackagesInput.val(0);
			}
		}
		
		// Form submission
		$('#bookset-without-products-form').on('submit', function(e) {
			// Validate Step 1 fields first
			if (!$('#school_id').val() || !$('#board_id').val() || !$('#grade_id').val()) {
				e.preventDefault();
				alert('Please fill all required fields in Bookset Information section (School, Board, Grade)');
				$('html, body').animate({
					scrollTop: $('#school_id').closest('.card').offset().top - 100
				}, 500);
				return false;
			}
			
			// Collect all package data
			packages.forEach(function(pkg) {
				pkg.package_name = $('.package-name-input[data-package-id="' + pkg.id + '"]').val();
				pkg.package_price = $('.package-price-input[data-package-id="' + pkg.id + '"]').val();
				pkg.package_offer_price = $('.package-offer-price-input[data-package-id="' + pkg.id + '"]').val();
				pkg.gst = $('.package-gst-input[data-package-id="' + pkg.id + '"]').val();
				pkg.hsn = $('.package-hsn-input[data-package-id="' + pkg.id + '"]').val();
				
				var $isItSelect = $('.package-isit-select[data-package-id="' + pkg.id + '"]');
				var isItValue = $isItSelect.val();
				if ($isItSelect.hasClass('select2-hidden-accessible')) {
					isItValue = $isItSelect.select2('val');
				}
				pkg.is_it = isItValue;
				
				var weightValue = $('.package-weight-input[data-package-id="' + pkg.id + '"]').val();
				pkg.weight = weightValue;
				pkg.note = $('.package-note-input[data-package-id="' + pkg.id + '"]').val();
			});
			
			// Validate
			if (packages.length === 0) {
				e.preventDefault();
				alert('Please add at least one package');
				return false;
			}
			
			// Remove previous error highlights
			$('.is-invalid').removeClass('is-invalid');
			$('.invalid-feedback').remove();
			
			var isValid = true;
			var firstErrorElement = null;
			var errorMessages = [];
			
			for (var i = 0; i < packages.length; i++) {
				var pkg = packages[i];
				var index = i;
				var packageErrors = [];
				
				// Check package name
				var $packageNameInput = $('.package-name-input[data-package-id="' + pkg.id + '"]');
				var packageNameValue = $packageNameInput.val();
				pkg.package_name = packageNameValue;
				var packageNameCheck = !packageNameValue || packageNameValue.trim() === '';
				
				if (packageNameCheck) {
					$packageNameInput.addClass('is-invalid');
					$packageNameInput.after('<div class="invalid-feedback">Please enter package name</div>');
					packageErrors.push('Package Name');
					if (!firstErrorElement) firstErrorElement = $packageNameInput;
				}
				
				// Check package price
				var $packagePriceInput = $('.package-price-input[data-package-id="' + pkg.id + '"]');
				var packagePriceValue = $packagePriceInput.val();
				pkg.package_price = packagePriceValue;
				var packagePriceParsed = parseFloat(packagePriceValue);
				var packagePriceCheck = !packagePriceValue || packagePriceValue === '' || packagePriceParsed < 0 || isNaN(packagePriceParsed);
				
				if (packagePriceCheck) {
					$packagePriceInput.addClass('is-invalid');
					$packagePriceInput.after('<div class="invalid-feedback">Please enter a valid package price</div>');
					packageErrors.push('Package Price');
					if (!firstErrorElement) firstErrorElement = $packagePriceInput;
				}
				
				// Check offer price
				var $offerPriceInput = $('.package-offer-price-input[data-package-id="' + pkg.id + '"]');
				var offerPriceValue = $offerPriceInput.val();
				pkg.package_offer_price = offerPriceValue;
				var offerPriceParsed = parseFloat(offerPriceValue);
				var offerPriceCheck = !offerPriceValue || offerPriceValue === '' || offerPriceParsed < 0 || isNaN(offerPriceParsed);
				
				if (offerPriceCheck) {
					$offerPriceInput.addClass('is-invalid');
					$offerPriceInput.after('<div class="invalid-feedback">Please enter a valid offer price</div>');
					packageErrors.push('Offer Price');
					if (!firstErrorElement) firstErrorElement = $offerPriceInput;
				}
				
				// Check GST
				var $gstInput = $('.package-gst-input[data-package-id="' + pkg.id + '"]');
				var gstValue = $gstInput.val();
				pkg.gst = gstValue;
				var gstParsed = parseFloat(gstValue);
				var gstCheck = !gstValue || gstValue === '' || gstParsed < 0 || gstParsed > 100 || isNaN(gstParsed);
				
				if (gstCheck) {
					$gstInput.addClass('is-invalid');
					$gstInput.after('<div class="invalid-feedback">Please enter a valid GST (0-100)</div>');
					packageErrors.push('GST');
					if (!firstErrorElement) firstErrorElement = $gstInput;
				}
				
				// Check is_it
				var $isItSelect = $('.package-isit-select[data-package-id="' + pkg.id + '"]');
				var isItValue = $isItSelect.val();
				if ($isItSelect.hasClass('select2-hidden-accessible')) {
					isItValue = $isItSelect.select2('val');
				}
				pkg.is_it = isItValue;
				
				if (!isItValue || isItValue === '' || isItValue === null || isItValue === undefined) {
					$isItSelect.addClass('is-invalid');
					if ($isItSelect.hasClass('select2-hidden-accessible')) {
						$isItSelect.next('.select2-container').find('.select2-selection').addClass('is-invalid');
					}
					$isItSelect.closest('.mb-3').find('label').after('<div class="invalid-feedback">Please select "Is It?"</div>');
					packageErrors.push('Is It?');
					if (!firstErrorElement) firstErrorElement = $isItSelect;
				}
				
				// Check weight
				var $weightInput = $('.package-weight-input[data-package-id="' + pkg.id + '"]');
				var weightValue = $weightInput.val();
				pkg.weight = weightValue;
				var weightParsed = parseFloat(weightValue);
				var weightCheck = !weightValue || weightValue === '' || weightParsed <= 0 || isNaN(weightParsed);
				
				if (weightCheck) {
					$weightInput.addClass('is-invalid');
					$weightInput.after('<div class="invalid-feedback">Please enter a valid weight</div>');
					packageErrors.push('Weight');
					if (!firstErrorElement) firstErrorElement = $weightInput;
				}
				
				if (packageErrors.length > 0) {
					isValid = false;
					errorMessages.push('Package ' + (index + 1) + ': ' + packageErrors.join(', '));
				}
			}
			
			// Scroll to first error and show summary
			if (!isValid) {
				if (firstErrorElement) {
					$('html, body').animate({
						scrollTop: firstErrorElement.offset().top - 100
					}, 500);
					firstErrorElement.focus();
				}
				
				// Show error summary
				var errorSummary = 'Please fill the following required fields:\n\n' + errorMessages.join('\n');
				alert(errorSummary);
			}
			
			if (!isValid) {
				e.preventDefault();
				return false;
			}
			
			// Set hidden input
			$('#packages_data').val(JSON.stringify(packages));
		});
		
		// Bind add package button
		$('#add_package_btn').on('click', function(e) {
			e.preventDefault();
			window.addPackage();
		});
		
		// Initialize Select2 for existing selects
		if ($('.select').length > 0) {
			$('.select').select2();
		}
		
		// Initialize first package automatically after everything is set up
		if (packages.length === 0) {
			window.addPackage();
		}
	}
	
	// Start checking for jQuery
	initScript();
})();
</script>

