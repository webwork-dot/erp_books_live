<div class="card">
	<div class="card-header">
		<h4 class="card-title">Manage Feature Images</h4>
		<p class="text-muted mb-0">Upload custom images for your features that will be displayed on your live site.</p>
	</div>
	<div class="card-body">
		<?php if (empty($features)): ?>
			<div class="alert alert-info">
				<p class="mb-0">No features assigned to your account. Please contact the administrator.</p>
			</div>
		<?php else: ?>
			<div class="row">
				<?php foreach ($features as $feature): ?>
					<div class="col-md-4 col-lg-3 mb-4">
						<div class="card h-100">
							<div class="card-body text-center">
								<?php if (!empty($feature['image'])): ?>
									<img src="<?php echo base_url('uploads/vendor_features/' . $feature['image']); ?>" 
										 alt="<?php echo htmlspecialchars($feature['feature_name']); ?>" 
										 class="img-fluid mb-3" 
										 style="max-height: 150px; width: 100%; object-fit: cover; border-radius: 8px;">
								<?php else: ?>
									<div class="bg-light d-flex align-items-center justify-content-center mb-3" 
										 style="height: 150px; border-radius: 8px;">
										<i class="fa fa-image fa-3x text-muted"></i>
									</div>
								<?php endif; ?>
								
								<h5 class="card-title"><?php echo htmlspecialchars($feature['feature_name']); ?></h5>
								<p class="text-muted small mb-3"><?php echo htmlspecialchars($feature['feature_slug']); ?></p>
								
								<button type="button" 
										class="btn btn-sm btn-primary w-100 mb-2" 
										onclick="openUploadModal(<?php echo $feature['feature_id']; ?>, '<?php echo htmlspecialchars($feature['feature_name'], ENT_QUOTES); ?>', '<?php echo !empty($feature['image']) ? base_url('uploads/vendor_features/' . $feature['image']) : ''; ?>')">
									<i class="fa fa-upload"></i> <?php echo !empty($feature['image']) ? 'Change Image' : 'Upload Image'; ?>
								</button>
								
								<?php if (!empty($feature['image'])): ?>
									<button type="button" 
											class="btn btn-sm btn-danger w-100" 
											onclick="deleteImage(<?php echo $feature['feature_id']; ?>)">
										<i class="fa fa-trash"></i> Delete Image
									</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<!-- Upload Image Modal -->
<div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="uploadImageModalLabel">Upload Feature Image</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="uploadImageForm" enctype="multipart/form-data">
				<div class="modal-body">
					<input type="hidden" id="modal_feature_id" name="feature_id">
					<div class="mb-3">
						<label for="feature_name_display" class="form-label">Feature Name</label>
						<input type="text" class="form-control" id="feature_name_display" readonly>
					</div>
					<div class="mb-3">
						<label for="image_preview" class="form-label">Current Image</label>
						<div id="image_preview_container" class="text-center mb-3">
							<img id="image_preview" src="" alt="Preview" class="img-fluid" style="max-height: 200px; display: none; border-radius: 8px;">
							<p id="no_image_text" class="text-muted">No image uploaded</p>
						</div>
					</div>
					<div class="mb-3">
						<label for="image_file" class="form-label">Select Image</label>
						<input type="file" class="form-control" id="image_file" name="image" accept="image/*" required>
						<small class="form-text text-muted">Allowed formats: JPG, PNG, GIF, WEBP. Max size: 2MB</small>
					</div>
					<div id="upload_error" class="alert alert-danger" style="display: none;"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-upload"></i> Upload Image
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	function openUploadModal(featureId, featureName, currentImageUrl) {
		document.getElementById('modal_feature_id').value = featureId;
		document.getElementById('feature_name_display').value = featureName;
		
		const previewImg = document.getElementById('image_preview');
		const noImageText = document.getElementById('no_image_text');
		
		if (currentImageUrl) {
			previewImg.src = currentImageUrl;
			previewImg.style.display = 'block';
			noImageText.style.display = 'none';
		} else {
			previewImg.style.display = 'none';
			noImageText.style.display = 'block';
		}
		
		// Reset form
		document.getElementById('uploadImageForm').reset();
		document.getElementById('upload_error').style.display = 'none';
		document.getElementById('modal_feature_id').value = featureId;
		document.getElementById('feature_name_display').value = featureName;
		
		// Remove existing event listener if any
		const fileInput = document.getElementById('image_file');
		const newFileInput = fileInput.cloneNode(true);
		fileInput.parentNode.replaceChild(newFileInput, fileInput);
		
		// Preview new image when selected
		newFileInput.addEventListener('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				const reader = new FileReader();
				reader.onload = function(e) {
					previewImg.src = e.target.result;
					previewImg.style.display = 'block';
					noImageText.style.display = 'none';
				};
				reader.readAsDataURL(file);
			}
		});
		
		const modal = new bootstrap.Modal(document.getElementById('uploadImageModal'));
		modal.show();
	}
	
	// Make function globally available
	window.openUploadModal = openUploadModal;
	
	// Form submission handler
	const uploadForm = document.getElementById('uploadImageForm');
	if (uploadForm) {
		uploadForm.addEventListener('submit', function(e) {
			e.preventDefault();
			
			const formData = new FormData(this);
			const errorDiv = document.getElementById('upload_error');
			
			errorDiv.style.display = 'none';
			
			fetch('<?php echo base_url("features/upload_image"); ?>', {
				method: 'POST',
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					location.reload();
				} else {
					errorDiv.textContent = data.message || 'Upload failed';
					errorDiv.style.display = 'block';
				}
			})
			.catch(error => {
				errorDiv.textContent = 'An error occurred. Please try again.';
				errorDiv.style.display = 'block';
			});
		});
	}
	
	// Make deleteImage globally available
	window.deleteImage = function(featureId) {
		if (!confirm('Are you sure you want to delete this image?')) {
			return;
		}
		
		const formData = new FormData();
		formData.append('feature_id', featureId);
		
		fetch('<?php echo base_url("features/delete_image"); ?>', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				location.reload();
			} else {
				alert(data.message || 'Delete failed');
			}
		})
		.catch(error => {
			alert('An error occurred. Please try again.');
		});
	};
});
</script>

