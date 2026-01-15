<?php
/**
 * Fix Admin Password Script
 * 
 * This script updates the admin user password to SHA1 hash
 * Run this file directly in browser: http://localhost/books-erp/erp-system/database/master/fix_admin_password.php
 */

// Database configuration - UPDATE THESE IF NEEDED
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'erp_master';

// Admin credentials
$username = 'admin';
$password = 'admin123';
$sha1_hash = 'c93ccd78b2076528346216b3b2f701e6';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Admin Password</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fix Admin Password</h1>
        
<?php

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    echo '<div class="error">';
    echo '<strong>Database Connection Failed:</strong> ' . $conn->connect_error;
    echo '</div>';
    echo '</div></body></html>';
    exit;
}

echo '<div class="info">';
echo '<strong>Database Connected Successfully</strong><br>';
echo 'Database: ' . $db_name . '<br>';
echo 'Host: ' . $db_host;
echo '</div>';

// Check if user exists
$sql = "SELECT id, username, email, password, status, role_id FROM erp_users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    echo '<h2>Current User Information</h2>';
    echo '<table>';
    echo '<tr><th>Field</th><th>Value</th></tr>';
    echo '<tr><td>ID</td><td>' . htmlspecialchars($user['id']) . '</td></tr>';
    echo '<tr><td>Username</td><td>' . htmlspecialchars($user['username']) . '</td></tr>';
    echo '<tr><td>Email</td><td>' . htmlspecialchars($user['email']) . '</td></tr>';
    echo '<tr><td>Status</td><td>' . ($user['status'] == 1 ? 'Active ✓' : 'Inactive ✗') . '</td></tr>';
    echo '<tr><td>Role ID</td><td>' . htmlspecialchars($user['role_id']) . '</td></tr>';
    echo '<tr><td>Current Password Hash</td><td><pre>' . htmlspecialchars($user['password']) . '</pre></td></tr>';
    echo '<tr><td>Hash Length</td><td>' . strlen($user['password']) . ' characters</td></tr>';
    echo '</table>';
    
    // Check current hash
    $current_hash = $user['password'];
    $is_sha1 = (strlen($current_hash) == 40 && ctype_xdigit($current_hash));
    $is_bcrypt = (strlen($current_hash) == 60 && substr($current_hash, 0, 4) == '$2y$');
    
    echo '<h2>Hash Analysis</h2>';
    if ($is_sha1) {
        echo '<div class="info">Current hash is SHA1 format (40 characters)</div>';
        if ($current_hash === $sha1_hash) {
            echo '<div class="success">Password hash is already correct! ✓</div>';
        } else {
            echo '<div class="error">Password hash exists but does not match expected hash. Will update.</div>';
        }
    } elseif ($is_bcrypt) {
        echo '<div class="error">Current hash is BCRYPT format (60 characters) - Needs update to SHA1</div>';
    } else {
        echo '<div class="error">Unknown hash format - Will update to SHA1</div>';
    }
    
    // Update password
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
        $update_sql = "UPDATE erp_users SET password = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $sha1_hash, $username);
        
        if ($update_stmt->execute()) {
            echo '<div class="success">';
            echo '<strong>Password Updated Successfully!</strong><br>';
            echo 'Username: ' . htmlspecialchars($username) . '<br>';
            echo 'New SHA1 Hash: ' . htmlspecialchars($sha1_hash) . '<br>';
            echo 'You can now login with username: <strong>admin</strong> and password: <strong>admin123</strong>';
            echo '</div>';
            
            // Verify the update
            $verify_sql = "SELECT password FROM erp_users WHERE username = ?";
            $verify_stmt = $conn->prepare($verify_sql);
            $verify_stmt->bind_param("s", $username);
            $verify_stmt->execute();
            $verify_result = $verify_stmt->get_result();
            $updated_user = $verify_result->fetch_assoc();
            
            echo '<h2>Verification</h2>';
            echo '<table>';
            echo '<tr><th>Test</th><th>Result</th></tr>';
            echo '<tr><td>Password Hash Updated</td><td>' . ($updated_user['password'] === $sha1_hash ? '✓ YES' : '✗ NO') . '</td></tr>';
            echo '<tr><td>Hash Length</td><td>' . strlen($updated_user['password']) . ' characters (should be 40)</td></tr>';
            echo '<tr><td>SHA1 Verification</td><td>' . (sha1($password) === $updated_user['password'] ? '✓ MATCH' : '✗ NO MATCH') . '</td></tr>';
            echo '</table>';
        } else {
            echo '<div class="error">';
            echo '<strong>Error updating password:</strong> ' . $update_stmt->error;
            echo '</div>';
        }
        
        $update_stmt->close();
    } else {
        // Show update form
        echo '<h2>Update Password</h2>';
        echo '<form method="POST">';
        echo '<div class="info">';
        echo 'Click the button below to update the admin password to SHA1 hash.<br>';
        echo 'New password hash: <code>' . htmlspecialchars($sha1_hash) . '</code>';
        echo '</div>';
        echo '<button type="submit" name="update_password" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Update Password to SHA1</button>';
        echo '</form>';
    }
    
} else {
    echo '<div class="error">';
    echo '<strong>User Not Found!</strong><br>';
    echo 'The admin user does not exist in the database.';
    echo '</div>';
    
    echo '<h2>Create Admin User</h2>';
    echo '<div class="info">';
    echo 'To create the admin user, run this SQL:<br>';
    echo '<pre>INSERT INTO erp_users (username, email, password, role_id, status) VALUES';
    echo "('admin', 'admin@erp.local', '$sha1_hash', 1, 1);</pre>";
    echo '</div>';
}

$conn->close();
?>

    </div>
</body>
</html>

