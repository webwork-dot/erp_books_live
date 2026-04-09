<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('size-charts'); ?>"><i class="isax isax-arrow-left me-1"></i>Edit Size Chart</a></h6>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<form method="post" action="<?php echo base_url('size-charts/edit/' . $size_chart['id']); ?>">
			<input type="hidden"
				name="<?php echo $this->security->get_csrf_token_name(); ?>"
				value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="mb-3">
				<label class="form-label">Chart Name <span class="text-danger">*</span></label>
				<input type="text" name="chart_name" class="form-control" required value="<?php echo set_value('chart_name', $size_chart['name'] ?? ''); ?>">
				<?php echo form_error('chart_name', '<small class="text-danger">', '</small>'); ?>
			</div>
			<div class="mb-3">
				<label class="form-label">Description</label>
				<textarea name="description" class="form-control" rows="3"><?php echo set_value('description', $size_chart['description'] ?? ''); ?></textarea>
			</div>
			<div class="mb-3">
				<label class="form-label">Status</label>
				<select name="status" class="form-select">
					<option value="active" <?php echo set_select('status', 'active', (($size_chart['status'] ?? '') === 'active')); ?>>Active</option>
					<option value="inactive" <?php echo set_select('status', 'inactive', (($size_chart['status'] ?? '') === 'inactive')); ?>>Inactive</option>
				</select>
			</div>

			<div class="mb-3">
				<label class="form-label">Existing Sizes</label>
				<?php if (!empty($sizes)): ?>
					<div class="row g-2">
						<?php foreach ($sizes as $s): ?>
							<?php if (($s['status'] ?? '') !== 'active') { continue; } ?>
							<div class="col-md-3">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="remove_size_ids[]"
										value="<?php echo (int) $s['id']; ?>" id="rm_size_<?php echo (int) $s['id']; ?>">
									<label class="form-check-label" for="rm_size_<?php echo (int) $s['id']; ?>">
										Remove <?php echo htmlspecialchars($s['name'] ?? ''); ?>
									</label>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<small class="text-muted d-block mt-2">Checked sizes will be marked inactive and won’t appear in dropdowns.</small>
				<?php else: ?>
					<p class="text-muted mb-0">No sizes found.</p>
				<?php endif; ?>
			</div>

			<div class="mb-2">
				<label class="form-label">Add More Sizes</label>
				<small class="text-muted d-block mb-2">Enter sizes separated by commas (e.g., S, M, L, XL) or one per line</small>
				<textarea name="sizes" class="form-control" rows="4" placeholder="S, M, L, XL"></textarea>
			</div>

			<div class="mt-3 d-flex gap-2 justify-content-end">
				<a href="<?php echo base_url('size-charts'); ?>" class="btn btn-outline-secondary">Cancel</a>
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</form>
	</div>
</div>
