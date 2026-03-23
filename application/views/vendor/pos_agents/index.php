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
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search username/email" value="<?php echo htmlspecialchars(isset($filters['search']) ? $filters['search'] : ''); ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="1" <?php echo (isset($filters['status']) && (string)$filters['status'] === '1') ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo (isset($filters['status']) && (string)$filters['status'] === '0') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo base_url('pos-agents'); ?>" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
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
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($agents)): ?>
                        <?php $sr_no = (($current_page - 1) * $per_page) + 1; ?>
                        <?php foreach ($agents as $agent): ?>
                            <tr>
                                <td><?php echo $sr_no++; ?></td>
                                <td><?php echo htmlspecialchars($agent['username']); ?></td>
                                <td><?php echo htmlspecialchars((string)$agent['email']); ?></td>
                                <td>
                                    <?php if ((int)$agent['status'] === 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo !empty($agent['created_at']) ? date('d-m-Y H:i', strtotime($agent['created_at'])) : '-'; ?></td>
                                <td>
                                    <a href="<?php echo base_url('pos-agents/edit/' . $agent['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="isax isax-edit"></i>
                                    </a>
                                    <a href="<?php echo base_url('pos-agents/reset-credentials/' . $agent['id']); ?>" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Reset Credentials" onclick="return confirm('Reset credentials for this agent?');">
                                        <i class="isax isax-refresh-2"></i>
                                    </a>
                                    <a href="<?php echo base_url('pos-agents/delete/' . $agent['id']); ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Deactivate" onclick="return confirm('Deactivate this agent?');">
                                        <i class="isax isax-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No POS agents found</td>
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
