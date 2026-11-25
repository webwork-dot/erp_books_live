<?php
	$suggested = isset($suggested_po) && is_array($suggested_po) ? $suggested_po : array('po_no' => 'PO-0001/26-27', 'fy_label' => '26-27');
	$companies = isset($companies) ? $companies : array();
	$warehouses = isset($warehouses) ? $warehouses : array();
	$suppliers = isset($suppliers) ? $suppliers : array();
	$active_suppliers = array();
	foreach ($suppliers as $s) {
		if (!isset($s['status']) || (int)$s['status'] === 1) {
			$active_suppliers[] = $s;
		}
	}
?>
<style>
.po-form .form-label { font-size: 0.78rem; font-weight: 600; margin-bottom: 0.2rem; }
.po-form .card { border: 1px solid #e6ebf2; border-radius: 10px; }
.po-party-box { background: #f8fafc; border: 1px solid #e6ebf2; border-radius: 8px; padding: 0.75rem; min-height: 140px; }
.po-party-box h6 { font-size: 0.8rem; font-weight: 700; margin-bottom: 0.5rem; }
.po-party-box .muted { color: #6c757d; font-size: 0.75rem; line-height: 1.35; }
.po-lines-card { overflow: visible; }
.po-lines-card .card-body { overflow: visible; }
.po-lines-wrap { overflow: visible !important; }
.po-lines thead th {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)) !important;
	color: #fff !important;
	font-size: 0.72rem;
	text-transform: uppercase;
	letter-spacing: .03em;
	white-space: nowrap;
	border-color: transparent !important;
	padding: 0.55rem 0.5rem;
}
.po-lines tbody td { vertical-align: middle; background: #faf7f2; }
.po-lines tbody td.po-prod-cell { overflow: visible; position: relative; }
.po-lines tfoot td { background: #fff; border-top: 1px solid #e6ebf2; }
.po-lines .form-control, .po-lines .form-select { font-size: 0.8rem; }
.po-summary { border: 1px dashed #c5ccd6; border-radius: 8px; padding: 0.75rem; background: #fff; }
.po-summary .row-line { display: flex; justify-content: space-between; font-size: 0.78rem; padding: 0.2rem 0; }
.po-summary .row-line.total { font-weight: 700; border-top: 1px solid #e6ebf2; margin-top: 0.35rem; padding-top: 0.45rem; }
.po-product-dd {
	position: fixed;
	z-index: 1080;
	background: #fff;
	border: 1px solid #dbe1ea;
	border-radius: 8px;
	max-height: 280px;
	overflow: auto;
	box-shadow: 0 12px 28px rgba(0,0,0,.14);
	display: none;
}
.po-product-dd .item { padding: 0.55rem 0.7rem; cursor: pointer; border-bottom: 1px solid #f1f3f6; }
.po-product-dd .item:hover { background: #fff7ed; }
.po-product-dd .item .name { font-weight: 600; font-size: 0.82rem; }
.po-product-dd .item .meta { font-size: 0.72rem; color: #6c757d; }
.po-tax-bar { background: #eef1f5; border-radius: 8px; padding: 0.55rem 0.75rem; font-size: 0.78rem; }
.po-tax-bar span { margin-right: 1rem; }
.po-tax-bar .tax { color: #2563eb; font-weight: 600; }
.po-grand { background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)); color: #fff; border-radius: 6px; padding: 0.35rem 0.75rem; font-weight: 700; }
.po-charges thead th {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)) !important;
	color: #fff !important;
	border-color: transparent !important;
}
.po-active-row { box-shadow: inset 3px 0 0 #f97316; }
.po-actions { display: inline-flex; flex-direction: row; align-items: center; gap: 0.35rem; white-space: nowrap; }
.po-actions .btn { flex: 0 0 auto; }
.po-line-meta { display: inline-flex; flex-direction: row; align-items: center; gap: 0.4rem; white-space: nowrap; }
.po-line-meta .line-no { min-width: 1rem; font-weight: 600; }
.po-line-meta .btn-hist { color: var(--vendor-primary, #7539ff); line-height: 1; padding: 0; border: 0; background: transparent; display: inline-flex; align-items: center; }
.po-line-meta .btn-hist i { font-size: 1rem; }
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">New Purchase Order</h6>
		<small class="text-muted">Company, warehouse, vendor and line items</small>
	</div>
	<a href="<?php echo base_url('purchase-in/orders'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
</div>

<div class="po-form" id="poForm">
	<div class="card mb-3">
		<div class="card-body">
			<div class="row g-2">
				<div class="col-md-3">
					<label class="form-label">Company <span class="text-danger">*</span></label>
					<select id="company_id" class="form-select form-select-sm">
						<option value="">Choose...</option>
						<?php foreach ($companies as $c): ?>
							<option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars((string)$c['company_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Warehouse <span class="text-danger">*</span></label>
					<select id="warehouse_id" class="form-select form-select-sm">
						<option value="">Choose...</option>
						<?php foreach ($warehouses as $w): if ((int)$w['status'] !== 1) continue; ?>
							<option value="<?php echo (int)$w['id']; ?>"><?php echo htmlspecialchars((string)$w['warehouse_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Vendor <span class="text-danger">*</span></label>
					<select id="purchase_vendor_id" class="form-select form-select-sm">
						<option value="">Choose...</option>
						<?php foreach ($active_suppliers as $s): ?>
							<option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars((string)$s['vendor_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="form-label">Suggested PO No</label>
					<input type="text" id="po_no" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$suggested['po_no']); ?>" readonly>
					<small class="text-muted">Auto-generated for FY <?php echo htmlspecialchars((string)$suggested['fy_label']); ?> (Apr–Mar)</small>
				</div>
			</div>

			<div class="row g-2 mt-2 align-items-end">
				<div class="col-md-3">
					<label class="form-label">PO Date</label>
					<input type="date" id="po_date" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" disabled>
				</div>
			</div>

			<div class="row g-2 mt-3">
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

	<div class="row g-3">
		<div class="col-lg-9">
			<div class="card po-lines-card">
				<div class="card-body p-2">
					<div class="po-lines-wrap">
						<table class="table table-sm po-lines mb-0" id="poLines">
							<thead>
								<tr>
									<th style="width:48px">#</th>
									<th>Product</th>
									<th style="width:90px">Qty</th>
									<th style="width:110px">Rate</th>
									<th style="width:120px">Taxable</th>
									<th style="width:110px">Actions</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<td></td>
									<td class="text-end fw-semibold small">Totals</td>
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

			<div class="card mt-3">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-sm align-middle mb-0 po-charges">
							<thead>
								<tr>
									<th>Type</th>
									<th>Description</th>
									<th style="width:90px">%</th>
									<th style="width:120px">Amount</th>
									<th style="width:120px">Effect</th>
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
		</div>

		<div class="col-lg-3">
			<div class="po-summary sticky-top" style="top:80px">
				<div class="fw-semibold mb-2 small text-uppercase text-muted">Line Preview</div>
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

	<div class="d-flex justify-content-end gap-2 mt-3 mb-4">
		<a href="<?php echo base_url('purchase-in/orders'); ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
		<button type="button" class="btn btn-primary btn-sm" id="btnSavePo"><i class="isax isax-tick-circle me-1"></i>Save Purchase Order</button>
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
	var companyStateId = 0, vendorStateId = 0, activeRow = null, openDd = null, openInput = null;

	function money(n){ return '₹' + (Number(n)||0).toFixed(2); }
	function num(n){ return (Number(n)||0).toFixed(2); }

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
				if (current) sel.value = current;
				if (!sel.value && sel.options.length === 2) sel.selectedIndex = 1;
				loadParty('warehouse', sel.value, 'boxWarehouse');
			});
	}

	document.getElementById('company_id').addEventListener('change', function(){
		loadParty('company', this.value, 'boxCompany', function(sid){ companyStateId = sid; });
		filterWarehouses(this.value);
	});
	document.getElementById('warehouse_id').addEventListener('change', function(){
		loadParty('warehouse', this.value, 'boxWarehouse');
	});
	document.getElementById('purchase_vendor_id').addEventListener('change', function(){
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
				'<input type="text" class="form-control form-control-sm prod-search" placeholder="Type name, category, or HSN..." autocomplete="off">'+
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
		return tr;
	}

	function hideProductDd(){
		if (openDd) {
			openDd.style.display = 'none';
			openDd = null;
			openInput = null;
		}
	}

	function placeProductDd(input, dd){
		var rect = input.getBoundingClientRect();
		var width = Math.max(rect.width, 320);
		var left = rect.left;
		if (left + width > window.innerWidth - 8) {
			left = Math.max(8, window.innerWidth - width - 8);
		}
		dd.style.left = left + 'px';
		dd.style.top = (rect.bottom + 4) + 'px';
		dd.style.width = width + 'px';
		dd.style.display = 'block';
		openDd = dd;
		openInput = input;
	}

	function bindRow(tr){
		tr.querySelector('.btn-add').addEventListener('click', function(){ addRow(); });
		tr.querySelector('.btn-del').addEventListener('click', function(){
			var rows = document.querySelectorAll('#poLines tbody tr');
			if (rows.length <= 1) {
				tr.querySelector('.prod-search').value = '';
				tr.querySelector('.prod-id').value = '';
				tr.querySelector('.rate').value = 0;
				tr.querySelector('.qty').value = 1;
				hideProductDd();
				recalc();
				return;
			}
			if (tr._productDd && tr._productDd.parentNode) {
				tr._productDd.parentNode.removeChild(tr._productDd);
			}
			if (openDd === tr._productDd) hideProductDd();
			tr.remove();
			renumber();
			recalc();
		});
		tr.querySelector('.btn-hist').addEventListener('click', function(){ openHistory(tr); });
		['qty','rate'].forEach(function(cls){
			tr.querySelector('.'+cls).addEventListener('input', function(){ setActive(tr); recalc(); });
			tr.querySelector('.'+cls).addEventListener('focus', function(){ setActive(tr); hideProductDd(); });
		});
		tr.addEventListener('click', function(){ setActive(tr); });

		var input = tr.querySelector('.prod-search');
		var dd = tr._productDd;
		var timer = null;
		input.addEventListener('focus', function(){ setActive(tr); searchProducts(tr, input.value); });
		input.addEventListener('input', function(){
			clearTimeout(timer);
			timer = setTimeout(function(){ searchProducts(tr, input.value); }, 220);
		});
	}

	document.addEventListener('click', function(e){
		if (!openDd) return;
		if (openInput && (openInput === e.target || openInput.contains(e.target))) return;
		if (openDd.contains(e.target)) return;
		hideProductDd();
	});
	window.addEventListener('scroll', function(){
		if (openDd && openInput) placeProductDd(openInput, openDd);
	}, true);
	window.addEventListener('resize', function(){
		if (openDd && openInput) placeProductDd(openInput, openDd);
	});

	function searchProducts(tr, q){
		var dd = tr._productDd;
		var input = tr.querySelector('.prod-search');
		fetch(urls.search+'?q='+encodeURIComponent(q||''))
			.then(function(r){ return r.json(); })
			.then(function(res){
				var items = res.products || [];
				dd.innerHTML = '';
				if (!items.length) {
					dd.innerHTML = '<div class="item text-muted">No products found</div>';
					placeProductDd(input, dd);
					return;
				}
				items.forEach(function(p){
					var el = document.createElement('div');
					el.className = 'item';
					el.innerHTML = '<div class="name">'+esc(p.product_name)+'</div>'+
						'<div class="meta">Stock: '+(p.stock||0)+' · '+esc(p.category||'')+' · ₹'+num(p.rate)+'</div>';
					el.addEventListener('mousedown', function(ev){ ev.preventDefault(); });
					el.addEventListener('click', function(){
						applyProduct(tr, p, true);
						hideProductDd();
						recalc();
					});
					dd.appendChild(el);
				});
				placeProductDd(input, dd);
			});
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

	addRow();
})();
</script>
