<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('products/bookset?tab=with_product'); ?>"><i class="isax isax-arrow-left me-2"></i>Add Bookset with Products</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->

<style>

	.badge {
		margin-left:8px;
	}
	.packages-container {
		height: 70vh;
		max-height: 70vh;
		overflow-y: auto !important;
		overscroll-behavior: contain;
		-webkit-overflow-scrolling: touch;
		padding-right: 10px;
		position: relative;
		display: block;
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
	.package-card .table {
		font-size: 0.875rem;
	}
	.package-card .table th {
		font-size: 0.8rem;
		font-weight: 600;
		padding: 0.5rem;
	}
	.package-card .table td {
		padding: 0.5rem;
		vertical-align: middle;
	}
	.package-status-badge {
		font-size: 0.75rem;
		padding: 0.25rem 0.5rem;
	}
	.package-products-table .form-control-sm {
		font-size: 0.8rem;
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

<?php echo form_open(base_url('products/bookset/package/add_with_products'), array('id' => 'bookset-with-products-form')); ?>

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
											<?php 
											if (!empty($school['type']) && $school['type'] == 'branch' && !empty($school['parent_school_name'])) {
												// Branch format: Parent School Name - Branch Name
												$displayText = htmlspecialchars($school['parent_school_name']) . ' - ' . htmlspecialchars($school['school_name']);
											} else {
												// School format: just the school name
												$displayText = htmlspecialchars($school['school_name']);
											}
											?>
											<option value="<?php echo $school['id']; ?>" data-type="<?php echo isset($school['type']) ? $school['type'] : 'school'; ?>">
												<?php echo $displayText; ?>
											</option>
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
								<div class="input-group flex-nowrap">
									<select name="grade_id" id="grade_id" class="select" required>
										<option value="">Select Grade</option>
										<?php if (!empty($grades)): ?>
											<?php foreach ($grades as $grade): ?>
												<option value="<?php echo $grade['id']; ?>"><?php echo htmlspecialchars($grade['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
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
					
				<div id="packages_area" class="packages-container" data-simplebar>
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

<!-- Add Grade Modal -->
<div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addGradeModalLabel">Add Grade</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addGradeForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="grade_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="grade_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addGrade()">Add Grade</button>
			</div>
		</div>
	</div>
</div>

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
		
		// Get types for dropdown
		var types = <?php echo json_encode($types ?? []); ?>;
		
		// Populate category dropdown when package is added (only main categories)
		function populateTypeDropdown(packageId) {
			var $select = $('.package-type-select[data-package-id="' + packageId + '"]');
			$select.empty().append('<option value="">Select Category</option>');
			
			// Show main categories: Textbook, Notebook, and Stationery
			$select.append('<option value="textbook" data-category="textbook">Textbook</option>');
			$select.append('<option value="notebook" data-category="notebook">Notebook</option>');
			$select.append('<option value="stationery" data-category="stationery">Stationery</option>');
			
			// Initialize Select2 if needed
			if ($select.hasClass('select')) {
				$select.select2();
			}
		}
		
		// Load boards when school is selected
		function loadBoards(schoolId) {
			var $boardSelect = $('#board_id');
			
			if (!schoolId) {
				// Check if Select2 is initialized
				if ($boardSelect.length && $boardSelect.hasClass('select2-hidden-accessible')) {
					$boardSelect.select2('destroy');
				}
				$boardSelect.empty().append('<option value="">Select School First</option>');
				// Reinitialize Select2 if it was initialized before
				if ($boardSelect.hasClass('select')) {
					$boardSelect.select2();
				}
				return;
			}
			
			$.ajax({
				url: '<?php echo base_url("products/bookset/package/get_boards"); ?>',
				type: 'GET',
				data: {
					school_id: schoolId
				},
				dataType: 'json',
				success: function(response) {
					// Check if Select2 is initialized
					if ($boardSelect.length && $boardSelect.hasClass('select2-hidden-accessible')) {
						// Destroy Select2 first
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
					
					// Reinitialize Select2 if it was initialized before
					if ($boardSelect.hasClass('select')) {
						$boardSelect.select2();
					}
				},
				error: function() {
					// Check if Select2 is initialized
					if ($boardSelect.length && $boardSelect.hasClass('select2-hidden-accessible')) {
						$boardSelect.select2('destroy');
					}
					$boardSelect.empty().append('<option value="">Error loading boards</option>');
					// Reinitialize Select2 if it was initialized before
					if ($boardSelect.hasClass('select')) {
						$boardSelect.select2();
					}
				}
			});
		}
		
		// Bind school change event (moved inside initScript to ensure jQuery is available)
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
							<div class="col-md-4 col-sm-6">
								<label class="form-label small fw-semibold mb-1">Category <span class="text-danger">*</span></label>
								<select class="form-select form-select-sm package-type-select" data-package-id="${packageCounter}" required>
									<option value="">Select Category</option>
								</select>
							</div>
							<div class="col-md-4 col-sm-6">
								<label class="form-label small fw-semibold mb-1">Package Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control form-control-sm package-name-input" data-package-id="${packageCounter}" placeholder="Enter package name" required>
							</div>
							<div class="col-md-4 col-sm-12">
								<label class="form-label small fw-semibold mb-1">Note</label>
								<input type="text" class="form-control form-control-sm package-note-input" data-package-id="${packageCounter}" placeholder="Optional note">
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
						
						<div class="mb-3">
							<label class="form-label small fw-semibold mb-1">Select Products <span class="text-danger">*</span></label>
							<select class="form-select form-select-sm package-product-select" data-package-id="${packageCounter}" multiple>
								<option value="">Select Category First</option>
							</select>
							<small class="text-muted">Select multiple products</small>
						</div>
						
						<div class="table-responsive">
							<table class="table table-sm table-bordered table-hover mb-0 package-products-table" data-package-id="${packageCounter}">
								<thead class="table-light">
									<tr>
										<th style="width: 25%;">Product Name</th>
										<th style="width: 25%;">Display Name</th>
										<th style="width: 15%;">Quantity</th>
										<th style="width: 20%;">Discounted MRP</th>
										<th style="width: 15%;">Action</th>
									</tr>
								</thead>
								<tbody class="package-products-tbody" data-package-id="${packageCounter}">
									<tr class="no-products-row">
										<td colspan="5" class="text-center text-muted py-3">
											<i class="isax isax-box-1"></i> No products selected
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			`;
			
			$('#packages_area').append(packageHtml);
			
			// Populate type dropdown
			populateTypeDropdown(packageCounter);
			
			// Initialize Select2 for the new package dropdowns
			$('.package-type-select[data-package-id="' + packageCounter + '"]').select2();
			$('.package-product-select[data-package-id="' + packageCounter + '"]').select2({
				placeholder: 'Select Products',
				allowClear: true
			});
			$('.package-isit-select[data-package-id="' + packageCounter + '"]').select2();
			
			// Initialize package object with products array
			packages.push({
				id: packageCounter,
				category: '',
				package_name: '',
				products: [], // Array to hold multiple products
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
			
			var categoryCheck = !!packageObj.category;
			var packageNameCheck = !!packageObj.package_name && packageObj.package_name.trim() !== '';
			var isItCheck = !!packageObj.is_it;
			// Weight must be > 0, not just exist
			var weightParsed = parseFloat(packageObj.weight);
			var weightCheck = !!packageObj.weight && packageObj.weight !== '' && weightParsed > 0 && !isNaN(weightParsed);
			var productsCheck = !!packageObj.products && packageObj.products.length > 0;
			
			var isValid = categoryCheck && packageNameCheck && isItCheck && weightCheck && productsCheck;
			
			if (isValid) {
				// Check if all products have required fields - get fresh values from DOM
				var allProductsValid = true;
				if (packageObj.products && packageObj.products.length > 0) {
					packageObj.products.forEach(function(product, index) {
						var $displayNameInput = $('.product-display-name[data-package-id="' + packageId + '"][data-product-index="' + index + '"]');
						var $quantityInput = $('.product-quantity[data-package-id="' + packageId + '"][data-product-index="' + index + '"]');
						var $discountedMrpInput = $('.product-discounted-mrp[data-package-id="' + packageId + '"][data-product-index="' + index + '"]');
						
						var displayNameValue = $displayNameInput.length ? $displayNameInput.val() : product.display_name;
						var quantityValue = $quantityInput.length ? $quantityInput.val() : product.quantity;
						var discountedMrpValue = $discountedMrpInput.length ? $discountedMrpInput.val() : product.discounted_mrp;
						
						if (!displayNameValue || displayNameValue.trim() === '' || 
							!quantityValue || parseInt(quantityValue) < 1 || 
							discountedMrpValue === '' || discountedMrpValue === null || discountedMrpValue === undefined || 
							parseFloat(discountedMrpValue) <= 0 || isNaN(parseFloat(discountedMrpValue))) {
							allProductsValid = false;
						}
					});
				}
				
				if (allProductsValid) {
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
			$field.closest('td').find('.invalid-feedback').remove();
			// For Select2
			if ($field.hasClass('select2-hidden-accessible')) {
				$field.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
			}
			// Also check parent container
			$field.parent().find('.invalid-feedback').remove();
		}
		
		// Bind package events
		function bindPackageEvents(packageId) {
			// Category change - load products
			$('.package-type-select[data-package-id="' + packageId + '"]').on('change', function() {
				clearFieldError($(this));
				var category = $(this).val();
				
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj && category) {
					packageObj.category = category;
					loadProductsForPackage(packageId, category);
					updatePackageStatus(packageId);
				} else {
					// Clear products if category is cleared
					var $select = $('.package-product-select[data-package-id="' + packageId + '"]');
					if ($select.hasClass('select2-hidden-accessible')) {
						$select.select2('destroy');
					}
					$select.empty().append('<option value="">Select Category First</option>');
					$select.select2({
						placeholder: 'Select Products',
						allowClear: true
					});
					if (packageObj) {
						packageObj.products = [];
					}
					renderPackageProducts(packageId);
					updatePackageStatus(packageId);
				}
			});
			
			// Package name
			$('.package-name-input[data-package-id="' + packageId + '"]').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.package_name = $(this).val();
					updatePackageStatus(packageId);
				}
			});
			
			// Product selection (multiple)
			$('.package-product-select[data-package-id="' + packageId + '"]').on('change', function() {
				clearFieldError($(this));
				var selectedProducts = $(this).val(); // Array of selected product IDs
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				
				if (!packageObj) return;
				
				// Get all available products for this package
				var availableProducts = {};
				$(this).find('option').each(function() {
					var productId = $(this).val();
					if (productId && $(this).data('product-data')) {
						availableProducts[productId] = $(this).data('product-data');
					}
				});
				
				// Update products array
				packageObj.products = [];
				if (selectedProducts && selectedProducts.length > 0) {
					selectedProducts.forEach(function(productId) {
						if (availableProducts[productId]) {
							var productData = availableProducts[productId];
							packageObj.products.push({
								product_id: productData.product_id,
								product_type: productData.product_type,
								product_name: productData.product_name,
								type_names: productData.type_names || '',
								display_name: productData.product_name,
								quantity: 1,
								discounted_mrp: 0
							});
						}
					});
				}
				
				// Render products table
				renderPackageProducts(packageId);
				updatePackageStatus(packageId);
			});
			
			// Is It
			$('.package-isit-select[data-package-id="' + packageId + '"]').on('change', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				if (packageObj) {
					packageObj.is_it = $(this).val();
					updatePackageStatus(packageId);
					updatePackageSummary(); // Update the summary count when is_it changes
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
		
		// Load products for package based on category (all products with is_set=1)
		function loadProductsForPackage(packageId, category) {
			var $select = $('.package-product-select[data-package-id="' + packageId + '"]');
			
			// Check if Select2 is initialized and destroy it
			if ($select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			$select.empty().append('<option value="">Loading...</option>');
			
			$.ajax({
				url: '<?php echo base_url("products/bookset/package/get_products_by_type"); ?>',
				type: 'GET',
				data: {
					category: category
				},
				dataType: 'json',
				success: function(response) {
					$select.empty();
					if (response && response.success && response.products) {
						response.products.forEach(function(product) {
							var displayText = product.product_name;
							if (product.type_names) {
								displayText += ' (' + product.type_names + ')';
							}
							if (product.publisher_name) {
								displayText += ' - ' + product.publisher_name;
							}
							
							var $option = $('<option></option>')
								.attr('value', product.id)
								.text(displayText)
								.data('product-data', product);
							$select.append($option);
						});
					}
					// Reinitialize Select2 with multiple selection
					$select.select2({
						placeholder: 'Select Products',
						allowClear: true
					});
				},
				error: function() {
					$select.empty().append('<option value="">Error loading products</option>');
					// Reinitialize Select2
					$select.select2({
						placeholder: 'Select Products',
						allowClear: true
					});
				}
			});
		}
		
		// Render products table for a package
		function renderPackageProducts(packageId) {
			var packageObj = packages.find(function(p) { return p.id === packageId; });
			if (!packageObj) return;
			
			var $tbody = $('.package-products-tbody[data-package-id="' + packageId + '"]');
			$tbody.empty();
			
			if (!packageObj.products || packageObj.products.length === 0) {
				$tbody.append('<tr class="no-products-row"><td colspan="5" class="text-center text-muted">No products selected</td></tr>');
				return;
			}
			
			packageObj.products.forEach(function(product, index) {
				var rowHtml = `
					<tr data-product-index="${index}">
						<td>${product.product_name}${product.type_names ? ' (' + product.type_names + ')' : ''}</td>
						<td>
							<input type="text" class="form-control form-control-sm product-display-name" 
								data-package-id="${packageId}" 
								data-product-index="${index}" 
								value="${product.display_name || product.product_name}" 
								required>
						</td>
						<td>
							<input type="number" class="form-control form-control-sm product-quantity" 
								data-package-id="${packageId}" 
								data-product-index="${index}" 
								value="${product.quantity || 1}" 
								min="1" 
								required>
						</td>
						<td>
							<input type="number" class="form-control form-control-sm product-discounted-mrp" 
								data-package-id="${packageId}" 
								data-product-index="${index}" 
								value="${product.discounted_mrp || ''}" 
								step="0.01" 
								min="0.01" 
								required>
						</td>
						<td>
							<button type="button" class="btn btn-sm btn-danger remove-product-btn" 
								data-package-id="${packageId}" 
								data-product-index="${index}">
								<i class="isax isax-trash"></i>
							</button>
						</td>
					</tr>
				`;
				$tbody.append(rowHtml);
			});
			
			// Bind events for product fields
			bindProductFieldEvents(packageId);
		}
		
		// Bind events for product table fields
		function bindProductFieldEvents(packageId) {
			// Display name
			$('.product-display-name[data-package-id="' + packageId + '"]').off('input').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				var index = parseInt($(this).data('product-index'));
				if (packageObj && packageObj.products[index]) {
					packageObj.products[index].display_name = $(this).val();
					updatePackageStatus(packageId);
				}
			});
			
			// Quantity
			$('.product-quantity[data-package-id="' + packageId + '"]').off('input').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				var index = parseInt($(this).data('product-index'));
				if (packageObj && packageObj.products[index]) {
					packageObj.products[index].quantity = parseInt($(this).val()) || 1;
					updatePackageStatus(packageId);
				}
			});
			
			// Discounted MRP
			$('.product-discounted-mrp[data-package-id="' + packageId + '"]').off('input').on('input', function() {
				clearFieldError($(this));
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				var index = parseInt($(this).data('product-index'));
				if (packageObj && packageObj.products[index]) {
					packageObj.products[index].discounted_mrp = parseFloat($(this).val()) || 0;
					updatePackageStatus(packageId);
				}
			});
			
			// Remove product
			$('.remove-product-btn[data-package-id="' + packageId + '"]').off('click').on('click', function() {
				var packageObj = packages.find(function(p) { return p.id === packageId; });
				var index = parseInt($(this).data('product-index'));
				if (packageObj && packageObj.products && packageObj.products[index]) {
					var productId = packageObj.products[index].product_type + '_' + packageObj.products[index].product_id;
					packageObj.products.splice(index, 1);
					
					// Update Select2 to remove the product
					var $select = $('.package-product-select[data-package-id="' + packageId + '"]');
					var currentVal = $select.val() || [];
					currentVal = currentVal.filter(function(id) { return id !== productId; });
					$select.val(currentVal).trigger('change');
					
					renderPackageProducts(packageId);
					updatePackageStatus(packageId);
				}
			});
		}
		
		// Update package summary
		function updatePackageSummary() {
			var total = packages.length;
			var mandatory = packages.filter(function(p) { return p.is_it === 'mandatory'; }).length;
			var optional = packages.filter(function(p) { return p.is_it === 'optional'; }).length;
			var mandatoryOptional = packages.filter(function(p) { return p.is_it === 'mandatory+optional'; }).length;
			
			// Calculate total - only count packages with "mandatory+optional" option
			var mandatoryOptionalTotal = mandatoryOptional;
			
			$('#total_packages_display').text(total);
			$('#mandatory_count_display').text(mandatory);
			$('#optional_count_display').text(optional);
			$('#mandatory_optional_count_display').text(mandatoryOptional);
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
				// Set to 0 if no mandatory+optional packages
				$mandatoryPackagesInput.val(0);
			}
		}
		
		// Form submission
		$('#bookset-with-products-form').on('submit', function(e) {
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
				// Get values from inputs - ensure category is properly retrieved
				var $categorySelect = $('.package-type-select[data-package-id="' + pkg.id + '"]');
				var categoryValue = $categorySelect.val();
				// If Select2 is used, try getting the actual value
				if ($categorySelect.hasClass('select2-hidden-accessible')) {
					categoryValue = $categorySelect.select2('val');
				}
				var categoryBefore = pkg.category;
				// Always update category if we got a value from DOM, even if it's empty string
				if (categoryValue !== null && categoryValue !== undefined) {
					pkg.category = categoryValue;
				}
				
				pkg.package_name = $('.package-name-input[data-package-id="' + pkg.id + '"]').val();
				
				var $isItSelect = $('.package-isit-select[data-package-id="' + pkg.id + '"]');
				var isItValue = $isItSelect.val();
				if ($isItSelect.hasClass('select2-hidden-accessible')) {
					isItValue = $isItSelect.select2('val');
				}
				pkg.is_it = isItValue;
				
				var weightValue = $('.package-weight-input[data-package-id="' + pkg.id + '"]').val();
				pkg.weight = weightValue;
				pkg.note = $('.package-note-input[data-package-id="' + pkg.id + '"]').val();
				
				// Products are already in pkg.products array from the table
				// Just ensure all fields are updated from the table inputs
				if (pkg.products && pkg.products.length > 0) {
					pkg.products.forEach(function(product, index) {
						var displayName = $('.product-display-name[data-package-id="' + pkg.id + '"][data-product-index="' + index + '"]').val();
						var quantity = $('.product-quantity[data-package-id="' + pkg.id + '"][data-product-index="' + index + '"]').val();
						var discountedMrp = $('.product-discounted-mrp[data-package-id="' + pkg.id + '"][data-product-index="' + index + '"]').val();
						
						if (displayName !== undefined) product.display_name = displayName;
						if (quantity !== undefined) product.quantity = parseInt(quantity) || 1;
						if (discountedMrp !== undefined) product.discounted_mrp = parseFloat(discountedMrp) || 0;
					});
				}
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
				
				// Check category - ALWAYS get it directly from the select element to ensure we have the latest value
				var $categorySelect = $('.package-type-select[data-package-id="' + pkg.id + '"]');
				var categoryValue = $categorySelect.val();
				if ($categorySelect.hasClass('select2-hidden-accessible')) {
					categoryValue = $categorySelect.select2('val');
				}
				// Update pkg.category with the value from DOM
				pkg.category = categoryValue;
				
				if (!categoryValue || categoryValue === '' || categoryValue === null || categoryValue === undefined) {
					$categorySelect.addClass('is-invalid');
					if ($categorySelect.hasClass('select2-hidden-accessible')) {
						$categorySelect.next('.select2-container').find('.select2-selection').addClass('is-invalid');
					}
					$categorySelect.closest('.mb-3').find('label').after('<div class="invalid-feedback">Please select a category</div>');
					packageErrors.push('Category');
					if (!firstErrorElement) firstErrorElement = $categorySelect;
				}
				
				// Check package name - get fresh value from DOM
				var $packageNameInput = $('.package-name-input[data-package-id="' + pkg.id + '"]');
				var packageNameValue = $packageNameInput.val();
				// Update pkg.package_name with the value from DOM
				pkg.package_name = packageNameValue;
				var packageNameCheck = !packageNameValue || packageNameValue.trim() === '';
				
				if (packageNameCheck) {
					$packageNameInput.addClass('is-invalid');
					$packageNameInput.after('<div class="invalid-feedback">Please enter package name</div>');
					packageErrors.push('Package Name');
					if (!firstErrorElement) firstErrorElement = $packageNameInput;
				}
				
				// Check is_it - ALWAYS get it directly from the select element
				var $isItSelect = $('.package-isit-select[data-package-id="' + pkg.id + '"]');
				var isItValue = $isItSelect.val();
				if ($isItSelect.hasClass('select2-hidden-accessible')) {
					isItValue = $isItSelect.select2('val');
				}
				// Update pkg.is_it with the value from DOM
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
				
				// Check weight - get fresh value from DOM
				var $weightInput = $('.package-weight-input[data-package-id="' + pkg.id + '"]');
				var weightValue = $weightInput.val();
				// Update pkg.weight with the value from DOM
				pkg.weight = weightValue;
				var weightParsed = parseFloat(weightValue);
				var weightCheck = !weightValue || weightValue === '' || weightParsed <= 0 || isNaN(weightParsed);
				
				if (weightCheck) {
					$weightInput.addClass('is-invalid');
					$weightInput.after('<div class="invalid-feedback">Please enter a valid weight</div>');
					packageErrors.push('Weight');
					if (!firstErrorElement) firstErrorElement = $weightInput;
				}
				
				// Check products
				var productsCheck = !pkg.products || pkg.products.length === 0;
				
				if (productsCheck) {
					var $productSelect = $('.package-product-select[data-package-id="' + pkg.id + '"]');
					$productSelect.addClass('is-invalid');
					if ($productSelect.hasClass('select2-hidden-accessible')) {
						$productSelect.next('.select2-container').find('.select2-selection').addClass('is-invalid');
					}
					$productSelect.closest('.mb-3').find('label').after('<div class="invalid-feedback d-block">Please select at least one product</div>');
					packageErrors.push('Products');
					if (!firstErrorElement) firstErrorElement = $productSelect;
				} else {
					// Validate product fields - get fresh values from DOM
					for (var j = 0; j < pkg.products.length; j++) {
						var product = pkg.products[j];
						var $displayNameInput = $('.product-display-name[data-package-id="' + pkg.id + '"][data-product-index="' + j + '"]');
						var $quantityInput = $('.product-quantity[data-package-id="' + pkg.id + '"][data-product-index="' + j + '"]');
						var $discountedMrpInput = $('.product-discounted-mrp[data-package-id="' + pkg.id + '"][data-product-index="' + j + '"]');
						
						// Get fresh values from DOM
						var displayNameValue = $displayNameInput.val();
						var quantityValue = $quantityInput.val();
						var discountedMrpValue = $discountedMrpInput.val();
						
						// Update product object with DOM values
						if (displayNameValue !== undefined) product.display_name = displayNameValue;
						if (quantityValue !== undefined) product.quantity = parseInt(quantityValue) || 0;
						if (discountedMrpValue !== undefined) product.discounted_mrp = parseFloat(discountedMrpValue) || 0;
						
						var displayNameCheck = !displayNameValue || displayNameValue.trim() === '';
						var quantityCheck = !quantityValue || parseInt(quantityValue) < 1;
						var discountedMrpCheck = discountedMrpValue === '' || discountedMrpValue === null || discountedMrpValue === undefined || parseFloat(discountedMrpValue) <= 0 || isNaN(parseFloat(discountedMrpValue));
						
						if (displayNameCheck) {
							$displayNameInput.addClass('is-invalid');
							$displayNameInput.closest('td').append('<div class="invalid-feedback d-block">Please enter display name</div>');
							packageErrors.push('Product ' + (j + 1) + ' Display Name');
							if (!firstErrorElement) firstErrorElement = $displayNameInput;
						}
						
						if (quantityCheck) {
							$quantityInput.addClass('is-invalid');
							$quantityInput.closest('td').append('<div class="invalid-feedback d-block">Please enter valid quantity (min 1)</div>');
							packageErrors.push('Product ' + (j + 1) + ' Quantity');
							if (!firstErrorElement) firstErrorElement = $quantityInput;
						}
						
						if (discountedMrpCheck) {
							$discountedMrpInput.addClass('is-invalid');
							$discountedMrpInput.closest('td').append('<div class="invalid-feedback d-block">Discounted MRP is required and must be greater than 0</div>');
							packageErrors.push('Product ' + (j + 1) + ' Discounted MRP');
							if (!firstErrorElement) firstErrorElement = $discountedMrpInput;
						}
					}
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

function addGrade() {
	var name = document.getElementById('grade_name').value;
	var description = document.getElementById('grade_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url('products/textbook/add_grade'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			var select = document.getElementById('grade_id');
			var $select = $('#grade_id');
			
			if ($select.length && $select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			var option = document.createElement('option');
			option.value = data.id;
			option.textContent = data.name;
			option.selected = true;
			select.appendChild(option);
			
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			document.getElementById('addGradeForm').reset();
		} else {
			alert(data.message || 'Failed to add grade');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}
</script>
