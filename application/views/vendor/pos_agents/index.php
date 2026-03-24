<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h6>POS Agents</h6>
    </div>
    <div>
        <a href="<?php echo base_url('pos-agents/add'); ?>" class="btn btn-primary">Add POS Agent</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <?php echo form_open('pos-agents', array('method' => 'get', 'class' => 'row g-3')); ?>
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" placeholder="Search username/email" value="<?php echo htmlspecialchars(isset($filters['search']) ? $filters['search'] : ''); ?>">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="1" <?php echo (isset($filters['status']) && (string)$filters['status'] === '1') ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo (isset($filters['status']) && (string)$filters['status'] === '0') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end pos-agents-filter-actions">
                <button type="submit" class="btn btn-icon-action btn-filter" title="Filter" aria-label="Filter" data-bs-toggle="tooltip">
                    <i class="isax isax-search-normal"></i>
                </button>
                <a href="<?php echo base_url('pos-agents'); ?>" class="btn btn-icon-action btn-reset" title="Reset" aria-label="Reset" data-bs-toggle="tooltip">
                    <i class="isax isax-refresh"></i>
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<style>
.pos-agents-compact .table > :not(caption) > * > * {
    padding: 0.45rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.25;
}

.pos-agents-compact .table thead th {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    white-space: nowrap;
}

.pos-agents-compact .btn.btn-sm {
    padding: 0.2rem 0.45rem;
}

.pos-agents-compact .form-check {
    min-height: 1rem;
    margin-bottom: 0;
}

.pos-agents-compact .form-switch .form-check-input {
    margin-top: 0.1rem;
}

.pos-agents-compact small {
    font-size: 0.75rem;
}

.pos-agents-filter-actions {
    gap: 0.55rem;
}

.pos-agents-filter-actions .btn-icon-action {
    width: 46px;
    height: 42px;
    border-radius: 10px;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.pos-agents-filter-actions .btn-filter {
    border: 1.5px solid #564f93;
    color: #564f93;
}

.pos-agents-filter-actions .btn-reset {
    border: 1.5px solid #36bf74;
    color: #36bf74;
}

.pos-agents-filter-actions .btn-icon-action:hover {
    background: #f8f9fb;
}
</style>

<div class="card pos-agents-compact">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Info</th>
                        <th>Assigned Schools</th>
                        <th>Assigned Category</th>
                        <th>UPI ID</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($agents)): ?>
                        <?php $sr_no = (($current_page - 1) * $per_page) + 1; ?>
                        <?php foreach ($agents as $agent): ?>
                            <tr id="agent-row-<?php echo (int)$agent['id']; ?>">
                                <td><?php echo $sr_no++; ?></td>
                                <td>
                                    <div class="fw-semibold"><?php echo htmlspecialchars($agent['username']); ?></div>
                                    <small class="text-muted"><?php echo !empty($agent['email']) ? htmlspecialchars((string)$agent['email']) : '-'; ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($agent['assigned_schools_preview'])): ?>
                                        <?php echo htmlspecialchars($agent['assigned_schools_preview']); ?>
                                        <?php if (!empty($agent['assigned_schools_more_count'])): ?>
                                            <span class="text-muted"> +<?php echo (int)$agent['assigned_schools_more_count']; ?> more</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($agent['assigned_categories_preview'])): ?>
                                        <?php echo htmlspecialchars($agent['assigned_categories_preview']); ?>
                                        <?php if (!empty($agent['assigned_categories_more_count'])): ?>
                                            <span class="text-muted"> +<?php echo (int)$agent['assigned_categories_more_count']; ?> more</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($agent['assigned_upi_preview'])): ?>
                                        <?php echo htmlspecialchars($agent['assigned_upi_preview']); ?>
                                        <?php if (!empty($agent['assigned_upi_more_count'])): ?>
                                            <span class="text-muted"> +<?php echo (int)$agent['assigned_upi_more_count']; ?> more</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input agent-status-toggle border-primary" type="checkbox"
                                            data-agent-id="<?php echo (int)$agent['id']; ?>"
                                            <?php echo ((int)$agent['status'] === 1) ? 'checked' : ''; ?>>
                                    </div>
                                </td>
                                <td><?php echo !empty($agent['created_at']) ? date('d-m-Y H:i', strtotime($agent['created_at'])) : '-'; ?></td>
                                <td>
                                    <a href="<?php echo base_url('pos-agents/edit/' . $agent['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="isax isax-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger agent-delete-btn" data-agent-id="<?php echo (int)$agent['id']; ?>" data-bs-toggle="tooltip" title="Delete">
                                        <i class="isax isax-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No POS agents found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($agents) && $total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-end">
                    <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo base_url('pos-agents?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo base_url('pos-agents?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo base_url('pos-agents?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tableBody = document.querySelector('.table tbody');

    document.querySelectorAll('.agent-status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var self = this;
            var agentId = self.getAttribute('data-agent-id');
            var status = self.checked ? 1 : 0;

            fetch('<?php echo base_url('pos-agents/toggle-status'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'agent_user_id=' + encodeURIComponent(agentId) + '&status=' + encodeURIComponent(status)
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

    document.querySelectorAll('.agent-delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var self = this;
            var agentId = self.getAttribute('data-agent-id');

            if (!confirm('Are you sure you want to delete this POS agent?')) {
                return;
            }

            fetch('<?php echo base_url('pos-agents/delete-agent'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'agent_user_id=' + encodeURIComponent(agentId)
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (!data || data.status !== 'success') {
                    alert((data && data.message) ? data.message : 'Failed to delete POS agent.');
                    return;
                }

                var row = document.getElementById('agent-row-' + agentId);
                if (row) {
                    row.remove();
                }

                if (tableBody && !tableBody.querySelector('tr')) {
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No POS agents found</td></tr>';
                }
            })
            .catch(function() {
                alert('An error occurred while deleting the POS agent.');
            });
        });
    });
});
</script>
