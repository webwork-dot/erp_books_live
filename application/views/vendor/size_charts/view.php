<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('size-charts'); ?>"><i class="isax isax-arrow-left me-1"></i>View Size Chart</a></h6>
	</div>
	<div class="d-flex gap-2">
		<a href="<?php echo base_url('size-charts/edit/' . $size_chart['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
	</div>
</div>

<div class="card mb-3">
	<div class="card-body">
		<div class="row g-2">
			<div class="col-md-4"><strong>Name:</strong> <?php echo htmlspecialchars($size_chart['name'] ?? ''); ?></div>
			<div class="col-md-4"><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($size_chart['status'] ?? '')); ?></div>
			<div class="col-md-12"><strong>Description:</strong> <?php echo htmlspecialchars($size_chart['description'] ?? ''); ?></div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<h6 class="mb-2">Sizes</h6>
		<?php if (!empty($sizes)): ?>
			<ul class="mb-0">
				<?php foreach ($sizes as $s): ?>
					<?php if (($s['status'] ?? '') !== 'active') { continue; } ?>
					<li><?php echo htmlspecialchars($s['name'] ?? ''); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<p class="text-muted mb-0">No sizes added.</p>
		<?php endif; ?>
	</div>
</div>
