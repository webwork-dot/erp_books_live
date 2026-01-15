<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><?php echo htmlspecialchars($feature['name']); ?></h6>
		<?php if (!empty($feature['description'])): ?>
			<p class="text-muted mb-0"><?php echo htmlspecialchars($feature['description']); ?></p>
		<?php endif; ?>
	</div>
	<div class="d-flex gap-2">
		<?php 
		// Get vendor feature data to check if image exists
		$this->db->select('image');
		$this->db->from('vendor_features');
		$this->db->where('feature_id', $feature['id']);
		$this->db->where('is_enabled', 1);
		$vendor_feature = $this->db->get()->row_array();
		$has_image = !empty($vendor_feature['image']);
		?>
		<button type="button" 
				class="btn btn-sm btn-outline-primary" 
				onclick="openFeatureImageModal(<?php echo $feature['id']; ?>, '<?php echo htmlspecialchars($feature['name'], ENT_QUOTES); ?>', '<?php echo !empty($vendor_feature['image']) ? base_url('../uploads/vendor_features/' . $vendor_feature['image']) : ''; ?>')">
			<i class="isax isax-image"></i> <?php echo $has_image ? 'Change Image' : 'Upload Image'; ?>
		</button>
	</div>
</div>
<!-- End Header -->

<!-- Feature Content -->
<div class="card">
	<div class="card-body">
		<div class="text-center py-5">
			<i class="isax isax-box" style="font-size: 64px; color: var(--primary);"></i>
			<h4 class="mt-3"><?php echo htmlspecialchars($feature['name']); ?></h4>
			<p class="text-muted">This feature is enabled and ready to use.</p>
			<p class="text-muted small">Feature Slug: <code><?php echo htmlspecialchars($feature['slug']); ?></code></p>
		</div>
	</div>
</div>

<!-- Upload Image Modal -->
<div class="modal fade" id="featureImageModal" tabindex="-1" aria-labelledby="featureImageModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="featureImageModalLabel">Upload Feature Image</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="featureImageForm" enctype="multipart/form-data">
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
						<i class="isax isax-upload"></i> Upload Image
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
function openFeatureImageModal(featureId, featureName, currentImageUrl) {
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
	document.getElementById('featureImageForm').reset();
	document.getElementById('upload_error').style.display = 'none';
	document.getElementById('modal_feature_id').value = featureId;
	document.getElementById('feature_name_display').value = featureName;
	
	// Preview new image when selected
	const fileInput = document.getElementById('image_file');
	const newFileInput = fileInput.cloneNode(true);
	fileInput.parentNode.replaceChild(newFileInput, fileInput);
	
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
	
	const modal = new bootstrap.Modal(document.getElementById('featureImageModal'));
	modal.show();
}

document.getElementById('featureImageForm').addEventListener('submit', function(e) {
	e.preventDefault();
	
	const formData = new FormData(this);
	const errorDiv = document.getElementById('upload_error');
	
	errorDiv.style.display = 'none';
	
	fetch('<?php echo base_url($vendor_domain . "/features/upload_image"); ?>', {
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
</script>

