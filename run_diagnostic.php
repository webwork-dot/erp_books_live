<?php
$conn = mysqli_connect('localhost', 'root', '', 'erp_client_kirtibookin');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$res = mysqli_query($conn, "SELECT id, invoice_no, order_unique_id FROM tbl_order_details WHERE id IN (43, 48, 51)");
while ($row = mysqli_fetch_assoc($res)) {
    echo "ID: {$row['id']} | Order No: {$row['order_unique_id']} | Invoice No: [{$row['invoice_no']}]\n";
}

mysqli_close($conn);
