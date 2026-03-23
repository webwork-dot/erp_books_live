<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        .invoice-container {
            border: 2px solid #000;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        .header-left {
            flex: 1;
        }
        .header-right {
            text-align: right;
        }
        .logo {
            max-width: 200px;
            max-height: 100px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-info {
            font-size: 11px;
            line-height: 1.6;
        }
        .invoice-info strong {
            font-weight: bold;
        }
        .invoice-body {
            margin-top: 30px;
        }
        .billing-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .section-box {
            border: 1px solid #ccc;
            padding: 15px;
        }
        .section-box h3 {
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .section-box p {
            margin: 5px 0;
            font-size: 11px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .products-table th,
        .products-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        .products-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .products-table td {
            text-align: right;
        }
        .products-table td:first-child {
            text-align: left;
        }
        .totals-section {
            margin-top: 20px;
            text-align: right;
        }
        .totals-table {
            width: 100%;
            max-width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px;
            border-bottom: 1px solid #ccc;
            font-size: 11px;
        }
        .totals-table td:first-child {
            text-align: left;
        }
        .totals-table td:last-child {
            text-align: right;
        }
        .totals-table .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="header-left">
                <?php if (!empty($logo_url)): ?>
                    <img src="<?php echo $logo_url; ?>" alt="Logo" class="logo" onerror="this.style.display='none';">
                <?php endif; ?>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-info">
                    <p><strong>Invoice No:</strong> <?php echo !empty($order->invoice_no) ? htmlspecialchars($order->invoice_no) : 'N/A'; ?></p>
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order->order_unique_id); ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('d M, Y', strtotime($order->order_date)); ?></p>
                    <p><strong>Payment Method:</strong> <?php echo strtoupper($order->payment_method == 'cod' ? 'Cash on Delivery' : $order->payment_method); ?></p>
                </div>
            </div>
        </div>

        <div class="invoice-body">
            <div class="billing-section">
                <div class="section-box">
                    <h3>BILL TO:</h3>
                    <?php if ($address): ?>
                        <p><strong><?php echo htmlspecialchars($address->name); ?></strong></p>
                        <p><?php echo htmlspecialchars($address->mobile_no); ?></p>
                        <p><?php echo htmlspecialchars($address->address); ?></p>
                        <p><?php echo htmlspecialchars($address->city . ', ' . $address->state . ' - ' . $address->pincode); ?></p>
                        <?php if (!empty($address->landmark)): ?>
                            <p>Landmark: <?php echo htmlspecialchars($address->landmark); ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p><strong><?php echo htmlspecialchars($order->user_name); ?></strong></p>
                        <p><?php echo htmlspecialchars($order->user_phone); ?></p>
                        <p>Address not available</p>
                    <?php endif; ?>
                </div>

                <div class="section-box">
                    <h3>SHIP TO:</h3>
                    <?php if ($address): ?>
                        <p><strong><?php echo htmlspecialchars($address->name); ?></strong></p>
                        <p><?php echo htmlspecialchars($address->mobile_no); ?></p>
                        <p><?php echo htmlspecialchars($address->address); ?></p>
                        <p><?php echo htmlspecialchars($address->city . ', ' . $address->state . ' - ' . $address->pincode); ?></p>
                        <?php if (!empty($address->landmark)): ?>
                            <p>Landmark: <?php echo htmlspecialchars($address->landmark); ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p><strong><?php echo htmlspecialchars($order->user_name); ?></strong></p>
                        <p><?php echo htmlspecialchars($order->user_phone); ?></p>
                        <p>Address not available</p>
                    <?php endif; ?>
                </div>
            </div>

            <table class="products-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_qty = 0;
                    $subtotal = 0;
                    foreach ($items as $item): 
                        $qty = isset($item->product_qty) ? (int)$item->product_qty : 1;
                        $unit_price = isset($item->product_price) ? (float)$item->product_price : 0;
                        $item_total = isset($item->total_price) ? (float)$item->total_price : ($unit_price * $qty);
                        $total_qty += $qty;
                        $subtotal += $item_total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars(isset($item->product_title) ? $item->product_title : 'N/A'); ?></td>
                            <td><?php echo $qty; ?></td>
                            <td><?php echo $order->currency . ' ' . number_format($unit_price, 2); ?></td>
                            <td><?php echo $order->currency . ' ' . number_format($item_total, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="totals-section">
                <table class="totals-table">
                    <tr>
                        <td>Subtotal:</td>
                        <td><?php echo $order->currency . ' ' . number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php if (isset($order->discount) && $order->discount > 0): ?>
                    <tr>
                        <td>Discount:</td>
                        <td>- <?php echo $order->currency . ' ' . number_format($order->discount, 2); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (isset($order->shipping_charges) && $order->shipping_charges > 0): ?>
                    <tr>
                        <td>Shipping:</td>
                        <td><?php echo $order->currency . ' ' . number_format($order->shipping_charges, 2); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="total-row">
                        <td>Total:</td>
                        <td><?php echo $order->currency . ' ' . number_format(isset($order->total_amount) ? $order->total_amount : $subtotal, 2); ?></td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <p>Thank you for your business!</p>
                <p>This is a computer-generated invoice and does not require a signature.</p>
            </div>
        </div>
    </div>
</body>
</html>

