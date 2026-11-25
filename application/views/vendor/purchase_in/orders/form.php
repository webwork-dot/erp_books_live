<?php
	$suggested = isset($suggested_po) && is_array($suggested_po) ? $suggested_po : array('po_no' => 'PO-0001/26-27', 'fy_label' => '26-27');
	$companies = isset($companies) ? $companies : array();
	$warehouses = isset($warehouses) ? $warehouses : array();
	$suppliers = isset($suppliers) ? $suppliers : array();
	$pf = isset($product_filters) && is_array($product_filters) ? $product_filters : array();
	$pf_publishers = isset($pf['publishers']) ? $pf['publishers'] : array();
	$pf_boards = isset($pf['boards']) ? $pf['boards'] : array();
	$pf_grades = isset($pf['grades']) ? $pf['grades'] : array();
	$pf_ages = isset($pf['ages']) ? $pf['ages'] : array();
	$pf_st_cats = isset($pf['stationery_categories']) ? $pf['stationery_categories'] : array();
	$pf_st_brands = isset($pf['stationery_brands']) ? $pf['stationery_brands'] : array();
	$pf_st_colours = isset($pf['stationery_colours']) ? $pf['stationery_colours'] : array();
	$active_suppliers = array();
	foreach ($suppliers as $s) {
		if (!isset($s['status']) || (int)$s['status'] === 1) {
			$active_suppliers[] = $s;
		}
	}
?>
<style>
.po-form .form-label { font-size: 0.72rem; font-weight: 600; margin-bottom: 0.1rem; }
.po-form .card { border: 1px solid #d8e0ea; border-radius: 8px; }
.po-form .card-body { padding: 0.55rem 0.7rem; }
.po-form .form-control-sm, .po-form .form-select-sm { min-height: 30px; padding-top: 0.2rem; padding-bottom: 0.2rem; font-size: 0.78rem; }
.po-party-box { background: #f8fafc; border: 1px solid #d8e0ea; border-radius: 6px; padding: 0.45rem 0.55rem; min-height: 88px; }
.po-party-box h6 { font-size: 0.72rem; font-weight: 700; margin-bottom: 0.25rem; }
.po-party-box .muted { color: #6c757d; font-size: 0.7rem; line-height: 1.25; }
.po-lines-card { overflow: visible; }
.po-lines-card .card-body { overflow: visible; }
.po-lines-wrap { overflow: visible !important; }
.po-lines,
.po-charges {
	border: 1px solid #c5d0de;
	border-collapse: collapse;
}
.po-lines th, .po-lines td,
.po-charges th, .po-charges td {
	border: 1px solid #c5d0de !important;
	padding: 0.28rem 0.4rem !important;
}
.po-lines thead th,
.po-charges thead th {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)) !important;
	color: #fff !important;
	font-size: 0.68rem;
	text-transform: uppercase;
	letter-spacing: .03em;
	white-space: nowrap;
}
.po-lines tbody td { vertical-align: middle; background: #fffdf8; }
.po-lines tbody td.po-prod-cell { overflow: visible; position: relative; }
.po-lines tfoot td { background: #f1f5f9; font-size: 0.75rem; }
.po-charges tbody td { background: #fff; font-size: 0.75rem; vertical-align: middle; }
.po-charges tfoot td { background: #f1f5f9; }
.po-lines .form-control, .po-lines .form-select,
.po-charges .form-control { font-size: 0.78rem; min-height: 28px; padding: 0.15rem 0.35rem; }
.po-summary { border: 1px solid #c5d0de; border-radius: 6px; padding: 0.5rem 0.6rem; background: #fff; }
.po-summary .row-line { display: flex; justify-content: space-between; font-size: 0.72rem; padding: 0.12rem 0; }
.po-summary .row-line.total { font-weight: 700; border-top: 1px solid #d8e0ea; margin-top: 0.25rem; padding-top: 0.3rem; }
.po-product-dd {
	position: fixed;
	z-index: 1080;
	background: #fff;
	border: 1px solid #c5d0de;
	border-radius: 8px;
	max-height: 420px;
	overflow: auto;
	box-shadow: 0 14px 36px rgba(15,23,42,.18);
	display: none;
	min-width: 520px;
}
.po-product-dd .dd-head {
	position: sticky;
	top: 0;
	z-index: 2;
	background: #f8fafc;
	border-bottom: 1px solid #e2e8f0;
	padding: 0.4rem 0.55rem;
	display: flex;
	gap: 0.35rem;
	flex-wrap: wrap;
	align-items: center;
}
.po-product-dd .dd-chip {
	border: 1px solid #cbd5e1;
	background: #fff;
	border-radius: 999px;
	padding: 0.1rem 0.55rem;
	font-size: 0.68rem;
	cursor: pointer;
	color: #334155;
}
.po-product-dd .dd-chip.active {
	background: var(--vendor-sidebar-bg, #1e293b);
	border-color: transparent;
	color: #fff;
}
.po-product-dd .dd-hint { margin-left: auto; font-size: 0.65rem; color: #94a3b8; }
.po-product-dd .item { padding: 0.45rem 0.65rem; cursor: pointer; border-bottom: 1px solid #eef2f7; display: grid; grid-template-columns: 1fr auto; gap: 0.35rem 0.75rem; align-items: center; }
.po-product-dd .item:hover,
.po-product-dd .item.active { background: #fff7ed; }
.po-product-dd .item .name { font-weight: 600; font-size: 0.8rem; line-height: 1.25; }
.po-product-dd .item .meta { font-size: 0.68rem; color: #64748b; }
.po-product-dd .item .stock { font-size: 0.72rem; font-weight: 650; white-space: nowrap; }
.po-product-dd .item .stock.in { color: #15803d; }
.po-product-dd .item .stock.out { color: #b91c1c; }
.po-prod-wrap .btn-browse-prod {
	position: absolute;
	right: 24px;
	top: 50%;
	transform: translateY(-50%);
	width: 20px;
	height: 20px;
	border: 0;
	background: transparent;
	color: #64748b;
	padding: 0;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	border-radius: 50%;
	cursor: pointer;
}
.po-prod-wrap .btn-browse-prod:hover { color: var(--vendor-primary, #7539ff); background: #eef2ff; }
.po-prod-wrap .prod-search { padding-right: 2.6rem; }
.po-prod-wrap.has-value .prod-search { padding-right: 2.6rem; }
.po-prod-wrap.has-value .btn-browse-prod { right: 24px; }
#poProductModal .modal-dialog { max-width: 980px; }
#poProductModal .po-pick-chips .btn { font-size: 0.72rem; padding: 0.2rem 0.55rem; }
#poProductModal .table thead th { font-size: 0.7rem; white-space: nowrap; }
#poProductModal .table td { font-size: 0.78rem; vertical-align: middle; }
#poProductModal .po-pick-row { cursor: pointer; }
#poProductModal .po-pick-row:hover,
#poProductModal .po-pick-row.active { background: #fff7ed; }
#poProductModal .po-subfilters { border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.55rem 0.65rem; background: #f8fafc; margin-bottom: 0.55rem; display: none; }
#poProductModal .po-subfilters.show { display: block; }
#poProductModal .po-subfilters .sa-title { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #64748b; margin-bottom: 0.35rem; }
#poProductModal .po-subfilters .form-label { font-size: 0.68rem; margin-bottom: 0.1rem; font-weight: 600; }
#poProductModal .po-subfilters .form-select-sm { min-height: 28px; font-size: 0.75rem; padding-top: 0.15rem; padding-bottom: 0.15rem; }
.po-tax-bar { background: #eef1f5; border: 1px solid #c5d0de; border-radius: 6px; padding: 0.35rem 0.55rem; font-size: 0.72rem; }
.po-tax-bar span { margin-right: 0.85rem; }
.po-tax-bar .tax { color: #2563eb; font-weight: 600; }
.po-grand { background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)); color: #fff; border-radius: 4px; padding: 0.2rem 0.55rem; font-weight: 700; font-size: 0.78rem; }
.po-active-row { box-shadow: inset 3px 0 0 #f97316; }
.po-actions { display: inline-flex; flex-direction: row; align-items: center; gap: 0.25rem; white-space: nowrap; }
.po-actions .btn { flex: 0 0 auto; padding: 0.15rem 0.35rem; }
.po-line-meta { display: inline-flex; flex-direction: row; align-items: center; gap: 0.3rem; white-space: nowrap; }
.po-line-meta .line-no { min-width: 1rem; font-weight: 600; font-size: 0.75rem; }
.po-line-meta .btn-hist { color: var(--vendor-primary, #7539ff); line-height: 1; padding: 0; border: 0; background: transparent; display: inline-flex; align-items: center; }
.po-line-meta .btn-hist i { font-size: 0.95rem; }
.po-shortcuts { font-size: 0.68rem; color: #64748b; }
.po-shortcuts kbd {
	display: inline-block;
	padding: 0.05rem 0.28rem;
	font-size: 0.65rem;
	border: 1px solid #cbd5e1;
	border-radius: 3px;
	background: #f8fafc;
	color: #334155;
}
.po-disabled-field { background: #f1f5f9 !important; }
.po-form .select2-container { width: 100% !important; }
.po-form .select2-container--default .select2-selection--single {
	min-height: 30px;
	height: 30px;
	border: 1px solid #ced4da;
	border-radius: 0.25rem;
	padding: 0;
	position: relative;
}
.po-form .select2-container--default .select2-selection--single .select2-selection__rendered {
	line-height: 28px;
	padding-left: 0.5rem;
	padding-right: 2.4rem;
	font-size: 0.78rem;
	color: #212529;
}
.po-form .select2-container--default .select2-selection--single .select2-selection__arrow {
	height: 28px;
	right: 4px;
	top: 1px;
}
.po-form .select2-container--default .select2-selection--single .select2-selection__clear {
	position: absolute;
	right: 22px;
	top: 0;
	height: 28px;
	line-height: 28px;
	margin: 0;
	padding: 0 4px;
	font-size: 1rem;
	color: #64748b;
	float: none !important;
}
.po-form .select2-container--default .select2-selection--single .select2-selection__clear:hover {
	color: #dc2626;
}
.po-form .select2-dropdown { font-size: 0.78rem; border-color: #ced4da; }
.po-form .select2-results__option { padding: 0.3rem 0.55rem; }
.po-prod-wrap { position: relative; display: block; }
.po-prod-wrap .prod-search { padding-right: 1.7rem; }
.po-prod-wrap .btn-clear-prod {
	position: absolute;
	right: 4px;
	top: 50%;
	transform: translateY(-50%);
	width: 20px;
	height: 20px;
	border: 0;
	background: transparent;
	color: #64748b;
	padding: 0;
	line-height: 1;
	display: none;
	align-items: center;
	justify-content: center;
	border-radius: 50%;
	cursor: pointer;
}
.po-prod-wrap .btn-clear-prod i { font-size: 0.85rem; }
.po-prod-wrap .btn-clear-prod:hover { color: #dc2626; background: #fee2e2; }
.po-prod-wrap.has-value .btn-clear-prod { display: inline-flex; }
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0">New Purchase Order</h6>
		<div class="po-shortcuts mt-1">
			<kbd>Enter</kbd> next · <kbd>↑</kbd><kbd>↓</kbd> products · <kbd>F2</kbd> browse · <kbd>Ctrl</kbd>+<kbd>S</kbd> save · <kbd>Ctrl</kbd>+<kbd>I</kbd> add · <kbd>Ctrl</kbd>+<kbd>D</kbd> delete · <kbd>Ctrl</kbd>+<kbd>H</kbd> history
		</div>
	</div>
	<a href="<?php echo base_url('purchase-in/orders'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
</div>

<div class="po-form" id="poForm">
	<div class="card mb-2">
		<div class="card-body">
			<div class="row g-2 align-items-end">
				<div class="col-md-2">
					<label class="form-label">PO Date</label>
					<input type="date" id="po_date" class="form-control form-control-sm po-disabled-field" value="<?php echo date('Y-m-d'); ?>" disabled>
				</div>
				<div class="col-md-2">
					<label class="form-label">Suggested PO No</label>
					<input type="text" id="po_no" class="form-control form-control-sm po-disabled-field" value="<?php echo htmlspecialchars((string)$suggested['po_no']); ?>" readonly>
				</div>
				<div class="col-md-2">
					<label class="form-label">Company <span class="text-danger">*</span></label>
					<select id="company_id" class="form-select form-select-sm po-select2">
						<option value="">Choose...</option>
						<?php foreach ($companies as $c): ?>
							<option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars((string)$c['company_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Warehouse <span class="text-danger">*</span></label>
					<select id="warehouse_id" class="form-select form-select-sm po-select2">
						<option value="">Choose...</option>
						<?php foreach ($warehouses as $w): if ((int)$w['status'] !== 1) continue; ?>
							<option value="<?php echo (int)$w['id']; ?>"><?php echo htmlspecialchars((string)$w['warehouse_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Vendor <span class="text-danger">*</span></label>
					<select id="purchase_vendor_id" class="form-select form-select-sm po-select2">
						<option value="">Choose...</option>
						<?php foreach ($active_suppliers as $s): ?>
							<option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars((string)$s['vendor_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<small class="text-muted d-block mt-1">PO No auto-generated for FY <?php echo htmlspecialchars((string)$suggested['fy_label']); ?> (Apr–Mar)</small>

			<div class="row g-2 mt-1">
				<div class="col-md-4">
					<div class="po-party-box" id="boxCompany">
						<h6>Company / Billing</h6>
						<div class="muted" data-field="body">-</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="po-party-box" id="boxWarehouse">
						<h6>Warehouse / Shipping</h6>
						<div class="muted" data-field="body">-</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="po-party-box" id="boxVendor">
						<h6>Vendor Details</h6>
						<div class="muted" data-field="body">-</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-2">
		<div class="col-lg-9">
			<div class="card po-lines-card mb-2">
				<div class="card-body">
					<div class="po-lines-wrap">
						<table class="table table-sm po-lines mb-0" id="poLines">
							<thead>
								<tr>
									<th style="width:48px">#</th>
									<th>Product</th>
									<th style="width:80px">Qty</th>
									<th style="width:95px">Rate</th>
									<th style="width:100px">Taxable</th>
									<th style="width:90px">Actions</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<td></td>
									<td class="text-end fw-semibold">Totals</td>
									<td class="fw-semibold" id="footQty">0</td>
									<td class="fw-semibold" id="footRate">0.00</td>
									<td class="fw-semibold" id="footTaxable">₹0.00</td>
									<td></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="po-tax-bar mt-2" id="taxBar">
						<span>Items - Basic: <strong id="sumBasic">₹0.00</strong></span>
						<span>Taxable: <strong id="sumTaxable">₹0.00</strong></span>
						<span class="tax">CGST: <strong id="sumCgst">₹0.00</strong></span>
						<span class="tax">SGST: <strong id="sumSgst">₹0.00</strong></span>
						<span class="tax">IGST: <strong id="sumIgst">₹0.00</strong></span>
					</div>
				</div>
			</div>

			<div class="card mb-2">
				<div class="card-body">
					<table class="table table-sm align-middle mb-0 po-charges">
						<thead>
							<tr>
								<th>Type</th>
								<th>Description</th>
								<th style="width:80px">%</th>
								<th style="width:110px">Amount</th>
								<th style="width:100px">Effect</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Order Discount</td>
								<td></td>
								<td><input type="number" step="0.01" min="0" class="form-control form-control-sm" id="discount_percent" value="0"></td>
								<td><input type="number" step="0.01" min="0" class="form-control form-control-sm" id="discount_amount" value="0"></td>
								<td id="effDiscount">0.00</td>
							</tr>
							<tr>
								<td>Other Charges</td>
								<td><input type="text" class="form-control form-control-sm" id="other_charges_desc" placeholder="Description"></td>
								<td></td>
								<td><input type="number" step="0.01" min="0" class="form-control form-control-sm" id="other_charges_amount" value="0"></td>
								<td id="effOther">0.00</td>
							</tr>
							<tr>
								<td>
									<div class="form-check mb-0">
										<input class="form-check-input" type="checkbox" id="apply_round_off">
										<label class="form-check-label" for="apply_round_off">Round Off</label>
									</div>
								</td>
								<td></td>
								<td></td>
								<td id="roundOffAmt">0.00</td>
								<td id="effRound">0.00</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4" class="text-end fw-semibold">Grand Total</td>
								<td><span class="po-grand" id="grandTotal">₹0.00</span></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="po-summary sticky-top" style="top:70px">
				<div class="fw-semibold mb-1 small text-uppercase text-muted">Line Preview</div>
				<div class="row-line"><span>HSN</span><span id="pvHsn">-</span></div>
				<div class="row-line"><span>GST %</span><span id="pvGst">0</span></div>
				<div class="row-line"><span>Rate</span><span id="pvRate">0.00</span></div>
				<div class="row-line"><span>Qty</span><span id="pvQty">0</span></div>
				<div class="row-line"><span>Basic Amount</span><span id="pvBasic">0.00</span></div>
				<div class="row-line"><span>Discount</span><span id="pvDisc">0.00</span></div>
				<div class="row-line"><span>Taxable</span><span id="pvTaxable">0.00</span></div>
				<div class="row-line"><span>GST Amount</span><span id="pvGstAmt">0.00</span></div>
				<div class="row-line total"><span>Total</span><span id="pvTotal">0.00</span></div>
			</div>
		</div>
	</div>

	<div class="d-flex justify-content-end gap-2 mt-2 mb-3">
		<a href="<?php echo base_url('purchase-in/orders'); ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
		<button type="button" class="btn btn-primary btn-sm" id="btnSavePo"><i class="isax isax-tick-circle me-1"></i>Save Purchase Order</button>
	</div>
</div>

<!-- Smart product picker modal -->
<div class="modal fade" id="poProductModal" tabindex="-1">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header py-2" style="background:var(--vendor-sidebar-bg,#1e293b);color:#fff;">
				<h6 class="modal-title mb-0">Find Product</h6>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row g-2 align-items-end mb-2">
					<div class="col-md-8">
						<label class="form-label mb-1" style="font-size:0.72rem;">Search (name / SKU / code / HSN — multi word)</label>
						<input type="text" id="poPickSearch" class="form-control form-control-sm" placeholder="e.g. a4 long 164  or  arihant chemistry" autocomplete="off">
					</div>
					<div class="col-md-4">
						<label class="form-label mb-1" style="font-size:0.72rem;">In stock only</label>
						<div class="form-check form-switch mt-1">
							<input class="form-check-input" type="checkbox" id="poPickInStock">
							<label class="form-check-label" for="poPickInStock" style="font-size:0.75rem;">Show stocked first</label>
						</div>
					</div>
				</div>
				<div class="po-pick-chips d-flex flex-wrap gap-1 mb-2" id="poPickTypes">
					<button type="button" class="btn btn-sm btn-dark" data-type="all">All</button>
					<button type="button" class="btn btn-sm btn-outline-secondary" data-type="textbook">Textbook</button>
					<button type="button" class="btn btn-sm btn-outline-secondary" data-type="notebook">Notebook</button>
					<button type="button" class="btn btn-sm btn-outline-secondary" data-type="stationery">Stationery</button>
					<button type="button" class="btn btn-sm btn-outline-secondary" data-type="individual">Individual</button>
				</div>

				<div class="po-subfilters" id="poSubTextbook">
					<div class="sa-title">Textbook filters</div>
					<div class="row g-2">
						<div class="col-md-3">
							<label class="form-label">Publisher</label>
							<select id="poFltPublisher" class="form-select form-select-sm">
								<option value="">All Publishers</option>
								<?php foreach ($pf_publishers as $pub): ?>
									<option value="<?php echo (int)$pub['id']; ?>"><?php echo htmlspecialchars((string)$pub['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label">Board</label>
							<select id="poFltBoard" class="form-select form-select-sm">
								<option value="">All Boards</option>
								<?php foreach ($pf_boards as $b): ?>
									<option value="<?php echo (int)$b['id']; ?>"><?php echo htmlspecialchars((string)$b['board_name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-2">
							<label class="form-label">Grade / Age</label>
							<select id="poFltGradeAgeType" class="form-select form-select-sm">
								<option value="">Select type</option>
								<option value="grade">Grade</option>
								<option value="age">Age</option>
							</select>
						</div>
						<div class="col-md-2" id="poFltGradeWrap">
							<label class="form-label">Grade</label>
							<select id="poFltGrade" class="form-select form-select-sm">
								<option value="">All Grades</option>
								<?php foreach ($pf_grades as $g): ?>
									<option value="<?php echo (int)$g['id']; ?>"><?php echo htmlspecialchars((string)$g['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-2" id="poFltAgeWrap" style="display:none;">
							<label class="form-label">Age</label>
							<select id="poFltAge" class="form-select form-select-sm">
								<option value="">All Ages</option>
								<?php foreach ($pf_ages as $a): ?>
									<option value="<?php echo (int)$a['id']; ?>"><?php echo htmlspecialchars((string)$a['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>

				<div class="po-subfilters" id="poSubNotebook">
					<div class="sa-title">Notebook filters</div>
					<div class="row g-2">
						<div class="col-md-4">
							<label class="form-label">Brand / Publisher</label>
							<select id="poFltNotebookBrand" class="form-select form-select-sm">
								<option value="">All Brands</option>
								<?php foreach ($pf_publishers as $pub): ?>
									<option value="<?php echo (int)$pub['id']; ?>"><?php echo htmlspecialchars((string)$pub['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>

				<div class="po-subfilters" id="poSubStationery">
					<div class="sa-title">Stationery filters</div>
					<div class="row g-2">
						<div class="col-md-4">
							<label class="form-label">Category</label>
							<select id="poFltCategory" class="form-select form-select-sm">
								<option value="">All Categories</option>
								<?php foreach ($pf_st_cats as $cat): ?>
									<option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars((string)$cat['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">Brand</label>
							<select id="poFltBrand" class="form-select form-select-sm">
								<option value="">All Brands</option>
								<?php foreach ($pf_st_brands as $br): ?>
									<option value="<?php echo (int)$br['id']; ?>"><?php echo htmlspecialchars((string)$br['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">Colour</label>
							<select id="poFltColour" class="form-select form-select-sm">
								<option value="">All Colours</option>
								<?php foreach ($pf_st_colours as $cl): ?>
									<option value="<?php echo (int)$cl['id']; ?>"><?php echo htmlspecialchars((string)$cl['name']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>

				<div class="d-flex justify-content-between align-items-center mb-1">
					<small class="text-muted" id="poPickCount">Type to search · ↑↓ Enter to pick</small>
					<small class="text-muted">F2 from product cell opens this</small>
				</div>
				<div class="table-responsive border rounded" style="max-height:460px;">
					<table class="table table-sm table-hover mb-0">
						<thead class="table-light sticky-top">
							<tr>
								<th style="width:42px">#</th>
								<th>Product</th>
								<th style="width:95px">Type</th>
								<th style="width:110px">SKU / Code</th>
								<th style="width:70px" class="text-end">Stock</th>
								<th style="width:80px" class="text-end">CP</th>
								<th style="width:70px"></th>
							</tr>
						</thead>
						<tbody id="poPickBody">
							<tr><td colspan="7" class="text-center text-muted py-4">Start typing to find products</td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Product history modal -->
<div class="modal fade" id="poHistoryModal" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header" style="background:#f97316;color:#fff;">
				<h6 class="modal-title mb-0" id="poHistoryTitle">Product</h6>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row g-2 mb-3">
					<div class="col-md-4"><div class="border rounded p-2 bg-white"><div class="small text-muted">Last Purchase</div><div class="fw-bold text-primary" id="histLastPurchase">₹0.00</div></div></div>
					<div class="col-md-4"><div class="border rounded p-2 bg-white"><div class="small text-muted">Current Stock</div><div class="fw-bold text-success" id="histStock">0</div></div></div>
				</div>
				<div class="table-responsive">
					<table class="table table-sm">
						<thead><tr><th>#</th><th>Date</th><th>Vendor</th><th>PO No</th><th>Qty</th><th>Rate</th><th></th></tr></thead>
						<tbody id="histBody"></tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer"><small class="text-muted me-auto">Press Esc to close</small></div>
		</div>
	</div>
</div>

<script>
(function(){
	var urls = {
		party: <?php echo json_encode(base_url('purchase-in/party-details')); ?>,
		warehouses: <?php echo json_encode(base_url('purchase-in/warehouses-by-company')); ?>,
		search: <?php echo json_encode(base_url('purchase-in/product-search')); ?>,
		history: <?php echo json_encode(base_url('purchase-in/product-history')); ?>,
		save: <?php echo json_encode(base_url('purchase-in/orders/save')); ?>
	};
	var companyStateId = 0, vendorStateId = 0, activeRow = null, openDd = null, openInput = null, ddIndex = -1;
	var pickType = 'all', pickIndex = -1, pickRows = [], pickTimer = null, pickForRow = null, ddType = 'all', searchSeq = 0;

	function currentProdFilters(){
		return {
			publisher_id: (document.getElementById('poFltPublisher') || {}).value || '',
			board_id: (document.getElementById('poFltBoard') || {}).value || '',
			grade_age_type: (document.getElementById('poFltGradeAgeType') || {}).value || '',
			grade_id: (document.getElementById('poFltGrade') || {}).value || '',
			age_id: (document.getElementById('poFltAge') || {}).value || '',
			category_id: (document.getElementById('poFltCategory') || {}).value || '',
			brand_id: (document.getElementById('poFltBrand') || {}).value || '',
			colour_id: (document.getElementById('poFltColour') || {}).value || '',
			notebook_brand_id: (document.getElementById('poFltNotebookBrand') || {}).value || ''
		};
	}
	function syncSubFilterPanels(type){
		var tb = document.getElementById('poSubTextbook');
		var nb = document.getElementById('poSubNotebook');
		var st = document.getElementById('poSubStationery');
		if (tb) tb.classList.toggle('show', type === 'textbook');
		if (nb) nb.classList.toggle('show', type === 'notebook');
		if (st) st.classList.toggle('show', type === 'stationery');
		syncGradeAgeWrap();
	}
	function syncGradeAgeWrap(){
		var t = (document.getElementById('poFltGradeAgeType') || {}).value || '';
		var gw = document.getElementById('poFltGradeWrap');
		var aw = document.getElementById('poFltAgeWrap');
		if (gw) gw.style.display = (t === 'age') ? 'none' : '';
		if (aw) aw.style.display = (t === 'age') ? '' : 'none';
		if (t !== 'grade' && document.getElementById('poFltGrade')) document.getElementById('poFltGrade').value = '';
		if (t !== 'age' && document.getElementById('poFltAge')) document.getElementById('poFltAge').value = '';
	}
	function clearTypeSubFilters(type){
		if (type !== 'textbook') {
			['poFltPublisher','poFltBoard','poFltGradeAgeType','poFltGrade','poFltAge'].forEach(function(id){
				var el = document.getElementById(id); if (el) el.value = '';
			});
		}
		if (type !== 'notebook') {
			var nb = document.getElementById('poFltNotebookBrand'); if (nb) nb.value = '';
		}
		if (type !== 'stationery') {
			['poFltCategory','poFltBrand','poFltColour'].forEach(function(id){
				var el = document.getElementById(id); if (el) el.value = '';
			});
		}
		syncGradeAgeWrap();
	}

	function money(n){ return '₹' + (Number(n)||0).toFixed(2); }
	function num(n){ return (Number(n)||0).toFixed(2); }
	function focusField(el){ if (!el) return; el.focus(); if (el.select) try { el.select(); } catch(e){} }
	function ddItems(){ return openDd ? openDd.querySelectorAll('.item[data-selectable="1"]') : []; }
	function highlightDd(idx){
		var items = ddItems();
		Array.prototype.forEach.call(items, function(it){ it.classList.remove('active'); });
		if (!items.length) { ddIndex = -1; return; }
		if (idx < 0) idx = items.length - 1;
		if (idx >= items.length) idx = 0;
		ddIndex = idx;
		items[ddIndex].classList.add('active');
		if (items[ddIndex].scrollIntoView) items[ddIndex].scrollIntoView({ block: 'nearest' });
	}
	function pickDdItem(){
		var items = ddItems();
		if (!items.length || ddIndex < 0 || !items[ddIndex]) return false;
		items[ddIndex].click();
		return true;
	}
	function deleteActiveRow(){
		if (!activeRow) return;
		var btn = activeRow.querySelector('.btn-del');
		if (btn) btn.click();
	}
	function savePo(){
		var btn = document.getElementById('btnSavePo');
		if (btn && !btn.disabled) btn.click();
	}

	function renderParty(boxId, data){
		var el = document.querySelector('#'+boxId+' [data-field="body"]');
		if (!el) return;
		if (!data) { el.innerHTML = '-'; return; }
		var html = '<div class="fw-semibold text-dark mb-1">'+esc(data.name||'-')+'</div>';
		html += '<div>GST: '+esc(data.gst||'-')+'</div>';
		if (data.fssai) html += '<div>FSSAI: '+esc(data.fssai)+'</div>';
		if (data.contact) html += '<div>Contact: '+esc(data.contact)+'</div>';
		if (data.phone) html += '<div>Phone: '+esc(data.phone)+'</div>';
		html += '<div class="mt-1">'+esc(data.address||'-')+'</div>';
		el.innerHTML = html;
	}
	function esc(s){ var d=document.createElement('div'); d.textContent=s==null?'':String(s); return d.innerHTML; }

	function loadParty(type, id, boxId, stateCb){
		if (!id) { renderParty(boxId, null); if (stateCb) stateCb(0); return; }
		fetch(urls.party+'?type='+encodeURIComponent(type)+'&id='+id)
			.then(function(r){ return r.json(); })
			.then(function(res){
				if (res.status === 'success') {
					renderParty(boxId, res.data);
					if (stateCb) stateCb(res.data.state_id||0);
					recalc();
				}
			});
	}

	function select2Ready(){
		return (typeof window.jQuery !== 'undefined' && window.jQuery.fn && window.jQuery.fn.select2);
	}
	function optionCount(sel){
		var n = 0;
		Array.prototype.forEach.call(sel.options, function(o){ if (o.value) n++; });
		return n;
	}
	function autoSelectIfOne(sel){
		if (!sel.value && optionCount(sel) === 1) {
			for (var i = 0; i < sel.options.length; i++) {
				if (sel.options[i].value) {
					sel.value = sel.options[i].value;
					break;
				}
			}
			return !!sel.value;
		}
		return false;
	}
	function refreshSelect2($el){
		if (!select2Ready()) return;
		var $ = window.jQuery;
		if ($el.hasClass('select2-hidden-accessible')) {
			$el.trigger('change.select2');
		}
	}
	function initPoSelect2(){
		if (!select2Ready()) return;
		var $ = window.jQuery;
		$('.po-select2').each(function(){
			var $el = $(this);
			if ($el.hasClass('select2-hidden-accessible')) {
				$el.select2('destroy');
			}
			$el.select2({
				width: '100%',
				placeholder: $el.find('option[value=""]').text() || 'Choose...',
				allowClear: true
			});
		});
		$('#company_id').off('change.poSel').on('change.poSel', function(){
			loadParty('company', this.value, 'boxCompany', function(sid){ companyStateId = sid; });
			filterWarehouses(this.value);
		});
		$('#warehouse_id').off('change.poSel').on('change.poSel', function(){
			loadParty('warehouse', this.value, 'boxWarehouse');
		});
		$('#purchase_vendor_id').off('change.poSel').on('change.poSel', function(){
			loadParty('vendor', this.value, 'boxVendor', function(sid){ vendorStateId = sid; });
		});
	}
	function applyDefaultSelections(){
		var company = document.getElementById('company_id');
		var vendor = document.getElementById('purchase_vendor_id');
		var warehouse = document.getElementById('warehouse_id');
		var companyChanged = autoSelectIfOne(company);
		var vendorChanged = autoSelectIfOne(vendor);
		if (select2Ready()) {
			var $ = window.jQuery;
			if (companyChanged) $('#company_id').trigger('change');
			else if (company.value) {
				loadParty('company', company.value, 'boxCompany', function(sid){ companyStateId = sid; });
				filterWarehouses(company.value);
			}
			if (vendorChanged) $('#purchase_vendor_id').trigger('change');
			else if (vendor.value) loadParty('vendor', vendor.value, 'boxVendor', function(sid){ vendorStateId = sid; });
			if (!companyChanged && autoSelectIfOne(warehouse)) {
				$('#warehouse_id').trigger('change');
			} else if (warehouse.value) {
				loadParty('warehouse', warehouse.value, 'boxWarehouse');
			}
			refreshSelect2($('#company_id'));
			refreshSelect2($('#purchase_vendor_id'));
			refreshSelect2($('#warehouse_id'));
		} else {
			if (companyChanged || company.value) {
				loadParty('company', company.value, 'boxCompany', function(sid){ companyStateId = sid; });
				filterWarehouses(company.value);
			}
			if (vendorChanged || vendor.value) {
				loadParty('vendor', vendor.value, 'boxVendor', function(sid){ vendorStateId = sid; });
			}
			if (autoSelectIfOne(warehouse) || warehouse.value) {
				loadParty('warehouse', warehouse.value, 'boxWarehouse');
			}
		}
	}

	function filterWarehouses(companyId){
		var sel = document.getElementById('warehouse_id');
		var current = sel.value;
		fetch(urls.warehouses+'?company_id='+companyId)
			.then(function(r){ return r.json(); })
			.then(function(res){
				sel.innerHTML = '<option value="">Choose...</option>';
				(res.warehouses||[]).forEach(function(w){
					var o = document.createElement('option');
					o.value = w.id; o.textContent = w.warehouse_name;
					sel.appendChild(o);
				});
				if (current && Array.prototype.some.call(sel.options, function(o){ return o.value === String(current); })) {
					sel.value = current;
				} else {
					sel.value = '';
					autoSelectIfOne(sel);
				}
				if (select2Ready()) {
					var $ = window.jQuery;
					var $wh = $('#warehouse_id');
					if ($wh.hasClass('select2-hidden-accessible')) {
						$wh.select2('destroy');
					}
					$wh.select2({
						width: '100%',
						placeholder: 'Choose...',
						allowClear: true
					});
					$wh.off('change.poSel').on('change.poSel', function(){
						loadParty('warehouse', this.value, 'boxWarehouse');
					});
					$wh.trigger('change');
				} else {
					loadParty('warehouse', sel.value, 'boxWarehouse');
				}
			});
	}

	document.getElementById('company_id').addEventListener('change', function(){
		if (select2Ready()) return;
		loadParty('company', this.value, 'boxCompany', function(sid){ companyStateId = sid; });
		filterWarehouses(this.value);
	});
	document.getElementById('warehouse_id').addEventListener('change', function(){
		if (select2Ready()) return;
		loadParty('warehouse', this.value, 'boxWarehouse');
	});
	document.getElementById('purchase_vendor_id').addEventListener('change', function(){
		if (select2Ready()) return;
		loadParty('vendor', this.value, 'boxVendor', function(sid){ vendorStateId = sid; });
	});
	['discount_percent','discount_amount','other_charges_amount','apply_round_off'].forEach(function(id){
		document.getElementById(id).addEventListener('input', recalc);
		document.getElementById(id).addEventListener('change', recalc);
	});

	function addRow(prefill){
		var tbody = document.querySelector('#poLines tbody');
		var tr = document.createElement('tr');
		tr.className = 'po-line';
		tr.innerHTML =
			'<td><div class="po-line-meta"><span class="line-no">1</span><button type="button" class="btn-hist" title="Purchase history"><i class="isax isax-eye"></i></button></div></td>'+
			'<td class="po-prod-cell" style="min-width:260px">'+
				'<div class="po-prod-wrap">'+
					'<input type="text" class="form-control form-control-sm prod-search" placeholder="Type few words… F2 browse" autocomplete="off">'+
					'<button type="button" class="btn-browse-prod" title="Browse products (F2)" tabindex="-1"><i class="isax isax-search-normal-1"></i></button>'+
					'<button type="button" class="btn-clear-prod" title="Clear product" tabindex="-1"><i class="isax isax-close-circle"></i></button>'+
				'</div>'+
				'<input type="hidden" class="prod-id"><input type="hidden" class="prod-type"><input type="hidden" class="prod-hsn"><input type="hidden" class="prod-gst" value="0"><input type="hidden" class="prod-name"><input type="hidden" class="prod-stock" value="0"><input type="hidden" class="prod-disc" value="0">'+
			'</td>'+
			'<td><input type="number" min="0" step="0.001" class="form-control form-control-sm qty" value="1"></td>'+
			'<td><input type="number" min="0" step="0.01" class="form-control form-control-sm rate" value="0"></td>'+
			'<td><span class="taxable-lbl">₹0.00</span></td>'+
			'<td><div class="po-actions">'+
				'<button type="button" class="btn btn-success btn-sm btn-add" title="Add row"><i class="isax isax-add"></i></button>'+
				'<button type="button" class="btn btn-danger btn-sm btn-del" title="Remove row"><i class="isax isax-trash"></i></button>'+
			'</div></td>';
		tbody.appendChild(tr);
		var dd = document.createElement('div');
		dd.className = 'po-product-dd';
		document.body.appendChild(dd);
		tr._productDd = dd;
		bindRow(tr);
		if (prefill) applyProduct(tr, prefill);
		renumber();
		setActive(tr);
		recalc();
		focusField(tr.querySelector('.prod-search'));
		return tr;
	}

	function hideProductDd(){
		if (openDd) {
			openDd.style.display = 'none';
			openDd = null;
			openInput = null;
			ddIndex = -1;
		}
	}

	function placeProductDd(input, dd){
		var rect = input.getBoundingClientRect();
		var width = Math.min(Math.max(rect.width, 560), window.innerWidth - 16);
		var left = rect.left;
		if (left + width > window.innerWidth - 8) {
			left = Math.max(8, window.innerWidth - width - 8);
		}
		var top = rect.bottom + 4;
		var maxH = Math.min(420, Math.max(220, window.innerHeight - top - 12));
		dd.style.left = left + 'px';
		dd.style.top = top + 'px';
		dd.style.width = width + 'px';
		dd.style.maxHeight = maxH + 'px';
		dd.style.display = 'block';
		openDd = dd;
		openInput = input;
	}

	function bindRow(tr){
		tr.querySelector('.btn-add').addEventListener('click', function(){ addRow(); });
		tr.querySelector('.btn-del').addEventListener('click', function(){
			var rows = document.querySelectorAll('#poLines tbody tr');
			if (rows.length <= 1) {
				clearProduct(tr);
				focusField(tr.querySelector('.prod-search'));
				return;
			}
			if (tr._productDd && tr._productDd.parentNode) {
				tr._productDd.parentNode.removeChild(tr._productDd);
			}
			if (openDd === tr._productDd) hideProductDd();
			var prev = tr.previousElementSibling;
			var next = tr.nextElementSibling;
			tr.remove();
			renumber();
			recalc();
			var focusTr = next || prev || document.querySelector('#poLines tbody tr');
			if (focusTr) {
				setActive(focusTr);
				focusField(focusTr.querySelector('.prod-search'));
			}
		});
		tr.querySelector('.btn-hist').addEventListener('click', function(){ openHistory(tr); });
		['qty','rate'].forEach(function(cls){
			tr.querySelector('.'+cls).addEventListener('input', function(){ setActive(tr); recalc(); });
			tr.querySelector('.'+cls).addEventListener('focus', function(){ setActive(tr); hideProductDd(); });
		});
		tr.addEventListener('click', function(){ setActive(tr); });

		var input = tr.querySelector('.prod-search');
		var qty = tr.querySelector('.qty');
		var rate = tr.querySelector('.rate');
		var clearBtn = tr.querySelector('.btn-clear-prod');
		var browseBtn = tr.querySelector('.btn-browse-prod');
		var timer = null;
		function syncClearBtn(){
			var wrap = tr.querySelector('.po-prod-wrap');
			if (!wrap) return;
			if ((input.value || '').trim() !== '' || tr.querySelector('.prod-id').value) wrap.classList.add('has-value');
			else wrap.classList.remove('has-value');
		}
		tr._syncClearBtn = syncClearBtn;
		if (clearBtn) {
			clearBtn.addEventListener('click', function(e){
				e.preventDefault();
				e.stopPropagation();
				clearProduct(tr);
				focusField(input);
			});
		}
		if (browseBtn) {
			browseBtn.addEventListener('click', function(e){
				e.preventDefault();
				e.stopPropagation();
				openProductPicker(tr, input.value);
			});
		}
		input.addEventListener('focus', function(){
			setActive(tr);
			if ((input.value || '').trim().length >= 1) searchProducts(tr, input.value, ddType);
		});
		input.addEventListener('input', function(){
			if (!input.value.trim()) {
				tr.querySelector('.prod-id').value = '';
				tr.querySelector('.prod-name').value = '';
			}
			syncClearBtn();
			clearTimeout(timer);
			timer = setTimeout(function(){ searchProducts(tr, input.value, ddType); }, 160);
		});
		syncClearBtn();
		input.addEventListener('keydown', function(e){
			if (e.key === 'F2') {
				e.preventDefault();
				openProductPicker(tr, input.value);
				return;
			}
			if (e.key === 'ArrowDown') {
				e.preventDefault();
				if (!openDd || openDd.style.display === 'none') searchProducts(tr, input.value, ddType);
				else highlightDd(ddIndex < 0 ? 0 : ddIndex + 1);
			} else if (e.key === 'ArrowUp') {
				e.preventDefault();
				if (openDd && openDd.style.display !== 'none') highlightDd(ddIndex < 0 ? 0 : ddIndex - 1);
			} else if (e.key === 'Enter') {
				e.preventDefault();
				if (openDd && openDd.style.display !== 'none' && ddItems().length) {
					if (ddIndex < 0) highlightDd(0);
					pickDdItem();
				} else if (tr.querySelector('.prod-id').value) {
					focusField(qty);
				} else if ((input.value || '').trim().length >= 1) {
					openProductPicker(tr, input.value);
				} else {
					searchProducts(tr, input.value, ddType);
				}
			} else if (e.key === 'Escape') {
				hideProductDd();
			} else if (e.key === 'Tab' && !e.shiftKey) {
				hideProductDd();
			}
		});
		qty.addEventListener('keydown', function(e){
			if (e.key === 'Enter') {
				e.preventDefault();
				focusField(rate);
			}
		});
		rate.addEventListener('keydown', function(e){
			if (e.key === 'Enter') {
				e.preventDefault();
				recalc();
				var next = tr.nextElementSibling;
				if (next) {
					setActive(next);
					focusField(next.querySelector('.prod-search'));
				} else {
					addRow();
				}
			}
		});
	}

	document.addEventListener('click', function(e){
		if (!openDd) return;
		if (openInput && (openInput === e.target || (openInput.closest && openInput.closest('.po-prod-wrap') && openInput.closest('.po-prod-wrap').contains(e.target)))) return;
		if (openDd.contains(e.target)) return;
		hideProductDd();
	});
	window.addEventListener('scroll', function(){
		if (openDd && openInput) placeProductDd(openInput, openDd);
	}, true);
	window.addEventListener('resize', function(){
		if (openDd && openInput) placeProductDd(openInput, openDd);
	});

	function fetchProducts(q, type, limit){
		var f = currentProdFilters();
		var url = urls.search
			+ '?q=' + encodeURIComponent(q || '')
			+ '&type=' + encodeURIComponent(type || 'all')
			+ '&limit=' + (limit || 60)
			+ '&publisher_id=' + encodeURIComponent(f.publisher_id)
			+ '&board_id=' + encodeURIComponent(f.board_id)
			+ '&grade_age_type=' + encodeURIComponent(f.grade_age_type)
			+ '&grade_id=' + encodeURIComponent(f.grade_id)
			+ '&age_id=' + encodeURIComponent(f.age_id)
			+ '&category_id=' + encodeURIComponent(f.category_id)
			+ '&brand_id=' + encodeURIComponent(f.brand_id)
			+ '&colour_id=' + encodeURIComponent(f.colour_id)
			+ '&notebook_brand_id=' + encodeURIComponent(f.notebook_brand_id);
		return fetch(url).then(function(r){ return r.json(); });
	}

	function renderDdHead(dd, tr, q){
		var head = document.createElement('div');
		head.className = 'dd-head';
		['all','textbook','notebook','stationery','individual'].forEach(function(t){
			var chip = document.createElement('button');
			chip.type = 'button';
			chip.className = 'dd-chip' + (ddType === t ? ' active' : '');
			chip.textContent = t === 'all' ? 'All' : (t.charAt(0).toUpperCase() + t.slice(1));
			chip.addEventListener('mousedown', function(ev){ ev.preventDefault(); });
			chip.addEventListener('click', function(){
				ddType = t;
				pickType = t;
				clearTypeSubFilters(t);
				searchProducts(tr, q, ddType);
			});
			head.appendChild(chip);
		});
		var hint = document.createElement('span');
		hint.className = 'dd-hint';
		hint.textContent = 'F2 full search';
		head.appendChild(hint);
		dd.appendChild(head);
	}

	function searchProducts(tr, q, type){
		var dd = tr._productDd;
		var input = tr.querySelector('.prod-search');
		var mySeq = ++searchSeq;
		type = type || ddType || 'all';
		dd.innerHTML = '<div class="item text-muted">Searching…</div>';
		placeProductDd(input, dd);
		fetchProducts(q, type, 50)
			.then(function(res){
				if (mySeq !== searchSeq) return;
				var items = res.products || [];
				dd.innerHTML = '';
				renderDdHead(dd, tr, q);
				if (!items.length) {
					var empty = document.createElement('div');
					empty.className = 'item text-muted';
					empty.textContent = (q ? 'No match for “'+q+'”' : 'Type a few letters, or press F2 to browse');
					dd.appendChild(empty);
					placeProductDd(input, dd);
					ddIndex = -1;
					return;
				}
				items.forEach(function(p){
					var el = document.createElement('div');
					el.className = 'item';
					el.setAttribute('data-selectable', '1');
					var stock = Number(p.stock)||0;
					el.innerHTML =
						'<div><div class="name">'+esc(p.product_name)+'</div>'+
						'<div class="meta">'+esc(p.category||'')+(p.sku ? ' · '+esc(p.sku) : '')+(p.product_code ? ' · '+esc(p.product_code) : '')+(p.hsn ? ' · HSN '+esc(p.hsn) : '')+(p.cost_price ? ' · CP ₹'+num(p.cost_price) : '')+'</div></div>'+
						'<div class="stock '+(stock>0?'in':'out')+'">'+(stock>0?('Stock '+stock):'Out')+'</div>';
					el.addEventListener('mousedown', function(ev){ ev.preventDefault(); });
					el.addEventListener('click', function(){
						applyProduct(tr, p, false);
						hideProductDd();
						recalc();
						focusField(tr.querySelector('.qty'));
					});
					dd.appendChild(el);
				});
				placeProductDd(input, dd);
				ddIndex = -1;
				highlightDd(0);
			})
			.catch(function(){
				if (mySeq !== searchSeq) return;
				dd.innerHTML = '<div class="item text-danger">Search failed</div>';
				placeProductDd(input, dd);
			});
	}

	function openProductPicker(tr, q){
		pickForRow = tr;
		setActive(tr);
		hideProductDd();
		var modalEl = document.getElementById('poProductModal');
		var searchEl = document.getElementById('poPickSearch');
		searchEl.value = q || '';
		pickType = ddType || 'all';
		Array.prototype.forEach.call(document.querySelectorAll('#poPickTypes [data-type]'), function(btn){
			var on = btn.getAttribute('data-type') === pickType;
			btn.classList.toggle('btn-dark', on);
			btn.classList.toggle('btn-outline-secondary', !on);
		});
		syncSubFilterPanels(pickType);
		var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
		modal.show();
		setTimeout(function(){
			focusField(searchEl);
			runProductPickerSearch();
		}, 180);
	}

	function highlightPick(idx){
		var rows = document.querySelectorAll('#poPickBody .po-pick-row');
		Array.prototype.forEach.call(rows, function(r){ r.classList.remove('active'); });
		if (!rows.length) { pickIndex = -1; return; }
		if (idx < 0) idx = rows.length - 1;
		if (idx >= rows.length) idx = 0;
		pickIndex = idx;
		rows[pickIndex].classList.add('active');
		rows[pickIndex].scrollIntoView({ block: 'nearest' });
	}

	function selectPickRow(){
		if (pickIndex < 0 || !pickRows[pickIndex] || !pickForRow) return;
		applyProduct(pickForRow, pickRows[pickIndex], false);
		recalc();
		bootstrap.Modal.getInstance(document.getElementById('poProductModal')).hide();
		setTimeout(function(){ focusField(pickForRow.querySelector('.qty')); }, 150);
	}

	function runProductPickerSearch(){
		var q = document.getElementById('poPickSearch').value || '';
		var inStock = document.getElementById('poPickInStock').checked;
		document.getElementById('poPickBody').innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">Searching…</td></tr>';
		fetchProducts(q, pickType, 80).then(function(res){
			var items = res.products || [];
			if (inStock) {
				items = items.slice().sort(function(a,b){
					var as = Number(a.stock)||0, bs = Number(b.stock)||0;
					if (as > 0 && bs <= 0) return -1;
					if (bs > 0 && as <= 0) return 1;
					return 0;
				});
			}
			pickRows = items;
			pickIndex = -1;
			document.getElementById('poPickCount').textContent = items.length
				? (items.length + ' product' + (items.length === 1 ? '' : 's') + (q ? ' for “'+q+'”' : ''))
				: 'No products found';
			var body = document.getElementById('poPickBody');
			if (!items.length) {
				body.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No products found. Try fewer / different words.</td></tr>';
				return;
			}
			body.innerHTML = items.map(function(p, i){
				var stock = Number(p.stock)||0;
				return '<tr class="po-pick-row" data-idx="'+i+'">'+
					'<td>'+(i+1)+'</td>'+
					'<td><div class="fw-semibold">'+esc(p.product_name)+'</div>'+(p.hsn ? '<small class="text-muted">HSN '+esc(p.hsn)+'</small>' : '')+'</td>'+
					'<td>'+esc(p.category||p.product_type||'-')+'</td>'+
					'<td><small>'+esc(p.sku||'-')+(p.product_code ? '<br>'+esc(p.product_code) : '')+'</small></td>'+
					'<td class="text-end '+(stock>0?'text-success fw-semibold':'text-danger')+'">'+stock+'</td>'+
					'<td class="text-end">'+(p.cost_price ? num(p.cost_price) : '-')+'</td>'+
					'<td><button type="button" class="btn btn-sm btn-primary py-0 px-2 use-prod">Use</button></td>'+
				'</tr>';
			}).join('');
			Array.prototype.forEach.call(body.querySelectorAll('.po-pick-row'), function(row){
				row.addEventListener('click', function(e){
					pickIndex = parseInt(row.getAttribute('data-idx'), 10);
					if (e.target && e.target.classList.contains('use-prod')) {
						selectPickRow();
					} else {
						highlightPick(pickIndex);
					}
				});
				row.addEventListener('dblclick', function(){
					pickIndex = parseInt(row.getAttribute('data-idx'), 10);
					selectPickRow();
				});
			});
			highlightPick(0);
		});
	}

	document.getElementById('poPickSearch').addEventListener('input', function(){
		clearTimeout(pickTimer);
		pickTimer = setTimeout(runProductPickerSearch, 140);
	});
	document.getElementById('poPickInStock').addEventListener('change', runProductPickerSearch);
	Array.prototype.forEach.call(document.querySelectorAll('#poPickTypes [data-type]'), function(btn){
		btn.addEventListener('click', function(){
			pickType = btn.getAttribute('data-type') || 'all';
			ddType = pickType;
			Array.prototype.forEach.call(document.querySelectorAll('#poPickTypes [data-type]'), function(b){
				var on = b.getAttribute('data-type') === pickType;
				b.classList.toggle('btn-dark', on);
				b.classList.toggle('btn-outline-secondary', !on);
			});
			clearTypeSubFilters(pickType);
			syncSubFilterPanels(pickType);
			runProductPickerSearch();
			focusField(document.getElementById('poPickSearch'));
		});
	});
	['poFltPublisher','poFltBoard','poFltGradeAgeType','poFltGrade','poFltAge','poFltNotebookBrand','poFltCategory','poFltBrand','poFltColour'].forEach(function(id){
		var el = document.getElementById(id);
		if (!el) return;
		el.addEventListener('change', function(){
			if (id === 'poFltGradeAgeType') syncGradeAgeWrap();
			runProductPickerSearch();
		});
	});
	document.getElementById('poPickSearch').addEventListener('keydown', function(e){
		if (e.key === 'ArrowDown') { e.preventDefault(); highlightPick(pickIndex < 0 ? 0 : pickIndex + 1); }
		else if (e.key === 'ArrowUp') { e.preventDefault(); highlightPick(pickIndex < 0 ? 0 : pickIndex - 1); }
		else if (e.key === 'Enter') { e.preventDefault(); selectPickRow(); }
	});

	function clearProduct(tr){
		tr.querySelector('.prod-search').value = '';
		tr.querySelector('.prod-id').value = '';
		tr.querySelector('.prod-type').value = '';
		tr.querySelector('.prod-hsn').value = '';
		tr.querySelector('.prod-gst').value = 0;
		tr.querySelector('.prod-name').value = '';
		tr.querySelector('.prod-stock').value = 0;
		tr.querySelector('.prod-disc').value = 0;
		tr.querySelector('.rate').value = 0;
		tr.querySelector('.qty').value = 1;
		tr.querySelector('.taxable-lbl').textContent = money(0);
		hideProductDd();
		if (tr._syncClearBtn) tr._syncClearBtn();
		setActive(tr);
		recalc();
	}

	function applyProduct(tr, p, openModal){
		tr.querySelector('.prod-search').value = p.product_name || '';
		tr.querySelector('.prod-id').value = p.id || '';
		tr.querySelector('.prod-type').value = p.product_type || '';
		tr.querySelector('.prod-hsn').value = p.hsn || '';
		tr.querySelector('.prod-gst').value = p.gst_percent || 0;
		tr.querySelector('.prod-name').value = p.product_name || '';
		tr.querySelector('.prod-stock').value = p.stock || 0;
		tr.querySelector('.rate').value = 0;
		if (tr._syncClearBtn) tr._syncClearBtn();
		setActive(tr);
		if (openModal) {
			setTimeout(function(){ openHistory(tr); }, 50);
		}
	}

	function renumber(){
		Array.prototype.forEach.call(document.querySelectorAll('#poLines tbody tr'), function(tr, i){
			tr.querySelector('.line-no').textContent = String(i+1);
		});
	}

	function setActive(tr){
		Array.prototype.forEach.call(document.querySelectorAll('#poLines tbody tr'), function(r){ r.classList.remove('po-active-row'); });
		tr.classList.add('po-active-row');
		activeRow = tr;
		updatePreview(tr);
	}

	function updatePreview(tr){
		if (!tr) return;
		var qty = Number(tr.querySelector('.qty').value)||0;
		var rate = Number(tr.querySelector('.rate').value)||0;
		var gst = Number(tr.querySelector('.prod-gst').value)||0;
		var disc = Number(tr.querySelector('.prod-disc').value)||0;
		var basic = qty * rate;
		var taxable = Math.max(0, basic - disc);
		var gstAmt = taxable * gst / 100;
		document.getElementById('pvHsn').textContent = tr.querySelector('.prod-hsn').value || '-';
		document.getElementById('pvGst').textContent = gst;
		document.getElementById('pvRate').textContent = num(rate);
		document.getElementById('pvQty').textContent = qty;
		document.getElementById('pvBasic').textContent = num(basic);
		document.getElementById('pvDisc').textContent = num(disc);
		document.getElementById('pvTaxable').textContent = num(taxable);
		document.getElementById('pvGstAmt').textContent = num(gstAmt);
		document.getElementById('pvTotal').textContent = num(taxable + gstAmt);
	}

	function collectLines(){
		var lines = [];
		Array.prototype.forEach.call(document.querySelectorAll('#poLines tbody tr'), function(tr){
			var name = tr.querySelector('.prod-name').value || tr.querySelector('.prod-search').value;
			if (!name) return;
			var qty = Number(tr.querySelector('.qty').value)||0;
			var rate = Number(tr.querySelector('.rate').value)||0;
			var gst = Number(tr.querySelector('.prod-gst').value)||0;
			var disc = Number(tr.querySelector('.prod-disc').value)||0;
			var basic = qty * rate;
			var taxable = Math.max(0, basic - disc);
			var gstAmt = taxable * gst / 100;
			tr.querySelector('.taxable-lbl').textContent = money(taxable);
			lines.push({
				tr: tr,
				product_id: tr.querySelector('.prod-id').value || null,
				product_type: tr.querySelector('.prod-type').value || '',
				product_name: name,
				hsn: tr.querySelector('.prod-hsn').value || '',
				gst_percent: gst,
				qty: qty,
				rate: rate,
				discount: disc,
				basic: basic,
				taxable: taxable,
				gst_amount: gstAmt
			});
		});
		return lines;
	}

	function recalc(){
		var lines = collectLines();
		var qty=0, rateSum=0, basic=0, taxable=0, gstTotal=0;
		lines.forEach(function(l){
			qty += l.qty; rateSum += l.rate; basic += l.basic; taxable += l.taxable; gstTotal += l.gst_amount;
		});
		document.getElementById('footQty').textContent = qty;
		document.getElementById('footRate').textContent = num(rateSum);
		document.getElementById('footTaxable').textContent = money(taxable);
		document.getElementById('sumBasic').textContent = money(basic);
		document.getElementById('sumTaxable').textContent = money(taxable);

		var sameState = !companyStateId || !vendorStateId || companyStateId === vendorStateId;
		var cgst=0, sgst=0, igst=0;
		if (sameState) { cgst = gstTotal/2; sgst = gstTotal - cgst; }
		else { igst = gstTotal; }
		document.getElementById('sumCgst').textContent = money(cgst);
		document.getElementById('sumSgst').textContent = money(sgst);
		document.getElementById('sumIgst').textContent = money(igst);

		var dPct = Number(document.getElementById('discount_percent').value)||0;
		var dAmt = Number(document.getElementById('discount_amount').value)||0;
		if (dPct > 0 && document.activeElement !== document.getElementById('discount_amount')) {
			dAmt = taxable * dPct / 100;
			document.getElementById('discount_amount').value = num(dAmt);
		}
		var after = Math.max(0, taxable - dAmt);
		var other = Number(document.getElementById('other_charges_amount').value)||0;
		var sub = after + gstTotal + other;
		var roundOff = 0;
		if (document.getElementById('apply_round_off').checked) {
			var rounded = Math.round(sub);
			roundOff = rounded - sub;
			sub = rounded;
		}
		document.getElementById('effDiscount').textContent = num(dAmt);
		document.getElementById('effOther').textContent = num(other);
		document.getElementById('roundOffAmt').textContent = num(roundOff);
		document.getElementById('effRound').textContent = num(roundOff);
		document.getElementById('grandTotal').textContent = money(sub);
		if (activeRow) updatePreview(activeRow);
	}

	function openHistory(tr){
		var pid = tr.querySelector('.prod-id').value;
		var name = tr.querySelector('.prod-name').value || tr.querySelector('.prod-search').value || 'Product';
		document.getElementById('poHistoryTitle').textContent = name;
		document.getElementById('histStock').textContent = tr.querySelector('.prod-stock').value || '0';
		document.getElementById('histBody').innerHTML = '<tr><td colspan="7" class="text-muted text-center">Loading...</td></tr>';
		var modal = new bootstrap.Modal(document.getElementById('poHistoryModal'));
		modal.show();
		if (!pid) {
			document.getElementById('histLastPurchase').textContent = '₹0.00';
			document.getElementById('histBody').innerHTML = '<tr><td colspan="7" class="text-muted text-center">Select a product first</td></tr>';
			return;
		}
		fetch(urls.history+'?product_id='+pid)
			.then(function(r){ return r.json(); })
			.then(function(res){
				document.getElementById('histLastPurchase').textContent = money(res.last_purchase||0);
				var rows = res.history || [];
				if (!rows.length) {
					document.getElementById('histBody').innerHTML = '<tr><td colspan="7" class="text-muted text-center">No purchase history</td></tr>';
					return;
				}
				document.getElementById('histBody').innerHTML = rows.map(function(h,i){
					var d = h.po_date ? new Date(h.po_date) : null;
					var ds = d ? d.toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'}) : '-';
					return '<tr>'+
						'<td>'+(i+1)+'</td><td>'+esc(ds)+'</td><td>'+esc(h.vendor_name||'-')+'</td>'+
						'<td>'+esc(h.po_no||'-')+'</td><td>'+esc(h.qty)+'</td><td>₹'+num(h.rate)+'</td>'+
						'<td><button type="button" class="btn btn-sm btn-primary use-rate" data-rate="'+esc(h.rate)+'">USE</button></td></tr>';
				}).join('');
				Array.prototype.forEach.call(document.querySelectorAll('#histBody .use-rate'), function(btn){
					btn.addEventListener('click', function(){
						tr.querySelector('.rate').value = btn.getAttribute('data-rate');
						recalc();
						bootstrap.Modal.getInstance(document.getElementById('poHistoryModal')).hide();
						setTimeout(function(){ focusField(tr.querySelector('.rate')); }, 200);
					});
				});
			});
	}

	document.getElementById('btnSavePo').addEventListener('click', function(){
		var btn = this;
		var lines = collectLines().map(function(l){
			return {
				product_id: l.product_id,
				product_type: l.product_type,
				product_name: l.product_name,
				hsn: l.hsn,
				gst_percent: l.gst_percent,
				qty: l.qty,
				rate: l.rate,
				discount: l.discount
			};
		});
		if (!document.getElementById('company_id').value || !document.getElementById('warehouse_id').value || !document.getElementById('purchase_vendor_id').value) {
			alert('Company, Warehouse and Vendor are required.');
			return;
		}
		if (!lines.length) { alert('Add at least one product line.'); return; }
		var incomplete = lines.filter(function(l){ return !(Number(l.qty) > 0); });
		if (incomplete.length) {
			alert('Each line needs quantity greater than 0.');
			return;
		}
		var fd = new FormData();
		fd.append('company_id', document.getElementById('company_id').value);
		fd.append('warehouse_id', document.getElementById('warehouse_id').value);
		fd.append('purchase_vendor_id', document.getElementById('purchase_vendor_id').value);
		fd.append('po_date', document.getElementById('po_date').value);
		fd.append('without_gst', 0);
		fd.append('direct_purchase', 0);
		fd.append('discount_percent', document.getElementById('discount_percent').value||0);
		fd.append('discount_amount', document.getElementById('discount_amount').value||0);
		fd.append('transport_percent', 0);
		fd.append('transport_amount', 0);
		fd.append('other_charges_desc', document.getElementById('other_charges_desc').value||'');
		fd.append('other_charges_amount', document.getElementById('other_charges_amount').value||0);
		fd.append('apply_round_off', document.getElementById('apply_round_off').checked ? 1 : 0);
		fd.append('items', JSON.stringify(lines));
		btn.disabled = true;
		fetch(urls.save, { method:'POST', body: fd, credentials:'same-origin' })
			.then(function(r){ return r.json(); })
			.then(function(res){
				btn.disabled = false;
				if (res.status === 'success') {
					window.location.href = res.redirect || <?php echo json_encode(base_url('purchase-in/orders')); ?>;
				} else {
					alert(res.message || 'Failed to save');
				}
			})
			.catch(function(){ btn.disabled = false; alert('Failed to save'); });
	});

	document.addEventListener('keydown', function(e){
		var tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
		var inModal = !!(e.target && e.target.closest && e.target.closest('.modal.show'));
		if (e.key === 'Escape') {
			if (openDd && openDd.style.display !== 'none') {
				hideProductDd();
				e.preventDefault();
				return;
			}
		}
		if (!(e.ctrlKey || e.metaKey)) return;
		var key = (e.key || '').toLowerCase();
		if (key === 's') {
			e.preventDefault();
			savePo();
			return;
		}
		if (inModal) return;
		if (key === 'i') {
			e.preventDefault();
			addRow();
			return;
		}
		if (key === 'd') {
			if (tag === 'input' || tag === 'textarea' || tag === 'select') return;
			e.preventDefault();
			deleteActiveRow();
			return;
		}
		if (key === 'h') {
			e.preventDefault();
			if (activeRow) openHistory(activeRow);
		}
	});

	addRow();
	function bootSelects(){
		initPoSelect2();
		applyDefaultSelections();
	}
	if (select2Ready()) {
		bootSelects();
	} else {
		var tries = 0;
		var t = setInterval(function(){
			tries++;
			if (select2Ready() || tries > 40) {
				clearInterval(t);
				bootSelects();
			}
		}, 50);
	}
})();
</script>
