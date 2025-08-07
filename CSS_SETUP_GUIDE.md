# CSS Setup and Usage Guide

## Current Configuration

Your Laravel project is now configured to use both **Bootstrap 5** and **Tailwind CSS** together without conflicts.

### What Was Fixed:

1. **Removed conflicting inline styles** from `app.blade.php`
2. **Properly configured Tailwind CSS** with `preflight: false` to prevent Bootstrap conflicts
3. **Added Bootstrap CDN** for components like grid system, buttons, etc.
4. **Set up proper asset compilation** with Vite

## How to Use Both Frameworks

### Option 1: Bootstrap Classes (Current register.blade.php)
```php
<!-- Bootstrap approach -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-7">
            <button class="btn btn-primary btn-lg">Register</button>
        </div>
    </div>
</div>
```

### Option 2: Tailwind Classes (register-tailwind.blade.php)
```php
<!-- Tailwind approach -->
<div class="max-w-6xl mx-auto py-12">
    <div class="flex">
        <div class="lg:w-7/12">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg">
                Register
            </button>
        </div>
    </div>
</div>
```

### Option 3: Mixed Approach (Recommended)
```php
<!-- Use Bootstrap for layout, Tailwind for utilities -->
<div class="container"> <!-- Bootstrap container -->
    <div class="row"> <!-- Bootstrap grid -->
        <div class="col-lg-6">
            <button class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition-colors">
                <!-- Tailwind utilities for styling -->
                Register
            </button>
        </div>
    </div>
</div>
```

## Development Workflow

1. **Always run the dev server** during development:
   ```bash
   npm run dev
   ```

2. **Build for production**:
   ```bash
   npm run build
   ```

3. **Check for conflicts**: If styles aren't applying:
   - Clear browser cache
   - Check browser developer tools for CSS conflicts
   - Ensure Vite dev server is running

## Best Practices

### Use Bootstrap For:
- Grid system (`container`, `row`, `col-*`)
- Complex components (modals, dropdowns, accordions)
- Form components (`form-control`, `btn`)

### Use Tailwind For:
- Spacing utilities (`p-4`, `m-2`, `space-y-4`)
- Colors (`bg-blue-600`, `text-gray-700`)
- Responsive design (`lg:w-7/12`, `md:flex`)
- Custom styling (`rounded-lg`, `shadow-lg`)

## File Structure

```
resources/
├── css/
│   └── app.css              # Main CSS file with Tailwind directives
├── js/
│   └── app.js               # Main JS file
└── views/
    ├── layouts/
    │   └── app.blade.php    # Main layout with both frameworks
    └── auth/tutor/
        ├── register.blade.php          # Bootstrap version
        └── register-tailwind.blade.php # Pure Tailwind version
```

## Troubleshooting

### Styles Not Loading:
1. Check if Vite dev server is running (`npm run dev`)
2. Clear browser cache (Ctrl+F5)
3. Check console for errors
4. Verify `@vite` directive is in your layout

### CSS Conflicts:
1. Use more specific selectors
2. Check browser developer tools
3. Use `!important` sparingly
4. Consider using Tailwind's `@apply` directive for custom components

### Build Issues:
1. Delete `node_modules` and run `npm install`
2. Delete `public/build` folder and run `npm run build`
3. Check `tailwind.config.js` content paths

## Examples

See the two register files for complete examples:
- `register.blade.php` - Bootstrap + Tailwind mixed approach
- `register-tailwind.blade.php` - Pure Tailwind approach

Both approaches are valid and will work without conflicts.
