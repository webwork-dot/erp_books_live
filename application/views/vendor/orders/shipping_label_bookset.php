<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        * { box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .container {
            border: 2px solid #000;
            padding: 14px;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }
        /* HTML preview only: constrain to an A4 "paper" */
        body.preview { background: #eee; padding: 10px; }
        body.preview .container {
            width: 190mm; /* A4 width (210mm) minus 10mm margins on both sides */
            margin: 0 auto;
            background: #fff;
        }
        body.pdf .container { width: 100%; }
        .section {
            border: 1px solid #ccc;
            padding: 10px;
        }
        .title {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .small { font-size: 10.5px; }
        .logo { max-width: 150px; max-height: 80px; }
        .badge {
            display: inline-block;
            background: #333;
            color: #fff;
            padding: 6px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        .muted { color: #666; }
        .mt-10 { margin-top: 10px; }
        .mt-12 { margin-top: 12px; }
        .mt-14 { margin-top: 14px; }
        .right { text-align: right; }
        .center { text-align: center; }
        .products th, .products td {
            border-bottom: 1px dotted #ccc;
            padding: 6px 0;
            vertical-align: top;
        }
        .products th { font-weight: bold; text-align: left; }
        .qty { width: 90px; text-align: right; }
        .total-box {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 10px;
            text-align: right;
        }
        .tracking {
            margin-top: 12px;
            padding: 10px;
            background: #f5f5f5;
            border: 1px solid #ccc;
            text-align: center;
        }
        .qr-wrap {
            margin-top: 12px;
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .qr {
            width: 120px;
            height: 120px;
            border: 2px solid #000;
            padding: 4px;
            margin: 0 auto;
            background: #fff;
        }
        .qr img { width: 100%; height: 100%; display: block; }
    </style>
</head>
<body class="<?php echo !empty($is_pdf) ? 'pdf' : 'preview'; ?>">
    <div class="container">
        <!-- Header -->
        <table>
            <tr>
                <td style="width:55%; vertical-align:top; word-wrap: break-word;">
                    <?php 
                    // Use absolute file path for PDF, base64 for HTML preview
                    // Use base64 for PDF (best compatibility), URL for HTML preview
                    if (!empty($logo_base64)): 
                        // For PDF, use base64 (best compatibility)
                        $logo_src = $logo_base64;
                    elseif (!empty($logo_file_path) && file_exists($logo_file_path)): 
                        // Fallback: use absolute file path
                        $logo_src = 'file://' . str_replace('\\', '/', $logo_file_path);
                    elseif (!empty($logo_url)): 
                        // For HTML preview, use URL
                        $logo_src = $logo_url;
                    else:
                        $logo_src = '';
                    endif;
                    ?>
                    <?php if (!empty($logo_src)): ?>
                        <img src="<?php echo htmlspecialchars($logo_src); ?>" alt="Logo" class="logo" style="max-width: 150px; max-height: 80px; width: auto; height: auto;" onerror="this.style.display='none';">
                    <?php endif; ?>
                </td>
                <td style="width:45%; vertical-align:top;" class="right">
                    <div class="small">
                        <div><strong>Order ID:</strong> <?php echo htmlspecialchars($order->order_unique_id); ?></div>
                        <div><strong>Order Date:</strong> <?php echo date('d M, Y', strtotime($order->order_date)); ?></div>
                        <div class="mt-10"><strong>Shipping No:</strong> <?php echo htmlspecialchars($shipping_number); ?></div>
                        <div><strong>Generated On:</strong> <?php echo date('d M, Y h:i A'); ?></div>
                        <div class="mt-10"><span class="badge"><?php echo strtoupper($order_type_label); ?></span></div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Ship to + Order details -->
        <table class="mt-12">
            <tr>
                <td style="width:50%; padding-right:10px; vertical-align:top; word-wrap: break-word; overflow-wrap: break-word;">
                    <div class="section">
                        <div class="title">SHIP TO</div>
                        <?php if ($address): ?>
                            <div style="word-wrap: break-word;"><strong><?php echo htmlspecialchars($address->name); ?></strong></div>
                            <div><?php echo htmlspecialchars($address->mobile_no); ?></div>
                            <div style="word-wrap: break-word;"><?php echo htmlspecialchars($address->address); ?></div>
                            <div><?php echo htmlspecialchars($address->city . ', ' . $address->state . ' - ' . $address->pincode); ?></div>
                            <?php if (!empty($address->landmark)): ?>
                                <div style="word-wrap: break-word;">Landmark: <?php echo htmlspecialchars($address->landmark); ?></div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div><strong><?php echo htmlspecialchars($order->user_name); ?></strong></div>
                            <div><?php echo htmlspecialchars($order->user_phone); ?></div>
                            <div>Address not available</div>
                        <?php endif; ?>
                    </div>
                </td>
                <td style="width:50%; padding-left:10px; vertical-align:top; word-wrap: break-word; overflow-wrap: break-word;">
                    <div class="section">
                        <div class="title">ORDER DETAILS</div>
                        <div><strong>Order ID:</strong> <?php echo htmlspecialchars($order->order_unique_id); ?></div>
                        <div><strong>Order Date:</strong> <?php echo date('d M, Y', strtotime($order->order_date)); ?></div>
                        <div><strong>Payment:</strong> <?php echo strtoupper($order->payment_method == 'cod' ? 'Cash on Delivery' : $order->payment_method); ?></div>
                        <div><strong>Invoice No:</strong> <?php echo !empty($order->invoice_no) ? htmlspecialchars($order->invoice_no) : 'N/A'; ?></div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Products -->
        <div class="section mt-12">
            <div class="title">PRODUCTS</div>
            <table class="products">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="qty">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_qty = 0;
                    $total_cost = 0;
                    foreach ($items as $item):
                        $qty = isset($item->product_qty) ? (int)$item->product_qty : 1;
                        $price = isset($item->total_price) ? (float)$item->total_price : (isset($item->product_price) ? (float)$item->product_price * $qty : 0);
                        $total_qty += $qty;
                        $total_cost += $price;
                    ?>
                        <tr>
                            <td style="word-wrap: break-word; overflow-wrap: break-word;"><?php echo htmlspecialchars(isset($item->product_title) ? $item->product_title : 'N/A'); ?></td>
                            <td class="qty"><?php echo $qty; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-box">
                <div><strong>Total Quantity:</strong> <?php echo $total_qty; ?></div>
                <div><strong>Total Cost:</strong> <?php echo $order->currency . ' ' . number_format($total_cost, 2); ?></div>
            </div>
        </div>

        <!-- Barcode -->
        <?php 
        // Use base64 for PDF (best compatibility), URL for HTML preview
        $barcode_src = '';
        if (!empty($barcode_base64)) {
            // For PDF, use base64 (best compatibility)
            $barcode_src = $barcode_base64;
        } elseif (!empty($barcode_file_path) && file_exists($barcode_file_path)) {
            // Fallback: use absolute file path
            $barcode_src = 'file://' . str_replace('\\', '/', $barcode_file_path);
        } elseif (!empty($barcode_url)) {
            // For HTML preview, use URL
            $barcode_src = $barcode_url;
        }
        ?>
        <?php if (!empty($barcode_src)): ?>
            <div class="qr-wrap">
                <div style="font-weight:bold; margin-bottom:8px;">Shipping Barcode</div>
                <div style="text-align:center; margin:10px 0;">
                    <img src="<?php echo htmlspecialchars($barcode_src); ?>" alt="Shipping Barcode" style="max-width:300px; max-height:150px; width: auto; height: auto;">
                </div>
                <div class="muted" style="margin-top:8px; font-size:10px; text-align:center;">Shipping No: <?php echo htmlspecialchars($shipping_number); ?></div>
            </div>
        <?php endif; ?>
        
        <!-- QR (legacy support) -->
        <?php if (!empty($qr_code) && empty($barcode_url)): ?>
            <div class="qr-wrap">
                <div style="font-weight:bold; margin-bottom:8px;">Scan QR Code for Shipping Details</div>
                <div class="qr"><img src="<?php echo $qr_code; ?>" alt="Shipping QR"></div>
                <div class="muted" style="margin-top:8px; font-size:10px;">Scan to view complete shipping information</div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


