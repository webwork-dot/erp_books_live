<?php

/**
 * Tax Invoice / Bill of Supply - -
 * Design based on kirtibook + shipping label
 * Logo from erp_clients (base64), seller GSTIN/PAN/name from erp_clients
 * Products displayed like shipping label: Bookset (with packages), Individual, Uniform
 */
$d = $data;
$shipping = isset($d['shipping']) ? $d['shipping'] : array();
$order_type_label = isset($d['order_type_label']) ? $d['order_type_label'] : 'Individual';
$items_arr = isset($d['items_arr']) ? $d['items_arr'] : array();
$bookset_products = isset($d['bookset_products']) ? $d['bookset_products'] : array();
$order = isset($d['order_obj']) ? $d['order_obj'] : (object) array();
$products = isset($d['products']) ? $d['products'] : array();

// Fallback when no address
$bill_name = !empty($shipping['name']) ? $shipping['name'] : (isset($d['user_name']) ? $d['user_name'] : '');
$bill_phone = !empty($shipping['mobile_no']) ? $shipping['mobile_no'] : (isset($d['user_phone']) ? $d['user_phone'] : '');
$bill_email = !empty($shipping['email']) ? $shipping['email'] : (isset($d['user_email']) ? $d['user_email'] : '');
$bill_addr = !empty($shipping['address']) ? trim($shipping['address']) : '';
$bill_city = !empty($shipping['city']) ? $shipping['city'] : '';
$bill_state = !empty($shipping['state']) ? $shipping['state'] : '';
$bill_pincode = !empty($shipping['pincode']) ? $shipping['pincode'] : '';
$bill_country = !empty($shipping['country']) ? $shipping['country'] : 'India';
$bill_landmark = !empty($shipping['landmark']) ? $shipping['landmark'] : '';
$full_address = trim(implode(', ', array_filter([$bill_addr, $bill_city, $bill_state, $bill_country, $bill_pincode])), ', ');
if ($bill_landmark)
  $full_address .= '. Landmark: ' . $bill_landmark;

$place_of_supply = $bill_state ?: 'Maharashtra';
$is_igst = (stripos($place_of_supply, 'Maharashtra') === false) ? 1 : 0;

$currency = isset($d['currency_code']) ? $d['currency_code'] : '₹';
$logo_src = isset($d['logo_src']) ? $d['logo_src'] : '';
$company_name = isset($d['company_name']) ? $d['company_name'] : '';
$company_address = isset($d['company_address']) ? $d['company_address'] : '';
$company_gstin = isset($d['company_gstin']) ? $d['company_gstin'] : '';
$company_pan = isset($d['company_pan']) ? $d['company_pan'] : '';
$company_phone = isset($d['company_phone']) ? $d['company_phone'] : '';

// Total invoice value for calculations
$total_invoice_value = isset($d['payable_amt']) ? floatval($d['payable_amt']) : 0;
if ($total_invoice_value <= 0 && !empty($products)) {
  foreach ($products as $p)
    $total_invoice_value += isset($p['total_price']) ? floatval($p['total_price']) : 0;
}

// Payment method display logic
$pm = isset($order->payment_method) ? strtolower($order->payment_method) : (isset($d['payment_method']) ? strtolower($d['payment_method']) : '');
$payment_method_display = 'Online Payment';
if ($pm === 'cod') {
  $payment_method_display = 'Cash on Delivery';
} elseif ($pm === 'payment_at_school' || $pm === 'payment_at_scho') {
  $payment_method_display = 'Payment at School';
} elseif ($pm === 'cash' || $pm === 'cash_payment') {
  $payment_method_display = 'Cash';
} elseif ($pm === 'upi' || $pm === 'upi_payment') {
  $payment_method_display = 'UPI';
} elseif (!empty($pm)) {
  $payment_method_display = ucwords(str_replace('_', ' ', $pm));
} else {
  $payment_method_display = '-';
}

$txn_details = array();
if (!empty($order->txn_id)) {
  $txn_details[] = 'Txn ID: ' . $order->txn_id;
}
if (!empty($order->payment_id)) {
  $txn_details[] = 'Payment ID: ' . $order->payment_id;
}
if (!empty($order->razorpay_order_id)) {
  $txn_details[] = 'Razorpay Order: ' . $order->razorpay_order_id;
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <style>
    body.invoice {
      font-family: DejaVu Sans, sans-serif;
      font-size: 10px;
      margin: 15px;
    }

    body.invoice p {
      margin: 0;
      padding: 0;
    }

    .logo {
      max-height: 70px;
      max-width: 220px;
      object-fit: contain;
    }

    .m-t-10 {
      margin-top: 12px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th {
      border: 1px solid #333;
      padding: 6px 8px;
      background-color: #f2f2f2;
      text-align: center;
      font-weight: bold;
    }

    .table td {
      border: 1px solid #333;
      padding: 6px 8px;
    }

    .text-left {
      text-align: left;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .bold {
      font-weight: bold;
    }

    .font12 {
      font-size: 11px;
    }

    .p-l-r {
      padding: 8px;
    }

    .border-t {
      border-top: 1px solid #333;
    }

    .border-b {
      border-bottom: 1px solid #333;
    }

    #page-wrap {
      max-width: 100%;
    }

    .book-pack {
      font-size: 9px;
    }
  </style>
</head>

<body class="invoice">
  <div class="panel-body" id="page-wrap">
    <table class="invoice-header" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
      <tr>
        <!-- Left Column: Logo -->
        <td style="width: 35%; text-align: left; vertical-align: middle; padding: 0;">
          <?php if (!empty($logo_src)): ?>
            <img src="<?= htmlspecialchars($logo_src) ?>" alt="Logo" class="logo" style="display: block;">
          <?php else: ?>
            <p style="font-size: 16px; color: #2d3748; margin: 0; text-align: left;">
              <b><?= htmlspecialchars($company_name) ?></b></p>
          <?php endif; ?>
        </td>

        <!-- Center Column: Title -->
        <td style="width: 30%; text-align: center; vertical-align: middle; padding: 0 5px;">
          <h1
            style="margin: 0; font-size: 14px; color: #1a365d; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">
            Tax Invoice</h1>
          <span style="font-size: 8.5px; color: #718096; display: block; margin-top: 3px; text-align: center;">Original
            for Recipient</span>
        </td>

        <!-- Right Column: Invoice Details -->
        <td
          style="width: 35%; text-align: right; vertical-align: middle; padding: 0; font-size: 9.5px; line-height: 1.5;">
          <div><b>Invoice No:</b> <?= htmlspecialchars($d['invoice_no']) ?></div>
          <div style="margin-top: 2px;"><b>Invoice Date:</b> <?= htmlspecialchars($d['invoice_date']) ?></div>
        </td>
      </tr>
    </table>

    <table id="invoice_3" class="m-t-10 table" style="table-layout: fixed;">
      <thead>
        <tr>
          <th style="width: 32%; text-align: center; vertical-align: middle;">Sold By</th>
          <th style="width: 32%; text-align: center; vertical-align: middle;">Bill To</th>
          <th style="width: 36%; text-align: center; vertical-align: middle;">Order & Payment Details</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="p-l-r text-left" style="vertical-align: top;">
            <p>Name: <b><?= htmlspecialchars($company_name) ?></b></p>
            <?php if ($company_address): ?>
              <p style="margin-top: 3.5px;">Address: <b><?= htmlspecialchars($company_address) ?></b></p><?php endif; ?>
            <?php if ($company_phone): ?>
              <p style="margin-top: 3.5px;">Contact: <b><?= htmlspecialchars($company_phone) ?></b></p><?php endif; ?>
            <p style="margin-top: 3.5px;">PAN: <b><?= htmlspecialchars($company_pan) ?></b></p>
            <p style="margin-top: 3.5px;">GSTIN: <b><?= htmlspecialchars($company_gstin) ?></b></p>
          </td>
          <td class="p-l-r text-left" style="vertical-align: top;">
            <p>Name: <b><?= htmlspecialchars($bill_name) ?></b></p>
            <p style="margin-top: 3.5px;">Address: <b><?= htmlspecialchars($full_address ?: '-') ?></b></p>
            <?php if ($bill_email): ?>
              <p style="margin-top: 3.5px;">Email: <b><?= htmlspecialchars($bill_email) ?></b></p><?php endif; ?>
            <?php if ($bill_phone): ?>
              <p style="margin-top: 3.5px;">Contact: <b><?= htmlspecialchars($bill_phone) ?></b></p><?php endif; ?>
            <p style="margin-top: 3.5px;">GSTIN: <b>URP</b></p>
          </td>
          <td class="p-l-r text-left" style="vertical-align: top;">
            <p>Order No: <b><?= htmlspecialchars($d['order_unique_id']) ?></b></p>
            <?php
            $order_date_raw = $d['order_date'];
            $date_parts = explode('|', $order_date_raw);
            $date_str = trim($date_parts[0]);
            $time_str = isset($date_parts[1]) ? trim($date_parts[1]) : '';
            ?>
            <p style="margin-top: 3.5px;">Order Date: <b><?= htmlspecialchars($date_str) ?></b></p>
            <?php if (!empty($time_str)): ?>
              <p style="margin-top: 3.5px;">Order Time: <b><?= htmlspecialchars($time_str) ?></b></p>
            <?php endif; ?>
            <p style="margin-top: 3.5px;">Payment Mode: <b><?= htmlspecialchars($payment_method_display) ?></b></p>
            <?php foreach ($txn_details as $detail): ?>
              <?php
              $parts = explode(':', $detail, 2);
              if (count($parts) == 2) {
                $label = trim($parts[0]);
                $val = trim($parts[1]);
                echo '<p style="margin-top: 3.5px;">' . htmlspecialchars($label) . ': <b style="word-break: break-all;">' . htmlspecialchars($val) . '</b></p>';
              } else {
                echo '<p style="margin-top: 3.5px;"><b style="word-break: break-all;">' . htmlspecialchars($detail) . '</b></p>';
              }
              ?>
            <?php endforeach; ?>
            <p style="margin-top: 3.5px;">Place of Supply: <b><?= htmlspecialchars($place_of_supply) ?></b></p>
          </td>
        </tr>
      </tbody>
    </table>

    <table id="invoice_1" class="m-t-10 table" style="margin-top: 12px;">
      <thead>
        <tr>
          <th rowspan="2" style="border: 1px solid #333; padding: 6px;">#</th>
          <th rowspan="2" class="text-left" style="border: 1px solid #333; padding: 6px;">Description of Goods</th>
          <th rowspan="2" style="border: 1px solid #333; padding: 6px;">HSN</th>
          <th rowspan="2" class="text-right" style="border: 1px solid #333; padding: 6px;">Rate</th>
          <th rowspan="2" style="border: 1px solid #333; padding: 6px;">Qty</th>
          <th rowspan="2" class="text-right" style="border: 1px solid #333; padding: 6px;">Taxable Amt</th>
          <?php if ($is_igst): ?>
            <th colspan="2" style="border: 1px solid #333; padding: 6px;">IGST</th>
          <?php else: ?>
            <th colspan="2" style="border: 1px solid #333; padding: 6px;">CGST</th>
            <th colspan="2" style="border: 1px solid #333; padding: 6px;">SGST</th>
          <?php endif; ?>
          <th rowspan="2" class="text-right" style="border: 1px solid #333; padding: 6px;">Amount (incl. Tax)</th>
        </tr>
        <tr>
          <?php if ($is_igst): ?>
            <th style="border: 1px solid #333; padding: 4px;">%</th>
            <th class="text-right" style="border: 1px solid #333; padding: 4px;">Amt</th>
          <?php else: ?>
            <th style="border: 1px solid #333; padding: 4px;">%</th>
            <th class="text-right" style="border: 1px solid #333; padding: 4px;">Amt</th>
            <th style="border: 1px solid #333; padding: 4px;">%</th>
            <th class="text-right" style="border: 1px solid #333; padding: 4px;">Amt</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php
        // Parse booksets dynamically from items_arr if available
        $has_bookset_json = false;
        $parsed_bookset_rows = array();

        if (!empty($items_arr)) {
          foreach ($items_arr as $item) {
            if (isset($item->order_type) && $item->order_type == 'bookset') {
              $packages_list = array();
              if (!empty($item->bookset_packages_json)) {
                $json = json_decode($item->bookset_packages_json, true);
                if (isset($json['packages'])) {
                  $packages_list = $json['packages'];
                }
              }

              $selected_ids = array_filter(explode(',', $item->package_id ?? ''));

              if (!empty($packages_list) && !empty($selected_ids)) {
                $has_bookset_json = true;
                foreach ($packages_list as $pkg) {
                  $pkg_id = $pkg['package_id'];
                  if (in_array($pkg_id, $selected_ids)) {
                    $qty = (int) $item->product_qty;
                    $tp = (float) $pkg['package_offer_price'] * $qty;
                    $gst_pct = (float) $pkg['gst'];
                    $gst_amt = ($gst_pct > 0) ? ($tp * ($gst_pct / 100) / (1 + ($gst_pct / 100))) : 0;
                    $taxable = $tp - $gst_amt;

                    $parsed_bookset_rows[] = array(
                      'desc' => $pkg['package_name'],
                      'hsn' => $pkg['hsn'] ?? '4901',
                      'qty' => $qty,
                      'taxable' => $taxable,
                      'gst_pct' => $gst_pct,
                      'gst_amt' => $gst_amt,
                      'total_price' => $tp
                    );
                  }
                }
              }
            }
          }
        }

        $sr = 1;
        $total_qty = 0;
        $total_taxable = 0;
        $total_gst = 0;
        $total_incl = 0;

        // BOOKSET: display expanded products from packages or individual items
        if ($order_type_label == 'Bookset' && $has_bookset_json) {
          $bookset_display_name = 'Bookset';
          if (!empty($items_arr)) {
            foreach ($items_arr as $it) {
              if (isset($it->order_type) && ($it->order_type == 'bookset' || $it->order_type == 'package') && !empty($it->product_title)) {
                $bookset_display_name = trim($it->product_title);
                break;
              }
            }
          }
          ?>
          <tr>
            <td colspan="<?= $is_igst ? 10 : 12 ?>" class="text-left"
              style="border: 1px solid #333; padding: 8px; background-color: #e0e0e0; font-weight: bold;">Bookset:
              <?= htmlspecialchars($bookset_display_name) ?></td>
          </tr>
          <?php
          foreach ($parsed_bookset_rows as $row_data) {
            $pname = $row_data['desc'];
            $qty = $row_data['qty'];
            $taxable = $row_data['taxable'];
            $gst_pct = $row_data['gst_pct'];
            $gst_amt = $row_data['gst_amt'];
            $tp = $row_data['total_price'];
            $hsn = $row_data['hsn'];
            $unit_rate = ($qty > 0) ? ($tp / $qty) : 0;

            $total_qty += $qty;
            $total_taxable += $taxable;
            $total_gst += $gst_amt;
            $total_incl += $tp;
            ?>
            <tr>
              <td style="border: 1px solid #333; padding: 6px;"><?= $sr++ ?></td>
              <td class="text-left" style="border: 1px solid #333; padding: 6px;"><small
                  class="book-pack"><?= htmlspecialchars($pname) ?></small></td>
              <td style="border: 1px solid #333; padding: 6px;"><?= htmlspecialchars($hsn) ?></td>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($unit_rate) ?>
              </td>
              <td style="border: 1px solid #333; padding: 6px;"><?= $qty ?></td>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($taxable) ?>
              </td>
              <?php if ($is_igst): ?>
                <td style="border: 1px solid #333; padding: 6px;"><?= $gst_pct ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt) ?>
                </td>
              <?php else: ?>
                <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                </td>
                <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                </td>
              <?php endif; ?>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= $currency ?>
                <?= price_format_decimal($tp) ?></td>
            </tr>
            <?php
          }
        } elseif ($order_type_label == 'Bookset' && !empty($bookset_products)) {
          $bookset_display_name = 'Bookset';
          if (!empty($items_arr)) {
            foreach ($items_arr as $it) {
              if (isset($it->order_type) && ($it->order_type == 'bookset' || $it->order_type == 'package') && !empty($it->product_title)) {
                $bookset_display_name = trim($it->product_title);
                break;
              }
            }
          }
          ?>
          <tr>
            <td colspan="<?= $is_igst ? 10 : 12 ?>" class="text-left"
              style="border: 1px solid #333; padding: 8px; background-color: #e0e0e0; font-weight: bold;">Bookset:
              <?= htmlspecialchars($bookset_display_name) ?></td>
          </tr>
          <?php
          $packages = array();
          foreach ($bookset_products as $bp) {
            $pkg_id = isset($bp->package_id) ? $bp->package_id : 0;
            $pkg_name = isset($bp->package_name) ? $bp->package_name : '';
            if (!isset($packages[$pkg_id]))
              $packages[$pkg_id] = array('name' => $pkg_name, 'products' => array());
            $packages[$pkg_id]['products'][] = $bp;
          }

          foreach ($packages as $pkg_id => $pkg_data) {
            foreach ($pkg_data['products'] as $bp) {
              $pname = isset($bp->product_name) ? $bp->product_name : 'Product';
              $qty = isset($bp->quantity) ? (int) $bp->quantity : 1;
              $tp = isset($bp->total_price) ? floatval($bp->total_price) : 0;
              $gst_pct = isset($bp->gst) ? (float) $bp->gst : 18;
              $gst_amt = ($gst_pct > 0) ? ($tp * ($gst_pct / 100) / (1 + ($gst_pct / 100))) : 0;
              $taxable = $tp - $gst_amt;
              $hsn = isset($bp->hsn) ? $bp->hsn : '4901';
              $unit_rate = ($qty > 0) ? ($tp / $qty) : 0;

              $total_qty += $qty;
              $total_taxable += $taxable;
              $total_gst += $gst_amt;
              $total_incl += $tp;
              ?>
              <tr>
                <td style="border: 1px solid #333; padding: 6px;"><?= $sr++ ?></td>
                <td class="text-left" style="border: 1px solid #333; padding: 6px;"><small
                    class="book-pack"><?= htmlspecialchars($pname) ?></small></td>
                <td style="border: 1px solid #333; padding: 6px;"><?= htmlspecialchars($hsn) ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($unit_rate) ?>
                </td>
                <td style="border: 1px solid #333; padding: 6px;"><?= $qty ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($taxable) ?>
                </td>
                <?php if ($is_igst): ?>
                  <td style="border: 1px solid #333; padding: 6px;"><?= $gst_pct ?></td>
                  <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt) ?>
                  </td>
                <?php else: ?>
                  <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                  <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                  </td>
                  <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                  <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                  </td>
                <?php endif; ?>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= $currency ?>
                  <?= price_format_decimal($tp) ?></td>
              </tr>
              <?php
            }
          }
        } elseif ($order_type_label == 'Bookset' && !empty($items_arr)) {
          // Fallback: no bookset_products, use items_arr
          $item_count = count($items_arr);
          $inv_per = ($item_count > 0 && $total_invoice_value > 0) ? ($total_invoice_value / $item_count) : 0;
          foreach ($items_arr as $item) {
            $desc = isset($item->product_title) ? $item->product_title : (isset($item->product_name) ? $item->product_name : '');
            $qty = isset($item->product_qty) ? (int) $item->product_qty : 1;
            $tp = isset($item->total_price) ? floatval($item->total_price) : $inv_per;
            $gst_amt = isset($item->total_gst_amt) ? floatval($item->total_gst_amt) : ($tp * 0.18 / 1.18);
            $taxable = isset($item->excl_price_total) && $item->excl_price_total > 0 ? floatval($item->excl_price_total) : ($tp - $gst_amt);
            $gst_pct = isset($item->product_gst) ? floatval($item->product_gst) : 18;
            $hsn = isset($item->hsn) ? $item->hsn : '4901';
            $unit_rate = isset($item->product_price) && $item->product_price > 0 ? (float) $item->product_price : (($qty > 0) ? ($tp / $qty) : 0);
            $total_qty += $qty;
            $total_taxable += $taxable;
            $total_gst += $gst_amt;
            $total_incl += $tp;
            $school = !empty($order->school_name) ? htmlspecialchars($order->school_name) . '<br>' : '';
            ?>
            <tr>
              <td style="border: 1px solid #333; padding: 6px;"><?= $sr++ ?></td>
              <td class="text-left" style="border: 1px solid #333; padding: 6px;"><?= $school ?><small
                  class="book-pack"><?= htmlspecialchars($desc) ?></small></td>
              <td style="border: 1px solid #333; padding: 6px;"><?= htmlspecialchars($hsn) ?></td>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($unit_rate) ?>
              </td>
              <td style="border: 1px solid #333; padding: 6px;"><?= $qty ?></td>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($taxable) ?>
              </td>
              <?php if ($is_igst): ?>
                <td style="border: 1px solid #333; padding: 6px;"><?= $gst_pct ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt) ?>
                </td>
              <?php else: ?>
                <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                </td>
                <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                </td>
              <?php endif; ?>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= $currency ?>
                <?= price_format_decimal($tp) ?></td>
            </tr>
            <?php
          }
        } else {
          // INDIVIDUAL / UNIFORM: flat product list from products (tbl_order_items)
          foreach ($items_arr as $p_obj):
            $qty = isset($p_obj->product_qty) ? (int) $p_obj->product_qty : 1;
            $total_price = isset($p_obj->total_price) ? (float) $p_obj->total_price : 0;
            $gst_amt = isset($p_obj->total_gst_amt) ? (float) $p_obj->total_gst_amt : 0;
            $taxable = isset($p_obj->excl_price_total) && $p_obj->excl_price_total > 0 ? (float) $p_obj->excl_price_total : ($total_price - $gst_amt);
            $gst_pct = isset($p_obj->product_gst) ? (float) $p_obj->product_gst : 0;
            $hsn = isset($p_obj->hsn) ? $p_obj->hsn : '4901';
            $unit_rate = isset($p_obj->product_price) && $p_obj->product_price > 0 ? (float) $p_obj->product_price : (($qty > 0) ? ($total_price / $qty) : 0);

            $total_qty += $qty;
            $total_taxable += $taxable;
            $total_gst += $gst_amt;
            $total_incl += $total_price;

            $item_school = !empty($p_obj->school_name) ? $p_obj->school_name : (!empty($order->school_name) ? $order->school_name : '');
            $school_str = !empty($item_school) ? '<b>' . htmlspecialchars($item_school) . '</b><br>' : '';

            $desc = htmlspecialchars(isset($p_obj->product_title) ? $p_obj->product_title : '');
            if (isset($p_obj->order_type) && $p_obj->order_type == 'uniform' || (isset($p_obj->is_variation) && $p_obj->is_variation == 1)) {
              if (empty($p_obj->class_name) && empty($p_obj->size_name) && !empty($p_obj->variation_name)) {
                $desc .= '<br><small>' . htmlspecialchars($p_obj->variation_name) . '</small>';
              }
              if (!empty($p_obj->class_name)) {
                $desc .= '<br><small>Class: ' . htmlspecialchars($p_obj->class_name) . '</small>';
              }
              if (!empty($p_obj->size_name)) {
                $desc .= '<br><small>Size: ' . htmlspecialchars($p_obj->size_name) . '</small>';
              }
              if (!empty($p_obj->hsn)) {
                $desc .= '<br><small>HSN: ' . htmlspecialchars($p_obj->hsn) . '</small>';
              }
            }

            $desc = $school_str . $desc;

            // If requested to show HSN "only for uniforms", we can conditionally clear it for non-uniforms
            // But since the column is HSN, we'll display what's in the DB.
            ?>
            <tr>
              <td style="border: 1px solid #333; padding: 6px;"><?= $sr++ ?></td>
              <td class="text-left" style="border: 1px solid #333; padding: 6px;"><?= $desc ?></td>
              <td style="border: 1px solid #333; padding: 6px;"><?= htmlspecialchars($hsn) ?></td>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($unit_rate) ?>
              </td>
              <td style="border: 1px solid #333; padding: 6px;"><?= $qty ?></td>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($taxable) ?>
              </td>
              <?php if ($is_igst): ?>
                <td style="border: 1px solid #333; padding: 6px;"><?= $gst_pct ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt) ?>
                </td>
              <?php else: ?>
                <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                </td>
                <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct / 2) ?></td>
                <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= price_format_decimal($gst_amt / 2) ?>
                </td>
              <?php endif; ?>
              <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= $currency ?>
                <?= price_format_decimal($total_price) ?></td>
            </tr>
          <?php endforeach;
        }
        ?>

        <?php
        $delivery_charge = isset($d['delivery_charge']) ? (float) $d['delivery_charge'] : 0;
        if ($delivery_charge > 0):
          $total_incl += $delivery_charge;
          ?>
          <tr>
            <td style="border: 1px solid #333; padding: 6px;"><?= $sr++ ?></td>
            <td class="text-left" style="border: 1px solid #333; padding: 6px;">Delivery / Freight Charges</td>
            <td style="border: 1px solid #333; padding: 6px;">-</td>
            <td style="border: 1px solid #333; padding: 6px;">-</td>
            <td style="border: 1px solid #333; padding: 6px;">-</td>
            <td class="text-right" style="border: 1px solid #333; padding: 6px;">
              <?= price_format_decimal($delivery_charge) ?></td>
            <?php if ($is_igst): ?>
              <td colspan="2" style="border: 1px solid #333; padding: 6px;">-</td>
            <?php else: ?>
              <td colspan="4" style="border: 1px solid #333; padding: 6px;">-</td>
            <?php endif; ?>
            <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= $currency ?>
              <?= price_format_decimal($delivery_charge) ?></td>
          </tr>
        <?php endif; ?>

        <?php
        $display_total = $total_incl;
        if (isset($d['payable_amt']) && (float) $d['payable_amt'] > 0) {
          $display_total = (float) $d['payable_amt'];
        }
        ?>
        <tr class="bold" style="background: #f5f5f5;">
          <td colspan="4" class="text-left" style="border: 1px solid #333; padding: 8px;">Total</td>
          <td style="border: 1px solid #333; padding: 8px;"><?= $total_qty ?></td>
          <td class="text-right" style="border: 1px solid #333; padding: 8px;">
            <?= price_format_decimal($total_taxable) ?></td>
          <?php if ($is_igst): ?>
            <td colspan="2" class="text-right" style="border: 1px solid #333; padding: 8px;">
              <?= price_format_decimal($total_gst) ?></td>
          <?php else: ?>
            <td colspan="2" class="text-right" style="border: 1px solid #333; padding: 8px;">
              <?= price_format_decimal($total_gst / 2) ?></td>
            <td colspan="2" class="text-right" style="border: 1px solid #333; padding: 8px;">
              <?= price_format_decimal($total_gst / 2) ?></td>
          <?php endif; ?>
          <td class="text-right" style="border: 1px solid #333; padding: 8px;"><?= $currency ?>
            <?= price_format_decimal($display_total) ?></td>
        </tr>
        <tr>
          <td colspan="<?= $is_igst ? 8 : 10 ?>" class="text-left" style="border: 1px solid #333; padding: 6px;">Total
            Invoice Value (In Words)</td>
          <td class="text-right" style="border: 1px solid #333; padding: 6px;">
            <?= function_exists('rupees_word') ? rupees_word($display_total) : (function_exists('price_format_decimal') ? price_format_decimal($display_total) : number_format($display_total, 2)) . ' Only' ?>
          </td>
        </tr>
      </tbody>
    </table>

    <table id="invoice" class="m-t-10" style="width:100%; margin-top: 15px;">
      <tr>
        <td colspan="2" class="text-left" style="padding: 8px;">
          <p><b>Declaration:</b> <small>The goods sold are intended for end user consumption and not for resale. Please
              note that this invoice is not a demand for payment.</small></p>
          <p><small>E.&O.E. | Whether tax is payable on reverse charge basis - No</small></p>
        </td>
        <td class="text-right" style="padding: 8px; width: 35%;">
          <p><b>For <?= htmlspecialchars($company_name) ?></b></p>
          <p><b>Authorized Signatory</b></p>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>