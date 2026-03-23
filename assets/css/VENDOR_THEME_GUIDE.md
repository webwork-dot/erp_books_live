# Vendor Complete Theme Customization Guide

This guide explains how to customize the **complete theme** for each vendor. Each color option changes the entire vendor dashboard, not just the sidebar.

## File Location
All theme customizations are in: `erp-system/assets/css/vendor-sidebar-colors.css`

## What Gets Themed

When you select a sidebar color for a vendor, it changes:
- ✅ **Sidebar** - Background and text colors (removes background images, uses solid colors)
- ✅ **Buttons** - Primary button colors match the theme
- ✅ **Badges** - Badge colors match the theme
- ✅ **Cards** - Card header accents use theme colors
- ✅ **Welcome Banner** - Banner gradient uses theme colors
- ✅ **Icons** - Menu icon colors
- ✅ **All UI Elements** - Complete theme consistency

## Available Themes

1. **Color 1 (sidebarbg1)** - Purple (#7539ff)
2. **Color 2 (sidebarbg2)** - Blue (#3550DC)
3. **Color 3 (sidebarbg3)** - Green (#22C55E)
4. **Color 4 (sidebarbg4)** - Orange (#F59E0B)
5. **Color 5 (sidebarbg5)** - Red (#DC2626)
6. **Color 6 (sidebarbg6)** - Dark Gray (#1F2937)

## How to Customize a Theme

Each theme uses CSS variables. To customize, edit the variables for that theme:

```css
[data-sidebarbg=sidebarbg1] {
  --vendor-primary: #7539ff;        /* Main theme color */
  --vendor-primary-dark: #5B2ECC;   /* Darker shade for hover */
  --vendor-primary-light: #F3EFFF;  /* Light shade for backgrounds */
  --vendor-sidebar-bg: #7539ff;     /* Sidebar background */
  --vendor-sidebar-text: #ffffff;   /* Sidebar text color */
  --vendor-sidebar-hover: rgba(255, 255, 255, 0.1);  /* Hover overlay */
  --vendor-sidebar-active: rgba(255, 255, 255, 0.15); /* Active item overlay */
}
```

## Customizing Individual Elements

### Change Sidebar Background
```css
[data-sidebarbg=sidebarbg1] #two-col-sidebar .sidebar {
  background: #YOUR_COLOR !important;
}
```

### Change Button Colors
```css
[data-sidebarbg=sidebarbg1] .btn-primary {
  background-color: var(--vendor-primary) !important;
}
```

### Change Badge Colors
```css
[data-sidebarbg=sidebarbg1] .badge-primary {
  background-color: var(--vendor-primary) !important;
}
```

### Change Welcome Banner
```css
[data-sidebarbg=sidebarbg1] .welcome-banner {
  background: linear-gradient(135deg, var(--vendor-primary) 0%, var(--vendor-primary-dark) 100%) !important;
}
```

## Important Notes

1. **Background Images Removed**: The system now uses solid colors instead of background images for better customization
2. **CSS Variables**: Use the `--vendor-*` variables for consistency
3. **!important**: All theme styles use `!important` to ensure they override default styles
4. **Complete Theme**: Changes affect the entire dashboard, not just the sidebar

## Testing Your Changes

1. Edit the CSS file
2. Save the file
3. Hard refresh the browser (Ctrl+F5)
4. Log in as the vendor to see the changes

## Color Format

You can use:
- **Hex colors**: `#7539ff`
- **RGB colors**: `rgb(117, 57, 255)`
- **RGBA colors**: `rgba(117, 57, 255, 0.9)` (with transparency)
- **Named colors**: `white`, `black`, `blue`, etc.

## Example: Creating a Custom Teal Theme

```css
[data-sidebarbg=sidebarbg2] {
  --vendor-primary: #14B8A6;        /* Teal */
  --vendor-primary-dark: #0D9488;  /* Darker teal */
  --vendor-primary-light: #E0F2F1;  /* Light teal */
  --vendor-sidebar-bg: #14B8A6;
  --vendor-sidebar-text: #ffffff;
  --vendor-sidebar-hover: rgba(255, 255, 255, 0.1);
  --vendor-sidebar-active: rgba(255, 255, 255, 0.15);
}
```

## Need Help?

- Use online color pickers: https://htmlcolorcodes.com/
- Test color contrast: https://webaim.org/resources/contrastchecker/
- Preview in browser: Use Developer Tools (F12) to test changes in real-time

