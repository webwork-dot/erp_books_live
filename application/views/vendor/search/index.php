<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Search Results</h6>
	</div>
</div>
<!-- End Header -->

<?php if (!empty($query)): ?>
	<div class="card mb-3">
		<div class="card-body">
			<p class="mb-0">Search results for: <strong><?php echo htmlspecialchars($query); ?></strong></p>
		</div>
	</div>
	
	<!-- Products Results -->
	<?php if (!empty($results['products'])): ?>
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Products (<?php echo count($results['products']); ?>)</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Product Name</th>
								<th>SKU</th>
								<th>ISBN</th>
								<th>Status</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($results['products'] as $product): ?>
								<tr>
									<td><?php echo htmlspecialchars($product['product_name']); ?></td>
									<td><?php echo htmlspecialchars($product['sku']); ?></td>
									<td><?php echo htmlspecialchars($product['isbn']); ?></td>
									<td>
										<span class="badge badge-<?php echo $product['status'] == 'active' ? 'success' : 'danger'; ?>">
											<?php echo ucfirst($product['status']); ?>
										</span>
									</td>
									<td class="text-end">
										<a href="<?php echo base_url('products/individual-products'); ?>" class="btn btn-sm btn-outline-primary">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<!-- Orders Results -->
	<?php if (!empty($results['orders'])): ?>
	<style>
	.search-orders .label { display: inline-block; padding: 0.35em 0.65em; font-size: 12px; font-weight: 600; line-height: 1; border-radius: 4px; }
	.search-orders .label-default { background-color: #6c757d !important; color: white; }
	.search-orders .label-warning { background-color: rgb(255 133 0) !important; color: white; }
	.search-orders .label-info { background-color: #17a2b8 !important; color: white; }
	.search-orders .label-success { background-color: #28a745 !important; color: white; }
	.search-orders .label-danger { background-color: #dc3545 !important; color: white; }
	.search-orders .badge-deliver-school { background-color: #ef1e36; color: #fff !important; padding: 0.35em 0.65em; border-radius: 4px; }
	.search-orders .badge-address { background-color: rgba(239, 30, 54, 0.31) !important; border: 1px solid #ef1e36; color: #ef1e36 !important; padding: 0.35em 0.65em; border-radius: 4px; }
	.search-orders .badge-payment-school { background-color: #ef1e36; color: #fff !important; padding: 0.35em 0.65em; border-radius: 4px; }
	.search-orders .badge-payment-cod { background-color: rgba(0, 123, 255, 0.39) !important; border: 1px solid #007bff; color: #007bff !important; padding: 0.35em 0.65em; border-radius: 4px; }
	.search-orders .badge-payment-other { background-color: rgba(108, 117, 125, 0.15) !important; border: 1px solid #6c757d; color: #6c757d !important; padding: 0.35em 0.65em; border-radius: 4px; }
	</style>
		<div class="card mb-3 search-orders">
			<div class="card-header">
				<h6 class="mb-0">Orders (<?php echo count($results['orders']); ?>)</h6>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover mb-0">
						<thead>
							<tr>
								<th>Order ID</th>
								<th>Status</th>
								<th>User Details</th>
								<th>Product Name</th>
								<th>Address</th>
								<th>School</th>
								<th>Grade</th>
								<th>Delivery</th>
								<th>Date</th>
								<th>Payment Method</th>
								<th>Invoice Number</th>
								<th>Courier</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($results['orders'] as $order): 
								$status_label = '';
								$status_class = '';
								switch ($order['status']) {
									case '1': $status_label = 'New Order'; $status_class = 'label-default'; break;
									case '2': $status_label = 'Processing'; $status_class = 'label-warning'; break;
									case '3': $status_label = 'Out for Delivery'; $status_class = 'label-info'; break;
									case '4': $status_label = 'Delivered'; $status_class = 'label-success'; break;
									case '7': $status_label = 'Return'; $status_class = 'label-danger'; break;
									default: $status_label = 'Unknown'; $status_class = 'label-default'; break;
								}
								$is_deliver_at_school = isset($order['is_deliver_at_school']) && $order['is_deliver_at_school'];
								$payment_method_display = $order['payment_method'];
								if ($payment_method_display == 'payment_at_school' || $payment_method_display == 'payment_at_scho') {
									$payment_method_display = 'Payment at School';
									$payment_badge = 'badge-payment-school';
								} elseif ($payment_method_display == 'cod') {
									$payment_method_display = 'Cash On Delivery';
									$payment_badge = 'badge-payment-cod';
								} else {
									$payment_method_display = ucfirst(str_replace('_', ' ', $payment_method_display));
									$payment_badge = 'badge-payment-other';
								}
								$courier_display = $order['courier_name'];
								if (!empty($order['ship_order_id']) || !empty($order['awb_no'])) {
									$courier_parts = array();
									if (!empty($order['courier_name']) && $order['courier_name'] != '-') $courier_parts[] = $order['courier_name'];
									if (!empty($order['ship_order_id'])) $courier_parts[] = 'Ship #' . htmlspecialchars($order['ship_order_id']);
									if (!empty($order['awb_no'])) $courier_parts[] = 'AWB ' . htmlspecialchars($order['awb_no']);
									$courier_display = !empty($courier_parts) ? implode(' · ', $courier_parts) : '-';
								}
							?>
								<tr>
									<td><a href="<?php echo base_url('orders/view/' . $order['order_unique_id']); ?>" class="text-primary fw-bold" style="text-decoration: underline;"><?php echo htmlspecialchars($order['order_unique_id']); ?></a></td>
									<td><span class="label <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
									<td>
										<div><?php echo htmlspecialchars($order['user_name']); ?></div>
										<small class="text-muted"><?php echo htmlspecialchars($order['user_phone']); ?></small>
									</td>
									<td><?php echo isset($order['product_name']) ? htmlspecialchars($order['product_name']) : '-'; ?></td>
									<td><?php echo isset($order['address']) ? htmlspecialchars($order['address']) : '-'; ?></td>
									<td><?php echo isset($order['school_name']) ? htmlspecialchars($order['school_name']) : '-'; ?></td>
									<td><?php echo isset($order['grade_name']) ? htmlspecialchars($order['grade_name']) : '-'; ?></td>
									<td><?php 
										if ($is_deliver_at_school) {
											echo '<span class="badge badge-pill badge-deliver-school">Deliver at School</span>';
										} else {
											echo '<span class="badge badge-pill badge-address">Deliver at Address</span>';
										}
									?></td>
									<td><?php echo $order['date']; ?></td>
									<td><span class="badge badge-pill <?php echo $payment_badge; ?>"><?php echo htmlspecialchars($payment_method_display); ?></span></td>
									<td><?php echo $order['invoice_no']; ?></td>
									<td><small><?php echo $courier_display; ?></small></td>
									<td class="text-end" nowrap="">
										<a href="<?php echo base_url('orders/view/' . $order['order_unique_id']); ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>
										<button type="button" class="btn btn-outline-primary btn-sm btn-timeline ms-1" data-order-id="<?php echo htmlspecialchars($order['order_unique_id']); ?>" data-toggle="tooltip" title="Order Timeline"><i class="fa fa-history"></i></button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- Order Timeline Modal -->
		<div class="modal fade" id="orderTimelineListModal" tabindex="-1" aria-labelledby="orderTimelineListModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="orderTimelineListModalLabel">Order Timeline</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body" id="orderTimelineListModalBody">
						<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>
					</div>
				</div>
			</div>
		</div>

		<script>
		$(document).ready(function() {
			$('.btn-timeline').on('click', function() {
				var orderId = $(this).data('order-id');
				var $modal = $('#orderTimelineListModal');
				var $body = $('#orderTimelineListModalBody');
				$body.html('<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
				$modal.find('.modal-title').text('Order Timeline - ' + orderId);
				if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
					var modal = new bootstrap.Modal($modal[0]);
					modal.show();
				} else {
					$modal.modal('show');
				}
				$.get('<?php echo base_url("orders/get_order_timeline/"); ?>' + encodeURIComponent(orderId))
					.done(function(html) { $body.html(html); })
					.fail(function() { $body.html('<p class="text-danger">Failed to load timeline.</p>'); });
			});
		});
		</script>
	<?php endif; ?>
	
	<!-- Schools Results -->
	<?php if (!empty($results['schools'])): ?>
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Schools (<?php echo count($results['schools']); ?>)</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>School Name</th>
								<th>Board</th>
								<th>Email</th>
								<th>Status</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($results['schools'] as $school): ?>
								<tr>
									<td><?php echo htmlspecialchars($school['school_name']); ?></td>
									<td><?php echo htmlspecialchars($school['board_name']); ?></td>
									<td><?php echo htmlspecialchars($school['email']); ?></td>
									<td>
										<span class="badge badge-<?php echo $school['status'] == 'active' ? 'success' : 'danger'; ?>">
											<?php echo ucfirst($school['status']); ?>
										</span>
									</td>
									<td class="text-end">
										<a href="<?php echo base_url('schools'); ?>" class="btn btn-sm btn-outline-primary">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<!-- Customers Results -->
	<?php if (!empty($results['customers'])): ?>
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Customers (<?php echo count($results['customers']); ?>)</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Status</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($results['customers'] as $customer): ?>
								<tr>
									<td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
									<td><?php echo htmlspecialchars($customer['email']); ?></td>
									<td><?php echo htmlspecialchars($customer['phone']); ?></td>
									<td>
										<span class="badge badge-<?php echo $customer['status'] == 'active' ? 'success' : 'danger'; ?>">
											<?php echo ucfirst($customer['status']); ?>
										</span>
									</td>
									<td class="text-end">
										<a href="<?php echo base_url('customers/list'); ?>" class="btn btn-sm btn-outline-primary">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<?php if (empty($results['products']) && empty($results['orders']) && empty($results['schools']) && empty($results['customers'])): ?>
		<div class="card">
			<div class="card-body text-center py-5">
				<p class="text-muted mb-0">No results found for "<?php echo htmlspecialchars($query); ?>"</p>
			</div>
		</div>
	<?php endif; ?>
<?php else: ?>
	<div class="card">
		<div class="card-body text-center py-5">
			<p class="text-muted mb-0">Please enter a search query</p>
		</div>
	</div>
<?php endif; ?>

