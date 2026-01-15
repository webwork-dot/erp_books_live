<?php
// Quick script to verify SHA1 hash
$password = 'admin123';
$sha1_hash = sha1($password);

echo "Password: $password\n";
echo "SHA1 Hash: $sha1_hash\n";
echo "Hash Length: " . strlen($sha1_hash) . " characters\n";
echo "\n";
echo "Expected: 40 characters for SHA1\n";
echo "Current DB hash: c93ccd78b2076528346216b3b2f701e6 (32 chars - WRONG!)\n";
echo "Correct SHA1: $sha1_hash (40 chars)\n";

