<?php
	$has_textbook = !empty($has_textbook_feature);
	$has_notebook = !empty($has_notebook_feature);
	$has_stationery = !empty($has_stationery_feature);
	$type_labels = array();
	if ($has_textbook) $type_labels[] = 'Textbook';
	if ($has_notebook) $type_labels[] = 'Notebook';
	if ($has_stationery) $type_labels[] = 'Stationery';
?>
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Import Stock</h6>
		<small class="text-muted">First import for each product is saved as opening stock</small>
	</div>
	<div class="d-flex gap-2">
		<a href="<?php echo base_url('products/stock_management/import/template'); ?>" class="btn btn-outline-primary btn-sm">
			<i class="isax isax-document-download me-1"></i>Download Excel Template
		</a>
		<a href="<?php echo base_url('products/stock_management'); ?>" class="btn btn-outline-secondary btn-sm">Back</a>
	</div>
</div>

<?php if ($this->session->flashdata('success')): ?>
	<div class="alert alert-success py-2"><?php echo htmlspecialchars((string)$this->session->flashdata('success')); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
	<div class="alert alert-danger py-2"><?php echo htmlspecialchars((string)$this->session->flashdata('error')); ?></div>
<?php endif; ?>

<?php if (empty($tables_ready)): ?>
	<div class="alert alert-warning">Stock tables are not available yet.</div>
<?php else: ?>
<div class="row g-3">
	<div class="col-lg-7">
		<div class="card">
			<div class="card-body">
				<h6 class="mb-3">Upload Excel</h6>
				<?php echo form_open_multipart(base_url('products/stock_management/import')); ?>
					<div class="mb-3">
						<label class="form-label">Excel file (.xlsx) <span class="text-danger">*</span></label>
						<input type="file" name="import_file" class="form-control form-control-sm" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
						<small class="text-muted">Use the template. Fill <strong>qty</strong> for rows to import. Leave qty blank to skip.</small>
					</div>
					<button type="submit" class="btn btn-primary btn-sm">
						<i class="isax isax-import me-1"></i>Import Stock
					</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="card">
			<div class="card-body">
				<h6 class="mb-3">Template columns (same as Update Stock)</h6>
				<ul class="mb-2 ps-3 small">
					<li class="mb-1"><strong>product_name*</strong>, <strong>type*</strong>, <strong>qty*</strong></li>
					<?php if ($has_textbook || $has_notebook): ?>
						<li class="mb-1">Textbook / Notebook: publisher, board, grade_age_type, grade, age</li>
					<?php endif; ?>
					<?php if ($has_stationery): ?>
						<li class="mb-1">Stationery: category, brand, colour</li>
					<?php endif; ?>
				</ul>
				<ul class="mb-0 ps-3 small">
					<li class="mb-1">Type dropdown: <?php echo !empty($type_labels) ? htmlspecialchars(implode(', ', $type_labels)) : 'none (enable catalog features)'; ?></li>
					<li class="mb-1">Dropdown lists use names (no IDs).</li>
					<li class="mb-1">No stock history → <strong>Opening Stock</strong>.</li>
					<li>Existing history → import adjustment.</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if (!empty($import_results) && empty($import_results['error'])): ?>
	<div class="card mt-3">
		<div class="card-body">
			<h6 class="mb-3">Import Results</h6>
			<div class="row g-2 mb-3 small">
				<div class="col-md-2"><strong>Total:</strong> <?php echo (int)$import_results['total']; ?></div>
				<div class="col-md-2 text-success"><strong>Opening:</strong> <?php echo (int)$import_results['opening']; ?></div>
				<div class="col-md-2 text-primary"><strong>Updated:</strong> <?php echo (int)$import_results['updated']; ?></div>
				<div class="col-md-2 text-muted"><strong>Skipped:</strong> <?php echo (int)$import_results['skipped']; ?></div>
				<div class="col-md-2 text-danger"><strong>Failed:</strong> <?php echo (int)$import_results['failed']; ?></div>
			</div>
			<?php if (!empty($import_results['rows'])): ?>
				<div class="table-responsive" style="max-height:420px; overflow:auto;">
					<table class="table table-sm table-bordered mb-0">
						<thead>
							<tr>
								<th style="width:70px;">Row</th>
								<th style="width:100px;">Status</th>
								<th>Message</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($import_results['rows'] as $row): ?>
								<?php
									$cls = '';
									if ($row['status'] === 'failed') $cls = 'table-danger';
									elseif ($row['status'] === 'opening') $cls = 'table-success';
									elseif ($row['status'] === 'updated') $cls = 'table-info';
								?>
								<tr class="<?php echo $cls; ?>">
									<td><?php echo (int)$row['line']; ?></td>
									<td><?php echo htmlspecialchars(ucfirst((string)$row['status'])); ?></td>
									<td><?php echo htmlspecialchars((string)$row['message']); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
<?php endif; ?>
