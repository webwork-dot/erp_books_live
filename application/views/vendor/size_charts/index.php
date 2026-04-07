<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0 fs-14">Size Charts</h6>
	</div>
	<div>
		<a href="<?php echo base_url('size-charts/add'); ?>" class="btn btn-primary btn-sm">
			<i class="isax isax-add"></i> Add Size Chart
		</a>
	</div>
</div>

<?php if ($this->session->flashdata('success')): ?>
	<div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
	<div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="card mb-3">
	<div class="card-body">
		<form method="get" action="<?php echo base_url('size-charts'); ?>">
			<div class="row g-2">
				<div class="col-md-6">
					<input type="text" name="search" class="form-control form-control-sm" placeholder="Search chart name"
						value="<?php echo htmlspecialchars($this->input->get('search') ?? ''); ?>">
				</div>
				<div class="col-md-3">
					<select name="status" class="form-select form-select-sm">
						<option value="">All Status</option>
						<option value="active" <?php echo (($this->input->get('status') === 'active') ? 'selected' : ''); ?>>Active</option>
						<option value="inactive" <?php echo (($this->input->get('status') === 'inactive') ? 'selected' : ''); ?>>Inactive</option>
					</select>
				</div>
				<div class="col-md-3 d-flex gap-2">
					<button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
					<a href="<?php echo base_url('size-charts'); ?>" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-sm align-middle">
				<thead>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Status</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($size_charts)): ?>
						<?php foreach ($size_charts as $chart): ?>
							<tr>
								<td><?php echo htmlspecialchars($chart['chart_name'] ?? $chart['name'] ?? ''); ?></td>
								<td><?php echo htmlspecialchars($chart['description'] ?? ''); ?></td>
								<td><?php echo htmlspecialchars(ucfirst($chart['status'] ?? '')); ?></td>
								<td class="text-end">
									<a href="<?php echo base_url('size-charts/view/' . $chart['id']); ?>" class="btn btn-outline-info btn-sm">View</a>
									<a href="<?php echo base_url('size-charts/edit/' . $chart['id']); ?>" class="btn btn-outline-primary btn-sm">Edit</a>
									<a href="<?php echo base_url('size-charts/delete/' . $chart['id']); ?>" class="btn btn-outline-danger btn-sm"
										onclick="return confirm('Delete this size chart?');">Delete</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="4" class="text-center text-muted">No size charts found.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php if (!empty($pagination)): ?>
			<div class="mt-2"><?php echo $pagination; ?></div>
		<?php endif; ?>
	</div>
</div>
