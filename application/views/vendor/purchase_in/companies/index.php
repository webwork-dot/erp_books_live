<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Companies</h6>
		<small class="text-muted">Purchase In companies</small>
	</div>
	<a href="<?php echo base_url('purchase-in/companies/add'); ?>" class="btn btn-primary btn-sm">
		<i class="isax isax-add me-1"></i>Add Company
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
						<th>Company Name</th>
						<th>Contact</th>
						<th>Email</th>
						<th>GST No.</th>
						<th>City</th>
						<th>Status</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): ?>
						<?php $sr = 1; foreach ($items as $row): ?>
							<tr>
								<td><?php echo $sr++; ?></td>
								<td class="fw-semibold"><?php echo htmlspecialchars((string)$row['company_name']); ?></td>
								<td>
									<?php echo htmlspecialchars((string)($row['contact_person_name'] ?: '-')); ?>
									<?php if (!empty($row['contact_person_number'])): ?>
										<div class="text-muted small"><?php echo htmlspecialchars((string)$row['contact_person_number']); ?></div>
									<?php endif; ?>
								</td>
								<td><?php echo htmlspecialchars((string)($row['email'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['gst_no'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['city_name'] ?: '-')); ?></td>
								<td>
									<span class="badge <?php echo ((int)$row['status'] === 1) ? 'bg-success' : 'bg-secondary'; ?>">
										<?php echo ((int)$row['status'] === 1) ? 'Active' : 'Inactive'; ?>
									</span>
								</td>
								<td class="text-end">
									<a href="<?php echo base_url('purchase-in/companies/edit/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="isax isax-edit"></i></a>
									<a href="<?php echo base_url('purchase-in/companies/delete/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this company?');"><i class="isax isax-trash"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr><td colspan="8" class="text-center text-muted py-4">No companies yet. <a href="<?php echo base_url('purchase-in/companies/add'); ?>">Add your first company</a></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
