# SVG Icons Guide

This guide explains how to use the new SVG icon system in the Personal Finance Tracker application.

## Overview

Instead of having inline SVGs scattered throughout the code, we now have a centralized system using Laravel Blade components. This makes the code cleaner, more maintainable, and easier to update.

## Available Icons

All icons are stored in `resources/views/components/icons/` and include:

-   `filter.blade.php` - Filter/sort icon
-   `image.blade.php` - Image/photo icon
-   `three-dots.blade.php` - Three dots menu icon
-   `edit.blade.php` - Edit/pencil icon
-   `delete.blade.php` - Delete/trash icon
-   `plus-circle.blade.php` - Plus in circle icon
-   `plus.blade.php` - Simple plus icon
-   `check.blade.php` - Check mark icon
-   `x.blade.php` - X/close icon

## How to Use

### Method 1: Blade Components (Recommended)

Use the `<x-icons.icon-name>` syntax:

```blade
<!-- Basic usage -->
<x-icons.filter />

<!-- With custom size -->
<x-icons.filter width="24" height="24" />

<!-- With CSS classes -->
<x-icons.filter class="text-white" />

<!-- With additional attributes -->
<x-icons.filter width="16" height="16" class="me-2" title="Filter options" />
```

### Method 2: Blade Directives

Use the `@icon` directive:

```blade
<!-- General icon directive -->
@icon('filter')

<!-- Specific icon directives -->
@iconFilter('width="24" height="24" class="text-white"')
@iconImage('width="16" height="16"')
@iconThreeDots('width="16" height="16"')
@iconEdit('width="16" height="16" class="me-2"')
@iconDelete('width="16" height="16" class="me-2"')
@iconPlusCircle('width="48" height="48" class="mb-3"')
```

## Adding New Icons

1. Create a new Blade component file in `resources/views/components/icons/`
2. Name it with a descriptive name (e.g., `settings.blade.php`)
3. Use this template:

```blade
<svg width="{{ $width ?? 16 }}" height="{{ $height ?? 16 }}" fill="currentColor" viewBox="0 0 16 16" {{ $attributes }}>
    <!-- Your SVG paths here -->
</svg>
```

4. Add a directive to `AppServiceProvider.php` if needed:

```php
Blade::directive('iconSettings', function ($expression) {
    return "<?php echo '<x-icons.settings' . ({$expression} ? ' ' . {$expression} : '') . ' />'; ?>";
});
```

## Benefits

-   **Cleaner Code**: No more long inline SVG strings
-   **Reusability**: Use the same icon multiple times with different sizes/styles
-   **Maintainability**: Update an icon once, it updates everywhere
-   **Performance**: SVGs are cached and optimized
-   **Consistency**: All icons follow the same structure and naming convention

## Examples from the Codebase

### Dashboard Filter Button

```blade
<x-icons.filter width="22" height="22" class="text-white" />
```

### Transaction Image Indicator

```blade
<x-icons.image width="16" height="16" class="text-muted" />
```

### Action Menu

```blade
<x-icons.three-dots width="16" height="16" />
```

### Edit Action

```blade
<x-icons.edit width="16" height="16" class="me-2 flex-shrink-0" />
```

### Delete Action

```blade
<x-icons.delete width="16" height="16" class="me-2 flex-shrink-0" />
```

### Empty State

```blade
<x-icons.plus-circle width="48" height="48" class="mb-3" />
```

## Tips

-   Always use `currentColor` for the fill attribute to inherit text color
-   Set default width/height in the component but allow overriding
-   Use CSS classes for styling (color, spacing, etc.)
-   Keep viewBox consistent for similar icon types
-   Use descriptive names for new icons
