<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('products/textbook'); ?>"><i class="isax isax-arrow-left me-2"></i>Bulk Import Textbooks</a></h6>
	</div>
	<div class="d-flex gap-2">
		<a href="<?php echo base_url('products/textbook/import/template'); ?>" class="btn btn-outline-primary">
			<i class="isax isax-document-download"></i> Download Template
		</a>
		<a href="<?php echo base_url('products/textbook'); ?>" class="btn btn-outline-secondary">Back to List</a>
	</div>
</div>
<!-- End Breadcrumb -->

<?php if ($this->session->flashdata('success')): ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php echo htmlspecialchars($this->session->flashdata('success')); ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<?php echo htmlspecialchars($this->session->flashdata('error')); ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-lg-8">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class="border-bottom pb-3 mb-3">Upload Excel File</h2>
				<?php echo form_open_multipart(base_url('products/textbook/import')); ?>
					<div class="mb-3">
						<label class="form-label">Excel file (.xlsx) <span class="text-danger">*</span></label>
						<input type="file" name="import_file" class="form-control" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
						<small class="text-muted fs-13">Use the downloaded template. Row 1 must contain column headers. Images are not imported in this version.</small>
					</div>
					<button type="submit" class="btn btn-primary">
						<i class="isax isax-import"></i> Import Textbooks
					</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class="border-bottom pb-3 mb-3">Instructions</h2>
				<ul class="mb-0 ps-3">
					<li class="mb-2">Download the template and fill the <strong>Textbooks</strong> sheet.</li>
					<li class="mb-2">Use the <strong>dropdown arrows</strong> on Publisher, Board, Grade/Age, Status, etc. (rows 2–501).</li>
					<li class="mb-2">For grades, ages, subjects, or types you can pick one from the list or type several names separated by commas.</li>
					<li class="mb-2">Reference sheets (Publishers, Boards, Grades, etc.) list all valid names.</li>
					<li class="mb-2">If ISBN (or SKU) already exists, the row will <strong>update</strong> that product.</li>
					<li>Products are saved to both the textbook catalog and the unified product table.</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if (!empty($import_results)): ?>
	<div class="card">
		<div class="card-body">
			<h2 class="border-bottom pb-3 mb-3">Import Results</h2>
			<div class="row mb-3">
				<div class="col-md-3"><strong>Total rows:</strong> <?php echo (int) $import_results['total']; ?></div>
				<div class="col-md-3 text-success"><strong>Created:</strong> <?php echo (int) $import_results['created']; ?></div>
				<div class="col-md-3 text-primary"><strong>Updated:</strong> <?php echo (int) $import_results['updated']; ?></div>
				<div class="col-md-3 text-danger"><strong>Failed:</strong> <?php echo (int) $import_results['failed']; ?></div>
			</div>
			<?php if (!empty($import_results['rows'])): ?>
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead>
							<tr>
								<th style="width:80px;">Row</th>
								<th style="width:100px;">Status</th>
								<th>Message</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($import_results['rows'] as $row): ?>
								<tr class="<?php echo $row['status'] === 'failed' ? 'table-danger' : ($row['status'] === 'updated' ? 'table-info' : ''); ?>">
									<td><?php echo (int) $row['line']; ?></td>
									<td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
									<td><?php echo htmlspecialchars($row['message']); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
