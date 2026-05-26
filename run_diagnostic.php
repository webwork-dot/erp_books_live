<?php
$conn = mysqli_connect('localhost', 'root', '', 'erp_client_varitty');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$res1 = mysqli_query($conn, "SHOW COLUMNS FROM erp_uniforms");
echo "erp_uniforms columns matching 'hsn':\n";
while ($row = mysqli_fetch_assoc($res1)) {
    if (stripos($row['Field'], 'hsn') !== false) {
        print_r($row);
    }
}

$res2 = mysqli_query($conn, "SHOW COLUMNS FROM tbl_order_items");
echo "\ntbl_order_items columns matching 'hsn':\n";
while ($row = mysqli_fetch_assoc($res2)) {
    if (stripos($row['Field'], 'hsn') !== false) {
        print_r($row);
    }
}

mysqli_close($conn);
