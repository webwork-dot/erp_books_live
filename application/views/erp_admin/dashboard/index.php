				<!-- Start Breadcrumb -->
				<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
					<div>
						<h6>Dashboard</h6>
					</div>
				</div>
				<!-- End Breadcrumb -->

			<!-- Welcome Section -->
			<?php
			// Set timezone to IST
			date_default_timezone_set('Asia/Kolkata');
			$ist_date = date('l, d M Y');
			$ist_time = date('h:i A');
			$user_name = isset($current_user['username']) ? htmlspecialchars($current_user['username']) : 'Admin';
			?>
			<div class="card mb-3">
				<div class="card-body py-2">
					<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
						<div class="d-flex align-items-center flex-wrap gap-3">
							<span class="text-muted">Welcome,</span>
							<span class="fw-semibold"><?php echo $user_name; ?></span>
						</div>
						<div class="d-flex align-items-center flex-wrap gap-3">
							<span class="d-flex align-items-center text-muted fs-13">
								<i class="isax isax-calendar5 me-1"></i><?php echo $ist_date; ?>
							</span>
							<span class="d-flex align-items-center text-muted fs-13">
								<i class="isax isax-clock5 me-1"></i><?php echo $ist_time; ?> IST
							</span>
						</div>
					</div>
				</div>
			</div>

				<!-- Statistics Cards -->
				<div class="row">
					<div class="col-md-4 d-flex">
						<div class="card flex-fill">
							<div class="card-body">
								<div class="mb-3">
									<h6 class="d-flex align-items-center mb-1"><i class="isax isax-category5 text-default me-2"></i>Overview</h6>
								</div>
								<div class="row g-4">
									<div class="col-xl-6">
										<div class="d-flex align-items-center">
											<span class="avatar avatar-44 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 me-2">
												<i class="isax isax-shapes5 fs-20"></i>
											</span>
											<div>
												<p class="mb-1 text-truncate">Total Vendors</p>
												<h6 class="fs-16 fw-semibold mb-0 text-truncate"><?php echo isset($total_vendors) ? $total_vendors : 0; ?></h6>
											</div>
										</div>
									</div>
									<div class="col-xl-6">
										<div class="d-flex align-items-center me-2">
											<span class="avatar avatar-44 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 me-2">
												<i class="isax isax-tick-circle fs-20"></i>
											</span>
											<div>
												<p class="mb-1 text-truncate">Active Vendors</p>
												<h6 class="fs-16 fw-semibold mb-0 text-truncate"><?php echo isset($active_vendors) ? $active_vendors : 0; ?></h6>
											</div>
										</div>
									</div>
									<div class="col-xl-6">
										<div class="d-flex align-items-center">
											<span class="avatar avatar-44 avatar-rounded bg-warning-subtle text-warning-emphasis flex-shrink-0 me-2">
												<i class="isax isax-danger fs-20"></i>
											</span>
											<div>
												<p class="mb-1 text-truncate">Suspended</p>
												<h6 class="fs-16 fw-semibold mb-0 text-truncate"><?php echo isset($suspended_vendors) ? $suspended_vendors : 0; ?></h6>
											</div>
										</div>
									</div>
									<div class="col-xl-6">
										<div class="d-flex align-items-center me-2">
											<span class="avatar avatar-44 avatar-rounded bg-info-subtle text-info-emphasis flex-shrink-0 me-2">
												<i class="isax isax-profile-2user5 fs-20"></i>
											</span>
											<div>
												<p class="mb-1 text-truncate">Users</p>
												<h6 class="fs-16 fw-semibold mb-0 text-truncate">-</h6>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8 d-flex flex-column">
						<div class="card d-flex">
							<div class="card-body flex-fill">
								<div class="d-flex align-items-center justify-content-between mb-3">
									<h6 class="mb-0">Recent Vendors</h6>
									<a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-sm btn-primary">View All</a>
								</div>
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Name</th>
												<th>Domain</th>
												<th>Status</th>
												<th>Created</th>
												<th class="text-end">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($recent_vendors)): ?>
												<?php foreach ($recent_vendors as $vendor): ?>
													<tr>
														<td><?php echo htmlspecialchars($vendor['name']); ?></td>
														<td><?php echo htmlspecialchars($vendor['domain']); ?></td>
														<td>
															<span class="badge badge-<?php echo $vendor['status'] == 'active' ? 'success' : ($vendor['status'] == 'suspended' ? 'warning' : 'danger'); ?>">
																<?php echo ucfirst($vendor['status']); ?>
															</span>
														</td>
														<td><?php echo date('Y-m-d', strtotime($vendor['created_at'])); ?></td>
														<td class="text-end">
															<a href="<?php echo base_url('erp-admin/vendors/edit/' . $vendor['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View">
																<i class="isax isax-eye"></i>
															</a>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="5" class="text-center text-muted">No vendors found</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
