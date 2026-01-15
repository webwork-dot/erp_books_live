<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1 style="margin: 0;">Manage Clients</h1>
    <a href="<?php echo base_url('erp-admin/clients/add'); ?>" class="btn btn-success">Add New Client</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>SR No.</th>
                    <th>Name</th>
                    <th>Domain</th>
                    <th>Database</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clients)): ?>
                    <?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($clients as $client): ?>
                        <tr>
                            <td><?php echo $sr_no++; ?></td>
                            <td><?php echo htmlspecialchars($client['name']); ?></td>
                            <td><?php echo htmlspecialchars($client['domain']); ?></td>
                            <td><?php echo htmlspecialchars($client['database_name']); ?></td>
                            <td>
                                <span class="badge <?php echo $client['status'] == 'active' ? 'badge-success' : ($client['status'] == 'suspended' ? 'badge-warning' : 'badge-danger'); ?>">
                                    <?php echo ucfirst($client['status']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?php echo base_url('erp-admin/clients/edit/' . $client['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="isax isax-edit"></i>
                                </a>
                                <a href="<?php echo base_url('erp-admin/clients/features/' . $client['id']); ?>" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Features">
                                    <i class="isax isax-shapes5"></i>
                                </a>
                                <a href="<?php echo base_url('erp-admin/clients/delete/' . $client['id']); ?>" onclick="return confirm('Are you sure you want to delete this client?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                    <i class="isax isax-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No clients found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if (!empty($clients)): ?>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Total Clients: <strong><?php echo $total_clients; ?></strong></p>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/clients?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
                                        <a class="page-link" href="<?php echo base_url('erp-admin/clients?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/clients?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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
