<?php
	$show_book_stock_filters = !empty($show_book_stock_filters);
	$show_stationery_stock_filters = !empty($show_stationery_stock_filters);
	$textbook_publishers = isset($textbook_publishers) ? $textbook_publishers : array();
	$textbook_grades = isset($textbook_grades) ? $textbook_grades : array();
	$textbook_ages = isset($textbook_ages) ? $textbook_ages : array();
	$stationery_categories = isset($stationery_categories) ? $stationery_categories : array();
	$stationery_brands = isset($stationery_brands) ? $stationery_brands : array();
	$stationery_colours = isset($stationery_colours) ? $stationery_colours : array();
	$schools = isset($schools) ? $schools : array();
	$boards = isset($boards) ? $boards : array();
	$prefill_item = isset($prefill_item) && is_array($prefill_item) ? $prefill_item : null;
?>
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0">Update Stock</h6>
		<small class="text-muted">Search products, set quantity, then save</small>
	</div>
	<a href="<?php echo base_url('products/stock_management'); ?>" class="btn btn-outline-secondary btn-sm">Back</a>
</div>

<style>
.stock-update-page .sa-panel {
	background: #fff;
	border: 1px solid #e6ebf2;
	border-radius: 8px;
	padding: 0.7rem 0.85rem;
	margin-bottom: 0.7rem;
}
.stock-update-page .sa-panel-title {
	font-size: 0.7rem;
	font-weight: 650;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	color: #64748b;
	margin-bottom: 0.45rem;
}
.stock-update-page .sa-section {
	margin-top: 0.55rem;
	padding-top: 0.55rem;
	border-top: 1px solid #eef2f7;
}
.stock-update-page .sa-label {
	display: block;
	font-size: 0.7rem;
	font-weight: 600;
	color: #475569;
	margin-bottom: 0.15rem;
}
.stock-update-page .sa-panel .form-control,
.stock-update-page .sa-panel .form-select {
	border-color: #d8e0ea;
	min-height: 32px;
	padding-top: 0.2rem;
	padding-bottom: 0.2rem;
	font-size: 0.8125rem;
}
.stock-update-page .sa-panel .form-control:focus,
.stock-update-page .sa-panel .form-select:focus {
	border-color: #94a3b8;
	box-shadow: 0 0 0 0.15rem rgba(100, 116, 139, 0.12);
}
.stock-update-page #adjustSearchBtn {
	min-height: 32px;
	font-weight: 600;
	padding-top: 0.25rem;
	padding-bottom: 0.25rem;
}
.stock-update-page .sa-filters-accordion {
	margin-top: 0.55rem;
	border: 1px solid #eef2f7;
	border-radius: 8px;
	overflow: hidden;
}
.stock-update-page .sa-filters-accordion .accordion-item {
	border: 0;
	background: transparent;
}
.stock-update-page .sa-filters-accordion .accordion-button {
	padding: 0.55rem 0.75rem;
	font-size: 0.8rem;
	font-weight: 650;
	color: #334155;
	background: #f8fafc;
	box-shadow: none;
}
.stock-update-page .sa-filters-accordion .accordion-button:not(.collapsed) {
	background: #f1f5f9;
	color: #0f172a;
	box-shadow: none;
}
.stock-update-page .sa-filters-accordion .accordion-button:focus {
	box-shadow: none;
	border-color: transparent;
}
.stock-update-page .sa-filters-accordion .accordion-button::after {
	width: 0.85rem;
	height: 0.85rem;
	background-size: 0.85rem;
}
.stock-update-page .sa-filters-accordion .accordion-body {
	padding: 0.65rem 0.75rem 0.75rem;
	background: #fff;
	border-top: 1px solid #eef2f7;
}
.stock-update-page #adjustSuggestions {
	border-radius: 8px;
	overflow: hidden;
	border: 1px solid #d8e0ea;
	margin-top: 2px;
}
.stock-update-page #adjustSuggestions .list-group-item {
	border: 0;
	border-bottom: 1px solid #eef2f7;
	padding: 0.4rem 0.65rem;
	font-size: 0.8rem;
}
.stock-update-page #adjustSuggestions .list-group-item:last-child {
	border-bottom: 0;
}
.stock-update-page .sa-selected-head {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 0.5rem;
	margin-bottom: 0.45rem;
}
.stock-update-page .sa-selected-head h6 {
	margin: 0;
	font-size: 0.85rem;
	font-weight: 650;
	color: #0f172a;
}
.stock-update-page .sa-count {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 1.35rem;
	height: 1.35rem;
	padding: 0 0.35rem;
	border-radius: 999px;
	background: #e8eef8;
	color: #1e3a8a;
	font-size: 0.7rem;
	font-weight: 700;
}
.stock-update-page .sa-table-wrap {
	border: 1px solid #e6ebf2;
	border-radius: 8px;
	overflow: auto;
	background: #fff;
	max-height: min(52vh, 420px);
}
.stock-update-page .sa-table-wrap .table {
	min-width: 920px;
	margin-bottom: 0;
}
.stock-update-page .sa-table-wrap thead th {
	position: sticky;
	top: 0;
	z-index: 1;
	background: #f1f5f9;
	color: #334155;
	font-size: 0.68rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.02em;
	border-bottom-width: 1px;
	white-space: nowrap;
	padding: 0.4rem 0.5rem;
}
.stock-update-page .sa-table-wrap td {
	vertical-align: middle;
	font-size: 0.8rem;
	padding: 0.35rem 0.5rem;
}
.stock-update-page .sa-empty {
	padding: 1.25rem 0.75rem;
	text-align: center;
	color: #94a3b8;
	font-size: 0.8125rem;
}
.stock-update-page .sa-empty strong {
	display: block;
	color: #64748b;
	margin-bottom: 0.15rem;
}
.stock-update-page .qty-col {
	min-width: 70px;
	width: 70px;
}
.stock-update-page .qty-input {
	min-width: 60px;
	width: 60px;
}
.stock-update-page .sa-actions {
	display: flex;
	justify-content: flex-end;
	gap: 0.45rem;
	flex-wrap: wrap;
	margin-top: 0.65rem;
}
.stock-update-page .sa-actions .btn {
	min-width: 100px;
	font-weight: 600;
}
.stock-update-page .alert {
	padding: 0.45rem 0.75rem;
	margin-bottom: 0.7rem;
	font-size: 0.85rem;
}
</style>

<div class="stock-update-page">
	<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success"><?php echo htmlspecialchars((string)$this->session->flashdata('success')); ?></div>
	<?php endif; ?>
	<?php if ($this->session->flashdata('error')): ?>
		<div class="alert alert-danger"><?php echo htmlspecialchars((string)$this->session->flashdata('error')); ?></div>
	<?php endif; ?>

	<form id="stockAdjustForm" method="post" action="<?php echo base_url('products/stock_management/adjust_bulk'); ?>">
		<div id="adjustHiddenInputs"></div>

		<div class="sa-panel">
			<div class="sa-panel-title">Find products</div>
			<div class="row g-2 align-items-end">
				<div class="col-lg-10 col-md-9 position-relative">
					<label class="sa-label" for="adjustSearchQ">Search</label>
					<input type="text" class="form-control form-control-sm" id="adjustSearchQ" placeholder="Product name, size, school..." autocomplete="off">
					<div id="adjustSuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index:1056; display:none; max-height:220px; overflow:auto;"></div>
				</div>
				<div class="col-lg-2 col-md-3 d-grid">
					<label class="sa-label d-none d-md-block">&nbsp;</label>
					<button type="button" class="btn btn-primary btn-sm" id="adjustSearchBtn">Search</button>
				</div>
			</div>

			<div class="accordion sa-filters-accordion" id="adjustFilterAccordion">
				<div class="accordion-item">
					<h2 class="accordion-header" id="adjustHeadingFilters">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#adjustCollapseFilters" aria-expanded="false" aria-controls="adjustCollapseFilters">
							<i class="isax isax-filter me-2"></i> Additional Filters
						</button>
					</h2>
					<div id="adjustCollapseFilters" class="accordion-collapse collapse" aria-labelledby="adjustHeadingFilters" data-bs-parent="#adjustFilterAccordion">
						<div class="accordion-body">
							<div class="row g-2">
								<div class="col-lg-4 col-md-6">
									<label class="sa-label" for="adjustSearchSchool">School</label>
									<select id="adjustSearchSchool" class="form-select form-select-sm">
										<option value="">All Schools</option>
										<?php foreach ($schools as $s): ?>
											<option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars((string)$s['school_name']); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<?php if ($show_book_stock_filters): ?>
							<div class="sa-section">
								<div class="sa-panel-title">Textbook / Notebook</div>
								<div class="row g-2">
									<div class="col-lg-3 col-md-6">
										<label class="sa-label" for="adjustSearchPublisher">Publisher</label>
										<select id="adjustSearchPublisher" class="form-select form-select-sm">
											<option value="">All Publishers</option>
											<?php foreach ($textbook_publishers as $pub): ?>
												<option value="<?php echo (int)$pub['id']; ?>"><?php echo htmlspecialchars((string)$pub['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-lg-3 col-md-6">
										<label class="sa-label" for="adjustSearchBoard">Board</label>
										<select id="adjustSearchBoard" class="form-select form-select-sm">
											<option value="">All Boards</option>
											<?php foreach ($boards as $b): ?>
												<option value="<?php echo (int)$b['id']; ?>"><?php echo htmlspecialchars((string)$b['board_name']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-lg-3 col-md-6">
										<label class="sa-label" for="adjustSearchGradeAgeType">Grade / Age</label>
										<select id="adjustSearchGradeAgeType" class="form-select form-select-sm">
											<option value="">Select type</option>
											<option value="grade">Grade</option>
											<option value="age">Age</option>
										</select>
									</div>
									<div class="col-lg-3 col-md-6" id="adjustGradeWrap">
										<label class="sa-label" for="adjustSearchGrade">Grade</label>
										<select id="adjustSearchGrade" class="form-select form-select-sm">
											<option value="">All Grades</option>
											<?php foreach ($textbook_grades as $g): ?>
												<option value="<?php echo (int)$g['id']; ?>"><?php echo htmlspecialchars((string)$g['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-lg-3 col-md-6" id="adjustAgeWrap" style="display:none;">
										<label class="sa-label" for="adjustSearchAge">Age</label>
										<select id="adjustSearchAge" class="form-select form-select-sm">
											<option value="">All Ages</option>
											<?php foreach ($textbook_ages as $a): ?>
												<option value="<?php echo (int)$a['id']; ?>"><?php echo htmlspecialchars((string)$a['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<?php endif; ?>

							<?php if ($show_stationery_stock_filters): ?>
							<div class="sa-section">
								<div class="sa-panel-title">Stationery</div>
								<div class="row g-2">
									<div class="col-lg-4 col-md-4">
										<label class="sa-label" for="adjustSearchCategory">Category</label>
										<select id="adjustSearchCategory" class="form-select form-select-sm">
											<option value="">All Categories</option>
											<?php foreach ($stationery_categories as $cat): ?>
												<option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars((string)$cat['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-lg-4 col-md-4">
										<label class="sa-label" for="adjustSearchBrand">Brand</label>
										<select id="adjustSearchBrand" class="form-select form-select-sm">
											<option value="">All Brands</option>
											<?php foreach ($stationery_brands as $br): ?>
												<option value="<?php echo (int)$br['id']; ?>"><?php echo htmlspecialchars((string)$br['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-lg-4 col-md-4">
										<label class="sa-label" for="adjustSearchColour">Colour</label>
										<select id="adjustSearchColour" class="form-select form-select-sm">
											<option value="">All Colours</option>
											<?php foreach ($stationery_colours as $cl): ?>
												<option value="<?php echo (int)$cl['id']; ?>"><?php echo htmlspecialchars((string)$cl['name']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="sa-panel mb-0">
			<div class="sa-selected-head">
				<h6>Selected products</h6>
				<span class="sa-count" id="adjustSelectedCount">0</span>
			</div>
			<div class="sa-table-wrap">
				<table class="table table-sm table-hover align-middle mb-0">
					<thead>
						<tr>
							<th>Product</th>
							<th>Type</th>
							<th>Size</th>
							<th>School</th>
							<th>Board</th>
							<th>Grade</th>
							<th>Current</th>
							<th>Action</th>
							<th class="qty-col">Qty</th>
							<th>Remark</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="adjustSelectedBody">
						<tr>
							<td colspan="11">
								<div class="sa-empty">
									<strong>No products selected</strong>
									<span>Use search or filters above to add items</span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="sa-actions">
				<a href="<?php echo base_url('products/stock_management'); ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
				<button type="submit" class="btn btn-primary btn-sm" id="adjustSubmitBtn">Update Stock</button>
			</div>
		</div>
	</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var selectedAdjustItems = [];
	var prefillItem = <?php echo json_encode($prefill_item); ?>;

	function itemKey(item) {
		return (item.item_type || '') + '|' + (item.item_ref_id || '') + '|' + (item.variation_key || 'default');
	}

	function renderSelectedAdjustItems() {
		var body = document.getElementById('adjustSelectedBody');
		var hiddenWrap = document.getElementById('adjustHiddenInputs');
		var countEl = document.getElementById('adjustSelectedCount');
		if (countEl) countEl.textContent = String(selectedAdjustItems.length);
		if (!selectedAdjustItems.length) {
			body.innerHTML = '<tr><td colspan="11"><div class="sa-empty"><strong>No products selected</strong><span>Use search or filters above to add items</span></div></td></tr>';
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
				'<td>' + schoolText + '</td>' +
				'<td>' + (item.board_name || '-') + '</td>' +
				'<td>' + (item.grade_name || '-') + '</td>' +
				'<td>' + Math.round(Number(item.qty_available || 0)) + '</td>' +
				'<td><select class="form-select form-select-sm adj-op" data-idx="' + idx + '"><option value="add"' + (item.operation === 'add' ? ' selected' : '') + '>Add</option><option value="subtract"' + (item.operation === 'subtract' ? ' selected' : '') + '>Subtract</option></select></td>' +
				'<td class="qty-col"><input type="number" min="1" step="1" class="form-control form-control-sm adj-qty qty-input" data-idx="' + idx + '" value="' + (item.qty || 1) + '"></td>' +
				'<td><input type="text" class="form-control form-control-sm adj-remark" data-idx="' + idx + '" value="' + (item.remarks || '') + '" placeholder="Optional"></td>' +
				'<td><button type="button" class="btn btn-sm btn-outline-danger adj-remove" data-idx="' + idx + '" title="Remove">&times;</button></td>' +
				'</tr>';
			hidden += '<input type="hidden" name="adjustments[' + idx + '][item_type]" value="' + (item.item_type || '') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][item_ref_id]" value="' + (item.item_ref_id || '') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][variation_key]" value="' + (item.variation_key || 'default') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][school_id]" value="' + (item.school_id || 0) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][branch_id]" value="' + (item.branch_id || 0) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][operation]" value="' + (item.operation || 'add') + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][qty]" value="' + (item.qty || 1) + '">' +
				'<input type="hidden" name="adjustments[' + idx + '][remarks]" value="' + (item.remarks || '') + '">';
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
			var metaBits = [];
			if (item.publisher_name) metaBits.push(item.publisher_name);
			if (item.brand_name && item.brand_name !== item.publisher_name) metaBits.push(item.brand_name);
			if (item.category_name) metaBits.push(item.category_name);
			if (item.colour_name) metaBits.push(item.colour_name);
			metaBits.push(schoolText);
			metaBits.push(boardText);
			html += '<button type="button" class="list-group-item list-group-item-action suggestion-item" ' +
				'data-item=\'' + JSON.stringify(item).replace(/'/g, '&#39;') + '\'>' +
				'<strong>' + (item.product_name || '-') + '</strong> | ' + (item.variation_key || '-') +
				' <span class="text-muted">| ' + metaBits.join(' | ') + '</span></button>';
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

	function valOrEmpty(id) {
		var el = document.getElementById(id);
		return el ? (el.value || '') : '';
	}

	function syncAdjustGradeAgeVisibility() {
		var typeEl = document.getElementById('adjustSearchGradeAgeType');
		var gradeWrap = document.getElementById('adjustGradeWrap');
		var ageWrap = document.getElementById('adjustAgeWrap');
		if (!typeEl || !gradeWrap || !ageWrap) return;
		var type = typeEl.value || '';
		if (type === 'age') {
			gradeWrap.style.display = 'none';
			ageWrap.style.display = '';
			var gradeEl = document.getElementById('adjustSearchGrade');
			if (gradeEl) gradeEl.value = '';
		} else {
			gradeWrap.style.display = '';
			ageWrap.style.display = 'none';
			var ageEl = document.getElementById('adjustSearchAge');
			if (ageEl) ageEl.value = '';
		}
	}

	function searchAdjustItems() {
		var q = valOrEmpty('adjustSearchQ');
		var s = valOrEmpty('adjustSearchSchool');
		var publisherId = valOrEmpty('adjustSearchPublisher');
		var boardId = valOrEmpty('adjustSearchBoard');
		var gradeAgeType = valOrEmpty('adjustSearchGradeAgeType');
		var gradeId = valOrEmpty('adjustSearchGrade');
		var ageId = valOrEmpty('adjustSearchAge');
		var categoryId = valOrEmpty('adjustSearchCategory');
		var brandId = valOrEmpty('adjustSearchBrand');
		var colourId = valOrEmpty('adjustSearchColour');

		var hasFilters = s !== '' || publisherId !== '' || boardId !== '' || gradeAgeType !== '' ||
			gradeId !== '' || ageId !== '' || categoryId !== '' || brandId !== '' || colourId !== '';

		if (q.length < 2 && !hasFilters) {
			renderSuggestions([]);
			return;
		}

		var url = '<?php echo base_url('products/stock_management/search_items'); ?>' +
			'?q=' + encodeURIComponent(q) +
			'&school_id=' + encodeURIComponent(s) +
			'&publisher_id=' + encodeURIComponent(publisherId) +
			'&board_id=' + encodeURIComponent(boardId) +
			'&grade_age_type=' + encodeURIComponent(gradeAgeType) +
			'&grade_id=' + encodeURIComponent(gradeId) +
			'&age_id=' + encodeURIComponent(ageId) +
			'&category_id=' + encodeURIComponent(categoryId) +
			'&brand_id=' + encodeURIComponent(brandId) +
			'&colour_id=' + encodeURIComponent(colourId);
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

	document.getElementById('adjustSearchBtn').addEventListener('click', function() {
		searchAdjustItems();
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
		searchAdjustItems();
	});
	document.getElementById('adjustSearchSchool').addEventListener('change', function() {
		searchAdjustItems();
	});
	['adjustSearchPublisher', 'adjustSearchBoard', 'adjustSearchGrade', 'adjustSearchAge',
		'adjustSearchCategory', 'adjustSearchBrand', 'adjustSearchColour'].forEach(function(id) {
		var el = document.getElementById(id);
		if (el) el.addEventListener('change', function() { searchAdjustItems(); });
	});
	var gradeAgeTypeEl = document.getElementById('adjustSearchGradeAgeType');
	if (gradeAgeTypeEl) {
		gradeAgeTypeEl.addEventListener('change', function() {
			syncAdjustGradeAgeVisibility();
			searchAdjustItems();
		});
		syncAdjustGradeAgeVisibility();
	}

	if (prefillItem && prefillItem.item_type && prefillItem.item_ref_id) {
		prefillItem.operation = prefillItem.operation || 'add';
		prefillItem.qty = prefillItem.qty || 1;
		prefillItem.remarks = prefillItem.remarks || '';
		selectedAdjustItems.push(prefillItem);
	}
	renderSelectedAdjustItems();
});
</script>
