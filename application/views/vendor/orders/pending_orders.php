<style type="text/css">
   .swal2-title{ font-size: 1.5rem!important; }
   
   /* Pagination Styles */
   .pagination_item_block {
      margin-top: 20px;
      margin-bottom: 20px;
   }
   
   .pagination_item_block .pagination {
      margin-bottom: 0;
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
   }
   
   .pagination_item_block .pagination .page-item {
      margin: 0;
   }
   
   .pagination_item_block .pagination .page-link {
      padding: 8px 12px;
      margin: 0;
      border: 1px solid #dee2e6;
      color: #495057;
      background-color: #fff;
      border-radius: 4px;
      text-decoration: none;
      display: inline-block;
      min-width: 38px;
      text-align: center;
   }
   
   .pagination_item_block .pagination .page-link:hover {
      background-color: #e9ecef;
      border-color: #adb5bd;
      color: #495057;
   }
   
   .pagination_item_block .pagination .page-item.active .page-link {
      background-color: #007bff;
      border-color: #007bff;
      color: #fff;
      z-index: 1;
   }
   
   .pagination_item_block .pagination .page-item.disabled .page-link {
      color: #6c757d;
      pointer-events: none;
      background-color: #fff;
      border-color: #dee2e6;
      opacity: 0.6;
   }
</style>

<div class="mobile_view home">

   <link rel="stylesheet" href="<?php echo base_url() ?>assets/alertify/alertify.min.css" />
   <link rel="stylesheet" href="<?php echo base_url() ?>assets/alertify/default.min.css" />
   <script src="<?php echo base_url() ?>assets/alertify/alertify.min.js"></script>
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/bootstrap-select.css">
   <script src="<?php echo base_url(); ?>assets/dist/js/bootstrap-select.js"></script>

   <!-- Search Filter -->
   <div class="row mb-3">
      <div class="col-12">
         <div class="card">
            <div class="card-body">
               <form method="get" action="<?php echo base_url('orders/pending-orders'); ?>" class="row">
                  <div class="col-md-4">
                     <label>Keywords</label>
                     <input type="text" name="keywords" class="form-control" value="<?php echo isset($filter_data['keywords']) ? htmlspecialchars($filter_data['keywords']) : ''; ?>" placeholder="Order ID, User Name, Phone...">
                  </div>
                  <!-- <div class="col-md-4">
                     <label>Date Range</label>
                     <input type="text" name="date_range" class="form-control daterange" value="<?php echo isset($filter_data['date_range']) ? htmlspecialchars($filter_data['date_range']) : ''; ?>" placeholder="Select Date Range">
                  </div> -->
                  <div class="col-md-4">
                     <label>&nbsp;</label>
                     <div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="<?php echo base_url('orders/pending-orders'); ?>" class="btn btn-secondary">Clear</a>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-12">
         <div class="card mrg_bottom">
            <div class="col-md-12" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 15px;">
               <div class="d-block left">
                  <div class="page_title" style="font-size: 20px;font-weight: 500;color:#000;">
                     Pending Orders (<?= $total_count; ?>)
                  </div>
               </div>
            </div>

            <div class="col-md-12 table-responsive">
               <table class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th>Order ID</th>
                        <th>Order Type</th>
                        <th>User Name</th>
                        <th>User Phone</th>
                        <th>Order Date</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Payment ID</th>
                        <th>Razorpay Order ID</th>
                        <th class="cat_action_list">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     if (!empty($order_list)):
                        foreach ($order_list as $key => $item): ?>
                           <tr class="item_holder">
                              <td><a href="<?php echo base_url('orders/view/' . $item['order_unique_id']); ?>"><?php echo $item['order_unique_id']; ?></a></td>
                              <td><?php echo isset($item['order_type']) ? $item['order_type'] : '-'; ?></td>
                              <td><?php echo $item['user_name']; ?></td>
                              <td><?php echo $item['user_phone']; ?></td>
                              <td><?php echo $item['date']; ?></td>
                              <td><?php echo isset($item['payment_method']) ? ucfirst($item['payment_method']) : '-'; ?></td>
                              <td>
                                 <span class="badge badge-<?php echo ($item['payment_status'] == 'pending') ? 'warning' : 'danger'; ?>">
                                    <?php echo strtoupper($item['payment_status']); ?>
                                 </span>
                              </td>
                              <td><?php echo $item['payment_id']; ?></td>
                              <td><?php echo isset($item['razorpay_order_id']) ? $item['razorpay_order_id'] : '-'; ?></td>
                              <td nowrap="">
                                 <a href="<?php echo base_url('orders/view/' . $item['order_unique_id']); ?>" class="btn btn-primary btn_edit" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>
                              </td>
                           </tr>
                        <?php endforeach; 
                     else: ?>
                        <tr>
                           <td colspan="10">
                              <p class="notf">Data not found</p>
                           </td>
                        </tr>
                     <?php endif; ?>
                  </tbody>
               </table>
               <div class="clearfix"></div>
            </div>

            <div class="clearfix"></div>
            <div class="col-md-12 col-xs-12">
               <div class="pagination_item_block">
                  <?php if (isset($pagination) && !empty($pagination)): ?>
                     <?php echo $pagination; ?>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   $(document).ready(function() {
      // Date range picker
      if ($('input[name="date_range"]').length) {
         $('input[name="date_range"]').daterangepicker({
            opens: 'left',
            locale: {
               format: 'DD-MM-YYYY',
               cancelLabel: 'Clear'
            },
         }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
         });
      }
   });
</script>

