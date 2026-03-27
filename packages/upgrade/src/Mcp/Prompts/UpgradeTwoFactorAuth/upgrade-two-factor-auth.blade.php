# Shopper Upgrade: Two-Factor Authentication

You are upgrading a project that uses Laravel Shopper to version 2.7+. This prompt covers the migration of the TwoFactorAuthenticatable trait to new SOLID interfaces.

## Pre-check

First, check if this upgrade is needed:

```bash
grep -r "TwoFactorAuthenticatable" app/ --include="*.php" -l 2>/dev/null
```

If **no results** are found, this upgrade does not apply. Skip this prompt entirely and inform the user.

## What Changed

The monolithic `TwoFactorAuthenticatable` trait has been split into focused interfaces and traits following the Interface Segregation Principle.

### Before (single trait)
```php
use Shopper\Traits\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use TwoFactorAuthenticatable;
}
```

### After (SOLID interfaces + traits)

The trait is replaced by two interfaces and their implementing traits:

**Interface 1: `HasStoreAuthentication`**
- `getStoreAuthenticationSecret(): ?string`
- `saveStoreAuthenticationSecret(?string $secret): void`
- `getStoreAuthenticationHolderName(): string`
- `getStoreAuthenticationQrCodeSvg(): string`
- `getStoreAuthenticationQrCodeUrl(): string`

**Interface 2: `HasStoreAuthenticationRecovery`**
- `getStoreAuthenticationRecoveryCodes(): ?array`
- `saveStoreAuthenticationRecoveryCodes(?array $codes): void`
- `replaceStoreAuthenticationRecoveryCode(string $code): void`

## Migration Steps

### Step 1: Update the User model

**Before:**
```php
use Shopper\Traits\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use TwoFactorAuthenticatable;
}
```

**After:**
```php
use Shopper\Contracts\HasStoreAuthentication;
use Shopper\Contracts\HasStoreAuthenticationRecovery;
use Shopper\Traits\InteractsWithStoreAuthentication;
use Shopper\Traits\InteractsWithStoreAuthenticationRecovery;

class User extends Authenticatable implements HasStoreAuthentication, HasStoreAuthenticationRecovery
{
    use InteractsWithStoreAuthentication;
    use InteractsWithStoreAuthenticationRecovery;
}
```

### Step 2: Update any code that type-hints ShopperUser for 2FA

**Before:**
```php
public function __invoke(ShopperUser $user): void
```

**After:**
```php
public function __invoke(HasStoreAuthentication $user): void
```

Search for these patterns:
```bash
grep -rn "ShopperUser.*2fa\|TwoFactorAuthenticatable\|two_factor_secret\|two_factor_recovery" app/ --include="*.php"
```

### Step 3: Verify database columns

The database columns remain the same (`two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`). No migration needed.
