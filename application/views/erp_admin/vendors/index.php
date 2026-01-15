<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1 style="margin: 0;">Manage Vendors</h1>
    <a href="<?php echo base_url('erp-admin/vendors/add'); ?>" class="btn btn-primary">Add New Vendor</a>
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
                <?php if (!empty($vendors)): ?>
                    <?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($vendors as $vendor): ?>
                        <tr>
                            <td><?php echo $sr_no++; ?></td>
                            <td><?php echo htmlspecialchars($vendor['name']); ?></td>
                            <td><?php echo htmlspecialchars($vendor['domain']); ?></td>
                            <td><?php echo htmlspecialchars($vendor['database_name']); ?></td>
                            <td>
                                <span class="badge <?php echo $vendor['status'] == 'active' ? 'badge-success' : ($vendor['status'] == 'suspended' ? 'badge-warning' : 'badge-danger'); ?>">
                                    <?php echo ucfirst($vendor['status']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?php echo base_url('erp-admin/vendors/edit/' . $vendor['id']); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="isax isax-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#featuresModal" data-vendor-id="<?php echo $vendor['id']; ?>" data-vendor-name="<?php echo htmlspecialchars($vendor['name']); ?>" title="Features">
                                    <i class="isax isax-shapes5"></i>
                                </button>
                                <a href="<?php echo base_url('erp-admin/vendors/delete/' . $vendor['id']); ?>" onclick="return confirm('Are you sure you want to delete this vendor?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                    <i class="isax isax-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No vendors found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if (!empty($vendors)): ?>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Total Vendors: <strong><?php echo $total_vendors; ?></strong></p>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/vendors?' . http_build_query(array_merge($filters, array('page' => $current_page - 1)))); ?>">Previous</a>
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
                                        <a class="page-link" href="<?php echo base_url('erp-admin/vendors?' . http_build_query(array_merge($filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/vendors?' . http_build_query(array_merge($filters, array('page' => $current_page + 1)))); ?>">Next</a>
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

<!-- Features Modal -->
<div class="modal fade" id="featuresModal" tabindex="-1" aria-labelledby="featuresModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="featuresModalLabel">Manage Features</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="featuresModalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading features...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveFeaturesBtn">Save Features</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentVendorId = null;

// Handle modal show event
document.getElementById('featuresModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    currentVendorId = button.getAttribute('data-vendor-id');
    var vendorName = button.getAttribute('data-vendor-name');
    
    // Update modal title
    document.getElementById('featuresModalLabel').textContent = 'Manage Features - ' + vendorName;
    
    // Load features
    loadVendorFeatures(currentVendorId);
});

// Load vendor features
function loadVendorFeatures(vendorId) {
    var content = document.getElementById('featuresModalContent');
    content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading features...</p></div>';
    
    fetch('<?php echo base_url('erp-admin/vendors/get_features/'); ?>' + vendorId + '?t=' + Date.now())
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                var html = '<div class="row">';
                
                if (data.features && data.features.length > 0) {
                    // Normalize enabled_features to numbers for consistent comparison
                    var enabledFeaturesNums = [];
                    if (data.enabled_features && Array.isArray(data.enabled_features)) {
                        enabledFeaturesNums = data.enabled_features.map(function(id) { 
                            var num = typeof id === 'string' ? parseInt(id, 10) : (typeof id === 'number' ? id : parseInt(String(id), 10));
                            return isNaN(num) ? 0 : num;
                        });
                    }
                    
                    // Normalize enabled_subcategories
                    var enabledSubcategories = {};
                    if (data.enabled_subcategories && typeof data.enabled_subcategories === 'object') {
                        for (var featureId in data.enabled_subcategories) {
                            var featureIdNum = parseInt(featureId, 10);
                            if (!isNaN(featureIdNum)) {
                                enabledSubcategories[featureIdNum] = data.enabled_subcategories[featureId].map(function(id) {
                                    return typeof id === 'string' ? parseInt(id, 10) : (typeof id === 'number' ? id : parseInt(String(id), 10));
                                });
                            }
                        }
                    }
                    
                    // Filter only main categories (no parent_id)
                    var mainFeatures = data.features.filter(function(feature) {
                        return !feature.parent_id || feature.parent_id === null || feature.parent_id === '';
                    });
                    
                    mainFeatures.forEach(function(feature) {
                        var featureId = typeof feature.id === 'string' ? parseInt(feature.id, 10) : (typeof feature.id === 'number' ? feature.id : parseInt(String(feature.id), 10));
                        if (isNaN(featureId)) featureId = 0;
                        var isChecked = enabledFeaturesNums.indexOf(featureId) !== -1 ? 'checked' : '';
                        var hasSubcategories = feature.subcategories && feature.subcategories.length > 0;
                        var enabledSubcatIds = enabledSubcategories[featureId] || [];
                        
                        html += '<div class="col-md-6 mb-3">';
                        html += '<div class="form-check">';
                        html += '<input type="checkbox" name="features[' + feature.id + ']" value="1" id="modal_feature_' + feature.id + '" class="form-check-input modal-feature-checkbox" data-feature-id="' + feature.id + '" ' + isChecked + '>';
                        html += '<label class="form-check-label" for="modal_feature_' + feature.id + '">';
                        html += '<strong>' + escapeHtml(feature.name) + '</strong>';
                        if (feature.description) {
                            html += '<br><small class="text-muted">' + escapeHtml(feature.description) + '</small>';
                        }
                        html += '</label>';
                        html += '</div>';
                        
                        // Add subcategories if they exist
                        if (hasSubcategories) {
                            html += '<div class="subcategories-container ms-4 mt-2" id="modal_subcategories_' + feature.id + '" style="display: ' + (isChecked ? 'block' : 'none') + ';">';
                            html += '<small class="text-muted d-block mb-2">Sub-categories:</small>';
                            feature.subcategories.forEach(function(subcat) {
                                var subcatId = typeof subcat.id === 'string' ? parseInt(subcat.id, 10) : (typeof subcat.id === 'number' ? subcat.id : parseInt(String(subcat.id), 10));
                                var subcatChecked = enabledSubcatIds.indexOf(subcatId) !== -1 ? 'checked' : '';
                                html += '<div class="form-check form-check-sm">';
                                html += '<input type="checkbox" name="subcategories[' + feature.id + '][]" value="' + subcat.id + '" id="modal_subcat_' + feature.id + '_' + subcat.id + '" class="form-check-input" ' + subcatChecked + '>';
                                html += '<label class="form-check-label" for="modal_subcat_' + feature.id + '_' + subcat.id + '">';
                                html += '<small>' + escapeHtml(subcat.name) + '</small>';
                                html += '</label>';
                                html += '</div>';
                            });
                            html += '</div>';
                        }
                        
                        html += '</div>';
                    });
                } else {
                    html += '<div class="col-12"><p class="text-muted">No features available.</p></div>';
                }
                
                html += '</div>';
                content.innerHTML = html;
                
                // Add event listeners for feature checkboxes to show/hide subcategories
                var featureCheckboxes = content.querySelectorAll('.modal-feature-checkbox');
                featureCheckboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        var featureId = this.getAttribute('data-feature-id');
                        var subcategoriesContainer = document.getElementById('modal_subcategories_' + featureId);
                        
                        if (subcategoriesContainer) {
                            if (this.checked) {
                                subcategoriesContainer.style.display = 'block';
                            } else {
                                subcategoriesContainer.style.display = 'none';
                                // Uncheck all subcategories when main feature is unchecked
                                var subcatCheckboxes = subcategoriesContainer.querySelectorAll('input[type="checkbox"]');
                                subcatCheckboxes.forEach(function(subcat) {
                                    subcat.checked = false;
                                });
                            }
                        }
                    });
                });
            } else {
                content.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Failed to load features') + '</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = '<div class="alert alert-danger">An error occurred while loading features.</div>';
        });
}

// Save features
document.getElementById('saveFeaturesBtn').addEventListener('click', function() {
    if (!currentVendorId) return;
    
    var formData = new FormData();
    
    // Get all feature checkboxes (main categories)
    var featureCheckboxes = document.querySelectorAll('#featuresModalContent input[name^="features["]');
    featureCheckboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            formData.append(checkbox.getAttribute('name'), checkbox.value);
        }
    });
    
    // Get all subcategory checkboxes
    var subcategoryCheckboxes = document.querySelectorAll('#featuresModalContent input[name^="subcategories["]');
    subcategoryCheckboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            formData.append(checkbox.getAttribute('name'), checkbox.value);
        }
    });
    
    // Add CSRF token
    formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
    
    var btn = this;
    btn.disabled = true;
    btn.textContent = 'Saving...';
    
    fetch('<?php echo base_url('erp-admin/vendors/update_features/'); ?>' + currentVendorId, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Show success message
            var alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            document.getElementById('featuresModalContent').insertAdjacentHTML('afterbegin', alert);
            
            // Close modal after 1 second
            setTimeout(function() {
                var modal = bootstrap.Modal.getInstance(document.getElementById('featuresModal'));
                modal.hide();
                // Reload page to reflect changes
                location.reload();
            }, 1000);
        } else {
            var alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' + (data.message || 'Failed to update features') + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            document.getElementById('featuresModalContent').insertAdjacentHTML('afterbegin', alert);
            btn.disabled = false;
            btn.textContent = 'Save Features';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        var alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">An error occurred while saving features.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        document.getElementById('featuresModalContent').insertAdjacentHTML('afterbegin', alert);
        btn.disabled = false;
        btn.textContent = 'Save Features';
    });
});

// Escape HTML function
function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>

