<?php
// Timeline partial - used by get_order_timeline (orders list) and can match order view design
date_default_timezone_set('Asia/Kolkata');
?>
<style>
.timeline-list-modal .timeline-item {
  border-left: 2px solid #28a745;
  padding-left: 10px;
  margin-left: 5px;
  margin-bottom: 15px;
  position: relative;
}
.timeline-list-modal .timeline-item:before {
  content: '';
  position: absolute;
  left: -6px;
  top: 0;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #28a745;
}
.timeline-list-modal .timeline-item.completed { border-left-color: #28a745; }
.timeline-list-modal .timeline-item.completed:before { background: #28a745; }
.timeline-list-modal .timeline-item.pending { border-left-color: #ffc107; }
.timeline-list-modal .timeline-item.pending:before { background: #ffc107; }
</style>
<div class="timeline-list-modal">
  <h6 class="mb-3"><?= htmlspecialchars($order_no) ?></h6>
  <?php
  if (empty($timeline_items)) {
    $od = $order_data[0];
    $order_date_display = '';
    if (!empty($od->order_date) && $od->order_date != '0000-00-00 00:00:00') {
      try {
        $date_obj = new DateTime($od->order_date);
        $date_obj->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $order_date_display = $date_obj->format('D, M d, Y, h:i A');
      } catch (Exception $e) {
        $order_date_display = date('D, M d, Y, h:i A', strtotime($od->order_date));
      }
    } else {
      $order_date_display = date('D, M d, Y, h:i A', strtotime($od->order_date));
    }
    ?>
    <div class="timeline-item completed">
      <div><b>✔ Order Placed</b></div>
      <small class="text-muted"><?= $order_date_display ?></small>
    </div>
    <?php
  } else {
    foreach ($timeline_items as $item) {
      $class = !empty($item['completed']) ? 'completed' : 'pending';
      $display_date = '';
      if (!empty($item['date']) && $item['date'] != '0000-00-00 00:00:00') {
        try {
          $date_obj = new DateTime($item['date']);
          $date_obj->setTimezone(new DateTimeZone('Asia/Kolkata'));
          $display_date = $date_obj->format('D, M d, Y, h:i A');
        } catch (Exception $e) {
          $display_date = date('D, M d, Y, h:i A', strtotime($item['date']));
        }
      } else {
        $display_date = !empty($item['date']) ? date('D, M d, Y, h:i A', strtotime($item['date'])) : '';
      }
      ?>
      <div class="timeline-item <?= $class ?>">
        <div><b>✔ <?= htmlspecialchars($item['status']) ?></b></div>
        <small class="text-muted"><?= $display_date ?></small>
        <?php if (!empty($item['notes'])): ?>
        <div class="mt-1"><small class="text-muted"><?= htmlspecialchars($item['notes']) ?></small></div>
        <?php endif; ?>
      </div>
      <?php
    }
  }
  ?>
</div>
