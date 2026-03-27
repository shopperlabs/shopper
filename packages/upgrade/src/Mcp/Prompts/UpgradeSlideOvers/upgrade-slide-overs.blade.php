# Shopper Upgrade: Slide-Over Components

You are upgrading a project that uses Laravel Shopper to version 2.7+. This prompt covers the migration of custom slide-over components.

## Pre-check

First, check if this upgrade is needed:

```bash
grep -r "Shopper\\\\Livewire\\\\Components\\\\SlideOverComponent\|Shopper\\\\Livewire\\\\Components\\\\SlideOverPanel\|closePanelWithEvents" app/Shopper/ --include="*.php" -l 2>/dev/null
```

If **no results** are found, this upgrade does not apply — the developer has no custom slide-over components. Skip this prompt entirely and inform the user.

## What Changed

Shopper's internal `SlideOverComponent` and `SlideOverPanel` classes have been removed and replaced by the `laravelcm/livewire-slide-overs` package.

### Deleted classes
- `Shopper\Livewire\Components\SlideOverComponent` — removed
- `Shopper\Livewire\Components\SlideOverPanel` — removed

### New base class
- `Laravelcm\LivewireSlideOvers\SlideOverComponent`

## Migration Steps

### Step 1: Update imports

**Before:**
```php
use Shopper\Livewire\Components\SlideOverComponent;

class MyCustomSlideOver extends SlideOverComponent
```

**After:**
```php
use Laravelcm\LivewireSlideOvers\SlideOverComponent;

class MyCustomSlideOver extends SlideOverComponent
```

### Step 2: Update panel configuration methods

**Before:**
```php
public static function panelMaxWidth(): string
{
    return '2xl';
}
```

**After:** Same — this method is preserved in the new package.

### Step 3: Update close panel calls

**Before:**
```php
$this->dispatch('closePanel');
// or
$this->closePanelWithEvents(['eventName']);
```

**After:**
```php
$this->closePanel();
```

The `closePanelWithEvents()` method no longer exists. Dispatch events separately:
```php
$this->dispatch('eventName');
$this->closePanel();
```

### Step 4: Update openPanel dispatch

**Before:**
```php
$this->dispatch('openPanel', component: 'my-component', arguments: [...]);
```

**After:** Same — the event name and signature are unchanged.

### Step 5: Update Blade views

If your custom slide-over has a Blade view that uses the old component:

@verbatim
**Before:**
```blade
<x-shopper::slide-over-panel>
```

**After:**
Use the new wrapper:
```blade
<x-shopper::slideover-card>
```
@endverbatim

### Step 6: Config cleanup

The `side-panel` Livewire component registration has been removed from config. If you referenced it directly, remove the reference.

## Search patterns

Find all files that need updating:
```bash
grep -rn "Shopper\\\\Livewire\\\\Components\\\\SlideOverComponent\|Shopper\\\\Livewire\\\\Components\\\\SlideOverPanel\|closePanelWithEvents" app/Shopper/ --include="*.php"
```
