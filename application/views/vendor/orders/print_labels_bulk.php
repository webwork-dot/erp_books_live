<?php
$label_data_list = isset($label_data_list) && is_array($label_data_list) ? $label_data_list : array();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Shipping Labels - <?php echo count($label_data_list); ?> labels</title>
    <style>
        @page {
            size: 4in 6in;
            margin: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .label-page {
            width: 100mm;
            padding: 10px;
            page-break-after: always;
            page-break-inside: avoid;
        }
        .label-page:last-child {
            page-break-after: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .box {
            border: 2px solid #000;
            padding: 6px;
        }
        .logo {
            max-height: 50px;
            max-width: 150px;
        }
        .header-table td {
            vertical-align: top;
            padding: 5px 0;
        }
        .info-box {
            margin: 10px 0;
        }
        .product-table {
            margin: 10px 0;
        }
        .product-table th,
        .product-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .product-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .product-table .col-price {
            text-align: right;
        }
        .barcode-section {
            text-align: center;
            margin-top: 20px;
        }
        .barcode-section img {
            max-width: 200px;
            height: auto;
        }
        .shipping-code-text {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 4px;
        }
        .order-type-badge {
            display: inline-block;
            border: 2px solid #000;
            padding: 2px 6px;
            margin-top: 2px;
            font-weight: bold;
        }
        .package-header {
            background-color: #e8e8e8;
            font-weight: bold;
            padding: 3px 4px;
            font-size: 10px;
        }
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 10px 0;
        }
        p {
            margin: 5px 0;
            line-height: 1.4;
        }
        @media print {
            body { overflow: hidden; }
        }
    </style>
</head>
<body>
<?php foreach ($label_data_list as $idx => $label_data): ?>
    <div class="label-page">
        <?php
        $order = isset($label_data['order']) ? $label_data['order'] : array();
        $order_type_label = isset($label_data['order_type_label']) ? $label_data['order_type_label'] : 'Individual';
        include(APPPATH . 'views/vendor/orders/_print_label_content.php');
        ?>
    </div>
<?php endforeach; ?>
<script>
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 300);
    };
</script>
</body>
</html>
