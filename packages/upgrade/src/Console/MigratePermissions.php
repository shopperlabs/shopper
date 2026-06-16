<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

#[AsCommand('shopper:upgrade:permissions')]
final class MigratePermissions extends Command
{
    /**
     * 2.x action prefix => 3.x action suffix.
     */
    private const array ACTIONS = [
        'browse' => 'browse',
        'read' => 'read',
        'edit' => 'edit',
        'add' => 'create',
        'delete' => 'delete',
    ];

    /**
     * [2.x underscore item, 3.x dotted base, 3.x group].
     *
     * @var array<int, array{string, string, string}>
     */
    private const array RESOURCES = [
        ['brands', 'brands', 'catalog'],
        ['categories', 'categories', 'catalog'],
        ['collections', 'collections', 'catalog'],
        ['products', 'products', 'products'],
        ['product_variants', 'products.variants', 'products'],
        ['attributes', 'attributes', 'products'],
        ['suppliers', 'suppliers', 'products'],
        ['tags', 'tags', 'products'],
        ['reviews', 'reviews', 'products'],
        ['orders', 'orders', 'sales'],
        ['discounts', 'discounts', 'sales'],
        ['customers', 'customers', 'customers'],
        ['inventories', 'inventories', 'inventory'],
    ];

    /**
     * Fixed 2.x name => 3.x dotted name (all in the `system` group).
     */
    private const array SYSTEM = [
        'access_dashboard' => 'system.dashboard',
        'access_setting' => 'system.settings',
        'view_users' => 'system.users',
    ];

    protected $signature = 'shopper:upgrade:permissions
        {--dry-run : Show what would be renamed without modifying data}
        {--force : Skip confirmation prompt}';

    protected $description = 'Rename admin permissions from underscore format (read_orders) to dot notation (orders.read) and regroup them';

    public function handle(): int
    {
        if ($this->hasAlreadyRun() && ! $this->option('force')) {
            warning('Permissions have already been migrated to dot notation. Use --force to run it again.');

            return self::SUCCESS;
        }

        $present = $this->presentMappings();

        if ($present === []) {
            info('No underscore-format permissions found. Nothing to migrate.');

            return self::SUCCESS;
        }

        table(
            headers: ['2.x name', '3.x name', 'Group'],
            rows: array_map(
                fn (array $mapping): array => [$mapping['old'], $mapping['new'], $mapping['group']],
                $present,
            ),
        );

        if ($this->option('dry-run')) {
            warning('Dry run — no data was modified. Run without --dry-run to apply.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! confirm('Rename these permissions to dot notation?', default: false)) {
            warning('Upgrade cancelled.');

            return self::SUCCESS;
        }

        [$renamed, $skipped] = spin(
            callback: fn (): array => $this->apply($present),
            message: 'Renaming permissions...',
        );

        info("{$renamed} permission(s) renamed to dot notation.");

        if ($skipped > 0) {
            warning("{$skipped} permission(s) skipped because the dot-notation name already existed.");
        }

        $this->markAsRun();

        return self::SUCCESS;
    }

    /**
     * The full 2.x => 3.x mapping, regardless of what exists in the database.
     *
     * @return array<int, array{old: string, new: string, group: string}>
     */
    private function map(): array
    {
        $map = [];

        foreach (self::SYSTEM as $old => $new) {
            $map[] = ['old' => $old, 'new' => $new, 'group' => 'system'];
        }

        foreach (self::RESOURCES as [$item, $base, $group]) {
            foreach (self::ACTIONS as $oldAction => $newAction) {
                $map[] = [
                    'old' => $oldAction.'_'.$item,
                    'new' => $base.'.'.$newAction,
                    'group' => $group,
                ];
            }
        }

        return $map;
    }

    /**
     * Only the mappings whose 2.x permission actually exists in the database.
     *
     * @return array<int, array{old: string, new: string, group: string}>
     */
    private function presentMappings(): array
    {
        $existing = DB::table($this->permissionsTable())->pluck('name')->all();

        return array_values(array_filter(
            $this->map(),
            fn (array $mapping): bool => in_array($mapping['old'], $existing, true),
        ));
    }

    /**
     * @param  array<int, array{old: string, new: string, group: string}>  $mappings
     * @return array{0: int, 1: int} renamed and skipped counts
     */
    private function apply(array $mappings): array
    {
        return DB::transaction(function () use ($mappings): array {
            $renamed = 0;
            $skipped = 0;

            foreach ($mappings as $mapping) {
                if (DB::table($this->permissionsTable())->where('name', $mapping['new'])->exists()) {
                    $skipped++;

                    continue;
                }

                $renamed += DB::table($this->permissionsTable())
                    ->where('name', $mapping['old'])
                    ->update([
                        'name' => $mapping['new'],
                        'group_name' => $mapping['group'],
                    ]);
            }

            return [$renamed, $skipped];
        });
    }

    private function permissionsTable(): string
    {
        return config('permission.table_names.permissions', 'permissions');
    }

    private function hasAlreadyRun(): bool
    {
        return DB::table(shopper_table('settings'))
            ->where('key', 'permissions_dot_notation_applied')
            ->exists();
    }

    private function markAsRun(): void
    {
        DB::table(shopper_table('settings'))->insert([
            'key' => 'permissions_dot_notation_applied',
            'value' => json_encode(now()->toIso8601String()),
        ]);
    }
}
