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
   .label {
      display: inline-block;
      padding: 0.25em 0.6em;
      font-size: 0.875rem;
      font-weight: 600;
      line-height: 1;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: 0.25rem;
   }
   .label-default { background-color: #6c757d; color: #fff; }
   .label-warning { background-color: #ffc107; color: #000; }
   .label-info { background-color: #17a2b8; color: #fff; }
   .label-success { background-color: #28a745; color: #fff; }
   .label-danger { background-color: #dc3545; color: #fff; }
</style>

<div class="mobile_view home">
   <div class="row">
      <div class="col-12">
         <ul class="nav nav-tabs brbm0" role="tablist">
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'all' || $order_status == '') ? 'active' : ''; ?>" href="<?php echo base_url('school-admin/orders'); ?>">
                  All Orders
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'pending') ? 'active' : ''; ?>" href="<?php echo base_url('school-admin/orders/pending'); ?>">
                  New Order <?php if(isset($order_counts['pending']) && $order_counts['pending'] > 0): ?><span class="badge bg-primary ms-1"><?php echo $order_counts['pending']; ?></span><?php endif; ?>
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'processing') ? 'active' : ''; ?>" href="<?php echo base_url('school-admin/orders/processing'); ?>">
                  Processing <?php if(isset($order_counts['processing']) && $order_counts['processing'] > 0): ?><span class="badge bg-primary ms-1"><?php echo $order_counts['processing']; ?></span><?php endif; ?>
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'out_for_delivery') ? 'active' : ''; ?>" href="<?php echo base_url('school-admin/orders/out_for_delivery'); ?>">
                  Out for Delivery <?php if(isset($order_counts['out_for_delivery']) && $order_counts['out_for_delivery'] > 0): ?><span class="badge bg-primary ms-1"><?php echo $order_counts['out_for_delivery']; ?></span><?php endif; ?>
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'delivered') ? 'active' : ''; ?>" href="<?php echo base_url('school-admin/orders/delivered'); ?>">
                  Delivered
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'return') ? 'active' : ''; ?>" href="<?php echo base_url('school-admin/orders/return'); ?>">
                  Return
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link tab-navigation <?php echo ($order_status == 'cancelled') ? 'active' : ''; ?>" href="<?php echo base_url('school-admin/orders/cancelled-orders'); ?>">
                  Cancelled
               </a>
            </li>
         </ul>
      </div>
   </div>

   <div class="row mb-3">
      <div class="col-12">
         <div class="card brtop0">
            <div class="card-body">
               <form method="get" action="<?php echo base_url('school-admin/orders/' . $order_status); ?>" class="row">
                  <div class="col-md-4">
                     <label>Keywords</label>
                     <input type="text" name="keywords" class="form-control" value="<?php echo isset($filter_data['keywords']) ? htmlspecialchars($filter_data['keywords']) : ''; ?>" placeholder="Order ID, User Name, Phone, Invoice Number...">
                  </div>
                  <div class="col-md-4">
                     <label>&nbsp;</label>
                     <div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="<?php echo base_url('school-admin/orders/' . $order_status); ?>" class="btn btn-secondary">Clear</a>
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
            <div class="col-md-12" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
               <div class="d-block left">
                  <div class="page_title" style="font-size: 20px;font-weight: 500;color:#000;">
                     <?php 
                     if ($order_status == 'all' || $order_status == '') {
                        echo 'All Orders';
                     } elseif ($order_status == 'pending') {
                        echo 'New Order';
                     } elseif ($order_status == 'processing') {
                        echo 'Processing';
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
            </div>

            <div class="col-md-12 table-responsive">
               <table class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                        <th>Order ID</th>
                        <?php if ($order_status == 'all' || $order_status == ''): ?>
                           <th>Status</th>
                        <?php endif; ?>
                        <th>Source</th>
                        <th>User Name</th>
                        <th>User Phone</th>
                        <th>
                           <?php
                           if ($order_status == 'all' || $order_status == '') {
                              echo 'Order Date';
                           } elseif ($order_status == 'pending') {
                              echo 'Order Date';
                           } elseif ($order_status == 'processing') {
                              echo 'Processing Date';
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
                        <th>Payment Id</th>
                        <th>Coupon Code</th>
                        <th>Invoice Number</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     if (!empty($order_list)):
                        foreach ($order_list as $item): 
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
                        ?>
                           <tr class="item_holder">
                              <td><?php echo $item['order_unique_id']; ?></td>
                              <?php if ($order_status == 'all' || $order_status == ''): ?>
                                 <td><span class="label <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
                              <?php endif; ?>
                              <td><?php echo $item['source']; ?></td>
                              <td><?php echo $item['user_name']; ?></td>
                              <td><?php echo $item['user_phone']; ?></td>
                              <td><?php echo $item['date']; ?></td>
                              <td><?php echo $item['payment_method']; ?></td>
                              <td><?php echo $item['payment_id']; ?></td>
                              <td><?php echo $item['coupon_code']; ?></td>
                              <td><?php echo $item['invoice_no']; ?></td>
                              <td nowrap="">
                                 <a href="javascript:void(0);" class="btn btn-primary btn_edit"><i class="fa fa-eye"></i></a>
                              </td>
                           </tr>
                        <?php endforeach; 
                     else: ?>
                        <tr>
                           <td colspan="<?php 
                              $colspan = 10;
                              if ($order_status == 'all' || $order_status == '') {
                                 $colspan = 11;
                              }
                              echo $colspan;
                           ?>">
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
