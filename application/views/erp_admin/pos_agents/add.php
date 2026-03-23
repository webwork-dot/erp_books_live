<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h6><a href="<?php echo base_url('erp-admin/pos-agents'); ?>"><i class="isax isax-arrow-left me-2"></i>Add POS Agent</a></h6>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php echo form_open('erp-admin/pos-agents/add'); ?>
            <div class="row gx-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="<?php echo set_value('username'); ?>" required>
                    <?php echo form_error('username', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>">
                    <?php echo form_error('email', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Password (Leave blank to auto-generate)</label>
                    <input type="text" name="password" class="form-control" value="<?php echo set_value('password'); ?>">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="vendor_id" class="form-select" required>
                        <option value="">Select Vendor</option>
                        <?php foreach ($vendors as $vendor): ?>
                            <option value="<?php echo (int)$vendor['id']; ?>" <?php echo set_select('vendor_id', (string)$vendor['id'], ((int)$selected_vendor_id === (int)$vendor['id'])); ?>>
                                <?php echo htmlspecialchars($vendor['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('vendor_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" <?php echo set_checkbox('status', '1', TRUE); ?>>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
            </div>

            <div class="border rounded p-3 mb-3">
                <h6 class="mb-3">School Access</h6>
                <div id="schools-container">
                    <?php $selected_schools = (array)$this->input->post('school_ids'); ?>
                    <?php if (!empty($schools)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Assign</th>
                                        <th>School</th>
                                        <th>Uniform</th>
                                        <th>Bookset</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($schools as $school): ?>
                                        <?php $sid = (int)$school['id']; ?>
                                        <tr>
                                            <td><input type="checkbox" name="school_ids[]" value="<?php echo $sid; ?>" <?php echo in_array((string)$sid, $selected_schools, TRUE) || in_array($sid, $selected_schools, TRUE) ? 'checked' : ''; ?>></td>
                                            <td><?php echo htmlspecialchars($school['school_name']); ?></td>
                                            <td><input type="checkbox" name="category_uniform[<?php echo $sid; ?>]" value="1" checked></td>
                                            <td><input type="checkbox" name="category_bookset[<?php echo $sid; ?>]" value="1" checked></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Select a vendor to load schools.</p>
                    <?php endif; ?>
                </div>
                <?php echo form_error('school_ids[]', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo base_url('erp-admin/pos-agents'); ?>" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create POS Agent</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
(function() {
    var vendorSelect = document.getElementById('vendor_id');
    var schoolsContainer = document.getElementById('schools-container');

    function renderSchools(schools) {
        if (!schools || !schools.length) {
            schoolsContainer.innerHTML = '<p class="text-muted mb-0">No active schools found for selected vendor.</p>';
            return;
        }

        var html = '';
        html += '<div class="table-responsive">';
        html += '<table class="table table-sm table-bordered align-middle">';
        html += '<thead><tr><th>Assign</th><th>School</th><th>Uniform</th><th>Bookset</th></tr></thead><tbody>';

        schools.forEach(function(school) {
            var sid = parseInt(school.id, 10);
            html += '<tr>';
            html += '<td><input type="checkbox" name="school_ids[]" value="' + sid + '"></td>';
            html += '<td>' + school.school_name + '</td>';
            html += '<td><input type="checkbox" name="category_uniform[' + sid + ']" value="1" checked></td>';
            html += '<td><input type="checkbox" name="category_bookset[' + sid + ']" value="1" checked></td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        schoolsContainer.innerHTML = html;
    }

    vendorSelect.addEventListener('change', function() {
        var vendorId = this.value;
        if (!vendorId) {
            schoolsContainer.innerHTML = '<p class="text-muted mb-0">Select a vendor to load schools.</p>';
            return;
        }

        schoolsContainer.innerHTML = '<p class="text-muted mb-0">Loading schools...</p>';
        fetch('<?php echo base_url('erp-admin/pos-agents/schools-by-vendor/'); ?>' + vendorId)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.status === 'success') {
                    renderSchools(data.schools || []);
                    return;
                }
                schoolsContainer.innerHTML = '<p class="text-danger mb-0">Failed to load schools.</p>';
            })
            .catch(function() {
                schoolsContainer.innerHTML = '<p class="text-danger mb-0">Failed to load schools.</p>';
            });
    });
})();
</script>
