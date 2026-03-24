<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h6>School UPI QR</h6>
    </div>
    <div>
        <a href="<?php echo base_url('pos-school-qr/add'); ?>" class="btn btn-primary">Add School UPI QR</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <?php echo form_open('pos-school-qr', array('method' => 'get', 'class' => 'row g-3')); ?>
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
            <div class="col-md-3 d-flex align-items-end pos-school-qr-filter-actions">
                <button type="submit" class="btn btn-icon-action btn-filter" title="Filter" aria-label="Filter" data-bs-toggle="tooltip">
                    <i class="isax isax-search-normal"></i>
                </button>
                <a href="<?php echo base_url('pos-school-qr'); ?>" class="btn btn-icon-action btn-reset" title="Reset" aria-label="Reset" data-bs-toggle="tooltip">
                    <i class="isax isax-refresh"></i>
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<style>
.pos-school-qr-compact .table > :not(caption) > * > * {
    padding: 0.45rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.25;
}

.pos-school-qr-compact .table thead th {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    white-space: nowrap;
}

.pos-school-qr-compact .btn.btn-sm {
    padding: 0.2rem 0.45rem;
}

.pos-school-qr-compact .form-check {
    min-height: 1rem;
    margin-bottom: 0;
}

.pos-school-qr-compact .form-switch .form-check-input {
    margin-top: 0.1rem;
}

.pos-school-qr-filter-actions {
    gap: 0.55rem;
}

.pos-school-qr-filter-actions .btn-icon-action {
    width: 46px;
    height: 42px;
    border-radius: 10px;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.pos-school-qr-filter-actions .btn-filter {
    border: 1.5px solid #564f93;
    color: #564f93;
}

.pos-school-qr-filter-actions .btn-reset {
    border: 1.5px solid #36bf74;
    color: #36bf74;
}

.pos-school-qr-filter-actions .btn-icon-action:hover {
    background: #f8f9fb;
}
</style>

<div class="card pos-school-qr-compact">
    <div class="card-body">
        <?php
            $school_name_map = array();
            if (!empty($schools)) {
                foreach ($schools as $school_row) {
                    $school_name_map[(int)$school_row['id']] = $school_row['school_name'];
                }
            }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>School</th>
                        <th>UPI ID</th>
                        <th>Payment Note</th>
                        <th>Status</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($qrs)): ?>
                        <?php $sr_no = (($current_page - 1) * $per_page) + 1; ?>
                        <?php foreach ($qrs as $qr): ?>
                            <tr id="qr-row-<?php echo (int)$qr['id']; ?>">
                                <td><?php echo $sr_no++; ?></td>
                                <td>
                                    <?php
                                        $sid = (int)$qr['school_id'];
                                        echo htmlspecialchars(isset($school_name_map[$sid]) ? $school_name_map[$sid] : ('School #' . $sid));
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars((string)$qr['upi_id']); ?></td>
                                <td><?php echo htmlspecialchars((string)$qr['payment_note']); ?></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input qr-status-toggle border-primary" type="checkbox"
                                            data-qr-id="<?php echo (int)$qr['id']; ?>"
                                            <?php echo ((int)$qr['is_active'] === 1) ? 'checked' : ''; ?>>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('pos-school-qr/edit/' . $qr['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="isax isax-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger qr-delete-btn" data-qr-id="<?php echo (int)$qr['id']; ?>" data-bs-toggle="tooltip" title="Delete">
                                        <i class="isax isax-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted">No records found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tableBody = document.querySelector('.table tbody');

    document.querySelectorAll('.qr-status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var self = this;
            var qrId = self.getAttribute('data-qr-id');
            var isActive = self.checked ? 1 : 0;

            fetch('<?php echo base_url('pos-school-qr/toggle-status'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(qrId) + '&is_active=' + encodeURIComponent(isActive)
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (!data || data.status !== 'success') {
                    self.checked = !self.checked;
                    alert((data && data.message) ? data.message : 'Failed to update status.');
                }
            })
            .catch(function() {
                self.checked = !self.checked;
                alert('An error occurred while updating status.');
            });
        });
    });

    document.querySelectorAll('.qr-delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var qrId = this.getAttribute('data-qr-id');

            if (!confirm('Delete this QR record?')) {
                return;
            }

            fetch('<?php echo base_url('pos-school-qr/delete-qr'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(qrId)
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (!data || data.status !== 'success') {
                    alert((data && data.message) ? data.message : 'Failed to delete QR.');
                    return;
                }

                var row = document.getElementById('qr-row-' + qrId);
                if (row) {
                    row.remove();
                }

                if (tableBody && !tableBody.querySelector('tr')) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No records found</td></tr>';
                }
            })
            .catch(function() {
                alert('An error occurred while deleting the QR record.');
            });
        });
    });
});
</script>
