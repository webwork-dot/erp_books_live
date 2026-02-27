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
  width: 80px;
  height: 80px;
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

.badge-payment-school, .badge-deliver-school {
    background-color: #ef1e1e29 !important;
    border: 1px solid #ef1e1e;
    color: #ef1e1e !important;
    font-size: 14px !important;
    border-radius: 4px;
}

.btn-outline-warning {
  color: var(--warning);
  background-color: var(--white);
  border-color: var(--warning) !important;
}

.btn-outline-secondary {
    color: #ef1e1e !important;
    background-color: var(--white);
    border-color: #ef1e1e !important;
}

.btn-outline-secondary:hover {
    color:rgb(255, 255, 255) !important;
    background-color: #ef1e1e !important;
    border-color: #ef1e1e !important;
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
} elseif($order_data[0]->payment_method == 'payment_at_school' || $order_data[0]->payment_method == 'payment_at_scho'){ 
  $payment_method_display = 'Payment at School'; 
} else{ 
  $payment_method_display = ucfirst(str_replace('_', ' ', $order_data[0]->payment_method)); 
}
?>

<div class="container-fluid order-page" style="padding: 20px;">
  <!-- BACK BUTTON, TIMELINE & MOVE BACK -->
  <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex gap-2">
      <a href="<?php echo base_url('orders'); ?>" class="btn btn-secondary btn-sm">
        <i class="fa fa-arrow-left"></i> Back to Orders
      </a>
      <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#orderTimelineModal">
        <i class="fa fa-history"></i> Order Timeline
      </button>
    </div>
    <div class="d-flex gap-2">
      <?php $os = $order_data[0]->order_status; ?>
      <?php if ($os == '2' || $os == 2): ?>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moveBackToPending('<?= $order_data[0]->order_unique_id ?>', this)">
          <i class="fa fa-arrow-left me-1"></i> Move Back to New Order
        </button>
      <?php endif; ?>
      <?php if ($os == '3' || $os == 3): ?>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moveBackToProcessing('<?= $order_data[0]->order_unique_id ?>', this)">
          <i class="fa fa-arrow-left me-1"></i> Move Back to Processing
        </button>
      <?php endif; ?>
    </div>
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
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge <?= $status_badge ?>">Order Status: <?= $status_text ?></span>
            <?php if (isset($order_type) && $order_type == 'bookset'): ?>
              <span class="badge badge-info">Bookset Order</span>
            <?php endif; ?>
            <?php if (isset($is_payment_at_school) && $is_payment_at_school): ?>
              <span class="badge badge-pill badge-payment-school">Payment at School</span>
            <?php endif; ?>
            <?php if (isset($is_deliver_at_school) && $is_deliver_at_school): ?>
              <span class="badge badge-pill badge-deliver-school">Deliver at School</span>
            <?php endif; ?>
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
            <a href="<?php echo base_url('orders/test_invoice/' . $order_data[0]->order_unique_id); ?>" class="btn btn-sm btn-outline-secondary" target="_blank" title="Test invoice generation">
              <i class="fa fa-file-pdf-o"></i> Test Invoice
            </a>
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
                  <th style="width: 100px;"></th>
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
                  // Use the new structure from items_arr (packages and books arrays)
                  $bookset_found = false;
                  foreach ($items_arr as $item) {
                    if (isset($item->order_type) && $item->order_type == 'bookset' && !empty($item->packages)) {
                      $bookset_found = true;

                      // Display bookset SKU row first
                      if (!empty($item->product_sku)) {
                        ?>
                        <tr>
                          <td colspan="5" style="background-color: #e8f4f8; font-weight: bold; padding: 10px;">
                            Bookset SKU: <?= htmlspecialchars($item->product_sku) ?>
                          </td>
                        </tr>
                        <?php
                      }

                      // Display each package and its books
                      foreach ($item->packages as $package) {
                        $pkg_has_products = !empty($package['books']);
                        ?>
                        <tr>
                          <td colspan="5" style="background-color: #f0f0f0; font-weight: bold; padding: 10px;">
                            Package: <?= htmlspecialchars($package['package_name']) ?>
                            <?php if (!$pkg_has_products): ?>
                              (<?= $currency_code . ' ' . number_format($package['package_price'], 2) ?>)
                            <?php endif; ?>
                          </td>
                        </tr>
                        <?php

                        // Display each book in this package
                        foreach ($package['books'] as $book) {
                          // Get product image
                          $product_image = '';
                          if (!empty($book['product_id'])) {
                            $product_id = $book['product_id'];
                            $product_type = $book['product_type'];

                            $img_query = null;
                            if ($product_type == 'textbook' && $this->db->table_exists('erp_textbook_images')) {
                              $img_query = $this->db->select('image_path')
                                ->from('erp_textbook_images')
                                ->where('textbook_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                              if ($img_query->num_rows() == 0) {
                                $img_query = $this->db->select('image_path')
                                  ->from('erp_textbook_images')
                                  ->where('textbook_id', $product_id)
                                  ->order_by('image_order', 'ASC')
                                  ->limit(1)
                                  ->get();
                              }
                            } elseif ($product_type == 'notebook' && $this->db->table_exists('erp_notebook_images')) {
                              $img_query = $this->db->select('image_path')
                                ->from('erp_notebook_images')
                                ->where('notebook_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                              if ($img_query->num_rows() == 0) {
                                $img_query = $this->db->select('image_path')
                                  ->from('erp_notebook_images')
                                  ->where('notebook_id', $product_id)
                                  ->order_by('image_order', 'ASC')
                                  ->limit(1)
                                  ->get();
                              }
                            } elseif ($product_type == 'stationery' && $this->db->table_exists('erp_stationery_images')) {
                              $img_query = $this->db->select('image_path')
                                ->from('erp_stationery_images')
                                ->where('stationery_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                              if ($img_query->num_rows() == 0) {
                                $img_query = $this->db->select('image_path')
                                  ->from('erp_stationery_images')
                                  ->where('stationery_id', $product_id)
                                  ->order_by('image_order', 'ASC')
                                  ->limit(1)
                                  ->get();
                              }
                            }

                            if ($img_query && $img_query->num_rows() > 0) {
                              $product_image = $img_query->row()->image_path;
                            }
                          }
                          ?>
                          <tr>
                            <td>
                              <?php if (!empty($product_image)):
                                $stored_path = trim($product_image);
                                if (strpos($stored_path, 'http://') === 0 || strpos($stored_path, 'https://') === 0) {
                                  $img_url = $stored_path;
                                } else {
                                  $img_url = get_vendor_domain_url() . '/' . ltrim($stored_path, '/');
                                }
                              ?>
                                <img src="<?= $img_url ?>" alt="Product Image" class="product-image">
                              <?php else: ?>
                                <img src="<?= base_url('assets/images/no-image.png') ?>" alt="No Image" class="product-image">
                              <?php endif; ?>
                            </td>
                            <td>
                              <strong><?= htmlspecialchars($book['product_name']) ?></strong><br>
                              <?php if (!empty($book['isbn'])): ?>
                                <small class="text-muted">ISBN: <?= htmlspecialchars($book['isbn']) ?></small>
                              <?php endif; ?>
                            </td>
                            <td class="text-center"><?= !empty($book['sku']) ? htmlspecialchars($book['sku']) : '-' ?></td>
                            <td class="text-center"><?= (int)$book['quantity'] ?></td>
                            <td class="text-end">
                              <?= $currency_code . ' ' . number_format($book['unit_price'], 2) ?>
                              <?php if ($book['quantity'] > 1): ?>
                                <br><small class="text-muted">(<?= $currency_code . ' ' . number_format($book['total_price'], 2) ?> total)</small>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php
                        }
                      }

                      break; // Only process first bookset item
                    }
                  }

                  // Fallback to old bookset_products structure if new structure not found
                  if (!$bookset_found && !empty($bookset_products)) {
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

                          // Calculate package prices based on products or stored price
                          foreach ($packages as $package_id => &$package_data) {
                            $calculated_package_price = 0;
                            $has_products = !empty($package_data['products']);

                            if ($has_products) {
                              // Calculate total from products: sum of (quantity * unit_price or discounted_mrp)
                              foreach ($package_data['products'] as $product) {
                                $product_price = 0;
                                if (isset($product->unit_price) && $product->unit_price > 0) {
                                  $product_price = (float)$product->unit_price;
                                } elseif (isset($product->discounted_mrp) && $product->discounted_mrp > 0) {
                                  $product_price = (float)$product->discounted_mrp;
                                } elseif (isset($product->total_price) && $product->total_price > 0) {
                                  $product_price = (float)$product->total_price;
                                }

                                $quantity = isset($product->quantity) ? (int)$product->quantity : 1;
                                $calculated_package_price += $product_price * $quantity;
                              }
                            } else {
                              // No products, use the stored package price
                              $calculated_package_price = (float)$package_data['package_price'];
                            }

                            // Update the package price
                            $package_data['package_price'] = $calculated_package_price;
                          }
                          
                          // Display bookset SKU row first (from tbl_order_items)
                          $bookset_sku = '';
                          foreach ($items_arr as $item) {
                            if (isset($item->order_type) && $item->order_type == 'bookset' && !empty($item->product_sku)) {
                              $bookset_sku = $item->product_sku;
                              break;
                            }
                          }
                          if (!empty($bookset_sku)) {
                            ?>
                            <tr>
                              <td colspan="5" style="background-color: #e8f4f8; font-weight: bold; padding: 10px;">
                                Bookset SKU: <?= htmlspecialchars($bookset_sku) ?>
                              </td>
                            </tr>
                            <?php
                          }
                          
                          // Display each package and its products
                          foreach ($packages as $package_id => $package_data) {
                            $pkg_has_products = !empty($package_data['products']);
                            ?>
                            <tr>
                      <td colspan="5" style="background-color: #f0f0f0; font-weight: bold; padding: 10px;">
                                Package: <?= htmlspecialchars($package_data['package_name']) ?>
                                <?php if (!$pkg_has_products): ?>
                                  (<?= $currency_code . ' ' . number_format($package_data['package_price'], 2) ?>)
                                <?php endif; ?>
                              </td>
                            </tr>
                            <?php
                            foreach ($package_data['products'] as $bookset_product) {
                          // Get product image - fetch from legacy tables with fallback to first image when is_main not set
                              $product_image = '';
                              if (!empty($bookset_product->product_id)) {
                                $product_id = $bookset_product->product_id;
                                $product_type = $bookset_product->product_type;

                                $img_query = null;
                                if ($product_type == 'textbook' && $this->db->table_exists('erp_textbook_images')) {
                                  $img_query = $this->db->select('image_path')
                                    ->from('erp_textbook_images')
                                    ->where('textbook_id', $product_id)
                                    ->where('is_main', 1)
                                    ->limit(1)
                                    ->get();
                                  if ($img_query->num_rows() == 0) {
                                    $img_query = $this->db->select('image_path')
                                      ->from('erp_textbook_images')
                                      ->where('textbook_id', $product_id)
                                      ->order_by('image_order', 'ASC')
                                      ->limit(1)
                                      ->get();
                                  }
                                } elseif ($product_type == 'notebook' && $this->db->table_exists('erp_notebook_images')) {
                                  $img_query = $this->db->select('image_path')
                                    ->from('erp_notebook_images')
                                    ->where('notebook_id', $product_id)
                                    ->where('is_main', 1)
                                    ->limit(1)
                                    ->get();
                                  if ($img_query->num_rows() == 0) {
                                    $img_query = $this->db->select('image_path')
                                      ->from('erp_notebook_images')
                                      ->where('notebook_id', $product_id)
                                      ->order_by('image_order', 'ASC')
                                      ->limit(1)
                                      ->get();
                                  }
                                } elseif ($product_type == 'stationery' && $this->db->table_exists('erp_stationery_images')) {
                                  $img_query = $this->db->select('image_path')
                                    ->from('erp_stationery_images')
                                    ->where('stationery_id', $product_id)
                                    ->where('is_main', 1)
                                    ->limit(1)
                                    ->get();
                                  if ($img_query->num_rows() == 0) {
                                    $img_query = $this->db->select('image_path')
                                      ->from('erp_stationery_images')
                                      ->where('stationery_id', $product_id)
                                      ->order_by('image_order', 'ASC')
                                      ->limit(1)
                                      ->get();
                                  }
                                }

                                if ($img_query && $img_query->num_rows() > 0) {
                                  $product_image = $img_query->row()->image_path;
                                }
                              }
                            ?>
                            <tr>
                        <td>
                                <?php if (!empty($product_image)): 
                                  $stored_path = trim($product_image);
                                  if (strpos($stored_path, 'http://') === 0 || strpos($stored_path, 'https://') === 0) {
                                    $img_url = $stored_path;
                                  } else {
                                    $img_url = get_vendor_domain_url() . '/' . ltrim($stored_path, '/');
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
                        } // Close fallback if (!$bookset_found && !empty($bookset_products))
                  
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
                        <strong>Student Name:</strong> <?= trim(htmlspecialchars((isset($bookset_info->f_name) ? $bookset_info->f_name : '') . ' ' . (isset($bookset_info->m_name) ? $bookset_info->m_name : '') . ' ' . (isset($bookset_info->s_name) ? $bookset_info->s_name : ''))) ?><br>
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
                } // Close main bookset if condition
                else {
                  // Display regular order items
                  foreach ($items_arr as $key => $val) {
                          // Get product image - for individual products, use product_image set in controller
                          $product_image = '';
                          if ($val->order_type == 'individual' && isset($val->product_image) && !empty($val->product_image)) {
                            // Use thumbnail_img from tbl_order_items for individual products
                            $product_image = $val->product_image;
                          } elseif (isset($val->product_id) && !empty($val->product_id)) {
                            $product_id = $val->product_id;

                            // Fallback: Determine product type from order_type or check tables
                            $product_type = '';
                            if (isset($val->order_type)) {
                              if ($val->order_type == 'uniform') {
                                $product_type = 'uniform';
                              } elseif ($val->order_type == 'textbook' || $val->order_type == 'notebook' || $val->order_type == 'stationery') {
                                $product_type = $val->order_type;
                              }
                            }

                            // If we couldn't determine from order_type, try to detect from tables
                            if (empty($product_type)) {
                              // Check if it's a uniform
                              if ($this->db->table_exists('erp_uniforms')) {
                                $uniform_check = $this->db->select('id')->from('erp_uniforms')->where('id', $product_id)->limit(1)->get();
                                if ($uniform_check->num_rows() > 0) {
                                  $product_type = 'uniform';
                                }
                              }

                              // Check if it's a textbook
                              if (empty($product_type) && $this->db->table_exists('erp_textbooks')) {
                                $textbook_check = $this->db->select('id')->from('erp_textbooks')->where('id', $product_id)->limit(1)->get();
                                if ($textbook_check->num_rows() > 0) {
                                  $product_type = 'textbook';
                                }
                              }

                              // Check if it's a notebook
                              if (empty($product_type) && $this->db->table_exists('erp_notebooks')) {
                                $notebook_check = $this->db->select('id')->from('erp_notebooks')->where('id', $product_id)->limit(1)->get();
                                if ($notebook_check->num_rows() > 0) {
                                  $product_type = 'notebook';
                                }
                              }

                              // Check if it's stationery
                              if (empty($product_type) && $this->db->table_exists('erp_stationery')) {
                                $stationery_check = $this->db->select('id')->from('erp_stationery')->where('id', $product_id)->limit(1)->get();
                                if ($stationery_check->num_rows() > 0) {
                                  $product_type = 'stationery';
                                }
                              }
                            }

                            // Fetch image based on product type (fallback only)
                            if ($product_type == 'uniform') {
                              $img_query = $this->db->select('image_path')
                                ->from('erp_uniform_images')
                                ->where('uniform_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                            } elseif ($product_type == 'textbook') {
                              $img_query = $this->db->select('image_path')
                                ->from('erp_textbook_images')
                                ->where('textbook_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                            } elseif ($product_type == 'notebook') {
                              $img_query = $this->db->select('image_path')
                                ->from('erp_notebook_images')
                                ->where('notebook_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                            } elseif ($product_type == 'stationery') {
                              if ($this->db->table_exists('erp_stationery_images')) {
                                $img_query = $this->db->select('image_path')
                                  ->from('erp_stationery_images')
                                  ->where('stationery_id', $product_id)
                                  ->where('is_main', 1)
                                  ->limit(1)
                                  ->get();
                              } else {
                                $img_query = null;
                              }
                            } else {
                              // For individual products, check the main products table
                              $img_query = $this->db->select('image_path')
                                ->from('product_images')
                                ->where('product_id', $product_id)
                                ->where('is_main', 1)
                                ->limit(1)
                                ->get();
                            }

                            if (isset($img_query) && $img_query && $img_query->num_rows() > 0) {
                              $product_image = $img_query->row()->image_path;
                            }
                          }
                        ?>
                        <tr>
                      <td>
                            <?php if (!empty($product_image)): 
                              $stored_path = trim($product_image);
                              if (strpos($stored_path, 'http://') === 0 || strpos($stored_path, 'https://') === 0) {
                                $img_url = $stored_path;
                              } else {
                                $img_url = get_vendor_domain_url() . '/' . ltrim($stored_path, '/');
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
          $erp_courier_id = isset($order_data[0]->erp_courier_id) ? (int)$order_data[0]->erp_courier_id : 0;
          $awb_no = isset($order_data[0]->awb_no) ? trim($order_data[0]->awb_no) : '';
          $has_courier_selected = ($courier == 'manual' && $erp_courier_id > 0);
          ?>
          
          <!-- Status 1: Pending - Show Move to Process button -->

          
          <!-- Status 2: Processing - Show Shipper Selection or Generate Label -->
          <?php if ($current_status == '1' || $current_status == 1): ?>
            <?php if (empty($courier)): ?>
              <!-- Shipper Selection -->
              <div class="mb-3">
                <p class="text-muted mb-3 text-center"><strong>Select Shipping Method</strong><br/> 
				<small class="text-muted mb-3 text-center">Assign a shipper to deliver your order</small></p>
              
                <div class="row" style="margin: 0;">
                  <div class="col-5" style="padding: 5px;">
                    <button type="button" class="btn btn-outline-primary btn-lg w-100" onclick="selectShipper('<?= $order_data[0]->id ?>', 'manual', this)" style="width: 100%;">
                      <i class="fa fa-truck"></i> Self Delivery
                    </button>
  </div>
                  <div class="col-2 text-center" style="padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-muted" style="font-weight: bold;">OR</span>
</div>
                  <div class="col-5" style="padding: 5px;">
                    <button type="button" class="btn btn-outline-info btn-lg w-100" data-bs-toggle="modal" data-bs-target="#thirdPartyShippingModal" style="width: 100%;">
                      <i class="fa fa-shipping-fast"></i> 3rd Party
                    </button>
                  </div>
                </div>
              </div>
            <?php elseif ($courier == 'manual'): ?>
              <!-- Self Delivery - Flow: 1) Generate Label, 2) Select Courier, 3) Ready to Ship -->
              <div class="mb-3">
                <div class="alert alert-light border d-flex align-items-center mb-3">
                  <i class="fa fa-truck text-primary me-2"></i>
                  <span><strong>Self Delivery</strong> Selected</span>
                </div>
              </div>
              <?php if (!$has_shipping_label): ?>
                <!-- Step 1: Generate shipping label first -->
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
              <?php elseif (!$has_courier_selected): ?>
                <!-- Step 2: After label generated, select courier -->
                <div class="alert alert-light border mb-2 small">
                  <i class="fa fa-check-circle text-success me-1"></i> Shipping label generated
                </div>
                <div class="row" style="margin: 0;">
                  <div class="col-6" style="padding: 5px;">
                    <a href="<?php echo base_url('orders/download_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-info btn-lg w-100" target="_blank" style="width: 100%;">
                      <i class="fa fa-download me-2"></i> Download Label
                    </a>
                  </div>
                  <div class="col-6" style="padding: 5px;">
                    <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#selectCourierModal" style="width: 100%;">
                      <i class="fa fa-truck me-2"></i> Select Courier
                    </button>
                  </div>
                </div>
              <?php else: ?>
                <!-- Step 3: Label + Courier selected - Ready to Ship or Out for Delivery -->
                <div class="alert alert-light border mb-2 small">
                  <strong>Courier:</strong> <?= htmlspecialchars(isset($courier_info['courier_name']) ? $courier_info['courier_name'] : '-') ?>
                  <?php if (!empty($awb_no)): ?><br><strong>AWB:</strong> <code><?= htmlspecialchars($awb_no) ?></code><?php endif; ?>
                  <a href="#" class="ms-2" data-bs-toggle="modal" data-bs-target="#selectCourierModal">Edit</a>
                </div>
                <div class="row" style="margin: 0;">
                  <div class="col-6" style="padding: 5px;">
                    <a href="<?php echo base_url('orders/download_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-info btn-lg w-100" target="_blank" style="width: 100%;">
                      <i class="fa fa-download"></i> Download
                    </a>
                  </div>
                  <div class="col-6" style="padding: 5px;">
                    <?php
                    $is_ready_to_ship = isset($order_data[0]->ready_to_ship) && $order_data[0]->ready_to_ship == 1;
                    if (!$is_ready_to_ship): ?>
                      <button type="button" class="btn btn-warning btn-lg w-100" onclick="markReadyToShip('<?= $order_data[0]->order_unique_id ?>', this)" style="width: 100%;">
                        <i class="fa fa-check-square-o"></i> Ready to Ship
                      </button>
                    <?php else: ?>
                      <button type="button" class="btn btn-success btn-lg w-100" onclick="moveToOutForDelivery('<?= $order_data[0]->order_unique_id ?>', this)" style="width: 100%;">
                        <i class="fa fa-truck"></i> Out for Delivery
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
                <?php if ($is_ready_to_ship): ?>
                <div class="row" style="margin: 0; margin-top: 10px;">
                  <div class="col-12" style="padding: 5px;">
                    <button type="button" class="btn btn-outline-warning btn-lg w-100" onclick="unmarkReadyToShip('<?= $order_data[0]->order_unique_id ?>', this)" style="width: 100%;">
                      <i class="fa fa-undo"></i> Unmark Ready
                    </button>
                  </div>
                </div>
                <?php endif; ?>
              <?php endif; ?>
            <?php elseif ($courier == '3rd_party' || $courier == 'shiprocket'): ?>
              <!-- 3rd Party - Show provider info -->
              <?php 
              $third_party_provider = isset($order_data[0]->third_party_provider) ? $order_data[0]->third_party_provider : 'shiprocket';
              $provider_label = ucfirst($third_party_provider);
              if ($provider_label == 'Bigship') $provider_label = 'Big Ship';
              ?>
              <div class="mb-3">
                <div class="alert alert-info border-0 mb-3">
                  <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-shipping-fast me-2"></i>
                    <strong>3rd Party Shipping</strong>
                  </div>
                  <small class="text-muted"><?= htmlspecialchars($provider_label) ?></small>
                  <?php if (!empty($order_data[0]->pkg_length_cm) || !empty($order_data[0]->pkg_weight_kg)): ?>
                    <div class="mt-2 small">
                      <strong>Dimensions:</strong> 
                      L: <?= htmlspecialchars($order_data[0]->pkg_length_cm ?? '-') ?> cm × 
                      B: <?= htmlspecialchars($order_data[0]->pkg_breadth_cm ?? '-') ?> cm × 
                      H: <?= htmlspecialchars($order_data[0]->pkg_height_cm ?? '-') ?> cm, 
                      W: <?= htmlspecialchars($order_data[0]->pkg_weight_kg ?? '-') ?> kg
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($order_data[0]->awb_no)): ?>
                    <div class="mt-2">
                      <strong>AWB:</strong> <code><?= htmlspecialchars($order_data[0]->awb_no) ?></code>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ($has_shipping_label): ?>
                <?php
                $is_ready_to_ship = isset($order_data[0]->ready_to_ship) && $order_data[0]->ready_to_ship == 1;
                ?>
                <div class="row" style="margin: 0;">
                  <div class="col-6" style="padding: 5px;">
                    <?php if (!$is_ready_to_ship): ?>
                      <button type="button" class="btn btn-warning btn-lg w-100" onclick="markReadyToShip('<?= $order_data[0]->order_unique_id ?>', this)" style="width: 100%;">
                        <i class="fa fa-check-square-o"></i> Ready to Ship
                      </button>
                    <?php else: ?>
                      <button type="button" class="btn btn-success btn-lg w-100" onclick="moveToOutForDelivery('<?= $order_data[0]->order_unique_id ?>', this)" style="width: 100%;">
                        <i class="fa fa-truck"></i> Out for Delivery
                      </button>
                    <?php endif; ?>
                  </div>
                  <div class="col-6" style="padding: 5px;">
                    <?php if ($is_ready_to_ship): ?>
                      <button type="button" class="btn btn-outline-warning btn-lg w-100" onclick="unmarkReadyToShip('<?= $order_data[0]->order_unique_id ?>', this)" style="width: 100%;">
                        <i class="fa fa-undo"></i> Unmark Ready
                      </button>
                    <?php else: ?>
                      <a href="<?php echo base_url('orders/download_shipping_label/' . $order_data[0]->order_unique_id); ?>" class="btn btn-outline-info btn-lg w-100" target="_blank" style="width: 100%;">
                        <i class="fa fa-download"></i> Download
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          <?php endif; ?>
          
          <!-- Status 3: Out for Delivery - Show Delivered button + Move back to Processing -->
          <?php if ($current_status == '3' || $current_status == 3): ?>
            <?php if ($has_shipping_label): ?>
              <div class="row" style="margin: 0;">
                <div class="col-6" style="padding: 5px;">
                  <button type="button" class="btn btn-success btn-lg w-100" onclick="moveToDelivered('<?= $order_data[0]->order_unique_id ?>', this)" style="width: 100%;">
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
                <button type="button" class="btn btn-success btn-lg" onclick="moveToDelivered('<?= $order_data[0]->order_unique_id ?>', this)">
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
      
      <?php 
      // Courier Information - show for self delivery when shipping label generated
      $show_courier_block = ($courier == 'manual' && $has_shipping_label && $erp_courier_id > 0);
      $courier_name_display = isset($courier_info['courier_name']) ? $courier_info['courier_name'] : '-';
      ?>
      <?php if ($show_courier_block): ?>
      <?php $shipping_no = isset($order_data[0]->ship_order_id) ? trim($order_data[0]->ship_order_id) : ''; ?>
      <!-- COURIER INFORMATION CARD (Self Delivery) - Compact -->
      <div class="card mb-3">
        <div class="card-header py-2">
          <b class="small"><i class="fa fa-truck me-1"></i>Courier</b>
        </div>
        <div class="card-body py-2 small">
          <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-primary fw-bold"><?= htmlspecialchars($courier_name_display) ?></span>
            <?php if (!empty($shipping_no)): ?>
            <span>·</span>
            <span><strong>Shipping #:</strong> <code class="px-1"><?= htmlspecialchars($shipping_no) ?></code></span>
            <?php endif; ?>
            <?php if (!empty($awb_no)): ?>
            <span>·</span>
            <span><strong>AWB:</strong> <code class="px-1"><?= htmlspecialchars($awb_no) ?></code></span>
            <?php endif; ?>
          </div>
          <?php if (!empty($order_data[0]->track_url)): 
            $track_url_display = $order_data[0]->track_url;
            $track_url_href = $track_url_display;
            if (!empty($awb_no)) {
              $track_url_href = str_replace(array('{{tracking_id}}', '{tracking_id}', '{{awb}}', '{awb}'), $awb_no, $track_url_display);
            }
          ?>
          <div class="mt-1">
            <a href="<?= htmlspecialchars($track_url_href) ?>" target="_blank" rel="noopener" class="text-break" style="font-size: 0.8rem;">
              <?= htmlspecialchars(strlen($track_url_display) > 45 ? substr($track_url_display, 0, 45) . '…' : $track_url_display) ?>
            </a>
          </div>
          <?php endif; ?>
          <?php if (!empty($order_data[0]->track_date)): ?>
          <div class="mt-1 text-muted" style="font-size: 0.75rem;">
            <i class="fa fa-clock-o me-1"></i><?= date('d M Y, h:i A', strtotime($order_data[0]->track_date)) ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- PACKAGE WEIGHT & BOOKSET INFO CARD -->
      <div class="card mb-3">
        <div class="card-header">
          <b>Package Information</b>
        </div>
        <div class="card-body">
          <?php 
          // Display total package weight
          $total_weight_gm = isset($order_data[0]->total_weight_gm) ? (float)$order_data[0]->total_weight_gm : 0;
          if ($total_weight_gm > 0) {
            $total_weight_kg = $total_weight_gm / 1000;
            ?>
            <div class="mb-3">
              <div class="d-flex align-items-center">
                <i class="fa fa-weight me-2 text-primary"></i>
                <div>
                  <strong>Total Package Weight:</strong><br>
                  <span class="text-muted">
                    <?= number_format($total_weight_gm, 2) ?> gm 
                    (<?= number_format($total_weight_kg, 2) ?> kg)
                  </span>
                </div>
              </div>
            </div>
            <?php
          } else {
            ?>
            <div class="mb-3">
              <div class="d-flex align-items-center">
                <i class="fa fa-weight me-2 text-muted"></i>
                <div>
                  <strong>Total Package Weight:</strong><br>
                  <span class="text-muted">Not available</span>
                </div>
              </div>
            </div>
            <?php
          }
          
          // Display bookset information if order is bookset
          if (isset($order_type) && $order_type == 'bookset' && !empty($bookset_info)) {
            ?>
            <hr class="my-3">
            <div>
              <strong class="mb-2 d-block">Bookset Details:</strong>
              <?php if (!empty($bookset_info->school_name)): ?>
                <div class="mb-2">
                  <i class="fa fa-school me-2 text-info"></i>
                  <strong>School:</strong> <?= htmlspecialchars($bookset_info->school_name) ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($bookset_info->board_name)): ?>
                <div class="mb-2">
                  <i class="fa fa-book me-2 text-success"></i>
                  <strong>Board:</strong> <?= htmlspecialchars($bookset_info->board_name) ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($bookset_info->grade_name)): ?>
                <div class="mb-2">
                  <i class="fa fa-graduation-cap me-2 text-warning"></i>
                  <strong>Grade:</strong> <?= htmlspecialchars($bookset_info->grade_name) ?>
                </div>
              <?php endif; ?>
            </div>
            <?php
          }

          // Display uniform order school/branch information (like bookset)
          if (isset($order_type) && $order_type == 'uniform' && !empty($uniform_info) && (!empty($uniform_info->school_name) || !empty($uniform_info->branch_name))) {
            ?>
            <hr class="my-3">
            <div>
              <strong class="mb-2 d-block">Uniform Order Details:</strong>
              <?php if (!empty($uniform_info->school_name)): ?>
                <div class="mb-2">
                  <i class="fa fa-school me-2 text-info"></i>
                  <strong>School:</strong> <?= htmlspecialchars($uniform_info->school_name) ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($uniform_info->branch_name)): ?>
                <div class="mb-2">
                  <i class="fa fa-building me-2 text-primary"></i>
                  <strong>Branch:</strong> <?= htmlspecialchars($uniform_info->branch_name) ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($uniform_info->address)): ?>
                <div class="mb-2 text-muted small">
                  <i class="fa fa-map-marker-alt me-2"></i>
                  <?= htmlspecialchars($uniform_info->address) ?>
                </div>
              <?php endif; ?>
            </div>
            <?php
          }

          // Deliver at School: School/Branch with address
          if (isset($is_deliver_at_school) && $is_deliver_at_school && ((!empty($uniform_info) && !empty($uniform_info->display_name)) || !empty($address_arr[0]->address))) {
            ?>
            <hr class="my-3">
            <div>
              <strong class="mb-2 d-block">Delivery (School/Branch):</strong>
              <div class="mb-2">
                <i class="fa fa-school me-2 text-danger"></i>
                <strong><?= htmlspecialchars((!empty($uniform_info) && !empty($uniform_info->display_name)) ? $uniform_info->display_name : $address_arr[0]->address) ?></strong>
              </div>
              <?php if (!empty($uniform_info) && !empty($uniform_info->address)): ?>
              <div class="mb-2 text-muted small">
                <i class="fa fa-map-marker-alt me-2"></i>
                <?= htmlspecialchars($uniform_info->address) ?>
              </div>
              <?php endif; ?>
            </div>
            <?php
          }

          // Student details for all deliver at school
          if (isset($is_deliver_at_school) && $is_deliver_at_school) {
            ?>
            <hr class="my-3">
            <div>
              <strong class="mb-2 d-block">Student Details (Deliver at School):</strong>
              <?php if (!empty($uniform_student_details)): ?>
                <?php foreach ($uniform_student_details as $stu): ?>
                <div class="mb-3 p-2 rounded" style="background: #fff3cd; border: 1px solid #ffc107;">
                  <?php if (!empty($stu->f_name)): ?><div><strong>Name:</strong> <?= htmlspecialchars($stu->f_name) ?></div><?php endif; ?>
                  <?php if (!empty($stu->grade)): ?><div><strong>Grade:</strong> <?= htmlspecialchars($stu->grade) ?></div><?php endif; ?>
                  <?php if (!empty($stu->roll_number)): ?><div><strong>Roll Number:</strong> <?= htmlspecialchars($stu->roll_number) ?></div><?php endif; ?>
                  <?php if (!empty($stu->remarks)): ?><div><strong>Remarks:</strong> <?= htmlspecialchars($stu->remarks) ?></div><?php endif; ?>
                </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="text-muted small">No student details</div>
              <?php endif; ?>
            </div>
            <?php
          }
          ?>
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
            $use_school_address = (isset($is_deliver_at_school) && $is_deliver_at_school && !empty($uniform_info) && !empty($uniform_info->display_name));
          ?>
            <div><b><?= htmlspecialchars($addr->name) ?></b></div>
            <div class="text-muted"><?= htmlspecialchars($addr->mobile_no) ?></div>
            <div class="mt-2">
              <?php 
              if ($use_school_address) { 
                echo htmlspecialchars($uniform_info->display_name);
                if (!empty($uniform_info->address)) {
                  echo '<br><span class="text-muted">' . htmlspecialchars($uniform_info->address) . '</span>';
                }
              } else {
                $address_parts = array();
                if (!empty($addr->address)) $address_parts[] = htmlspecialchars($addr->address);
                if (!empty($addr->city)) $address_parts[] = htmlspecialchars($addr->city);
                if (!empty($addr->state)) $address_parts[] = htmlspecialchars($addr->state);
                if (!empty($addr->pincode)) $address_parts[] = htmlspecialchars($addr->pincode);
                if (!empty($addr->country)) $address_parts[] = htmlspecialchars($addr->country);
                echo implode(', ', $address_parts);
              }
              ?>
            </div>
            <?php if(!$use_school_address && !empty($addr->landmark)): ?>
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
          <?php if (isset($is_payment_at_school) && $is_payment_at_school): ?>
          <div><span class="badge badge-payment-school"><?= htmlspecialchars($payment_method_display) ?></span></div>
          <?php else: ?>
          <div><b><?= htmlspecialchars($payment_method_display) ?></b></div>
          <?php endif; ?>
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

      
    </div>
    
  </div>
</div>

<!-- Order Timeline Modal -->
<div class="modal fade" id="orderTimelineModal" tabindex="-1" aria-labelledby="orderTimelineModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderTimelineModalLabel">Order Timeline</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <?php
          // Helper to map tbl_order_status.status_title to display name
          $status_title_map = array(
            '1' => 'New Order / Pending',
            '2' => 'Processing',
            '3' => 'Out for Delivery',
            '4' => 'Delivered',
            '7' => 'Return'
          );
          
          // Build timeline from tbl_order_status (all data where order_id = tbl_order_details.id)
          $timeline_items = array();
          
          // 1. Order Placed - from tbl_order_details.order_date (initial state)
          $timeline_items[] = array(
            'status' => 'Order Placed',
            'date' => $order_data[0]->order_date,
            'completed' => true,
            'notes' => ''
          );
          
          // 2. Add ALL entries from tbl_order_status (order_id = tbl_order_details.id)
          if (!empty($additional_status)) {
            foreach ($additional_status as $status) {
              $display_status = isset($status_title_map[$status->status_title]) 
                ? $status_title_map[$status->status_title] 
                : $status->status_title;
              $timeline_items[] = array(
                'status' => $display_status,
                'date' => $status->created_at,
                'completed' => true,
                'notes' => isset($status->status_desc) ? $status->status_desc : ''
              );
            }
          }
          
          // 3. Add tbl_order_details entries that may not have tbl_order_status records
          // Courier Assigned - track_date (when erp_courier_id set)
          if (!empty($order_data[0]->track_date) && !empty($order_data[0]->erp_courier_id)) {
            $has_courier = false;
            foreach ($timeline_items as $item) {
              if (stripos($item['status'], 'Courier') !== false || stripos($item['status'], 'Shipper') !== false) {
                $has_courier = true;
                break;
              }
            }
            if (!$has_courier) {
              $timeline_items[] = array(
                'status' => 'Courier Assigned',
                'date' => $order_data[0]->track_date,
                'completed' => true,
                'notes' => 'Courier and tracking details added'
              );
            }
          }
          // Ready to Ship - ready_to_ship_time
          if (!empty($order_data[0]->ready_to_ship_time)) {
            $has_ready = false;
            foreach ($timeline_items as $item) {
              if (stripos($item['status'], 'Ready to Ship') !== false) {
                $has_ready = true;
                break;
              }
            }
            if (!$has_ready) {
              $timeline_items[] = array(
                'status' => 'Ready to Ship',
                'date' => $order_data[0]->ready_to_ship_time,
                'completed' => true,
                'notes' => 'Order marked as ready to ship'
              );
            }
          }
          // Shipping Label - fallback if no tbl_order_status entry
          if (!empty($order_data[0]->shipping_label)) {
            $has_label = false;
            foreach ($timeline_items as $item) {
              if (stripos($item['status'], 'Label') !== false || stripos($item['status'], 'label') !== false) {
                $has_label = true;
                break;
              }
            }
            if (!$has_label) {
              $timeline_items[] = array(
                'status' => 'Shipping Label Generated',
                'date' => !empty($order_data[0]->processing_date) ? $order_data[0]->processing_date : $order_data[0]->order_date,
                'completed' => true,
                'notes' => 'Shipping label has been generated'
              );
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
<!-- 3rd Party Shipping Modal -->
<div class="modal fade" id="thirdPartyShippingModal" tabindex="-1" aria-labelledby="thirdPartyShippingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="thirdPartyShippingModalLabel">
          3rd Party Shipping
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
          <label class="form-label fw-bold">Package Dimensions</label>
          <div class="row g-2">
            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Length (cm)</label>
              <input type="number" id="pkgLength" class="form-control" min="0" step="0.01" placeholder="0">
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Breadth (cm)</label>
              <input type="number" id="pkgBreadth" class="form-control" min="0" step="0.01" placeholder="0">
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Height (cm)</label>
              <input type="number" id="pkgHeight" class="form-control" min="0" step="0.01" placeholder="0">
            </div>

            <div class="col-6 col-md-3">
              <label class="form-label small text-muted">Weight (kg)</label>
              <input type="number" id="pkgWeight" class="form-control" min="0" step="0.01" placeholder="0">
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

     
		
		<button type="button" class="btn btn-primary" id="saveThirdPartyBtn" disabled onclick="saveThirdPartyShipping()">
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
<!-- Select Courier & AWB Modal (Self Delivery) -->
<div class="modal fade" id="selectCourierModal" tabindex="-1" aria-labelledby="selectCourierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="selectCourierModalLabel">Select Courier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="courierStep1" class="courier-step">
          <p class="text-muted mb-3">Select the courier for this order:</p>
          <div id="courierListContainer" class="list-group">
            <div id="courierListLoading" class="text-center py-4">
              <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
              <p class="mt-2 text-muted">Loading couriers...</p>
            </div>
            <div id="courierListEmpty" class="alert alert-warning" style="display: none;">No couriers found. Please add couriers in <a href="<?php echo base_url('couriers'); ?>">Couriers</a> first.</div>
          </div>
        </div>
        <div id="courierStep2" class="courier-step mt-3" style="display: none;">
          <hr>
          <p class="text-muted mb-2">AWB / Tracking Number <small class="text-muted">(optional - leave blank to skip)</small>:</p>
          <input type="text" id="awbNumberInput" class="form-control form-control-lg" placeholder="e.g. 123456789012" maxlength="50">
          <input type="hidden" id="selectedCourierId" value="">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveCourierAwbBtn" onclick="saveCourierAndAwb()" disabled>
          <i class="fa fa-save me-1"></i> Save & Continue
        </button>
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
/*
function moveToProcessing(orderUniqueId, btnElement) {
  // Disable button and show loading state
  var $btn = $(btnElement);
  var originalText = $btn.html();
  $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

  $.ajax({
    url: '<?php echo base_url("orders/move_to_processing_single"); ?>',
    type: 'POST',
    data: {
      order_unique_id: orderUniqueId
    },
    dataType: 'json',
    success: function(response) {
      if (response.status == '200') {
        location.reload();
      } else {
        alert(response.message || 'Error updating order status');
        $btn.prop('disabled', false).html(originalText);
      }
    },
    error: function() {
      alert('Error updating order status. Please try again.');
      $btn.prop('disabled', false).html(originalText);
    }
  });
}*/

function selectShipper(orderId, courierType, btnElement) {

  Swal.fire({
    title: 'Are you sure?',
    text: "You want to assign this courier?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, assign it!'
  }).then((result) => {

    if (result.isConfirmed) {

      var $btn = $(btnElement);
      var originalText = $btn.html();
      $btn.prop('disabled', true)
          .html('<i class="fa fa-spinner fa-spin"></i> Processing...');

      $.ajax({
        url: '<?php echo base_url("orders/bulk_set_shipper"); ?>',
        type: 'POST',
        data: {
          order_ids: [orderId],
          courier: courierType,
          <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: 'json',
        success: function(response) {
          if (response.status == '200') {

            Swal.fire({
              icon: 'success',
              title: 'Assigned!',
              text: 'Courier assigned successfully.',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload();
            });

          } else {
            Swal.fire('Error', response.message || 'Error setting shipper', 'error');
            $btn.prop('disabled', false).html(originalText);
          }
        },
        error: function(xhr) {
          let msg = 'Error setting shipper. Please try again.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
          }

          Swal.fire('Error', msg, 'error');
          $btn.prop('disabled', false).html(originalText);
        }
      });

    }

  });
}

function moveToOutForDelivery(orderUniqueId, btnElement) {
  // Disable button and show loading state
  var $btn = $(btnElement);
  var originalText = $btn.html();
  $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

  $.ajax({
    url: '<?php echo base_url("orders/move_to_out_for_delivery_single"); ?>',
    type: 'POST',
    data: {
      order_unique_id: orderUniqueId
    },
    dataType: 'json',
    success: function(response) {
      if (response.status == '200') {
        location.reload();
      } else {
        alert(response.message || 'Error updating order status');
        $btn.prop('disabled', false).html(originalText);
      }
    },
    error: function() {
      alert('Error updating order status. Please try again.');
      $btn.prop('disabled', false).html(originalText);
    }
  });
}

function moveToDelivered(orderUniqueId, btnElement) {
  // Disable button and show loading state
  var $btn = $(btnElement);
  var originalText = $btn.html();
  $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

  $.ajax({
    url: '<?php echo base_url("orders/move_to_delivered_single"); ?>',
    type: 'POST',
    data: {
      order_unique_id: orderUniqueId
    },
    dataType: 'json',
    success: function(response) {
      if (response.status == '200') {
        location.reload();
      } else {
        alert(response.message || 'Error updating order status');
        $btn.prop('disabled', false).html(originalText);
      }
    },
    error: function() {
      alert('Error updating order status. Please try again.');
      $btn.prop('disabled', false).html(originalText);
    }
  });
}

// 3rd Party Shipping Modal
var orderIdForThirdParty = '<?= $order_data[0]->id ?>';
/*
$('#thirdPartyShippingModal').on('show.bs.modal', function() {
  $('#thirdPartyProvider').val('');
  $('#thirdPartyShippingModal .third-party-option').removeClass('active');
  $('#pkgLength, #pkgBreadth, #pkgHeight, #pkgWeight').val('');
  $('#saveThirdPartyBtn').prop('disabled', true);
  $('#vendorAddressDisplay').html('<span class="text-muted">Loading...</span>');
  $.get('<?php echo base_url("orders/get_vendor_address"); ?>', function(data) {
    if (data.success && data.address_full) {
      $('#vendorAddressDisplay').html('<span>' + (data.address_full || 'Please add address in Profile.') + '</span>');
    } else {
      $('#vendorAddressDisplay').html('<span class="text-warning">Please add address in Profile.</span>');
    }
  }).fail(function() {
    $('#vendorAddressDisplay').html('<span class="text-warning">Could not load address.</span>');
  });
});
*/

$('#thirdPartyShippingModal .third-party-option').on('click', function() {
  var provider = $(this).data('provider');
  $('#thirdPartyProvider').val(provider);
  $('#thirdPartyShippingModal .third-party-option').removeClass('active');
  $(this).addClass('active');
  $('#saveThirdPartyBtn').prop('disabled', false);
});


// Select Courier Modal - load couriers when modal opens
var orderUniqueIdForCourier = '<?= $order_data[0]->order_unique_id ?>';
var existingCourierId = '<?= isset($order_data[0]->erp_courier_id) && $order_data[0]->erp_courier_id ? (int)$order_data[0]->erp_courier_id : 0 ?>';
var existingAwbNo = '<?= isset($order_data[0]->awb_no) ? addslashes(trim($order_data[0]->awb_no)) : "" ?>';
$('#selectCourierModal').on('show.bs.modal', function() {
  $('#courierListLoading').show();
  $('#courierListEmpty').hide();
  $('#courierStep2').hide();
  $('#selectedCourierId').val('');
  $('#awbNumberInput').val(existingAwbNo);
  $('#saveCourierAwbBtn').prop('disabled', true);
  $('#courierListContainer .list-group-item-action').remove();
  
  $.get('<?php echo base_url("orders/get_order_couriers"); ?>', function(data) {
    $('#courierListLoading').hide();
    if (data.success && data.couriers && data.couriers.length > 0) {
      data.couriers.forEach(function(c) {
        var item = $('<a href="#" class="list-group-item list-group-item-action courier-item" data-id="' + c.id + '" data-name="' + (c.courier_name || '').replace(/"/g, '&quot;') + '">' + (c.courier_name || 'Courier #' + c.id) + '</a>');
        $('#courierListContainer').append(item);
      });
      if (existingCourierId > 0) {
        $('#selectedCourierId').val(existingCourierId);
        $('#courierListContainer .courier-item[data-id="' + existingCourierId + '"]').addClass('active');
        $('#courierStep2').show();
        $('#saveCourierAwbBtn').prop('disabled', false);
      }
      $('#courierListContainer .courier-item').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#selectedCourierId').val(id);
        $('#courierListContainer .courier-item').removeClass('active');
        $(this).addClass('active');
        $('#courierStep2').show();
        $('#awbNumberInput').focus();
        $('#saveCourierAwbBtn').prop('disabled', false);
      });
    } else {
      $('#courierListEmpty').show();
    }
  }).fail(function() {
    $('#courierListLoading').hide();
    $('#courierListEmpty').text('Failed to load couriers.').show();
  });
});

function saveCourierAndAwb() {
  var courierId = $('#selectedCourierId').val();
  var awbNo = $('#awbNumberInput').val().trim();
  if (!courierId || courierId == '0') {
    alert('Please select a courier.');
    return;
  }
  var $btn = $('#saveCourierAwbBtn');
  $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');
  
  $.ajax({
    url: '<?php echo base_url("orders/save_order_courier_awb"); ?>',
    type: 'POST',
    data: {
      order_unique_id: orderUniqueIdForCourier,
      erp_courier_id: courierId,
      awb_no: awbNo,
      <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
    },
    dataType: 'json',
    success: function(response) {
      if (response.status == '200') {
        $('#selectCourierModal').modal('hide');
        location.reload();
      } else {
        alert(response.message || 'Failed to save.');
        $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save & Continue');
      }
    },
    error: function() {
      alert('Error saving. Please try again.');
      $btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save & Continue');
    }
  });
}

function markReadyToShip(orderUniqueId, btnElement) {
  // Disable button and show loading state
  var $btn = $(btnElement);
  var originalText = $btn.html();
  $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

  $.ajax({
    url: '<?php echo base_url("orders/mark_ready_to_ship"); ?>',
    type: 'POST',
    data: {
      order_unique_id: orderUniqueId
    },
    dataType: 'json',
    success: function(response) {
      if (response.status == '200') {
        location.reload();
      } else {
        alert(response.message || 'Error marking order ready to ship');
        $btn.prop('disabled', false).html(originalText);
      }
    },
    error: function() {
      alert('Error marking order ready to ship. Please try again.');
      $btn.prop('disabled', false).html(originalText);
    }
  });
}

function unmarkReadyToShip(orderUniqueId, btnElement) {
  // Disable button and show loading state
  var $btn = $(btnElement);
  var originalText = $btn.html();
  $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

  $.ajax({
    url: '<?php echo base_url("orders/unmark_ready_to_ship"); ?>',
    type: 'POST',
    data: {
      order_unique_id: orderUniqueId
    },
    dataType: 'json',
    success: function(response) {
      if (response.status == '200') {
        location.reload();
      } else {
        alert(response.message || 'Error unmarking order ready to ship');
        $btn.prop('disabled', false).html(originalText);
      }
    },
    error: function() {
      alert('Error unmarking order ready to ship. Please try again.');
      $btn.prop('disabled', false).html(originalText);
    }
  });
}

function moveBackToProcessing(orderUniqueId, btnElement) {
  Swal.fire({
    title: 'Move back to Processing?',
    text: 'This will move the order back to Processing and reset all shipping details (label, courier, AWB, tracking). Continue?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, move back'
  }).then(function(result) {
    if (!result.isConfirmed) return;
    var $btn = $(btnElement);
    var originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    $.ajax({
      url: '<?php echo base_url("orders/move_back_to_processing_single"); ?>',
      type: 'POST',
      data: { order_unique_id: orderUniqueId },
      dataType: 'json',
      success: function(response) {
        if (response.status == '200') {
          Swal.fire({ title: 'Success', text: response.message || 'Order moved back to Processing.', icon: 'success' }).then(function() { location.reload(); });
        } else {
          Swal.fire({ title: 'Error', text: response.message || 'Error moving order back.', icon: 'error' });
          $btn.prop('disabled', false).html(originalText);
        }
      },
      error: function() {
        Swal.fire({ title: 'Error', text: 'Error moving order back. Please try again.', icon: 'error' });
        $btn.prop('disabled', false).html(originalText);
      }
    });
  });
}

function moveBackToPending(orderUniqueId, btnElement) {
  Swal.fire({
    title: 'Move back to New Order?',
    text: 'This will move the order back to New Order and reset all processing/shipping details (including 3rd party shipping). Continue?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, move back'
  }).then(function(result) {
    if (!result.isConfirmed) return;
    var $btn = $(btnElement);
    var originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    $.ajax({
      url: '<?php echo base_url("orders/move_back_to_pending_single"); ?>',
      type: 'POST',
      data: { order_unique_id: orderUniqueId },
      dataType: 'json',
      success: function(response) {
        if (response.status == '200') {
          Swal.fire({ title: 'Success', text: response.message || 'Order moved back to New Order.', icon: 'success' }).then(function() { location.reload(); });
        } else {
          Swal.fire({ title: 'Error', text: response.message || 'Error moving order back.', icon: 'error' });
          $btn.prop('disabled', false).html(originalText);
        }
      },
      error: function() {
        Swal.fire({ title: 'Error', text: 'Error moving order back. Please try again.', icon: 'error' });
        $btn.prop('disabled', false).html(originalText);
      }
    });
  });
}
</script>

<script>
$('#thirdPartyShippingModal').on('show.bs.modal', function() {

    $('#thirdPartyProvidersContainer').html('<span class="text-muted">Loading providers...</span>');
    $('#pickupAddressSection').hide();
    $('#pickupAddressSelect').html('<option value="">Select Pickup Address</option>');
    $('#saveThirdPartyBtn').prop('disabled', true);

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


// Provider Click
$(document).on('click', '.third-party-option', function() {

    var provider = $(this).data('provider');

    $('.third-party-option').removeClass('active');
    $(this).addClass('active');

    $('#thirdPartyProvider').val(provider);

    loadPickupAddresses(provider);
});


/* =========================================
   GLOBAL CSRF SETUP
========================================= */

var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';




/* =========================================
   SAVE THIRD PARTY SHIPPING
========================================= */
function saveThirdPartyShipping() {

    var $btn = $('#saveThirdPartyBtn');
    if ($btn.prop('disabled')) return;

    var provider     = $('#thirdPartyProvider').val();
    var pickup       = $('#pickupAddressSelect').val();
    var length       = parseFloat($('#pkgLength').val()) || 0;
    var breadth      = parseFloat($('#pkgBreadth').val()) || 0;
    var height       = parseFloat($('#pkgHeight').val()) || 0;
    var weight       = parseFloat($('#pkgWeight').val()) || 0;
    var scheduleDate = $('#scheduleDate').val();
    var fromTime     = $('#fromTime').val();
    var toTime       = $('#toTime').val();

    if (!provider || !pickup) {
        Swal.fire('Error', 'Select provider & pickup address.', 'warning');
        return;
    }

    if (weight <= 0) {
        Swal.fire('Error', 'Enter valid weight.', 'warning');
        return;
    }

	if (provider.toLowerCase() === 'velocity') {
		let scheduleDate = $('#scheduleDate').val();
		let fromTime     = $('#fromTime').val();
		let toTime       = $('#toTime').val();

		if (!scheduleDate || !fromTime || !toTime) {
			Swal.fire('Error', 'Pickup schedule is required for Velocity.', 'warning');
			return;
		}

		let now = new Date();
		let selectedDate = new Date(scheduleDate + 'T' + fromTime);

		// Past date check
		if (selectedDate < now) {
			Swal.fire('Error', 'Pickup time cannot be in the past.', 'warning');
			return;
		}

		// Time comparison
		if (fromTime >= toTime) {
			Swal.fire('Error', 'From Time must be earlier than To Time.', 'warning');
			return;
		}

		// Optional: Minimum 1 hour window
		let from = new Date(scheduleDate + 'T' + fromTime);
		let to   = new Date(scheduleDate + 'T' + toTime);

		let diffMinutes = (to - from) / 60000;

		if (diffMinutes < 30) {
			Swal.fire('Error', 'Pickup window must be at least 30 minutes.', 'warning');
			return;
		}
	}

    var ajaxData = {
        order_ids            : [orderIdForThirdParty],
        third_party_provider : provider,
        pickup_address_id    : pickup,
        length               : length,
        breadth              : breadth,
        height               : height,
        weight               : weight
    };

    if (provider.toLowerCase() === 'velocity') {
        ajaxData.schedule_date = scheduleDate;
        ajaxData.from_Time     = fromTime;
        ajaxData.to_Time       = toTime;
    }

    $btn.prop('disabled', true);
    $('#saveBtnText').hide();
    $('#saveBtnLoader').show();
	  
    $.ajax({
        url: '<?php echo base_url("orders/bulk_save_third_party_shipping"); ?>',
        type: 'POST',
        dataType: 'json',
        data: ajaxData
    })
    .done(function (res) {
        if (res.csrf && res.csrf.hash) {
            csrfHash = res.csrf.hash;
        }
		
        if (res.status === '200') {
            Swal.fire('Success', res.message, 'success')
                .then(() => location.reload());
        } else {
            $btn.prop('disabled', false);
            $('#saveBtnText').show();
            $('#saveBtnLoader').hide();
            Swal.fire('Error', res.message, 'error');
        }
    });
}


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


function validateThirdPartyForm(){
    var provider = $('#thirdPartyProvider').val();
    var pickup   = $('#pickupAddressSelect').val();
    var length   = parseFloat($('#pkgLength').val()) || 0;
    var breadth  = parseFloat($('#pkgBreadth').val()) || 0;
    var height   = parseFloat($('#pkgHeight').val()) || 0;
    var weight   = parseFloat($('#pkgWeight').val()) || 0;

    if (provider && pickup && weight > 0 && length > 0 && breadth > 0 && height > 0) {
        $('#saveThirdPartyBtn').prop('disabled', false);
    } else {
        $('#saveThirdPartyBtn').prop('disabled', true);
    }
}
$(document).on('change keyup', 
    '#thirdPartyProvider, #pickupAddressSelect, #pkgLength, #pkgBreadth, #pkgHeight, #pkgWeight',
    function() {
        validateThirdPartyForm();
    }
);
$('#pickupAddressSelect').on('change', function(){
    validateThirdPartyForm();
});

</script>
