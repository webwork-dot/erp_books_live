<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Details - <?php echo htmlspecialchars($shipping_number); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header .shipping-number {
            font-size: 18px;
            color: #666;
            font-weight: bold;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-section {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
        }
        .info-section h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }
        .info-section p {
            margin: 8px 0;
            font-size: 14px;
        }
        .info-section strong {
            color: #555;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .products-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Shipping Details</h1>
            <div class="shipping-number">Shipping Number: <?php echo htmlspecialchars($shipping_number); ?></div>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <h3>Order Information</h3>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order->order_unique_id); ?></p>
                <p><strong>Order Date:</strong> <?php echo date('d M, Y', strtotime($order->order_date)); ?></p>
                <p><strong>Payment Method:</strong> <?php echo strtoupper($order->payment_method == 'cod' ? 'Cash on Delivery' : $order->payment_method); ?></p>
                <?php if (!empty($order->invoice_no)): ?>
                    <p><strong>Invoice No:</strong> <?php echo htmlspecialchars($order->invoice_no); ?></p>
                <?php endif; ?>
                <?php if (!empty($order->ship_order_id)): ?>
                    <p><strong>Shipping ID:</strong> <?php echo htmlspecialchars($order->ship_order_id); ?></p>
                <?php endif; ?>
            </div>

            <div class="info-section">
                <h3>Shipping Address</h3>
                <?php if ($address): ?>
                    <p><strong><?php echo htmlspecialchars($address->name); ?></strong></p>
                    <p><?php echo htmlspecialchars($address->mobile_no); ?></p>
                    <p><?php echo htmlspecialchars($address->address); ?></p>
                    <p><?php echo htmlspecialchars($address->city . ', ' . $address->state . ' - ' . $address->pincode); ?></p>
                    <?php if (!empty($address->landmark)): ?>
                        <p><strong>Landmark:</strong> <?php echo htmlspecialchars($address->landmark); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Address not available</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-section">
            <h3>Order Items</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
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
                            <td><?php echo htmlspecialchars(isset($item->product_title) ? $item->product_title : 'N/A'); ?></td>
                            <td><?php echo $qty; ?></td>
                            <td><?php echo isset($order->currency) ? $order->currency : 'INR'; ?> <?php echo number_format($price, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: bold; background-color: #f0f0f0;">
                        <td>Total</td>
                        <td><?php echo $total_qty; ?></td>
                        <td><?php echo isset($order->currency) ? $order->currency : 'INR'; ?> <?php echo number_format($total_cost, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>This is a computer-generated shipping details page.</p>
            <p>For any queries, please contact the vendor.</p>
        </div>
    </div>
</body>
</html>

