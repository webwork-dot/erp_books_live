# Vendor Logo Upload Feature

## Overview
This feature allows administrators to upload custom logos for each vendor. The vendor's logo will be displayed in the sidebar and header instead of the default logo.

## Database Changes
Run the migration: `add_logo_to_vendors.sql`

This adds a `logo` column to the `erp_clients` table to store the logo file path.

## File Structure
- Logos are stored in: `uploads/vendors/logos/`
- File naming: `vendor_{vendor_id}_{timestamp}.{ext}`
- Allowed formats: GIF, JPG, JPEG, PNG, SVG
- Max file size: 2MB

## Features

### Admin Interface
1. **Add Vendor Form**: Optional logo upload field
2. **Edit Vendor Form**: 
   - Logo upload field
   - Preview of current logo (if exists)
   - Option to remove existing logo

### Display Logic
- **Sidebar**: Shows vendor logo in all logo variants (normal, small, dark, dark-small)
- **Header**: Shows vendor logo in header
- **Fallback**: If no logo is uploaded, displays default logo
- **Error Handling**: If logo file is missing, falls back to default logo

## Usage

### For Administrators
1. Go to **Admin → Vendors → Edit Vendor**
2. Scroll to **"Vendor Logo"** section
3. Click **"Choose File"** and select a logo image
4. Click **"Save Changes"**
5. The logo will appear in the vendor's dashboard immediately

### To Remove Logo
1. Check the **"Remove logo"** checkbox
2. Click **"Save Changes"**
3. Default logo will be restored

## Technical Details

### Upload Configuration
- **Path**: `./uploads/vendors/logos/`
- **Allowed Types**: `gif|jpg|jpeg|png|svg`
- **Max Size**: 2048 KB (2MB)
- **File Naming**: Auto-generated with vendor ID and timestamp
- **Overwrite**: Enabled (replaces existing logo for same vendor)

### File Cleanup
- Old logos are automatically deleted when:
  - A new logo is uploaded
  - Logo is removed via checkbox
- Files are stored with vendor ID in filename for easy identification

## Security
- File type validation (only images allowed)
- File size limit (2MB max)
- Secure file naming (prevents conflicts)
- Automatic cleanup of old files

## Notes
- Ensure `uploads/vendors/logos/` directory has write permissions (755 or 777)
- Recommended logo dimensions: 200x60px for best display
- SVG logos work best for scalability
- PNG with transparency recommended for dark backgrounds

