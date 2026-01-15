<?php
/**
 * Main Layout Template for ERP Admin
 * 
 * This file includes header, sidebar, content, and footer
 * All pages should use this layout
 */
// Reconstruct $data array from extracted variables
// CodeIgniter automatically extracts the data array keys as variables
// So we need to reconstruct it for passing to child views
$data = array();
// Get all defined variables
$vars = get_defined_vars();
// Filter out system variables and reconstruct data array
foreach ($vars as $key => $value) {
	// Skip CodeIgniter system variables
	if (!in_array($key, array('CI', 'this', 'data', 'view_data', 'vars'))) {
		$data[$key] = $value;
	}
}
?>
<?php $this->load->view('erp_admin/layouts/header_template', $data); ?>
<?php $this->load->view('erp_admin/layouts/sidebar_template', $data); ?>

<div class="page-wrapper">
	<div class="content">
		<?php 
		// Get flashdata and immediately clear it to prevent showing on refresh
		$flash_success = $this->session->flashdata('success');
		$flash_error = $this->session->flashdata('error');
		
		// Immediately clear flashdata from session to prevent it from showing again
		// CodeIgniter stores flashdata in flash:new: and flash:old: keys
		if ($flash_success || $flash_error) {
			// Clear all flashdata keys
			$this->session->unset_userdata('flash:new:success');
			$this->session->unset_userdata('flash:old:success');
			$this->session->unset_userdata('flash:new:error');
			$this->session->unset_userdata('flash:old:error');
		}
		
		// Only show one alert at a time - prioritize error over success
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
// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
	var alert = document.getElementById('flash-alert');
	if (alert) {
		var alertId = alert.getAttribute('data-alert-id');
		var storageKey = 'alert_shown_' + alertId;
		
		// Check if this specific alert was already shown (using unique ID)
		if (localStorage.getItem(storageKey)) {
			// Already shown, hide it immediately
			alert.remove();
			return;
		}
		
		// Mark this alert as shown
		localStorage.setItem(storageKey, Date.now().toString());
		
		// Auto-dismiss after 5 seconds
		setTimeout(function() {
			if (alert && alert.parentNode) {
				var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
				bsAlert.close();
			}
		}, 5000);
		
		// Clean up localStorage after alert is closed
		alert.addEventListener('closed.bs.alert', function() {
			setTimeout(function() {
				localStorage.removeItem(storageKey);
			}, 1000);
		});
		
		// Also clean up on manual close
		var closeBtn = alert.querySelector('.btn-close');
		if (closeBtn) {
			closeBtn.addEventListener('click', function() {
				setTimeout(function() {
					localStorage.removeItem(storageKey);
				}, 1000);
			});
		}
		
		// Clean up old localStorage entries (older than 5 minutes)
		var now = Date.now();
		for (var i = 0; i < localStorage.length; i++) {
			var key = localStorage.key(i);
			if (key && key.startsWith('alert_shown_')) {
				var timestamp = localStorage.getItem(key);
				if (timestamp && (now - parseInt(timestamp)) > 300000) { // 5 minutes
					localStorage.removeItem(key);
				}
			}
		}
	}
});
</script>

<?php $this->load->view('erp_admin/layouts/footer_template', $data); ?>

