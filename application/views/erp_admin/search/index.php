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
	
	<!-- Vendors Results -->
	<?php if (!empty($results['vendors'])): ?>
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Vendors (<?php echo count($results['vendors']); ?>)</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Domain</th>
								<th>Username</th>
								<th>Status</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($results['vendors'] as $vendor): ?>
								<tr>
									<td><?php echo htmlspecialchars($vendor['name']); ?></td>
									<td><?php echo htmlspecialchars($vendor['domain']); ?></td>
									<td><?php echo htmlspecialchars($vendor['username']); ?></td>
									<td>
										<span class="badge badge-<?php echo $vendor['status'] == 'active' ? 'success' : ($vendor['status'] == 'suspended' ? 'warning' : 'danger'); ?>">
											<?php echo ucfirst($vendor['status']); ?>
										</span>
									</td>
									<td class="text-end">
										<a href="<?php echo base_url('erp-admin/vendors/edit/' . $vendor['id']); ?>" class="btn btn-sm btn-outline-primary">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<!-- Users Results -->
	<?php if (!empty($results['users'])): ?>
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">Users (<?php echo count($results['users']); ?>)</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Username</th>
								<th>Email</th>
								<th>User Type</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($results['users'] as $user): ?>
								<tr>
									<td><?php echo htmlspecialchars($user['username']); ?></td>
									<td><?php echo htmlspecialchars($user['email']); ?></td>
									<td>
										<span class="badge badge-info"><?php echo ucfirst($user['user_type']); ?></span>
									</td>
									<td class="text-end">
										<a href="<?php echo base_url('erp-admin/users'); ?>" class="btn btn-sm btn-outline-primary">View</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<?php if (empty($results['vendors']) && empty($results['users'])): ?>
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

