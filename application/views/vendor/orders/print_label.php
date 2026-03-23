<?php
$order = isset($order) && is_array($order) ? $order : array();
$order_type_label = isset($order_type_label) ? $order_type_label : 'Individual';
$slot_no = isset($order['slot_no']) ? $order['slot_no'] : '';
$date = isset($order['date']) ? $order['date'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Shipping Label - <?php echo htmlspecialchars($slot_no); ?></title>
    <style>
        @page {
            size: 4in 6in;
            margin: 0;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 100mm;
        }
        
        #print-wrap {
            width: 100%;
            max-width: 100mm;
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
        
        .no-print {
            display: none;
        }
        
        /* Hide scrollbars for print */
        @media print {
            body {
                overflow: hidden;
            }
        }
    </style>
</head>
<body>
    <div id="print-wrap">
        <!-- Header: Logo + Order No left, School/Date right -->
        <table class="header-table">
            <tr>
                <td style="width: 40%;">
                    <?php if (!empty($order['logo_src'])): ?>
                        <img src="<?php echo htmlspecialchars($order['logo_src']); ?>" class="logo" alt="Logo">
                    
                    <?php endif; ?>
                   
                <td style="text-align: right; font-size: 10px;">
                    <?php if ($order_type_label == 'Bookset'): ?>
                        <?php if (!empty($order['school_name'])): ?><b><?php echo htmlspecialchars($order['school_name']); ?></b><br><?php endif; ?>
                        <?php if (!empty($order['board_name'])): ?>Board: <?php echo htmlspecialchars($order['board_name']); ?><br><?php endif; ?>
                        <?php if (!empty($order['grade_name'])): ?>Grade: <?php echo htmlspecialchars($order['grade_name']); ?><br><?php endif; ?>
                    <?php elseif (in_array($order_type_label, array('Uniform', 'Individual')) && !empty($order['school_name'])): ?>
                        <b><?php echo htmlspecialchars($order['school_name']); ?></b><br>
                    <?php elseif (!empty($order['category_name'])): ?>
                        <b><?php echo htmlspecialchars($order['category_name']); ?></b><br>
                    <?php endif; ?>
                    <b>Order Date: <?php echo htmlspecialchars(!empty($order['date']) ? $order['date'] : (!empty($order['created_at']) ? $order['created_at'] : $date)); ?></b><br>
                    <span class="order-type-badge"><?php echo htmlspecialchars(strtoupper((string)$order_type_label)); ?></span>
                </td>
            </tr>
        </table>
        
        <!-- Slot No and Pincode Boxes -->
        <table class="info-box">
            <tr>
                <td class="box" style="width: 50%; text-align: center;">
                    <b><?php echo htmlspecialchars( $order['order_unique_id'] ?? '' ); ?></b>
                </td>
                <td class="box" style="width: 50%; text-align: center;">
                    <b>Pincode: <?php echo htmlspecialchars(isset($order['pincode']) ? $order['pincode'] : ''); ?></b>
                </td>
            </tr>
        </table>
        
        <!-- Shipping Address -->
        <div class="info-box">
            <p><b>Shipping To:</b> <?php echo htmlspecialchars(isset($order['shipping_name']) ? $order['shipping_name'] : ''); ?> <b>Phone: </b> <?php echo htmlspecialchars(isset($order['phone']) ? $order['phone'] : ''); ?></p> 
           
            
            <?php if (!empty($order['student_name']) || !empty($order['roll_number'])): ?>
                <p><b>Student:</b> <?php echo htmlspecialchars($order['student_name'] ?? ''); ?><?php if (!empty($order['roll_number'])): ?> <b>Roll No:</b> <?php echo htmlspecialchars($order['roll_number']); ?><?php endif; ?></p>
            <?php endif; ?>
            
          
            
            <?php
            $addr_parts = array(
                isset($order['address_line1']) ? $order['address_line1'] : (isset($order['address']) ? $order['address'] : ''),
                isset($order['address_city']) ? $order['address_city'] : '',
                isset($order['address_state']) ? $order['address_state'] : '',
                isset($order['address_country']) ? $order['address_country'] : '',
                isset($order['pincode']) ? $order['pincode'] : ''
            );
            $full_address = trim(implode(', ', array_filter($addr_parts)));
            ?>
            <p><b>Address:</b> <?php echo htmlspecialchars($full_address); ?></p>
        </div>
        
        <hr>
        
        <!-- Product List: Bookset (all packages + products) vs Individual (flat) -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="col-price" style="width:25%;">Price</th>
                </tr>
            </thead>
            <tbody>

                <?php if ($order_type_label == 'Bookset' && !empty($order['bookset_display_name'])): ?>
                <tr>
                <td colspan="2" class="package-header"><b>Bookset: <?php echo htmlspecialchars($order['bookset_display_name']); ?></b></td>
                </tr>
                <?php endif; ?>

                <?php if ($order_type_label == 'Bookset'): ?>
                    <?php if (!empty($order['products_structured']) && is_array($order['products_structured'])): ?>
                    <?php foreach ($order['products_structured'] as $pkg): ?>
                        <?php $pkg_name = isset($pkg['package_name']) ? $pkg['package_name'] : ''; ?>
                        <?php $pkg_price = isset($pkg['package_price']) ? floatval($pkg['package_price']) : 0; ?>
                        <tr>
                            <td class="package-header"><b>Package: <?php echo htmlspecialchars($pkg_name); ?></b></td>
                            <td class="col-price package-header"><?php echo $pkg_price > 0 ? number_format($pkg_price, 2) : ''; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($order_type_label != 'Bookset' || empty($order['products_structured'])): ?>
                    <?php $items = isset($order['items']) && is_array($order['items']) ? $order['items'] : array(); foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(is_array($item) ? (isset($item['name']) ? $item['name'] : '') : $item); ?></td>
                        <td class="col-price"></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <hr>
        
        <!-- Seller Details (same as fetch_shipping_label) -->
        <div class="info-box">
            <p><b>Seller:</b> <?php echo htmlspecialchars(isset($order['seller_name']) ? $order['seller_name'] : 'Kirti Book Agency'); ?></p>
            <?php if (!empty($order['seller_address']) || !empty($order['seller_pincode'])): ?>
            <p><b>Address:</b> <?php echo htmlspecialchars($order['seller_address'] ?? ''); ?><?php if (!empty($order['seller_pincode'])): ?> <?php echo htmlspecialchars($order['seller_pincode']); ?><?php endif; ?></p>
            <?php endif; ?>
            <?php if (!empty($order['seller_pan'])): ?><p><b>PAN:</b> <?php echo htmlspecialchars($order['seller_pan']); ?></p><?php endif; ?>
            <?php if (!empty($order['seller_gstin'])): ?><p><b>GSTIN:</b> <?php echo htmlspecialchars($order['seller_gstin']); ?></p><?php endif; ?>
        </div>
        
        <!-- Barcode (3rd_party) / QR Code (manual/self) + Shipping number for manual scan -->
        <div class="barcode-section">
            <?php if (!empty($order['barcode'])): ?>
                <img src="<?php echo htmlspecialchars($order['barcode']); ?>" alt="Barcode">
            <?php elseif (!empty($order['qr_code'])): ?>
                <img src="<?php echo htmlspecialchars($order['qr_code']); ?>" alt="QR Code">
            <?php endif; ?>
            <?php if (!empty($order['shipping_code'])): ?>
            <div class="shipping-code-text"><?php echo htmlspecialchars($order['shipping_code']); ?></div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto-trigger print dialog when page loads
        window.onload = function() {
            // Small delay to ensure page is fully rendered
            setTimeout(function() {
                window.print();
            }, 250);
        };
    </script>
</body>
</html>
