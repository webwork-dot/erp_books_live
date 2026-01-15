<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Variations</h6>
	</div>
	<div>
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariationTypeModal">
			<i class="isax isax-add"></i> Add Variation Type
		</button>
	</div>
</div>
<!-- End Breadcrumb -->

<!-- Variation Types List -->
<div class="card">
	<div class="card-body">
		<?php if (!empty($variation_types)): ?>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>SR No.</th>
							<th>Name</th>
							<th>Description</th>
							<th>Values</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $sr_no = 1; foreach ($variation_types as $type): ?>
							<tr>
								<td><?php echo $sr_no++; ?></td>
								<td><strong><?php echo htmlspecialchars($type['name']); ?></strong></td>
								<td><?php echo htmlspecialchars($type['description'] ?: '-'); ?></td>
								<td>
									<div id="values-display-<?php echo $type['id']; ?>" class="values-display">
										<span class="text-muted">Loading...</span>
									</div>
								</td>
								<td>
									<span class="badge <?php echo ($type['status'] == 'active') ? 'badge-success' : 'badge-danger'; ?>">
										<?php echo ucfirst($type['status']); ?>
									</span>
								</td>
								<td class="text-end">
									<button type="button" class="btn btn-sm btn-outline-primary" onclick="manageValues(<?php echo $type['id']; ?>, '<?php echo htmlspecialchars($type['name'], ENT_QUOTES); ?>')" data-bs-toggle="tooltip" title="Manage Values">
										<i class="isax isax-setting-2"></i>
									</button>
									<button type="button" class="btn btn-sm btn-outline-secondary" onclick="editType(<?php echo $type['id']; ?>, '<?php echo htmlspecialchars($type['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($type['description'] ?: '', ENT_QUOTES); ?>')" data-bs-toggle="tooltip" title="Edit">
										<i class="isax isax-edit"></i>
									</button>
									<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteType(<?php echo $type['id']; ?>, '<?php echo htmlspecialchars($type['name'], ENT_QUOTES); ?>')" data-bs-toggle="tooltip" title="Delete">
										<i class="isax isax-trash"></i>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php else: ?>
			<div class="text-center py-5">
				<p class="text-muted">No variation types found. Create your first variation type to get started.</p>
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariationTypeModal">
					<i class="isax isax-add"></i> Add Variation Type
				</button>
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- Add Variation Type Modal -->
<div class="modal fade" id="addVariationTypeModal" tabindex="-1" aria-labelledby="addVariationTypeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addVariationTypeModalLabel">Add Variation Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addVariationTypeForm" enctype="multipart/form-data">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="variation_type_name" class="form-control" required placeholder="e.g., Size, Color, Material">
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="variation_type_description" class="form-control" rows="3" placeholder="Optional description"></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">Image</label>
						<input type="file" name="image" id="variation_type_image" class="form-control" accept="image/*">
						<small class="text-muted">Optional: Upload an image for this variation type (Max 2MB)</small>
						<div id="variation_type_image_preview" class="mt-2" style="display: none;">
							<img id="variation_type_image_preview_img" src="" alt="Preview" style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="resetAddVariationTypeForm()">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addVariationType()">Add Variation Type</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Variation Type Modal -->
<div class="modal fade" id="editVariationTypeModal" tabindex="-1" aria-labelledby="editVariationTypeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editVariationTypeModalLabel">Edit Variation Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="editVariationTypeForm">
					<input type="hidden" id="edit_type_id" value="">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="edit_variation_type_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="edit_variation_type_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="updateVariationType()">Update Variation Type</button>
			</div>
		</div>
	</div>
</div>

<!-- Manage Values Modal -->
<div class="modal fade" id="manageValuesModal" tabindex="-1" aria-labelledby="manageValuesModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="manageValuesModalLabel">Manage Values: <span id="modal_type_name"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Add Value Form (shown by default) -->
				<div id="addValueForm" class="card mb-4 border-primary">
					<div class="card-header bg-light">
						<h6 class="mb-0">Add New Values</h6>
					</div>
					<div class="card-body">
						<form id="addValueFormFields" enctype="multipart/form-data">
							<input type="hidden" id="add_value_type_id" value="">
							<div class="table-responsive">
								<table class="table table-sm table-bordered mb-0">
									<thead class="table-light">
										<tr>
											<th style="width: 45%;">Value Name <span class="text-danger">*</span></th>
											<th style="width: 45%;">Value Code</th>
											<th style="width: 10%;" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody id="valueRowsContainer">
										<!-- Value rows will be added here dynamically -->
									</tbody>
								</table>
							</div>
							<div class="d-flex gap-2 mt-3">
								<button type="button" class="btn btn-sm btn-outline-primary" onclick="addValueRow()">
									<i class="isax isax-add"></i> Add Row
								</button>
								<button type="button" class="btn btn-sm btn-primary" onclick="addVariationValues()">
									<i class="isax isax-tick-circle"></i> Save Values
								</button>
							</div>
						</form>
					</div>
				</div>
				
				<!-- Values List -->
				<div class="mb-3">
					<h6 class="mb-2">Existing Values</h6>
					<div id="valuesListContainer">
						<p class="text-muted">Loading values...</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<style>
.values-display {
	max-width: 400px;
	word-wrap: break-word;
	line-height: 1.6;
}

.values-display .badge {
	margin-left: 5px;
	font-size: 0.75rem;
}
</style>

<script>
// Helper functions for SweetAlert
function showError(message) {
	if (typeof Swal !== 'undefined') {
		Swal.fire({
			icon: 'error',
			title: 'Error',
			text: message,
			confirmButtonColor: '#dc3545'
		});
	} else {
		alert('Error: ' + message);
	}
}

function confirmAction(message, title, confirmText, cancelText) {
	if (typeof Swal !== 'undefined') {
		return Swal.fire({
			title: title || 'Confirm',
			text: message,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: confirmText || 'Yes',
			cancelButtonText: cancelText || 'Cancel',
			confirmButtonColor: '#dc3545',
			cancelButtonColor: '#6c757d'
		}).then((result) => {
			return result.isConfirmed;
		});
	} else {
		return Promise.resolve(confirm(message));
	}
}

// Load values on page load
document.addEventListener('DOMContentLoaded', function() {
	<?php if (!empty($variation_types)): ?>
		<?php foreach ($variation_types as $type): ?>
			loadValuesDisplay(<?php echo $type['id']; ?>);
		<?php endforeach; ?>
	<?php endif; ?>
});

function loadValuesDisplay(typeId) {
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/variations/get_values" : "products/variations/get_values"); ?>?type_id=' + typeId)
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				var displayElement = document.getElementById('values-display-' + typeId);
				if (displayElement) {
					if (data.values.length > 0) {
						var valueNames = data.values.map(function(v) { return v.name; });
						var displayText = valueNames.join(', ');
						// If too long, show first few with count
						if (displayText.length > 100) {
							var firstFew = valueNames.slice(0, 3).join(', ');
							displayElement.innerHTML = '<span class="text-dark">' + firstFew + '</span> <span class="badge badge-info">+' + (data.values.length - 3) + ' more</span>';
						} else {
							displayElement.innerHTML = '<span class="text-dark">' + displayText + '</span>';
						}
					} else {
						displayElement.innerHTML = '<span class="text-muted">No values</span>';
					}
				}
			}
		})
		.catch(error => {
			console.error('Error loading values:', error);
			var displayElement = document.getElementById('values-display-' + typeId);
			if (displayElement) {
				displayElement.innerHTML = '<span class="text-danger">Error loading</span>';
			}
		});
}

// Image preview for variation type
document.addEventListener('DOMContentLoaded', function() {
	var imageInput = document.getElementById('variation_type_image');
	if (imageInput) {
		imageInput.addEventListener('change', function(e) {
			var file = e.target.files[0];
			if (file) {
				// Validate file size (2MB)
				if (file.size > 2097152) {
					showError('Image size exceeds 2MB limit.');
					this.value = '';
					document.getElementById('variation_type_image_preview').style.display = 'none';
					return;
				}
				
				var reader = new FileReader();
				reader.onload = function(e) {
					var previewDiv = document.getElementById('variation_type_image_preview');
					var previewImg = document.getElementById('variation_type_image_preview_img');
					previewImg.src = e.target.result;
					previewDiv.style.display = 'block';
				};
				reader.readAsDataURL(file);
			} else {
				document.getElementById('variation_type_image_preview').style.display = 'none';
			}
		});
	}
	
	// Reset form when modal is closed
	var addModal = document.getElementById('addVariationTypeModal');
	if (addModal) {
		addModal.addEventListener('hidden.bs.modal', function() {
			resetAddVariationTypeForm();
		});
	}
});

function resetAddVariationTypeForm() {
	document.getElementById('variation_type_name').value = '';
	document.getElementById('variation_type_description').value = '';
	document.getElementById('variation_type_image').value = '';
	document.getElementById('variation_type_image_preview').style.display = 'none';
}

function addVariationType() {
	var name = document.getElementById('variation_type_name').value;
	var description = document.getElementById('variation_type_description').value;
	var imageInput = document.getElementById('variation_type_image');
	
	if (!name) {
		showError('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	// Add image if selected
	if (imageInput && imageInput.files && imageInput.files.length > 0) {
		formData.append('image', imageInput.files[0]);
	}
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/variations/add_type" : "products/variations/add_type"); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			Swal.fire({
				icon: 'success',
				title: 'Success!',
				text: 'Variation type added successfully!',
				confirmButtonColor: '#28a745',
				timer: 1500,
				showConfirmButton: true
			}).then(() => {
				resetAddVariationTypeForm();
				var modal = bootstrap.Modal.getInstance(document.getElementById('addVariationTypeModal'));
				if (modal) {
					modal.hide();
				}
				location.reload();
			});
		} else {
			showError(data.message || 'Failed to add variation type');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showError('An error occurred');
	});
}

function editType(id, name, description) {
	document.getElementById('edit_type_id').value = id;
	document.getElementById('edit_variation_type_name').value = name;
	document.getElementById('edit_variation_type_description').value = description || '';
	
	var modal = new bootstrap.Modal(document.getElementById('editVariationTypeModal'));
	modal.show();
}

function updateVariationType() {
	var id = document.getElementById('edit_type_id').value;
	var name = document.getElementById('edit_variation_type_name').value;
	var description = document.getElementById('edit_variation_type_description').value;
	
	if (!name) {
		showError('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/variations/update_type/" : "products/variations/update_type/"); ?>' + id, {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			Swal.fire({
				icon: 'success',
				title: 'Success!',
				text: 'Variation type updated successfully!',
				confirmButtonColor: '#28a745',
				timer: 1500,
				showConfirmButton: true
			}).then(() => {
				location.reload();
			});
		} else {
			showError(data.message || 'Failed to update variation type');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showError('An error occurred');
	});
}

function deleteType(id, name) {
	confirmAction('Are you sure you want to delete "' + name + '"? This will also delete all values associated with this variation type.', 'Confirm Delete', 'Yes, Delete', 'Cancel').then(function(confirmed) {
		if (!confirmed) {
			return;
		}
		
		deleteTypeConfirmed(id);
	});
}

function deleteTypeConfirmed(id) {
	
	fetch('<?php echo base_url(isset($current_vendor["domain"]) ? $current_vendor["domain"] . "/products/variations/delete_type/" : "products/variations/delete_type/"); ?>' + id, {
		method: 'POST'
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			Swal.fire({
				icon: 'success',
				title: 'Deleted!',
				text: 'Variation type deleted successfully!',
				confirmButtonColor: '#28a745',
				timer: 1500,
				showConfirmButton: true
			}).then(() => {
				location.reload();
			});
		} else {
			showError(data.message || 'Failed to delete variation type');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showError('An error occurred');
	});
}

function manageValues(typeId, typeName) {
	document.getElementById('modal_type_name').textContent = typeName;
	document.getElementById('add_value_type_id').value = typeId;
	
	// Reset form and show it
	document.getElementById('valueRowsContainer').innerHTML = '';
	valueRowCounter = 0;
	document.getElementById('addValueForm').style.display = 'block';
	// Add first row automatically
	addValueRow();
	
	// Load values
	loadValues(typeId);
	
	var modal = new bootstrap.Modal(document.getElementById('manageValuesModal'));
	modal.show();
}

function loadValues(typeId) {
	document.getElementById('valuesListContainer').innerHTML = '<p class="text-muted">Loading values...</p>';
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/variations/get_values" : "products/variations/get_values"); ?>?type_id=' + typeId)
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
					if (data.values.length > 0) {
					var html = '<div class="table-responsive"><table class="table table-sm">';
					html += '<thead><tr><th>Name</th><th>Code</th><th>Actions</th></tr></thead><tbody>';
					
					data.values.forEach(function(value) {
						html += '<tr>';
						html += '<td><strong>' + value.name + '</strong></td>';
						html += '<td>' + (value.value || '-') + '</td>';
						html += '<td>';
						html += '<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteValue(' + value.id + ', ' + typeId + ')"><i class="isax isax-trash"></i></button>';
						html += '</td>';
						html += '</tr>';
					});
					
					html += '</tbody></table></div>';
					document.getElementById('valuesListContainer').innerHTML = html;
				} else {
					document.getElementById('valuesListContainer').innerHTML = '<p class="text-muted">No values found. Add your first value.</p>';
				}
			} else {
				document.getElementById('valuesListContainer').innerHTML = '<p class="text-danger">Error loading values.</p>';
			}
		})
		.catch(error => {
			console.error('Error:', error);
			document.getElementById('valuesListContainer').innerHTML = '<p class="text-danger">Error loading values.</p>';
		});
}

var valueRowCounter = 0;

function addValueRow() {
	valueRowCounter++;
	var container = document.getElementById('valueRowsContainer');
	
	var rowHtml = '<tr class="value-row" data-row-id="' + valueRowCounter + '">';
	rowHtml += '<td>';
	rowHtml += '<input type="text" class="form-control form-control-sm value-name" placeholder="e.g., Small, Red" required>';
	rowHtml += '</td>';
	rowHtml += '<td>';
	rowHtml += '<input type="text" class="form-control form-control-sm value-code" placeholder="Optional">';
	rowHtml += '</td>';
	rowHtml += '<td class="text-center">';
	rowHtml += '<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeValueRow(' + valueRowCounter + ')" title="Remove">';
	rowHtml += '<i class="isax isax-trash"></i>';
	rowHtml += '</button>';
	rowHtml += '</td>';
	rowHtml += '</tr>';
	
	container.insertAdjacentHTML('beforeend', rowHtml);
}

function removeValueRow(rowId) {
	var row = document.querySelector('.value-row[data-row-id="' + rowId + '"]');
	if (row) {
		row.remove();
		// If no rows left, add one back
		var container = document.getElementById('valueRowsContainer');
		if (container.querySelectorAll('.value-row').length === 0) {
			addValueRow();
		}
	}
}

function addVariationValues() {
	var typeId = document.getElementById('add_value_type_id').value;
	var valueRows = document.querySelectorAll('#valueRowsContainer .value-row');
	
	if (valueRows.length === 0) {
		showError('Please add at least one value row');
		return;
	}
	
	// Collect all values
	var values = [];
	var hasErrors = false;
	
	valueRows.forEach(function(row, index) {
		var nameInput = row.querySelector('.value-name');
		var codeInput = row.querySelector('.value-code');
		
		var name = nameInput ? nameInput.value.trim() : '';
		var code = codeInput ? codeInput.value.trim() : '';
		
		if (name) {
			values.push({
				name: name,
				value: code || ''
			});
		} else {
			hasErrors = true;
		}
	});
	
	if (hasErrors) {
		showError('Please fill in all value names');
		return;
	}
	
	if (values.length === 0) {
		showError('Please enter at least one value');
		return;
	}
	
	// Prepare FormData
	var formData = new FormData();
	formData.append('type_id', typeId);
	formData.append('values', JSON.stringify(values));
	
	// Show loading
	var addButton = document.querySelector('#addValueFormFields button.btn-primary');
	var originalText = addButton.innerHTML;
	addButton.disabled = true;
	addButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/variations/add_value" : "products/variations/add_value"); ?>', {
		method: 'POST',
		body: formData
	})
		.then(response => response.json())
		.then(data => {
			addButton.disabled = false;
			addButton.innerHTML = originalText;
			
			if (data.status === 'success') {
				var successCount = data.added_count || 0;
				var failCount = data.failed_count || 0;
				
				if (successCount > 0) {
					// Clear form but keep it visible
					document.getElementById('valueRowsContainer').innerHTML = '';
					valueRowCounter = 0;
					addValueRow();
					loadValues(typeId);
					loadValuesDisplay(typeId);
					
					if (failCount > 0) {
						Swal.fire({
							icon: 'warning',
							title: 'Partial Success',
							text: data.message || ('Added ' + successCount + ' value(s) successfully. ' + failCount + ' failed.'),
							confirmButtonColor: '#ffc107'
						});
					} else {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: data.message || ('Successfully added ' + successCount + ' value(s)!'),
							confirmButtonColor: '#28a745',
							timer: 2000,
							showConfirmButton: true
						});
					}
				} else {
					showError(data.message || 'Failed to add values. Please try again.');
				}
			} else {
				showError(data.message || 'Failed to add values. Please try again.');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			addButton.disabled = false;
			addButton.innerHTML = originalText;
			showError('An error occurred while adding values.');
		});
}

function deleteValue(valueId, typeId) {
	confirmAction('Are you sure you want to delete this value?', 'Confirm Delete', 'Yes, Delete', 'Cancel').then(function(confirmed) {
		if (!confirmed) {
			return;
		}
		
		deleteValueConfirmed(valueId, typeId);
	});
}

function deleteValueConfirmed(valueId, typeId) {
	
	fetch('<?php echo base_url(isset($current_vendor['domain']) ? $current_vendor["domain"] . "/products/variations/delete_value/" : "products/variations/delete_value/"); ?>' + valueId, {
		method: 'POST'
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			Swal.fire({
				icon: 'success',
				title: 'Deleted!',
				text: 'Value deleted successfully!',
				confirmButtonColor: '#28a745',
				timer: 1500,
				showConfirmButton: true
			}).then(() => {
				loadValues(typeId);
				loadValuesDisplay(typeId);
			});
		} else {
			showError(data.message || 'Failed to delete value');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showError('An error occurred');
	});
}
</script>

