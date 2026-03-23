<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/template/css/iconsax.css'); ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        .login-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 24px;
            font-weight: 500;
            color: #212529;
            margin-bottom: 0.75rem;
            letter-spacing: -0.3px;
        }
        
        .login-header p {
            font-size: 14px;
            color: #6c757d;
            font-weight: 400;
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 400;
            color: #495057;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 14px;
            line-height: 1.5;
            color: #212529;
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 3px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            font-family: inherit;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #495057;
            box-shadow: 0 0 0 2px rgba(73, 80, 87, 0.1);
        }
        
        .form-control::placeholder {
            color: #adb5bd;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.15s ease;
            z-index: 10;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .password-toggle:hover {
            color: #212529;
        }
        
        .password-toggle i {
            font-size: 16px;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            font-size: 14px;
            font-weight: 500;
            color: #ffffff;
            background-color: #495057;
            border: 1px solid #495057;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            background-color: #3d4146;
            border-color: #3d4146;
        }
        
        .btn-login:active {
            background-color: #343a40;
            border-color: #343a40;
        }
        
        .error-message {
            background-color: #fff5f5;
            color: #c92a2a;
            padding: 0.75rem;
            border-radius: 3px;
            margin-bottom: 1.25rem;
            border: 1px solid #ffe0e0;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .info-message {
            background-color: #f0f7ff;
            color: #1864ab;
            padding: 0.75rem;
            border-radius: 3px;
            margin-bottom: 1.25rem;
            border: 1px solid #d0e7ff;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .form-error {
            color: #c92a2a;
            font-size: 12px;
            margin-top: 0.375rem;
        }
        
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .login-container {
                padding: 2rem 1.5rem;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Welcome</h1>
            <p>Please sign in to continue</p>
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
        
        <?php echo form_open('auth/login', ['class' => 'login-form']); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    class="form-control" 
                    placeholder="Enter your username"
                    value="<?php echo set_value('username'); ?>" 
                    required 
                    autofocus
                >
                <?php echo form_error('username', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control" 
                        placeholder="Enter your password"
                        required
                    >
                    <span class="password-toggle" onclick="togglePassword('password')">
                        <i class="isax isax-eye" id="password-eye"></i>
                    </span>
                </div>
                <?php echo form_error('password', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <button type="submit" class="btn-login">
                Sign In
            </button>
        <?php echo form_close(); ?>
    </div>
    
    <script>
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

