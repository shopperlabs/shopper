# Shopper Upgrade

The upgrade assistant for [Shopper](https://shopperphp.com). It moves a 2.x store to 3.x:
database migrations, class renames in your application code, and the data migrations the new
version expects. Remove it once the upgrade is done.

## Requirements

- PHP 8.3+
- Laravel 12.x or 13.x
- a Shopper 2.x application, with `shopper/framework` already bumped to `^3.0`

## Installation

```bash
composer require shopper/upgrade --dev
```

## Usage

Commit your work and back up the database, then run the full upgrade:

```bash
php artisan shopper:upgrade
```

It runs six steps in order and asks for confirmation before touching anything:

1. `migrate`
2. `shopper:upgrade:rector`, applies the 2.x to 3.x class renames to the paths given with
   `--path=app` (comma separated)
3. `shopper:upgrade:permissions`, renames admin permissions from `read_orders` to `orders.read`
   and regroups them
4. `shopper:stock:reconcile --fix`, rebuilds the stock snapshot from the inventory ledger
5. `shopper:link --force`, links the storage and the panel assets
6. `shopper:files:secure`, moves the files of virtual products to the private disk

Every step is also available on its own. `shopper:upgrade:rector --dry-run` previews the code
changes without writing a file, and `--force` skips the confirmations for CI.

`shopper:upgrade:fix-zero-decimal-currencies` is not part of the chain. It divides every
zero-decimal amount (XAF, JPY...) by 100, so it only applies to a store whose amounts were all
written before 2.7 and never fixed. A store that wrote amounts on 2.7 or later holds correct rows
the command would divide too. It runs once and refuses to run again.

## Documentation

Read the [upgrade guide](https://docs.shopperphp.com) for the breaking changes of 3.x and
the manual steps the assistant cannot do for you.
