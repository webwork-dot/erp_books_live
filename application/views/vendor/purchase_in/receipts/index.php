<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Purchase Receipts</h6>
		<small class="text-muted">Goods received against purchase orders</small>
	</div>
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
						<th>PR No</th>
						<th>PO No</th>
						<th>Invoice No</th>
						<th>Received Date</th>
						<th>Company</th>
						<th>Warehouse</th>
						<th>Vendor</th>
						<th class="text-end">Total Price</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): ?>
						<?php $sr = 1; foreach ($items as $row): ?>
							<tr>
								<td><?php echo $sr++; ?></td>
								<td class="fw-semibold"><?php echo htmlspecialchars((string)$row['pr_no']); ?></td>
								<td>
									<?php if (!empty($row['po_id'])): ?>
										<a href="<?php echo base_url('purchase-in/orders/view/' . (int)$row['po_id']); ?>"><?php echo htmlspecialchars((string)($row['po_no'] ?: '-')); ?></a>
									<?php else: ?>
										-
									<?php endif; ?>
								</td>
								<td><?php echo htmlspecialchars((string)($row['invoice_no'] ?: '-')); ?></td>
								<td><?php echo !empty($row['received_date']) ? date('d-m-Y', strtotime($row['received_date'])) : '-'; ?></td>
								<td><?php echo htmlspecialchars((string)($row['company_name'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['warehouse_name'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['supplier_name'] ?: '-')); ?></td>
								<td class="text-end fw-semibold">₹<?php echo number_format((float)(isset($row['grand_total']) ? $row['grand_total'] : 0), 2); ?></td>
								<td class="text-end">
									<a href="<?php echo base_url('purchase-in/receipts/view/' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="isax isax-eye"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr><td colspan="10" class="text-center text-muted py-4">No purchase receipts yet. Receive delivery from a pending PO.</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
