<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-1">Stock Management</h6>
		<p class="text-muted mb-0">Simple stock entry screen for uniforms/books with size-wise rows.</p>
	</div>
	<div class="d-flex gap-2">
		<button type="button" class="btn btn-primary btn-sm" id="openStockAdjustModalBtn">Update Stock</button>
		<a href="<?php echo base_url('products/stock_management/export'); ?>" class="btn btn-outline-success btn-sm">Download Excel (CSV)</a>
	</div>
</div>
<style>
.stock-table-wrap {
	overflow-x: auto;
	-webkit-overflow-scrolling: touch;
}
.stock-table-wrap .table {
	min-width: 1280px;
	margin-bottom: 0;
}
.stock-table-wrap .table th,
.stock-table-wrap .table td {
	vertical-align: middle;
	white-space: nowrap;
}
.stock-col-product {
	min-width: 140px;
	max-width: 180px;
}
.stock-col-school {
	min-width: 150px;
	max-width: 210px;
	white-space: normal !important;
	line-height: 1.2;
}
.stock-col-action {
	min-width: 120px;
}
.stock-col-action .d-flex {
	flex-wrap: nowrap;
}
.stock-adjust-modal .modal-dialog {
	max-width: 92vw;
}
.stock-adjust-modal .modal-content {
	min-height: 62vh;
}
.stock-adjust-modal .modal-body .table-responsive table {
	min-width: 1200px;
}
.stock-adjust-modal .qty-col {
	min-width: 70px;
	width: 70px;
}
.stock-adjust-modal .qty-input {
	min-width: 60px;
	width: 60px;
}
</style>

<?php if (!$tables_ready): ?>
	<div class="alert alert-warning">
		Stock tables are not available yet. Enable <code>stock_management</code> and sync vendor features first.
	</div>
<?php else: ?>
	<div class="card mb-3">
		<div class="card-body">
			<form method="get" action="<?php echo base_url('products/stock_management'); ?>">
			<div class="row g-3 align-items-end">
				<div class="col-md-3">
					<label class="form-label mb-1">Search</label>
					<input type="text" name="q" value="<?php echo htmlspecialchars((string)$search_q); ?>" class="form-control form-control-sm" placeholder="Product, size, type, id">
				</div>
				<div class="col-md-2">
					<label class="form-label mb-1">School</label>
					<select name="school_id" class="form-select form-select-sm">
						<option value="">All Schools</option>
						<?php foreach ($schools as $s): ?>
							<option value="<?php echo (int)$s['id']; ?>" <?php echo ((int)$filter_school_id === (int)$s['id']) ? 'selected' : ''; ?>>
								<?php echo htmlspecialchars((string)$s['school_name']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label mb-1">Board</label>
					<select name="board_id" class="form-select form-select-sm">
						<option value="">All Boards</option>
						<?php foreach ($boards as $b): ?>
							<option value="<?php echo (int)$b['id']; ?>" <?php echo ((int)$filter_board_id === (int)$b['id']) ? 'selected' : ''; ?>>
								<?php echo htmlspecialchars((string)$b['board_name']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label mb-1">Low Stock Alert Threshold</label>
					<input type="number" min="0" name="low_stock" value="<?php echo (int)$low_stock_threshold; ?>" class="form-control form-control-sm">
				</div>
				<div class="col-md-3 d-flex gap-2">
					<button type="submit" class="btn btn-sm btn-primary">Apply</button>
					<a href="<?php echo base_url('products/stock_management'); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
					<small class="text-muted align-self-center">Rows with quantity <= <?php echo (int)$low_stock_threshold; ?> are highlighted as low stock.</small>
				</div>
			</div>
			</form>
		</div>
	</div>

		<div class="card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<strong>Stock List</strong>
			</div>
			<div class="card-body p-0">
				<div class="stock-table-wrap">
					<table class="table table-sm table-bordered mb-0">
						<thead>
							<tr>
								<th style="min-width: 70px;">Image</th>
								<th class="stock-col-product">Product Name</th>
								<th style="min-width: 140px;">Uniform Type</th>
								<th style="min-width: 110px;">Size</th>
								<th style="min-width: 90px;">Gender</th>
								<th class="stock-col-school">School</th>
								<th style="min-width: 110px;">Board</th>
								<th style="min-width: 120px;">Grade</th>
								<th style="min-width: 140px;">Total Qty</th>
								<th style="min-width: 170px;">Last Update</th>
								<th style="min-width: 160px;">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($stock_rows)): ?>
								<?php foreach ($stock_rows as $i => $row): ?>
									<?php $is_low = ((float)$row['qty_available'] <= (float)$low_stock_threshold); ?>
									<tr class="<?php echo $is_low ? 'table-warning' : ''; ?>">
										<td>
											<?php if (!empty($row['image_path'])): ?>
												<?php
													$img_path = trim((string)$row['image_path']);
													$img_url = (strpos($img_path, 'http://') === 0 || strpos($img_path, 'https://') === 0) ? $img_path : (rtrim(get_vendor_domain_url(), '/') . '/' . ltrim($img_path, '/'));
												?>
												<img src="<?php echo htmlspecialchars($img_url); ?>" alt="Item" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
											<?php else: ?>
												<span class="text-muted">-</span>
											<?php endif; ?>
										</td>
										<td class="stock-col-product"><?php echo htmlspecialchars((string)$row['product_name']); ?></td>
										<td><?php echo htmlspecialchars((string)$row['uniform_type_name']); ?></td>
										<td><?php echo htmlspecialchars((string)$row['variation_key']); ?></td>
										<td><?php echo htmlspecialchars((string)$row['gender']); ?></td>
										<td class="stock-col-school">
											<?php echo htmlspecialchars((string)$row['school_name']); ?>
											<?php if (!empty($row['branch_name'])): ?>
												<div><small class="text-muted"><?php echo htmlspecialchars((string)$row['branch_name']); ?></small></div>
											<?php endif; ?>
										</td>
										<td><?php echo htmlspecialchars((string)$row['board_name']); ?></td>
										<td><?php echo htmlspecialchars((string)$row['grade_name']); ?></td>
										<td><strong><?php echo (int)round((float)$row['qty_available']); ?></strong></td>
										<td>
											<?php if (!empty($row['last_stock_update'])): ?>
												<?php echo date('d-m-Y h:i:s A', strtotime($row['last_stock_update'])); ?>
											<?php else: ?>
												<span class="text-muted">Never</span>
											<?php endif; ?>
										</td>
										<td>
											<div class="d-flex gap-1">
												<button
													type="button"
													class="btn btn-sm btn-outline-primary stock-btn"
													data-item-type="<?php echo htmlspecialchars((string)$row['item_type']); ?>"
													data-item-ref-id="<?php echo (int)$row['item_ref_id']; ?>"
													data-variation-key="<?php echo htmlspecialchars((string)$row['variation_key']); ?>"
													data-school-id="<?php echo isset($row['school_id']) ? (int)$row['school_id'] : 0; ?>"
													data-branch-id="<?php echo isset($row['branch_id']) ? (int)$row['branch_id'] : 0; ?>"
													data-product-name="<?php echo htmlspecialchars((string)$row['product_name']); ?>"
													data-uniform-type="<?php echo htmlspecialchars((string)$row['uniform_type_name']); ?>"
													data-gender="<?php echo htmlspecialchars((string)$row['gender']); ?>"
													data-school="<?php echo htmlspecialchars((string)$row['school_name']); ?>"
													data-branch="<?php echo htmlspecialchars((string)$row['branch_name']); ?>"
													data-board="<?php echo htmlspecialchars((string)$row['board_name']); ?>"
													data-grade="<?php echo htmlspecialchars((string)$row['grade_name']); ?>"
													data-current-qty="<?php echo (int)round((float)$row['qty_available']); ?>">
													Stock
												</button>
												<button
													type="button"
													class="btn btn-sm btn-outline-secondary history-btn"
													data-item-type="<?php echo htmlspecialchars((string)$row['item_type']); ?>"
													data-item-ref-id="<?php echo (int)$row['item_ref_id']; ?>"
													data-variation-key="<?php echo htmlspecialchars((string)$row['variation_key']); ?>"
													data-product-name="<?php echo htmlspecialchars((string)$row['product_name']); ?>"
													data-uniform-type="<?php echo htmlspecialchars((string)$row['uniform_type_name']); ?>"
													data-gender="<?php echo htmlspecialchars((string)$row['gender']); ?>"
													data-school="<?php echo htmlspecialchars((string)$row['school_name']); ?>"
													data-branch="<?php echo htmlspecialchars((string)$row['branch_name']); ?>"
													data-board="<?php echo htmlspecialchars((string)$row['board_name']); ?>"
													data-grade="<?php echo htmlspecialchars((string)$row['grade_name']); ?>">
													History
												</button>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="12" class="text-center text-muted py-3">No product rows found for stock management.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	<?php if (!empty($total_pages) && $total_pages > 1): ?>
	<div class="d-flex justify-content-end mt-3">
		<nav>
			<ul class="pagination pagination-sm mb-0">
				<?php
				$base_params = array(
					'q' => $search_q,
					'school_id' => $filter_school_id,
					'board_id' => $filter_board_id,
					'low_stock' => $low_stock_threshold
				);
				?>
				<?php if ($current_page > 1): ?>
					<li class="page-item">
						<a class="page-link" href="<?php echo base_url('products/stock_management?' . http_build_query(array_merge($base_params, array('page' => $current_page - 1)))); ?>">Prev</a>
					</li>
				<?php endif; ?>
				<?php
					$start_page = max(1, (int)$current_page - 2);
					$end_page = min((int)$total_pages, (int)$current_page + 2);
					if ($start_page > 1):
				?>
					<li class="page-item">
						<a class="page-link" href="<?php echo base_url('products/stock_management?' . http_build_query(array_merge($base_params, array('page' => 1)))); ?>">1</a>
					</li>
					<?php if ($start_page > 2): ?>
						<li class="page-item disabled"><span class="page-link">...</span></li>
					<?php endif; ?>
				<?php endif; ?>

<div class="modal fade stock-adjust-modal" id="stockAdjustModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen-lg-down modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Stock</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-2 mb-2">
					<div class="col-md-5 position-relative">
						<input type="text" class="form-control form-control-sm" id="adjustSearchQ" placeholder="Search product/size/school" autocomplete="off">
						<div id="adjustSuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index:1056; display:none; max-height:220px; overflow:auto;"></div>
					</div>
					<div class="col-md-2 d-grid"><button type="button" class="btn btn-sm btn-outline-primary" id="adjustSearchBtn">Search</button></div>
				</div>
				<div class="table-responsive mb-3">
					<table class="table table-sm table-bordered">
						<thead><tr><th>Product</th><th>Uniform Type</th><th>Size</th><th>Gender</th><th>School</th><th>Board</th><th>Grade</th><th>Current</th><th>Type</th><th class="qty-col">Qty</th><th>Remark</th><th></th></tr></thead>
						<tbody id="adjustSelectedBody"><tr><td colspan="12" class="text-center text-muted">No selected products</td></tr></tbody>
					</table>
				</div>
				<form id="stockAdjustForm" method="post" action="<?php echo base_url('products/stock_management/adjust_bulk'); ?>">
					<div id="adjustHiddenInputs"></div>
					<div class="mt-3 text-end"><button type="submit" class="btn btn-primary btn-sm">Update Stock</button></div>
				</form>
			</div>
		</div>
	</div>
</div>

				<?php for ($p = $start_page; $p <= $end_page; $p++): ?>
					<li class="page-item <?php echo ($p === (int)$current_page) ? 'active' : ''; ?>">
						<a class="page-link" href="<?php echo base_url('products/stock_management?' . http_build_query(array_merge($base_params, array('page' => $p)))); ?>"><?php echo $p; ?></a>
					</li>
				<?php endfor; ?>

				<?php if ($end_page < (int)$total_pages): ?>
					<?php if ($end_page < (int)$total_pages - 1): ?>
						<li class="page-item disabled"><span class="page-link">...</span></li>
					<?php endif; ?>
					<li class="page-item">
						<a class="page-link" href="<?php echo base_url('products/stock_management?' . http_build_query(array_merge($base_params, array('page' => $total_pages)))); ?>"><?php echo (int)$total_pages; ?></a>
					</li>
				<?php endif; ?>
				<?php if ($current_page < $total_pages): ?>
					<li class="page-item">
						<a class="page-link" href="<?php echo base_url('products/stock_management?' . http_build_query(array_merge($base_params, array('page' => $current_page + 1)))); ?>">Next</a>
					</li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
	<?php endif; ?>

<?php endif; ?>
<div class="modal fade" id="stockHistoryModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Stock History</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-2">
					<strong id="historyProductTitle"></strong>
					<div class="small text-muted">Opening: <span id="historyOpeningQty">0</span> | Current: <span id="historyCurrentQty">0</span></div>
				</div>
				<div class="table-responsive mb-2">
					<table class="table table-sm table-bordered mb-0">
						<tbody>
							<tr>
								<th style="width: 140px;">Product Name</th><td id="historyMetaProduct">-</td>
								<th style="width: 140px;">Uniform Type</th><td id="historyMetaUniformType">-</td>
							</tr>
							<tr>
								<th>Size</th><td id="historyMetaSize">-</td>
								<th>Gender</th><td id="historyMetaGender">-</td>
							</tr>
							<tr>
								<th>School</th><td id="historyMetaSchool">-</td>
								<th>Board</th><td id="historyMetaBoard">-</td>
							</tr>
							<tr>
								<th>Grade</th><td id="historyMetaGrade">-</td>
								<th></th><td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="table-responsive">
					<table class="table table-sm table-bordered mb-0">
						<thead>
							<tr>
								<th>Date</th>
								<th>IN/OUT</th>
								<th>Source</th>
								<th>Qty</th>
								<th>Before</th>
								<th>After</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody id="historyTableBody">
							<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>
						</tbody>
					</table>
				</div>
				<div class="d-flex justify-content-end mt-2">
					<nav>
						<ul class="pagination pagination-sm mb-0" id="historyPagination"></ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var adjustModalEl = document.getElementById('stockAdjustModal');
	var adjustModal = adjustModalEl ? new bootstrap.Modal(adjustModalEl) : null;

	var selectedAdjustItems = [];

	function itemKey(item) {
		return (item.item_type || '') + '|' + (item.item_ref_id || '') + '|' + (item.variation_key || 'default');
	}

	function renderSelectedAdjustItems() {
		var body = document.getElementById('adjustSelectedBody');
		var hiddenWrap = document.getElementById('adjustHiddenInputs');
		if (!selectedAdjustItems.length) {
			body.innerHTML = '<tr><td colspan="12" class="text-center text-muted">No selected products</td></tr>';
			hiddenWrap.innerHTML = '';
			return;
		}
		var html = '';
		var hidden = '';
		selectedAdjustItems.forEach(function(item, idx) {
			var schoolText = item.school_name || '-';
			if (item.branch_name) schoolText += ' (' + item.branch_name + ')';
			html += '<tr>' +
				'<td>' + (item.product_name || '-') + '</td>' +
				'<td>' + (item.uniform_type_name || '-') + '</td>' +
				'<td>' + (item.variation_key || '-') + '</td>' +
				'<td>' + (item.gender || '-') + '</td>' +
				'<td>' + schoolText + '</td>' +
				'<td>' + (item.board_name || '-') + '</td>' +
				'<td>' + (item.grade_name || '-') + '</td>' +
				'<td>' + Math.round(Number(item.qty_available || 0)) + '</td>' +
				'<td><select class="form-select form-select-sm adj-op" data-idx="' + idx + '"><option value="add"' + (item.operation === 'add' ? ' selected' : '') + '>Add</option><option value="subtract"' + (item.operation === 'subtract' ? ' selected' : '') + '>Subtract</option></select></td>' +
				'<td class="qty-col"><input type="number" min="1" step="1" class="form-control form-control-sm adj-qty qty-input" data-idx="' + idx + '" value="' + (item.qty || 1) + '"></td>' +
				'<td><input type="text" class="form-control form-control-sm adj-remark" data-idx="' + idx + '" value="' + (item.remarks || '') + '"></td>' +
				'<td><button type="button" class="btn btn-sm btn-outline-danger adj-remove" data-idx="' + idx + '">X</button></td>' +
				'</tr>';
			hidden += '<input type="hidden" name="adjustments[' + idx + '][item_type]" value="' + (item.item_type || '') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][item_ref_id]" value="' + (item.item_ref_id || '') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][variation_key]" value="' + (item.variation_key || 'default') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][school_id]" value="' + (item.school_id || 0) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][branch_id]" value="' + (item.branch_id || 0) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][operation]" value="' + (item.operation || 'add') + '" class="adj-op-hidden" data-idx="' + idx + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][qty]" value="' + (item.qty || 1) + '" class="adj-qty-hidden" data-idx="' + idx + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][remarks]" value="' + (item.remarks || '') + '" class="adj-remark-hidden" data-idx="' + idx + '">';
		});
		body.innerHTML = html;
		hiddenWrap.innerHTML = hidden;

		document.querySelectorAll('.adj-remove').forEach(function(btn) {
			btn.addEventListener('click', function() {
				var idx = Number(btn.getAttribute('data-idx'));
				selectedAdjustItems.splice(idx, 1);
				renderSelectedAdjustItems();
			});
		});
		document.querySelectorAll('.adj-op').forEach(function(el) {
			el.addEventListener('change', function() {
				var idx = Number(el.getAttribute('data-idx'));
				selectedAdjustItems[idx].operation = el.value;
				renderSelectedAdjustItems();
			});
		});
		document.querySelectorAll('.adj-qty').forEach(function(el) {
			el.addEventListener('input', function() {
				var idx = Number(el.getAttribute('data-idx'));
				selectedAdjustItems[idx].qty = Number(el.value || 0);
			});
		});
		document.querySelectorAll('.adj-remark').forEach(function(el) {
			el.addEventListener('input', function() {
				var idx = Number(el.getAttribute('data-idx'));
				selectedAdjustItems[idx].remarks = el.value || '';
			});
		});
	}

	function renderSuggestions(items) {
		var wrap = document.getElementById('adjustSuggestions');
		if (!items || !items.length) {
			wrap.style.display = 'none';
			wrap.innerHTML = '';
			return;
		}
		var html = '';
		items.forEach(function(item) {
			var key = itemKey(item);
			var duplicate = selectedAdjustItems.some(function(s) { return itemKey(s) === key; });
			if (duplicate) return;
			var schoolText = item.school_name || '-';
			if (item.branch_name) schoolText += ' (' + item.branch_name + ')';
			var boardText = item.board_name || '-';
			var genderText = item.gender || '-';
			html += '<button type="button" class="list-group-item list-group-item-action suggestion-item" ' +
				'data-item=\'' + JSON.stringify(item).replace(/'/g, '&#39;') + '\'>' +
				'<strong>' + (item.product_name || '-') + '</strong> | ' + (item.variation_key || '-') +
				' <span class="text-muted">| ' + genderText + ' | ' + schoolText + ' | ' + boardText + '</span></button>';
		});
		if (!html) {
			wrap.style.display = 'none';
			wrap.innerHTML = '';
			return;
		}
		wrap.innerHTML = html;
		wrap.style.display = 'block';
		document.querySelectorAll('.suggestion-item').forEach(function(btn) {
			btn.addEventListener('click', function() {
				try {
					var item = JSON.parse((btn.getAttribute('data-item') || '{}').replace(/&#39;/g, "'"));
					if (!item.item_type || !item.item_ref_id) return;
					if (selectedAdjustItems.some(function(s) { return itemKey(s) === itemKey(item); })) return;
					item.operation = 'add';
					item.qty = 1;
					item.remarks = '';
					selectedAdjustItems.push(item);
					renderSelectedAdjustItems();
					document.getElementById('adjustSearchQ').value = '';
					document.getElementById('adjustSuggestions').style.display = 'none';
				} catch (e) {}
			});
		});
	}

	function searchAdjustItems() {
		var q = document.getElementById('adjustSearchQ').value || '';
		var url = '<?php echo base_url('products/stock_management/search_items'); ?>' +
			'?q=' + encodeURIComponent(q);
		fetch(url)
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (!data || data.status !== 'success') {
					renderSuggestions([]);
					return;
				}
				renderSuggestions(data.items || []);
			})
			.catch(function() {
				renderSuggestions([]);
			});
	}

	document.getElementById('openStockAdjustModalBtn').addEventListener('click', function() {
		if (!adjustModal) return;
		document.getElementById('stockAdjustForm').reset();
		selectedAdjustItems = [];
		renderSelectedAdjustItems();
		renderSuggestions([]);
		adjustModal.show();
	});

	document.getElementById('adjustSearchBtn').addEventListener('click', function() {
		searchAdjustItems();
	});

	document.querySelectorAll('.stock-btn').forEach(function(btn) {
		btn.addEventListener('click', function() {
			if (!adjustModal) return;
			var item = {
				item_type: btn.getAttribute('data-item-type') || '',
				item_ref_id: btn.getAttribute('data-item-ref-id') || '',
				variation_key: btn.getAttribute('data-variation-key') || 'default',
				school_id: Number(btn.getAttribute('data-school-id') || 0),
				branch_id: Number(btn.getAttribute('data-branch-id') || 0),
				product_name: btn.getAttribute('data-product-name') || '',
				uniform_type_name: btn.getAttribute('data-uniform-type') || '',
				gender: btn.getAttribute('data-gender') || '',
				school_name: btn.getAttribute('data-school') || '',
				branch_name: btn.getAttribute('data-branch') || '',
				board_name: btn.getAttribute('data-board') || '',
				grade_name: btn.getAttribute('data-grade') || '',
				qty_available: btn.getAttribute('data-current-qty') || '0',
				operation: 'add',
				qty: 1,
				remarks: ''
			};
			if (!selectedAdjustItems.some(function(s) { return itemKey(s) === itemKey(item); })) {
				selectedAdjustItems.push(item);
			}
			renderSelectedAdjustItems();
			adjustModal.show();
		});
	});

	document.getElementById('stockAdjustForm').addEventListener('submit', function(e) {
		if (!selectedAdjustItems.length) {
			e.preventDefault();
			alert('Please select at least one product.');
			return;
		}
		for (var i = 0; i < selectedAdjustItems.length; i++) {
			var item = selectedAdjustItems[i];
			var qty = Number(item.qty || 0);
			var current = Number(item.qty_available || 0);
			if (qty <= 0) {
				e.preventDefault();
				alert('Enter valid qty for all selected rows.');
				return;
			}
			if (item.operation === 'subtract' && qty > current) {
				e.preventDefault();
				alert('Subtract qty cannot exceed current stock for: ' + (item.product_name || 'item'));
				return;
			}
		}

		// Rebuild hidden inputs with latest edited values
		var hiddenWrap = document.getElementById('adjustHiddenInputs');
		var hidden = '';
		selectedAdjustItems.forEach(function(item, idx) {
			hidden += '<input type="hidden" name="adjustments[' + idx + '][item_type]" value="' + (item.item_type || '') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][item_ref_id]" value="' + (item.item_ref_id || '') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][variation_key]" value="' + (item.variation_key || 'default') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][school_id]" value="' + (item.school_id || 0) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][branch_id]" value="' + (item.branch_id || 0) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][operation]" value="' + (item.operation || 'add') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][qty]" value="' + (item.qty || 1) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][remarks]" value="' + (item.remarks || '') + '">';
		});
		hiddenWrap.innerHTML = hidden;
	});

	document.getElementById('adjustSearchQ').addEventListener('input', function() {
		var q = this.value || '';
		if (q.length < 2) {
			renderSuggestions([]);
			return;
		}
		searchAdjustItems();
	});

	var historyModalEl = document.getElementById('stockHistoryModal');
	var historyModal = historyModalEl ? new bootstrap.Modal(historyModalEl) : null;
	var historyItemContext = null;

	function loadItemHistory(page) {
		if (!historyItemContext) return;
		var itemType = historyItemContext.itemType;
		var itemRefId = historyItemContext.itemRefId;
		var variationKey = historyItemContext.variationKey;
		var productName = historyItemContext.productName;
		var uniformType = historyItemContext.uniformType;
		var gender = historyItemContext.gender;
		var school = historyItemContext.school;
		var branch = historyItemContext.branch;
		var board = historyItemContext.board;
		var grade = historyItemContext.grade;

		document.getElementById('historyProductTitle').textContent = productName + ' (' + variationKey + ')';
		document.getElementById('historyMetaProduct').textContent = productName || '-';
		document.getElementById('historyMetaUniformType').textContent = uniformType || '-';
		document.getElementById('historyMetaSize').textContent = variationKey || '-';
		document.getElementById('historyMetaGender').textContent = gender || '-';
		document.getElementById('historyMetaSchool').textContent = school ? (branch ? (school + ' (' + branch + ')') : school) : '-';
		document.getElementById('historyMetaBoard').textContent = board || '-';
		document.getElementById('historyMetaGrade').textContent = grade || '-';
		document.getElementById('historyOpeningQty').textContent = '0';
		document.getElementById('historyCurrentQty').textContent = '0';
		document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>';
		document.getElementById('historyPagination').innerHTML = '';

		var url = '<?php echo base_url('products/stock_management/item_history'); ?>' +
			'?item_type=' + encodeURIComponent(itemType) +
			'&item_ref_id=' + encodeURIComponent(itemRefId) +
			'&variation_key=' + encodeURIComponent(variationKey) +
			'&page=' + encodeURIComponent(page || 1);

		fetch(url)
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (!data || data.status !== 'success') {
					document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load history</td></tr>';
					return;
				}
				document.getElementById('historyOpeningQty').textContent = Math.round(Number(data.opening_qty || 0));
				document.getElementById('historyCurrentQty').textContent = Math.round(Number(data.current_qty || 0));
				if (!data.history || data.history.length === 0) {
					document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">No history found for this item</td></tr>';
				} else {
					var html = '';
					data.history.forEach(function(row) {
						html += '<tr>' +
							'<td>' + (row.created_at || '-') + '</td>' +
							'<td><span class="badge ' + (row.direction === 'IN' ? 'bg-success' : 'bg-danger') + '">' + row.direction + '</span></td>' +
							'<td>' + (row.source || '-') + '</td>' +
							'<td>' + Math.round(Number(row.qty_delta || 0)) + '</td>' +
							'<td>' + Math.round(Number(row.qty_before || 0)) + '</td>' +
							'<td>' + Math.round(Number(row.qty_after || 0)) + '</td>' +
							'<td>' + (row.remarks || '-') + '</td>' +
							'</tr>';
					});
					document.getElementById('historyTableBody').innerHTML = html;
				}

				var pWrap = document.getElementById('historyPagination');
				var cp = Number(data.current_page || 1);
				var tp = Number(data.total_pages || 1);
				var pHtml = '';
				if (tp > 1) {
					if (cp > 1) {
						pHtml += '<li class="page-item"><a class="page-link history-page-link" href="#" data-page="' + (cp - 1) + '">Prev</a></li>';
					}
					var start = Math.max(1, cp - 2);
					var end = Math.min(tp, cp + 2);
					for (var p = start; p <= end; p++) {
						pHtml += '<li class="page-item ' + (p === cp ? 'active' : '') + '"><a class="page-link history-page-link" href="#" data-page="' + p + '">' + p + '</a></li>';
					}
					if (cp < tp) {
						pHtml += '<li class="page-item"><a class="page-link history-page-link" href="#" data-page="' + (cp + 1) + '">Next</a></li>';
					}
				}
				pWrap.innerHTML = pHtml;
				document.querySelectorAll('.history-page-link').forEach(function(link) {
					link.addEventListener('click', function(e) {
						e.preventDefault();
						var targetPage = Number(link.getAttribute('data-page') || 1);
						loadItemHistory(targetPage);
					});
				});
			})
			.catch(function() {
				document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load history</td></tr>';
			});
	}

	document.querySelectorAll('.history-btn').forEach(function(btn) {
		btn.addEventListener('click', function() {
			if (!historyModal) return;
			historyItemContext = {
				itemType: btn.getAttribute('data-item-type') || '',
				itemRefId: btn.getAttribute('data-item-ref-id') || '',
				variationKey: btn.getAttribute('data-variation-key') || 'default',
				productName: btn.getAttribute('data-product-name') || '',
				uniformType: btn.getAttribute('data-uniform-type') || '',
				gender: btn.getAttribute('data-gender') || '',
				school: btn.getAttribute('data-school') || '',
				branch: btn.getAttribute('data-branch') || '',
				board: btn.getAttribute('data-board') || '',
				grade: btn.getAttribute('data-grade') || ''
			};
			loadItemHistory(1);
			historyModal.show();
		});
	});
});
</script>
