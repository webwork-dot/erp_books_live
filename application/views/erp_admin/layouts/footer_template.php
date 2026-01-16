	<!-- Start Footer -->
	<div class="footer d-sm-flex align-items-center justify-content-between bg-white py-2 px-4 border-top footer-responsive">
		<p class="text-dark mb-0">&copy; <?php echo date('Y'); ?> <a href="javascript:void(0);" class="link-primary">ERP System</a>, All Rights Reserved</p>
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

	<!-- jQuery -->
	<script src="<?php echo base_url('assets/template/js/jquery-3.7.1.min.js'); ?>"></script>

	<!-- Bootstrap Core JS -->
	<script src="<?php echo base_url('assets/template/js/bootstrap.bundle.min.js'); ?>"></script> 
	
	<!-- Daterangepikcer JS -->
	<script src="<?php echo base_url('assets/template/js/moment.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/template/plugins/daterangepicker/daterangepicker.js'); ?>"></script>

	<!-- Simplebar JS -->
	<script src="<?php echo base_url('assets/template/plugins/simplebar/simplebar.min.js'); ?>"></script>

	<!-- Datetimepicker JS -->
	<script src="<?php echo base_url('assets/template/js/bootstrap-datetimepicker.min.js'); ?>"></script>

	<!-- Select2 JS -->
	<script src="<?php echo base_url('assets/template/plugins/select2/js/select2.min.js'); ?>"></script>

	<!-- Chart JS -->
	<script src="<?php echo base_url('assets/template/plugins/apexchart/apexcharts.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/template/plugins/apexchart/chart-data.js'); ?>"></script>

	<!-- Datatable JS -->
	<script src="<?php echo base_url('assets/template/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/template/js/dataTables.bootstrap5.min.js'); ?>"></script>

	<!-- Custom JS -->
	<script src="<?php echo base_url('assets/template/js/script.js'); ?>"></script>

	<!-- Initialize Select2 -->
	<script>
		$(document).ready(function() {
			$('.select').select2({
				theme: 'bootstrap-5',
				width: '100%'
			});
		});
		
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

