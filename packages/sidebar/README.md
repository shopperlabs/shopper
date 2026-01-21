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

## License

This package is licensed under MIT. You are free to use it in personal and commercial projects.
