<style>

    .banner-card {
        background: transparent !important;
    }
</style>


<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title"><?php echo $page_heading; ?></h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                <li class="breadcrumb-item active"><?php echo $page_heading; ?></li>
            </ul>
        </div>
    </div>
</div>

<!-- Settings Form -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Customize Your Live Site Appearance</h4>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('site-settings/save', array('id' => 'site-settings-form', 'class' => 'custom-form')); ?>

                <!-- Tab Navigation -->
                <div class="bookset-tabs-wrapper">
                    <ul class="nav nav-tabs bookset-tabs" id="site-settings-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                                <i class="fas fa-info-circle"></i>
                                <span>General</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab" aria-controls="branding" aria-selected="false">
                                <i class="fas fa-image"></i>
                                <span>Branding</span>
                            </button>
                        </li>
                       

                       
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false">
                                <i class="fas fa-search"></i>
                                <span>SEO</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced" type="button" role="tab" aria-controls="advanced" aria-selected="false">
                                <i class="fas fa-cogs"></i>
                                <span>Advanced</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner" type="button" role="tab" aria-controls="banner" aria-selected="false">
                                <i class="fas fa-image"></i>
                                <span>Banner</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content mt-4" id="site-settings-tab-content">
                    <!-- General Tab -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_title">Site Title</label>
                                    <input type="text" class="form-control" id="site_title" name="site_title"
                                           value="<?php echo isset($settings['site_title']) ? htmlspecialchars($settings['site_title']) : ''; ?>"
                                           placeholder="Enter your site title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_description">Site Description</label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="2"
                                              placeholder="Enter site description"><?php echo isset($settings['site_description']) ? htmlspecialchars($settings['site_description']) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branding Tab -->
                    <div class="tab-pane fade" id="branding" role="tabpanel" aria-labelledby="branding-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Site Logo</label>
                                    <input type="file" class="form-control form-control-sm" id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                                    <small class="form-text text-muted">Recommended: 200x60px, Max: 2MB</small>

                                    <!-- Current Logo Display -->
                                    <?php if (isset($settings['logo_path']) && !empty($settings['logo_path'])): ?>
                                        <div class="mt-2 p-2 border rounded bg-light">
                                            <small class="text-muted d-block mb-1">Current Logo:</small>
                                            <img src="<?php echo base_url($settings['logo_path']); ?>" alt="Current Logo" style="max-width: 150px; max-height: 45px;" class="border">
                                            <a href="<?php echo base_url('site-settings/delete-logo'); ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Are you sure you want to delete the logo?')">
                                                <i class="fas fa-trash"></i> Remove
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Logo Preview -->
                                    <div id="logo-preview" class="mt-2" style="display: none;">
                                        <small class="text-muted d-block mb-1">New Logo Preview:</small>
                                        <img id="logo-preview-img" src="" alt="Logo Preview" style="max-width: 150px; max-height: 45px;" class="border rounded">
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="clearLogoPreview()">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="favicon">Favicon</label>
                                    <input type="file" class="form-control form-control-sm" id="favicon" name="favicon" accept=".ico,.png,.gif,.jpg" onchange="previewFavicon(this)">
                                    <small class="form-text text-muted">Recommended: 32x32px, Max: 512KB</small>

                                    <!-- Current Favicon Display -->
                                    <?php if (isset($settings['favicon_path']) && !empty($settings['favicon_path'])): ?>
                                        <div class="mt-2 p-2 border rounded bg-light">
                                            <small class="text-muted d-block mb-1">Current Favicon:</small>
                                            <img src="<?php echo base_url($settings['favicon_path']); ?>" alt="Current Favicon" style="width: 32px; height: 32px;" class="border rounded">
                                            <a href="<?php echo base_url('site-settings/delete-favicon'); ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Are you sure you want to delete the favicon?')">
                                                <i class="fas fa-trash"></i> Remove
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Favicon Preview -->
                                    <div id="favicon-preview" class="mt-2" style="display: none;">
                                        <small class="text-muted d-block mb-1">New Favicon Preview:</small>
                                        <img id="favicon-preview-img" src="" alt="Favicon Preview" style="width: 32px; height: 32px;" class="border rounded">
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="clearFaviconPreview()">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                 
                    <!-- SEO Tab -->
                    <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="meta_title" class="form-label">
                                        Meta Title
                                        <span class="badge bg-info ms-1" id="title-count">0/60</span>
                                    </label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                           value="<?php echo isset($settings['meta_title']) ? htmlspecialchars($settings['meta_title']) : ''; ?>"
                                           placeholder="Enter page title for search engines"
                                           maxlength="255">
                                    <small class="form-text text-muted">Appears in search results and browser tabs.</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="meta_keywords" class="form-label">
                                        Meta Keywords
                                        <span class="badge bg-info ms-1" id="keywords-count">0</span>
                                    </label>
                                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="2"
                                              placeholder="keyword1, keyword2, keyword3"><?php echo isset($settings['meta_keywords']) ? htmlspecialchars($settings['meta_keywords']) : ''; ?></textarea>
                                    <small class="form-text text-muted">Comma-separated keywords for SEO.</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="meta_description" class="form-label">
                                        Meta Description
                                        <span class="badge bg-info ms-1" id="description-count">0/160</span>
                                    </label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"
                                              placeholder="Brief description for search engines"><?php echo isset($settings['meta_description']) ? htmlspecialchars($settings['meta_description']) : ''; ?></textarea>
                                    <small class="form-text text-muted">Appears under your page title in search results.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Tab -->
                    <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3">Additional Options</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                               <?php echo (isset($settings['is_active']) && $settings['is_active']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_active">
                                            Enable custom site settings
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">When enabled, these settings will be applied to your live site</small>
                                </div>
                            </div>
                        </div>
                    </div>
                                
                    <!-- Banner Tab -->
                    <div class="tab-pane fade" id="banner" role="tabpanel" aria-labelledby="banner-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3">Manage Banners</h5>
                                <p class="text-muted">Upload multiple banner images for your site. Recommended: 1200x400px, Max: 5MB each.</p>
                            </div>
                        </div>
                                        
                        <!-- Upload New Banner Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Add New Banner</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="banner_image">Banner Image(s)</label>
                                            <input type="file" class="form-control form-control-sm" id="banner_image" name="banner_image" accept="image/*" onchange="previewBanner(this)" multiple>
                                            <small class="form-text text-muted">Select one or more image files to upload as banners. Recommended: 1200x400px, Max: 5MB each.</small>
                                                            
                                            <!-- Banner Preview -->
                                            <div id="banner-preview" class="mt-2" style="display: none;">
                                                <small class="text-muted d-block mb-1">Banner Preview:</small>
                                                <img id="banner-preview-img" src="" alt="Banner Preview" style="max-width: 100%; max-height: 200px;" class="border rounded">
                                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="clearBannerPreview()">
                                                    <i class="fas fa-times"></i> Clear
                                                </button>
                                            </div>
                                        </div>
                                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="banner_alt_text">Alt Text</label>
                                                    <input type="text" class="form-control form-control-sm" id="banner_alt_text" name="banner_alt_text" placeholder="Alternative text for accessibility">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="banner_caption">Caption</label>
                                                    <input type="text" class="form-control form-control-sm" id="banner_caption" name="banner_caption" placeholder="Caption for the banner">
                                                </div>
                                            </div>
                                        </div>
                                                        
                                        <div class="form-group mt-3">
                                            <label for="banner_sort_order">Display Order</label>
                                            <input type="number" class="form-control form-control-sm" id="banner_sort_order" name="banner_sort_order" min="0" value="0" placeholder="Sort order (lower numbers appear first)">
                                        </div>
                                                        
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="banner_is_active" name="banner_is_active" value="1" checked>
                                            <label class="form-check-label" for="banner_is_active">
                                                Make this banner active
                                            </label>
                                        </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" id="upload-banner-btn" onclick="uploadBanner()">
                                            <i class="fas fa-upload"></i> Upload Banner
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        
                        <!-- Existing Banners List -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Current Banners</h6>
                                <div id="existing-banners-list">
                                    <?php 
                                    // Get all banners for this vendor
                                    $banners = isset($banners) ? $banners : array();
                                    if (!empty($banners)): 
                                        echo '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">';
                                        foreach($banners as $banner):
                                    ?>
                                    <div class="col banner-item" data-banner-id="<?php echo $banner['id']; ?>">
                                        <div class="card h-100 banner-card" style="background: #f8f9fa !important; border: 1px solid #e9ecef !important;">
                                            <img src="<?php echo base_url($banner['banner_image']); ?>" alt="<?php echo htmlspecialchars($banner['alt_text']); ?>" class="card-img-top banner-preview">
                                            <div class="card-body p-2">
                                                <h6 class="card-title text-truncate" title="<?php echo htmlspecialchars($banner['caption']); ?>"><?php echo htmlspecialchars($banner['caption']); ?></h6>
                                                <div class="banner-meta text-muted small" style="padding-top: 5px; border-top: 1px solid #e9ecef; margin-top: 5px;">
                                                    <span class="badge bg-light text-dark border">#<?php echo $banner['sort_order']; ?></span>
                                                    <span class="badge <?php echo $banner['is_active'] ? 'bg-success' : 'bg-danger'; ?> ms-1">
                                                        <?php echo $banner['is_active'] ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-footer p-2 text-center" style="background: #f1f3f5 !important; border-top: 1px solid #e9ecef !important;">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-icon" title="Edit" onclick="editBanner(<?php echo $banner['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-icon" title="Delete" onclick="deleteBanner(<?php echo $banner['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                        endforeach;
                                        echo '</div>';
                                    else: 
                                    ?>
                                    <div class="alert alert-info mb-0">No banners uploaded yet. Add your first banner using the form above.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Edit Modal -->
                <div class="modal fade" id="bannerEditModal" tabindex="-1" aria-labelledby="bannerEditModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bannerEditModalLabel">Edit Banner</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="banner-preview-container text-center p-3" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 0.375rem;">
                                            <img id="modal_banner_image" src="" alt="Banner Preview" style="max-width: 100%; height: auto; max-height: 300px; object-fit: contain;" class="border rounded">
                                            <p class="text-muted mt-2">Current Banner Image</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="modal_banner_alt_text">Alt Text</label>
                                            <input type="text" class="form-control" id="modal_banner_alt_text" name="modal_banner_alt_text" placeholder="Alternative text for accessibility">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="modal_banner_caption">Caption</label>
                                            <input type="text" class="form-control" id="modal_banner_caption" name="modal_banner_caption" placeholder="Caption for the banner">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="modal_banner_sort_order">Display Order</label>
                                            <input type="number" class="form-control" id="modal_banner_sort_order" name="modal_banner_sort_order" min="0" value="0" placeholder="Sort order (lower numbers appear first)">
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="modal_banner_is_active" name="modal_banner_is_active" value="1">
                                            <label class="form-check-label" for="modal_banner_is_active">
                                                Make this banner active
                                            </label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="modal_new_banner_image">Replace Banner Image (optional)</label>
                                            <input type="file" class="form-control" id="modal_new_banner_image" name="modal_new_banner_image" accept="image/*">
                                            <small class="form-text text-muted">Leave empty to keep current image</small>
                                        </div>
                                        <input type="hidden" id="modal_banner_id" name="modal_banner_id">
                                                                                <input type="hidden" id="modal_banner_image_url" name="modal_banner_image_url">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveBannerChangesBtn">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" id="save-settings-btn">
                            <i class="fas fa-save"></i> Save All Settings
                        </button>
                        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Bookset-style Tab Navigation */
.bookset-tabs-wrapper {
    background: #ffffff;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 1.5rem;
    padding: 0;
}
.bookset-tabs {
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 0;
    padding-left: 0;
    padding-right: 0;
}
.bookset-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    font-size: 0.875rem;
    padding: 0.75rem 1.25rem;
    border: 1px solid transparent;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
    border-bottom: none;
    margin-bottom: -1px;
    transition: all 0.2s ease;
    background: transparent;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}
.bookset-tabs .nav-link:hover {
    color: #495057;
    background: #f8f9fa;
    border-color: #dee2e6 #dee2e6 transparent;
}
.bookset-tabs .nav-link.active {
    color: #ffffff;
    background: #3550dc;
    border-color: #dee2e6 #dee2e6 #ffffff;
    font-weight: 600;
    z-index: 1;
}
.bookset-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 1px;
    background: #ffffff;
}
.bookset-tabs .nav-link i {
    font-size: 1em;
}
.bookset-tabs .nav-link.active i {
    color: #ffffff;
}
.bookset-tabs .nav-item {
    margin-bottom: 0;
}

/* Tab Content */
.tab-content {
    padding: 1.5rem 0;
}

/* Form Styling */
.form-control {
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.form-control-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

/* Color Picker Styling */
.form-control-color {
    width: 50px;
    height: 36px;
    padding: 0;
    border: 2px solid #e9ecef;
    border-radius: 0.375rem;
    cursor: pointer;
}

.input-group-sm .form-control {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-left: 1px solid #ced4da;
    box-shadow: none;
}

/* Form Labels */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

/* Form Text */
.form-text {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.375rem;
}

.form-text.text-muted {
    color: #6c757d !important;
}

/* Current File Display */
.mt-1 img {
    border-radius: 4px;
    border: 1px solid #dee2e6;
}

/* Button Styling */
.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

/* Section Headers */
.mb-3 h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
}

/* Character Counters */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

/* Banner Card Styles */
.banner-card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: box-shadow 0.15s ease-in-out;
    border: 1px solid #e9ecef;
}

/* Override sidebar gradient for banner section */
[data-sidebarbg=sidebarbg1] .welcome-banner, [data-sidebarbg=sidebarbg1] [class*="banner"] {
    background: #f8f9fa !important;
}

.banner-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.banner-preview {
    height: 120px;
    object-fit: cover;
    border-bottom: 1px solid #e9ecef;
}

.banner-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-icon {
    padding: 0.25rem 0.5rem;
    margin: 0 0.1rem;
}

.btn-icon i {
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-tabs {
        flex-wrap: wrap;
    }

    .nav-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }

    .tab-content {
        padding: 1rem 0;
    }
}
</style>

<script>
// Define base URL for AJAX requests
var base_url = '<?php echo rtrim(base_url(), "/") . "/"; ?>';

// Wait for jQuery to be loaded
function initSiteSettings() {
    if (typeof jQuery === 'undefined') {
        // jQuery not loaded yet, wait and try again
        setTimeout(initSiteSettings, 100);
        return;
    }

    $(document).ready(function() {
        // Sync color picker with text input
        $('input[type="color"]').on('input', function() {
            const textInput = $(this).parent().find('input[type="text"]');
            if (textInput.length) {
                textInput.val(this.value);
            }
        });

        // Sync text input with color picker (for manual entry)
        $('.input-group input[type="text"]').on('input', function() {
            const colorInput = $(this).parent().find('input[type="color"]');
            if (colorInput.length && /^#[0-9A-F]{6}$/i.test(this.value)) {
                colorInput.val(this.value);
            }
        });

        // Character counters for SEO fields
        $('#meta_title').on('input', function() {
            const count = $(this).val().length;
            $('#title-count').text(count + '/60');
            $('#title-count').removeClass('bg-info bg-warning bg-danger');
            if (count > 50) {
                $('#title-count').addClass('bg-warning');
            }
            if (count > 60) {
                $('#title-count').addClass('bg-danger');
            } else {
                $('#title-count').addClass('bg-info');
            }
        });

        $('#meta_keywords').on('input', function() {
            const keywords = $(this).val().split(',').filter(k => k.trim().length > 0);
            $('#keywords-count').text(keywords.length);
        });

        $('#meta_description').on('input', function() {
            const count = $(this).val().length;
            $('#description-count').text(count + '/160');
            $('#description-count').removeClass('bg-info bg-warning bg-danger');
            if (count > 150) {
                $('#description-count').addClass('bg-warning');
            }
            if (count > 160) {
                $('#description-count').addClass('bg-danger');
            } else {
                $('#description-count').addClass('bg-info');
            }
        });

        // Initialize character counts
        $('#meta_title').trigger('input');
        $('#meta_keywords').trigger('input');
        $('#meta_description').trigger('input');
    });
}

// Initialize when page loads
initSiteSettings();

// Form submission debugging
function initFormDebugging() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initFormDebugging, 100);
        return;
    }

    $('#site-settings-form').on('submit', function(e) {
        console.log('Form submitted');

        // Check logo file input
        var logoInput = $('#logo')[0];
        if (logoInput && logoInput.files && logoInput.files.length > 0) {
            console.log('Logo file selected:', logoInput.files[0].name, 'Size:', logoInput.files[0].size, 'Type:', logoInput.files[0].type);
        } else {
            console.log('No logo file selected');
        }

        // Check favicon file input
        var faviconInput = $('#favicon')[0];
        if (faviconInput && faviconInput.files && faviconInput.files.length > 0) {
            console.log('Favicon file selected:', faviconInput.files[0].name, 'Size:', faviconInput.files[0].size, 'Type:', faviconInput.files[0].type);
        } else {
            console.log('No favicon file selected');
        }

        // Show loading state
        $('#save-settings-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    });
}

initFormDebugging();

// Logo preview function
function previewLogo(input) {
    if (typeof jQuery === 'undefined') {
        setTimeout(function() { previewLogo(input); }, 100);
        return;
    }

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file.');
            input.value = '';
            return;
        }

        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            $('#logo-preview-img').attr('src', e.target.result);
            $('#logo-preview').show();
        };
        reader.readAsDataURL(file);
    }
}

// Clear logo preview
function clearLogoPreview() {
    if (typeof jQuery === 'undefined') {
        setTimeout(clearLogoPreview, 100);
        return;
    }

    $('#logo').val('');
    $('#logo-preview').hide();
    $('#logo-preview-img').attr('src', '');
}

// Favicon preview function
function previewFavicon(input) {
    if (typeof jQuery === 'undefined') {
        setTimeout(function() { previewFavicon(input); }, 100);
        return;
    }

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file.');
            input.value = '';
            return;
        }

        // Validate file size (512KB max)
        if (file.size > 512 * 1024) {
            alert('File size must be less than 512KB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            $('#favicon-preview-img').attr('src', e.target.result);
            $('#favicon-preview').show();
        };
        reader.readAsDataURL(file);
    }
}

// Clear favicon preview
function clearFaviconPreview() {
    if (typeof jQuery === 'undefined') {
        setTimeout(clearFaviconPreview, 100);
        return;
    }

    $('#favicon').val('');
    $('#favicon-preview').hide();
    $('#favicon-preview-img').attr('src', '');
}

// Banner preview function
function previewBanner(input) {
    if (typeof jQuery === 'undefined') {
        setTimeout(function() { previewBanner(input); }, 100);
        return;
    }

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file.');
            input.value = '';
            return;
        }

        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            $('#banner-preview-img').attr('src', e.target.result);
            $('#banner-preview').show();
        };
        reader.readAsDataURL(file);
    }
}

// Clear banner preview
function clearBannerPreview() {
    if (typeof jQuery === 'undefined') {
        setTimeout(clearBannerPreview, 100);
        return;
    }

    $('#banner_image').val('');
    $('#banner-preview').hide();
    $('#banner-preview-img').attr('src', '');
}

// Edit banner function
function editBanner(bannerId) {
    if (typeof jQuery === 'undefined') {
        setTimeout(function() { editBanner(bannerId); }, 100);
        return;
    }
				
    // Show loading state
    $('#saveBannerChangesBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
    
    // Fetch banner data via AJAX
    $.get(base_url + 'site-settings/get-banner-data/' + bannerId, function(response) {
        if (response.status === 'success') {
            // Populate the modal with banner data
            var bannerImagePath = response.data.banner_image;
            // Check if banner image path is valid
            if (bannerImagePath && typeof bannerImagePath === 'string') {
                // The base_url includes the vendor domain (e.g., http://localhost/books_erp/vendor/)
                // But the image path is relative to the site root
                // So we need to construct the proper URL
                if (bannerImagePath.startsWith('http')) {
                    // If it's already a full URL, use it as is
                    $('#modal_banner_image').attr('src', bannerImagePath);
                } else {
                    // Construct the proper image URL by using the window origin and removing vendor part
                    var fullPageUrl = window.location.href;
                    var siteUrl = window.location.protocol + '//' + window.location.host + '/';
                    $('#modal_banner_image').attr('src', siteUrl + bannerImagePath);
                }
            } else {
                // Set a placeholder or empty image if path is invalid
                $('#modal_banner_image').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            }
            $('#modal_banner_alt_text').val(response.data.alt_text);
            $('#modal_banner_caption').val(response.data.caption);
            $('#modal_banner_sort_order').val(response.data.sort_order);
            $('#modal_banner_is_active').prop('checked', response.data.is_active == 1);
            $('#modal_banner_id').val(bannerId);
            
            // Reset the file input
            $('#modal_new_banner_image').val('');
            
            // Open the modal
            $('#bannerEditModal').modal('show');
            
            // Re-enable button
            $('#saveBannerChangesBtn').prop('disabled', false).html('Save Changes');
        } else {
            alert('Error: ' + response.message);
            $('#saveBannerChangesBtn').prop('disabled', false).html('Save Changes');
        }
    }, 'json').fail(function() {
        alert('An error occurred while fetching banner data.');
        $('#saveBannerChangesBtn').prop('disabled', false).html('Save Changes');
    });
}

// Delete banner function
function deleteBanner(bannerId) {
    if (confirm('Are you sure you want to delete this banner?')) {
        // Send AJAX request to delete the banner
        $.post(base_url + 'site-settings/delete-banner-ajax/' + bannerId, function(response) {
            if (response.status === 'success') {
                // Remove the banner item from the list
                $('[data-banner-id=' + bannerId + ']').fadeOut(function() {
                    $(this).remove();
                    // Show message if no banners left
                    if ($('#existing-banners-list .banner-item').length === 0) {
                        $('#existing-banners-list').html('<div class="alert alert-info">No banners uploaded yet. Add your first banner using the form above.</div>');
                    }
                });
                alert(response.message);
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json').fail(function() {
            alert('An error occurred while trying to delete the banner.');
        });
    }
}

// Upload banner function
function uploadBanner() {
    var formData = new FormData();
    
    // Check if we're updating an existing banner
    var bannerIdForEdit = $('#banner_id_for_edit').val();
    var isUpdate = bannerIdForEdit && bannerIdForEdit.length > 0;
    
    // Get the selected files
    var bannerFiles = document.getElementById('banner_image').files;
    
    // For updates, we might not have new files
    if (!isUpdate && bannerFiles.length === 0) {
        alert('Please select at least one banner image to upload.');
        return;
    }
    
    // Add each file to the form data (only for new uploads or if files are selected for update)
    if (bannerFiles.length > 0) {
        for (var i = 0; i < bannerFiles.length; i++) {
            var file = bannerFiles[i];
            
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select a valid image file for banner ' + (i+1) + '.');
                return;
            }
            
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size for banner ' + (i+1) + ' must be less than 5MB.');
                return;
            }
            
            // Add file to form data
            formData.append('banner_images[]', file);
        }
    }
    
    // Add other form fields
    formData.append('alt_text', $('#banner_alt_text').val());
    formData.append('caption', $('#banner_caption').val());
    formData.append('sort_order', $('#banner_sort_order').val());
    formData.append('is_active', $('#banner_is_active').is(':checked') ? 1 : 0);
    
    // Add banner ID if updating
    if (isUpdate) {
        formData.append('banner_id', bannerIdForEdit);
    }  
    
    // Disable the upload button to prevent multiple clicks
    $('#upload-banner-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> ' + (isUpdate ? 'Updating...' : 'Uploading...'));
    
    // Send AJAX request to upload/update the banner
    $.ajax({
        url: base_url + 'site-settings/' + (isUpdate ? 'update-banner-ajax' : 'add-banner-ajax'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                // Reset the form only if it was a new upload or files were provided
                if (!isUpdate || bannerFiles.length > 0) {
                    $('#banner_image').val('');
                    clearBannerPreview();
                }
                
                // Reset form fields if it was an update
                if (isUpdate) {
                    $('#banner_alt_text').val('');
                    $('#banner_caption').val('');
                    $('#banner_sort_order').val('0');
                    $('#banner_is_active').prop('checked', true);
                    $('#banner_id_for_edit').remove();
                    $('#upload-banner-btn').html('<i class="fas fa-upload"></i> Upload Banner');
                }
                
                // Reload the banners list
                loadBannersList();
                
                alert(response.message);
            } else {
                alert('Error: ' + response.message);
            }
            
            // Re-enable the upload button
            $('#upload-banner-btn').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload Banner');
        },
        error: function(xhr, status, error) {
            console.error('Banner operation error:', xhr.responseText);
            alert('An error occurred while ' + (isUpdate ? 'updating' : 'uploading') + ' the banner: ' + error);
            
            // Re-enable the upload button
            $('#upload-banner-btn').prop('disabled', false).html((isUpdate ? '<i class="fas fa-upload"></i> Upload Banner' : '<i class="fas fa-upload"></i> Upload Banner'));
        }
    });
}

// Function to load banners list
function loadBannersList() {
    // Reload the page to update the banner list
    location.reload();
}

// Save banner changes from modal
function saveBannerChanges() {
    if (typeof jQuery === 'undefined') {
        setTimeout(saveBannerChanges, 100);
        return;
    }
    
    var bannerId = $('#modal_banner_id').val();
    var formData = new FormData();
    
    // Add form fields
    formData.append('alt_text', $('#modal_banner_alt_text').val());
    formData.append('caption', $('#modal_banner_caption').val());
    formData.append('sort_order', $('#modal_banner_sort_order').val());
    formData.append('is_active', $('#modal_banner_is_active').is(':checked') ? 1 : 0);
    formData.append('banner_id', bannerId);
    
    // Add new image file if provided
    var newImageFile = document.getElementById('modal_new_banner_image').files[0];
    if (newImageFile) {
        // Validate file type
        if (!newImageFile.type.match('image.*')) {
            alert('Please select a valid image file.');
            return;
        }
        
        // Validate file size (5MB max)
        if (newImageFile.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            return;
        }
        
        formData.append('banner_images[]', newImageFile);
    } else {
        // If no new image is provided, still send an empty flag to distinguish from regular updates
        formData.append('no_new_image', '1');
    }
    
    // Disable the save button to prevent multiple clicks
    $('#saveBannerChangesBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    
    // Send AJAX request to update the banner
    $.ajax({
        url: base_url + 'site-settings/update-banner-ajax',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                // Close the modal
                $('#bannerEditModal').modal('hide');
                
                // Reload the banners list
                loadBannersList();
                
                alert(response.message);
            } else {
                alert('Error: ' + response.message);
            }
            
            // Re-enable the save button
            $('#saveBannerChangesBtn').prop('disabled', false).html('Save Changes');
        },
        error: function(xhr, status, error) {
            console.error('Banner update error:', xhr.responseText);
            alert('An error occurred while updating the banner: ' + error);
            
            // Re-enable the save button
            $('#saveBannerChangesBtn').prop('disabled', false).html('Save Changes');
        }
    });
}

// Update the banner form to use our custom upload function instead of submitting the main form
$(document).ready(function() {
    // Prevent the banner form from being submitted with the main form
    $('#banner_image').closest('form').find('button[type="submit"], input[type="submit"]').click(function(e) {
        // Don't submit banner image with the main form
        if ($(this).closest('#site-settings-form').length) {
            // Temporarily remove banner image from form submission
            $('#banner_image').attr('name', '');
            
            // Restore after a brief moment
            setTimeout(function() {
                $('#banner_image').attr('name', 'banner_image');
            }, 100);
        }
    });
    
    // Handle save banner changes button click
    $('#saveBannerChangesBtn').click(function() {
        saveBannerChanges();
    });
});
</script>
