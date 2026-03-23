<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1 style="margin: 0;">Manage Features</h1>
    <button onclick="openOffCanvas('add')" class="btn btn-primary">Add New Feature</button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>SR No.</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Parent Category</th>
                    <th>Description</th>
                    <th>Is School</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($features)): ?>
                    <?php $sr_no = (($current_page - 1) * $per_page) + 1; foreach ($features as $feature): ?>
                        <tr>
                            <td><?php echo $sr_no++; ?></td>
                            <td>
                                <?php echo htmlspecialchars($feature['name']); ?>
                                <?php if (!empty($feature['parent_id'])): ?>
                                    <br><small class="text-muted">(Sub-category)</small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($feature['slug']); ?></td>
                            <td>
                                <?php if (!empty($feature['parent_name'])): ?>
                                    <span class="badge badge-info"><?php echo htmlspecialchars($feature['parent_name']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Main Category</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($feature['description']); ?></td>
                            <td>
                                <span class="badge <?php echo (isset($feature['is_school']) && $feature['is_school']) ? 'badge-info' : 'badge-secondary'; ?>">
                                    <?php echo (isset($feature['is_school']) && $feature['is_school']) ? 'Yes' : 'No'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $feature['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $feature['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button onclick="openOffCanvas('edit', <?php echo $feature['id']; ?>)" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="isax isax-edit"></i>
                                </button>
                                <a href="<?php echo base_url('erp-admin/features/delete/' . $feature['id']); ?>" onclick="return confirm('Are you sure you want to delete this feature?');" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                    <i class="isax isax-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No features found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if (!empty($features)): ?>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Total Features: <strong><?php echo $total_features; ?></strong></p>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/features?page=' . ($current_page - 1)); ?>">Previous</a>
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
                                        <a class="page-link" href="<?php echo base_url('erp-admin/features?page=' . $i); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('erp-admin/features?page=' . ($current_page + 1)); ?>">Next</a>
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

<!-- Off Canvas Overlay -->
<div id="offCanvasOverlay" class="offcanvas-overlay" onclick="closeOffCanvas()"></div>

<!-- Off Canvas Panel (Add) -->
<div id="offCanvasAdd" class="offcanvas-panel">
    <div class="offcanvas-header">
        <h2>Add New Feature</h2>
        <button onclick="closeOffCanvas()" class="offcanvas-close">&times;</button>
    </div>
    <div class="offcanvas-body">
        <?php echo form_open('erp-admin/features/add', array('id' => 'addFeatureForm')); ?>
            <div class="form-group">
                <label for="add_name">Feature Name *</label>
                <input type="text" name="name" id="add_name" class="form-control" required>
                <?php echo form_error('name', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="add_slug">Slug *</label>
                <input type="text" name="slug" id="add_slug" class="form-control" required>
                <small class="text-muted">Auto-generated from feature name. You can edit it if needed.</small>
                <div id="add_slug-status" class="mt-1"></div>
                <?php echo form_error('slug', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="add_description">Description</label>
                <textarea name="description" id="add_description" class="form-control" rows="4"></textarea>
                <?php echo form_error('description', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="add_parent_id">Parent Category</label>
                <select name="parent_id" id="add_parent_id" class="form-control">
                    <option value="">Main Category (No Parent)</option>
                    <?php if (!empty($main_categories)): ?>
                        <?php foreach ($main_categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="text-muted">Leave empty for main category, or select a parent to create a sub-category</small>
                <?php echo form_error('parent_id', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="add_is_school">
                    <input type="checkbox" name="is_school" id="add_is_school" value="1"> Is School Feature
                </label>
            </div>
            
            <div class="form-group">
                <label for="add_is_active">Is Active *</label>
                <select name="is_active" id="add_is_active" class="form-control" required>
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
                <?php echo form_error('is_active', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem;">Product Options</label>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <label class="toggle-button">
                        <input type="checkbox" name="has_variations" id="add_has_variations" value="1">
                        <span class="toggle-label">Variations</span>
                    </label>
                    <label class="toggle-button">
                        <input type="checkbox" name="has_size" id="add_has_size" value="1">
                        <span class="toggle-label">Size</span>
                    </label>
                    <label class="toggle-button">
                        <input type="checkbox" name="has_colour" id="add_has_colour" value="1">
                        <span class="toggle-label">Colour</span>
                    </label>
                </div>
                <small class="text-muted">Select which product options are available for this feature</small>
            </div>
            
            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Create Feature</button>
                <button type="button" onclick="closeOffCanvas()" class="btn btn-outline">Cancel</button>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Off Canvas Panel (Edit) -->
<div id="offCanvasEdit" class="offcanvas-panel">
    <div class="offcanvas-header">
        <h2>Edit Feature</h2>
        <button onclick="closeOffCanvas()" class="offcanvas-close">&times;</button>
    </div>
    <div class="offcanvas-body">
        <div id="editFormContainer">
            <!-- Form will be loaded here via AJAX or populated on page load -->
        </div>
    </div>
</div>

<style>
/* Toggle Button Styles */
.toggle-button {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.toggle-button:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.toggle-button input[type="checkbox"] {
    display: none;
}

.toggle-label {
    font-size: 0.875rem;
    color: #666;
    cursor: pointer;
    transition: all 0.2s ease;
}

.toggle-button:has(input[type="checkbox"]:checked) {
    border-color: #667eea;
    background: #f0f4ff;
}

.toggle-button:has(input[type="checkbox"]:checked) .toggle-label {
    color: #667eea;
    font-weight: 600;
}

/* Fallback for browsers that don't support :has() */
.toggle-button.active {
    border-color: #667eea;
    background: #f0f4ff;
}

.toggle-button.active .toggle-label {
    color: #667eea;
    font-weight: 600;
}

/* Off Canvas Styles */
/* Off Canvas Styles */
.offcanvas-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040;
    transition: opacity 0.15s ease;
}

.offcanvas-overlay.active {
    display: block;
    opacity: 1;
}

.offcanvas-panel {
    position: fixed;
    top: 0;
    right: -450px;
    width: 450px;
    height: 100vh;
    background: var(--bg-card);
    box-shadow: var(--shadow-lg);
    z-index: 1050;
    transition: right 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.offcanvas-panel.active {
    right: 0;
}

.offcanvas-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-header);
    position: sticky;
    top: 0;
    z-index: 10;
}

.offcanvas-header h2 {
    margin: 0;
    font-size: var(--text-h2);
    font-weight: var(--fw-semibold);
    color: var(--text-primary);
    font-family: var(--font-primary);
}

.offcanvas-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius);
    transition: background-color 0.15s ease;
}

.offcanvas-close:hover {
    background-color: var(--bg-app);
    color: var(--text-primary);
}

.offcanvas-body {
    padding: 1.5rem;
    flex: 1;
}
</style>

<script>
// Feature data for edit form
var featuresData = <?php echo json_encode($features); ?>;
var mainCategoriesData = <?php echo json_encode(isset($main_categories) ? $main_categories : array()); ?>;

function openOffCanvas(type, id = null) {
    var overlay = document.getElementById('offCanvasOverlay');
    var panel = document.getElementById('offCanvas' + type.charAt(0).toUpperCase() + type.slice(1));
    
    if (type === 'edit' && id) {
        var feature = featuresData.find(f => f.id == id);
        if (feature) {
            populateEditForm(feature);
        }
    } else {
        // Reset add form
        document.getElementById('addFeatureForm').reset();
        // Reset slug generation state
        if (window.addSlugManuallyEdited !== undefined) {
            window.addSlugManuallyEdited = false;
        }
        var addSlugStatus = document.getElementById('add_slug-status');
        if (addSlugStatus) addSlugStatus.innerHTML = '';
        var addSlugInput = document.getElementById('add_slug');
        if (addSlugInput) {
            addSlugInput.classList.remove('is-valid', 'is-invalid');
        }
    }
    
    overlay.classList.add('active');
    panel.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Initialize slug generation for add form
    if (type === 'add') {
        setTimeout(function() {
            initAddSlugGeneration();
            initToggleButtons(); // Re-initialize toggle buttons
        }, 100);
    }
}

function closeOffCanvas() {
    var overlay = document.getElementById('offCanvasOverlay');
    var addPanel = document.getElementById('offCanvasAdd');
    var editPanel = document.getElementById('offCanvasEdit');
    
    overlay.classList.remove('active');
    addPanel.classList.remove('active');
    editPanel.classList.remove('active');
    document.body.style.overflow = '';
}

function populateEditForm(feature) {
    var container = document.getElementById('editFormContainer');
    var isSchoolChecked = (feature.is_school == 1 || feature.is_school === '1') ? 'checked' : '';
    var isActiveSelected1 = (feature.is_active == 1 || feature.is_active === '1') ? 'selected' : '';
    var isActiveSelected0 = (feature.is_active == 0 || feature.is_active === '0') ? 'selected' : '';
    var parentId = feature.parent_id || '';
    
    // Build parent category options (exclude current feature)
    var parentOptions = '<option value="">Main Category (No Parent)</option>';
    if (mainCategoriesData && mainCategoriesData.length > 0) {
        mainCategoriesData.forEach(function(cat) {
            if (cat.id != feature.id) {
                var selected = (cat.id == parentId) ? 'selected' : '';
                parentOptions += '<option value="' + cat.id + '" ' + selected + '>' + escapeHtml(cat.name) + '</option>';
            }
        });
    }
    
    container.innerHTML = `
        <form action="<?php echo base_url('erp-admin/features/edit/'); ?>${feature.id}" method="post" id="editFeatureForm">
            <div class="form-group">
                <label for="edit_name">Feature Name *</label>
                <input type="text" name="name" id="edit_name" class="form-control" value="${escapeHtml(feature.name)}" required>
            </div>
            
            <div class="form-group">
                <label for="edit_slug">Slug *</label>
                <input type="text" name="slug" id="edit_slug" class="form-control" value="${escapeHtml(feature.slug)}" required>
                <small class="text-muted">Auto-generated from feature name. You can edit it if needed.</small>
                <div id="edit_slug-status" class="mt-1"></div>
            </div>
            
            <div class="form-group">
                <label for="edit_description">Description</label>
                <textarea name="description" id="edit_description" class="form-control" rows="4">${escapeHtml(feature.description || '')}</textarea>
            </div>
            
            <div class="form-group">
                <label for="edit_parent_id">Parent Category</label>
                <select name="parent_id" id="edit_parent_id" class="form-control">
                    ${parentOptions}
                </select>
                <small class="text-muted">Leave empty for main category, or select a parent to create a sub-category</small>
            </div>
            
            <div class="form-group">
                <label for="edit_is_school">
                    <input type="checkbox" name="is_school" id="edit_is_school" value="1" ${isSchoolChecked}> Is School Feature
                </label>
            </div>
            
            <div class="form-group">
                <label for="edit_is_active">Is Active *</label>
                <select name="is_active" id="edit_is_active" class="form-control" required>
                    <option value="1" ${isActiveSelected1}>Active</option>
                    <option value="0" ${isActiveSelected0}>Inactive</option>
                </select>
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem;">Product Options</label>
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <label class="toggle-button">
                        <input type="checkbox" name="has_variations" id="edit_has_variations" value="1" ${(feature.has_variations == 1) ? 'checked' : ''}>
                        <span class="toggle-label">Variations</span>
                    </label>
                    <label class="toggle-button">
                        <input type="checkbox" name="has_size" id="edit_has_size" value="1" ${(feature.has_size == 1) ? 'checked' : ''}>
                        <span class="toggle-label">Size</span>
                    </label>
                    <label class="toggle-button">
                        <input type="checkbox" name="has_colour" id="edit_has_colour" value="1" ${(feature.has_colour == 1) ? 'checked' : ''}>
                        <span class="toggle-label">Colour</span>
                    </label>
                </div>
                <small class="text-muted">Select which product options are available for this feature</small>
            </div>
            
            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Update Feature</button>
                <button type="button" onclick="closeOffCanvas()" class="btn btn-outline">Cancel</button>
            </div>
        </form>
    `;
    
    // Initialize slug generation for edit form
    setTimeout(function() {
        initEditSlugGeneration(feature.id, feature.slug);
        initToggleButtons(); // Initialize toggle buttons for edit form
        
        // Set active class for checked toggle buttons in edit form
        var editToggleButtons = document.querySelectorAll('#offCanvasEdit .toggle-button');
        editToggleButtons.forEach(function(button) {
            var checkbox = button.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                button.classList.add('active');
            }
        });
    }, 100);
}

// Function to generate slug from text
function generateSlug(text) {
    return text
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '') // Remove special characters
        .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
        .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
}

// Initialize slug generation for add form
function initAddSlugGeneration() {
    var nameInput = document.getElementById('add_name');
    var slugInput = document.getElementById('add_slug');
    var slugStatus = document.getElementById('add_slug-status');
    
    if (!nameInput || !slugInput) return;
    
    window.addSlugManuallyEdited = false;
    var checkSlugTimeout = null;
    
    nameInput.addEventListener('input', function() {
        if (!window.addSlugManuallyEdited) {
            var slug = generateSlug(this.value);
            slugInput.value = slug;
            if (slug.length > 0) {
                checkSlugUniqueness(slug, null, slugInput, slugStatus, checkSlugTimeout, function(timeout) {
                    checkSlugTimeout = timeout;
                });
            }
        }
    });
    
    slugInput.addEventListener('input', function() {
        window.addSlugManuallyEdited = true;
        if (this.value.length > 0) {
            checkSlugUniqueness(this.value, null, slugInput, slugStatus, checkSlugTimeout, function(timeout) {
                checkSlugTimeout = timeout;
            });
        } else {
            slugStatus.innerHTML = '';
        }
    });
}

// Initialize slug generation for edit form
function initEditSlugGeneration(featureId, originalSlug) {
    var nameInput = document.getElementById('edit_name');
    var slugInput = document.getElementById('edit_slug');
    var slugStatus = document.getElementById('edit_slug-status');
    
    if (!nameInput || !slugInput) return;
    
    window.editSlugManuallyEdited = false;
    var checkSlugTimeout = null;
    
    nameInput.addEventListener('input', function() {
        if (!window.editSlugManuallyEdited) {
            var slug = generateSlug(this.value);
            slugInput.value = slug;
            if (slug.length > 0) {
                checkSlugUniqueness(slug, featureId, slugInput, slugStatus, checkSlugTimeout, function(timeout) {
                    checkSlugTimeout = timeout;
                }, originalSlug);
            }
        }
    });
    
    slugInput.addEventListener('input', function() {
        window.editSlugManuallyEdited = true;
        if (this.value.length > 0) {
            checkSlugUniqueness(this.value, featureId, slugInput, slugStatus, checkSlugTimeout, function(timeout) {
                checkSlugTimeout = timeout;
            }, originalSlug);
        } else {
            slugStatus.innerHTML = '';
        }
    });
}

// Check slug uniqueness via AJAX
function checkSlugUniqueness(slug, featureId, slugInput, slugStatus, currentTimeout, setTimeoutCallback, originalSlug) {
    // Clear previous timeout
    if (currentTimeout) {
        clearTimeout(currentTimeout);
    }
    
    // If editing and slug is same as original, no need to check
    if (featureId && slug === originalSlug) {
        slugStatus.innerHTML = '';
        slugInput.classList.remove('is-invalid', 'is-valid');
        return;
    }
    
    // Debounce: wait 500ms after user stops typing
    var timeout = setTimeout(function() {
        if (slug.length === 0) {
            slugStatus.innerHTML = '';
            return;
        }
        
        slugStatus.innerHTML = '<small class="text-muted"><i class="isax isax-loading-1 isax-spin"></i> Checking...</small>';
        
        var url = '<?php echo base_url('erp-admin/features/check_slug/'); ?>' + encodeURIComponent(slug);
        if (featureId) {
            url += '/' + featureId;
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.available) {
                            slugStatus.innerHTML = '<small class="text-success"><i class="isax isax-tick-circle"></i> Available</small>';
                            slugInput.classList.remove('is-invalid');
                            slugInput.classList.add('is-valid');
                        } else {
                            var uniqueSlug = response.suggested_slug || slug + '-' + Math.floor(Math.random() * 1000);
                            slugStatus.innerHTML = '<small class="text-warning"><i class="isax isax-warning-2"></i> Not available. Suggested: <strong>' + uniqueSlug + '</strong></small>';
                            slugInput.classList.remove('is-valid');
                            slugInput.classList.add('is-invalid');
                            // Auto-update slug if it was auto-generated
                            if ((featureId && !window.editSlugManuallyEdited) || (!featureId && !window.addSlugManuallyEdited)) {
                                slugInput.value = uniqueSlug;
                                checkSlugUniqueness(uniqueSlug, featureId, slugInput, slugStatus, null, setTimeoutCallback, originalSlug);
                            }
                        }
                    } catch(e) {
                        slugStatus.innerHTML = '';
                    }
                } else {
                    slugStatus.innerHTML = '';
                }
            }
        };
        xhr.send();
    }, 500);
    
    setTimeoutCallback(timeout);
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return (text || '').toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Initialize toggle button styling for checkboxes
function initToggleButtons() {
    // Handle toggle buttons in add form
    var addToggleButtons = document.querySelectorAll('#offCanvasAdd .toggle-button');
    addToggleButtons.forEach(function(button) {
        var checkbox = button.querySelector('input[type="checkbox"]');
        if (checkbox) {
            // Set initial state
            if (checkbox.checked) {
                button.classList.add('active');
            }
            // Handle change events
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            });
        }
    });
    
    // Handle toggle buttons in edit form (will be initialized when form is populated)
    document.addEventListener('change', function(e) {
        if (e.target && e.target.type === 'checkbox' && e.target.closest('.toggle-button')) {
            var button = e.target.closest('.toggle-button');
            if (e.target.checked) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        }
    });
}

// Initialize toggle buttons on page load
document.addEventListener('DOMContentLoaded', function() {
    initToggleButtons();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeOffCanvas();
    }
});
</script>
