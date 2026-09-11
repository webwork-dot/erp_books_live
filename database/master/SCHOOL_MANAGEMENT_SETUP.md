# School Management System - Database Setup Guide

## Overview
This document provides all SQL statements needed to set up the School Management system for vendors.

## Step 1: Create Main Tables

Run the SQL file: `create_schools_tables.sql`

This creates:
- `erp_schools` - Main school information table
- `erp_school_images` - School images table
- `states` - Indian states table
- `cities` - Indian cities table

## Step 2: Import States Data

The states table structure is already created. You need to import states data from `varitty_varitdbc.sql`.

**Extract and run the INSERT statements for states:**

From `varitty_varitdbc.sql`, find the section starting around line 55880:
```sql
INSERT INTO `states` (`id`, `name`, `code`, `country_id`) VALUES
(1547, 'Andaman and Nicobar Islands', 'AN', 101),
(1548, 'Andhra Pradesh', 'AP', 101),
...
```

**Note:** The states INSERT statement in varitty_varitdbc.sql contains all Indian states. Copy the entire INSERT statement and run it.

## Step 3: Import Cities Data

**Extract and run the INSERT statements for cities:**

From `varitty_varitdbc.sql`, find the section starting around line 1070:
```sql
INSERT INTO `cities` (`id`, `name`, `country_id`, `state_id`) VALUES
(14717, 'Bombuflat', 101, 1547),
(14718, 'Garacharma', 101, 1547),
...
```

**Note:** The cities INSERT statement in varitty_varitdbc.sql contains all Indian cities. Copy the entire INSERT statement and run it.

## Quick Setup Script

To extract states and cities from varitty_varitdbc.sql, you can use this approach:

1. Open `varitty_varitdbc.sql` in a text editor
2. Search for `INSERT INTO \`states\`` - Copy the entire INSERT statement
3. Search for `INSERT INTO \`cities\`` - Copy the entire INSERT statement (it may be split across multiple INSERT statements)
4. Run them in your database

## Database Structure Summary

### erp_schools Table
- Stores all school information
- Links to vendor via `vendor_id`
- Links to states/cities via `state_id` and `city_id`
- Admin credentials stored with SHA1 hashed password

### erp_school_images Table
- Stores multiple images per school
- Supports primary image designation
- Display order for sorting

### states Table
- Indian states with codes
- country_id = 101 (India)

### cities Table
- Indian cities
- Linked to states via state_id
- country_id = 101 (India)

## File Upload Directory

Create the following directory for school images:
```
erp-system/uploads/schools/
```

Set permissions to 755 or 777 (depending on your server configuration).

## Next Steps

1. Run `create_schools_tables.sql` to create table structures
2. Import states data from varitty_varitdbc.sql
3. Import cities data from varitty_varitdbc.sql
4. Create uploads/schools/ directory
5. Test the school management functionality

## Private Bookset Column

Hide a school from public clickable discovery while keeping a tokenized unique storefront URL accessible.

**Apply to master template DB first** (new client DBs are built from `erp_master` / `erp_master.sql`):

```sql
-- See also: add_private_bookset_column.sql
USE erp_master;
ALTER TABLE erp_schools
  ADD COLUMN is_private_bookset TINYINT(1) NOT NULL DEFAULT 0
  COMMENT 'Private Bookset: view-only in public listings (1=yes, 0=no)'
  AFTER status;

ALTER TABLE erp_schools
  ADD COLUMN private_bookset_token VARCHAR(64) NULL DEFAULT NULL
  COMMENT 'Secret token for private unique school-bookset URL'
  AFTER is_private_bookset;
```

Then apply the same `ALTER`s on each existing tenant DB.

When `is_private_bookset = 1`:
- School still appears on Our Schools / school-bookset listing for **viewing only** (card is not clickable)
- Plain `/school-bookset/{id}` and cold `/bookset/{slug}` return 404
- Unique share URL is `/school-bookset/{id}/{token}` (Copy / WhatsApp in admin)
- Opening the unique URL unlocks bookset details for that browser session
- School is excluded from search suggestions (prevents click-through)

## Features Implemented

✅ Add School with all required fields
✅ Edit School
✅ Delete School
✅ Multiple Image Upload
✅ State/City Dropdown (AJAX-based)
✅ Admin Login Details Management
✅ School Status Management
✅ Search and Filter Schools
✅ Private Bookset toggle + unique URL copy/WhatsApp share

