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
                        <th width="240">Actions</th>
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
                                    <a href="<?php echo base_url('pos-agents/stock/' . (int)$agent['id']); ?>" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Assign Stock">
                                        <i class="isax isax-box"></i>
                                    </a>
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

<div class="modal fade" id="agentStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">POS Agent Stock - <span id="agentStockTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-2">
                    <div class="col-md-8 position-relative">
                        <input type="text" id="agentStockSearch" class="form-control form-control-sm" placeholder="Search product/size/school/board" autocomplete="off">
                        <div id="agentStockSuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index:1060; display:none; max-height:220px; overflow:auto;"></div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <small class="text-muted">Select product and assign/return qty.</small>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr><th>Product</th><th>Size</th><th>Main Qty</th><th>Agent Qty</th><th>Action</th><th>Qty</th><th>Remark</th><th></th></tr>
                        </thead>
                        <tbody id="agentStockSelectedBody">
                            <tr><td colspan="8" class="text-center text-muted">No selected products</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end mb-3">
                    <button type="button" class="btn btn-sm btn-primary" id="agentStockApplyBtn">Apply Stock</button>
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <h6 class="mb-2">Agent Current Stock</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead><tr><th>Product Name</th><th>Uniform Type</th><th>Size</th><th>Gender</th><th>School</th><th>Board</th><th>Grade</th><th>Total Qty</th><th>Sold Qty</th><th>Remain Qty</th><th>Last Assigned Date</th><th>Action</th></tr></thead>
                                <tbody id="agentHoldingsBody"><tr><td colspan="12" class="text-center text-muted">No stock</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="agentItemHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Item History - <span id="agentItemHistoryTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Direction</th><th>Source</th><th>Qty</th><th>Before</th><th>After</th><th>Date</th><th>Remarks</th></tr></thead>
                        <tbody id="agentItemHistoryBody"><tr><td colspan="7" class="text-center text-muted">Loading...</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tableBody = document.querySelector('.table tbody');
    var agentStockModalEl = document.getElementById('agentStockModal');
    var agentStockModal = agentStockModalEl ? new bootstrap.Modal(agentStockModalEl) : null;
    var agentItemHistoryModalEl = document.getElementById('agentItemHistoryModal');
    var agentItemHistoryModal = agentItemHistoryModalEl ? new bootstrap.Modal(agentItemHistoryModalEl) : null;
    var currentAgentId = 0;
    var selectedStockItems = [];

    function stockKey(item) {
        return (item.item_type || '') + '|' + (item.item_ref_id || '') + '|' + (item.variation_key || 'default');
    }

    function renderSelectedStockRows() {
        var body = document.getElementById('agentStockSelectedBody');
        if (!selectedStockItems.length) {
            body.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No selected products</td></tr>';
            return;
        }
        var html = '';
        selectedStockItems.forEach(function(item, idx) {
            html += '<tr>' +
                '<td>' + (item.product_name || '-') + '</td>' +
                '<td>' + (item.variation_key || '-') + '</td>' +
                '<td>' + Math.round(Number(item.main_qty || 0)) + '</td>' +
                '<td>' + Math.round(Number(item.agent_qty || 0)) + '</td>' +
                '<td><select class="form-select form-select-sm st-action" data-idx="' + idx + '"><option value="assign"' + (item.action === 'assign' ? ' selected' : '') + '>Assign</option><option value="return"' + (item.action === 'return' ? ' selected' : '') + '>Return</option></select></td>' +
                '<td><input type="number" min="1" step="1" class="form-control form-control-sm st-qty" data-idx="' + idx + '" value="' + (item.qty || 1) + '" style="width:70px;"></td>' +
                '<td><input type="text" class="form-control form-control-sm st-remark" data-idx="' + idx + '" value="' + (item.remark || '') + '"></td>' +
                '<td><button type="button" class="btn btn-sm btn-outline-danger st-remove" data-idx="' + idx + '">X</button></td>' +
                '</tr>';
        });
        body.innerHTML = html;
        document.querySelectorAll('.st-action').forEach(function(el) { el.addEventListener('change', function() { selectedStockItems[Number(el.getAttribute('data-idx'))].action = el.value; }); });
        document.querySelectorAll('.st-qty').forEach(function(el) { el.addEventListener('input', function() { selectedStockItems[Number(el.getAttribute('data-idx'))].qty = Number(el.value || 0); }); });
        document.querySelectorAll('.st-remark').forEach(function(el) { el.addEventListener('input', function() { selectedStockItems[Number(el.getAttribute('data-idx'))].remark = el.value || ''; }); });
        document.querySelectorAll('.st-remove').forEach(function(el) { el.addEventListener('click', function() { selectedStockItems.splice(Number(el.getAttribute('data-idx')), 1); renderSelectedStockRows(); }); });
    }

    function loadAgentSummary() {
        if (!currentAgentId) return;
        fetch('<?php echo base_url('pos-agents/stock/summary'); ?>?agent_user_id=' + encodeURIComponent(currentAgentId))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var hBody = document.getElementById('agentHoldingsBody');
                if (!data || data.status !== 'success') {
                    hBody.innerHTML = '<tr><td colspan="12" class="text-center text-danger">Failed</td></tr>';
                    return;
                }
                var hHtml = '';
                (data.holdings || []).forEach(function(r) {
                    hHtml += '<tr>' +
                        '<td>' + (r.product_name || '-') + '</td>' +
                        '<td>' + (r.uniform_type_name || '-') + '</td>' +
                        '<td>' + (r.variation_key || '-') + '</td>' +
                        '<td>' + (r.gender || '-') + '</td>' +
                        '<td>' + (r.school_name || '-') + '</td>' +
                        '<td>' + (r.board_name || '-') + '</td>' +
                        '<td>' + (r.grade_name || '-') + '</td>' +
                        '<td>' + Math.round(Number(r.total_qty || 0)) + '</td>' +
                        '<td>' + Math.round(Number(r.sold_qty || 0)) + '</td>' +
                        '<td>' + Math.round(Number(r.remain_qty || 0)) + '</td>' +
                        '<td>' + (r.last_assigned_date || '-') + '</td>' +
                        '<td><div class="d-flex gap-1"><button type="button" class="btn btn-sm btn-outline-primary quick-stock-btn" ' +
                        'data-item-type="' + (r.item_type || '') + '" ' +
                        'data-item-ref-id="' + (r.item_ref_id || '') + '" ' +
                        'data-variation-key="' + (r.variation_key || 'default') + '" ' +
                        'data-product-name="' + (r.product_name || '') + '" ' +
                        'data-main-qty="0" ' +
                        'data-agent-qty="' + Math.round(Number(r.remain_qty || 0)) + '">Stock</button>' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary quick-history-btn" ' +
                        'data-item-type="' + (r.item_type || '') + '" ' +
                        'data-item-ref-id="' + (r.item_ref_id || '') + '" ' +
                        'data-variation-key="' + (r.variation_key || 'default') + '" ' +
                        'data-product-name="' + (r.product_name || '') + '">History</button></div></td>' +
                        '</tr>';
                });
                hBody.innerHTML = hHtml || '<tr><td colspan="12" class="text-center text-muted">No stock</td></tr>';

                document.querySelectorAll('.quick-stock-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var item = {
                            item_type: btn.getAttribute('data-item-type') || '',
                            item_ref_id: btn.getAttribute('data-item-ref-id') || '',
                            variation_key: btn.getAttribute('data-variation-key') || 'default',
                            product_name: btn.getAttribute('data-product-name') || '',
                            main_qty: Number(btn.getAttribute('data-main-qty') || 0),
                            agent_qty: Number(btn.getAttribute('data-agent-qty') || 0),
                            action: 'return',
                            qty: 1,
                            remark: ''
                        };
                        if (!selectedStockItems.some(function(s) { return stockKey(s) === stockKey(item); })) {
                            selectedStockItems.push(item);
                        }
                        renderSelectedStockRows();
                    });
                });

                document.querySelectorAll('.quick-history-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        if (!agentItemHistoryModal) return;
                        var itemType = btn.getAttribute('data-item-type') || '';
                        var itemRefId = btn.getAttribute('data-item-ref-id') || '';
                        var variationKey = btn.getAttribute('data-variation-key') || 'default';
                        var productName = btn.getAttribute('data-product-name') || '';
                        document.getElementById('agentItemHistoryTitle').textContent = productName + ' (' + variationKey + ')';
                        document.getElementById('agentItemHistoryBody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>';
                        fetch('<?php echo base_url('pos-agents/stock/history'); ?>?agent_user_id=' + encodeURIComponent(currentAgentId) + '&item_type=' + encodeURIComponent(itemType) + '&item_ref_id=' + encodeURIComponent(itemRefId) + '&variation_key=' + encodeURIComponent(variationKey))
                            .then(function(r){ return r.json(); })
                            .then(function(data){
                                if (!data || data.status !== 'success') {
                                    document.getElementById('agentItemHistoryBody').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load history</td></tr>';
                                    return;
                                }
                                var html = '';
                                (data.history || []).forEach(function(h){
                                    html += '<tr><td>' + (h.direction || '-') + '</td><td>' + (h.source || '-') + '</td><td>' + Math.round(Number(h.qty || 0)) + '</td><td>' + Math.round(Number(h.before || 0)) + '</td><td>' + Math.round(Number(h.after || 0)) + '</td><td>' + (h.date || '-') + '</td><td>' + (h.remarks || '-') + '</td></tr>';
                                });
                                document.getElementById('agentItemHistoryBody').innerHTML = html || '<tr><td colspan="7" class="text-center text-muted">No history</td></tr>';
                            })
                            .catch(function(){
                                document.getElementById('agentItemHistoryBody').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load history</td></tr>';
                            });
                        agentItemHistoryModal.show();
                    });
                });
            });
    }

    function searchCatalog() {
        var q = document.getElementById('agentStockSearch').value || '';
        if (q.length < 2) {
            document.getElementById('agentStockSuggestions').style.display = 'none';
            document.getElementById('agentStockSuggestions').innerHTML = '';
            return;
        }
        fetch('<?php echo base_url('pos-agents/stock/catalog'); ?>?agent_user_id=' + encodeURIComponent(currentAgentId) + '&q=' + encodeURIComponent(q))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var wrap = document.getElementById('agentStockSuggestions');
                if (!data || data.status !== 'success' || !data.items || !data.items.length) {
                    wrap.style.display = 'none';
                    wrap.innerHTML = '';
                    return;
                }
                var html = '';
                data.items.forEach(function(item) {
                    if (selectedStockItems.some(function(s) { return stockKey(s) === stockKey(item); })) return;
                    html += '<button type="button" class="list-group-item list-group-item-action st-suggestion" data-item=\'' + JSON.stringify(item).replace(/'/g, '&#39;') + '\'>' +
                        '<strong>' + (item.product_name || '-') + '</strong> | ' + (item.variation_key || '-') + ' | ' + (item.school_name || '-') + ' | ' + (item.board_name || '-') + '</button>';
                });
                if (!html) {
                    wrap.style.display = 'none';
                    wrap.innerHTML = '';
                    return;
                }
                wrap.innerHTML = html;
                wrap.style.display = 'block';
                document.querySelectorAll('.st-suggestion').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var item = JSON.parse((btn.getAttribute('data-item') || '{}').replace(/&#39;/g, "'"));
                        item.action = 'assign';
                        item.qty = 1;
                        item.remark = '';
                        item.main_qty = Number(item.main_qty || 0);
                        item.agent_qty = Number(item.agent_qty || 0);
                        selectedStockItems.push(item);
                        renderSelectedStockRows();
                        document.getElementById('agentStockSearch').value = '';
                        wrap.style.display = 'none';
                    });
                });
            });
    }

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

    document.querySelectorAll('.agent-stock-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!agentStockModal) return;
            currentAgentId = Number(btn.getAttribute('data-agent-id') || 0);
            document.getElementById('agentStockTitle').textContent = btn.getAttribute('data-agent-name') || '';
            selectedStockItems = [];
            renderSelectedStockRows();
            loadAgentSummary();
            agentStockModal.show();
        });
    });

    document.getElementById('agentStockSearch').addEventListener('input', searchCatalog);
    document.getElementById('agentStockApplyBtn').addEventListener('click', function() {
        if (!currentAgentId) return;
        if (!selectedStockItems.length) {
            alert('Select at least one product.');
            return;
        }
        for (var i = 0; i < selectedStockItems.length; i++) {
            var it = selectedStockItems[i];
            var qty = Number(it.qty || 0);
            if (qty <= 0) {
                alert('Enter valid qty for all selected rows.');
                return;
            }
            if (it.action === 'assign' && qty > Number(it.main_qty || 0)) {
                alert('Assign qty exceeds main stock for ' + (it.product_name || 'item'));
                return;
            }
            if (it.action === 'return' && qty > Number(it.agent_qty || 0)) {
                alert('Return qty exceeds agent stock for ' + (it.product_name || 'item'));
                return;
            }
        }
        var calls = selectedStockItems.map(function(item) {
            var form = new URLSearchParams();
            form.set('agent_user_id', currentAgentId);
            form.set('item_type', item.item_type || '');
            form.set('item_ref_id', item.item_ref_id || '');
            form.set('variation_key', item.variation_key || 'default');
            form.set('action', item.action || 'assign');
            form.set('qty', item.qty || 0);
            form.set('remarks', item.remark || '');
            return fetch('<?php echo base_url('pos-agents/stock/transfer'); ?>', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: form.toString() })
                .then(function(r) { return r.json(); });
        });
        Promise.all(calls).then(function(results) {
            var failed = results.filter(function(r) { return !r || r.status !== 'success'; });
            if (failed.length) {
                alert((failed[0] && failed[0].message) ? failed[0].message : 'Some stock updates failed.');
            } else {
                alert('Stock updated successfully.');
            }
            selectedStockItems = [];
            renderSelectedStockRows();
            loadAgentSummary();
        }).catch(function() {
            alert('Failed to update stock.');
        });
    });
});
</script>
