<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h6>School UPI QR</h6>
    </div>
    <div>
        <a href="<?php echo base_url('erp-admin/pos-school-qr/add'); ?>" class="btn btn-primary">Add School UPI QR</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <?php echo form_open('erp-admin/pos-school-qr', array('method' => 'get', 'class' => 'row g-3')); ?>
            <div class="col-md-3">
                <select name="vendor_id" id="vendor_id" class="form-select">
                    <option value="">All Vendors</option>
                    <?php foreach ($vendors as $vendor): ?>
                        <option value="<?php echo (int)$vendor['id']; ?>" <?php echo ((int)$filters['vendor_id'] === (int)$vendor['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($vendor['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="school_id" id="school_id" class="form-select">
                    <option value="">All Schools</option>
                    <?php foreach ($schools as $school): ?>
                        <option value="<?php echo (int)$school['id']; ?>" <?php echo ((int)$filters['school_id'] === (int)$school['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($school['school_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-select">
                    <option value="">Any Status</option>
                    <option value="1" <?php echo ((string)$filters['is_active'] === '1') ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ((string)$filters['is_active'] === '0') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            <div class="col-md-2"><a href="<?php echo base_url('erp-admin/pos-school-qr'); ?>" class="btn btn-outline-secondary w-100">Reset</a></div>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vendor ID</th>
                        <th>School ID</th>
                        <th>UPI ID</th>
                        <th>Payment Note</th>
                        <th>Status</th>
                        <th width="260">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($qrs)): ?>
                        <?php $sr_no = (($current_page - 1) * $per_page) + 1; ?>
                        <?php foreach ($qrs as $qr): ?>
                            <tr>
                                <td><?php echo $sr_no++; ?></td>
                                <td><?php echo (int)$qr['vendor_id']; ?></td>
                                <td><?php echo (int)$qr['school_id']; ?></td>
                                <td><?php echo htmlspecialchars((string)$qr['upi_id']); ?></td>
                                <td><?php echo htmlspecialchars((string)$qr['payment_note']); ?></td>
                                <td>
                                    <?php if ((int)$qr['is_active'] === 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('erp-admin/pos-school-qr/edit/' . $qr['id']); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <?php if ((int)$qr['is_active'] === 1): ?>
                                        <a href="<?php echo base_url('erp-admin/pos-school-qr/deactivate/' . $qr['id']); ?>" class="btn btn-sm btn-outline-warning">Deactivate</a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('erp-admin/pos-school-qr/activate/' . $qr['id']); ?>" class="btn btn-sm btn-outline-success">Activate</a>
                                    <?php endif; ?>
                                    <a href="<?php echo base_url('erp-admin/pos-school-qr/delete/' . $qr['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this QR record?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted">No records found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function() {
    var vendorSelect = document.getElementById('vendor_id');
    var schoolSelect = document.getElementById('school_id');

    vendorSelect.addEventListener('change', function() {
        var vendorId = this.value;
        schoolSelect.innerHTML = '<option value="">All Schools</option>';

        if (!vendorId) {
            return;
        }

        fetch('<?php echo base_url('erp-admin/pos-agents/schools-by-vendor/'); ?>' + vendorId)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.status !== 'success') {
                    return;
                }

                (data.schools || []).forEach(function(school) {
                    var option = document.createElement('option');
                    option.value = school.id;
                    option.textContent = school.school_name;
                    schoolSelect.appendChild(option);
                });
            });
    });
})();
</script>
