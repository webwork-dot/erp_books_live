<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0">Activity Logs</h6>
		<small class="text-muted">Purchase In trail from Vendor → Company → Warehouse → PO</small>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-hover align-middle mb-0">
				<thead>
					<tr>
						<th>When</th>
						<th>Entity</th>
						<th>Action</th>
						<th>Label</th>
						<th>By</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($items)): ?>
						<?php foreach ($items as $row): ?>
							<tr>
								<td class="text-nowrap"><?php echo !empty($row['created_at']) ? date('d M Y H:i', strtotime($row['created_at'])) : '-'; ?></td>
								<td><span class="badge bg-light text-dark text-capitalize"><?php echo htmlspecialchars(str_replace('_', ' ', (string)$row['entity_type'])); ?></span></td>
								<td><?php echo htmlspecialchars((string)($row['action_label'] ?: $row['action'])); ?></td>
								<td class="fw-semibold"><?php echo htmlspecialchars((string)($row['entity_label'] ?: '-')); ?></td>
								<td><?php echo htmlspecialchars((string)($row['actor_name'] ?: '-')); ?></td>
								<td class="small text-muted" style="max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
									<?php
										$meta = '';
										if (!empty($row['meta_json'])) {
											$decoded = json_decode($row['meta_json'], TRUE);
											$meta = is_array($decoded) ? json_encode($decoded) : (string)$row['meta_json'];
										}
										echo htmlspecialchars($meta ?: '-');
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr><td colspan="6" class="text-center text-muted py-4">No activity yet.</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
