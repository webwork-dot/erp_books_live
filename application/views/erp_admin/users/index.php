<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1 style="margin: 0;">Manage Users</h1>
    <a href="<?php echo base_url('erp-admin/users/add'); ?>" class="btn btn-primary">Add New User</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>SR No.</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $sr_no++; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo isset($user['role_name']) ? htmlspecialchars($user['role_name']) : 'N/A'; ?></td>
                            <td>
                                <span class="badge <?php echo $user['status'] == 1 ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $user['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?php echo base_url('erp-admin/users/edit/' . $user['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="isax isax-edit"></i>
                                </a>
                                <a href="<?php echo base_url('erp-admin/users/delete/' . $user['id']); ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                    <i class="isax isax-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if (!empty($users)): ?>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Total Users: <strong><?php echo $total_users; ?></strong></p>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/users?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?php echo $i; ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo base_url('erp-admin/users?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/users?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

