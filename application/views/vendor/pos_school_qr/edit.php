<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h6><a href="<?php echo base_url('pos-school-qr'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit School UPI QR</a></h6>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php echo form_open_multipart('pos-school-qr/edit/' . $qr['id']); ?>
            <div class="row gx-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label">School <span class="text-danger">*</span></label>
                    <select name="school_id" id="school_id" class="form-select" required>
                        <option value="">Select School</option>
                        <?php foreach ($schools as $school): ?>
                            <option value="<?php echo (int)$school['id']; ?>" <?php echo set_select('school_id', (string)$school['id'], ((int)$qr['school_id'] === (int)$school['id'])); ?>><?php echo htmlspecialchars($school['school_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Replace QR Image</label>
                    <input type="file" name="qr_image" class="form-control" accept=".png,.jpg,.jpeg,.webp">
                    <?php if (!empty($qr['qr_image_path'])): ?>
                        <small class="text-muted">Current: <?php echo htmlspecialchars($qr['qr_image_path']); ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">UPI ID <span class="text-danger">*</span></label>
                    <input type="text" name="upi_id" class="form-control" value="<?php echo set_value('upi_id', (string)$qr['upi_id']); ?>" maxlength="120" required>
                    <?php echo form_error('upi_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Note (optional)</label>
                    <input type="text" name="payment_note" class="form-control" value="<?php echo set_value('payment_note', (string)$qr['payment_note']); ?>" maxlength="255">
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" <?php echo set_checkbox('is_active', '1', ((int)$qr['is_active'] === 1)); ?>>
                        <label class="form-check-label">Set as Active QR</label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo base_url('pos-school-qr'); ?>" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
