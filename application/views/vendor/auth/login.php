<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo isset($title) ? $title : 'Vendor Login'; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/bootstrap.min.css'); ?>">
	
	<!-- Iconsax CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/iconsax.css'); ?>">
	
	<!-- Main CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/template/css/style.css'); ?>">
	
	<!-- Custom Theme CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/theme.css'); ?>">
	
	<style>
		.login-wrapper {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: #f8f9fa;
		}
		.login-card {
			max-width: 400px;
			width: 100%;
		}
	</style>
</head>
<body>
	<div class="login-wrapper">
		<div class="login-card">
			<div class="card">
				<div class="card-body p-4">
					<div class="text-center mb-4">
						<h4 class="mb-2"><?php echo isset($vendor['name']) ? htmlspecialchars($vendor['name']) : 'Vendor'; ?> Login</h4>
						<p class="text-muted">Enter your credentials to access your dashboard</p>
					</div>
					
					<?php if ($this->session->flashdata('error')): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?php echo $this->session->flashdata('error'); ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php endif; ?>
					
					<?php if ($this->session->flashdata('success')): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?php echo $this->session->flashdata('success'); ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php endif; ?>
					
					<?php echo form_open(isset($vendor['domain']) ? $vendor['domain'] . '/login' : 'login'); ?>
						<div class="mb-3">
							<label class="form-label">Username <span class="text-danger">*</span></label>
							<input type="text" name="username" class="form-control" value="<?php echo set_value('username'); ?>" required autofocus>
							<?php echo form_error('username', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
						
						<div class="mb-3">
							<label class="form-label">Password <span class="text-danger">*</span></label>
							<div class="position-relative">
								<input type="password" name="password" id="password" class="form-control" required>
								<span class="position-absolute end-0 top-50 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('password')">
									<i class="isax isax-eye" id="password-eye"></i>
								</span>
							</div>
							<?php echo form_error('password', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
						
						<div class="d-grid">
							<button type="submit" class="btn btn-primary">Login</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	
	<!-- jQuery -->
	<script src="<?php echo base_url('assets/template/js/jquery-3.7.1.min.js'); ?>"></script>
	
	<!-- Bootstrap Core JS -->
	<script src="<?php echo base_url('assets/template/js/bootstrap.bundle.min.js'); ?>"></script>
	
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

