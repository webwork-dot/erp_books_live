<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('master-size-charts'); ?>"><i class="isax isax-arrow-left me-1"></i>Edit Master Size Chart</a></h6>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?php echo base_url('master-size-charts/edit/' . (int) $chart['id']); ?>" enctype="multipart/form-data">
			<input type="hidden"
				name="<?php echo $this->security->get_csrf_token_name(); ?>"
				value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="mb-3">
				<label class="form-label">Name <span class="text-danger">*</span></label>
				<input type="text" name="chart_name" class="form-control" required
					value="<?php echo set_value('chart_name', $chart['name'] ?? ''); ?>">
				<?php echo form_error('chart_name', '<small class="text-danger">', '</small>'); ?>
			</div>
			<div class="mb-3">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="active" <?php echo set_select('status', 'active', ($chart['status'] ?? '') === 'active'); ?>>Active</option>
					<option value="inactive" <?php echo set_select('status', 'inactive', ($chart['status'] ?? '') === 'inactive'); ?>>Inactive</option>
				</select>
			</div>

			<?php if (!empty($images)): ?>
				<div class="mb-3">
					<label class="form-label">Current images</label>
					<div class="row g-2">
						<?php foreach ($images as $img): ?>
							<div class="col-6 col-md-3">
								<div class="border rounded p-1 position-relative">
									<?php
									$msc_stored = trim($img['image_path']);
									if (strpos($msc_stored, 'http://') === 0 || strpos($msc_stored, 'https://') === 0) {
										$msc_img_url = $msc_stored;
									} else {
										$msc_img_url = get_vendor_domain_url() . '/' . ltrim($msc_stored, '/');
									}
									?>
									<img src="<?php echo htmlspecialchars($msc_img_url, ENT_QUOTES, 'UTF-8'); ?>"
										alt="" class="img-fluid rounded" style="max-height: 120px; width: 100%; object-fit: cover;">
									<div class="form-check mt-1">
										<input class="form-check-input" type="checkbox" name="remove_image_ids[]" value="<?php echo (int) $img['id']; ?>" id="rm_<?php echo (int) $img['id']; ?>">
										<label class="form-check-label small" for="rm_<?php echo (int) $img['id']; ?>">Remove</label>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<small class="text-muted d-block mt-1">Tick “Remove” and save to delete selected images from disk.</small>
				</div>
			<?php endif; ?>

			<div class="mb-3">
				<label class="form-label">Add images</label>
				<input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
				<small class="text-muted">Append more images (optional).</small>
			</div>
			<div class="mt-3 d-flex gap-2 justify-content-end">
				<a href="<?php echo base_url('master-size-charts'); ?>" class="btn btn-outline-secondary">Cancel</a>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</form>
	</div>
</div>
