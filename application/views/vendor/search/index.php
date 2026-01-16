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
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Orders (<?php echo count($results['orders']); ?>)</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Order Number</th>
								<th>Customer</th>
								<th>Amount</th>
								<th>Status</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($results['orders'] as $order): ?>
								<tr>
									<td><?php echo htmlspecialchars($order['order_number']); ?></td>
									<td><?php echo htmlspecialchars($order['customer_name']); ?></td>
									<td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
									<td>
										<span class="badge badge-<?php echo $order['status'] == 'completed' ? 'success' : 'warning'; ?>">
											<?php echo ucfirst($order['status']); ?>
										</span>
									</td>
									<td class="text-end">
										<a href="<?php echo base_url('orders'); ?>" class="btn btn-sm btn-outline-primary">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
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

