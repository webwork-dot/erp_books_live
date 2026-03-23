# 3rd Party Shipping Migration

## Overview
This adds support for 3rd party shipping providers (Shiprocket, Big Ship) with:
- Modal to select provider and enter dimensions
- Vendor pickup address from erp_clients
- New table `tbl_order_third_party_shipping` for full shipping details

## Migration Steps

### 1. Master Database (erp_master)
Run `add_state_country_to_erp_clients.sql` to add state and country to vendor profile:
```sql
-- Adds state, country for pickup address in 3rd party modal
ALTER TABLE `erp_clients` 
ADD COLUMN `state` VARCHAR(100) NULL AFTER `pincode`,
ADD COLUMN `country` VARCHAR(100) NULL AFTER `state`;
```
**Note:** If columns already exist, you may get an error - skip or remove those lines.

### 2. Vendor/Tenant Database
Run `add_third_party_shipping.sql` in each vendor database (e.g. erp_client_shivambookscom):
- Adds `third_party_provider`, `pkg_length_cm`, `pkg_breadth_cm`, `pkg_height_cm`, `pkg_weight_kg` to tbl_order_details
- Modifies `courier` enum to include '3rd_party'
- Creates `tbl_order_third_party_shipping` table

## Flow
1. User clicks "3rd Party" on order view → Modal opens
2. User selects Shiprocket or Big Ship
3. Vendor address (from erp_clients) is shown for pickup
4. User enters dimensions: length, breadth, height, weight
5. Save → Updates tbl_order_details + tbl_order_third_party_shipping
6. Order can then be marked Ready to Ship → Out for Delivery

## tbl_order_third_party_shipping
Stores: order_id, order_unique_id, delivery_address_full, pickup_address_full, dimensions, third_party_provider, pickup_provider (for future mini courier selection within 3rd party).
