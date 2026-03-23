<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Multi-Tenant ERP System</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">
    <style>
        body {
            background: var(--bg-secondary);
        }
        .header {
            background: var(--white);
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }
        .header h1 {
            color: var(--primary-color);
            margin: 0;
        }
        .content {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }
        .welcome-box {
            background: var(--white);
            padding: 3rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            text-align: center;
        }
        .welcome-box h1 {
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        .welcome-box p {
            color: var(--text-secondary);
            font-size: 1.125rem;
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
        <h1>Multi-Tenant ERP System</h1>
    </div>
    <div class="content">
        <div class="welcome-box">
            <h1>Welcome to Multi-Tenant School Ecommerce ERP</h1>
            <p>Your complete solution for managing school ecommerce operations</p>
            <div class="links">
                <a href="<?php echo site_url('erp-admin/auth/login'); ?>" class="btn btn-primary">Super Admin</a>
            </div>
            <p style="margin-top: 1.5rem; color: var(--text-muted); font-size: 0.875rem;">
                Please login as Super Admin to create a client and get started.
            </p>
        </div>
    </div>
</body>
</html>

