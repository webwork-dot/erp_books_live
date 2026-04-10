<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('master-size-charts'); ?>"><i class="isax isax-arrow-left me-1"></i>Add Master Size Chart</a></h6>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?php echo base_url('master-size-charts/add'); ?>" enctype="multipart/form-data">
			<input type="hidden"
				name="<?php echo $this->security->get_csrf_token_name(); ?>"
				value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="mb-3">
				<label class="form-label">Name <span class="text-danger">*</span></label>
				<input type="text" name="chart_name" class="form-control" required value="<?php echo set_value('chart_name'); ?>">
				<?php echo form_error('chart_name', '<small class="text-danger">', '</small>'); ?>
			</div>
			<div class="mb-3">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="active" <?php echo set_select('status', 'active', TRUE); ?>>Active</option>
					<option value="inactive" <?php echo set_select('status', 'inactive'); ?>>Inactive</option>
				</select>
			</div>
			<div class="mb-3">
				<label class="form-label">Images</label>
				<input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
				<small class="text-muted">You can select multiple files (JPG, PNG, GIF, WebP).</small>
			</div>
			<div class="mt-3 d-flex gap-2 justify-content-end">
				<a href="<?php echo base_url('master-size-charts'); ?>" class="btn btn-outline-secondary">Cancel</a>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</form>
	</div>
</div>
