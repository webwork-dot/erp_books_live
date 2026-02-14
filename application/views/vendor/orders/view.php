<style>
.order-page .card {
  border: 1px solid #e5e5e5;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.order-page .card-header {
  background: #fafafa;
  font-weight: 600;
  border-bottom: 1px solid #e5e5e5;
  padding: 12px 15px;
}

.order-page table td {
  vertical-align: middle;
}

.timeline-item {
  border-left: 2px solid #28a745;
  padding-left: 10px;
  margin-left: 5px;
  margin-bottom: 15px;
  position: relative;
}

.timeline-item:before {
  content: '';
  position: absolute;
  left: -6px;
  top: 0;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #28a745;
}

.timeline-item.completed {
  border-left-color: #28a745;
}

.timeline-item.completed:before {
  background: #28a745;
}

.timeline-item.pending {
  border-left-color: #ffc107;
}

.timeline-item.pending:before {
  background: #ffc107;
}

.order-page .sticky-sidebar {
  position: sticky;
  top: 20px;
}

.order-page .product-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border: 1px solid #eee;
  border-radius: 4px;
}

.order-page .badge {
  padding: 6px 12px;
  font-size: 12px;
  font-weight: 600;
}

.order-page .card-body .row {
  margin-left: 0;
  margin-right: 0;
}

.order-page .card-body .row > [class*="col-"] {
  padding-left: 5px;
  padding-right: 5px;
}
</style>

<?php 
$currency = isset($order_data[0]->currency) ? $order_data[0]->currency : 'INR';
$currency_code = $currency;
$ci = &get_instance();

// Calculate totals
$_total_price = 0; // Subtotal (pre-tax)
$_total_qty = 0;
$_total_tax = 0; // Total GST/Tax amount

// For bookset orders, calculate total from bookset_products if available
if (isset($order_type) && $order_type == 'bookset' && !empty($bookset_products)) {
  // Calculate from erp_bookset_order_products which has the actual ordered prices
  $package_totals = array(); // Track package totals to avoid double counting
  foreach ($bookset_products as $bookset_product) {
    // For subtotal (pre-tax), we should use prices without GST
    // First, try to use unit_price * quantity (assuming unit_price is pre-tax)
    if (isset($bookset_product->unit_price) && $bookset_product->unit_price > 0 && isset($bookset_product->quantity)) {
      $_total_price += (float)$bookset_product->unit_price * (int)$bookset_product->quantity;
    }
    // Second, try total_price (but this might include tax, so we'll subtract tax if available)
    elseif (isset($bookset_product->total_price) && $bookset_product->total_price > 0) {
      // If we have a way to get pre-tax price, use it; otherwise use total_price
      // Note: For bookset products, total_price might already be pre-tax
      $_total_price += (float)$bookset_product->total_price;
    }
    // Third, try package_price (sum per package, not per product)
    elseif (isset($bookset_product->package_price) && $bookset_product->package_price > 0) {
      $pkg_id = isset($bookset_product->package_id) ? $bookset_product->package_id : 0;
      if (!isset($package_totals[$pkg_id])) {
        $package_totals[$pkg_id] = (float)$bookset_product->package_price;
        $_total_price += $package_totals[$pkg_id];
      }
    }
    $_total_qty += isset($bookset_product->quantity) ? (int)$bookset_product->quantity : 1;
  }
  
  // If still 0, try to get from order items
  if ($_total_price == 0) {
    foreach ($items_arr as $val) {
      if (isset($val->order_type) && $val->order_type == 'bookset') {
        // Try to get from erp_bookset_order_products table directly
        if ($this->db->table_exists('erp_bookset_order_products')) {
          $order_products = $this->db->select('SUM(total_price) as total')
            ->from('erp_bookset_order_products')
            ->where('order_id', isset($order_data[0]->id) ? $order_data[0]->id : 0)
            ->get()
            ->row();
          if (!empty($order_products) && isset($order_products->total) && $order_products->total > 0) {
            $_total_price = (float)$order_products->total;
            break;
          }
        }
        
        // Fallback: use excl_price_total (pre-tax) if available, otherwise total_price
        if (isset($val->excl_price_total) && $val->excl_price_total > 0) {
          $_total_price += (float)$val->excl_price_total;
        } elseif (isset($val->total_price) && $val->total_price > 0) {
          $_total_price += (float)$val->total_price;
        } elseif (isset($val->product_price) && $val->product_price > 0) {
          $_total_price += (float)$val->product_price * (isset($val->product_qty) ? (int)$val->product_qty : 1);
        } else {
          // Last resort: try to calculate from packages
          if (!empty($val->package_id)) {
            $package_ids_array = explode(',', $val->package_id);
            $package_ids_array = array_filter(array_map('trim', $package_ids_array));
            if (!empty($package_ids_array) && $this->db->table_exists('erp_bookset_packages')) {
              $packages = $this->db->select('package_price, package_offer_price')
                ->from('erp_bookset_packages')
                ->where_in('id', $package_ids_array)
                ->get()
                ->result();
              foreach ($packages as $pkg) {
                $pkg_price = ($pkg->package_offer_price > 0) ? (float)$pkg->package_offer_price : (float)$pkg->package_price;
                $_total_price += $pkg_price;
              }
            }
          }
        }
        $_total_qty += isset($val->product_qty) ? (int)$val->product_qty : 1;
        break; // Only process first bookset item
      }
    }
  }
} else {
  // Regular individual/uniform items
  foreach ($items_arr as $val) {
    // Calculate price (pre-tax)
    $rowwise_total_price = isset($val->excl_price_total) && $val->excl_price_total > 0 
      ? (float)$val->excl_price_total 
      : (isset($val->total_price) && $val->total_price > 0 
        ? (float)$val->total_price 
        : (isset($val->product_price) && $val->product_price > 0 
          ? (float)$val->product_price * (isset($val->product_qty) ? (int)$val->product_qty : 1) 
          : 0));
    $_total_price += $rowwise_total_price;
    
    // Add tax amount
    if (isset($val->total_gst_amt) && $val->total_gst_amt > 0) {
      $_total_tax += (float)$val->total_gst_amt;
    }
    
    $_total_qty += isset($val->product_qty) ? (int)$val->product_qty : 1;
  }
}

// Calculate tax for bookset orders from order items
if (isset($order_type) && $order_type == 'bookset' && !empty($items_arr)) {
  foreach ($items_arr as $val) {
    if (isset($val->order_type) && $val->order_type == 'bookset') {
      if (isset($val->total_gst_amt) && $val->total_gst_amt > 0) {
        $_total_tax += (float)$val->total_gst_amt;
      }
      break; // Only process first bookset item
    }
  }
}

// If tax is still 0, try to get from order details
if ($_total_tax == 0 && !empty($order_data[0])) {
  if (isset($order_data[0]->gst_total) && $order_data[0]->gst_total > 0) {
    $_total_tax = (float)$order_data[0]->gst_total;
  }
}

// Final fallback: If total is still 0 for bookset orders, use order's payable_amt or total_amt
if ($_total_price == 0 && isset($order_type) && $order_type == 'bookset' && !empty($order_data[0])) {
  if (isset($order_data[0]->payable_amt) && $order_data[0]->payable_amt > 0) {
    $_total_price = (float)$order_data[0]->payable_amt;
  } elseif (isset($order_data[0]->total_amt) && $order_data[0]->total_amt > 0) {
    $_total_price = (float)$order_data[0]->total_amt;
  }
}

// Get status badge
$status_badge = 'badge-warning';
$status_text = 'Pending';
                  if($order_data[0]->payment_status == 'pending'){ 
  $status_badge = 'badge-danger';
  $status_text = 'Payment Pending';
                  } else{ 
                    switch ($order_data[0]->order_status) {
                      case '1':
      $status_badge = 'badge-warning';
      $status_text = 'Pending';
                        break;
                      case '2':
      $status_badge = 'badge-info';
      $status_text = 'Processing';
                        break;
                      case '3':
      $status_badge = 'badge-primary';
      $status_text = 'Out for Delivery';
                        break;
                      case '4':
      $status_badge = 'badge-success';
      $status_text = 'Delivered';
                        break;
                      case '7':
      $status_badge = 'badge-danger';
      $status_text = 'Return';
                        break;
                      default:
      $status_badge = 'badge-secondary';
      $status_text = 'Cancelled';
                        break;
                    }
                  } 

// Get payment method display name
$payment_method_display = 'Cash On Delivery';
if($order_data[0]->payment_method == 'cod'){ 
  $payment_method_display = 'Cash On Delivery'; 
} elseif($order_data[0]->payment_method == 'cashfree'){ 
  $payment_method_display = 'Cashfree'; 
} elseif($order_data[0]->payment_method == 'razorpay'){ 
  $payment_method_display = 'Razorpay'; 
} else{ 
  $payment_method_display = ucfirst($order_data[0]->payment_method); 
}
?>

<div class="container-fluid order-page" style="padding: 20px;">
  <!-- BACK BUTTON -->
  <div class="mb-3">
    <a href="<?php echo base_url('orders'); ?>" class="btn btn-secondary btn-sm">
      <i class="fa fa-arrow-left"></i> Back to Orders
    </a>
                </div>
  
  <div class="row">
    
    <!-- LEFT SIDE (70%) -->
    <div class="col-md-8">
      
      <!-- ORDER HEADER -->
      <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">Order #<?= $order_data[0]->order_unique_id ?></h5>
            <small class="text-muted"><?= date('D, M d, Y, h:i A', strtotime($order_data[0]->order_date)); ?></small>
              </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge <?= $status_badge ?>"><?= $status_text ?></span>
            <?php if ($order_data[0]->order_status != 5): ?>
              <?php if (!empty($order_data[0]->invoice_url)): ?>
                <a href="<?php echo base_url($order_data[0]->invoice_url); ?>" class="btn btn-sm btn-success" target="_blank">
                  <i class="fa fa-download"></i> Invoice
                </a>
              <?php else: ?>
                <a href="<?php echo base_url('orders/download_invoice/' . $order_data[0]->order_unique_id); ?>" class="btn btn-sm btn-success" target="_blank">
                  <i class="fa fa-download"></i> Invoice
                </a>
              <?php endif; ?>
            <?php endif; ?>
                </div>
              </div>
            </div>

      <!-- PRODUCTS CARD -->
      <div class="card">
        <div class="card-header">
          <b>Products (<?= $_total_qty ?> Items)</b>
          </div>
        <div class="card-body p-0">
                  <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 60px;"></th>
                  <th>Product</th>
                  <th width="100" class="text-center">SKU</th>
                  <th width="80" class="text-center">Qty</th>
                  <th width="120" class="text-end">Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        // Display bookset products if order type is bookset
                if (isset($order_type) && $order_type == 'bookset') {
                  // If bookset_products is empty, try to get from items_arr
                  if (empty($bookset_products) && !empty($items_arr)) {
                    foreach ($items_arr as $item) {
                      if (isset($item->order_type) && $item->order_type == 'bookset') {
                        // Create a bookset product entry from order item
                        $bookset_products[] = (object)array(
                          'package_id' => 0,
                          'package_name' => 'Bookset Order',
                          'package_price' => isset($item->product_price) ? $item->product_price : 0,
                          'product_id' => isset($item->product_id) ? $item->product_id : 0,
                          'product_type' => 'bookset',
                          'product_name' => isset($item->product_title) ? $item->product_title : 'Bookset',
                          'product_sku' => isset($item->product_sku) ? $item->product_sku : '',
                          'quantity' => isset($item->product_qty) ? $item->product_qty : 1,
                          'unit_price' => isset($item->product_price) ? $item->product_price : 0,
                          'total_price' => isset($item->total_price) ? $item->total_price : 0,
                        );
                        break; // Only use first bookset item
                      }
                    }
                  }
                  
                  if (!empty($bookset_products)) {
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
                      <td colspan="5" style="background-color: #f0f0f0; font-weight: bold; padding: 10px;">
                                Package: <?= htmlspecialchars($package_data['package_name']) ?> 
                                (<?= $currency_code . ' ' . number_format($package_data['package_price'], 2) ?>)
                              </td>
                            </tr>
                            <?php
                            foreach ($package_data['products'] as $bookset_product) {
                      // Get product image
                              $product_image = '';
                              if (!empty($bookset_product->product_id)) {
                                $product_id = $bookset_product->product_id;
                                $product_type = $bookset_product->product_type;
                                
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
                                } elseif ($product_type == 'stationery') {
                                  // Check if stationery images table exists
                                  if ($this->db->table_exists('erp_stationery_images')) {
                                    $img_query = $this->db->select('image_path')
                                      ->from('erp_stationery_images')
                                      ->where('stationery_id', $product_id)
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
                              }
                            ?>
                            <tr>
                        <td>
                                <?php if (!empty($product_image)): 
                                  if (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) {
                                    $img_url = $product_image;
                                  } else {
                                    $img_url = base_url($product_image);
                                  }
                                ?>
                            <img src="<?= $img_url ?>" class="product-image" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>';" />
                                <?php else: ?>
                            <div class="product-image d-flex align-items-center justify-content-center" style="background: #f5f5f5;">
                              <i class="fa fa-image text-muted"></i>
                                  </div>
                                <?php endif; ?>
                              </td>
                        <td>
                          <div>
                            <b><?= htmlspecialchars($bookset_product->product_name) ?></b>
                            <br><small class="text-muted">Type: <?= ucfirst($bookset_product->product_type) ?></small>
                                <?php if (!empty($bookset_product->product_sku)): ?>
                            <br><small class="text-muted">SKU: <?= htmlspecialchars($bookset_product->product_sku) ?></small>
                                <?php endif; ?>
                          </div>
                              </td>
                        <td class="text-center"><?= !empty($bookset_product->product_sku) ? htmlspecialchars($bookset_product->product_sku) : '-' ?></td>
                        <td class="text-center"><?= $bookset_product->quantity ?></td>
                        <td class="text-end">
                          <?php 
                          // Calculate unit price with fallbacks
                          $display_unit_price = 0;
                          if (isset($bookset_product->unit_price) && $bookset_product->unit_price > 0) {
                            $display_unit_price = (float)$bookset_product->unit_price;
                          } elseif (isset($bookset_product->total_price) && $bookset_product->total_price > 0 && isset($bookset_product->quantity) && $bookset_product->quantity > 0) {
                            $display_unit_price = (float)$bookset_product->total_price / (int)$bookset_product->quantity;
                          } elseif (isset($bookset_product->package_price) && $bookset_product->package_price > 0) {
                            // If unit price is 0, try to estimate from package price
                            // This is a rough estimate - ideally unit_price should be stored correctly
                            $display_unit_price = (float)$bookset_product->package_price;
                          }
                          echo $currency_code . ' ' . number_format($display_unit_price, 2);
                          ?>
                        </td>
                            </tr>
                            <?php
                            }
                          }
                          
                  } // Close if (!empty($bookset_products))
                  
                  // Display bookset information (student details) if available
                          if (!empty($bookset_info)) {
                            ?>
                            <tr>
                      <td colspan="5" style="background-color: #e8f4f8; padding: 15px;">
                                <strong>Bookset Information:</strong><br>
                                <?php if (!empty($bookset_info->school_name)): ?>
                        <strong>School:</strong> <?= htmlspecialchars($bookset_info->school_name) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->grade_name)): ?>
                        <strong>Grade:</strong> <?= htmlspecialchars($bookset_info->grade_name) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->board_name)): ?>
                        <strong>Board:</strong> <?= htmlspecialchars($bookset_info->board_name) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->f_name) || !empty($bookset_info->m_name) || !empty($bookset_info->s_name)): ?>
                        <strong>Student Name:</strong> <?= trim(htmlspecialchars(($bookset_info->f_name ?? '') . ' ' . ($bookset_info->m_name ?? '') . ' ' . ($bookset_info->s_name ?? ''))) ?><br>
                        <?php endif; ?>
                        <?php if (!empty($bookset_info->roll_number)): ?>
                        <strong>Roll Number:</strong> <?= htmlspecialchars($bookset_info->roll_number) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($bookset_info->dob)): ?>
                        <strong>Date of Birth:</strong> <?= date('d-m-Y', strtotime($bookset_info->dob)) ?>
                                <?php endif; ?>
                              </td>
                            </tr>
                            <?php
                          }
                        } else {
                          // Display regular order items
                          foreach ($items_arr as $key => $val) {
                          // Get product image
                          $product_image = '';
                          if (isset($val->product_id) && !empty($val->product_id)) {
                            $product_id = $val->product_id;
                              $img_query = $this->db->select('image_path')
                                ->from('erp_uniform_images')
                                ->where('uniform_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                            if ($img_query->num_rows() > 0) {
                              $image_path = $img_query->row()->image_path;
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
                      <td>
                            <?php if (!empty($product_image)): 
                              if (strpos($product_image, 'http://') === 0 || strpos($product_image, 'https://') === 0) {
                                $img_url = $product_image;
                              } else {
                                $img_url = base_url($product_image);
                              }
                            ?>
                          <img src="<?= $img_url ?>" class="product-image" onerror="this.onerror=null; this.src='<?php echo base_url('assets/template/img/placeholder-image.png'); ?>';" />
                            <?php else: ?>
                          <div class="product-image d-flex align-items-center justify-content-center" style="background: #f5f5f5;">
                            <i class="fa fa-image text-muted"></i>
                              </div>
                            <?php endif; ?>
                          </td>
                      <td>
                        <div>
                          <b><?= isset($val->product_title) ? htmlspecialchars($val->product_title) : (isset($val->product_name) ? htmlspecialchars($val->product_name) : 'N/A') ?></b>
                          <?php if(isset($val->is_variation) && $val->is_variation == 1 && isset($val->variation_name) && $val->variation_name != ''): ?>
                            <br><small class="text-muted"><?= htmlspecialchars($val->variation_name) ?></small>
                          <?php endif; ?>
                            <?php if (!empty($val->size_name)): ?>
                            <br><small class="text-muted">Size: <?= htmlspecialchars($val->size_name) ?></small>
                            <?php endif; ?>
                            <?php if (!empty($val->school_name)): ?>
                            <br><small class="text-muted">School: <?= htmlspecialchars($val->school_name) ?></small>
                            <?php endif; ?>
                        </div>
                          </td>
                      <td class="text-center"><?= isset($val->product_sku) ? htmlspecialchars($val->product_sku) : '-' ?></td>
                      <td class="text-center"><?= isset($val->product_qty) ? $val->product_qty : '1' ?></td>
                      <td class="text-end"><?= $currency_code . ' ' . number_format(isset($val->product_price) ? $val->product_price : 0, 2) ?></td>
                        </tr>
                        <?php 
                          } 
                }
                ?>
              </tbody>
            </table>
          </div>
          
          <!-- Order Summary Footer -->
          <div class="card-footer bg-light">
            <div class="row">
              <div class="col-md-8 text-end">
                <strong>Subtotal (Pre-Tax):</strong>
              </div>
              <div class="col-md-4 text-end">
                <strong><?= $currency_code . ' ' . number_format($_total_price, 2) ?></strong>
              </div>
            </div>
            <?php if ($_total_tax > 0): ?>
            <div class="row mt-2">
              <div class="col-md-8 text-end">
                <strong>Tax/GST:</strong>
              </div>
              <div class="col-md-4 text-end">
                <strong>+ <?= $currency_code . ' ' . number_format($_total_tax, 2) ?></strong>
              </div>
            </div>
            <?php endif; ?>
                        <?php if (!empty($order_data[0]->coupon_code) && isset($order_data[0]->discount_amt) && $order_data[0]->discount_amt > 0): ?>
            <div class="row mt-2">
              <div class="col-md-8 text-end">
                <strong>Coupon (<?= htmlspecialchars($order_data[0]->coupon_code) ?>):</strong>
              </div>
              <div class="col-md-4 text-end">
                <strong>- <?= $currency_code . ' ' . number_format($order_data[0]->discount_amt, 2) ?></strong>
              </div>
            </div>
                        <?php endif; ?>
            <div class="row mt-2">
              <div class="col-md-8 text-end">
                <strong>Delivery Charge:</strong>
              </div>
              <div class="col-md-4 text-end">
                <strong><?= isset($order_data[0]->delivery_charge) && $order_data[0]->delivery_charge > 0 ? '+ ' . $currency_code . ' ' . number_format($order_data[0]->delivery_charge, 2) : 'Free' ?></strong>
              </div>
            </div>
                        <?php if (isset($order_data[0]->wallet_amount) && $order_data[0]->wallet_amount > 0): ?>
            <div class="row mt-2">
              <div class="col-md-8 text-end">
                <strong>Wallet Used:</strong>
              </div>
              <div class="col-md-4 text-end">
                <strong>- <?= $currency_code . ' ' . number_format($order_data[0]->wallet_amount, 2) ?></strong>
              </div>
            </div>
                        <?php endif; ?>
            <hr>
            <div class="row">
              <div class="col-md-8 text-end">
                <h5 class="mb-0"><strong>Total (After Tax + Delivery):</strong></h5>
              </div>
              <div class="col-md-4 text-end">
                <h5 class="mb-0"><strong>
                  <?php 
                  // Calculate total: Subtotal + Tax + Delivery - Discount - Wallet
                  $calculated_total = $_total_price + $_total_tax + (isset($order_data[0]->delivery_charge) ? (float)$order_data[0]->delivery_charge : 0);
                  $calculated_total -= (isset($order_data[0]->discount_amt) ? (float)$order_data[0]->discount_amt : 0);
                  $calculated_total -= (isset($order_data[0]->wallet_amount) ? (float)$order_data[0]->wallet_amount : 0);
                  
                  // Use payable_amt if available, otherwise use calculated total
                  $final_total = isset($order_data[0]->payable_amt) && $order_data[0]->payable_amt > 0 
                    ? (float)$order_data[0]->payable_amt 
                    : $calculated_total;
                  echo $currency_code . ' ' . number_format($final_total, 2);
                  ?>
                </strong></h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
      
        </div>

    <!-- RIGHT PANEL (30%) - Sticky -->
    <div class="col-md-4 sticky-sidebar">
      
      <!-- ORDER ACTIONS CARD -->
      <div class="card mb-3">
        <div class="card-header bg-light">
          <h6 class="mb-0"><i class="fa fa-cog"></i> <strong>Order Actions</strong></h6>
      </div>
        <div class="card-body">
          <?php 
          $current_status = $order_data[0]->order_status;
          $has_shipping_label = !empty($order_data[0]->shipping_label);
          // Also check shipping_label_generated field if it exists
          if (isset($order_data[0]->shipping_label_generated) && $order_data[0]->shipping_label_generated == 1) {
            $has_shipping_label = true;
          }
          $courier = isset($order_data[0]->courier) ? $order_data[0]->courier : '';
          ?>
          
          <!-- Status 1: Pending - Show Move to Process button -->
          <?php if ($current_status == '1' || $current_status == 1): ?>
            <div class="d-grid">
              <button type="button" class="btn btn-primary btn-lg" onclick="moveToProcessing('<?= $order_data[0]->order_unique_id ?>')">
                <i class="fa fa-arrow-right me-2"></i> Move to Processing
              </button>
    </div>
          <?php endif; ?>
          
          <!-- Status 2: Processing - Show Shipper Selection or Generate Label -->
          <?php if ($current_status == '2' || $current_status == 2): ?>
            <?php if (empty($courier)): ?>
              <!-- Shipper Selection -->
              <div class="mb-3">
                <p class="text-muted mb-3 text-center"><strong>Select Shipping Method</strong></p>
                <div class="row" style="margin: 0;">
                  <div class="col-5" style="padding: 5px;">
                    <button type="button" class="btn btn-outline-primary btn-lg w-100" onclick="selectShipper('<?= $order_data[0]->order_unique_id ?>', 'manual')" style="width: 100%;">
                      <i class="fa fa-truck"></i> Self Delivery
                    </button>
  </div>
                  <div class="col-2 text-center" style="padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-muted" style="font-weight: bold;">OR</span>
</div>
                  <div class="col-5" style="padding: 5px;">
                    <button type="button" class="btn btn-outline-info btn-lg w-100" onclick="selectShipper('<?= $order_data[0]->order_unique_id ?>', 'shiprocket')" style="width: 100%;">
                      <i class="fa fa-shipping-fast"></i> 3rd Party
                    </button>
                  </div>
                </div>
              </div>
            <?php elseif ($courier == 'manual'): ?>
              <!-- Self Delivery - Show Generate Label button -->
              <div class="mb-3">
                <div class="alert alert-light border d-flex align-items-center mb-3">
                  <i class="fa fa-truck text-primary me-2"></i>
                  <span><strong>Self Delivery</strong> Selected</span>
                </div>
              </div>
              <?php if (!$has_shipping_label): ?>
                <div class="d-grid">
                  <a href="<?php echo base_url('orders/generate_shipping_label/' . $order_data[0]->order_unique_id); ?>" 
                     class="btn btn-primary btn-lg" 
                     id="generateLabelBtn"
                     onclick="showGenerateLoading(this); return true;">
                    <span id="generateLabelText">
                      <i class="fa fa-file-pdf me-2"></i> Generate Shipping Label
                    </span>
                    <span id="generateLabelSpinner" style="display: none;">
                      <i class="fa fa-spinner fa-spin me-2"></i> Generating...
                    </span>
                  </a>
                </div>
              <?php else: ?>
                <div class="row" style="margin: 0;">
                  <div class="col-6" style="padding: 5px;">
                    <a href="<?php echo base_url('orders/download_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-info btn-lg w-100" target="_blank" style="width: 100%;">
                      <i class="fa fa-download"></i> Download
                    </a>
                  </div>
                  <div class="col-6" style="padding: 5px;">
                    <button type="button" class="btn btn-success btn-lg w-100" onclick="moveToOutForDelivery('<?= $order_data[0]->order_unique_id ?>')" style="width: 100%;">
                      <i class="fa fa-truck"></i> Out for Delivery
                    </button>
                  </div>
                </div>
              <?php endif; ?>
            <?php elseif ($courier == 'shiprocket'): ?>
              <!-- 3rd Party - Show Shiprocket info -->
              <div class="mb-3">
                <div class="alert alert-info border-0 mb-3">
                  <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-shipping-fast me-2"></i>
                    <strong>3rd Party Shipping</strong>
                  </div>
                  <small class="text-muted">Shiprocket</small>
                  <?php if (!empty($order_data[0]->awb_number)): ?>
                    <div class="mt-2">
                      <strong>AWB:</strong> <code><?= htmlspecialchars($order_data[0]->awb_number) ?></code>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ($has_shipping_label): ?>
                <div class="d-grid">
                  <button type="button" class="btn btn-success btn-lg" onclick="moveToOutForDelivery('<?= $order_data[0]->order_unique_id ?>')">
                    <i class="fa fa-truck me-2"></i> Move to Out for Delivery
                  </button>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          <?php endif; ?>
          
          <!-- Status 3: Out for Delivery - Show Delivered button -->
          <?php if ($current_status == '3' || $current_status == 3): ?>
            <?php if ($has_shipping_label): ?>
              <div class="row" style="margin: 0;">
                <div class="col-6" style="padding: 5px;">
                  <button type="button" class="btn btn-success btn-lg w-100" onclick="moveToDelivered('<?= $order_data[0]->order_unique_id ?>')" style="width: 100%;">
                    <i class="fa fa-check-circle"></i> Delivered
                  </button>
                </div>
                <div class="col-6" style="padding: 5px;">
                  <a href="<?php echo base_url('orders/download_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-outline-info btn-lg w-100" target="_blank" style="width: 100%;">
                    <i class="fa fa-download"></i> Download
                  </a>
                </div>
              </div>
            <?php else: ?>
              <div class="d-grid">
                <button type="button" class="btn btn-success btn-lg" onclick="moveToDelivered('<?= $order_data[0]->order_unique_id ?>')">
                  <i class="fa fa-check-circle me-2"></i> Mark as Delivered
                </button>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          
          <!-- Status 4: Delivered - Show info only -->
          <?php if ($current_status == '4' || $current_status == 4): ?>
            <div class="alert alert-success border-0 mb-3">
              <div class="d-flex align-items-center mb-2">
                <i class="fa fa-check-circle me-2"></i>
                <strong>Order Delivered</strong>
              </div>
              <?php if (!empty($order_data[0]->delivery_date)): ?>
                <small class="text-muted">
                  <?= date('D, M d, Y, h:i A', strtotime($order_data[0]->delivery_date)) ?>
                </small>
              <?php endif; ?>
            </div>
            <?php if ($has_shipping_label): ?>
              <div class="d-grid">
                <a href="<?php echo base_url('orders/download_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-outline-info" target="_blank">
                  <i class="fa fa-download me-2"></i> Download Label
                </a>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          
          <!-- TESTING: Regenerate Label Button (Always Visible) -->
          <hr class="my-3">
          <div class="d-grid">
            <a href="<?php echo base_url('orders/generate_shipping_label/' . $order_data[0]->order_unique_id); ?>" 
               class="btn btn-warning btn-sm" 
               id="regenerateLabelBtn"
               onclick="showRegenerateLoading(this); return true;">
              <span id="regenerateLabelText">
                <i class="fa fa-refresh me-2"></i> Regenerate Label (Testing)
              </span>
              <span id="regenerateLabelSpinner" style="display: none;">
                <i class="fa fa-spinner fa-spin me-2"></i> Generating...
              </span>
            </a>
          </div>
        </div>
      </div>
      
      <!-- CUSTOMER CARD -->
      <div class="card mb-3">
        <div class="card-header">
          <b>Customer</b>
        </div>
        <div class="card-body">
          <div><b><?= htmlspecialchars(isset($address_arr[0]->name) ? $address_arr[0]->name : $order_data[0]->user_name) ?></b></div>
          <div class="text-muted"><?= htmlspecialchars(isset($address_arr[0]->mobile_no) ? $address_arr[0]->mobile_no : $order_data[0]->user_phone) ?></div>
        </div>
      </div>

      <!-- ADDRESS CARD -->
      <div class="card mb-3">
        <div class="card-header">
          <b>Billing & Shipping Address</b>
        </div>
        <div class="card-body">
          <?php if (isset($address_arr[0])): 
            $addr = $address_arr[0];
          ?>
            <div><b><?= htmlspecialchars($addr->name) ?></b></div>
            <div class="text-muted"><?= htmlspecialchars($addr->mobile_no) ?></div>
            <div class="mt-2">
              <?php 
              $address_parts = array();
              if (!empty($addr->address)) $address_parts[] = htmlspecialchars($addr->address);
              if (!empty($addr->city)) $address_parts[] = htmlspecialchars($addr->city);
              if (!empty($addr->state)) $address_parts[] = htmlspecialchars($addr->state);
              if (!empty($addr->pincode)) $address_parts[] = htmlspecialchars($addr->pincode);
              if (!empty($addr->country)) $address_parts[] = htmlspecialchars($addr->country);
              echo implode(', ', $address_parts);
              ?>
            </div>
            <?php if(!empty($addr->landmark)): ?>
            <div class="mt-1 text-muted">
              <small>Landmark: <?= htmlspecialchars($addr->landmark) ?></small>
            </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-muted">No address available</div>
          <?php endif; ?>
        </div>
      </div>

      <!-- PAYMENT CARD -->
      <div class="card mb-3">
        <div class="card-header">
          <b>Payment</b>
        </div>
        <div class="card-body">
          <div><b><?= htmlspecialchars($payment_method_display) ?></b></div>
          <div class="text-muted"><?= date('D, M d, Y, h:i A', strtotime($order_data[0]->order_date)); ?></div>
          <?php if (!empty($order_data[0]->txn_id)): ?>
          <div class="mt-2">
            <small class="text-muted">Tran. Id: <?= htmlspecialchars($order_data[0]->txn_id) ?></small>
          </div>
          <?php endif; ?>
          <div class="mt-2">
            <h5 class="mb-0"><b>
              <?php 
              // Calculate total: Subtotal + Tax + Delivery - Discount - Wallet
              $payment_total = $_total_price + $_total_tax + (isset($order_data[0]->delivery_charge) ? (float)$order_data[0]->delivery_charge : 0);
              $payment_total -= (isset($order_data[0]->discount_amt) ? (float)$order_data[0]->discount_amt : 0);
              $payment_total -= (isset($order_data[0]->wallet_amount) ? (float)$order_data[0]->wallet_amount : 0);
              
              // Use payable_amt if available, otherwise use calculated total
              $final_payment = isset($order_data[0]->payable_amt) && $order_data[0]->payable_amt > 0 
                ? (float)$order_data[0]->payable_amt 
                : $payment_total;
              echo $currency_code . ' ' . number_format($final_payment, 2);
              ?>
            </b></h5>
          </div>
        </div>
      </div>

      <!-- TIMELINE CARD -->
      <div class="card">
        <div class="card-header">
          <b>Order Timeline</b>
        </div>
        <div class="card-body">
          <?php
          // Build timeline from actual order dates and status history
          $timeline_items = array();
          
          // 1. Order Placed - Always show
          $timeline_items[] = array(
            'status' => 'Order Placed',
            'date' => $order_data[0]->order_date,
            'completed' => true,
            'notes' => ''
          );
          
          // 2. Processing - Use processing_date from tbl_order_details
          if (!empty($order_data[0]->processing_date)) {
            $timeline_items[] = array(
              'status' => 'Processing',
              'date' => $order_data[0]->processing_date,
              'completed' => true,
              'notes' => 'Order moved to processing'
            );
          }
          
          // 3. Shipping Label Generated - Check from tbl_order_status first, then fallback to shipping_label field
          $label_generated_date = null;
          if (!empty($additional_status)) {
            foreach ($additional_status as $status) {
              if (stripos($status->status_title, 'Shipping Label Generated') !== false || 
                  stripos($status->status_title, 'label') !== false) {
                $label_generated_date = $status->created_at;
                break;
              }
            }
          }
          
          if (!empty($label_generated_date)) {
            $timeline_items[] = array(
              'status' => 'Shipping Label Generated',
              'date' => $label_generated_date,
              'completed' => true,
              'notes' => 'Shipping label has been generated'
            );
          } elseif (!empty($order_data[0]->shipping_label)) {
            // Fallback: if label exists but no status entry, use processing_date or order_date
            $timeline_items[] = array(
              'status' => 'Shipping Label Generated',
              'date' => !empty($order_data[0]->processing_date) ? $order_data[0]->processing_date : $order_data[0]->order_date,
              'completed' => true,
              'notes' => 'Shipping label has been generated'
            );
          }
          
          // 4. Out for Delivery - Use shipment_date from tbl_order_details
          if (!empty($order_data[0]->shipment_date)) {
            $timeline_items[] = array(
              'status' => 'Out for Delivery',
              'date' => $order_data[0]->shipment_date,
              'completed' => true,
              'notes' => 'Order moved to out for delivery'
            );
          }
          
          // 5. Delivered - Use delivery_date from tbl_order_details
          if (!empty($order_data[0]->delivery_date)) {
            $timeline_items[] = array(
              'status' => 'Delivered',
              'date' => $order_data[0]->delivery_date,
              'completed' => true,
              'notes' => 'Order delivered successfully'
            );
          }
          
          // 6. Add additional entries from tbl_order_status (for custom notes)
          if (!empty($additional_status)) {
            foreach ($additional_status as $status) {
              // Only add if it's not already in timeline (check by status title)
              $exists = false;
              foreach ($timeline_items as $item) {
                if (stripos($item['status'], $status->status_title) !== false || 
                    stripos($status->status_title, $item['status']) !== false) {
                  $exists = true;
                  break;
                }
              }
              
              if (!$exists) {
                $timeline_items[] = array(
                  'status' => $status->status_title,
                  'date' => $status->created_at,
                  'completed' => true,
                  'notes' => $status->status_desc
                );
              }
            }
          }
          
          // 7. Add entries from erp_order_status_history if available
          if (!empty($status_history)) {
            foreach ($status_history as $history) {
              if ($history->status_type == 'order_status') {
                $status_label = '';
                switch ($history->new_status) {
                  case '1':
                    $status_label = 'Pending';
                    break;
                  case '2':
                    $status_label = 'Processing';
                    break;
                  case '3':
                    $status_label = 'Out for Delivery';
                    break;
                  case '4':
                    $status_label = 'Delivered';
                    break;
                  case 'label_generated':
                    $status_label = 'Shipping Label Generated';
                    break;
                  case 'shipper_selected':
                    $status_label = 'Shipper Selected';
                    break;
                  case '7':
                    $status_label = 'Return';
                    break;
                  default:
                    $status_label = ucfirst($history->new_status);
                }
                
                // Check if this status already exists in timeline
                $exists = false;
                foreach ($timeline_items as $item) {
                  if (stripos($item['status'], $status_label) !== false || 
                      stripos($status_label, $item['status']) !== false) {
                    // If exists, update notes if this one has more details
                    if (!empty($history->notes) && empty($item['notes'])) {
                      $item['notes'] = $history->notes;
                    }
                    $exists = true;
                    break;
                  }
                }
                
                if (!$exists) {
                  $timeline_items[] = array(
                    'status' => $status_label,
                    'date' => $history->created_at,
                    'completed' => true,
                    'notes' => $history->notes
                  );
                }
              }
            }
          }
          
          // Sort timeline by date
          usort($timeline_items, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
          });
          
          // Set timezone to IST for date display
          date_default_timezone_set('Asia/Kolkata');
          
          // Display timeline
          foreach ($timeline_items as $item) {
            $class = $item['completed'] ? 'completed' : 'pending';
            // Format date in IST timezone
            $display_date = '';
            if (!empty($item['date']) && $item['date'] != '0000-00-00 00:00:00') {
              try {
                // Assume the date is in server timezone, convert to IST
                $date_obj = new DateTime($item['date']);
                $date_obj->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $display_date = $date_obj->format('D, M d, Y, h:i A');
              } catch (Exception $e) {
                // Fallback to simple date formatting
                $display_date = date('D, M d, Y, h:i A', strtotime($item['date']));
              }
            } else {
              $display_date = date('D, M d, Y, h:i A', strtotime($item['date']));
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
          
          // If no timeline items, show at least order placed
          if (empty($timeline_items)) {
            $order_date_display = '';
            if (!empty($order_data[0]->order_date) && $order_data[0]->order_date != '0000-00-00 00:00:00') {
              try {
                $date_obj = new DateTime($order_data[0]->order_date);
                $date_obj->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $order_date_display = $date_obj->format('D, M d, Y, h:i A');
              } catch (Exception $e) {
                $order_date_display = date('D, M d, Y, h:i A', strtotime($order_data[0]->order_date));
              }
            } else {
              $order_date_display = date('D, M d, Y, h:i A', strtotime($order_data[0]->order_date));
            }
            ?>
            <div class="timeline-item completed">
              <div><b>✔ Order Placed</b></div>
              <small class="text-muted"><?= $order_date_display ?></small>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
      
    </div>
    
  </div>
</div>

<!-- JavaScript for Order Actions -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Function to show loading spinner for generate label button
function showGenerateLoading(btn) {
  var btnElement = $(btn);
  var textSpan = $('#generateLabelText');
  var spinnerSpan = $('#generateLabelSpinner');
  
  // Disable button and show loading
  btnElement.prop('disabled', true).addClass('disabled');
  textSpan.hide();
  spinnerSpan.show();
  
  // The page will redirect, so the loading will be visible until redirect
  // If redirect fails, we can add a timeout to re-enable (optional)
  setTimeout(function() {
    // Re-enable after 30 seconds if still on page (fallback)
    btnElement.prop('disabled', false).removeClass('disabled');
    textSpan.show();
    spinnerSpan.hide();
  }, 30000);
}

// Function to show loading spinner for regenerate label button
function showRegenerateLoading(btn) {
  var btnElement = $(btn);
  var textSpan = $('#regenerateLabelText');
  var spinnerSpan = $('#regenerateLabelSpinner');
  
  // Disable button and show loading
  btnElement.prop('disabled', true).addClass('disabled');
  textSpan.hide();
  spinnerSpan.show();
  
  // The page will redirect, so the loading will be visible until redirect
  // If redirect fails, we can add a timeout to re-enable (optional)
  setTimeout(function() {
    // Re-enable after 30 seconds if still on page (fallback)
    btnElement.prop('disabled', false).removeClass('disabled');
    textSpan.show();
    spinnerSpan.hide();
  }, 30000);
}

function moveToProcessing(orderUniqueId) {
  if (confirm('Are you sure you want to move this order to Processing?')) {
    $.ajax({
      url: '<?php echo base_url("orders/move_to_processing_single"); ?>',
      type: 'POST',
      data: {
        order_unique_id: orderUniqueId
      },
      dataType: 'json',
      success: function(response) {
        if (response.status == '200') {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message || 'Error updating order status');
        }
      },
      error: function() {
        alert('Error updating order status. Please try again.');
      }
    });
  }
}

function selectShipper(orderUniqueId, courierType) {
  if (confirm('Set shipper to ' + (courierType == 'manual' ? 'Self Delivery' : '3rd Party (Shiprocket)') + '?')) {
    $.ajax({
      url: '<?php echo base_url("orders/set_shipper"); ?>',
      type: 'POST',
      data: {
        order_unique_id: orderUniqueId,
        courier: courierType,
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
      },
      dataType: 'json',
      success: function(response) {
        if (response.status == '200') {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message || 'Error setting shipper');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        console.error('Response:', xhr.responseText);
        if (xhr.responseJSON && xhr.responseJSON.message) {
          alert(xhr.responseJSON.message);
        } else {
          alert('Error setting shipper. Please try again.');
        }
      }
    });
  }
}

function moveToOutForDelivery(orderUniqueId) {
  if (confirm('Are you sure you want to move this order to Out for Delivery?')) {
    $.ajax({
      url: '<?php echo base_url("orders/move_to_out_for_delivery_single"); ?>',
      type: 'POST',
      data: {
        order_unique_id: orderUniqueId
      },
      dataType: 'json',
      success: function(response) {
        if (response.status == '200') {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message || 'Error updating order status');
        }
      },
      error: function() {
        alert('Error updating order status. Please try again.');
      }
    });
  }
}

function moveToDelivered(orderUniqueId) {
  if (confirm('Are you sure you want to mark this order as Delivered?')) {
    $.ajax({
      url: '<?php echo base_url("orders/move_to_delivered_single"); ?>',
      type: 'POST',
      data: {
        order_unique_id: orderUniqueId
      },
      dataType: 'json',
      success: function(response) {
        if (response.status == '200') {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message || 'Error updating order status');
        }
      },
      error: function() {
        alert('Error updating order status. Please try again.');
      }
    });
  }
}
</script>
