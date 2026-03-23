<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h6><a href="<?php echo base_url('pos-agents'); ?>"><i class="isax isax-arrow-left me-2"></i>Add POS Agent</a></h6>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php echo form_open('pos-agents/add'); ?>
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
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" <?php echo set_checkbox('status', '1', TRUE); ?>>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
            </div>

            <div class="border rounded p-3 mb-3">
                <h6 class="mb-3">School Access</h6>
                <?php
                    $selected_schools = array_map('intval', (array)$this->input->post('school_ids'));
                    $category_access = (array)$this->input->post('category_access');
                    $upi_qr_map = (array)$this->input->post('upi_qr_id');
                ?>
                <div class="mb-3">
                    <label class="form-label">Select Schools <span class="text-danger">*</span></label>
                    <select name="school_ids[]" id="school_ids" class="form-select" multiple="multiple" style="width:100%;">
                        <?php foreach ($schools as $school): ?>
                            <?php $sid = (int)$school['id']; ?>
                            <option value="<?php echo $sid; ?>" <?php echo in_array($sid, $selected_schools, TRUE) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($school['school_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="school-config-container" class="table-responsive"></div>
                <?php echo form_error('school_ids[]', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo base_url('pos-agents'); ?>" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create POS Agent</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
(function() {
    var schools = <?php echo json_encode($schools); ?>;
    var upiOptionsBySchool = <?php echo json_encode(isset($upi_options_by_school) ? $upi_options_by_school : array()); ?>;
    var initialCategoryMap = <?php echo json_encode($category_access); ?>;
    var initialUpiMap = <?php echo json_encode($upi_qr_map); ?>;
    var schoolSelect = $('#school_ids');
    var configContainer = $('#school-config-container');

    function getSchoolName(schoolId) {
        var sid = parseInt(schoolId, 10);
        for (var i = 0; i < schools.length; i++) {
            if (parseInt(schools[i].id, 10) === sid) {
                return schools[i].school_name;
            }
        }
        return 'School #' + sid;
    }

    function buildUpiOptionsHtml(schoolId, selectedUpiId) {
        var sid = String(parseInt(schoolId, 10));
        var options = upiOptionsBySchool[sid] || [];
        var html = '<option value="">Select UPI ID</option>';

        for (var i = 0; i < options.length; i++) {
            var opt = options[i];
            var oid = String(opt.id);
            var selected = (String(selectedUpiId || '') === oid) ? ' selected' : '';
            html += '<option value="' + oid + '"' + selected + '>' + String(opt.label || opt.upi_id || ('QR #' + oid)) + '</option>';
        }

        return html;
    }

    function renderSchoolConfigRows() {
        var selected = schoolSelect.val() || [];
        if (!selected.length) {
            configContainer.html('<p class="text-muted mb-0">Select one or more schools to configure category and UPI.</p>');
            return;
        }

        var html = '';
        html += '<table class="table table-sm table-bordered align-middle">';
        html += '<thead><tr><th>School</th><th>Category Access</th><th>UPI ID</th></tr></thead><tbody>';

        for (var i = 0; i < selected.length; i++) {
            var sid = selected[i];
            var selectedCategory = initialCategoryMap[sid] || 'both';
            var selectedUpi = initialUpiMap[sid] || '';

            html += '<tr>';
            html += '<td>' + getSchoolName(sid) + '</td>';
            html += '<td>';
            html += '<select name="category_access[' + sid + ']" class="form-select">';
            html += '<option value="both"' + (selectedCategory === 'both' ? ' selected' : '') + '>Uniform + Bookset</option>';
            html += '<option value="uniform"' + (selectedCategory === 'uniform' ? ' selected' : '') + '>Uniform Only</option>';
            html += '<option value="bookset"' + (selectedCategory === 'bookset' ? ' selected' : '') + '>Bookset Only</option>';
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<select name="upi_qr_id[' + sid + ']" class="form-select">';
            html += buildUpiOptionsHtml(sid, selectedUpi);
            html += '</select>';
            html += '</td>';
            html += '</tr>';
        }

        html += '</tbody></table>';
        configContainer.html(html);
    }

    if (schoolSelect.length && typeof schoolSelect.select2 === 'function') {
        schoolSelect.select2({
            width: '100%',
            placeholder: 'Select schools'
        });
    }

    schoolSelect.on('change', renderSchoolConfigRows);
    renderSchoolConfigRows();
})();
</script>
