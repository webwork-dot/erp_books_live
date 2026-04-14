<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <div>
        <h6 class="mb-0">POS Agent Stock - <?php echo htmlspecialchars((string)$agent['username']); ?></h6>
    </div>
    <div>
        <a href="<?php echo base_url('pos-agents'); ?>" class="btn btn-outline-secondary btn-sm">Back to POS Agents</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
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
                    <tr><th>Product</th><th>Size</th><th>Gender</th><th>School</th><th>Grade</th><th>Main Qty</th><th>Agent Qty</th><th>Action</th><th>Qty</th><th>Remark</th><th></th></tr>
                </thead>
                <tbody id="agentStockSelectedBody">
                    <tr><td colspan="11" class="text-center text-muted">No selected products</td></tr>
                </tbody>
            </table>
        </div>
        <div class="text-end mb-3">
            <button type="button" class="btn btn-sm btn-primary" id="agentStockApplyBtn">Apply Stock</button>
        </div>

        <h6 class="mb-2">Agent Current Stock</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead><tr><th>Product Name</th><th>Uniform Type</th><th>Size</th><th>Gender</th><th>School</th><th>Board</th><th>Grade</th><th>Total Qty</th><th>Sold Qty</th><th>Remain Qty</th><th>Last Assigned Date</th><th>Action</th></tr></thead>
                <tbody id="agentHoldingsBody"><tr><td colspan="12" class="text-center text-muted">No stock</td></tr></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="agentItemHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 92vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Item History - <span id="agentItemHistoryTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Product Name</th><th>Uniform Type</th><th>Size</th><th>Gender</th><th>School</th><th>Board</th><th>Grade</th></tr></thead>
                        <tbody><tr><td id="histMetaProductName">-</td><td id="histMetaUniformType">-</td><td id="histMetaSize">-</td><td id="histMetaGender">-</td><td id="histMetaSchool">-</td><td id="histMetaBoard">-</td><td id="histMetaGrade">-</td></tr></tbody>
                    </table>
                </div>
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
    var currentAgentId = <?php echo (int)$agent['id']; ?>;
    var selectedStockItems = [];
    var agentItemHistoryModalEl = document.getElementById('agentItemHistoryModal');
    var agentItemHistoryModal = agentItemHistoryModalEl ? new bootstrap.Modal(agentItemHistoryModalEl) : null;

    function stockKey(item) {
        return (item.item_type || '') + '|' + (item.item_ref_id || '') + '|' + (item.variation_key || 'default');
    }

    function renderSelectedStockRows() {
        var body = document.getElementById('agentStockSelectedBody');
        if (!selectedStockItems.length) {
            body.innerHTML = '<tr><td colspan="11" class="text-center text-muted">No selected products</td></tr>';
            return;
        }
        var html = '';
        selectedStockItems.forEach(function(item, idx) {
            var schoolText = item.school_name || '-';
            if (item.branch_name && item.branch_name !== '-') {
                schoolText += '<br><small class="text-muted">' + item.branch_name + '</small>';
            }
            html += '<tr>' +
                '<td>' + (item.product_name || '-') + '</td>' +
                '<td>' + (item.variation_key || '-') + '</td>' +
                '<td>' + (item.gender || '-') + '</td>' +
                '<td>' + schoolText + '</td>' +
                '<td>' + (item.grade_name || '-') + '</td>' +
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

    function bindHoldingsActionButtons() {
        document.querySelectorAll('.quick-stock-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var item = {
                    item_type: btn.getAttribute('data-item-type') || '',
                    item_ref_id: btn.getAttribute('data-item-ref-id') || '',
                    variation_key: btn.getAttribute('data-variation-key') || 'default',
                    product_name: btn.getAttribute('data-product-name') || '',
                    gender: btn.getAttribute('data-gender') || '-',
                    school_name: btn.getAttribute('data-school-name') || '-',
                    branch_name: btn.getAttribute('data-branch-name') || '',
                    grade_name: btn.getAttribute('data-grade-name') || '-',
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
                var uniformType = btn.getAttribute('data-uniform-type') || '-';
                var gender = btn.getAttribute('data-gender') || '-';
                var schoolName = btn.getAttribute('data-school-name') || '-';
                var branchName = btn.getAttribute('data-branch-name') || '';
                var boardName = btn.getAttribute('data-board-name') || '-';
                var gradeName = btn.getAttribute('data-grade-name') || '-';
                var schoolText = schoolName;
                if (branchName && branchName !== '-') {
                    schoolText += ' (' + branchName + ')';
                }
                document.getElementById('agentItemHistoryTitle').textContent = productName + ' (' + variationKey + ')';
                document.getElementById('histMetaProductName').textContent = productName || '-';
                document.getElementById('histMetaUniformType').textContent = uniformType || '-';
                document.getElementById('histMetaSize').textContent = variationKey || '-';
                document.getElementById('histMetaGender').textContent = gender || '-';
                document.getElementById('histMetaSchool').textContent = schoolText || '-';
                document.getElementById('histMetaBoard').textContent = boardName || '-';
                document.getElementById('histMetaGrade').textContent = gradeName || '-';
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
    }

    function loadAgentSummary() {
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
                        'data-gender="' + (r.gender || '-') + '" ' +
                        'data-school-name="' + (r.school_name || '-') + '" ' +
                        'data-branch-name="' + (r.branch_name || '') + '" ' +
                        'data-grade-name="' + (r.grade_name || '-') + '" ' +
                        'data-main-qty="0" ' +
                        'data-agent-qty="' + Math.round(Number(r.remain_qty || 0)) + '">Stock</button>' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary quick-history-btn" ' +
                        'data-item-type="' + (r.item_type || '') + '" ' +
                        'data-item-ref-id="' + (r.item_ref_id || '') + '" ' +
                        'data-variation-key="' + (r.variation_key || 'default') + '" ' +
                        'data-product-name="' + (r.product_name || '') + '" ' +
                        'data-uniform-type="' + (r.uniform_type_name || '-') + '" ' +
                        'data-gender="' + (r.gender || '-') + '" ' +
                        'data-school-name="' + (r.school_name || '-') + '" ' +
                        'data-branch-name="' + (r.branch_name || '') + '" ' +
                        'data-board-name="' + (r.board_name || '-') + '" ' +
                        'data-grade-name="' + (r.grade_name || '-') + '">History</button></div></td>' +
                        '</tr>';
                });
                hBody.innerHTML = hHtml || '<tr><td colspan="12" class="text-center text-muted">No stock</td></tr>';
                bindHoldingsActionButtons();
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
                    var schoolText = item.school_name || '-';
                    if (item.branch_name) schoolText += ' (' + item.branch_name + ')';
                    html += '<button type="button" class="list-group-item list-group-item-action st-suggestion" data-item=\'' + JSON.stringify(item).replace(/'/g, '&#39;') + '\'>' +
                        '<strong>' + (item.product_name || '-') + '</strong> | ' + (item.variation_key || '-') + ' | ' + (item.gender || '-') + ' | ' + schoolText + ' | ' + (item.board_name || '-') + '</button>';
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
            })
            .catch(function() {
                var wrap = document.getElementById('agentStockSuggestions');
                wrap.style.display = 'none';
                wrap.innerHTML = '';
            });
    }

    document.getElementById('agentStockSearch').addEventListener('input', searchCatalog);
    document.getElementById('agentStockApplyBtn').addEventListener('click', function() {
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
                selectedStockItems = [];
                renderSelectedStockRows();
                loadAgentSummary();
                alert('Stock updated successfully.');
            }
        });
    });

    renderSelectedStockRows();
    loadAgentSummary();
});
</script>
