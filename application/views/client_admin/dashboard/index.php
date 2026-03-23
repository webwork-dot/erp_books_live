<h1>Client Dashboard</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
    <div class="card">
        <div class="card-body">
            <h3 style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Total Orders</h3>
            <p style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin: 0;"><?php echo $total_orders; ?></p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Pending Orders</h3>
            <p style="font-size: 2rem; font-weight: bold; color: var(--warning-color); margin: 0;"><?php echo $pending_orders; ?></p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Total Products</h3>
            <p style="font-size: 2rem; font-weight: bold; color: var(--success-color); margin: 0;"><?php echo $total_products; ?></p>
        </div>
    </div>
</div>

<h2 style="margin-top: 2rem;">Recent Orders</h2>
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recent_orders)): ?>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                            <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span class="badge badge-success">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No orders found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
