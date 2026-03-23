<style>
.reports-container {
    padding: 20px 0;
}

.reports-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.reports-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.reports-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-value {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.95rem;
    color: #7f8c8d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
}

.chart-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chart-title i {
    color: #667eea;
}

.date-filter {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    border: 1px solid #f0f0f0;
}

.filter-controls {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-label {
    font-weight: 600;
    color: #2c3e50;
    margin-right: 10px;
}

.filter-select {
    padding: 8px 15px;
    border: 2px solid #e0e6ed;
    border-radius: 8px;
    background: white;
    font-size: 0.9rem;
    min-width: 150px;
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.table-responsive {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
    margin-bottom: 30px;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-new { background: #e3f2fd; color: #1976d2; }
.status-processing { background: #fff3e0; color: #f57c00; }
.status-delivery { background: #fce4ec; color: #c2185b; }
.status-delivered { background: #e8f5e9; color: #2e7d32; }
.status-return { background: #ffebee; color: #c62828; }

.amount-text {
    font-weight: 600;
    color: #2c3e50;
}

.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}

@media (max-width: 768px) {
    .reports-header {
        padding: 20px;
        text-align: center;
    }

    .reports-title {
        font-size: 2rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .charts-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-select {
        width: 100%;
    }

    .stat-card {
        padding: 20px;
    }

    .stat-value {
        font-size: 1.8rem;
    }
}
</style>

<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">School & Branch Reports</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('school-admin/dashboard'); ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ul>
        </div>
    </div>
</div>

<div class="reports-container">
    <!-- Reports Header -->
    <div class="reports-header">
        <h1 class="reports-title">School Performance Reports</h1>
        <p class="reports-subtitle">Comprehensive analytics and insights for your school and all its branches bookset orders</p>
    </div>

    <!-- Date Filter -->
    <div class="date-filter">
        <div class="filter-controls">
            <span class="filter-label">Time Period:</span>
            <select class="filter-select" id="preset-filter" onchange="changePreset(this.value)">
                <option value="today" <?php echo ($preset == 'today') ? 'selected' : ''; ?>>Today</option>
                <option value="this_week" <?php echo ($preset == 'this_week') ? 'selected' : ''; ?>>This Week</option>
                <option value="this_month" <?php echo ($preset == 'this_month') ? 'selected' : ''; ?>>This Month</option>
                <option value="last_month" <?php echo ($preset == 'last_month') ? 'selected' : ''; ?>>Last Month</option>
                <option value="this_year" <?php echo ($preset == 'this_year') ? 'selected' : ''; ?>>This Year</option>
            </select>
            <small class="text-muted ms-2">
                Showing data from <strong><?php echo date('M d, Y', strtotime($date_from)); ?></strong> to <strong><?php echo date('M d, Y', strtotime($date_to)); ?></strong>
            </small>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <?php
        $total_orders = 0;
        $total_revenue = 0;
        foreach ($order_status_summary as $status) {
            $total_orders += $status['count'];
            $total_revenue += $status['total_revenue'];
        }
        ?>

        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($total_orders); ?></div>
            <div class="stat-label">Total Orders</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">₹<?php echo number_format($total_revenue, 2); ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>

        <?php if (!empty($student_distribution)): ?>
        <div class="stat-card">
            <div class="stat-value"><?php echo number_format($student_distribution['unique_students']); ?></div>
            <div class="stat-label">Unique Students</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">₹<?php echo number_format($student_distribution['avg_order_value'], 2); ?></div>
            <div class="stat-label">Avg Order Value</div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Monthly Revenue Chart -->
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="isax isax-trend-up"></i> Monthly Revenue Trend
            </h4>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="isax isax-chart-2"></i> Order Status Distribution
            </h4>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="charts-grid">
        <!-- Grade Distribution -->
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="isax isax-book"></i> Orders by Grade
            </h4>
            <div class="chart-container">
                <canvas id="gradeChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="isax isax-card"></i> Payment Methods
            </h4>
            <div class="chart-container">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="table-responsive">
        <h4 class="chart-title mb-4">
            <i class="isax isax-document-text"></i> Order Status Summary
        </h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Order Count</th>
                    <th>Total Revenue</th>
                    <th>Avg Order Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_status_summary as $status): ?>
                <tr>
                    <td>
                        <span class="status-badge status-<?php
                            $status_class = 'new';
                            if (strpos($status['status_name'], 'Processing') !== false) $status_class = 'processing';
                            elseif (strpos($status['status_name'], 'Delivery') !== false) $status_class = 'delivery';
                            elseif (strpos($status['status_name'], 'Delivered') !== false) $status_class = 'delivered';
                            elseif (strpos($status['status_name'], 'Return') !== false) $status_class = 'return';
                            echo $status_class;
                        ?>">
                            <?php echo htmlspecialchars($status['status_name']); ?>
                        </span>
                    </td>
                    <td><?php echo number_format($status['count']); ?></td>
                    <td class="amount-text">₹<?php echo number_format($status['total_revenue'], 2); ?></td>
                    <td class="amount-text">₹<?php echo $status['count'] > 0 ? number_format($status['total_revenue'] / $status['count'], 2) : '0.00'; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($order_status_summary)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No orders found for the selected period.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Popular Packages Table -->
    <div class="table-responsive">
        <h4 class="chart-title mb-4">
            <i class="isax isax-box"></i> Popular Packages
        </h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Package Name</th>
                    <th>Order Count</th>
                    <th>Total Revenue</th>
                    <th>Popularity Rank</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($popular_packages as $package): ?>
                <tr>
                    <td><?php echo htmlspecialchars($package['package_name']); ?></td>
                    <td><?php echo number_format($package['order_count']); ?></td>
                    <td class="amount-text">₹<?php echo number_format($package['revenue'], 2); ?></td>
                    <td><span class="badge bg-primary">#<?php echo $rank++; ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($popular_packages)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">No package data available for the selected period.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart configurations
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueData = <?php echo json_encode($monthly_revenue); ?>;
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month_name),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(item => item.revenue),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Order Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const statusData = <?php echo json_encode($order_status_summary); ?>;
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.map(item => item.status_name),
                datasets: [{
                    data: statusData.map(item => item.count),
                    backgroundColor: [
                        '#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Grade Distribution Chart
    const gradeCtx = document.getElementById('gradeChart');
    if (gradeCtx) {
        const gradeData = <?php echo json_encode($grade_distribution); ?>;
        new Chart(gradeCtx, {
            type: 'bar',
            data: {
                labels: gradeData.map(item => item.grade),
                datasets: [{
                    label: 'Orders',
                    data: gradeData.map(item => item.order_count),
                    backgroundColor: '#667eea',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentChart');
    if (paymentCtx) {
        const paymentData = <?php echo json_encode($payment_methods); ?>;
        new Chart(paymentCtx, {
            type: 'pie',
            data: {
                labels: paymentData.map(item => item.payment_method),
                datasets: [{
                    data: paymentData.map(item => item.revenue),
                    backgroundColor: [
                        '#667eea', '#764ba2', '#f093fb', '#f5576c'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});

// Date filter function
function changePreset(preset) {
    const url = new URL(window.location);
    url.searchParams.set('preset', preset);
    window.location.href = url.toString();
}
</script>