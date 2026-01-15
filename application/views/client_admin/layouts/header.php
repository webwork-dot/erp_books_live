<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Client Admin'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: #F8F9FA;
            border-right: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: var(--white);
        }
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }
        .logo-img {
            height: 50px;
            width: auto;
        }
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        .logo {
            font-size: 20px;
            font-weight: var(--fw-bold);
            color: var(--primary);
            font-family: var(--font-primary);
            line-height: 1.2;
        }
        .logo-subtitle {
            font-size: 11px;
            color: var(--text-secondary);
            font-weight: var(--fw-normal);
            margin-top: 2px;
        }
        .sidebar-nav {
            flex: 1;
            padding: 0.75rem 0;
            overflow-y: auto;
            overflow-x: hidden;
            background: var(--white);
        }
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--border-dark);
            border-radius: 3px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
            padding: 0;
        }
        .nav-menu-item {
            position: relative;
        }
        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.15s ease;
            font-family: var(--font-primary);
            font-weight: var(--fw-medium);
            font-size: 14px;
            position: relative;
            border-left: 4px solid transparent;
        }
        .nav-menu a i {
            width: 20px;
            font-size: 16px;
            margin-right: 12px;
            text-align: center;
            color: #6C757D;
        }
        .nav-menu a .menu-arrow {
            margin-left: auto;
            font-size: 12px;
            color: #6C757D;
            transition: transform 0.15s ease;
        }
        .nav-menu a:hover {
            color: var(--primary);
            background-color: var(--primary-soft);
            text-decoration: none;
            border-left-color: var(--primary);
        }
        .nav-menu a:hover i {
            color: var(--primary);
        }
        .nav-menu a:hover .menu-arrow {
            color: var(--primary);
        }
        .menu-active {
            background: var(--primary-soft) !important;
            color: var(--primary) !important;
            font-weight: var(--fw-semibold);
            border-left-color: var(--primary) !important;
        }
        .menu-active i {
            color: var(--primary) !important;
        }
        .menu-active .menu-arrow {
            color: var(--primary) !important;
        }
        .nav-submenu {
            display: none;
            background: #F8F9FA;
            padding: 0.5rem 0;
            margin-left: 0;
        }
        .nav-submenu.active {
            display: block;
        }
        .nav-submenu a {
            padding: 0.625rem 1.25rem 0.625rem 3.5rem;
            font-size: 13px;
            color: #6C757D;
        }
        .nav-submenu a::before {
            content: '○';
            margin-right: 10px;
            font-size: 6px;
            color: #6C757D;
        }
        .nav-submenu a:hover {
            color: var(--primary);
            background-color: #E8F0FF;
        }
        .nav-submenu a:hover::before {
            color: var(--primary);
        }
        .nav-submenu .menu-active {
            color: var(--primary) !important;
            background-color: #E8F0FF !important;
        }
        .nav-submenu .menu-active::before {
            color: var(--primary) !important;
        }
        .sidebar-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border-light);
            background: var(--white);
        }
        .user-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: var(--text-body);
        }
        .user-info span {
            font-weight: var(--fw-semibold);
            color: var(--text-primary);
            font-size: 14px;
        }
        .user-info a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: var(--fw-medium);
            transition: color 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 13px;
        }
        .user-info a:hover {
            color: var(--primary);
        }
        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background: var(--bg-header);
            border-bottom: 1px solid var(--border-light);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-logo-container {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }
        .header-logo-img {
            height: 36px;
            width: auto;
        }
        .header-logo {
            font-size: var(--text-h2);
            font-weight: var(--fw-semibold);
            color: var(--primary);
            font-family: var(--font-primary);
            letter-spacing: -0.3px;
        }
        .header-user-info {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            color: var(--text-secondary);
            font-size: var(--text-body);
        }
        .header-user-info span {
            color: var(--text-secondary);
            font-weight: var(--fw-medium);
        }
        .content {
            padding: 1.5rem;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <div class="logo"><?php echo isset($tenant['name']) ? htmlspecialchars($tenant['name']) : 'Client Admin'; ?></div>
                    <div class="logo-subtitle">Admin Panel</div>
                </div>
            </div>
        </div>
        <div class="sidebar-nav">
            <div class="nav-menu">
                <div class="nav-menu-item">
                    <a href="<?php echo base_url('client-admin/dashboard'); ?>" class="<?php echo (uri_string() == 'client-admin/dashboard' || uri_string() == 'client-admin') ? 'menu-active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="nav-menu-item">
                    <a href="<?php echo base_url('client-admin/products'); ?>" class="<?php echo (strpos(uri_string(), 'client-admin/products') !== false) ? 'menu-active' : ''; ?>">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </div>
                <div class="nav-menu-item">
                    <a href="<?php echo base_url('client-admin/orders'); ?>" class="<?php echo (strpos(uri_string(), 'client-admin/orders') !== false) ? 'menu-active' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </div>
                <div class="nav-menu-item">
                    <a href="<?php echo base_url('client-admin/schools'); ?>" class="<?php echo (strpos(uri_string(), 'client-admin/schools') !== false) ? 'menu-active' : ''; ?>">
                        <i class="fas fa-school"></i>
                        <span>Schools</span>
                    </a>
                </div>
                <div class="nav-menu-item">
                    <a href="<?php echo base_url('client-admin/settings'); ?>" class="<?php echo (strpos(uri_string(), 'client-admin/settings') !== false) ? 'menu-active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-footer">
            <div class="user-info">
                <span><?php echo isset($current_user['username']) ? htmlspecialchars($current_user['username']) : 'User'; ?></span>
                <a href="<?php echo base_url('client-admin/auth/logout'); ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="header">
            <div class="header-content">
                <div class="header-logo-container">
                    <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="Logo" class="header-logo-img">
                    <div class="header-logo"><?php echo isset($tenant['name']) ? htmlspecialchars($tenant['name']) : 'Client Admin'; ?></div>
                </div>
                <div class="header-user-info">
                    <span>Welcome, <?php echo isset($current_user['username']) ? htmlspecialchars($current_user['username']) : 'User'; ?></span>
                </div>
            </div>
        </div>
        <div class="content">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-error"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
