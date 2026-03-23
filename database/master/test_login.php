<?php
/**
 * Test Login Debug Script
 * 
 * This script helps debug login issues by:
 * 1. Checking if the admin user exists
 * 2. Verifying the password hash
 * 3. Testing the SHA1 hash verification
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'erp_master';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== Login Debug Test ===\n\n";

// Test credentials
$username = 'admin';
$password = 'admin123';
$expected_hash = 'c93ccd78b2076528346216b3b2f701e6';

echo "Testing credentials:\n";
echo "Username: $username\n";
echo "Password: $password\n";
echo "Expected SHA1 Hash: $expected_hash\n\n";

// Calculate SHA1 hash
$calculated_hash = sha1($password);
echo "Calculated SHA1 Hash: $calculated_hash\n";
echo "Hash Match: " . ($calculated_hash === $expected_hash ? "YES ✓" : "NO ✗") . "\n\n";

// Check if user exists
$sql = "SELECT id, username, email, password, status, role_id FROM erp_users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    echo "=== User Found in Database ===\n";
    echo "ID: " . $user['id'] . "\n";
    echo "Username: " . $user['username'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Status: " . $user['status'] . " " . ($user['status'] == 1 ? "✓ (Active)" : "✗ (Inactive)") . "\n";
    echo "Role ID: " . $user['role_id'] . "\n";
    echo "Password Hash in DB: " . $user['password'] . "\n";
    echo "Hash Length: " . strlen($user['password']) . " characters\n\n";
    
    // Test password verification
    echo "=== Password Verification Test ===\n";
    $db_hash = $user['password'];
    $test_hash = sha1($password);
    
    echo "DB Hash: $db_hash\n";
    echo "Test Hash: $test_hash\n";
    echo "Hash Comparison: " . ($test_hash === $db_hash ? "MATCH ✓" : "NO MATCH ✗") . "\n\n";
    
    // Check if hash looks like SHA1 (40 characters)
    if (strlen($db_hash) == 40) {
        echo "Hash format: SHA1 (40 characters) ✓\n";
    } elseif (strlen($db_hash) == 60 && substr($db_hash, 0, 4) == '$2y$') {
        echo "Hash format: BCRYPT (60 characters) ✗ - NEEDS UPDATE\n";
        echo "\nTo fix, run this SQL:\n";
        echo "UPDATE erp_users SET password = '$expected_hash' WHERE username = 'admin';\n";
    } else {
        echo "Hash format: UNKNOWN ✗\n";
    }
    
    // Final verification
    echo "\n=== Final Login Test ===\n";
    if ($user['status'] == 1 && sha1($password) === $db_hash) {
        echo "✓ Login should work!\n";
    } else {
        echo "✗ Login will fail!\n";
        if ($user['status'] != 1) {
            echo "  - Reason: User status is not active (status = " . $user['status'] . ")\n";
        }
        if (sha1($password) !== $db_hash) {
            echo "  - Reason: Password hash doesn't match\n";
            echo "  - Update password with: UPDATE erp_users SET password = '$expected_hash' WHERE username = 'admin';\n";
        }
    }
    
} else {
    echo "=== ERROR: User Not Found ===\n";
    echo "The admin user does not exist in the database!\n\n";
    echo "To create the user, run:\n";
    echo "INSERT INTO erp_users (username, email, password, role_id, status) VALUES\n";
    echo "('admin', 'admin@erp.local', '$expected_hash', 1, 1);\n";
}

$conn->close();
echo "\n=== End of Test ===\n";

