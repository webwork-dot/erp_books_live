<?php
/**
 * Tax Invoice / Bill of Supply - Shivam Books
 * Design based on kirtibook invoice concept
 * Uses tbl_order_details, tbl_order_items, tbl_order_address data
 * Requires: common_helper (price_format_decimal, rupees_word)
 */
$d = $data;
$shipping = isset($d['shipping']) ? $d['shipping'] : array();
$products = isset($d['products']) ? $d['products'] : array();

// Fallback when no address - use order details
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
if ($bill_landmark) $full_address .= '. Landmark: ' . $bill_landmark;

// IGST vs CGST+SGST: if buyer state is same as seller (e.g. Maharashtra), use CGST+SGST
$place_of_supply = $bill_state ?: 'Maharashtra';
$is_igst = (stripos($place_of_supply, 'Maharashtra') === false) ? 1 : 0;

$currency = isset($d['currency_code']) ? $d['currency_code'] : '₹';
$logo_url = isset($d['logo_url']) ? $d['logo_url'] : 'https://shivambook.com/assets/images/logo.png';
$company_name = isset($d['company_name']) ? $d['company_name'] : 'Shivam Books';
$company_address = isset($d['company_address']) ? $d['company_address'] : '';
$company_gstin = isset($d['company_gstin']) ? $d['company_gstin'] : '-';
$company_pan = isset($d['company_pan']) ? $d['company_pan'] : '-';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body.invoice { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 15px; }
#invoice { width: 100%; border-collapse: collapse; }
#invoice th, #invoice td { padding: 6px 8px; vertical-align: top; }
.head-img { width: 25%; }
.head { width: 75%; text-align: right; }
.logo { max-height: 50px; }
.m-t-10 { margin-top: 12px; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { border: 1px solid #333; padding: 6px 8px; }
.text-left { text-align: left; }
.text-right { text-align: right; }
.text-center { text-align: center; }
.bold { font-weight: bold; }
.font12 { font-size: 11px; }
.p-l-r { padding: 8px; }
.border-t { border-top: 1px solid #333; }
.border-b { border-bottom: 1px solid #333; }
#page-wrap { max-width: 100%; }
</style>
</head>
<body class="invoice">
<div class="panel-body" id="page-wrap">
<table id="invoice">
  <thead>
  <tr>
    <th class="head-img text-left">
      <img src="<?= htmlspecialchars($logo_url) ?>" alt="Logo" class="logo" onerror="this.style.display='none'">
      <p style="margin-top:5px;"><b><?= htmlspecialchars($company_name) ?></b></p>
    </th>
    <th class="text-right head">
      <p><b>Tax Invoice / Bill of Supply / Cash Memo</b></p>
      <p>(Original for Recipient)</p>
      <p class="m-t-10"><b>Order No:</b> <?= htmlspecialchars($d['order_unique_id']) ?> | <b>Order Date:</b> <?= htmlspecialchars($d['order_date']) ?></p>
      <p><b>Invoice No:</b> <?= htmlspecialchars($d['invoice_no']) ?> | <b>Invoice Date:</b> <?= htmlspecialchars($d['invoice_date']) ?></p>
    </th>
  </tr>
  </thead>
</table>

<table id="invoice_3" class="m-t-10 table" style="table-layout: fixed; border-top: 2px solid #333;">
  <thead>
  <tr>
    <th class="p-l-r text-left" style="width: 50%; border: 1px solid #333;">
      <p><b>Sold By:</b> <?= htmlspecialchars($company_name) ?></p>
      <?php if ($company_address): ?><p><b>Address:</b> <?= htmlspecialchars($company_address) ?></p><?php endif; ?>
      <p><b>PAN:</b> <?= htmlspecialchars($company_pan) ?></p>
      <p><b>GSTIN:</b> <?= htmlspecialchars($company_gstin) ?></p>
      <p><b>Place of Supply:</b> <?= htmlspecialchars($place_of_supply) ?></p>
    </th>
    <th class="p-l-r text-left" style="width: 50%; border: 1px solid #333;">
      <p><b>Bill To:</b> <?= htmlspecialchars($bill_name) ?></p>
      <p><b>Address:</b> <?= htmlspecialchars($full_address ?: '-') ?></p>
      <?php if ($bill_email): ?><p><b>Email:</b> <?= htmlspecialchars($bill_email) ?></p><?php endif; ?>
      <?php if ($bill_phone): ?><p><b>Contact:</b> <?= htmlspecialchars($bill_phone) ?></p><?php endif; ?>
      <p><b>GSTIN:</b> URP</p>
    </th>
  </tr>
  </thead>
</table>

<table id="invoice_1" class="m-t-10 table" style="margin-top: 12px;">
  <thead>
  <tr>
    <th rowspan="2" style="border: 1px solid #333; padding: 6px;">#</th>
    <th rowspan="2" class="text-left" style="border: 1px solid #333; padding: 6px;">Description of Goods</th>
    <th rowspan="2" style="border: 1px solid #333; padding: 6px;">HSN</th>
    <th rowspan="2" style="border: 1px solid #333; padding: 6px;">Qty</th>
    <th rowspan="2" style="border: 1px solid #333; padding: 6px;">Taxable Amt</th>
    <?php if ($is_igst): ?>
    <th colspan="2" style="border: 1px solid #333; padding: 6px;">IGST</th>
    <?php else: ?>
    <th colspan="2" style="border: 1px solid #333; padding: 6px;">CGST</th>
    <th colspan="2" style="border: 1px solid #333; padding: 6px;">SGST</th>
    <?php endif; ?>
    <th rowspan="2" style="border: 1px solid #333; padding: 6px;">Amount (incl. Tax)</th>
  </tr>
  <tr>
    <?php if ($is_igst): ?>
    <th style="border: 1px solid #333; padding: 4px;">%</th>
    <th style="border: 1px solid #333; padding: 4px;">Amt</th>
    <?php else: ?>
    <th style="border: 1px solid #333; padding: 4px;">%</th>
    <th style="border: 1px solid #333; padding: 4px;">Amt</th>
    <th style="border: 1px solid #333; padding: 4px;">%</th>
    <th style="border: 1px solid #333; padding: 4px;">Amt</th>
    <?php endif; ?>
  </tr>
  </thead>
  <tbody>
  <?php
  $sr = 1;
  $total_qty = 0;
  $total_taxable = 0;
  $total_gst = 0;
  $total_incl = 0;
  foreach ($products as $p):
    $qty = isset($p['product_qty']) ? (int)$p['product_qty'] : 1;
    $total_price = isset($p['total_price']) ? (float)$p['total_price'] : 0;
    $gst_amt = isset($p['total_gst_amt']) ? (float)$p['total_gst_amt'] : 0;
    $taxable = isset($p['excl_price_total']) && $p['excl_price_total'] > 0 ? (float)$p['excl_price_total'] : ($total_price - $gst_amt);
    $gst_pct = isset($p['product_gst']) ? (float)$p['product_gst'] : 0;
    $hsn = isset($p['hsn']) ? $p['hsn'] : '4901';
    $total_qty += $qty;
    $total_taxable += $taxable;
    $total_gst += $gst_amt;
    $total_incl += $total_price;
  ?>
  <tr>
    <td style="border: 1px solid #333; padding: 6px;"><?= $sr++ ?></td>
    <td class="text-left" style="border: 1px solid #333; padding: 6px;"><?= htmlspecialchars(isset($p['product_title']) ? $p['product_title'] : '') ?></td>
    <td style="border: 1px solid #333; padding: 6px;"><?= htmlspecialchars($hsn) ?></td>
    <td style="border: 1px solid #333; padding: 6px;"><?= $qty ?></td>
    <td style="border: 1px solid #333; padding: 6px;"><?= $currency ?><?= price_format_decimal($taxable) ?></td>
    <?php if ($is_igst): ?>
    <td style="border: 1px solid #333; padding: 6px;"><?= $gst_pct ?>%</td>
    <td style="border: 1px solid #333; padding: 6px;"><?= $currency ?><?= price_format_decimal($gst_amt) ?></td>
    <?php else: ?>
    <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct/2) ?>%</td>
    <td style="border: 1px solid #333; padding: 6px;"><?= $currency ?><?= price_format_decimal($gst_amt/2) ?></td>
    <td style="border: 1px solid #333; padding: 6px;"><?= ($gst_pct/2) ?>%</td>
    <td style="border: 1px solid #333; padding: 6px;"><?= $currency ?><?= price_format_decimal($gst_amt/2) ?></td>
    <?php endif; ?>
    <td style="border: 1px solid #333; padding: 6px;"><?= $currency ?><?= price_format_decimal($total_price) ?></td>
  </tr>
  <?php endforeach; ?>

  <?php
  $delivery_charge = isset($d['delivery_charge']) ? (float)$d['delivery_charge'] : 0;
  if ($delivery_charge > 0):
    $total_incl += $delivery_charge;
  ?>
  <tr>
    <td style="border: 1px solid #333; padding: 6px;"><?= $sr++ ?></td>
    <td class="text-left" style="border: 1px solid #333; padding: 6px;">Delivery / Freight Charges</td>
    <td style="border: 1px solid #333; padding: 6px;">-</td>
    <td style="border: 1px solid #333; padding: 6px;">-</td>
    <td style="border: 1px solid #333; padding: 6px;"><?= $currency ?><?= price_format_decimal($delivery_charge) ?></td>
    <?php if ($is_igst): ?>
    <td colspan="2" style="border: 1px solid #333; padding: 6px;">-</td>
    <?php else: ?>
    <td colspan="4" style="border: 1px solid #333; padding: 6px;">-</td>
    <?php endif; ?>
    <td style="border: 1px solid #333; padding: 6px;"><?= $currency ?><?= price_format_decimal($delivery_charge) ?></td>
  </tr>
  <?php endif; ?>

  <tr class="bold" style="background: #f5f5f5;">
    <td colspan="3" class="text-left" style="border: 1px solid #333; padding: 8px;">Total</td>
    <td style="border: 1px solid #333; padding: 8px;"><?= $total_qty ?></td>
    <td style="border: 1px solid #333; padding: 8px;"><?= $currency ?><?= price_format_decimal($total_taxable) ?></td>
    <?php if ($is_igst): ?>
    <td colspan="2" style="border: 1px solid #333; padding: 8px;"><?= $currency ?><?= price_format_decimal($total_gst) ?></td>
    <?php else: ?>
    <td colspan="2" style="border: 1px solid #333; padding: 8px;"><?= $currency ?><?= price_format_decimal($total_gst/2) ?></td>
    <td colspan="2" style="border: 1px solid #333; padding: 8px;"><?= $currency ?><?= price_format_decimal($total_gst/2) ?></td>
    <?php endif; ?>
    <td style="border: 1px solid #333; padding: 8px;"><?= $currency ?><?= price_format_decimal($total_incl) ?></td>
  </tr>
  <tr>
    <td colspan="<?= $is_igst ? 7 : 9 ?>" class="text-left" style="border: 1px solid #333; padding: 6px;">Total Invoice Value (In Words)</td>
    <td class="text-right" style="border: 1px solid #333; padding: 6px;"><?= function_exists('rupees_word') ? rupees_word($total_incl) : (function_exists('price_format_decimal') ? price_format_decimal($total_incl) : number_format($total_incl, 2)) . ' Only' ?></td>
  </tr>
  </tbody>
</table>

<table id="invoice" class="m-t-10" style="width:100%; margin-top: 15px;">
  <tr>
    <td colspan="2" class="text-left" style="padding: 8px;">
      <p><b>Declaration:</b> <small>The goods sold are intended for end user consumption and not for resale. Please note that this invoice is not a demand for payment.</small></p>
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
