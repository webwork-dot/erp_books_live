<?php
/**
 * Authentication Debug Script
 * Tests the exact same logic as the Auth controller
 */

// Load CodeIgniter
define('ENVIRONMENT', 'development');
define('BASEPATH', dirname(__FILE__) . '/../../system/');
define('APPPATH', dirname(__FILE__) . '/../../application/');

// Include CodeIgniter bootstrap
require_once BASEPATH . 'core/CodeIgniter.php';

// But we'll test directly
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'erp_master';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = 'admin';
$password = 'admin123';

echo "=== Authentication Debug Test ===\n\n";

// Test 1: Check if user exists
echo "Test 1: Check if user exists\n";
$sql = "SELECT erp_users.*, erp_user_roles.name as role_name, erp_user_roles.permissions 
        FROM erp_users 
        LEFT JOIN erp_user_roles ON erp_user_roles.id = erp_users.role_id 
        WHERE erp_users.username = ? AND erp_users.status = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "✓ User found!\n";
    echo "  ID: " . $user['id'] . "\n";
    echo "  Username: " . $user['username'] . "\n";
    echo "  Status: " . $user['status'] . "\n";
    echo "  Role ID: " . ($user['role_id'] ?? 'NULL') . "\n";
    echo "  Password Hash: " . $user['password'] . "\n";
    echo "  Hash Length: " . strlen($user['password']) . "\n\n";
    
    // Test 2: Verify password
    echo "Test 2: Verify password\n";
    $db_hash = $user['password'];
    $input_hash = sha1($password);
    
    echo "  Input password: $password\n";
    echo "  Input SHA1: $input_hash\n";
    echo "  DB Hash: $db_hash\n";
    echo "  Hash Match: " . ($input_hash === $db_hash ? "✓ YES" : "✗ NO") . "\n\n";
    
    // Test 3: Test the exact verification function
    echo "Test 3: Test verification function\n";
    $verify_result = (sha1($password) === $db_hash);
    echo "  sha1(\$password) === \$db_hash: " . ($verify_result ? "✓ TRUE" : "✗ FALSE") . "\n\n";
    
    // Test 4: Check if role_id might be causing issues
    echo "Test 4: Check role_id\n";
    if (empty($user['role_id'])) {
        echo "  ✗ WARNING: role_id is empty or NULL!\n";
        echo "  This might cause issues with the JOIN query.\n\n";
    } else {
        echo "  ✓ role_id is set: " . $user['role_id'] . "\n\n";
    }
    
    // Final verdict
    echo "=== Final Verdict ===\n";
    if ($verify_result && $user['status'] == 1) {
        echo "✓ Login SHOULD work!\n";
        echo "If login still fails, check:\n";
        echo "  1. Database connection in CodeIgniter config\n";
        echo "  2. The Erp_user_model constructor database loading\n";
        echo "  3. Check application logs for debug messages\n";
    } else {
        echo "✗ Login will FAIL!\n";
        if (!$verify_result) {
            echo "  Reason: Password hash doesn't match\n";
        }
        if ($user['status'] != 1) {
            echo "  Reason: User status is not active\n";
        }
    }
    
} else {
    echo "✗ User NOT found!\n";
    echo "This means the query is not finding the user.\n";
    echo "Possible reasons:\n";
    echo "  1. Username doesn't match (case sensitive?)\n";
    echo "  2. Status is not 1\n";
    echo "  3. Database connection issue\n";
    
    // Try without status check
    echo "\nTrying without status check...\n";
    $sql2 = "SELECT * FROM erp_users WHERE username = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("s", $username);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    if ($result2->num_rows > 0) {
        $user2 = $result2->fetch_assoc();
        echo "✓ User found without status check!\n";
        echo "  Status: " . $user2['status'] . " (should be 1)\n";
    } else {
        echo "✗ User still not found. Username might be wrong.\n";
    }
}

$conn->close();

