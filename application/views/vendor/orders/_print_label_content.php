<?php
$order = isset($order) && is_array($order) ? $order : array();
$order_type_label = isset($order_type_label) ? $order_type_label : 'Individual';
$date = isset($order['date']) ? $order['date'] : '';
?>
<div class="print-wrap">
    <table class="header-table">
        <tr>
            <td style="width: 40%;">
                <?php if (!empty($order['logo_src'])): ?>
                    <img src="<?php echo htmlspecialchars($order['logo_src']); ?>" class="logo" alt="Logo">
                <?php endif; ?>
            </td>
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
    <table class="info-box">
        <tr>
            <td class="box" style="width: 50%; text-align: center;">
                <b><?php echo htmlspecialchars($order['order_unique_id'] ?? ''); ?></b>
            </td>
            <td class="box" style="width: 50%; text-align: center;">
				<b>
					<?php
					if(isset($order['payment_method']) && strtolower($order['payment_method']) == 'cod'){
						echo "CASH ON DELIVERY";
					}else{
						echo "PREPAID";
					}
					?>
				</b>
			</td>
        </tr>
    </table>
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
                <td colspan="2" class="package-header"><b><?php echo htmlspecialchars($order['bookset_display_name']); ?></b></td>
            </tr> 
            <?php endif; ?>
            <?php if ($order_type_label == 'Bookset'): ?>
                <?php if (!empty($order['products_structured']) && is_array($order['products_structured'])): ?>
                    <?php foreach ($order['products_structured'] as $pkg): ?>
                        <?php $pkg_name = isset($pkg['package_name']) ? $pkg['package_name'] : ''; ?>
                        <?php $pkg_price = isset($pkg['package_price']) ? floatval($pkg['package_price']) : 0; ?>
                        <tr>
                            <td class="package-header"><b><?php echo htmlspecialchars($pkg_name); ?></b></td>
                            <td class="col-price package-header" style="font-weight:400"><?php echo $pkg_price > 0 ? number_format($pkg_price, 2) : ''; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($order_type_label != 'Bookset' || empty($order['products_structured'])): ?>
                <?php $items = isset($order['items']) && is_array($order['items']) ? $order['items'] : array(); foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars(is_array($item) ? (isset($item['name']) ? $item['name'] : '') : $item); ?></td>
                    <td class="col-price"><?php echo round($order['total_amt']);?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if (isset($order['payment_method']) && $order['payment_method'] == 'cod'): ?>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:center; padding: 8px 0;"><b>PLEASE COLLECT CASH : <?php echo number_format(round($order['total_amt']), 2); ?></b></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table> 
    <hr>
    <div class="info-box">
        <p><b>Seller:</b> <?php echo htmlspecialchars(isset($order['seller_name']) ? $order['seller_name'] : 'Kirti Book Agency'); ?></p>
        <?php if (!empty($order['seller_address']) || !empty($order['seller_pincode'])): ?>
        <p><b>Address:</b> <?php echo htmlspecialchars($order['seller_address'] ?? ''); ?><?php if (!empty($order['seller_pincode'])): ?> <?php echo htmlspecialchars($order['seller_pincode']); ?><?php endif; ?></p>
        <?php endif; ?>
        <?php if (!empty($order['seller_gstin'])): ?><p><b>GSTIN:</b> <?php echo htmlspecialchars($order['seller_gstin']); ?></p><?php endif; ?>
    </div>
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
