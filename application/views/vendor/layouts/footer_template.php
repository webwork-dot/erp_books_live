	<!-- Start Footer -->
	<div class="footer d-sm-flex align-items-center justify-content-between bg-white py-2 px-4 border-top footer-responsive">
		<p class="text-dark mb-0">&copy; <?php echo date('Y'); ?> <a href="javascript:void(0);" class="link-primary"><?php echo isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'Vendor'; ?></a>, All Rights Reserved</p>
		<p class="text-dark">Version : 1.0.0</p>
	</div>
	<!-- End Footer -->
	
	<style>
		/* Apply margin-left only on large screens (desktop) */
		@media (min-width: 992px) {
			.footer-responsive {
				margin-left: 200px;
			}
		}
	</style>

</div>
<!-- End Wrapper -->

<!-- Bootstrap Core JS -->
<script src="<?php echo base_url('assets/template/js/bootstrap.bundle.min.js'); ?>"></script> 

<!-- Select2 JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert2 JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Simplebar JS -->
<script src="<?php echo base_url('assets/template/plugins/simplebar/simplebar.min.js'); ?>"></script>

<!-- Custom JS -->
<script src="<?php echo base_url('assets/template/js/script.js'); ?>"></script>
<script>
var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

/* Attach CSRF to all POST */
$.ajaxSetup({
    beforeSend: function (xhr, settings) {

        if (settings.type === 'POST') {

            if (typeof settings.data === 'string') {
                settings.data += '&' + csrfName + '=' + csrfHash;
            } else {
                settings.data = settings.data || {};
                settings.data[csrfName] = csrfHash;
            }
        }
    }
});

/* Update CSRF only if returned */
$(document).ajaxComplete(function (event, xhr) {

    try {
        var response = xhr.responseJSON || JSON.parse(xhr.responseText);

        if (response && response.csrf && response.csrf.hash) {
            csrfHash = response.csrf.hash;
        }

    } catch (e) {
    }

});
</script>

</body>
</html>

