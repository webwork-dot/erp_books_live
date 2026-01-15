<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Calculate base URL - use config if available, otherwise use relative path
if (function_exists('base_url')) {
    $base_url = base_url();
} elseif (function_exists('config_item') && config_item('base_url')) {
    $base_url = rtrim(config_item('base_url'), '/') . '/';
} else {
    // Fallback: use relative path
    $base_url = '/books-erp/erp-system/';
}
?>
<link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/theme.css">
<div class="alert alert-danger" style="margin: 1rem;">

<h4>A PHP Error was encountered</h4>

<p>Severity: <?php echo isset($severity) ? $severity : 'Unknown'; ?></p>
<p>Message:  <?php echo isset($message) ? $message : 'No message'; ?></p>
<p>Filename: <?php echo isset($filepath) ? $filepath : 'Unknown'; ?></p>
<p>Line Number: <?php echo isset($line) ? $line : 'Unknown'; ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach (debug_backtrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			File: <?php echo $error['file'] ?><br />
			Line: <?php echo isset($error['line']) ? $error['line'] : 'N/A'; ?><br />
			Function: <?php echo isset($error['function']) ? $error['function'] : 'N/A'; ?>
			</p>

		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>
