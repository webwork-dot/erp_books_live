<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('size-charts'); ?>"><i class="isax isax-arrow-left me-1"></i>Add Size Chart</a></h6>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?php echo base_url('size-charts/add'); ?>">
			<div class="mb-3">
				<label class="form-label">Chart Name <span class="text-danger">*</span></label>
				<input type="text" name="chart_name" class="form-control" required value="<?php echo set_value('chart_name'); ?>">
				<?php echo form_error('chart_name', '<small class="text-danger">', '</small>'); ?>
			</div>
			<div class="mb-3">
				<label class="form-label">Description</label>
				<textarea name="description" class="form-control" rows="3"><?php echo set_value('description'); ?></textarea>
			</div>
			<div class="mb-3">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="active" <?php echo set_select('status', 'active', TRUE); ?>>Active</option>
					<option value="inactive" <?php echo set_select('status', 'inactive'); ?>>Inactive</option>
				</select>
			</div>

			<div class="mb-2">
				<label class="form-label">Sizes <span class="text-danger">*</span></label>
				<small class="text-muted d-block mb-2">Enter sizes separated by commas (e.g., S, M, L, XL) or one per line</small>
				<textarea name="sizes" class="form-control" rows="5" required placeholder="S, M, L, XL"><?php echo set_value('sizes'); ?></textarea>
				<?php echo form_error('sizes', '<small class="text-danger">', '</small>'); ?>
			</div>

			<div class="mt-3 d-flex gap-2 justify-content-end">
				<a href="<?php echo base_url('size-charts'); ?>" class="btn btn-outline-secondary">Cancel</a>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</form>
	</div>
</div>
