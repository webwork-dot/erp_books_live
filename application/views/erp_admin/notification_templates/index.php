<div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 1.25rem;">
	<h1 style="margin:0;">Notification Types</h1>
	<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-outline-secondary">Back to Vendors</a>
</div>

<?php
$eventsById = [];
if (!empty($events)) {
	foreach ($events as $e) {
		$eventsById[(int)$e['id']] = $e;
	}
}

?>

<div class="row">
			<div class="col-lg-7">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title mb-3">Event Types</h5>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Key</th>
										<th>Title</th>
										<th>Status</th>
										<th class="text-end">Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($events)): ?>
										<?php foreach ($events as $e): ?>
											<tr>
												<td><code><?php echo htmlspecialchars($e['event_key']); ?></code></td>
												<td><?php echo htmlspecialchars($e['title']); ?></td>
												<td>
													<span class="badge <?php echo !empty($e['is_active']) ? 'badge-success' : 'badge-danger'; ?>">
														<?php echo !empty($e['is_active']) ? 'Active' : 'Inactive'; ?>
													</span>
												</td>
												<td class="text-end">
													<a href="<?php echo base_url('erp-admin/notification-templates?edit_event_id=' . (int)$e['id']); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
														<i class="isax isax-edit"></i>
													</a>
													<a href="<?php echo base_url('erp-admin/notification-templates/delete_event/' . (int)$e['id']); ?>"
														onclick="return confirm('Delete this event? This may delete related templates too.');"
														class="btn btn-sm btn-outline-danger" title="Delete">
														<i class="isax isax-trash"></i>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="4" class="text-center text-muted">No events found</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
						<small class="text-muted">Use event keys like <code>order_placed</code>, <code>order_shipped</code>, etc.</small>
					</div>
				</div>
			</div>

			<div class="col-lg-5">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title mb-3"><?php echo !empty($edit_event) ? 'Edit Event' : 'Add Event'; ?></h5>
						<?php echo form_open('erp-admin/notification-templates/save_event'); ?>
							<input type="hidden" name="event_id" value="<?php echo !empty($edit_event['id']) ? (int)$edit_event['id'] : 0; ?>">

							<div class="form-group mb-2">
								<label>Event Key *</label>
								<input type="text" name="event_key" class="form-control" value="<?php echo htmlspecialchars($edit_event['event_key'] ?? ''); ?>" required>
							</div>

							<div class="form-group mb-2">
								<label>Title *</label>
								<input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($edit_event['title'] ?? ''); ?>" required>
							</div>

							<div class="form-group mb-3">
								<label>Active</label>
								<select name="is_active" class="form-control">
									<option value="1" <?php echo (isset($edit_event['is_active']) && (int)$edit_event['is_active'] === 0) ? '' : 'selected'; ?>>Yes</option>
									<option value="0" <?php echo (isset($edit_event['is_active']) && (int)$edit_event['is_active'] === 0) ? 'selected' : ''; ?>>No</option>
								</select>
							</div>

							<div style="display:flex; gap: .5rem;">
								<button type="submit" class="btn btn-primary"><?php echo !empty($edit_event) ? 'Save Changes' : 'Create Event'; ?></button>
								<a href="<?php echo base_url('erp-admin/notification-templates'); ?>" class="btn btn-outline-secondary">Clear</a>
							</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
</div>
