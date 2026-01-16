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

  <div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div>
      <h6 class="mb-0">Offers (<?= $total_count; ?>)</h6>
    </div>
    <div>
      <a href="<?php echo base_url($vendor_domain . '/offers/add'); ?>" class="btn btn-primary">
        <i class="isax isax-add me-1"></i>Add New Offer
      </a>
    </div>
  </div>

   <!-- Search Filter -->
   <div class="row mb-3">
      <div class="col-12">
         <div class="card">
            <div class="card-body">
               <form method="get" action="<?php echo base_url($vendor_domain . '/orders/rejected-orders'); ?>" class="row">
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
                        <a href="<?php echo base_url($vendor_domain . '/orders/rejected-orders'); ?>" class="btn btn-secondary">Clear</a>
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
            <!-- <div class="col-md-12" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 15px;">
               <div class="d-block left">
                  <div class="page_title" style="font-size: 20px;font-weight: 500;color:#000;">
                    Offers (<?= $total_count; ?>)
                  </div>
               </div>
            </div> -->

            <div class="col-md-12 table-responsive">
               <table class="table table-striped table-bordered table-hover">
                  <thead>
                     <tr>
                      <th>#</th>
                      <th>Offer Type</th>
                      <th>Code/Title</th>
                      <th>Minimum Requirement</th>
                      <th>Offer Value</th>
                      <th>Status</th>
                      <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     if (!empty($order_list)):
                        foreach ($order_list as $key => $item): ?>
                           <tr class="item_holder">
                              <td><?php echo $key + 1; ?></td>
                              
                              <td><?php echo ($item['offer_type'] == 'discount_code') ? 'Discount Code' : 'Automatic Discount'; ?></td>
                              <td><?php echo ($item['offer_type'] == 'discount_code') ? $item['discount_code'] : $item['title']; ?></td>
                              <td><?php echo ($item['min_type'] == 'quantity') ? $item['min_value'] . ' items': '₹' . $item['min_value']; ?></td>
                              <td>
                                 <?php
                                    if ($item['offer_value_type'] == 'percentage') {
                                        echo $item['offer_value'] . '%';
                                    } elseif ($item['offer_value_type'] == 'amount') {
                                        echo '₹' . $item['offer_value'];
                                    } else {
                                        echo $item['free_quantity'] . ' free items';
                                    }
                                 ?>
                              </td>
                              <td><?php echo ($item['status'] == '1') ? '<span class="badge badge-success">Active</span>': '<span class="badge badge-danger">Inactive</span>'; ?></td>
                              
                              <td nowrap="">
                                 <!-- <a href="<?php echo site_url($vendor_domain . "/orders/view/" . $item['order_unique_id']); ?>" class="btn btn-primary btn_edit" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a> -->
                              </td>

                           </tr>
                        <?php endforeach; 
                     else: ?>
                        <tr>
                           <td colspan="13">
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


