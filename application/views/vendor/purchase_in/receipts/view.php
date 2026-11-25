<?php
	$item = isset($item) && is_array($item) ? $item : array();
	$company = isset($company) && is_array($company) ? $company : array();
	$warehouse = isset($warehouse) && is_array($warehouse) ? $warehouse : array();
	$supplier = isset($supplier) && is_array($supplier) ? $supplier : array();
	$items = !empty($item['items']) ? $item['items'] : array();
	$fmtQty = function($n) {
		return rtrim(rtrim(number_format((float)$n, 3, '.', ''), '0'), '.');
	};
?>
<style>
.pr-view-lines thead th {
	background: var(--vendor-sidebar-bg, var(--vendor-primary, #7539ff)) !important;
	color: #fff !important;
	border-color: transparent !important;
	font-size: 0.75rem;
	text-transform: uppercase;
}
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Purchase Receipt <?php echo htmlspecialchars((string)$item['pr_no']); ?></h6>
		<small class="text-muted">
			PO <?php echo htmlspecialchars((string)($item['po_no'] ?: '-')); ?>
			· Invoice <?php echo htmlspecialchars((string)($item['invoice_no'] ?: '-')); ?>
			· <?php echo !empty($item['received_date']) ? date('d-m-Y', strtotime($item['received_date'])) : '-'; ?>
		</small>
	</div>
	<a href="<?php echo base_url('purchase-in/receipts'); ?>" class="btn btn-outline-secondary btn-sm"><i class="isax isax-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-3 mb-3">
	<div class="col-md-4">
		<div class="card h-100"><div class="card-body">
			<div class="fw-semibold mb-1">Company</div>
			<div class="small"><?php echo htmlspecialchars((string)($company['company_name'] ?? '-')); ?></div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card h-100"><div class="card-body">
			<div class="fw-semibold mb-1">Warehouse</div>
			<div class="small"><?php echo htmlspecialchars((string)($warehouse['warehouse_name'] ?? '-')); ?></div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card h-100"><div class="card-body">
			<div class="fw-semibold mb-1">Vendor</div>
			<div class="small"><?php echo htmlspecialchars((string)($supplier['vendor_name'] ?? '-')); ?></div>
		</div></div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-sm align-middle mb-0 pr-view-lines">
				<thead>
					<tr>
						<th>#</th>
						<th>Product</th>
						<th class="text-end">Ordered Qty</th>
						<th class="text-end">Received Qty</th>
						<th class="text-end">Rate</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): $sr=1; foreach ($items as $line): ?>
						<tr>
							<td><?php echo $sr++; ?></td>
							<td><?php echo htmlspecialchars((string)$line['product_name']); ?></td>
							<td class="text-end"><?php echo $fmtQty($line['ordered_qty']); ?></td>
							<td class="text-end text-success fw-semibold"><?php echo $fmtQty($line['receive_qty']); ?></td>
							<td class="text-end">₹<?php echo number_format((float)$line['rate'], 2); ?></td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td colspan="5" class="text-center text-muted">No lines</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
			<?php
				$t_pct = (float)(isset($item['transport_percent']) ? $item['transport_percent'] : 0);
				$t_amt = (float)(isset($item['transport_amount']) ? $item['transport_amount'] : 0);
				$t_effect = round($t_amt + ($t_amt * $t_pct / 100), 2);
			?>
			<div class="small text-muted">
				Transport Charges And GST:
				<?php echo number_format($t_pct, 2); ?>%
				· Amt ₹<?php echo number_format($t_amt, 2); ?>
				· Effect ₹<?php echo number_format($t_effect, 2); ?>
			</div>
			<div class="fw-bold">
				Items ₹<?php echo number_format((float)(isset($item['items_total']) ? $item['items_total'] : 0), 2); ?>
				· Total ₹<?php echo number_format((float)(isset($item['grand_total']) ? $item['grand_total'] : 0), 2); ?>
			</div>
		</div>
	</div>
</div>
