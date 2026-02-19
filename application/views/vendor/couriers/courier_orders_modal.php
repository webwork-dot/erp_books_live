<?php
$orders_base_url = isset($orders_base_url) ? $orders_base_url : base_url('orders/view/');
$show_track_date = (!empty($out_for_delivery) && isset($out_for_delivery[0]['track_date'])) || (!empty($delivered) && isset($delivered[0]['track_date']));
$show_shipping_awb = (!empty($out_for_delivery) && (isset($out_for_delivery[0]['ship_order_id']) || isset($out_for_delivery[0]['awb_no']))) || (!empty($delivered) && (isset($delivered[0]['ship_order_id']) || isset($delivered[0]['awb_no'])));
$count_ofd = !empty($out_for_delivery) ? count($out_for_delivery) : 0;
$count_del = !empty($delivered) ? count($delivered) : 0;
?>
<div class="courier-orders-modal-content">
	<?php if (empty($out_for_delivery) && empty($delivered)): ?>
		<p class="text-muted mb-0">No orders found for this courier.</p>
	<?php else: ?>
		<ul class="nav nav-tabs mb-3" id="courierOrdersTabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link <?= $count_ofd > 0 ? 'active' : '' ?>" id="tab-ofd" data-bs-toggle="tab" data-bs-target="#pane-ofd" type="button" role="tab">
					<i class="fa fa-truck me-1"></i>Out for Delivery <span class="badge bg-info ms-1"><?= $count_ofd ?></span>
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link <?= $count_ofd == 0 && $count_del > 0 ? 'active' : '' ?>" id="tab-delivered" data-bs-toggle="tab" data-bs-target="#pane-delivered" type="button" role="tab">
					<i class="fa fa-check-circle me-1"></i>Delivered <span class="badge bg-success ms-1"><?= $count_del ?></span>
				</button>
			</li>
		</ul>
		<div class="tab-content" id="courierOrdersTabContent">
			<div class="tab-pane fade <?= $count_ofd > 0 ? 'show active' : '' ?>" id="pane-ofd" role="tabpanel">
				<?php if (!empty($out_for_delivery)): ?>
			<div class="table-responsive">
				<table class="table table-sm table-hover">
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Customer</th>
							<th>Invoice</th>
							<?php if ($show_shipping_awb): ?><th>Shipping #</th><th>AWB</th><?php endif; ?>
							<th>Marked Out for Delivery</th>
							<?php if ($show_track_date): ?><th>Courier Assigned</th><?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($out_for_delivery as $o): ?>
						<tr>
							<td>
								<a href="<?= $orders_base_url . htmlspecialchars($o['order_unique_id']) ?>" class="text-primary fw-bold" target="_blank"><?= htmlspecialchars($o['order_unique_id']) ?></a>
							</td>
							<td>
								<div><?= htmlspecialchars($o['user_name']) ?></div>
								<?php if (!empty($o['user_phone'])): ?><small class="text-muted"><?= htmlspecialchars($o['user_phone']) ?></small><?php endif; ?>
							</td>
							<td><?= htmlspecialchars($o['invoice_no'] ?: '-') ?></td>
							<?php if ($show_shipping_awb): ?>
							<td><?= !empty($o['ship_order_id']) ? htmlspecialchars($o['ship_order_id']) : '<span class="text-muted">-</span>' ?></td>
							<td><?= !empty($o['awb_no']) ? '<code>' . htmlspecialchars($o['awb_no']) . '</code>' : '<span class="text-muted">-</span>' ?></td>
							<?php endif; ?>
							<td>
								<?php if (!empty($o['shipment_date']) && $o['shipment_date'] != '0000-00-00 00:00:00'): ?>
									<?= date('d M Y, h:i A', strtotime($o['shipment_date'])) ?>
								<?php else: ?>
									<span class="text-muted">-</span>
								<?php endif; ?>
							</td>
							<?php if ($show_track_date): ?>
							<td>
								<?php if (!empty($o['track_date']) && $o['track_date'] != '0000-00-00 00:00:00'): ?>
									<?= date('d M Y, h:i A', strtotime($o['track_date'])) ?>
								<?php else: ?>
									<span class="text-muted">-</span>
								<?php endif; ?>
							</td>
							<?php endif; ?>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
				<?php else: ?>
			<p class="text-muted mb-0">No orders out for delivery.</p>
				<?php endif; ?>
			</div>
			<div class="tab-pane fade <?= $count_ofd == 0 && $count_del > 0 ? 'show active' : '' ?>" id="pane-delivered" role="tabpanel">
				<?php if (!empty($delivered)): ?>
			<div class="table-responsive">
				<table class="table table-sm table-hover">
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Customer</th>
							<th>Invoice</th>
							<?php if ($show_shipping_awb): ?><th>Shipping #</th><th>AWB</th><?php endif; ?>
							<th>Marked Out for Delivery</th>
							<th>Delivered On</th>
							<?php if ($show_track_date): ?><th>Courier Assigned</th><?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($delivered as $o): ?>
						<tr>
							<td>
								<a href="<?= $orders_base_url . htmlspecialchars($o['order_unique_id']) ?>" class="text-primary fw-bold" target="_blank"><?= htmlspecialchars($o['order_unique_id']) ?></a>
							</td>
							<td>
								<div><?= htmlspecialchars($o['user_name']) ?></div>
								<?php if (!empty($o['user_phone'])): ?><small class="text-muted"><?= htmlspecialchars($o['user_phone']) ?></small><?php endif; ?>
							</td>
							<td><?= htmlspecialchars($o['invoice_no'] ?: '-') ?></td>
							<?php if ($show_shipping_awb): ?>
							<td><?= !empty($o['ship_order_id']) ? htmlspecialchars($o['ship_order_id']) : '<span class="text-muted">-</span>' ?></td>
							<td><?= !empty($o['awb_no']) ? '<code>' . htmlspecialchars($o['awb_no']) . '</code>' : '<span class="text-muted">-</span>' ?></td>
							<?php endif; ?>
							<td>
								<?php if (!empty($o['shipment_date']) && $o['shipment_date'] != '0000-00-00 00:00:00'): ?>
									<?= date('d M Y, h:i A', strtotime($o['shipment_date'])) ?>
								<?php else: ?>
									<span class="text-muted">-</span>
								<?php endif; ?>
							</td>
							<td>
								<?php if (!empty($o['delivery_date']) && $o['delivery_date'] != '0000-00-00 00:00:00'): ?>
									<?= date('d M Y, h:i A', strtotime($o['delivery_date'])) ?>
								<?php else: ?>
									<span class="text-muted">-</span>
								<?php endif; ?>
							</td>
							<?php if ($show_track_date): ?>
							<td>
								<?php if (!empty($o['track_date']) && $o['track_date'] != '0000-00-00 00:00:00'): ?>
									<?= date('d M Y, h:i A', strtotime($o['track_date'])) ?>
								<?php else: ?>
									<span class="text-muted">-</span>
								<?php endif; ?>
							</td>
							<?php endif; ?>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
				<?php else: ?>
			<p class="text-muted mb-0">No delivered orders.</p>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
