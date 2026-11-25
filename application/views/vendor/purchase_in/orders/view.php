<?php
	$item = isset($item) && is_array($item) ? $item : array();
	$company = isset($company) && is_array($company) ? $company : array();
	$warehouse = isset($warehouse) && is_array($warehouse) ? $warehouse : array();
	$supplier = isset($supplier) && is_array($supplier) ? $supplier : array();
	$items = !empty($item['items']) ? $item['items'] : array();
	$fmt = function($n) { return '₹' . number_format((float)$n, 2); };
	$addr = function($row) {
		$parts = array();
		foreach (array('address_1','address_2','address_3') as $k) {
			if (!empty($row[$k])) $parts[] = $row[$k];
		}
		if (!empty($row['pincode'])) $parts[] = $row['pincode'];
		return $parts ? implode(', ', $parts) : '-';
	};
?>
<style>
.po-view-lines thead th {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)) !important;
	color: #fff !important;
	border-color: transparent !important;
	font-size: 0.75rem;
	text-transform: uppercase;
	letter-spacing: .03em;
	white-space: nowrap;
}
.po-view-lines thead th a,
.po-view-lines thead th span { color: #fff !important; }
.po-view-tax {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 0.75rem 1.25rem;
	background: #eef1f5;
	border-radius: 8px;
	padding: 0.65rem 0.85rem;
	font-size: 0.82rem;
}
.po-view-tax .tax { color: var(--vendor-primary, #7539ff); font-weight: 600; }
.po-view-footer {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
	gap: 0.75rem;
	margin-top: 0.85rem;
}
.po-view-charges { font-size: 0.8rem; color: #6c757d; }
.po-view-grand {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff));
	color: #fff !important;
	border-radius: 6px;
	padding: 0.4rem 0.85rem;
	font-weight: 700;
	font-size: 1rem;
	white-space: nowrap;
}
</style>
<?php $po_status = strtolower((string)($item['status'] ?? 'pending')); ?>
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Purchase Order <?php echo htmlspecialchars((string)$item['po_no']); ?></h6>
		<small class="text-muted">Date <?php echo !empty($item['po_date']) ? date('d-m-Y', strtotime($item['po_date'])) : '-'; ?> · Status <span class="text-capitalize"><?php echo htmlspecialchars((string)$item['status']); ?></span></small>
	</div>
	<div class="d-flex gap-2">
		<?php if ($po_status !== 'received'): ?>
			<a href="<?php echo base_url('purchase-in/orders?receive=' . (int)$item['id']); ?>" class="btn btn-success btn-sm"><i class="isax isax-box-tick me-1"></i>Receive Delivery</a>
		<?php endif; ?>
		<a href="<?php echo base_url('purchase-in/orders'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
	</div>
</div>

<div class="row g-3 mb-3">
	<div class="col-md-4">
		<div class="card h-100"><div class="card-body">
			<div class="fw-semibold mb-1">Company / Billing</div>
			<div class="small"><?php echo htmlspecialchars((string)($company['company_name'] ?? '-')); ?></div>
			<div class="small text-muted">GST: <?php echo htmlspecialchars((string)($company['gst_no'] ?? '-')); ?></div>
			<div class="small text-muted"><?php echo htmlspecialchars($addr($company)); ?></div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card h-100"><div class="card-body">
			<div class="fw-semibold mb-1">Warehouse / Shipping</div>
			<div class="small"><?php echo htmlspecialchars((string)($warehouse['warehouse_name'] ?? '-')); ?></div>
			<div class="small text-muted"><?php echo htmlspecialchars($addr($warehouse)); ?></div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card h-100"><div class="card-body">
			<div class="fw-semibold mb-1">Vendor</div>
			<div class="small"><?php echo htmlspecialchars((string)($supplier['vendor_name'] ?? '-')); ?></div>
			<div class="small text-muted">GST: <?php echo htmlspecialchars((string)($supplier['gst_no'] ?? '-')); ?></div>
			<div class="small text-muted"><?php echo htmlspecialchars($addr($supplier)); ?></div>
		</div></div>
	</div>
</div>

<div class="card mb-3">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-sm align-middle mb-0 po-view-lines">
				<thead>
					<tr>
						<th>#</th>
						<th>Product</th>
						<th>HSN</th>
						<th class="text-end">Ordered</th>
						<th class="text-end">Received</th>
						<th class="text-end">Pending</th>
						<th class="text-end">Rate</th>
						<th class="text-end">Taxable</th>
						<th class="text-end">Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$fmtQty = function($n) {
							return rtrim(rtrim(number_format((float)$n, 3, '.', ''), '0'), '.');
						};
					?>
					<?php if (!empty($items)): $sr=1; foreach ($items as $line): ?>
						<?php
							$ordered = (float)$line['qty'];
							$received = isset($line['qty_received']) ? (float)$line['qty_received'] : 0;
							$pending = isset($line['qty_pending']) ? (float)$line['qty_pending'] : max(0, $ordered - $received);
						?>
						<tr>
							<td><?php echo $sr++; ?></td>
							<td><?php echo htmlspecialchars((string)$line['product_name']); ?></td>
							<td><?php echo htmlspecialchars((string)($line['hsn'] ?: '-')); ?></td>
							<td class="text-end"><?php echo $fmtQty($ordered); ?></td>
							<td class="text-end text-success fw-semibold"><?php echo $fmtQty($received); ?></td>
							<td class="text-end <?php echo $pending > 0 ? 'text-warning fw-semibold' : 'text-muted'; ?>"><?php echo $fmtQty($pending); ?></td>
							<td class="text-end"><?php echo $fmt($line['rate']); ?></td>
							<td class="text-end"><?php echo $fmt($line['taxable_amount']); ?></td>
							<td class="text-end"><?php echo $fmt($line['line_total']); ?></td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td colspan="9" class="text-center text-muted">No lines</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="po-view-tax">
			<span>Items - Basic: <strong><?php echo $fmt($item['basic_amount']); ?></strong></span>
			<span>Taxable: <strong><?php echo $fmt($item['taxable_amount']); ?></strong></span>
			<span class="tax">CGST: <strong><?php echo $fmt($item['cgst_amount']); ?></strong></span>
			<span class="tax">SGST: <strong><?php echo $fmt($item['sgst_amount']); ?></strong></span>
			<span class="tax">IGST: <strong><?php echo $fmt($item['igst_amount']); ?></strong></span>
		</div>
		<div class="po-view-footer">
			<div class="po-view-charges">
				Discount <?php echo $fmt($item['discount_amount']); ?>
				· Transport <?php echo $fmt($item['transport_amount']); ?>
				· Other <?php echo $fmt($item['other_charges_amount']); ?>
				<?php if (!empty($item['apply_round_off']) || (float)$item['round_off'] != 0): ?>
					· Round Off <?php echo $fmt($item['round_off']); ?>
				<?php endif; ?>
			</div>
			<div class="po-view-grand">Grand Total <?php echo $fmt($item['grand_total']); ?></div>
		</div>
	</div>
</div>
