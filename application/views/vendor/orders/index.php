<style type="text/css">
   .tab-navigation {
    padding: 8px 15px;
    background: white;
    border-top-left-radius: 8px;
    color: black;
    margin-right: 2px;
    border-top-right-radius: 8px;
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.1);
   }

   .tab-navigation.active {
      background: #4caf50;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      border-bottom-right-radius: 0px;
      border-bottom-left-radius: 0px;
      box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.0);
      color: white;
   }   
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
   
   /* Order count badges on tabs */
   .tab-navigation .badge {
      font-size: 0.75rem;
      padding: 0.25em 0.5em;
      border-radius: 10px;
      font-weight: 600;
   }
   
   .tab-navigation.active .badge {
      background-color: rgba(255, 255, 255, 0.3) !important;
      color: white !important;
   }
   
   /* Status labels - base */
   .label {
      display: inline-block;
      padding: 0.35em 0.65em;
      font-size: 12px;
      font-weight: 600;
      line-height: 1;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: 4px;
      border: 1px solid transparent;
   }
   
   /* Order status labels - outline style */
   .label-default {
    background-color: #6c757d !important;
    color: white;
    
   }
   
   .label-warning {
    background-color: rgb(255 133 0) !important;
    color: white;
   }

   .label-primary {
    background-color: #007bff !important;
    color: white;
   }
   .label-info {
    background-color: #17a2b8 !important;
    color: white;
   }
   
   .label-success {
    background-color: #28a745 !important;
    color: white;
   }


   .badge-payment-school {
     
      background-color: #007bff !important;
      color: #fff !important;
      padding: 0.35em 0.65em;
      border-radius: 4px;
   }

   .badge-deliver-school, .badge-payment-school {
    background-color: #ef1e36;
    color: #fff !important;
    padding: 0.35em 0.65em;
    border-radius: 4px;
   }
   /* Deliver at Address - outline style */
   .badge-address {
      background-color: rgb(239 30 54 / 9%) !important;
      border: 1px solid #ef1e36;
      color: #ef1e36 !important;
      padding: 0.35em 0.65em;
      border-radius: 4px;
   }
   /* Cash On Delivery - outline style */
   .badge-payment-cod {
      background-color: rgb(0 123 255 / 9%) !important;
      border: 1px solid #007bff;
      color: #007bff !important;
      font-size: 12px !important;
      border-radius: 4px;
      padding: 0.35em 0.65em;
   }
   /* Other payment methods - outline style */
   .badge-payment-other {
      background-color: rgba(108, 117, 125, 0.15) !important;
      border: 1px solid #6c757d;
      color: #6c757d !important;
      padding: 0.35em 0.65em;
      border-radius: 4px;
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
               <a class="nav-link tab-navigation <?php echo ($order_status == 'all' || $order_status == '') ? 'active' : ''; ?>" href="<?php echo base_url('orders'); ?>">
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
               <a class="nav-link tab-navigation <?php echo ($order_status == 'ready_for_shipment') ? 'active' : ''; ?>" href="<?php echo base_url('orders/ready_for_shipment'); ?>">
                  Ready for Shipment <?php if(isset($order_counts['ready_for_shipment']) && $order_counts['ready_for_shipment'] > 0): ?><span class="badge bg-primary ms-1"><?php echo $order_counts['ready_for_shipment']; ?></span><?php endif; ?>
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
               <form method="get" action="<?php echo base_url('orders/' . $order_status); ?>" class="row">
                  <input type="hidden" name="per_page" value="<?php echo isset($filter_data['per_page']) ? (int)$filter_data['per_page'] : 10; ?>">
                  <div class="col-md-2">
                     <label>Keywords</label>
                     <input type="text" name="keywords" class="form-control" value="<?php echo isset($filter_data['keywords']) ? htmlspecialchars($filter_data['keywords']) : ''; ?>" placeholder="Order ID, User Name, Phone, Invoice Number...">
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
                  <div class="col-md-2">
                     <label>Payment Method</label>
                     <select name="payment_method" class="form-control">
                        <option value="">All Payment Methods</option>
                        <option value="cod" <?php echo (isset($filter_data['payment_method']) && $filter_data['payment_method'] == 'cod') ? 'selected' : ''; ?>>Cash On Delivery</option>
                        <option value="non_cod" <?php echo (isset($filter_data['payment_method']) && $filter_data['payment_method'] == 'non_cod') ? 'selected' : ''; ?>>
                           Online / Non-COD
                        </option>
                        <option value="payment_at_school" <?php echo (isset($filter_data['payment_method']) && $filter_data['payment_method'] == 'payment_at_school') ? 'selected' : ''; ?>>Payment at School</option>
                     </select>
                  </div>
                  <div class="col-md-2">
                     <label>Delivery Type</label>
                     <select name="delivery_type" class="form-control">
                        <option value="">All</option>
                        <option value="school" <?php echo (isset($filter_data['delivery_type']) && $filter_data['delivery_type'] == 'school') ? 'selected' : ''; ?>>Deliver at School</option>
                        <option value="address" <?php echo (isset($filter_data['delivery_type']) && $filter_data['delivery_type'] == 'address') ? 'selected' : ''; ?>>Deliver at Address</option>
                     </select>
                  </div>
                  <div class="col-md-2">
                     <label>Pincode</label>
                     <input type="text" name="pincode" class="form-control" value="<?php echo isset($filter_data['pincode']) ? htmlspecialchars($filter_data['pincode']) : ''; ?>" placeholder="Enter Pincode">
                  </div>
                  <div class="col-md-2">
                     <label>&nbsp;</label>
                     <div>
                        <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        <a href="<?php echo base_url('orders/' . $order_status); ?>" class="btn btn-secondary btn-sm ms-1">Clear</a>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
	   <?php if ($order_status == 'processing' || $order_status == 'ready_for_shipment' || $order_status == 'out_for_delivery'): ?>
		  <div class="card-body py-2 px-3 pb-0">
			 <ul class="nav nav-tabs brbm0" id="courierFilterTabs" role="tablist">
				 <li class="nav-item" role="presentation">
				   <button class="nav-link tab-navigation active" id="tab-third-party" type="button" role="tab" data-courier-filter="third_party">3rd Party</button>
				</li>					 
				<li class="nav-item" role="presentation">
				   <button class="nav-link tab-navigation " id="tab-self-delivery" type="button" role="tab" data-courier-filter="manual">Self Delivery</button>
				</li>                    
			 </ul>
		  </div>
	   <?php endif; ?>
      <div class="col-12">
         <!-- <div class="card f-filter">
            <div class="card-body py-1 my-0"> -->
               <div class="card mrg_bottom">
                 

                  <?php if ($order_status == 'all' || $order_status == ''): ?>
                     <!-- No form for "All Orders" view -->
                  <?php elseif ($order_status == 'pending'): ?>
                     <?php echo form_open(base_url('orders/move_to_processing'), ['id' => 'form_', 'class' => 'add-ajax-redirect-form', 'enctype' => 'multipart/form-data']); ?>
                  <?php elseif ($order_status == 'processing'): ?>
                     <?php echo form_open(base_url('orders/move_to_processing'), ['id' => 'form_', 'class' => 'add-ajax-redirect-form', 'enctype' => 'multipart/form-data']); ?>
                  <?php elseif ($order_status == 'ready_for_shipment'): ?>
                     <?php echo form_open(base_url('orders/move_to_out_for_delivery'), ['id' => 'form_', 'class' => 'add-ajax-redirect-form', 'enctype' => 'multipart/form-data']); ?>
                  <?php elseif ($order_status == 'out_for_delivery'): ?>
                     <?php echo form_open(base_url('orders/move_to_delivered'), ['id' => 'form_', 'class' => 'add-ajax-redirect-form', 'enctype' => 'multipart/form-data']); ?>
                  <?php endif; ?>

                  <div class="col-md-12" style="margin-bottom: 15px;">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-block left">
                           <div class="page_title" style="font-size: 20px;font-weight: 500;color:#000;">
                              <?php 
                              if ($order_status == 'all' || $order_status == '') {
                                 echo 'All Orders';
                              } elseif ($order_status == 'pending') {
                                 echo 'New Order';
                              } elseif ($order_status == 'processing') {
                                 echo 'Processing';
                              } elseif ($order_status == 'ready_for_shipment') {
                                 echo 'Ready for Shipment';
                              } elseif ($order_status == 'out_for_delivery') {
                                 echo 'Out for Delivery';
                              } elseif ($order_status == 'delivered') {
                                 echo 'Delivered';
                              } elseif ($order_status == 'return') {
                                 echo 'Return';
                              } else {
                                 echo ucfirst($order_status);
                              }
                              ?> Orders (<?= $total_count; ?>)
                           </div>
                        </div>

                        <?php
                        $export_params = array_filter(array(
                           'order_status' => $order_status ?: 'all',
                           'keywords' => isset($filter_data['keywords']) ? $filter_data['keywords'] : '',
                           'pincode' => isset($filter_data['pincode']) ? $filter_data['pincode'] : '',
                           'school' => isset($filter_data['school']) ? $filter_data['school'] : '',
                           'grade' => isset($filter_data['grade']) ? $filter_data['grade'] : '',
                           'payment_method' => isset($filter_data['payment_method']) ? $filter_data['payment_method'] : '',
                           'delivery_type' => isset($filter_data['delivery_type']) ? $filter_data['delivery_type'] : '',
                           'date_range' => isset($filter_data['date_range']) ? $filter_data['date_range'] : ''
                        ));
                        $export_url = base_url('orders/export?' . http_build_query($export_params));
                        ?>
                        <div class="pull-right d-flex gap-2 flex-wrap align-items-center">
                           <a href="<?php echo htmlspecialchars($export_url); ?>" class="btn btn-outline-success btn-sm" target="_blank" title="Export current list to Excel">
                              <i class="fa fa-file-excel-o me-1"></i> Export to Excel
                           </a>
                        <?php if ($order_status == 'all' || $order_status == ''): ?>
                        <!-- No other action buttons for "All Orders" view -->
                        <?php elseif ($order_status == 'pending'): ?>
                           <button type="button" id="btn_bulk_self_delivery" class="btn btn-outline-primary waves-effect waves-light btn-md mb-0" disabled>
                              <i class="fa fa-truck me-1"></i> Self Delivery (<span class="total_orders">0</span>)
                           </button>
                           <button type="button" id="btn_bulk_3rd_party" class="btn btn-outline-info waves-effect waves-light btn-md mb-0" disabled>
                              <i class="fa fa-shipping-fast me-1"></i> 3rd Party (<span class="total_orders">0</span>)
                           </button>
                        <?php elseif ($order_status == 'processing'): ?>
                           <button type="button" id="btn_bulk_print_labels" class="btn btn-outline-primary waves-effect waves-light btn-md mb-0" disabled>
                              <i class="fa fa-print me-1"></i> Print Shipping Labels (<span class="total_orders">0</span>)
                           </button>
                           <button type="button" id="btn_bulk_download_labels" class="btn btn-outline-secondary waves-effect waves-light btn-md mb-0" disabled>
                              <span id="bulkLabelsText">
                                 <i class="fa fa-file-pdf-o me-1"></i> Download Shipping Labels (<span class="total_orders">0</span>)
                              </span>
                              <span id="bulkLabelsSpinner" style="display:none;">
                                 <i class="fa fa-spinner fa-spin me-1"></i> Generating &amp; Downloading...
                              </span>
                           </button>
                        <?php elseif ($order_status == 'ready_for_shipment'): ?>
                           <button type="button" id="btn_bulk_print_labels" class="btn btn-outline-primary waves-effect waves-light btn-md mb-0" disabled>
                              <i class="fa fa-print me-1"></i> Print Shipping Labels (<span class="total_orders">0</span>)
                           </button>
                           <button type="button" id="btn_bulk_download_labels" class="btn btn-outline-secondary waves-effect waves-light btn-md mb-0" disabled>
                              <span id="bulkLabelsText">
                                 <i class="fa fa-file-pdf-o me-1"></i> Download Shipping Labels (<span class="total_orders">0</span>)
                              </span>
                              <span id="bulkLabelsSpinner" style="display:none;">
                                 <i class="fa fa-spinner fa-spin me-1"></i> Generating &amp; Downloading...
                              </span>
                           </button>
                           <button type="button" name="button" id="btn_out_for_delivery" value="update" class="btn btn-primary waves-effect waves-light btn-md mb-0" disabled>
                              Move to Out for Delivery (<span class="total_orders">0</span>)
                           </button>
                        <?php elseif ($order_status == 'out_for_delivery'): ?>
                           <button type="button" name="button" id="btn_delivered" value="update" class="btn btn-primary waves-effect waves-light btn-md mb-0" disabled>
                              Move to Delivered (<span class="total_orders">0</span>)
                           </button>
                        <?php endif; ?>
                     </div>
                  </div>

                  <div class="col-md-12 table-responsive">
                     <table class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <?php if ($order_status == 'all' || $order_status == 'pending' || $order_status == '' || $order_status == 'processing' || $order_status == 'ready_for_shipment' || $order_status == 'out_for_delivery'): ?>
                                 <th class="flex-center center">
                                    <div class="checkbox checkbox-primary">
                                       <input type="checkbox" class="package_change" id="checkAll_order" value="1">
                                       <label for="checkAll_order">&nbsp;</label>
                                    </div>
                                 </th>
                              <?php endif; ?>
                              <th>Order ID</th>
                              <?php if ($order_status == 'all' || $order_status == ''): ?>
                                 <th>Status</th>
                              <?php endif; ?>
                              <th>User Details</th>
                              <th>Product Name</th>
                              <th>Address</th>
                              <th>School</th>
                              <th>Grade</th>
                              <th>Delivery</th>
                              <th nowrap="">
                                 <?php
                                 if ($order_status == 'all' || $order_status == '') {
                                    echo 'Order Date';
                                 } elseif ($order_status == 'pending') {
                                    echo 'Order Date';
                                 } elseif ($order_status == 'processing') {
                                    echo 'Processing Date';
                                 } elseif ($order_status == 'ready_for_shipment') {
                                    echo 'Ready Date';
                                 } elseif ($order_status == 'out_for_delivery') {
                                    echo 'Shipment Date';
                                 } elseif ($order_status == 'delivered') {
                                    echo 'Delivery Date';
                                 } elseif ($order_status == 'return') {
                                    echo 'Return Date';
                                 } else {
                                    echo 'Order Date';
                                 }
                                 ?>
                              </th>
                              <th>Payment Method</th>
                              <th>Shipping Company</th>
                              <th>AWB Number</th>
                              <th>Invoice Number</th>
                              <th class="cat_action_list">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $i = 1;
                           $ci = &get_instance();
						   $prev_has_label = null;
                           $show_label_batches = ($order_status == 'processing' || $order_status == 'ready_for_shipment');
                           if (!empty($order_list)):
                              foreach ($order_list as $key => $item): 
									$has_label = $show_label_batches && (!empty($item['shipping_label']) || !empty($item['awb_no']));
                                 // Section divider between printed and awaiting-label batches
                                 if ($show_label_batches && $prev_has_label === true && $has_label === false):
                                    $colspan = 16;
                                 ?>
                                 <tr class="table-light">
                                    <td colspan="<?php echo $colspan; ?>" class="py-2 text-muted small fw-bold">
                                       <i class="fa fa-print me-1"></i> ——— Awaiting Label ———
                                    </td>
                                 </tr>
                                 <?php
                                 endif;
                                 $prev_has_label = $has_label;
                                 // Determine if order is actionable (can be moved to next status)
                                 $is_actionable = false;
                                 if ($order_status == 'all' || $order_status == '') {
                                    $is_actionable = ($item['status'] == '1' || $item['status'] == '2' || $item['status'] == '6' || $item['status'] == '3');
                                 } else {
                                    $is_actionable = ($order_status == 'pending' || $order_status == 'processing' || $order_status == 'ready_for_shipment' || $order_status == 'out_for_delivery');
                                 }
                                 
                                 // Get status label and class
                                 $status_label = '';
                                 $status_class = '';
                                 switch ($item['status']) {
                                    case '1':
                                       $status_label = 'New Order';
                                       $status_class = 'label-default';
                                       break;
                                    case '2':
                                       $status_label = 'Processing';
                                       $status_class = 'label-warning';
                                       break;
                                    case '6':
                                       $status_label = 'Ready for Shipment';
                                       $status_class = 'label-primary';
                                       break;
                                    case '3':
                                       $status_label = 'Out for Delivery';
                                       $status_class = 'label-info';
                                       break;
                                    case '4':
                                       $status_label = 'Delivered';
                                       $status_class = 'label-success';
                                       break;
                                    case '7':
                                       $status_label = 'Return';
                                       $status_class = 'label-danger';
                                       break;
                                    default:
                                       $status_label = 'Unknown';
                                       $status_class = 'label-default';
                                       break;
                                 }
                                 $is_payment_at_school = isset($item['is_payment_at_school']) && $item['is_payment_at_school'];
                                 $is_deliver_at_school = isset($item['is_deliver_at_school']) && $item['is_deliver_at_school'];
                                 $courier_type = isset($item['courier']) ? $item['courier'] : '';
                              ?>
                                 <tr class="item_holder" data-courier="<?php echo htmlspecialchars($courier_type); ?>" data-order-unique-id="<?php echo htmlspecialchars(isset($item['order_unique_id']) ? $item['order_unique_id'] : ''); ?>">
                                    <?php if ($is_actionable): ?>
                                       <td>
                                          <div class="checkbox checkbox-primary">
                                             <input type="checkbox" class="package_change order_id" id="order_<?php echo $item['id']; ?>" name="order_id[]" value="<?php echo $item['id']; ?>" onclick="getCount()">
                                             <label for="order_<?php echo $item['id']; ?>">&nbsp;</label>
                                          </div>
                                       </td>
                                    <?php elseif ($order_status == 'all' || $order_status == ''): ?>
                                       <td></td>
                                    <?php endif; ?>
                                    <td>
                                       <a href="<?php echo base_url('orders/view/' . $item['order_unique_id']); ?>" class="text-primary fw-bold" style="text-decoration: underline;"><?php echo $item['order_unique_id']; ?></a>
                                       <?php if ($has_label): ?>
                                       <i class="fa fa-print text-success ms-1" data-toggle="tooltip" title="Label Printed"></i>
                                       <?php endif; ?>
                                    </td>
                                    <?php if ($order_status == 'all' || $order_status == ''): ?>
                                       <td><span class="label <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
                                    <?php endif; ?>
                                    <td>
                                       <div><?php echo $item['user_name']; ?></div>
                                       <small class="text-muted"><?php echo $item['user_phone']; ?></small>
                                    </td>
                                    <td><?php echo isset($item['product_name']) ? $item['product_name'] : '-'; ?></td>
                                    <td><?php echo isset($item['address']) ? $item['address'] : '-'; ?></td>
                                    <td><?php echo isset($item['school_name']) ? $item['school_name'] : '-'; ?></td>
                                    <td><?php echo isset($item['grade_name']) ? $item['grade_name'] : '-'; ?></td>
                                    <td><?php 
                                        if ($is_deliver_at_school) {
                                            echo '<span class="badge badge-pill badge-deliver-school">Deliver at School</span>';
                                        } else {
                                            echo '<span class="badge badge-pill badge-address">Deliver at Address</span>';
                                        }
                                    ?></td>
                                    <td><?php echo $item['date']; ?></td>
                                    <td><?php 
                                        $payment_method_display = $item['payment_method'];
                                        if($payment_method_display == 'payment_at_school' || $payment_method_display == 'payment_at_scho') {
                                            $payment_method_display = 'Payment at School';
                                            echo '<span class="badge badge-pill badge-payment-school">' . htmlspecialchars($payment_method_display) . '</span>';
                                        } elseif($payment_method_display == 'cod') {
                                            $payment_method_display = 'Cash On Delivery';
                                            echo '<span class="badge badge-pill badge-payment-cod">' . htmlspecialchars($payment_method_display) . '</span>';
                                        } else {
                                            $payment_method_display = ucfirst(str_replace('_', ' ', $payment_method_display));
                                            echo '<span class="badge badge-pill badge-payment-other">' . htmlspecialchars($payment_method_display) . '</span>';
                                        }
                                    ?></td>
                                    <td>
                                       <?php
                                       $display_courier = '-';
                                       // Prefer explicit 3rd party provider label when available
                                       if (!empty($item['third_party_provider'])) {
                                          $display_courier = ucfirst($item['third_party_provider']);
                                       } elseif (!empty($item['courier_name']) && $item['courier_name'] !== '-') {
                                          $display_courier = $item['courier_name'];
                                       } elseif (!empty($courier_type)) {
                                          $display_courier = ucfirst(str_replace('_', ' ', $courier_type));
                                       }
                                       echo htmlspecialchars($display_courier);
                                       ?>
                                    </td>
                                    <td>
                                       <?php
                                       if (!empty($item['awb_no'])) {
                                          echo '<code>' . htmlspecialchars($item['awb_no']) . '</code>';
                                       } else {
                                          echo '<span class="text-muted">-</span>';
                                       }
                                       ?>
                                    </td>
                                    <td><?php echo $item['invoice_no']; ?></td>

                                    <td nowrap="">
                                       <a href="<?php echo base_url('orders/view/' . $item['order_unique_id']); ?>" class="btn btn-sm btn-primary btn_edit" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>
                                       <button type="button" class="btn btn-outline-primary btn-sm btn-timeline ms-1" data-order-id="<?php echo htmlspecialchars($item['order_unique_id']); ?>" data-toggle="tooltip" title="Order Timeline"><i class="fa fa-history"></i></button>
                                       <?php if ($order_status == 'processing'): ?>
                                       <button type="button" class="btn btn-sm btn-outline-primary btn-print ms-1" data-order-id="<?php echo htmlspecialchars($item['order_unique_id']); ?>" data-toggle="tooltip" title="Print Label">
                                            <i class="fa fa-print"></i>
                                       </button>
                                       <?php endif; ?>
                                    </td>
                                 </tr>
                              <?php endforeach; 
                           else: ?>
                              <tr>
                                 <td colspan="<?php
                                    // Base columns (without checkbox/status): Order ID, User, Product, Address, School, Grade,
                                    // Delivery, Date, Payment, Shipping Company, AWB, Invoice, Action = 13
                                    $colspan = 15; // default when there is a checkbox (most views)
                                    if ($order_status == 'all' || $order_status == '') {
                                       // + Status column
                                       $colspan = 17;
                                    } elseif ($order_status == 'pending' || $order_status == 'processing' || $order_status == 'ready_for_shipment' || $order_status == 'out_for_delivery') {
                                       $colspan = 16;
                                    } else {
                                       // No checkbox for delivered/return, so 14 (no status) or 15 (with status)
                                       $colspan = 14;
                                    }
                                    echo $colspan;
                                 ?>">
                                    <p class="notf">
                                       <?php
                                       $has_filters = !empty($filter_data['keywords']) || !empty($filter_data['pincode']) || !empty($filter_data['school']) || !empty($filter_data['grade']) || !empty($filter_data['payment_method']);
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
                     <div class="pagination_item_block d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <?php
                        $total_pages = isset($total_pages) ? (int)$total_pages : 0;
                        $current_page = isset($current_page) ? (int)$current_page : 1;
                        $pagination_base = isset($pagination_base) ? $pagination_base : 'orders';
                        $pg_filters = isset($filter_data) ? array_filter((array)$filter_data) : array();
                        unset($pg_filters['order_status']); // Remove order_status since it's already in the URL path
                        $per_page_val = isset($filter_data['per_page']) ? (int)$filter_data['per_page'] : 10;
                        $allowed_per_page = array(10, 25, 50, 100);
                        ?>
                        <?php
                        $pg_params_for_perpage = $pg_filters;
                        unset($pg_params_for_perpage['per_page'], $pg_params_for_perpage['page'], $pg_params_for_perpage['order_status']);
                        $pg_params_str = http_build_query(array_filter($pg_params_for_perpage));
                        ?>
                        <div class="d-flex align-items-center gap-2">
                           <label class="mb-0 text-muted small">Show</label>
                           <select class="form-select form-select-sm" id="perPageSelect" style="width: auto; min-width: 70px;" data-base="<?php echo htmlspecialchars(base_url($pagination_base), ENT_QUOTES); ?>" data-params="<?php echo htmlspecialchars($pg_params_str, ENT_QUOTES); ?>">
                              <?php foreach ($allowed_per_page as $opt): ?>
                                 <option value="<?php echo $opt; ?>" <?php echo ($per_page_val == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                              <?php endforeach; ?>
                           </select>
                           <span class="text-muted small">per page</span>
                        </div>
                        <?php if (!empty($order_list) && $total_pages > 1): ?>
                           <nav aria-label="Page navigation" class="d-flex justify-content-center flex-grow-1">
                              <ul class="pagination pagination-sm mb-0 justify-content-center">
                                 <?php if ($current_page > 1): ?>
                                    <li class="page-item">
                                       <a class="page-link" href="<?php echo base_url($pagination_base . '?' . http_build_query(array_merge($pg_filters, array('page' => $current_page - 1)))); ?>">Previous</a>
                                    </li>
                                 <?php else: ?>
                                    <li class="page-item disabled">
                                       <span class="page-link">Previous</span>
                                    </li>
                                 <?php endif; ?>

                                 <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if ($i == $current_page): ?>
                                       <li class="page-item active">
                                          <span class="page-link"><?php echo $i; ?></span>
                                       </li>
                                    <?php else: ?>
                                       <li class="page-item">
                                          <a class="page-link" href="<?php echo base_url($pagination_base . '?' . http_build_query(array_merge($pg_filters, array('page' => $i)))); ?>"><?php echo $i; ?></a>
                                       </li>
                                    <?php endif; ?>
                                 <?php endfor; ?>

                                 <?php if ($current_page < $total_pages): ?>
                                    <li class="page-item">
                                       <a class="page-link" href="<?php echo base_url($pagination_base . '?' . http_build_query(array_merge($pg_filters, array('page' => $current_page + 1)))); ?>">Next</a>
                                    </li>
                                 <?php else: ?>
                                    <li class="page-item disabled">
                                       <span class="page-link">Next</span>
                                    </li>
                                 <?php endif; ?>
                              </ul>
                           </nav>
                        <?php elseif (isset($pagination) && !empty($pagination)): ?>
                           <?php echo $pagination; ?>
                        <?php endif; ?>
                     </div>
                  </div>

                  <?php if ($order_status == 'pending'): ?>
                     <input type="submit" name="submit_orderc" class="submit_orderc" value="1" style="display:none;">
                     <?php echo form_close(); ?>
                  <?php elseif ($order_status == 'processing'): ?>
                     <input type="submit" name="submit_orderc" class="submit_orderc" value="1" style="display:none;">
                     <?php echo form_close(); ?>
                  <?php elseif ($order_status == 'ready_for_shipment'): ?>
                     <input type="submit" name="submit_orderc" class="submit_orderc" value="1" style="display:none;">
                     <?php echo form_close(); ?>
                  <?php elseif ($order_status == 'out_for_delivery'): ?>
                     <input type="submit" name="submit_orderc" class="submit_orderc" value="1" style="display:none;">
                     <?php echo form_close(); ?>
                  <?php endif; ?>
               </div>
            <!-- </div>
         </div> -->
      </div>
   </div>
</div>

<!-- Hidden form for bulk shipping label download (must be outside other forms) -->
<form id="bulkDownloadShippingForm"
      method="post"
      action="<?php echo base_url('orders/bulk_download_shipping_labels'); ?>"
      target="_blank"
      style="display:none;">
   <input type="hidden"
          name="<?php echo $this->security->get_csrf_token_name(); ?>"
          value="<?php echo $this->security->get_csrf_hash(); ?>">
</form>

<!-- Bulk download progress modal -->
<div id="bulkDownloadProgressModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" style="background: rgba(0,0,0,0.6);">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body text-center py-5">
            <div class="mb-3">
               <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                  <span class="visually-hidden">Loading...</span>
               </div>
            </div>
            <h5 class="mb-2">Generating shipping labels...</h5>
            <p class="text-muted mb-3" id="bulkDownloadProgressText">0 / 0</p>
            <div class="progress" style="height: 8px;">
               <div id="bulkDownloadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- 3rd Party Shipping Modal -->
<div class="modal fade" id="thirdPartyShippingModal" tabindex="-1" aria-labelledby="thirdPartyShippingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="thirdPartyShippingModalLabel">
          Bulk 3rd Party Shipping
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">

        <!-- Provider Selection -->
        <div class="mb-4">
          <label class="form-label fw-bold">Select 3rd Party Provider</label>
          <div id="thirdPartyProvidersContainer" class="d-flex gap-2 flex-wrap">
            <span class="text-muted">Loading providers...</span>
          </div>
          <input type="hidden" id="thirdPartyProvider">
        </div>

        <!-- Pickup Address -->
        <div class="mb-4" id="pickupAddressSection" style="display:none;">
          <label class="form-label fw-bold">Pickup Address</label>
          <select id="pickupAddressSelect" class="form-select">
            <option value="">Select Pickup Address</option>
          </select>
        </div>

        <!-- Package Dimensions -->
        <div class="mb-3">
          <label class="form-label fw-bold">Package Dimensions (Applies to ALL selected orders)</label>
          <div class="row g-2">
            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Length (cm)</label>
              <input type="number" id="pkgLength" class="form-control" min="0" step="0.01" value="42" placeholder="0">
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Breadth (cm)</label>
              <input type="number" id="pkgBreadth" class="form-control" min="0" step="0.01" value="30" placeholder="0">
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Height (cm)</label>
              <input type="number" id="pkgHeight" class="form-control" min="0" step="0.01"  value="17" placeholder="0">
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Weight (kg)</label>
              <input type="number" id="pkgWeight" class="form-control" min="0" step="0.01" value="5" placeholder="0">
            </div>
          </div>
        </div>
		
		 
        <div class="mb-3" id="velocityScheduleSection" style="display:none;">
          <label class="form-label fw-bold">Pickup Schedule (Velocity Only)</label>
          <div class="row g-2">
            <div class="col-md-4">
              <input type="date" id="scheduleDate"  class="form-control">
            </div>
            <div class="col-md-4">
              <input type="time" id="fromTime" class="form-control">
            </div>
            <div class="col-md-4">
              <input type="time" id="toTime" class="form-control">
            </div>
          </div>
        </div>
		

      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>

		<button type="button" class="btn btn-primary" id="saveThirdPartyBtn" disabled onclick="saveBulkThirdPartyShipping()">
			<span id="saveBtnText">
				<i class="fa fa-save me-1"></i> Save & Continue
			</span>
			<span id="saveBtnLoader" style="display:none;">
				<i class="fa fa-spinner fa-spin me-1"></i> Processing...
			</span>
		</button>
		
      </div>

    </div>
  </div>
</div>

<!-- Order Timeline Modal -->
<div class="modal fade" id="orderTimelineListModal" tabindex="-1" aria-labelledby="orderTimelineListModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="orderTimelineListModalLabel">Order Timeline</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body" id="orderTimelineListModalBody">
            <div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>
         </div>
      </div>
   </div>
</div>

<!-- Hidden Print Area -->
<div id="print-area" style="display:none;"></div>

<script>
   $(document).ready(function() {
      // Per-page selector - use JS redirect to avoid nested form issues
      $('#perPageSelect').on('change', function() {
         var base = $(this).data('base');
         var params = $(this).data('params') || '';
         var perPage = $(this).val();
         var sep = base.indexOf('?') >= 0 ? '&' : '?';
         var q = params ? (params + '&') : '';
         q += 'per_page=' + perPage + '&page=1';
         window.location.href = base + sep + q;
      });

      // Date range picker
      if ($('input[name="daterange"]').length) {
         $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            locale: {
               format: 'DD-MM-YYYY',
               cancelLabel: 'Clear'
            },
         }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
         });
      }

      // Order Timeline button - load timeline via AJAX and show modal
      $('.btn-timeline').on('click', function() {
         var orderId = $(this).data('order-id');
         var $modal = $('#orderTimelineListModal');
         var $body = $('#orderTimelineListModalBody');
         $body.html('<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
         $modal.find('.modal-title').text('Order Timeline - ' + orderId);
         if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var modal = new bootstrap.Modal($modal[0]);
            modal.show();
         } else {
            $modal.modal('show');
         }
         $.get('<?php echo base_url("orders/get_order_timeline/"); ?>' + encodeURIComponent(orderId))
            .done(function(html) {
               $body.html(html);
            })
            .fail(function() {
               $body.html('<p class="text-danger">Failed to load timeline.</p>');
            });
      });

      // Print Label button - open print page in new window (avoids AJAX/popup layout issues)
      $(document).on('click', '.btn-print', function() {
         var orderId = $(this).data('order-id');
         if (!orderId) return;
         var printUrl = '<?php echo base_url("orders/print_label/"); ?>' + encodeURIComponent(orderId);
         window.open(printUrl, 'PrintLabel_' + orderId, 'width=800,height=600,scrollbars=yes,resizable=yes');
      });

      // Check all checkbox
      $("#checkAll_order").click(function() {
         $('.order_id').not(this).prop('checked', this.checked);
         getCount();
      });

      // Update button visibility on checkbox change
      $('.order_id').on('change', function() {
         getCount();
      });

      // Move to Processing button click
      $("#btn_process").click(function(e) {
         e.preventDefault();
         var selectedCount = $('input[name="order_id[]"]:checked').length;
         var $btn = $(this);

         // Don't proceed if button is disabled or no orders selected
         if ($btn.prop('disabled') || selectedCount === 0) {
            return false;
         }

         $btn.prop('disabled', true).html('Please wait...<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');

         Swal.fire({
            title: 'Are you sure?',
            text: "You are about to move " + selectedCount + " order(s) to Processing. Are you sure you want to proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
         }).then((result) => {
            if (result.isConfirmed) {
               // Submit form
               document.getElementsByClassName("submit_orderc")[0].click();
            } else {
               $btn.prop('disabled', false).html('Move to Processing (<span class="total_orders">' + selectedCount + '</span>)');
               Swal.close();
            }
         });
      });

      // Move to Out for Delivery button click
      $("#btn_out_for_delivery").click(function(e) {
         e.preventDefault();
         var selectedCount = $('input[name="order_id[]"]:checked').length;
         var $btn = $(this);

         // Don't proceed if button is disabled or no orders selected
         if ($btn.prop('disabled') || selectedCount === 0) {
            return false;
         }

         $btn.prop('disabled', true).html('Please wait...<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');

         Swal.fire({
            title: 'Are you sure?',
            text: "You are about to move " + selectedCount + " order(s) to Out for Delivery. Are you sure you want to proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
         }).then((result) => {
            if (result.isConfirmed) {
               // Submit form
               document.getElementsByClassName("submit_orderc")[0].click();
            } else {
               $btn.prop('disabled', false).html('Move to Out for Delivery (<span class="total_orders">' + selectedCount + '</span>)');
               Swal.close();
            }
         });
      });

      // Move to Delivered button click
      $("#btn_delivered").click(function(e) {
         e.preventDefault();
         var selectedCount = $('input[name="order_id[]"]:checked').length;
         var $btn = $(this);

         // Don't proceed if button is disabled or no orders selected
         if ($btn.prop('disabled') || selectedCount === 0) {
            return false;
         }

         $btn.prop('disabled', true).html('Please wait...<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');

         Swal.fire({
            title: 'Are you sure?',
            text: "You are about to move " + selectedCount + " order(s) to Delivered. Are you sure you want to proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
         }).then((result) => {
            if (result.isConfirmed) {
               // Submit form
               document.getElementsByClassName("submit_orderc")[0].click();
            } else {
               $btn.prop('disabled', false).html('Move to Delivered (<span class="total_orders">' + selectedCount + '</span>)');
               Swal.close();
            }
         });
      });
   });

   function getCount() {
      var selectedCount = $('input[name="order_id[]"]:checked').length;
      $(".total_orders").html(selectedCount);
      
      // Enable/disable button based on selection
      if (selectedCount > 0) {
         $("#btn_process").prop('disabled', false);
         $("#btn_out_for_delivery").prop('disabled', false);
         $("#btn_delivered").prop('disabled', false);
         $("#btn_bulk_download_labels").prop('disabled', false);
         $("#btn_bulk_print_labels").prop('disabled', false);
      } else {
         $("#btn_process").prop('disabled', true);
         $("#btn_out_for_delivery").prop('disabled', true);
         $("#btn_delivered").prop('disabled', true);
         $("#btn_bulk_download_labels").prop('disabled', true);
         $("#btn_bulk_print_labels").prop('disabled', true);
      }
   }

   $("#checkAll_order").change(function() {
      $("input[name='order_id[]']").prop("checked", $(this).prop("checked"));
      getCount();
   });


	 $('.add-ajax-redirect-form').submit(function(e) {
        e.preventDefault();
        $(".loader").show();
        $('.btn_verify').attr("disabled", true)
        $('.btn_verify').html('<i class="fa fa-spinner fa-spin" aria-hidden="true" style="font-size: 14px;color: #fff;"></i> Processing');
        var url = $(this).attr('action');
        var form = $('.add-ajax-redirect-form')[0];

        // FormData object
        var data = new FormData(form);

            $.ajax({
            type: 'POST',
            url: url,
            async: true,
            dataType: 'json',
            data: data,
            processData: false,
            contentType: false,
            success: function(res) {
            if (res.status == '200') {
                 $(".loader").fadeOut("slow");
				 Swal.fire({
            		title: "Success!",
            		text:  res.message,
            		icon: "success",
            		customClass: {
            			confirmButton: "btn btn-primary"
            		},
            		buttonsStyling: !1
            	  }).then(() => { location.reload()});
            }
            else{
				if (res.errors && typeof res.errors === 'object') {
					$.each(res.errors, function(key, value){
						$('[name="'+key+'"]').addClass('is-invalid');
						$('[name="'+key+'"]').next().html(value);
						if(value == ""){
							$('[name="'+key+'"]').removeClass('is-invalid');
							$('[name="'+key+'"]').addClass('is-valid');
						}
					});
				}
				$(".loader").fadeOut("slow");
				$('.btn_verify').html('<i class="fa fa-save"></i>  Save');
				$('.btn_verify').attr("disabled", false);
				var t = $('input[name="order_id[]"]:checked').length;
				$('#btn_process').html('Move to Processing Selected Orders (<span class="total_orders">'+t+'</span>)');
				$("#btn_process").prop('disabled', false);
				Swal.fire({
					title: "Allocation failed",
					text: res.message || "Orders could not be moved to processing. Please try again or contact support.",
					icon: "error",
					customClass: { confirmButton: "btn btn-primary" },
					buttonsStyling: false
				});
          }
         },
			error: function(xhr, status, err) {
				$(".loader").fadeOut("slow");
				$('.btn_verify').html('<i class="fa fa-save"></i>  Save');
				$('.btn_verify').attr("disabled", false);
				var t = $('input[name="order_id[]"]:checked').length;
				$('#btn_process').html('Move to Processing Selected Orders (<span class="total_orders">'+t+'</span>)');
				$("#btn_process").prop('disabled', false);
				Swal.fire({
					title: "Allocation failed",
					text: "Request failed. Please check your connection and try again.",
					icon: "error",
					customClass: { confirmButton: "btn btn-primary" },
					buttonsStyling: false
				});
			}
        });
        return false;
    });
</script>



<script>
$(document).ready(function(){

    /* =========================================
       CHECKBOX HANDLING
    ========================================= */

    $("#checkAll_order").on('change', function(){
        $("input[name='order_id[]']").prop("checked", $(this).prop("checked"));
        updateSelectedCount();
    });

    $(document).on('change', '.order_id', function(){
        updateSelectedCount();
    });

    function updateSelectedCount(){
        var count = $('input[name="order_id[]"]:checked').length;
        $(".total_orders").html(count);

        var enable = count > 0;

        $("#btn_bulk_self_delivery").prop('disabled', !enable);
        $("#btn_bulk_3rd_party").prop('disabled', !enable);
        $("#btn_out_for_delivery").prop('disabled', !enable);
        $("#btn_delivered").prop('disabled', !enable);
        $("#btn_bulk_download_labels").prop('disabled', !enable);
        $("#btn_bulk_print_labels").prop('disabled', !enable);
    }


    /* =========================================
       BULK SELF DELIVERY
    ========================================= */

    $("#btn_bulk_self_delivery").on('click', function(){

        var orderIds = getSelectedOrders();
        if(orderIds.length === 0) return;

        Swal.fire({
            title: 'Are you sure?',
            text: "Assign Self Delivery to " + orderIds.length + " order(s)?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, assign'
        }).then(function(result){

            if(!result.isConfirmed) return;

            $.ajax({
                url: '<?php echo base_url("orders/bulk_set_shipper"); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    order_ids: orderIds,
                    courier: 'manual'
                }
            }).done(function(res){

                if(res.status === '200'){
                    Swal.fire('Success', res.message, 'success')
                        .then(()=> location.reload());
                }else{
                    Swal.fire('Error', res.message || 'Failed', 'error');
                }

            });

        });

    });


    /* =========================================
       BULK 3RD PARTY MODAL
    ========================================= */

    $("#btn_bulk_3rd_party").on('click', function(){

        if(getSelectedOrders().length === 0) return;

        var modal = new bootstrap.Modal(document.getElementById('thirdPartyShippingModal'));
        modal.show();
    });


    $('#thirdPartyShippingModal').on('show.bs.modal', function(){

        resetBulkShippingModal();

        $('#thirdPartyProvidersContainer').html('<span class="text-muted">Loading providers...</span>');

		$.get('<?php echo base_url("vendor/orders/get_active_shipping_providers"); ?>', function(res) {

			if (res.success && res.providers.length > 0) {

				var html = '';

				res.providers.forEach(function(p) {
					html += `
						<button type="button"
								class="btn btn-outline-primary third-party-option"
								data-provider="${p.provider}">
							${p.provider.charAt(0).toUpperCase() + p.provider.slice(1)}
						</button>`;
				});

				$('#thirdPartyProvidersContainer').html(html);

			} else {
				$('#thirdPartyProvidersContainer').html(
					'<span class="text-danger">No active providers found</span>'
				);
			}

		}, 'json');  

    });


    /* =========================================
       PROVIDER CLICK
    ========================================= */

    $(document).on('click', '.third-party-option', function(){

        $('.third-party-option').removeClass('active');
        $(this).addClass('active');

        var provider = $(this).data('provider');

        $('#thirdPartyProvider').val(provider);

        loadPickupAddresses(provider);

        validateBulkShipping();

    });


    /* =========================================
       LOAD PICKUP ADDRESSES
    ========================================= */

	function loadPickupAddresses(provider) {

		if (!provider) return;
		
		if (provider.toLowerCase() === 'velocity') {
			$('#velocityScheduleSection').slideDown();
			let now = new Date();
			let today = now.toISOString().split('T')[0];

			// Set minimum selectable date
			$('#scheduleDate').attr('min', today);

			$('#scheduleDate').prop('required', true);
			$('#fromTime').prop('required', true);
			$('#toTime').prop('required', true);

			$('#scheduleDate').val(today);
			$('#fromTime').val('09:00');
			$('#toTime').val('18:00');

		} else {

			$('#velocityScheduleSection').slideUp();

			$('#scheduleDate').prop('required', false).val('').removeAttr('min');
			$('#fromTime').prop('required', false).val('');
			$('#toTime').prop('required', false).val('');
		}
		

		$('#pickupAddressSection').show();
		$('#pickupAddressSelect').html('<option value="">Loading...</option>');

		$.ajax({
			url: '<?php echo base_url("vendor/orders/get_provider_pickup_addresses"); ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				provider: provider
			}
		})
		.done(function (res) {
			if (res.csrf && res.csrf.hash) {
				csrfHash = res.csrf.hash;
			}

			if (res.success && res.data && res.data.length > 0) {

				var options = '<option value="">Select Pickup Address</option>';

				res.data.forEach(function (addr) {
					options += '<option value="' + addr.value + '">' +
									addr.name +
							   '</option>';
				});

				$('#pickupAddressSelect').html(options);

			} else {

				$('#pickupAddressSelect').html(
					'<option value="">No pickup address found</option>'
				);
			}

		})
		.fail(function () {

			$('#pickupAddressSelect').html(
				'<option value="">Error loading pickup addresses</option>'
			);
		});
	}


    /* =========================================
       VALIDATION
    ========================================= */

    function validateBulkShipping(){

        var provider = $('#thirdPartyProvider').val();
        var pickup   = $('#pickupAddressSelect').val();
        var weight   = parseFloat($('#pkgWeight').val()) || 0;

        if(provider && pickup && weight > 0){
            $('#saveThirdPartyBtn').prop('disabled', false);
        } else {
            $('#saveThirdPartyBtn').prop('disabled', true);
        }
    }

    $(document).on('change keyup',
        '#thirdPartyProvider, #pickupAddressSelect, #pkgLength, #pkgBreadth, #pkgHeight, #pkgWeight',
        validateBulkShipping
    );


    /* =========================================
       SAVE BULK SHIPPING
    ========================================= */

    window.saveBulkThirdPartyShipping = function(){

        var provider = $('#thirdPartyProvider').val();
        var pickup   = $('#pickupAddressSelect').val();
        var weight   = parseFloat($('#pkgWeight').val()) || 0;
        var orderIds = getSelectedOrders();

        if(orderIds.length === 0){
            Swal.fire('Error','No orders selected','warning');
            return;
        }

        if(!provider || !pickup || weight <= 0){
            Swal.fire('Error','Fill all required fields','warning');
            return;
        }

        var data = {
            order_ids: orderIds,
            third_party_provider: provider,
            pickup_address_id: pickup,
            length: $('#pkgLength').val(),
            breadth: $('#pkgBreadth').val(),
            height: $('#pkgHeight').val(),
            weight: weight
        };

        if(provider.toLowerCase() === 'velocity'){
            data.schedule_date = $('#scheduleDate').val();
            data.from_Time     = $('#fromTime').val();
            data.to_Time       = $('#toTime').val();
        }

        $('#saveThirdPartyBtn').prop('disabled', true);
        $('#saveBtnText').hide();
        $('#saveBtnLoader').show();

        $.ajax({
            url: '<?php echo base_url("orders/bulk_save_third_party_shipping"); ?>',
            type: 'POST',
            dataType: 'json',
            data: data
        }).done(function(res){

            if(res.status === '200'){
                var failedData = res.data && typeof res.data === 'object' && Object.keys(res.data).length > 0;
                if (failedData) {
                    var failedList = '';
                    for (var ord in res.data) { failedList += '• ' + ord + ': ' + res.data[ord] + '\n'; }
                    Swal.fire({
                        title: 'Shipping allocation completed with errors',
                        html: '<p class="text-start mb-2">' + (res.message || 'Some orders could not be assigned.') + '</p>' +
                              '<pre class="text-start small bg-light p-2 rounded mb-0" style="max-height: 200px; overflow: auto;">' + failedList.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre>',
                        icon: 'warning',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false
                    }).then(function(){ location.reload(); });
                } else {
                    Swal.fire('Success', res.message, 'success').then(function(){ location.reload(); });
                }
            } else {
                $('#saveThirdPartyBtn').prop('disabled', false);
                $('#saveBtnText').show();
                $('#saveBtnLoader').hide();
                var errDetail = res.data && typeof res.data === 'object' && Object.keys(res.data).length > 0
                    ? '<pre class="text-start small bg-light p-2 rounded mt-2">' + Object.keys(res.data).map(function(k){ return k + ': ' + res.data[k]; }).join('\n').replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre>'
                    : '';
                Swal.fire({
                    title: 'Shipping allocation failed',
                    html: '<p>' + (res.message || 'Orders could not be assigned. Please try again or contact support.') + '</p>' + errDetail,
                    icon: 'error',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false
                });
            }

        }).fail(function(xhr, status, err){
            $('#saveThirdPartyBtn').prop('disabled', false);
            $('#saveBtnText').show();
            $('#saveBtnLoader').hide();
            Swal.fire({
                title: 'Shipping allocation failed',
                text: 'Request failed. Please check your connection and try again.',
                icon: 'error',
                customClass: { confirmButton: 'btn btn-primary' },
                buttonsStyling: false
            });
        });

    };


    /* =========================================
       HELPERS
    ========================================= */

    function getSelectedOrders(){
        var ids = [];
        $('input[name="order_id[]"]:checked').each(function(){
            ids.push($(this).val());
        });
        return ids;
    }

    function resetBulkShippingModal(){
        $('#thirdPartyProvider').val('');
        $('#pickupAddressSection').hide();
        $('#velocityScheduleSection').hide();
        //$('#pkgLength, #pkgBreadth, #pkgHeight, #pkgWeight').val('');
        $('#saveThirdPartyBtn').prop('disabled', true);
        $('#saveBtnText').show();
        $('#saveBtnLoader').hide();
    }

});

// Apply courier filter (Self Delivery vs 3rd Party) – used on load and tab click
function applyCourierFilter(courierFilter) {
    $('tr.item_holder').each(function(){
        var rowCourier = ($(this).data('courier') || '').toString().toLowerCase();

        if (courierFilter === 'manual') {
            if (rowCourier === 'manual' || rowCourier === '') {
                $(this).show();
            } else {
                $(this).hide();
            }
        } else if (courierFilter === 'third_party') {
            if (rowCourier && rowCourier !== 'manual') {
                $(this).show();
            } else {
                $(this).hide();
            }
        } else {
            $(this).show();
        }
    });
}

// On page load: apply default Self Delivery filter so 3rd party orders don't appear until tab clicked
$(document).ready(function(){
    if ($('#courierFilterTabs').length && $('#tab-self-delivery').hasClass('active')) {
        applyCourierFilter('manual');
    }
});

// Courier filter tabs (Self Delivery vs 3rd Party)
$(document).on('click', '#courierFilterTabs button[data-courier-filter]', function(){
    var courierFilter = $(this).data('courier-filter');

    $('#courierFilterTabs button').removeClass('active');
    $(this).addClass('active');

    applyCourierFilter(courierFilter);

    // Show bulk shipping label buttons for both Self Delivery and 3rd Party tabs
    $('#btn_bulk_download_labels, #btn_bulk_print_labels').show();

    // Clear selections after filter change
    $("#checkAll_order").prop('checked', false);
    $("input[name='order_id[]']").prop('checked', false);
    $(".total_orders").html(0);
    $("#btn_bulk_self_delivery, #btn_bulk_3rd_party, #btn_out_for_delivery, #btn_delivered, #btn_bulk_download_labels, #btn_bulk_print_labels").prop('disabled', true);
});

// Bulk print shipping labels - open single page with all labels (one print = all pages)
$(document).on('click', '#btn_bulk_print_labels', function(){
    var orderUniqueIds = [];
    $('input[name="order_id[]"]:checked').each(function(){
        var $row = $(this).closest('tr.item_holder');
        var uid = $row.data('order-unique-id');
        if (uid) orderUniqueIds.push(uid);
    });
    if (orderUniqueIds.length === 0) {
        if (typeof Swal !== 'undefined') Swal.fire('Select orders first');
        else alert('Select orders first');
        return;
    }
    var $form = $('<form method="post" action="<?php echo base_url("orders/print_labels_bulk"); ?>" target="_blank" style="display:none;">');
    $form.append('<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">');
    orderUniqueIds.forEach(function(uid){
        $form.append($('<input>', { type: 'hidden', name: 'order_unique_ids[]', value: uid }));
    });
    $('body').append($form);
    $form.submit();
    $form.remove();
});

// Bulk download shipping labels (with progress modal)
$(document).on('click', '#btn_bulk_download_labels', function(){

    var orderIds = [];

    $('input[name="order_id[]"]:checked').each(function(){
        orderIds.push($(this).val());
    });

    if(orderIds.length === 0){
        if (typeof Swal !== 'undefined') {
            Swal.fire('Select orders first');
        }
        return;
    }

    var total = orderIds.length;
    var progressModal = new bootstrap.Modal(document.getElementById('bulkDownloadProgressModal'));
    var $progressText = $('#bulkDownloadProgressText');
    var $progressBar = $('#bulkDownloadProgressBar');
    var progressInterval = null;
    var currentProgress = 0;

    var updateProgress = function(n, label) {
        currentProgress = Math.min(n, total);
        var pct = total > 0 ? Math.round((currentProgress / total) * 100) : 0;
        $progressText.text(currentProgress + ' / ' + total);
        $progressBar.css('width', pct + '%').attr('aria-valuenow', pct).text(pct + '%');
        if (label) {
            $progressText.closest('.modal-body').find('h5').text(label);
        }
    };

    $('#btn_bulk_download_labels').prop('disabled', true);
    $('#bulkLabelsText').hide();
    $('#bulkLabelsSpinner').show();

    updateProgress(0, 'Generating shipping labels...');
    progressModal.show();

    // Simulate progress (0 -> total over time while request runs)
    progressInterval = setInterval(function(){
        if (currentProgress < total) {
            updateProgress(currentProgress + 1);
        }
    }, 500);

    var formData = new FormData();
    formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
    orderIds.forEach(function(id){
        formData.append('order_ids[]', id);
    });

    fetch('<?php echo base_url('orders/bulk_download_shipping_labels'); ?>', {
        method: 'POST',
        body: formData
    }).then(function(response) {
        clearInterval(progressInterval);
        updateProgress(total, 'Preparing download...');
        var contentType = response.headers.get('Content-Type') || '';
        if (contentType.indexOf('application/zip') !== -1 || contentType.indexOf('application/octet-stream') !== -1) {
            return response.blob();
        }
        return response.text().then(function(text) {
            throw new Error('Server returned an error. ' + (text.indexOf('error') !== -1 ? 'Please check selected orders.' : ''));
        });
    }).then(function(blob) {
        updateProgress(total, 'Download complete!');
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'shipping_labels_' + new Date().toISOString().slice(0,10) + '.zip';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        setTimeout(function(){
            progressModal.hide();
            $('#btn_bulk_download_labels').prop('disabled', false);
            $('#bulkLabelsText').show();
            $('#bulkLabelsSpinner').hide();
        }, 1200);
    }).catch(function(err) {
        clearInterval(progressInterval);
        progressModal.hide();
        $('#btn_bulk_download_labels').prop('disabled', false);
        $('#bulkLabelsText').show();
        $('#bulkLabelsSpinner').hide();
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', err.message || 'Failed to download shipping labels.', 'error');
        } else {
            alert('Failed to download: ' + (err.message || 'Unknown error'));
        }
    });
});
</script>