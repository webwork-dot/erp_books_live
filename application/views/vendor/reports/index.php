<style>
.report-card { transition: all 0.2s ease; }
.report-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.report-table th { font-weight: 600; white-space: nowrap; }
.report-table td { vertical-align: middle; }
.currency { font-family: 'Consolas', monospace; font-weight: 600; }
.report-preset-tab {
	padding: 10px 16px;
	background: #f8f9fa;
	border: 1px solid #dee2e6;
	border-bottom: none;
	border-top-left-radius: 8px;
	border-top-right-radius: 8px;
	margin-right: 2px;
	color: #495057;
	font-size: 13px;
	font-weight: 500;
	cursor: pointer;
	text-decoration: none;
	display: inline-block;
	transition: all 0.2s ease;
}
.report-preset-tab:hover { background: #e9ecef; color: #495057; }
.report-preset-tab.active {
	background: #fff;
	color: var(--bs-primary, #0d6efd);
	border-color: #dee2e6;
	margin-bottom: -1px;
	padding-bottom: 11px;
	position: relative;
	z-index: 1;
}
</style>

<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
	<div>
		<h6 class="mb-1">Reports</h6>
		<p class="text-muted mb-0 fs-13">Sales, orders by school, location & delivery performance</p>
	</div>
</div>

<!-- Date Preset Tabs (above filters card) -->
<?php
$presets = array(
	'today' => 'Today',
	'yesterday' => 'Yesterday',
	'this_week' => 'This Week',
	'last_week' => 'Last Week',
	'this_month' => 'This Month',
	'last_month' => 'Last Month',
	'this_quarter' => 'This Quarter',
	'this_year' => 'This Year',
	'custom' => 'Custom'
);
$is_custom = ($preset === 'custom');
?>
<div class="mb-0">
	<div class="d-flex flex-wrap gap-0 align-items-end border-bottom border-secondary" style="border-color: #dee2e6 !important;">
		<?php foreach ($presets as $val => $label): $active = ($preset === $val) ? 'active' : ''; ?>
		<button type="button" class="report-preset-tab preset-btn <?php echo $active; ?>" data-preset="<?php echo htmlspecialchars($val); ?>"><?php echo htmlspecialchars($label); ?></button>
		<?php endforeach; ?>
	</div>
</div>

<!-- Filters Card -->
<div class="card mb-4" style="border-top-left-radius: 0; border-top-right-radius: 0;">
	<div class="card-body">
		<form method="get" action="<?php echo base_url('reports'); ?>" id="reportFiltersForm" class="row g-3">
			<input type="hidden" name="preset" id="presetInput" value="<?php echo htmlspecialchars($preset); ?>">
			<div id="customDateRange" class="col-12 <?php echo $is_custom ? '' : 'd-none'; ?>">
				<div class="d-flex flex-wrap gap-3 align-items-end">
					<div>
						<label class="form-label fs-13 mb-0">From</label>
						<input type="date" name="from" id="dateFrom" class="form-control form-control-sm" value="<?php echo $is_custom && !empty($date_from) ? htmlspecialchars($date_from) : ''; ?>" style="width: 140px;">
					</div>
					<div>
						<label class="form-label fs-13 mb-0">To</label>
						<input type="date" name="to" id="dateTo" class="form-control form-control-sm" value="<?php echo $is_custom && !empty($date_to) ? htmlspecialchars($date_to) : ''; ?>" style="width: 140px;">
					</div>
				</div>
			</div>
			<?php if (!empty($schools)): ?>
			<div class="col-md-2">
				<label class="form-label fs-13">School</label>
				<select name="school" class="form-select form-select-sm">
					<option value="">All Schools</option>
					<?php foreach ($schools as $s): ?>
					<option value="<?php echo (int)$s['id']; ?>" <?php echo (isset($filters['school_id']) && $filters['school_id'] == $s['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['school_name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
			<?php if (!empty($states)): ?>
			<div class="col-md-2">
				<label class="form-label fs-13">State</label>
				<select name="state" class="form-select form-select-sm">
					<option value="">All States</option>
					<?php foreach ($states as $st): ?>
					<option value="<?php echo htmlspecialchars($st['name']); ?>" <?php echo (isset($filters['state']) && $filters['state'] == $st['name']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($st['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
			<?php if (!empty($cities)): ?>
			<div class="col-md-2">
				<label class="form-label fs-13">City</label>
				<select name="city" class="form-select form-select-sm">
					<option value="">All Cities</option>
					<?php foreach ($cities as $c): ?>
					<option value="<?php echo htmlspecialchars($c['name']); ?>" <?php echo (isset($filters['city']) && $filters['city'] == $c['name']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
			<div class="col-md-2">
				<label class="form-label fs-13">Order Type</label>
				<select name="order_type" class="form-select form-select-sm">
					<option value="">All Types</option>
					<option value="bookset" <?php echo (isset($filters['order_type']) && $filters['order_type'] === 'bookset') ? 'selected' : ''; ?>>Bookset</option>
					<option value="individual" <?php echo (isset($filters['order_type']) && $filters['order_type'] === 'individual') ? 'selected' : ''; ?>>Individual</option>
					<option value="uniform" <?php echo (isset($filters['order_type']) && $filters['order_type'] === 'uniform') ? 'selected' : ''; ?>>Uniform</option>
				</select>
			</div>
			<div class="col-md-2 d-flex align-items-end">
				<button type="submit" class="btn btn-primary btn-sm me-2"><i class="isax isax-filter me-1"></i>Apply</button>
				<a href="<?php echo base_url('reports'); ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
			</div>
		</form>
		<?php
		$export_params = 'preset=' . urlencode($preset);
		if ($preset === 'custom' && !empty($date_from) && !empty($date_to)) {
			$export_params .= '&from=' . urlencode($date_from) . '&to=' . urlencode($date_to);
		}
		$export_params .= '&school=' . urlencode($filters['school_id'] ?? '') . '&state=' . urlencode($filters['state'] ?? '') . '&city=' . urlencode($filters['city'] ?? '') . '&order_type=' . urlencode($filters['order_type'] ?? '');
		?>
		<p class="text-muted fs-12 mt-2 mb-0" id="dateRangeLabel"><?php echo $date_from; ?> to <?php echo $date_to; ?></p>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var presetBtns = document.querySelectorAll('.preset-btn');
	var presetInput = document.getElementById('presetInput');
	var customDateRange = document.getElementById('customDateRange');
	var dateFrom = document.getElementById('dateFrom');
	var dateTo = document.getElementById('dateTo');
	var form = document.getElementById('reportFiltersForm');

	presetBtns.forEach(function(btn) {
		btn.addEventListener('click', function() {
			var preset = this.getAttribute('data-preset');
			presetInput.value = preset;

			if (preset === 'custom') {
				presetBtns.forEach(function(b) { b.classList.remove('active'); });
				this.classList.add('active');
				customDateRange.classList.remove('d-none');
				dateFrom.removeAttribute('disabled');
				dateTo.removeAttribute('disabled');
				dateFrom.setAttribute('name', 'from');
				dateTo.setAttribute('name', 'to');
				if (!dateFrom.value || !dateTo.value) {
					var today = new Date();
					dateTo.value = today.toISOString().split('T')[0];
					var first = new Date(today.getFullYear(), today.getMonth(), 1);
					dateFrom.value = first.toISOString().split('T')[0];
				}
			} else {
				customDateRange.classList.add('d-none');
				dateFrom.setAttribute('disabled', 'disabled');
				dateTo.setAttribute('disabled', 'disabled');
				dateFrom.removeAttribute('name');
				dateTo.removeAttribute('name');
				form.submit();
			}
		});
	});

	// On custom, ensure from/to have name for form submit
	if (presetInput.value === 'custom') {
		dateFrom.setAttribute('name', 'from');
		dateTo.setAttribute('name', 'to');
	} else {
		dateFrom.setAttribute('disabled', 'disabled');
		dateTo.setAttribute('disabled', 'disabled');
		dateFrom.removeAttribute('name');
		dateTo.removeAttribute('name');
	}

	form.addEventListener('submit', function(e) {
		if (presetInput.value === 'custom') {
			dateFrom.removeAttribute('disabled');
			dateTo.removeAttribute('disabled');
			if (!dateFrom.value || !dateTo.value) {
				e.preventDefault();
				alert('Please select From and To dates for Custom range.');
				return false;
			}
		}
	});
});
</script>

<?php if (!empty($sales_trend)): ?>
<script src="<?php echo base_url('assets/template/plugins/apexchart/apexcharts.min.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var trendData = <?php echo json_encode($sales_trend); ?>;
	var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var categories = trendData.map(function(d) {
		var p = d.period;
		if (!p) return '';
		if (p.length === 10) {
			var parts = p.split('-');
			if (parts.length === 3) return parts[2] + ' ' + (months[parseInt(parts[1],10)-1] || '');
		}
		if (p.length === 7) {
			var parts = p.split('-');
			if (parts.length === 2) return (months[parseInt(parts[1],10)-1] || '') + ' ' + parts[0];
		}
		return p;
	});
	var revenueData = trendData.map(function(d) { return parseFloat(d.total_revenue) || 0; });

	var options = {
		series: [{ name: 'Revenue (₹)', data: revenueData }],
		chart: { type: 'bar', height: 280, toolbar: { show: true }, zoom: { enabled: true } },
		plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
		colors: ['#198754'],
		xaxis: { categories: categories, labels: { rotate: -45, style: { fontSize: '11px' } } },
		yaxis: {
			title: { text: 'Revenue (₹)' },
			labels: { formatter: function(v) { return '₹' + (v >= 1000 ? (v/1000).toFixed(1) + 'k' : Math.round(v)); } }
		},
		tooltip: {
			y: { formatter: function(val) { return '₹' + Number(val).toLocaleString('en-IN', {minimumFractionDigits: 2}); } }
		},
		dataLabels: { enabled: false }
	};

	var chartEl = document.getElementById('revenueChart');
	if (chartEl && typeof ApexCharts !== 'undefined') {
		var chart = new ApexCharts(chartEl, options);
		chart.render();
	}
});
</script>
<?php endif; ?>

<?php
$total_orders = isset($sales_summary['order_count']) ? (int)$sales_summary['order_count'] : 0;
$total_revenue = isset($sales_summary['total_revenue']) ? (float)$sales_summary['total_revenue'] : 0;
$avg_order_value = isset($sales_summary['avg_order_value']) ? (float)$sales_summary['avg_order_value'] : 0;
?>
<!-- Row 1: Revenue Chart (col-8) + Orders by Type (col-4) -->
<div class="row">
	<div class="col-8 mb-4">
		<div class="card h-100">
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-6 mb-2">
						<div class="card border-primary">
							<div class="card-body p-0 text-center" style="display: flex; align-items: center;justify-content: center;">
								<div class="d-flex align-items-center justify-content-center">
									<i class="ri-shopping-cart-2-fill text-primary me-2" style="font-size: 20px;"></i>
									<span style="font-size: 14px;">ORDERS: </span>
								</div>
								<?php
								$search_url = base_url('search');
								$params = array();
								if (!empty($date_from)) $params[] = 'date_from=' . urlencode($date_from);
								if (!empty($date_to)) $params[] = 'date_to=' . urlencode($date_to);
								if (!empty($filters['school_id'])) $params[] = 'school_id=' . urlencode($filters['school_id']);
								if (!empty($filters['state'])) $params[] = 'state=' . urlencode($filters['state']);
								if (!empty($filters['city'])) $params[] = 'city=' . urlencode($filters['city']);
								if (!empty($filters['order_type'])) $params[] = 'order_type=' . urlencode($filters['order_type']);
								if (!empty($params)) {
									$search_url .= '?' . implode('&', $params);
								}
								?>
								<a href="<?php echo $search_url; ?>" style="text-decoration: none; color: inherit;">
									<h4 style="font-size: 20px; margin-left: 5px;" class="fw-bold text-primary mb-0"><?php echo number_format($total_orders); ?></h4>
								</a>
							</div>
						</div>
					</div>
					<div class="col-md-6 mb-2">
						<div class="card border-success ">
							<div class="card-body p-0 text-center" style="display: flex; align-items: center;justify-content: center;">
								<div class="d-flex align-items-center justify-content-center">
									<i class="ri-money-rupee-circle-fill text-success me-2" style="font-size: 20px;"></i>
									<span style="font-size: 14px;">REVENUE: </span>
								</div>
								<h4 style="font-size: 20px; margin-left: 5px;" class="fw-bold text-success mb-0">₹<?php echo number_format($total_revenue, 2); ?></h4>
							</div>
						</div>
					</div>
				</div>
				<?php if (!empty($sales_trend)): ?>
				<div id="revenueChart" style="min-height: 280px;"></div>
				<?php else: ?>
				<p class="text-muted text-center py-4 mb-0">No revenue data for this period</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="col-4 mb-4">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between py-2">
				<h6 class="mb-0"><i class="isax isax-category me-2"></i>Orders by Type</h6>
				<a href="<?php echo base_url('reports/export/sales'); ?>?<?php echo $export_params; ?>" class="btn btn-sm btn-outline-primary">Export</a>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover report-table mb-0">
						<thead class="table-light">
							<tr>
								<th>Order Type</th>
								<th class="text-end">Orders</th>
								<th class="text-end">Revenue</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($orders_by_type)): ?>
								<?php foreach ($orders_by_type as $row): ?>
								<tr>
									<td><?php echo htmlspecialchars(ucfirst($row['order_type'])); ?></td>
									<td class="text-end"><?php echo number_format($row['order_count']); ?></td>
									<td class="text-end currency">₹<?php echo number_format($row['total_revenue'], 2); ?></td>
								</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td colspan="3" class="text-center text-muted py-4">No orders for this period</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Row 2: Delivery Overview (full width) -->
<div class="row">
	<div class="col-12 mb-4">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between py-2">
				<h6 class="mb-0"><i class="isax isax-truck me-2"></i>Delivery Overview</h6>
				<a href="<?php echo base_url('reports/export/delivery'); ?>?<?php echo $export_params; ?>" class="btn btn-sm btn-outline-primary">Export</a>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover report-table mb-0">
						<thead class="table-light">
							<tr>
								<th>Courier</th>
								<th class="text-end">Orders</th>
								<th class="text-end">Revenue</th>
								<th class="text-end">Delivered</th>
								<th class="text-end">Returns</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($delivery_performance)): ?>
								<?php foreach ($delivery_performance as $row): ?>
								<tr>
									<td><?php echo htmlspecialchars($row['courier_name']); ?></td>
									<td class="text-end"><?php echo number_format($row['order_count']); ?></td>
									<td class="text-end currency">₹<?php echo number_format($row['total_revenue'], 2); ?></td>
									<td class="text-end text-success"><?php echo number_format($row['delivered_count']); ?></td>
									<td class="text-end text-danger"><?php echo number_format($row['return_count']); ?></td>
								</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td colspan="5" class="text-center text-muted py-4">No delivery data for this period</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Orders by School (Bookset vendors) -->
<?php if (!empty($has_bookset) && !empty($orders_by_school)): ?>
<div class="card mb-4">
	<div class="card-header d-flex align-items-center justify-content-between py-2">
		<h6 class="mb-0"><i class="isax isax-building-4 me-2"></i>Orders by School</h6>
		<a href="<?php echo base_url('reports/export/school'); ?>?<?php echo $export_params; ?>" class="btn btn-sm btn-outline-primary">Export</a>
	</div>
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover report-table mb-0">
				<thead class="table-light">
					<tr>
						<th>School</th>
						<th class="text-end">Orders</th>
						<th class="text-end">Revenue</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders_by_school as $row): ?>
					<tr>
						<td><?php echo htmlspecialchars($row['school_name'] ?: 'Unknown'); ?></td>
						<td class="text-end"><?php echo number_format($row['order_count']); ?></td>
						<td class="text-end currency">₹<?php echo number_format($row['total_revenue'], 2); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php elseif (!empty($has_bookset)): ?>
<div class="card mb-4">
	<div class="card-header py-2"><h6 class="mb-0"><i class="isax isax-building-4 me-2"></i>Orders by School</h6></div>
	<div class="card-body text-center text-muted py-4">No school-wise orders for this period</div>
</div>
<?php endif; ?>

<!-- Orders by Location -->
<div class="row">
	<div class="col-lg-6 mb-4">
		<div class="card h-100">
			<div class="card-header d-flex align-items-center justify-content-between py-2">
				<h6 class="mb-0"><i class="isax isax-map me-2"></i>Orders by State</h6>
				<a href="<?php echo base_url('reports/export/location'); ?>?<?php echo $export_params; ?>" class="btn btn-sm btn-outline-primary">Export</a>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover report-table mb-0">
						<thead class="table-light">
							<tr>
								<th>State</th>
								<th class="text-end">Orders</th>
								<th class="text-end">Revenue</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($orders_by_location)): ?>
								<?php foreach ($orders_by_location as $row): ?>
								<tr>
									<td><?php echo htmlspecialchars($row['location_name']); ?></td>
									<td class="text-end"><?php echo number_format($row['order_count']); ?></td>
									<td class="text-end currency">₹<?php echo number_format($row['total_revenue'], 2); ?></td>
								</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr><td colspan="3" class="text-center text-muted py-4">No location data</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 mb-4">
		<div class="card h-100">
			<div class="card-header py-2"><h6 class="mb-0"><i class="isax isax-location me-2"></i>Orders by City</h6></div>
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-hover report-table mb-0">
						<thead class="table-light">
							<tr>
								<th>City</th>
								<th class="text-end">Orders</th>
								<th class="text-end">Revenue</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($orders_by_city)): ?>
								<?php foreach (array_slice($orders_by_city, 0, 15) as $row): ?>
								<tr>
									<td><?php echo htmlspecialchars($row['location_name']); ?></td>
									<td class="text-end"><?php echo number_format($row['order_count']); ?></td>
									<td class="text-end currency">₹<?php echo number_format($row['total_revenue'], 2); ?></td>
								</tr>
								<?php endforeach; ?>
								<?php if (count($orders_by_city) > 15): ?>
								<tr><td colspan="3" class="text-center text-muted fs-12">Showing top 15 of <?php echo count($orders_by_city); ?> cities</td></tr>
								<?php endif; ?>
							<?php else: ?>
								<tr><td colspan="3" class="text-center text-muted py-4">No city data</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
