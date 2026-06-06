<?php
$lines = file('application/controllers/Vendor/Orders.php');
foreach ($lines as $num => $line) {
    if (strpos($line, 'erp_clients') !== false) {
        echo ($num + 1) . ": " . trim($line) . "\n";
    }
}
