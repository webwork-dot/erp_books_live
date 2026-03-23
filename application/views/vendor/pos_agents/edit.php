<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
        <h6><a href="<?php echo base_url('pos-agents'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit POS Agent</a></h6>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php
            $mapped_schools = array();
            if (!empty($existing_access)) {
                foreach ($existing_access as $row) {
                    $mapped_schools[(int)$row['school_id']] = $row;
                }
            }
            $selected_schools = array_keys($mapped_schools);
            $category_access = array();
            $upi_qr_map = array();

            foreach ($mapped_schools as $sid => $row) {
                $can_uniform = isset($row['can_uniform']) ? (int)$row['can_uniform'] : 0;
                $can_bookset = isset($row['can_bookset']) ? (int)$row['can_bookset'] : 0;

                if ($can_uniform === 1 && $can_bookset === 1) {
                    $category_access[$sid] = 'both';
                } elseif ($can_uniform === 1) {
                    $category_access[$sid] = 'uniform';
                } elseif ($can_bookset === 1) {
                    $category_access[$sid] = 'bookset';
                }

                if (isset($row['upi_qr_id']) && (int)$row['upi_qr_id'] > 0) {
                    $upi_qr_map[$sid] = (int)$row['upi_qr_id'];
                }
            }

            $post_school_ids = $this->input->post('school_ids');
            if (is_array($post_school_ids)) {
                $selected_schools = array_map('intval', $post_school_ids);
            }

            $post_category_access = $this->input->post('category_access');
            if (is_array($post_category_access)) {
                $category_access = $post_category_access;
            }

            $post_upi_qr = $this->input->post('upi_qr_id');
            if (is_array($post_upi_qr)) {
                $upi_qr_map = $post_upi_qr;
            }
        ?>
        <?php echo form_open('pos-agents/edit/' . $agent['id']); ?>
            <div class="row gx-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($agent['username']); ?>" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email', $agent['email']); ?>">
                    <?php echo form_error('email', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">New Password (optional)</label>
                    <input type="text" name="password" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="status" value="1" <?php echo set_checkbox('status', '1', ((int)$agent['status'] === 1)); ?>>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
            </div>

            <div class="border rounded p-3 mb-3">
                <h6 class="mb-3">School Access</h6>
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
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo base_url('pos-agents'); ?>" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
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
