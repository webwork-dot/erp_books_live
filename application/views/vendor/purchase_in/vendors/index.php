<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Vendors</h6>
		<small class="text-muted">Purchase In suppliers</small>
	</div>
	<a href="<?php echo base_url('purchase-in/vendors/add'); ?>" class="btn btn-primary btn-sm">
		<i class="isax isax-add me-1"></i>Add Vendor
	</a>
</div>

<?php if ($this->session->flashdata('success')): ?>
	<div class="alert alert-success py-2"><?php echo htmlspecialchars((string)$this->session->flashdata('success')); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
	<div class="alert alert-danger py-2"><?php echo htmlspecialchars((string)$this->session->flashdata('error')); ?></div>
<?php endif; ?>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover align-middle mb-0">
				<thead>
					<tr>
						<th>SR</th>
						<th>Vendor Name</th>
						<th>Contact</th>
						<th>Email</th>
						<th>GST No.</th>
						<th>City</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): ?>
						<?php $sr = 1; foreach ($items as $row): ?>
							<tr>
								<td><?php echo $sr++; ?></td>
								<td class="fw-semibold"><?php echo htmlspecialchars((string)$row['vendor_name']); ?></td>
								<td>
									<?php echo htmlspecialchars((string)($row['contact_person_name'] ?: '-')); ?>
									<?php if (!empty($row['contact_person_number'])): ?>
										<div class="text-muted small"><?php echo htmlspecialchars((string)$row['contact_person_number']); ?></div>
									<?php endif; ?>
								</td>
								<td><?php echo htmlspecialchars((string)($row['email'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['gst_no'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['city_name'] ?: '-')); ?></td>
								<td class="text-end">
									<a href="<?php echo base_url('purchase-in/vendors/edit/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="isax isax-edit"></i></a>
									<a href="<?php echo base_url('purchase-in/vendors/delete/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this vendor?');"><i class="isax isax-trash"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr><td colspan="7" class="text-center text-muted py-4">No vendors yet. <a href="<?php echo base_url('purchase-in/vendors/add'); ?>">Add your first vendor</a></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
