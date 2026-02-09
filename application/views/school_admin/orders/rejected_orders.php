<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Cancelled Orders</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('school-admin/dashboard'); ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Cancelled Orders</li>
            </ul>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($order_list)): ?>
                    <?php foreach ($order_list as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_unique_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['user_phone']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                            <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo !empty($order['invoice_url']) ? '<a href="'.base_url($order['invoice_url']).'" target="_blank">'.htmlspecialchars($order['invoice_no']).'</a>' : htmlspecialchars($order['invoice_no']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No cancelled orders found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <?php echo isset($pagination) ? $pagination : ''; ?>
        </div>
    </div>
 </div>
