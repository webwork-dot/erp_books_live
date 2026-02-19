<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0 fs-14">Edit Courier</h6>
	</div>
	<div>
		<a href="<?php echo base_url('couriers'); ?>" class="btn btn-outline-secondary btn-sm">
			<i class="isax isax-arrow-left me-1"></i>Back
		</a>
	</div>
</div>
<!-- End Header -->

<?php echo form_open(base_url('couriers/edit/' . $courier['id']), array('id' => 'courierForm')); ?>

<div class="row">
	<div class="col-md-12">
		<div class="card mb-3">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Courier Information</h6>
			</div>
			<div class="card-body">
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Courier Name <span class="text-danger">*</span></label>
						<input type="text" name="courier_name" class="form-control" value="<?php echo set_value('courier_name', $courier['courier_name']); ?>" required maxlength="150" placeholder="e.g. Blue Dart, DTDC">
						<?php echo form_error('courier_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					<div class="col-md-6">
						<label class="form-label">Tracking Link</label>
						<input type="url" name="tracking_link" class="form-control" value="<?php echo set_value('tracking_link', $courier['tracking_link']); ?>" maxlength="255" placeholder="https://tracking.example.com/">
					
						<?php echo form_error('tracking_link', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
					<div class="col-md-6">
						<label class="form-label">Status <span class="text-danger">*</span></label>
						<select name="status" class="form-select" required>
							<option value="1" <?php echo set_select('status', '1', $courier['status'] == 1); ?>>Active</option>
							<option value="0" <?php echo set_select('status', '0', $courier['status'] == 0); ?>>Inactive</option>
						</select>
						<?php echo form_error('status', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="d-flex gap-2">
	<button type="submit" class="btn btn-primary">
		<i class="isax isax-tick-circle me-1"></i>Update Courier
	</button>
	<a href="<?php echo base_url('couriers'); ?>" class="btn btn-outline-secondary">
		<i class="isax isax-close-circle me-1"></i>Cancel
	</a>
</div>

<?php echo form_close(); ?>
