<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Login'; ?> - ERP System</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/template/css/bootstrap.min.css'); ?>">
    <!-- Iconsax CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/template/css/iconsax.css'); ?>">
    <style>
        body {
            background: var(--bg-app);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-container {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: var(--text-primary);
            font-size: var(--text-h1);
            margin-bottom: 0.5rem;
            font-family: var(--font-primary);
            font-weight: var(--fw-bold);
        }
        .login-header p {
            color: var(--text-secondary);
            font-size: var(--text-body);
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: var(--fw-medium);
            color: var(--text-primary);
            font-size: var(--text-body);
        }
        .error-message {
            background-color: #FEF2F2;
            color: #991B1B;
            padding: 0.75rem;
            border-radius: var(--radius);
            margin-bottom: 1.25rem;
            border: 1px solid #FECACA;
            font-size: var(--text-body);
        }
        .form-error {
            color: var(--danger);
            font-size: var(--text-small);
            margin-top: 0.25rem;
        }
        .info-message {
            background-color: #EFF6FF;
            color: #1E40AF;
            padding: 0.75rem;
            border-radius: var(--radius);
            margin-bottom: 1.25rem;
            border: 1px solid #BFDBFE;
            font-size: var(--text-body);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>ERP System</h1>
            <p>Multi-Tenant School Ecommerce ERP</p>
        </div>
        
        <?php if (isset($error) && !empty($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($debug_info) && !empty($debug_info)): ?>
            <div class="info-message">
                <?php echo $debug_info; ?>
            </div>
        <?php endif; ?>
        
        <?php echo form_open('auth/login'); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo set_value('username'); ?>" required autofocus>
                <?php echo form_error('username', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="position-relative">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('password')">
                        <i class="isax isax-eye" id="password-eye"></i>
                    </span>
                </div>
                <?php echo form_error('password', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        <?php echo form_close(); ?>
        
        <div class="text-center mt-3">
            <p class="text-muted fs-13">The system will automatically detect your user type and redirect you to the appropriate dashboard.</p>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            var input = document.getElementById(inputId);
            var eyeIcon = document.getElementById(inputId + '-eye');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('isax-eye');
                eyeIcon.classList.add('isax-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('isax-eye-slash');
                eyeIcon.classList.add('isax-eye');
            }
        }
    </script>
</body>
</html>

