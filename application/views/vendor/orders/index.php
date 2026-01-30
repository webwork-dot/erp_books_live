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
   
   /* Status labels */
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
   
   .label-default {
      background-color: #6c757d;
      color: #fff;
   }
   
   .label-warning {
      background-color: #ffc107;
      color: #000;
   }
   
   .label-info {
      background-color: #17a2b8;
      color: #fff;
   }
   
   .label-success {
      background-color: #28a745;
      color: #fff;
   }
   
   .label-danger {
      background-color: #dc3545;
      color: #fff;
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
               <form method="get" action="<?php echo base_url('orders/' . $order_status); ?>" class="row">
                  <div class="col-md-4">
                     <label>Keywords</label>
                     <input type="text" name="keywords" class="form-control" value="<?php echo isset($filter_data['keywords']) ? htmlspecialchars($filter_data['keywords']) : ''; ?>" placeholder="Order ID, User Name, Phone, Invoice Number...">
                  </div>
                  <!-- <div class="col-md-4">
                     <label>Date Range</label>
                     <input type="text" name="date_range" class="form-control daterange" value="<?php echo isset($filter_data['date_range']) ? htmlspecialchars($filter_data['date_range']) : ''; ?>" placeholder="Select Date Range">
                  </div> -->
                  <div class="col-md-4">
                     <label>&nbsp;</label>
                     <div>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="<?php echo base_url('orders/' . $order_status); ?>" class="btn btn-secondary">Clear</a>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-12">
         <!-- <div class="card f-filter">
            <div class="card-body py-1 my-0"> -->
               <div class="card mrg_bottom">

                  <?php if ($order_status == 'all' || $order_status == ''): ?>
                     <!-- No form for "All Orders" view -->
                  <?php elseif ($order_status == 'pending'): ?>
                     <?php echo form_open(base_url('orders/move_to_processing'), ['id' => 'form_', 'class' => 'add-ajax-redirect-form', 'enctype' => 'multipart/form-data']); ?>
                  <?php elseif ($order_status == 'processing'): ?>
                     <?php echo form_open(base_url('orders/move_to_out_for_delivery'), ['id' => 'form_', 'class' => 'add-ajax-redirect-form', 'enctype' => 'multipart/form-data']); ?>
                  <?php elseif ($order_status == 'out_for_delivery'): ?>
                     <?php echo form_open(base_url('orders/move_to_delivered'), ['id' => 'form_', 'class' => 'add-ajax-redirect-form', 'enctype' => 'multipart/form-data']); ?>
                  <?php endif; ?>

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

                     <?php if ($order_status == 'all' || $order_status == ''): ?>
                        <!-- No action buttons for "All Orders" view -->
                     <?php elseif ($order_status == 'pending'): ?>
                        <div class="pull-right">
                           <button type="button" name="button" id="btn_process" value="update" class="btn btn-primary waves-effect waves-light btn-md mb-0" disabled>
                              Move to Processing (<span class="total_orders">0</span>)
                           </button>
                        </div>
                     <?php elseif ($order_status == 'processing'): ?>
                        <div class="pull-right">
                           <button type="button" name="button" id="btn_out_for_delivery" value="update" class="btn btn-primary waves-effect waves-light btn-md mb-0" disabled>
                              Move to Out for Delivery (<span class="total_orders">0</span>)
                           </button>
                        </div>
                     <?php elseif ($order_status == 'out_for_delivery'): ?>
                        <div class="pull-right">
                           <button type="button" name="button" id="btn_delivered" value="update" class="btn btn-primary waves-effect waves-light btn-md mb-0" disabled>
                              Move to Delivered (<span class="total_orders">0</span>)
                           </button>
                        </div>
                     <?php endif; ?>
                  </div>

                  <div class="col-md-12 table-responsive">
                     <table class="table table-striped table-bordered table-hover">
                        <thead>
                           <tr>
                              <?php if ($order_status == 'all' || $order_status == 'pending' || $order_status == '' || $order_status == 'processing' || $order_status == 'out_for_delivery'): ?>
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
                              <th>Source</th>
                              <th>User Name</th>
                              <th>User Phone</th>
                              <th nowrap="">
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
                              <th class="cat_action_list">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $i = 1;
                           $ci = &get_instance();
                           if (!empty($order_list)):
                              foreach ($order_list as $key => $item): 
                                 // Determine if order is actionable (can be moved to next status)
                                 $is_actionable = false;
                                 if ($order_status == 'all' || $order_status == '') {
                                    $is_actionable = ($item['status'] == '1' || $item['status'] == '2' || $item['status'] == '3');
                                 } else {
                                    $is_actionable = ($order_status == 'pending' || $order_status == 'processing' || $order_status == 'out_for_delivery');
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
                                    <td><a href="<?php echo base_url('orders/view/' . $item['order_unique_id']); ?>"><?php echo $item['order_unique_id']; ?></a></td>
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
                                       <a href="<?php echo base_url('orders/view/' . $item['order_unique_id']); ?>" class="btn btn-primary btn_edit" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>
                                    </td>
                                 </tr>
                              <?php endforeach; 
                           else: ?>
                              <tr>
                                 <td colspan="<?php 
                                    $colspan = 10;
                                    if ($order_status == 'all' || $order_status == '') {
                                       $colspan = 12; // checkbox + status column
                                    } elseif ($order_status == 'pending' || $order_status == 'processing' || $order_status == 'out_for_delivery') {
                                       $colspan = 11; // checkbox column
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

                  <?php if ($order_status == 'pending'): ?>
                     <input type="submit" name="submit_orderc" class="submit_orderc" value="1" style="display:none;">
                     <?php echo form_close(); ?>
                  <?php elseif ($order_status == 'processing'): ?>
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

<script>
   $(document).ready(function() {
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
      } else {
         $("#btn_process").prop('disabled', true);
         $("#btn_out_for_delivery").prop('disabled', true);
         $("#btn_delivered").prop('disabled', true);
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
				$.each(res.errors, function(key, value){
					$('[name="'+key+'"]').addClass('is-invalid'); //select parent twice to select div form-group class and add has-error class
					$('[name="'+key+'"]').next().html(value); //select span help-block class set text error string
					if(value == ""){
						$('[name="'+key+'"]').removeClass('is-invalid');
						$('[name="'+key+'"]').addClass('is-valid');
					}
				});
			   Swal.fire({
					title: "Error!",
					html: true,
					html: res.message ,
					icon: "error",
					customClass: {
						confirmButton: "btn btn-primary"
					},
					buttonsStyling: !1
				})
            $(".loader").fadeOut("slow");
            $('.btn_verify').html('<i class="fa fa-save"></i>  Save');
            $('.btn_verify').attr("disabled", false)
            var t = $('input[name="order_id[]"]:checked').length;

        	$('#btn_process').html('Move to Processing Selected Orders (<span class="total_orders">'+t+'</span>)');
            $("#btn_process").prop('disabled', false);
          }
         }
        });
        return false;
    });
</script>
