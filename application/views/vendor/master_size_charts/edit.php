<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('master-size-charts'); ?>"><i class="isax isax-arrow-left me-1"></i>Edit Master Size Chart</a></h6>
	</div>
</div>

<div class="card shadow-sm border-0">
	<div class="card-body">
		<form id="master-size-chart-form" method="post" action="<?php echo base_url('master-size-charts/edit/' . (int) $chart['id']); ?>">
			<input type="hidden"
				name="<?php echo $this->security->get_csrf_token_name(); ?>"
				value="<?php echo $this->security->get_csrf_hash(); ?>">
			
			<div class="row">
				<div class="col-md-8">
					<div class="mb-3">
						<label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
						<input type="text" name="chart_name" class="form-control" placeholder="e.g. Boys Shirt Size Chart" required 
							value="<?php echo set_value('chart_name', $chart['name'] ?? ''); ?>">
						<?php echo form_error('chart_name', '<small class="text-danger">', '</small>'); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mb-3">
						<label class="form-label fw-semibold">Status</label>
						<select name="status" class="form-select">
							<option value="active" <?php echo set_select('status', 'active', ($chart['status'] ?? '') === 'active'); ?>>Active</option>
							<option value="inactive" <?php echo set_select('status', 'inactive', ($chart['status'] ?? '') === 'inactive'); ?>>Inactive</option>
						</select>
					</div>
				</div>
			</div>

			<!-- Backward Compatibility: Legacy image gallery -->
			<?php if (!empty($images)): ?>
				<div class="mb-4 bg-light p-3 rounded border">
					<label class="form-label fw-semibold text-danger d-flex align-items-center">
						<i class="isax isax-image me-2"></i> Legacy Size Chart Images
					</label>
					<div class="row g-2">
						<?php foreach ($images as $img): ?>
							<div class="col-6 col-md-3">
								<div class="border rounded p-1 bg-white position-relative">
									<?php
									$msc_stored = trim($img['image_path']);
									if (strpos($msc_stored, 'http://') === 0 || strpos($msc_stored, 'https://') === 0) {
										$msc_img_url = $msc_stored;
									} else {
										$msc_img_url = get_vendor_domain_url() . '/' . ltrim($msc_stored, '/');
									}
									?>
									<img src="<?php echo htmlspecialchars($msc_img_url, ENT_QUOTES, 'UTF-8'); ?>"
										alt="" class="img-fluid rounded" style="max-height: 120px; width: 100%; object-fit: cover;">
									<div class="form-check mt-1">
										<input class="form-check-input" type="checkbox" name="remove_image_ids[]" value="<?php echo (int) $img['id']; ?>" id="rm_<?php echo (int) $img['id']; ?>">
										<label class="form-check-label small fw-semibold text-muted" for="rm_<?php echo (int) $img['id']; ?>">Remove</label>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<small class="text-muted d-block mt-2">
						<i class="isax isax-info-circle me-1"></i> These are legacy images. Once you build and save a table below, the storefront will display the table. You can tick "Remove" and click Save to permanently delete legacy images.
					</small>
				</div>
			<?php endif; ?>

			<hr class="my-4 text-muted">

			<h5 class="card-title mb-3 d-flex align-items-center">
				<i class="isax isax-grid-3 me-2 text-primary fs-18"></i> Size Chart Table Builder
			</h5>

			<div class="row g-3 mb-4 bg-light p-3 rounded border">
				<!-- Column Management -->
				<div class="col-md-6 border-end pr-md-4">
					<label class="form-label fw-semibold text-dark mb-2">1. Add Measurement Columns</label>
					<div class="input-group input-group-sm mb-2">
						<input type="text" id="new-column-input" class="form-control" placeholder="e.g., Chest (in), Length (in), Sleeve">
						<button class="btn btn-primary d-flex align-items-center" type="button" id="add-column-btn">
							<i class="isax isax-add me-1"></i> Add Column
						</button>
					</div>
					<small class="text-muted d-block">Add the parameters you want to measure (e.g. Chest, Waist, Length, Shoulder).</small>
				</div>

				<!-- Row Management / Size Selection -->
				<div class="col-md-6 ps-md-4">
					<label class="form-label fw-semibold text-dark mb-2">2. Add Size Rows</label>
					
					<!-- Option A: Import from size chart -->
					<div class="row g-2 mb-2">
						<div class="col-8">
							<select id="import-chart-select" class="form-select form-select-sm">
								<option value="">-- Load from Size Chart --</option>
								<?php
								$charts_processed = [];
								if (!empty($all_sizes_grouped)):
									foreach ($all_sizes_grouped as $item):
										if (in_array($item['chart_id'], $charts_processed)) continue;
										$charts_processed[] = $item['chart_id'];
										?>
										<option value="<?php echo htmlspecialchars($item['chart_id']); ?>">
											<?php echo htmlspecialchars($item['chart_name']); ?>
										</option>
										<?php
									endforeach;
								endif;
								?>
							</select>
						</div>
						<div class="col-4">
							<button class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center" type="button" id="import-chart-btn">
								<i class="isax isax-import me-1"></i> Load
							</button>
						</div>
					</div>

					<!-- Option B: Custom row name -->
					<div class="input-group input-group-sm">
						<input type="text" id="new-row-input" class="form-control" placeholder="e.g., S, M, L or 28, 30, 32">
						<button class="btn btn-outline-secondary d-flex align-items-center" type="button" id="add-row-btn">
							<i class="isax isax-add me-1"></i> Add Size
						</button>
					</div>
				</div>
			</div>

			<!-- Dynamic Table Container -->
			<div class="table-responsive rounded border mb-4">
				<table class="table table-bordered table-hover align-middle mb-0" id="size-chart-table">
					<thead class="table-light">
						<tr id="table-header-row">
							<th style="min-width: 120px;" class="bg-white">Size</th>
							<!-- Columns will be added dynamically here -->
							<th style="width: 80px;" class="text-center bg-white">Action</th>
						</tr>
					</thead>
					<tbody id="table-body">
						<!-- Rows will be added dynamically here -->
						<tr id="no-rows-alert">
							<td colspan="2" class="text-center py-4 text-muted">
								<i class="isax isax-info-circle d-block mb-2 fs-24 text-secondary"></i>
								No sizes or columns added yet. Add columns and sizes above to construct your table.
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Hidden input to store JSON data -->
			<input type="hidden" name="chart_data" id="chart-data-json" value="">

			<!-- Action Buttons -->
			<div class="mt-4 d-flex gap-2 justify-content-end border-top pt-3">
				<a href="<?php echo base_url('master-size-charts'); ?>" class="btn btn-outline-secondary px-4">Cancel</a>
				<button type="submit" class="btn btn-primary px-4 fw-semibold">Save Size Chart</button>
			</div>
		</form>
	</div>
</div>

<style>
#size-chart-table th, #size-chart-table td {
	vertical-align: middle;
	padding: 0.6rem 0.8rem;
}
.column-header-container {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 6px;
}
.column-header-container span {
	font-weight: 600;
}
.btn-delete-col {
	padding: 2px 5px;
	font-size: 10px;
	border: none;
	background: transparent;
	color: #dc3545;
	border-radius: 4px;
	cursor: pointer;
}
.btn-delete-col:hover {
	background: rgba(220, 53, 69, 0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const allSizesGrouped = <?php echo json_encode($all_sizes_grouped ?? []); ?>;
	
	// Load initial chart data from DB JSON if available
	const initialDbData = <?php echo !empty($chart['chart_data']) ? $chart['chart_data'] : 'null'; ?>;

	const tableHeaderRow = document.getElementById('table-header-row');
	const tableBody = document.getElementById('table-body');
	const addColumnBtn = document.getElementById('add-column-btn');
	const newColumnInput = document.getElementById('new-column-input');
	const addRowBtn = document.getElementById('add-row-btn');
	const newRowInput = document.getElementById('new-row-input');
	const importChartSelect = document.getElementById('import-chart-select');
	const importChartBtn = document.getElementById('import-chart-btn');
	const form = document.getElementById('master-size-chart-form');
	const chartDataJsonInput = document.getElementById('chart-data-json');
	const noRowsAlert = document.getElementById('no-rows-alert');

	let columns = []; // Array of column names (excluding primary 'Size')
	let rows = [];    // Array of size row objects: { size_name: string, values: { colName: string } }

	// Pre-populate table if data exists
	if (initialDbData && Array.isArray(initialDbData.columns) && Array.isArray(initialDbData.rows)) {
		columns = initialDbData.columns;
		rows = initialDbData.rows;
		renderTable();
	}

	// Helper to check if size row already exists
	function sizeRowExists(name) {
		return rows.some(row => row.size_name.toLowerCase() === name.toLowerCase());
	}

	// Helper to check if column already exists
	function columnExists(name) {
		return columns.some(col => col.toLowerCase() === name.toLowerCase());
	}

	// Update view table representation
	function renderTable() {
		// 1. Clear dynamic parts from header
		const thElements = Array.from(tableHeaderRow.querySelectorAll('th'));
		// Keep the first (Size) and last (Action) headers
		thElements.forEach((th, idx) => {
			if (idx > 0 && idx < thElements.length - 1) {
				th.remove();
			}
		});

		// 2. Insert column headers before the last (Action) column
		const actionTh = tableHeaderRow.querySelector('th:last-child');
		columns.forEach((colName) => {
			const th = document.createElement('th');
			th.className = 'text-nowrap';
			th.innerHTML = `
				<div class="column-header-container">
					<span>${escapeHtml(colName)}</span>
					<button type="button" class="btn-delete-col" data-col="${escapeHtml(colName)}" title="Delete Column">
						<i class="isax isax-trash fs-12"></i>
					</button>
				</div>
			`;
			tableHeaderRow.insertBefore(th, actionTh);
		});

		// 3. Clear and rebuild table body
		tableBody.innerHTML = '';
		if (rows.length === 0) {
			tableBody.appendChild(noRowsAlert);
			// Set colSpan of noRowsAlert
			noRowsAlert.querySelector('td').colSpan = columns.length + 2;
			return;
		}

		rows.forEach((row, rowIdx) => {
			const tr = document.createElement('tr');

			// First Cell: Size name (editable input)
			const tdSize = document.createElement('td');
			tdSize.innerHTML = `
				<input type="text" class="form-control form-control-sm size-name-input fw-semibold" 
					value="${escapeHtml(row.size_name)}" data-row-idx="${rowIdx}">
			`;
			tr.appendChild(tdSize);

			// Measurement Cells
			columns.forEach((colName) => {
				const tdVal = document.createElement('td');
				const currentVal = row.values[colName] || '';
				tdVal.innerHTML = `
					<input type="text" class="form-control form-control-sm cell-value-input text-center" 
						value="${escapeHtml(currentVal)}" data-row-idx="${rowIdx}" data-col="${escapeHtml(colName)}" placeholder="-">
				`;
				tr.appendChild(tdVal);
			});

			// Action Cell: Delete Row
			const tdAction = document.createElement('td');
			tdAction.className = 'text-center';
			tdAction.innerHTML = `
				<button type="button" class="btn btn-outline-danger btn-sm btn-delete-row" data-row-idx="${rowIdx}" title="Delete Row">
					<i class="isax isax-trash fs-14"></i>
				</button>
			`;
			tr.appendChild(tdAction);

			tableBody.appendChild(tr);
		});

		// Update colSpan of header cells just in case
		actionTh.colSpan = 1;
	}

	// Escape HTML to prevent XSS in building client-side views
	function escapeHtml(text) {
		if (text === undefined || text === null) return '';
		return String(text)
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;")
			.replace(/'/g, "&#039;");
	}

	// Add column handler
	addColumnBtn.addEventListener('click', function() {
		const val = newColumnInput.value.trim();
		if (!val) return;

		// Support comma-separated column additions
		const newCols = val.split(',').map(c => c.trim()).filter(c => c.length > 0);

		newCols.forEach((col) => {
			if (col.toLowerCase() === 'size') {
				alert('Column name "Size" is reserved for the primary row label.');
				return;
			}
			if (columnExists(col)) {
				alert(`Column "${col}" already exists.`);
				return;
			}
			columns.push(col);
			// Add column key to all rows
			rows.forEach(r => {
				if (r.values[col] === undefined) {
					r.values[col] = '';
				}
			});
		});

		newColumnInput.value = '';
		renderTable();
	});

	// Support Enter key on column input
	newColumnInput.addEventListener('keypress', function(e) {
		if (e.key === 'Enter') {
			e.preventDefault();
			addColumnBtn.click();
		}
	});

	// Add row handler
	addRowBtn.addEventListener('click', function() {
		const val = newRowInput.value.trim();
		if (!val) return;

		// Support comma-separated size additions
		const newSizes = val.split(',').map(s => s.trim()).filter(s => s.length > 0);

		newSizes.forEach((size) => {
			if (sizeRowExists(size)) {
				alert(`Size "${size}" is already in the table.`);
				return;
			}
			const newRow = {
				size_name: size,
				values: {}
			};
			columns.forEach(col => {
				newRow.values[col] = '';
			});
			rows.push(newRow);
		});

		newRowInput.value = '';
		renderTable();
	});

	// Support Enter key on row input
	newRowInput.addEventListener('keypress', function(e) {
		if (e.key === 'Enter') {
			e.preventDefault();
			addRowBtn.click();
		}
	});

	// Import size chart handler
	importChartBtn.addEventListener('click', function() {
		const chartId = importChartSelect.value;
		if (!chartId) {
			alert('Please select a size chart to load.');
			return;
		}

		// Filter sizes that belong to selected chart ID
		const sizesToImport = allSizesGrouped.filter(item => String(item.chart_id) === String(chartId));
		if (sizesToImport.length === 0) {
			alert('No active sizes found for the selected size chart.');
			return;
		}

		let addedCount = 0;
		sizesToImport.forEach(item => {
			if (!sizeRowExists(item.size_name)) {
				const newRow = {
					size_name: item.size_name,
					values: {}
				};
				columns.forEach(col => {
					newRow.values[col] = '';
				});
				rows.push(newRow);
				addedCount++;
			}
		});

		if (addedCount > 0) {
			renderTable();
		} else {
			alert('All sizes from this chart are already in the table.');
		}
	});

	// Listen to cell value inputs and update state
	tableBody.addEventListener('input', function(e) {
		if (e.target.classList.contains('cell-value-input')) {
			const rowIdx = parseInt(e.target.dataset.rowIdx, 10);
			const colName = e.target.dataset.col;
			const val = e.target.value;
			if (rows[rowIdx]) {
				rows[rowIdx].values[colName] = val;
			}
		} else if (e.target.classList.contains('size-name-input')) {
			const rowIdx = parseInt(e.target.dataset.rowIdx, 10);
			const val = e.target.value.trim();
			if (rows[rowIdx]) {
				rows[rowIdx].size_name = val;
			}
		}
	});

	// Delete row handler
	tableBody.addEventListener('click', function(e) {
		const button = e.target.closest('.btn-delete-row');
		if (button) {
			const rowIdx = parseInt(button.dataset.rowIdx, 10);
			if (confirm('Are you sure you want to remove this size row?')) {
				rows.splice(rowIdx, 1);
				renderTable();
			}
		}
	});

	// Delete column handler
	tableHeaderRow.addEventListener('click', function(e) {
		const button = e.target.closest('.btn-delete-col');
		if (button) {
			const colName = button.dataset.col;
			if (confirm(`Are you sure you want to delete the column "${colName}"? All values for this column will be discarded.`)) {
				// Remove column from array
				columns = columns.filter(c => c !== colName);
				// Remove values from all rows
				rows.forEach(r => {
					delete r.values[colName];
				});
				renderTable();
			}
		}
	});

	// Form Submission: serialize table to JSON
	form.addEventListener('submit', function(e) {
		// Clean up rows with empty size names
		rows = rows.filter(r => r.size_name.trim().length > 0);

		if (columns.length === 0 && rows.length === 0) {
			// Allow empty chart submission (will clear size chart data)
			chartDataJsonInput.value = '';
			return;
		}

		if (columns.length === 0 || rows.length === 0) {
			alert('To save a size chart table, you must add at least one column and one size row.');
			e.preventDefault();
			return;
		}

		// Check for duplicate sizes
		const sizeNames = rows.map(r => r.size_name.toLowerCase());
		const hasDuplicates = sizeNames.some((val, i) => sizeNames.indexOf(val) !== i);
		if (hasDuplicates) {
			alert('Duplicate size rows detected. Each row must have a unique size name.');
			e.preventDefault();
			return;
		}

		// Serialize to JSON
		const payload = {
			columns: columns,
			rows: rows
		};

		chartDataJsonInput.value = JSON.stringify(payload);
	});
});
</script>
