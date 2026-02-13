<style>
.page_title {
  font-family: "Open Sans", sans-serif;
  font-size: 1.2em;
  font-weight: 500;
  color: #666666;
  padding: 20px;
}

.search_list {
  align-items: flex-start;
  display: flex;
  flex-flow: row nowrap;
  justify-content: flex-start;
  padding: 20px;
  flex: 1 1 0;
  width: auto;
  float: right;
  text-align: right;
  position: relative;
}

.add_btn_primary {
  float: left;
  display: flex;
}

.address_item_header {
  background: #f7f7f7;
  border-radius: 4px;
  border: 1px solid #dfe6e8;
  padding: 25px 15px;
  display: inline-block;
  width: 100%;
}
</style>

<?php 
$currency = isset($order_data[0]->currency) ? $order_data[0]->currency : 'INR';
$currency_code = $currency;
$ci = &get_instance();
?>

<div class="row">
  <div class="col-md-12 mr_bottom20">
    <div class="card mr_bottom20 mr_top10">
      <div class="page_title_white user_dashboard_item" style="background-color:#FFFFFF;">
        <div class="row">
          <div class="col-9 col-md-10">
            <div class="page_title"><?= $current_page ?></div>
          </div>
          <div class="col-3 col-md-2">
            <div class="search_list">
              <div class="add_btn_primary">
                <?php if ($order_data[0]->order_status != 5): ?>
                  <?php if (!empty($order_data[0]->invoice_url)): ?>
                    <a href="<?php echo base_url($order_data[0]->invoice_url); ?>" class="btn btn-success" target="_blank">
                      <i class="fa fa-download"></i> Invoice
                    </a>
                  <?php else: ?>
                    <a href="<?php echo base_url('orders/download_invoice/' . $order_data[0]->order_unique_id); ?>" class="btn btn-success" target="_blank">
                      <i class="fa fa-download"></i> Invoice
                    </a>
                  <?php endif; ?>
                <?php else: ?>
                  <a href="javascript:void(0);" class="btn btn-success disabled" title="Invoice not available for cancelled orders">
                    <i class="fa fa-download"></i> Invoice
                  </a>
                <?php endif; ?>
                
                <?php if ($order_data[0]->order_status == '2' || $order_data[0]->order_status == 2): // Processing ?>
                  <?php if (!empty($order_data[0]->shipping_label)): ?>
                    <a href="<?php echo base_url('orders/download_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-info" target="_blank" style="margin-left: 10px;">
                      <i class="fa fa-download"></i> Download Shipping Label
                    </a>
                    <a href="<?php echo base_url('orders/generate_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-warning" style="margin-left: 10px;" onclick="return confirm('Are you sure you want to regenerate the shipping label? The old label will be deleted.');">
                      <i class="fa fa-refresh"></i> Regenerate Label
                    </a>
                  <?php else: ?>
                    <a href="<?php echo base_url('orders/generate_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-primary" style="margin-left: 10px;">
                      <i class="fa fa-file"></i> Generate Shipping Label
                    </a>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>

      <div class="form-group m-form__group align-items-center" style="margin: 20px;">
        <div class="">
          <div class="address_item_header" style="background: #eee">
            <div class="row">
              <div class="col-md-4">
                <h4 style="margin-top:0px;font-weight: 600;margin-bottom: 15px">Order Details</h4>
                <div style="font-size: 14px">
                  <strong style="font-weight: 500;color: #575757">Order ID</strong>:&nbsp;&nbsp;&nbsp;<?= $order_data[0]->order_unique_id ?>
                </div>

                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Invoice Number</strong>:&nbsp;&nbsp;&nbsp;<?= ($order_data[0]->invoice_no != '' ? $order_data[0]->invoice_no : '-') ;?>
                </div>

                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Order Date:</strong>&nbsp;&nbsp;&nbsp;<?php echo date('d-m-Y h:i A', strtotime($order_data[0]->order_date));?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Order Type:</strong>&nbsp;&nbsp;&nbsp;<?= isset($order_type) ? ucfirst($order_type) : 'Individual' ?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Payable Amount:</strong>&nbsp;&nbsp;&nbsp;<?php echo $currency_code . ' ' . (isset($order_data[0]->payable_amt) ? $order_data[0]->payable_amt : '0');?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Payment Mode:</strong>&nbsp;&nbsp;&nbsp;
                  <?php 
                  if($order_data[0]->payment_method == 'cod'){ 
                    echo 'Cash On Delivery'; 
                  } elseif($order_data[0]->payment_method == 'cashfree'){ 
                    echo 'Cashfree'; 
                  } elseif($order_data[0]->payment_method == 'razorpay'){ 
                    echo 'Razorpay'; 
                  } else{ 
                    echo ucfirst($order_data[0]->payment_method); 
                  } 
                  ?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500; color: #575757">Payment Status:</strong>&nbsp;&nbsp;&nbsp;<?= strtoupper($order_data[0]->payment_status) ?>
                </div>

                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Order Status:</strong>&nbsp;&nbsp;&nbsp;
                  <?php 
                  if($order_data[0]->payment_status == 'pending'){ 
                    echo '<span style="color:red;font-size:14px">Payment Pending</span>';
                  } else{ 
                    switch ($order_data[0]->order_status) {
                      case '1':
                        echo '<span style="color:orange;font-size:14px">Pending</span>';
                        break;
                      case '2':
                        echo '<span style="color:orange;font-size:14px">Processing</span>';
                        break;
                      case '3':
                        echo '<span style="color:orange;font-size:14px">Out for Delivery</span>';
                        break;
                      case '4':
                        echo '<span style="color:green;font-size:14px">Delivered</span>';
                        break;
                      case '7':
                        echo '<span style="color:red;font-size:14px">Return</span>';
                        break;
                      default:
                        echo '<span style="color:red;font-size:14px">Cancelled</span>';
                        break;
                    }
                  } 
                  ?>
                </div>
              </div>

              <div class="col-md-4">
                <h4 style="margin-top:0px;font-weight: 600;margin-bottom: 15px">Billing Address</h4>
                <div style="font-size: 14px">
                  <strong style="font-weight: 500;color: #575757">Name:</strong>
                  <?php echo isset($address_arr[0]->name) ? $address_arr[0]->name : $order_data[0]->user_name;?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Phone:</strong>
                  <?php echo isset($address_arr[0]->mobile_no) ? $address_arr[0]->mobile_no : $order_data[0]->user_phone;?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Address:</strong><br>
                  <?php 
                  if (isset($address_arr[0])) {
                    $addr = $address_arr[0];
                    echo ($addr->address ? $addr->address . ', ' : '') . 
                         ($addr->city ? $addr->city . ', ' : '') . 
                         ($addr->state ? $addr->state . ', ' : '') . 
                         ($addr->country ? $addr->country . ' - ' : '') . 
                         ($addr->pincode ? $addr->pincode : '');
                  } else {
                    echo '-';
                  }
                  ?>
                  <br>
                  <?php if(isset($address_arr[0]->landmark) && $address_arr[0]->landmark != "") { ?>
                  <strong style="font-weight: 500;color: #575757">Landmark:</strong><br>
                  <?php echo $address_arr[0]->landmark; }
                  ?>
                </div>
              </div>
              <div class="col-md-4">
                <h4 style="margin-top:0px;font-weight: 600;margin-bottom: 15px">Shipping Address</h4>
                <div style="font-size: 14px">
                  <strong style="font-weight: 500;color: #575757">Name:</strong>
                  <?php echo isset($address_arr[0]->name) ? $address_arr[0]->name : $order_data[0]->user_name;?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Phone:</strong>
                  <?php echo isset($address_arr[0]->mobile_no) ? $address_arr[0]->mobile_no : $order_data[0]->user_phone;?>
                </div>
                <div style="font-size: 14px;margin-top: 5px">
                  <strong style="font-weight: 500;color: #575757">Address:</strong><br>
                  <?php 
                  if (isset($address_arr[0])) {
                    $addr = $address_arr[0];
                    echo ($addr->address ? $addr->address . ', ' : '') . 
                         ($addr->city ? $addr->city . ', ' : '') . 
                         ($addr->state ? $addr->state . ', ' : '') . 
                         ($addr->country ? $addr->country . ' - ' : '') . 
                         ($addr->pincode ? $addr->pincode : '');
                  } else {
                    echo '-';
                  }
                  ?>
                  <br>
                  <?php if(isset($address_arr[0]->landmark) && $address_arr[0]->landmark != "") { ?>
                  <strong style="font-weight: 500;color: #575757">Landmark:</strong><br>
                  <?php echo $address_arr[0]->landmark; }
                  ?>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

          </div>
        </div>
        <br />
        <br />
        <div class="no-padding">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-condensed">
                      <thead>
                        <tr class="top_bdr">
                          <td class="rank_item text-center bdr_left bdr_right"></td>
                          <td class="bdr_right" width="300px"><strong>Product</strong></td>
                          <td class="text-center bdr_right"><strong>SKU</strong></td>
                          <td class="text-center bdr_right"><strong>Quantity</strong></td>
                          <td class="text-center bdr_right"><strong>Price</strong></td>
                          <td class="text-center bdr_right"><strong>Total Price</strong></td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $_total_amt = $_total_price = $_total_qty = 0;

                        // Display bookset products if order type is bookset
                        if (isset($order_type) && $order_type == 'bookset' && !empty($bookset_products)) {
                          // Group bookset products by package
                          $packages = array();
                          foreach ($bookset_products as $bookset_product) {
                            $package_id = $bookset_product->package_id;
                            if (!isset($packages[$package_id])) {
                              $packages[$package_id] = array(
                                'package_name' => $bookset_product->package_name,
                                'package_price' => $bookset_product->package_price,
                                'products' => array()
                              );
                            }
                            $packages[$package_id]['products'][] = $bookset_product;
                          }
                          
                          // Display each package and its products
                          foreach ($packages as $package_id => $package_data) {
                            ?>
                            <tr>
                              <td colspan="6" style="background-color: #f0f0f0; font-weight: bold; padding: 10px;">
                                Package: <?= htmlspecialchars($package_data['package_name']) ?> 
                                (<?= $currency_code . ' ' . number_format($package_data['package_price'], 2) ?>)
                              </td>
                            </tr>
                            <?php
                            foreach ($package_data['products'] as $bookset_product) {
                              $rowwise_total_price = isset($bookset_product->total_price) ? $bookset_product->total_price : ($bookset_product->unit_price * $bookset_product->quantity);
                              $_total_price += $rowwise_total_price;
                              $_total_qty += $bookset_product->quantity;
                              
                              // Get product image (try different product tables based on product_type)
                              $product_image = '';
                              if (!empty($bookset_product->product_id)) {
                                $product_id = $bookset_product->product_id;
                                $product_type = $bookset_product->product_type;
                                
                                // Try to get image based on product type
                                if ($product_type == 'textbook') {
                                  $img_query = $this->db->select('image_path')
                                    ->from('erp_textbook_images')
                                    ->where('textbook_id', $product_id)
                                    ->where('is_main', 1)
                                    ->limit(1)
                                    ->get();
                                  if ($img_query->num_rows() > 0) {
                                    $image_path = $img_query->row()->image_path;
                                    if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
                                      $product_image = $image_path;
                                    } elseif (strpos($image_path, 'assets/uploads/') === 0) {
                                      $product_image = $image_path;
                                    } else {
                                      $product_image = 'assets/uploads/' . ltrim($image_path, '/');
                                    }
                                  }
                                } elseif ($product_type == 'notebook') {
                                  $img_query = $this->db->select('image_path')
                                    ->from('erp_notebook_images')
                                    ->where('notebook_id', $product_id)
                                    ->where('is_main', 1)
                                    ->limit(1)
                                    ->get();
                                  if ($img_query->num_rows() > 0) {
                                    $image_path = $img_query->row()->image_path;
                                    if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
                                      $product_image = $image_path;
                                    } elseif (strpos($image_path, 'assets/uploads/') === 0) {
                                      $product_image = $image_path;
                                    } else {
                                      $product_image = 'assets/uploads/' . ltrim($image_path, '/');
                                    }
                                  }
                                }
                              }
                            ?>
                            <tr>
                              <td class="thick-line bdr_left" style="width: 100px;">
                                <?php if (!empty($product_image)): 
                                  if (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) {
                                    $img_url = $product_image;
                                  } else {
                                    $img_url = base_url($product_image);
                                  }
                                ?>
                                  <img src="<?= $img_url ?>" style="height: 115px;width: auto;border: 1px solid #ddd;border-radius:2px;max-width: 100px;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>';" />
                                <?php else: ?>
                                  <div style="height: 115px;width: 100px;border: 1px solid #ddd;border-radius:2px;display: flex;align-items: center;justify-content: center;background: #f5f5f5;">
                                    <i class="fa fa-image" style="font-size: 24px;color: #ccc;"></i>
                                  </div>
                                <?php endif; ?>
                              </td>
                              <td class="thick-line bdr_left">
                                <?= htmlspecialchars($bookset_product->product_name) ?>
                                <p class="mb-1"><small>Type: <?= ucfirst($bookset_product->product_type) ?></small></p>
                                <?php if (!empty($bookset_product->product_sku)): ?>
                                <p class="mb-1"><small>SKU: <?= htmlspecialchars($bookset_product->product_sku) ?></small></p>
                                <?php endif; ?>
                                <?php if (!empty($bookset_product->product_isbn)): ?>
                                <p class="mb-1"><small>ISBN: <?= htmlspecialchars($bookset_product->product_isbn) ?></small></p>
                                <?php endif; ?>
                              </td>
                              <td class="thick-line text-center bdr_left"><?= !empty($bookset_product->product_sku) ? htmlspecialchars($bookset_product->product_sku) : '-' ?></td>
                              <td class="thick-line text-center bdr_left"><?= $bookset_product->quantity ?></td>
                              <td class="text-center thick-line"><?= $currency_code . ' ' . number_format($bookset_product->unit_price, 2) ?></td>
                              <td class="text-center thick-line"><?= $currency_code . ' ' . number_format($rowwise_total_price, 2) ?></td>
                            </tr>
                            <?php
                            }
                          }
                          
                          // Display bookset information if available
                          if (!empty($bookset_info)) {
                            ?>
                            <tr>
                              <td colspan="6" style="background-color: #e8f4f8; padding: 10px;">
                                <strong>Bookset Information:</strong><br>
                                <?php if (!empty($bookset_info->school_name)): ?>
                                School: <?= htmlspecialchars($bookset_info->school_name) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->grade_name)): ?>
                                Grade: <?= htmlspecialchars($bookset_info->grade_name) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->board_name)): ?>
                                Board: <?= htmlspecialchars($bookset_info->board_name) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->f_name) || !empty($bookset_info->m_name) || !empty($bookset_info->s_name)): ?>
                                Student Name: <?= trim(htmlspecialchars($bookset_info->f_name . ' ' . $bookset_info->m_name . ' ' . $bookset_info->s_name)) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->dob)): ?>
                                Date of Birth: <?= date('d-m-Y', strtotime($bookset_info->dob)) ?>
                                <?php endif; ?>
                              </td>
                            </tr>
                            <?php
                          }
                        } else {
                          // Display regular order items
                          foreach ($items_arr as $key => $val) {
                          $rowwise_total_price = isset($val->total_price) ? $val->total_price : (isset($val->product_price) ? $val->product_price * (isset($val->product_qty) ? $val->product_qty : 1) : 0);
                          $_total_price += $rowwise_total_price;
                          
                          // Get product image
                          $product_image = '';
                          if (isset($val->product_id) && !empty($val->product_id)) {
                            // Try to get image from different product tables
                            $product_id = $val->product_id;
                           
                            
                              $img_query = $this->db->select('image_path')
                                ->from('erp_uniform_images')
                                ->where('uniform_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();

                            if ($img_query->num_rows() > 0) {
                              $image_path = $img_query->row()->image_path;
                              // Handle path format - ensure assets/uploads/ prefix is always present
                              if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0) {
                                $product_image = $image_path;
                              } elseif (strpos($image_path, 'assets/uploads/') === 0) {
                                $product_image = $image_path;
                              } elseif (strpos($image_path, 'vendors/') === 0) {
                                $product_image = 'assets/uploads/' . $image_path;
                              } else {
                                $product_image = 'assets/uploads/' . ltrim($image_path, '/');
                              }
                            }
                          }
                        ?>
                        <tr>
                          <td class="thick-line bdr_left" style="width: 100px;">
                            <?php if (!empty($product_image)): 
                              // Always use base_url() to construct the full URL
                              if (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) {
                                $img_url = $product_image;
                              } else {
                                $img_url = base_url($product_image);
                              }
                            ?>
                              <img src="<?= $img_url ?>" style="height: 115px;width: auto;border: 1px solid #ddd;border-radius:2px;max-width: 100px;" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>';" />
                            <?php else: ?>
                              <div style="height: 115px;width: 100px;border: 1px solid #ddd;border-radius:2px;display: flex;align-items: center;justify-content: center;background: #f5f5f5;">
                                <i class="fa fa-image" style="font-size: 24px;color: #ccc;"></i>
                              </div>
                            <?php endif; ?>
                          </td>
                          <td class="thick-line bdr_left">
                            <?= isset($val->product_title) ? $val->product_title : (isset($val->product_name) ? $val->product_name : 'N/A') ?>
                            <?php  
                            if(isset($val->is_variation) && $val->is_variation == 1){
                              $var_data = array();
                              if(isset($val->variation_name) && $val->variation_name != '') {
                                $var_data[] = $val->variation_name;
                              }
                              if (!empty($var_data)) {
                                echo '<p class="mb-1"><small>(' . implode(", ", $var_data) . ')</small></p>';
                              }
                            }
                            ?>
                            <?php if (!empty($val->size_name)): ?>
                            <p class="mb-1"><small>Size: <?= $val->size_name; ?></small></p>
                            <?php endif; ?>
                            <?php if (!empty($val->school_name)): ?>
                            <p class="mb-1"><small>School: <?= $val->school_name; ?></small></p>
                            <?php endif; ?>
                            <?php if (!empty($val->branch_name)): ?>
                            <p class="mb-1"><small>Branch: <?= $val->branch_name; ?></small></p>
                            <?php endif; ?>
                          </td>
                          <td class="thick-line text-center bdr_left"><?= isset($val->product_sku) ? $val->product_sku : '-' ?></td>
                          <td class="thick-line text-center bdr_left"><?= isset($val->product_qty) ? $val->product_qty : '1' ?></td>
                          <td class="text-center thick-line"><?= $currency_code . ' ' . (isset($val->product_price) ? $val->product_price : '0') ?></td>
                          <td class="text-center thick-line"><?= $currency_code . ' ' . number_format($rowwise_total_price, 2) ?>                          </td>
                        </tr>
                        <?php 
                          } 
                        } // End of else block for regular items
                        ?>

                        <tr>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line text-right bdr_left"><strong>Total</strong></td>
                          <td class="text-center thick-line" style="font-weight: 600">
                            <?= $currency_code . ' ' . number_format($_total_price, 2) ?></td>
                        </tr>

                        <?php if (!empty($order_data[0]->coupon_code) && isset($order_data[0]->discount_amt) && $order_data[0]->discount_amt > 0): ?>
                        <tr>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line text-right bdr_left">
                            <strong>Coupon (<?= htmlspecialchars($order_data[0]->coupon_code) ?>)</strong>
                          </td>
                          <td class="text-center thick-line" style="font-weight: 600">
                            <?= $currency_code . ' ' . number_format($order_data[0]->discount_amt, 2) ?>
                          </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line text-right bdr_left"><strong>Courier Charge</strong></td>
                          <td class="text-center thick-line" style="font-weight: 600">
                            <?= isset($order_data[0]->delivery_charge) && $order_data[0]->delivery_charge > 0 ? '+ ' . $currency_code . ' ' . number_format($order_data[0]->delivery_charge, 2) : 'Free' ?>
                          </td>
                        </tr>
                        <?php if (isset($order_data[0]->wallet_amount) && $order_data[0]->wallet_amount > 0): ?>
                        <tr>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line text-right bdr_left"><strong>Wallet Used</strong></td>
                          <td class="text-center thick-line" style="font-weight: 600">
                            <?= $currency_code . ' ' . number_format($order_data[0]->wallet_amount, 2) ?>
                          </td>
                        </tr>
                        <?php endif; ?>

                        <tr>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line bdr_left"></td>
                          <td class="thick-line text-right bdr_left">
                            <strong>Payable Amount</strong></td>
                          <td class="text-center thick-line" style="font-weight: 600">
                            <?= $currency_code . ' ' . (isset($order_data[0]->payable_amt) ? number_format($order_data[0]->payable_amt, 2) : number_format($_total_price, 2)) ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

