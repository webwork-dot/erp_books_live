<?php
/**
 * One-time script to create the barcode upload directory
 * Run this file once via browser: https://admin.shivambook.com/create_barcode_directory.php
 * Then delete this file for security
 */

// Security check - only allow if accessed directly
if (php_sapi_name() !== 'cli' && !isset($_GET['confirm'])) {
    die('For security, add ?confirm=yes to the URL to run this script.<br>Example: create_barcode_directory.php?confirm=yes');
}

echo "<h1>Barcode Directory Setup</h1>";
echo "<hr>";

// Define the directory path
$base_path = '/www/webwork/erp.webwork.co.in/';
$relative_dir = 'uploads/vendor_picqer_barcode/';
$full_path = $base_path . $relative_dir;

echo "<strong>Target Directory:</strong> $full_path<br><br>";

// Check if directory already exists
if (is_dir($full_path)) {
    echo "✓ Directory already exists: $full_path<br>";
    
    // Check permissions
    if (is_writable($full_path)) {
        echo "✓ Directory is writable<br>";
        echo "<br><strong style='color:green;'>✓ Setup complete! The directory is ready to use.</strong><br>";
        echo "<br>You can now delete this file (create_barcode_directory.php) for security.<br>";
    } else {
        echo "⚠ Directory exists but is NOT writable<br>";
        echo "Current permissions: " . substr(sprintf('%o', fileperms($full_path)), -4) . "<br>";
        echo "<br><strong>Action Required:</strong><br>";
        echo "Please set permissions to 775 or 777 using SSH or file manager:<br>";
        echo "<code>chmod 775 $full_path</code><br>";
    }
} else {
    echo "Directory does not exist. Attempting to create...<br><br>";
    
    // Try to create the directory
    if (@mkdir($full_path, 0775, true)) {
        echo "✓ Directory created successfully: $full_path<br>";
        
        // Check if it's writable
        if (is_writable($full_path)) {
            echo "✓ Directory is writable<br>";
            echo "<br><strong style='color:green;'>✓ Setup complete! The directory is ready to use.</strong><br>";
            echo "<br>You can now delete this file (create_barcode_directory.php) for security.<br>";
        } else {
            echo "⚠ Directory created but is NOT writable<br>";
            echo "Current permissions: " . substr(sprintf('%o', fileperms($full_path)), -4) . "<br>";
            echo "<br><strong>Action Required:</strong><br>";
            echo "Please set permissions to 775 or 777 using SSH:<br>";
            echo "<code>chmod 775 $full_path</code><br>";
        }
    } else {
        $error = error_get_last();
        $error_msg = $error && isset($error['message']) ? $error['message'] : 'Unknown error';
        echo "✗ Failed to create directory<br>";
        echo "Error: $error_msg<br><br>";
        
        echo "<strong>Manual Steps Required:</strong><br>";
        echo "1. Create the directory manually using SSH or file manager:<br>";
        echo "   <code>mkdir -p $full_path</code><br><br>";
        echo "2. Set permissions:<br>";
        echo "   <code>chmod 775 $full_path</code><br><br>";
        echo "3. (Optional) Set ownership to web server user:<br>";
        echo "   <code>chown www-data:www-data $full_path</code><br>";
        echo "   (Replace 'www-data' with your actual web server user - check with 'ps aux | grep apache' or 'ps aux | grep nginx')<br>";
    }
}

echo "<hr>";
echo "<p><a href='" . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/') . "'>Go Back</a></p>";
?>

