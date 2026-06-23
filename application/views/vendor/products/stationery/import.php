<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('products/stationery'); ?>"><i class="isax isax-arrow-left me-2"></i>Bulk Import Stationery</a></h6>
	</div>
	<div class="d-flex gap-2">
		<a href="<?php echo base_url('products/stationery/import/template'); ?>" class="btn btn-outline-primary">
			<i class="isax isax-document-download"></i> Download Template
		</a>
		<a href="<?php echo base_url('products/stationery'); ?>" class="btn btn-outline-secondary">Back to List</a>
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
				<?php echo form_open_multipart(base_url('products/stationery/import')); ?>
					<div class="mb-3">
						<label class="form-label">Excel file (.xlsx) <span class="text-danger">*</span></label>
						<input type="file" name="import_file" class="form-control" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
						<small class="text-muted fs-13">Use the downloaded template. Row 1 must contain column headers. Images are not imported.</small>
					</div>
					<button type="submit" class="btn btn-primary">
						<i class="isax isax-import"></i> Import Stationery
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
					<li class="mb-2">Download the template and fill the <strong>Stationery</strong> sheet.</li>
					<li class="mb-2">Required columns match the add form: category, product name, brand, colour, min quantity, description, GST, MRP, and selling price.</li>
					<li class="mb-2">Use dropdowns for category, brand, and colour, or type a <strong>new name</strong> — it will be created automatically.</li>
					<li class="mb-2">Reference sheets list existing categories, brands, and colours.</li>
					<li class="mb-2">If ISBN (or SKU) already exists, the row will <strong>update</strong> that product. Rows without both ISBN and SKU always create new products.</li>
					<li>Products are saved to both the stationery catalog and the unified product table.</li>
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
