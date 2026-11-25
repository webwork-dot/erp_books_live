<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Warehouse</h6>
		<small class="text-muted">Purchase In warehouses</small>
	</div>
	<a href="<?php echo base_url('purchase-in/warehouses/add'); ?>" class="btn btn-primary btn-sm">
		<i class="isax isax-add me-1"></i>Add Warehouse
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
						<th>Warehouse Name</th>
						<th>Companies</th>
						<th>City</th>
						<th>Pincode</th>
						<th>Status</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): ?>
						<?php $sr = 1; foreach ($items as $row): ?>
							<tr>
								<td><?php echo $sr++; ?></td>
								<td class="fw-semibold"><?php echo htmlspecialchars((string)$row['warehouse_name']); ?></td>
								<td><?php echo !empty($row['company_names']) ? htmlspecialchars(implode(', ', $row['company_names'])) : '-'; ?></td>
								<td><?php echo htmlspecialchars((string)($row['city_name'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['pincode'] ?: '-')); ?></td>
								<td>
									<span class="badge <?php echo ((int)$row['status'] === 1) ? 'bg-success' : 'bg-secondary'; ?>">
										<?php echo ((int)$row['status'] === 1) ? 'Active' : 'Inactive'; ?>
									</span>
								</td>
								<td class="text-end">
									<a href="<?php echo base_url('purchase-in/warehouses/edit/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="isax isax-edit"></i></a>
									<a href="<?php echo base_url('purchase-in/warehouses/delete/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this warehouse?');"><i class="isax isax-trash"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr><td colspan="7" class="text-center text-muted py-4">No warehouses yet. <a href="<?php echo base_url('purchase-in/warehouses/add'); ?>">Add your first warehouse</a></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
