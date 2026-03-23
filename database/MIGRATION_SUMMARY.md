# Database Migration Summary

## SQL Files Created

### 1. `add_is_individual_is_set_to_products.sql`
Adds `is_individual` and `is_set` columns to:
- `erp_notebooks`
- `erp_textbooks`
- `erp_uniforms`
- `erp_stationery`

**To run:**
```sql
SOURCE erp-system/database/add_is_individual_is_set_to_products.sql;
```

### 2. `add_is_main_to_image_tables.sql`
Adds `is_main` column to image tables:
- `erp_notebook_images`
- `erp_textbook_images`
- `erp_uniform_images`
- `erp_stationery_images`

**To run:**
```sql
SOURCE erp-system/database/add_is_main_to_image_tables.sql;
```

### 3. `create_erp_products_table.sql`
Creates unified `erp_products` table for all non-bookset products, based on the old `products` schema from `ganeshbooks_dbc` with multi-vendor and type support.

**To run (per vendor database):**
```sql
SOURCE erp-system/database/create_erp_products_table.sql;
```

### 4. Unified products data migration

`application/controllers/Erp_admin/Product_migration.php` copies data from:
- `erp_textbooks`
- `erp_notebooks`
- `erp_stationery`
- `erp_uniforms`
- `erp_individual_products`

into `erp_products` with `legacy_table` / `legacy_id` mappings.

**To run (CLI recommended, after creating `erp_products`):**
```bash
php index.php Erp_admin/Product_migration/run
```

## Implementation Status

### ✅ Completed
1. SQL migrations created for all tables
2. Notebooks add/edit forms updated with:
   - `is_individual` and `is_set` checkboxes
   - Image drag-and-drop sorting
   - Main image selection
3. Image sortable JavaScript library created (`assets/js/image-sortable.js`)

### 🔄 In Progress
- Textbooks forms (need to locate)
- Uniforms forms (updating)
- Stationery forms (updating)

## Features Added

### Checkboxes
- **Is Individual**: Checkbox to mark product as individual
- **Is Set**: Checkbox to mark product as set
- Both can be checked simultaneously if needed

### Image Management
- **Drag and Drop**: Reorder images by dragging
- **Main Image Selection**: Click "Set as Main" button on any image
- **Visual Feedback**: Main image button shows green "Main" label
- **Image Removal**: Remove individual images with delete button

## Next Steps

1. Run the SQL migrations on your database
2. Update controllers to handle:
   - `is_individual` and `is_set` fields
   - Image ordering from `image_order` hidden input
   - Main image from `main_image_index` or `main_image_id` hidden input
3. Update image upload logic to save `image_order` and `is_main` values

