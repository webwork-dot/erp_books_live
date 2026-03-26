<div class="card card-body mb-3">
	<div class="d-flex align-items-center justify-content-between">
		<h6>Add New Class</h6>
		<a href="<?php echo base_url('classes'); ?>" class="btn btn-outline-secondary">
			<i class="isax isax-arrow-left ps-0 me-1"></i>Back to List
		</a>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<?php echo form_open(base_url('classes/add')); ?>
				
				<div class="mb-3">
					<label class="form-label">Class Name <span class="text-danger">*</span></label>
					<input type="text" name="class_name" class="form-control <?php echo (form_error('class_name')) ? 'is-invalid' : ''; ?>" value="<?php echo set_value('class_name'); ?>" placeholder="e.g. 1st Grade, Class 10, etc.">
					<?php echo form_error('class_name', '<div class="invalid-feedback">', '</div>'); ?>
				</div>
				
				<div class="d-flex justify-content-end gap-2">
					<button type="submit" class="btn btn-primary">
						<i class="isax isax-add me-1"></i>Create Class
					</button>
					<a href="<?php echo base_url('classes'); ?>" class="btn btn-outline-secondary">Cancel</a>
				</div>
				
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
