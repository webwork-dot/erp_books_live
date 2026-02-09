<?php
$data = array();
$vars = get_defined_vars();
foreach ($vars as $key => $value) {
	if (!in_array($key, array('CI', 'this', 'data', 'view_data', 'vars'))) {
		$data[$key] = $value;
	}
}
?>
<?php $this->load->view('school_admin/layouts/header_template', $data); ?>
<?php $this->load->view('school_admin/layouts/sidebar_template', $data); ?>

<div class="page-wrapper">
	<div class="content">
		<?php 
		$flash_success = $this->session->flashdata('success');
		$flash_error = $this->session->flashdata('error');
		
		if ($flash_success) {
			$this->session->unmark_flash('success');
			$this->session->unset_userdata('success');
		}
		if ($flash_error) {
			$this->session->unmark_flash('error');
			$this->session->unset_userdata('error');
		}
		
		if ($flash_error): ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert" id="flash-alert" data-alert-id="<?php echo md5($flash_error . time()); ?>">
				<?php echo htmlspecialchars($flash_error); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php elseif ($flash_success): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert" id="flash-alert" data-alert-id="<?php echo md5($flash_success . time()); ?>">
				<?php echo htmlspecialchars($flash_success); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>
		<?php echo isset($content) ? $content : ''; ?>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var alert = document.getElementById('flash-alert');
	if (alert) {
		var alertId = alert.getAttribute('data-alert-id');
		var storageKey = 'alert_shown_' + alertId;
		
		if (localStorage.getItem(storageKey)) {
			alert.remove();
			return;
		}
		
		localStorage.setItem(storageKey, '1');
		
		setTimeout(function() {
			if (alert && alert.parentNode) {
				var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
				bsAlert.close();
			}
		}, 5000);
		
		alert.addEventListener('closed.bs.alert', function() {
			setTimeout(function() {
				localStorage.removeItem(storageKey);
			}, 1000);
		});
		
		var closeBtn = alert.querySelector('.btn-close');
		if (closeBtn) {
			closeBtn.addEventListener('click', function() {
				setTimeout(function() {
					localStorage.removeItem(storageKey);
				}, 1000);
			});
		}
		
		var now = Date.now();
		for (var i = 0; i < localStorage.length; i++) {
			var key = localStorage.key(i);
			if (key && key.startsWith('alert_shown_')) {
				var timestamp = localStorage.getItem(key);
				if (timestamp && (now - parseInt(timestamp)) > 300000) {
					localStorage.removeItem(key);
				}
			}
		}
	}
});
</script>

<?php $this->load->view('school_admin/layouts/footer_template', $data); ?>
