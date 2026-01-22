<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Home'; ?> - <?php echo isset($tenant['name']) ? htmlspecialchars($tenant['name']) : 'ERP'; ?></title>
    
    <?php 
    // Load common helper for dynamic logo and favicon
    $this->load->helper('common');
    
    // Get vendor site settings for favicon
    $vendor_settings = get_vendor_site_settings();
    $favicon_url = !empty($vendor_settings['favicon_path']) && file_exists(FCPATH . $vendor_settings['favicon_path']) 
        ? base_url($vendor_settings['favicon_path']) 
        : base_url('assets/template/img/favicon.png');
    ?>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo $favicon_url; ?>">
    
    <link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">
    <style>
        body {
            background: var(--bg-app);
        }
        .header {
            background: var(--bg-header);
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .logo-img {
            height: 50px;
            width: auto;
        }
        .header h1 {
            color: var(--primary);
            margin: 0;
            font-family: var(--font-primary);
            font-weight: var(--fw-bold);
            font-size: var(--text-h1);
        }
        .content {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }
        .welcome-box {
            background: var(--bg-card);
            padding: 3rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }
        .welcome-box h1 {
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-family: var(--font-primary);
            font-weight: var(--fw-bold);
            font-size: var(--text-h1);
        }
        .welcome-box p {
            color: var(--text-secondary);
            font-size: var(--text-h3);
            margin-bottom: 2rem;
        }
        .links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <?php 
            // Get dynamic logo from vendor site settings
            $logo_url = get_dynamic_logo('header');
            ?>
            <img src="<?php echo $logo_url; ?>" alt="Logo" class="logo-img" onerror="this.src='<?php echo base_url('assets/template/img/logo.svg'); ?>';">
            <h1><?php echo isset($tenant['name']) ? htmlspecialchars($tenant['name']) : 'Multi-Tenant ERP System'; ?></h1>
        </div>
    </div>
    <div class="content">
        <div class="welcome-box">
            <h1>Welcome to <?php echo isset($tenant['name']) ? htmlspecialchars($tenant['name']) : 'ERP System'; ?></h1>
            <p>Multi-Tenant School Ecommerce ERP System</p>
            <div class="links">
                <a href="<?php echo site_url('erp-admin/auth/login'); ?>" class="btn btn-primary">Super Admin</a>
                <?php if (isset($tenant) && $tenant): ?>
                    <a href="<?php echo site_url('client-admin/auth/login'); ?>" class="btn btn-secondary">Client Admin</a>
                <?php endif; ?>
            </div>
            <?php if (!isset($tenant) || !$tenant): ?>
                <p style="margin-top: 1.5rem; color: var(--text-muted); font-size: var(--text-body);">
                    No tenant configured. Please login as Super Admin to create a vendor.
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
