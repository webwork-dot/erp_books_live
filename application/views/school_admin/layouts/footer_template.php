	<div class="footer d-sm-flex align-items-center justify-content-between bg-white py-2 px-4 border-top footer-responsive">
		<p class="text-dark mb-0">&copy; <?php echo date('Y'); ?> <a href="javascript:void(0);" class="link-primary"><?php echo isset($current_vendor['name']) ? htmlspecialchars($current_vendor['name']) : 'School'; ?></a>, All Rights Reserved</p>
		<p class="text-dark">Version : 1.0.0</p>
	</div>
	
	<style>
		@media (min-width: 992px) {
			.footer-responsive {
				margin-left: 200px;
			}
		}
	</style>

</div>

<script src="<?php echo base_url('assets/template/js/bootstrap.bundle.min.js'); ?>"></script> 
<script src="<?php echo base_url('assets/template/plugins/simplebar/simplebar.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/template/js/script.js'); ?>"></script>

</body>
</html>
