<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('erp-admin/users'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit User</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h6 class="mb-3">Basic Details</h6>
				<?php echo form_open('erp-admin/users/edit/' . $user['id']); ?>
					<div class="row gx-3">
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Username</label>
								<input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
								<small class="text-muted fs-13">Username cannot be changed</small>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Email <span class="text-danger">*</span></label>
								<input type="email" name="email" id="email" class="form-control" value="<?php echo set_value('email', $user['email']); ?>" required>
								<?php echo form_error('email', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">New Password</label>
								<div class="position-relative">
									<input type="password" name="password" id="password" class="form-control">
									<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('password')">
										<i class="isax isax-eye" id="password-eye"></i>
									</span>
								</div>
								<small class="text-muted fs-13">Leave blank to keep current password</small>
								<?php echo form_error('password', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Role <span class="text-danger">*</span></label>
								<select name="role_id" id="role_id" class="select" required>
									<option value="">Select Role</option>
									<option value="1" <?php echo set_select('role_id', '1', $user['role_id'] == 1); ?>>Super Admin</option>
								</select>
								<?php echo form_error('role_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Status</label>
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" name="status" id="status" value="1" <?php echo set_checkbox('status', '1', $user['status'] == 1); ?>>
									<label class="form-check-label" for="status">Active</label>
								</div>
								<?php echo form_error('status', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
					</div>
					<div class="border-top my-3 pt-3">
						<div class="d-flex align-items-center justify-content-end gap-2">
							<a href="<?php echo base_url('erp-admin/users'); ?>" class="btn btn-outline">Cancel</a>
							<button type="submit" class="btn btn-primary">Save Changes</button>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
