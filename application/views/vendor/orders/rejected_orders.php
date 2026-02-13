<style type="text/css">
   .swal2-title{ font-size: 1.5rem!important; }
   
   .tab-navigation {
    padding: 8px 15px;
    background: white;
    border-top-left-radius: 8px;
    color: black;
    margin-right: 2px;
    border-top-right-radius: 8px;
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.1);
   }
   
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

   <!-- Order Tabs -->
   <div class="row">
      <div class="col-12">
         <ul class="nav nav-tabs brbm0" role="tablist">
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'all' || $order_status == '') ? 'active' : ''; ?>" href="<?php echo base_url('orders/all'); ?>">
                  All Orders
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'pending') ? 'active' : ''; ?>" href="<?php echo base_url('orders/pending'); ?>">
                  New Order <?php if(isset($order_counts['pending']) && $order_counts['pending'] > 0): ?><span class="badge bg-primary ms-1"><?php echo $order_counts['pending']; ?></span><?php endif; ?>
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'processing') ? 'active' : ''; ?>" href="<?php echo base_url('orders/processing'); ?>">
                  Processing <?php if(isset($order_counts['processing']) && $order_counts['processing'] > 0): ?><span class="badge bg-primary ms-1"><?php echo $order_counts['processing']; ?></span><?php endif; ?>
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'out_for_delivery') ? 'active' : ''; ?>" href="<?php echo base_url('orders/out_for_delivery'); ?>">
                  Out for Delivery <?php if(isset($order_counts['out_for_delivery']) && $order_counts['out_for_delivery'] > 0): ?><span class="badge bg-primary ms-1"><?php echo $order_counts['out_for_delivery']; ?></span><?php endif; ?>
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'delivered') ? 'active' : ''; ?>" href="<?php echo base_url('orders/delivered'); ?>">
                  Delivered
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'return') ? 'active' : ''; ?>" href="<?php echo base_url('orders/return'); ?>">
                  Return
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'cancelled') ? 'active' : ''; ?>" href="<?php echo base_url('orders/cancelled-orders'); ?>">
                  Cancelled
               </a>
            </li>
         </ul>
      </div>
   </div>

   <!-- Search Filter -->
   <div class="row mb-3">
      <div class="col-12">
         <div class="card brtop0">
            <div class="card-body">
               <form method="get" action="<?php echo base_url('orders/cancelled-orders'); ?>" class="row">
                  <div class="col-md-3">
                     <label>Keywords</label>
                     <input type="text" name="keywords" class="form-control" value="<?php echo isset($filter_data['keywords']) ? htmlspecialchars($filter_data['keywords']) : ''; ?>" placeholder="Order ID, User Name, Phone...">
                  </div>
                  <div class="col-md-2">
                     <label>Pincode</label>
                     <input type="text" name="pincode" class="form-control" value="<?php echo isset($filter_data['pincode']) ? htmlspecialchars($filter_data['pincode']) : ''; ?>" placeholder="Enter Pincode">
                  </div>
                  <div class="col-md-2">
                     <label>School</label>
                     <select name="school" class="form-control">
                        <option value="">All Schools</option>
                        <?php if(isset($schools) && !empty($schools)): ?>
                           <?php foreach($schools as $school): ?>
                              <option value="<?php echo $school['id']; ?>" <?php echo (isset($filter_data['school']) && $filter_data['school'] == $school['id']) ? 'selected' : ''; ?>>
                                 <?php echo htmlspecialchars($school['name']); ?>
                              </option>
                           <?php endforeach; ?>
                        <?php endif; ?>
                     </select>
                  </div>
                  <div class="col-md-2">
                     <label>Grade</label>
                     <select name="grade" class="form-control">
                        <option value="">All Grades</option>
                        <?php if(isset($grades) && !empty($grades)): ?>
                           <?php foreach($grades as $grade): ?>
                              <option value="<?php echo $grade['id']; ?>" <?php echo (isset($filter_data['grade']) && $filter_data['grade'] == $grade['id']) ? 'selected' : ''; ?>>
                                 <?php echo htmlspecialchars($grade['name']); ?>
                              </option>
                           <?php endforeach; ?>
                        <?php endif; ?>
                     </select>
                  </div>
                  <div class="col-md-3">
                     <label>&nbsp;</label>
                     <div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="<?php echo base_url('orders/cancelled-orders'); ?>" class="btn btn-secondary">Clear</a>
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
                     Cancelled Orders (<?= $total_count; ?>)
                  </div>
               </div>
            </div>

            <div class="col-md-12 table-responsive">
               <table class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th>Order ID</th>
                        <th>Order Type</th>
                        <th>User ID</th>
                        <th>Product Name</th>
                        <th>Address</th>
                        <th>School</th>
                        <th>Grade</th>
                        <th>Order Date</th>
                        <th>Cancelled Date</th>
                        <th>Payable Amount</th>
                        <th>Refund Amount</th>
                        <th>Payment Status</th>
                        <th>Payment ID</th>
                        <th>Razorpay Order ID</th>
                        <th>Invoice Number</th>
                        <th>Remark</th>
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
                              <td><?php echo isset($item['user_id']) ? $item['user_id'] : '-'; ?></td>
                              <td><?php echo isset($item['product_name']) ? $item['product_name'] : '-'; ?></td>
                              <td><?php echo isset($item['address']) ? $item['address'] : '-'; ?></td>
                              <td><?php echo isset($item['school_name']) ? $item['school_name'] : '-'; ?></td>
                              <td><?php echo isset($item['grade_name']) ? $item['grade_name'] : '-'; ?></td>
                              <td><?php echo $item['order_date']; ?></td>
                              <td><?php echo $item['cancelled_date']; ?></td>
                              <td><?php echo isset($item['payable_amt']) ? number_format($item['payable_amt'], 2) : '0.00'; ?></td>
                              <td><?php echo isset($item['refund_amt']) ? number_format($item['refund_amt'], 2) : '0.00'; ?></td>
                              <td>
                                 <span class="badge badge-<?php echo ($item['payment_status'] == 'success') ? 'success' : 'info'; ?>">
                                    <?php echo strtoupper($item['payment_status']); ?>
                                 </span>
                              </td>
                              <td><?php echo isset($item['payment_id']) ? $item['payment_id'] : '-'; ?></td>
                              <td><?php echo isset($item['razorpay_order_id']) ? $item['razorpay_order_id'] : '-'; ?></td>
                              <td><?php echo isset($item['invoice_no']) ? $item['invoice_no'] : '-'; ?></td>
                              <td><?php echo isset($item['remark']) && !empty($item['remark']) ? htmlspecialchars($item['remark']) : '-'; ?></td>
                              <td nowrap="">
                                 <a href="<?php echo base_url('orders/view/' . $item['order_unique_id']); ?>" class="btn btn-primary btn_edit" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>
                              </td>
                           </tr>
                        <?php endforeach; 
                     else: ?>
                        <tr>
                           <td colspan="17">
                              <p class="notf">
                                 <?php 
                                 $has_filters = !empty($filter_data['keywords']) || !empty($filter_data['pincode']) || !empty($filter_data['school']) || !empty($filter_data['grade']);
                                 if ($has_filters): 
                                 ?>
                                    No orders found with the current filters. Please try changing your filter criteria.
                                 <?php else: ?>
                                    Data not found
                                 <?php endif; ?>
                              </p>
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


