# Shopper Upgrade: Filament Schemas Migration

You are upgrading a project that uses Laravel Shopper to version 2.7+. This prompt covers the migration from Filament's `InteractsWithForms` to `InteractsWithSchemas`.

## Pre-check

First, check if this upgrade is needed:

```bash
grep -r "InteractsWithForms\|HasForms" app/Shopper/ app/Livewire/ --include="*.php" -l 2>/dev/null
```

If **no results** are found, this upgrade does not apply. The developer has no custom Filament components in `app/Shopper/` or `app/Livewire/`. Skip this prompt entirely and inform the user.

Note: `InteractsWithForms` still works in Filament 4.x — it's not removed, just deprecated. This migration is recommended but not urgent.

## What Changed

Filament 4.7+ introduced `InteractsWithSchemas` as the replacement for `InteractsWithForms`. Shopper 2.7 has migrated all its internal components to use Schemas.

If a developer has custom components in `app/Shopper/` that extend Shopper components or use `InteractsWithForms` directly, they should migrate for consistency.

## Migration Steps

### Step 1: Update trait usage

**Before:**
```php
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class MyComponent extends Component implements HasForms
{
    use InteractsWithForms;
}
```

**After:**
```php
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

class MyComponent extends Component implements HasSchemas
{
    use InteractsWithSchemas;
}
```

### Step 2: Update form method signature

**Before:**
```php
use Filament\Forms\Form;

public function form(Form $form): Form
```

**After:**
```php
use Filament\Schemas\Schema;

public function form(Schema $schema): Schema
```

### Step 3: Update schema builder imports

**Before:**
```php
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
```

**After:**
```php
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
```

Note: Form field components (`TextInput`, `Select`, `Toggle`, etc.) stay in `Filament\Forms\Components`. Only layout/schema components move.

### Step 4: Search and replace

```bash
grep -rn "InteractsWithForms\|HasForms\|Filament\\\\Forms\\\\Form" app/Shopper/ app/Livewire/ --include="*.php"
```

Apply changes file by file. Do not batch — verify each file compiles after the change.
