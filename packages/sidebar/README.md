# Laravel Sidebar

A headless sidebar package for Laravel that allows you to create and manage sidebars with full customization support.

Originally forked from [SpartnerNL/Laravel-Sidebar](https://github.com/SpartnerNL/Laravel-Sidebar).

## Installation

Require this package in your project:

```bash
composer require shopper/sidebar
```

Publish the config file:

```bash
php artisan vendor:publish --provider="Shopper\Sidebar\SidebarServiceProvider" --tag="sidebar-config"
```

Publish the views (optional, for customization):

```bash
php artisan vendor:publish --provider="Shopper\Sidebar\SidebarServiceProvider" --tag="sidebar-views"
```

Add the middleware to your route group or `bootstrap/app.php`:

```php
// In a route group
Route::middleware(['web', \Shopper\Sidebar\Middleware\ResolveSidebars::class])
    ->group(function () {
        // Your routes
    });

// Or in bootstrap/app.php (Laravel 11+)
->withMiddleware(function (Middleware $middleware) {
    $middleware->appendToGroup('web', [
        \Shopper\Sidebar\Middleware\ResolveSidebars::class,
    ]);
})
```

## Configuration

The config file (`config/sidebar.php`) allows you to customize:

```php
return [
    // Caching method: null, 'static', or 'user-based'
    'cache' => [
        'method' => null,
        'duration' => 1440,
    ],

    // Sidebar dimensions (CSS values)
    'width' => '16.5rem',
    'collapsed_width' => '4.5rem',

    // Responsive breakpoint (pixels)
    'breakpoint' => 1024,

    // Allow sidebar to be collapsed on desktop
    'collapsible' => true,
];
```

## Creating a Sidebar

### 1. Create a Sidebar Class

```php
<?php

namespace App\Sidebar;

use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Contracts\Sidebar;

class AdminSidebar implements Sidebar
{
    public function __construct(private Menu $menu) {}

    public function build(): void
    {
        $this->menu->group('Main', function ($group) {
            $group->item('Dashboard')
                ->url('/dashboard')
                ->icon('heroicon-o-home')
                ->weight(1);

            $group->item('Users')
                ->url('/users')
                ->icon('heroicon-o-users')
                ->weight(2)
                ->items(function ($items) {
                    $items->item('All Users')->url('/users');
                    $items->item('Create User')->url('/users/create');
                });
        });

        $this->menu->group('Settings', function ($group) {
            $group->item('General')
                ->url('/settings')
                ->icon('heroicon-o-cog');
        });
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
```

### 2. Register the Sidebar

Create an event listener or use the `SidebarBuilder` event:

```php
<?php

namespace App\Providers;

use App\Sidebar\AdminSidebar;
use Illuminate\Support\ServiceProvider;
use Shopper\Sidebar\SidebarManager;

class SidebarServiceProvider extends ServiceProvider
{
    public function boot(SidebarManager $manager): void
    {
        $manager->register(AdminSidebar::class);
    }
}
```

## Usage

The sidebar package provides two ways to render your sidebar:

### Option 1: Livewire Component (Simple)

Use the built-in Livewire component for a ready-to-use sidebar with Alpine.js state management:

```blade
@livewire('sidebar', [
    'sidebarClass' => \App\Sidebar\AdminSidebar::class,
    'class' => 'your-sidebar-classes',
    'collapsible' => true,
])
```

The Livewire component includes:
- Alpine.js store integration (`$store.sidebar`)
- Collapse/expand functionality
- Responsive behavior (mobile/desktop)
- LocalStorage persistence

### Option 2: Blade Components (Full Customization)

For complete control over the sidebar layout, use the `SidebarRenderer` directly in your Blade views:

```blade
{{-- layouts/sidebar.blade.php --}}
<aside
    class="sidebar"
    x-bind:class="{ 'sidebar-collapsed': $store.sidebar.isCollapsed }"
>
    {{-- Custom header with branding --}}
    <div class="sidebar-header">
        <img src="/logo.png" alt="Logo" />
        <span x-show="!$store.sidebar.isCollapsed">My App</span>
    </div>

    {{-- Sidebar menu (rendered by the package) --}}
    <nav class="sidebar-nav">
        {!! $sidebar !!}
    </nav>

    {{-- Custom footer --}}
    <div class="sidebar-footer">
        <a href="/settings">Settings</a>
    </div>
</aside>
```

Both options use the same underlying `SidebarRenderer`, which renders:
- `item.blade.php` - Individual menu items
- `group.blade.php` - Menu groups
- `badge.blade.php` - Item badges
- `append.blade.php` - Appended content

## Alpine.js Store

The sidebar uses an Alpine.js store for state management. Initialize it in your JavaScript:

```javascript
import Alpine from 'alpinejs'
import sidebarStore from '@shopper/sidebar/stores/sidebar'

Alpine.store('sidebar', sidebarStore())
Alpine.store('sidebar').init()
```

### Store API

```javascript
// State
$store.sidebar.isOpen        // Mobile: sidebar visibility
$store.sidebar.isCollapsed   // Desktop: collapsed state
$store.sidebar.collapsible   // Whether collapse is enabled

// Methods
$store.sidebar.toggle()      // Smart toggle (mobile: open/close, desktop: collapse/expand)
$store.sidebar.open()        // Open sidebar (mobile)
$store.sidebar.close()       // Close sidebar (mobile)
$store.sidebar.collapse()    // Collapse sidebar (desktop)
$store.sidebar.expand()      // Expand sidebar (desktop)
$store.sidebar.toggleCollapse()

// Group management
$store.sidebar.toggleGroup(label)
$store.sidebar.isGroupCollapsed(label)
```

## CSS Variables

The sidebar dimensions are available as CSS variables:

```css
:root {
    --sidebar-width: 16.5rem;
    --sidebar-collapsed-width: 4.5rem;
}
```

Inject them in your layout using the helper functions:

```blade
<style>
    :root {
        --sidebar-width: {{ \Shopper\Sidebar\sidebar_width() }};
        --sidebar-collapsed-width: {{ \Shopper\Sidebar\sidebar_collapsed_width() }};
    }
</style>

<body
    data-sidebar-breakpoint="{{ \Shopper\Sidebar\sidebar_breakpoint() }}"
    data-sidebar-collapsible="{{ \Shopper\Sidebar\sidebar_is_collapsible() ? 'true' : 'false' }}"
>
```

## Helper Functions

The package provides namespaced helper functions:

```php
\Shopper\Sidebar\sidebar_config($key, $default)  // Get config value
\Shopper\Sidebar\sidebar_width()                  // Get sidebar width
\Shopper\Sidebar\sidebar_collapsed_width()        // Get collapsed width
\Shopper\Sidebar\sidebar_breakpoint()             // Get responsive breakpoint
\Shopper\Sidebar\sidebar_is_collapsible()         // Check if collapsible
```

## Menu Item Options

```php
$group->item('Label')
    ->url('/path')                    // URL
    ->route('route.name')             // Or use route name
    ->icon('heroicon-o-home')         // Icon (Blade UI Kit format)
    ->weight(1)                       // Sort order
    ->badge('New')                    // Add badge
    ->newTab()                        // Open in new tab
    ->authorize(fn() => auth()->check()) // Authorization callback
    ->items(function ($items) {       // Sub-items
        $items->item('Sub Item')->url('/sub');
    });
```

## Breadcrumbs

The package ships a breadcrumb system that derives the current trail from the active sidebar item and lets deeper pages push their own crumbs. It works standalone on any Laravel + Livewire project.

### How it works

On each request:

1. The sidebar's **active item** is auto-detected (based on the current URL and the item's `url()` / `isActiveWhen()`).
2. The top-level active item becomes the first crumb, with a dropdown listing its **siblings** in the same group.
3. If the active item has nested active children, each level is appended as an extra crumb.
4. Pages can **push** additional crumbs through the `WithBreadcrumbs` trait (e.g. the name of an edited entity).

### Render the trail

Drop the pre-built partial anywhere in your layout:

```blade
@include(config('sidebar.breadcrumbs.view', 'sidebar::breadcrumbs'))
```

The default view ships with semantic HTML + Alpine.js behaviour and is styled via `sh-breadcrumb-*` class hooks — bring your own CSS (Tailwind, Bootstrap, vanilla).

### Override the view

Point the config to your own view for full visual customization:

```php
// config/sidebar.php
'breadcrumbs' => [
    'view' => 'my-app.breadcrumbs',
],
```

Inside the view, call the helper to retrieve the trail:

```blade
@php($breadcrumbs = \Shopper\Sidebar\sidebar_breadcrumbs())
```

Or publish the default view and tweak it:

```bash
php artisan vendor:publish --provider="Shopper\Sidebar\SidebarServiceProvider" --tag="sidebar-views"
```

### Push crumbs from a page

Use the `WithBreadcrumbs` trait on any Livewire component:

```php
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;

class ProductEdit extends Component
{
    use WithBreadcrumbs;

    public Product $product;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: $this->product->name),
        ];
    }
}
```

If the page lives under `/products/{product}/edit`, the header renders:
`Products ▾ / Nike Air Max 90` — the first crumb comes from the sidebar, the second from your push.

### Value objects

```php
new Breadcrumb(
    text: 'Products',                            // required label
    url: '/admin/products',                      // optional link (sanitized)
    icon: 'phosphor-package',                    // optional blade-ui-kit icon
    links: [                                     // optional dropdown siblings
        new BreadcrumbLink(
            text: 'Brands',
            url: '/admin/brands',
            icon: 'phosphor-tag',
            spa: true,                           // wire:navigate on the link
        ),
    ],
    spa: true,                                   // wire:navigate on the crumb
);
```

URLs are sanitized at construction: `javascript:`, `data:`, and other unsafe schemes fall back to `null` (Breadcrumb) or `#` (BreadcrumbLink). Safe schemes are `http://`, `https://`, plus relative (`/`), fragment (`#`), and query (`?`) URLs.

### Class hooks

The default view exposes these hooks for custom CSS — no Tailwind imposed:

| Class                             | Element                                       |
|-----------------------------------|-----------------------------------------------|
| `.sh-breadcrumb`                  | `<nav>` wrapper                               |
| `.sh-breadcrumb-list`             | `<ol>` list                                   |
| `.sh-breadcrumb-item`             | each `<li>`                                   |
| `.sh-breadcrumb-separator`        | the `/` between crumbs                        |
| `.sh-breadcrumb-link`             | clickable crumb label                         |
| `.sh-breadcrumb-current`          | active (last) crumb                           |
| `.sh-breadcrumb-group`            | wrapper around label + chevron                |
| `.sh-breadcrumb-toggle-wrapper`   | relative container for the chevron + dropdown |
| `.sh-breadcrumb-toggle`           | the chevron button                            |
| `.sh-breadcrumb-toggle-open`      | added by Alpine when the dropdown is open     |
| `.sh-breadcrumb-chevron`          | chevron SVG                                   |
| `.sh-breadcrumb-dropdown`         | dropdown panel                                |
| `.sh-breadcrumb-dropdown-current` | current section inside the dropdown           |
| `.sh-breadcrumb-dropdown-divider` | separator                                     |
| `.sh-breadcrumb-dropdown-item`    | each sibling link                             |
| `.sh-breadcrumb-dropdown-icon`    | icon inside a dropdown item                   |

### Helper

```php
\Shopper\Sidebar\sidebar_breadcrumbs(): array  // returns list<Breadcrumb>
```

Assembles the full trail by combining the auto-detected crumbs with what pages have pushed. Returns an empty array if no sidebar matches and no page pushed anything.

### Registry

Behind the scenes, crumbs pushed through the trait are stored on the `Breadcrumbs` registry, bound as a `scoped()` singleton (fresh per request — Octane safe). You can resolve it directly for advanced flows:

```php
resolve(\Shopper\Sidebar\Breadcrumbs\Breadcrumbs::class)
    ->push(new Breadcrumb(text: 'Custom section'));
```

## License

This package is licensed under MIT. You are free to use it in personal and commercial projects.
