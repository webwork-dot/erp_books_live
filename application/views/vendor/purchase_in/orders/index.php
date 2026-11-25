<?php
	$status_badge = function($status) {
		$s = strtolower((string)$status);
		if ($s === 'received') return 'bg-success-subtle text-success';
		if ($s === 'partial') return 'bg-warning-subtle text-warning';
		return 'bg-secondary-subtle text-secondary';
	};
?>
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Purchase Orders</h6>
		<small class="text-muted">Create and track purchase orders</small>
	</div>
	<a href="<?php echo base_url('purchase-in/orders/add'); ?>" class="btn btn-primary btn-sm">
		<i class="isax isax-add me-1"></i>New Purchase Order
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
						<th>PO No</th>
						<th>Date</th>
						<th>Company</th>
						<th>Warehouse</th>
						<th>Vendor</th>
						<th class="text-end">Grand Total</th>
						<th>Status</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): ?>
						<?php $sr = 1; foreach ($items as $row): ?>
							<?php $st = strtolower((string)$row['status']); ?>
							<tr>
								<td><?php echo $sr++; ?></td>
								<td class="fw-semibold"><?php echo htmlspecialchars((string)$row['po_no']); ?></td>
								<td><?php echo !empty($row['po_date']) ? date('d-m-Y', strtotime($row['po_date'])) : '-'; ?></td>
								<td><?php echo htmlspecialchars((string)($row['company_name'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['warehouse_name'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['supplier_name'] ?: '-')); ?></td>
								<td class="text-end">₹<?php echo number_format((float)$row['grand_total'], 2); ?></td>
								<td><span class="badge <?php echo $status_badge($st); ?> text-capitalize"><?php echo htmlspecialchars((string)$row['status']); ?></span></td>
								<td class="text-end text-nowrap">
									<a href="<?php echo base_url('purchase-in/orders/view/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="isax isax-eye"></i></a>
									<?php if ($st !== 'received'): ?>
										<button type="button" class="btn btn-sm btn-outline-success btn-receive" data-po-id="<?php echo (int)$row['id']; ?>" title="Receive Delivery">
											<i class="isax isax-box-tick"></i>
										</button>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr><td colspan="9" class="text-center text-muted py-4">No purchase orders yet. <a href="<?php echo base_url('purchase-in/orders/add'); ?>">Create your first PO</a></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<style>
#receiveDeliveryModal .recv-modal-header {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)) !important;
	color: #fff !important;
}
#receiveDeliveryModal .recv-modal-header .modal-title,
#receiveDeliveryModal .recv-modal-header h6 {
	color: #fff !important;
}
#receiveDeliveryModal .recv-lines thead th {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)) !important;
	color: #fff !important;
	border-color: transparent !important;
	font-size: 0.75rem;
	text-transform: uppercase;
	letter-spacing: .03em;
	white-space: nowrap;
}
#receiveDeliveryModal .recv-lines thead th.recv-qty-col {
	min-width: 120px;
}
</style>
<div class="modal fade" id="receiveDeliveryModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header recv-modal-header">
				<h6 class="modal-title mb-0 text-white">Receive Delivery</h6>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div id="recvError" class="alert alert-danger py-2 d-none"></div>
				<div class="row g-2 mb-3">
					<div class="col-md-4">
						<label class="form-label small fw-semibold mb-1">Company</label>
						<input type="text" class="form-control form-control-sm" id="recvCompany" readonly>
					</div>
					<div class="col-md-4">
						<label class="form-label small fw-semibold mb-1">Warehouse</label>
						<input type="text" class="form-control form-control-sm" id="recvWarehouse" readonly>
					</div>
					<div class="col-md-4">
						<label class="form-label small fw-semibold mb-1">Vendor</label>
						<input type="text" class="form-control form-control-sm" id="recvVendor" readonly>
					</div>
					<div class="col-md-4">
						<label class="form-label small fw-semibold mb-1">Suggested PR No</label>
						<input type="text" class="form-control form-control-sm" id="recvPrNo" readonly>
					</div>
					<div class="col-md-4">
						<label class="form-label small fw-semibold mb-1">Invoice No <span class="text-danger">*</span></label>
						<input type="text" class="form-control form-control-sm" id="recvInvoiceNo" placeholder="Invoice Number">
					</div>
					<div class="col-md-4">
						<label class="form-label small fw-semibold mb-1">Received Date</label>
						<input type="date" class="form-control form-control-sm" id="recvDate" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-sm align-middle mb-0 recv-lines">
						<thead>
							<tr>
								<th>#</th>
								<th>Product</th>
								<th class="text-end">Ordered</th>
								<th class="text-end">Received</th>
								<th class="text-end">Pending</th>
								<th class="recv-qty-col">Receive Qty</th>
							</tr>
						</thead>
						<tbody id="recvLinesBody"></tbody>
					</table>
				</div>
				<div class="mt-3">
					<label class="form-label small fw-semibold mb-2">Transport Charges And GST</label>
					<div class="d-flex flex-wrap align-items-end gap-2">
						<div style="width:110px">
							<label class="form-label small text-muted mb-1">%</label>
							<input type="number" step="0.01" min="0" class="form-control form-control-sm" id="recvTransportPercent" value="0" placeholder="%">
						</div>
						<div style="width:130px">
							<label class="form-label small text-muted mb-1">Amount</label>
							<input type="number" step="0.01" min="0" class="form-control form-control-sm" id="recvTransportAmount" value="0" placeholder="Amount">
						</div>
						<div style="width:130px">
							<label class="form-label small text-muted mb-1">Effect</label>
							<input type="text" class="form-control form-control-sm bg-light" id="recvTransportEffect" value="0.00" readonly>
						</div>
						<div class="ms-auto text-end">
							<div class="small text-muted">Items Total: <strong id="recvItemsTotal">₹0.00</strong></div>
							<div class="fw-bold">Receipt Total: <span id="recvGrandTotal">₹0.00</span></div>
						</div>
					</div>
				</div>
				<input type="hidden" id="recvPoId" value="">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary btn-sm" id="btnSaveReceive"><i class="isax isax-tick-circle me-1"></i>Save Receipt</button>
			</div>
		</div>
	</div>
</div>

<script>
(function(){
	function whenBootstrapReady(cb){
		if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
			cb();
			return;
		}
		window.addEventListener('load', function(){ cb(); });
		var tries = 0;
		var t = setInterval(function(){
			tries++;
			if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
				clearInterval(t);
				cb();
			} else if (tries > 40) {
				clearInterval(t);
			}
		}, 50);
	}

	whenBootstrapReady(function(){
		var urls = {
			data: <?php echo json_encode(base_url('purchase-in/orders/receive-data/')); ?>,
			save: <?php echo json_encode(base_url('purchase-in/orders/receive-save')); ?>
		};
		var modalEl = document.getElementById('receiveDeliveryModal');
		if (!modalEl || typeof bootstrap === 'undefined') return;
		var modal = bootstrap.Modal.getOrCreateInstance(modalEl);

		var lineRates = {};
		function showErr(msg){
			var el = document.getElementById('recvError');
			el.textContent = msg || '';
			el.classList.toggle('d-none', !msg);
		}
		function esc(s){ var d=document.createElement('div'); d.textContent=s==null?'':String(s); return d.innerHTML; }
		function fmtQty(n){ var x=Number(n)||0; return (Math.round(x*1000)/1000).toString(); }
		function money(n){ return '₹' + (Number(n)||0).toFixed(2); }
		function recalcRecvTotal(){
			var itemsTotal = 0;
			document.querySelectorAll('#recvLinesBody tr[data-po-item-id]').forEach(function(tr){
				var id = tr.getAttribute('data-po-item-id');
				var qty = Number(tr.querySelector('.recv-qty').value) || 0;
				var rate = Number(lineRates[id] || 0);
				itemsTotal += qty * rate;
			});
			var tPct = Number(document.getElementById('recvTransportPercent').value) || 0;
			var tAmt = Number(document.getElementById('recvTransportAmount').value) || 0;
			var tEffect = tAmt + (tAmt * tPct / 100);
			document.getElementById('recvTransportEffect').value = tEffect.toFixed(2);
			document.getElementById('recvItemsTotal').textContent = money(itemsTotal);
			document.getElementById('recvGrandTotal').textContent = money(itemsTotal + tEffect);
		}

		function openReceive(poId){
			showErr('');
			lineRates = {};
			document.getElementById('recvPoId').value = poId;
			document.getElementById('recvCompany').value = '';
			document.getElementById('recvWarehouse').value = '';
			document.getElementById('recvVendor').value = '';
			document.getElementById('recvPrNo').value = '';
			document.getElementById('recvInvoiceNo').value = '';
			document.getElementById('recvDate').value = '<?php echo date('Y-m-d'); ?>';
			document.getElementById('recvDate').min = '<?php echo date('Y-m-d'); ?>';
			document.getElementById('recvTransportPercent').value = '0';
			document.getElementById('recvTransportAmount').value = '0';
			document.getElementById('recvTransportEffect').value = '0.00';
			document.getElementById('recvLinesBody').innerHTML = '<tr><td colspan="6" class="text-center text-muted">Loading...</td></tr>';
			recalcRecvTotal();
			modal.show();

			fetch(urls.data + poId, { credentials: 'same-origin' })
				.then(function(r){ return r.text().then(function(t){ try { return JSON.parse(t); } catch(e){ throw new Error('Invalid response'); } }); })
				.then(function(res){
					if (res.status !== 'success') {
						showErr(res.message || 'Failed to load PO');
						return;
					}
					document.getElementById('recvPoId').value = res.po.id;
					document.getElementById('recvCompany').value = res.po.company || '';
					document.getElementById('recvWarehouse').value = res.po.warehouse || '';
					document.getElementById('recvVendor').value = res.po.vendor || '';
					document.getElementById('recvPrNo').value = (res.suggested_pr && res.suggested_pr.pr_no) ? res.suggested_pr.pr_no : '';
					var lines = res.lines || [];
					document.getElementById('recvLinesBody').innerHTML = lines.map(function(l, i){
						lineRates[l.po_item_id] = Number(l.rate) || 0;
						var pending = Number(l.qty_pending) || 0;
						var done = pending <= 0;
						var qtyInput = done
							? '<input type="number" class="form-control form-control-sm recv-qty" value="0" disabled title="Already fully received">'
							: '<input type="number" min="0" step="0.001" max="'+pending+'" class="form-control form-control-sm recv-qty" value="'+pending+'">';
						return '<tr data-po-item-id="'+l.po_item_id+'"'+(done ? ' class="table-light text-muted"' : '')+'>'+
							'<td>'+(i+1)+'</td>'+
							'<td>'+esc(l.product_name)+(done ? ' <span class="badge bg-success-subtle text-success">Received</span>' : '')+'</td>'+
							'<td class="text-end">'+fmtQty(l.ordered_qty)+'</td>'+
							'<td class="text-end text-success fw-semibold">'+fmtQty(l.qty_received)+'</td>'+
							'<td class="text-end '+(pending > 0 ? 'text-warning fw-semibold' : '')+'">'+fmtQty(l.qty_pending)+'</td>'+
							'<td>'+qtyInput+'</td>'+
						'</tr>';
					}).join('');
					document.querySelectorAll('#recvLinesBody .recv-qty:not([disabled])').forEach(function(inp){
						inp.addEventListener('input', recalcRecvTotal);
					});
					recalcRecvTotal();
				})
				.catch(function(err){ showErr(err.message || 'Failed to load PO'); });
		}

		['recvTransportPercent','recvTransportAmount'].forEach(function(id){
			document.getElementById(id).addEventListener('input', recalcRecvTotal);
		});

		document.querySelectorAll('.btn-receive').forEach(function(btn){
			btn.addEventListener('click', function(e){
				e.preventDefault();
				openReceive(btn.getAttribute('data-po-id'));
			});
		});

		document.getElementById('btnSaveReceive').addEventListener('click', function(){
			var btn = this;
			var items = [];
			document.querySelectorAll('#recvLinesBody tr').forEach(function(tr){
				var id = tr.getAttribute('data-po-item-id');
				if (!id) return;
				var qty = Number(tr.querySelector('.recv-qty').value) || 0;
				if (qty > 0) items.push({ po_item_id: id, receive_qty: qty });
			});
			var invoice = document.getElementById('recvInvoiceNo').value.trim();
			if (!invoice) { showErr('Invoice No is required.'); return; }
			if (!items.length) { showErr('Enter receive qty for at least one line.'); return; }
			showErr('');
			var fd = new FormData();
			var recvDate = document.getElementById('recvDate').value;
			var today = '<?php echo date('Y-m-d'); ?>';
			if (recvDate && recvDate < today) {
				showErr('Past received dates are not allowed.');
				return;
			}
			fd.append('po_id', document.getElementById('recvPoId').value);
			fd.append('invoice_no', invoice);
			fd.append('received_date', recvDate);
			fd.append('transport_percent', document.getElementById('recvTransportPercent').value || 0);
			fd.append('transport_amount', document.getElementById('recvTransportAmount').value || 0);
			fd.append('items', JSON.stringify(items));
			btn.disabled = true;
			fetch(urls.save, { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function(r){ return r.json(); })
				.then(function(res){
					btn.disabled = false;
					if (res.status === 'success') {
						window.location.href = res.redirect || <?php echo json_encode(base_url('purchase-in/receipts')); ?>;
					} else {
						showErr(res.message || 'Failed to save');
					}
				})
				.catch(function(){ btn.disabled = false; showErr('Failed to save'); });
		});

		var params = new URLSearchParams(window.location.search);
		var autoId = params.get('receive');
		if (autoId) openReceive(autoId);
	});
})();
</script>
