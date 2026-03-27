<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Console;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

final class FixZeroDecimalCurrency extends Command
{
    protected $signature = 'shopper:fix-zero-decimal-currencies
        {--dry-run : Show what would be changed without modifying data}
        {--force : Skip confirmation prompt}';

    protected $description = 'Fix monetary values for zero-decimal currencies that were incorrectly multiplied by 100 in previous versions';

    /** @var array<string, int> */
    private array $results = [];

    public function handle(): int
    {
        $currencies = DB::table(shopper_table('currencies'))
            ->whereIn('code', zero_decimal_currencies())
            ->pluck('id', 'code');

        if ($currencies->isEmpty()) {
            info('No zero-decimal currencies found in your database. Nothing to fix.');

            return self::SUCCESS;
        }

        info('Zero-decimal currencies detected: '.$currencies->keys()->implode(', '));

        $counts = $this->analyze($currencies);

        if (array_sum($counts) === 0) {
            info('All tables are clean — no rows need fixing.');

            return self::SUCCESS;
        }

        table(
            headers: ['Table', 'Rows to fix'],
            rows: collect($counts)
                ->map(fn (int $count, string $name): array => [$name, (string) $count])
                ->values()
                ->all(),
        );

        if ($this->option('dry-run')) {
            warning('Dry run — no data was modified. Run without --dry-run to apply.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! confirm('Apply these fixes to your database?', default: false)) {
            warning('Upgrade cancelled.');

            return self::SUCCESS;
        }

        spin(
            callback: fn () => $this->applyFixes($currencies),
            message: 'Fixing zero-decimal currency values...',
        );

        table(
            headers: ['Table', 'Rows fixed', 'Status'],
            rows: collect($this->results)
                ->map(fn (int $count, string $name): array => [
                    $name,
                    (string) $count,
                    $count > 0 ? '✓ Fixed' : '— Skipped',
                ])
                ->values()
                ->all(),
        );

        info('Zero-decimal currency data fixed successfully.');

        return self::SUCCESS;
    }

    /**
     * @param  Collection<string, int>  $currencies
     * @return array<string, int>
     */
    private function analyze(Collection $currencies): array
    {
        $currencyIds = $currencies->values()->all();
        $currencyCodes = $currencies->keys()->all();
        $orderIds = $this->orderIdsForCurrencies($currencyCodes);
        $zoneIds = $this->zoneIdsForCurrencies($currencyIds);
        $cartLineIds = $this->cartLineIdsForCurrencies($currencyCodes);

        return [
            'prices' => DB::table(shopper_table('prices'))
                ->whereIn('currency_id', $currencyIds)
                ->count(),
            'orders' => DB::table(shopper_table('orders'))
                ->whereIn('currency_code', $currencyCodes)
                ->count(),
            'order_items' => $orderIds->isNotEmpty()
                ? DB::table(shopper_table('order_items'))
                    ->whereIn('order_id', $orderIds)
                    ->count()
                : 0,
            'carrier_options' => DB::table(shopper_table('carrier_options'))
                ->whereIn('zone_id', $zoneIds)
                ->count(),
            'cart_lines' => $cartLineIds->count(),
            'cart_line_tax_lines' => $cartLineIds->isNotEmpty()
                ? DB::table(shopper_table('cart_line_tax_lines'))
                    ->whereIn('cart_line_id', $cartLineIds)
                    ->count()
                : 0,
            'cart_line_adjustments' => $cartLineIds->isNotEmpty()
                ? DB::table(shopper_table('cart_line_adjustments'))
                    ->whereIn('cart_line_id', $cartLineIds)
                    ->count()
                : 0,
            'discounts' => DB::table(shopper_table('discounts'))
                ->where('type', 'fixed_amount')
                ->whereNotNull('zone_id')
                ->whereIn('zone_id', $zoneIds)
                ->count(),
        ];
    }

    /**
     * The site should be in maintenance mode during upgrade.
     *
     * @param  Collection<string, int>  $currencies
     */
    private function applyFixes(Collection $currencies): void
    {
        $currencyIds = $currencies->values()->all();
        $currencyCodes = $currencies->keys()->all();
        $zoneIds = $this->zoneIdsForCurrencies($currencyIds);

        $this->results['prices'] = $this->fixTable(
            fn (): int => DB::table(shopper_table('prices'))
                ->whereIn('currency_id', $currencyIds)
                ->update([
                    'amount' => DB::raw('FLOOR(amount / 100)'),
                    'compare_amount' => DB::raw('CASE WHEN compare_amount IS NOT NULL THEN FLOOR(compare_amount / 100) ELSE NULL END'),
                    'cost_amount' => DB::raw('CASE WHEN cost_amount IS NOT NULL THEN FLOOR(cost_amount / 100) ELSE NULL END'),
                ]),
        );

        $this->results['orders'] = $this->fixTable(
            fn (): int => DB::table(shopper_table('orders'))
                ->whereIn('currency_code', $currencyCodes)
                ->update([
                    'price_amount' => DB::raw('FLOOR(price_amount / 100)'),
                    'tax_amount' => DB::raw('CASE WHEN tax_amount IS NOT NULL THEN FLOOR(tax_amount / 100) ELSE NULL END'),
                ]),
        );

        $orderIds = $this->orderIdsForCurrencies($currencyCodes);

        $this->results['order_items'] = $orderIds->isNotEmpty()
            ? $this->fixTable(
                fn (): int => DB::table(shopper_table('order_items'))
                    ->whereIn('order_id', $orderIds)
                    ->update([
                        'unit_price_amount' => DB::raw('FLOOR(unit_price_amount / 100)'),
                        'tax_amount' => DB::raw('FLOOR(tax_amount / 100)'),
                        'discount_amount' => DB::raw('FLOOR(discount_amount / 100)'),
                    ]),
            )
            : 0;

        $this->results['carrier_options'] = $this->fixTable(
            fn (): int => DB::table(shopper_table('carrier_options'))
                ->whereIn('zone_id', $zoneIds)
                ->update([
                    'price' => DB::raw('FLOOR(price / 100)'),
                ]),
        );

        $cartLineIds = $this->cartLineIdsForCurrencies($currencyCodes);

        $this->results['cart_lines'] = $cartLineIds->isNotEmpty()
            ? $this->fixTable(
                fn (): int => DB::table(shopper_table('cart_lines'))
                    ->whereIn('id', $cartLineIds)
                    ->update([
                        'unit_price_amount' => DB::raw('FLOOR(unit_price_amount / 100)'),
                    ]),
            )
            : 0;

        $this->results['cart_line_tax_lines'] = $cartLineIds->isNotEmpty()
            ? $this->fixTable(
                fn (): int => DB::table(shopper_table('cart_line_tax_lines'))
                    ->whereIn('cart_line_id', $cartLineIds)
                    ->update([
                        'amount' => DB::raw('FLOOR(amount / 100)'),
                    ]),
            )
            : 0;

        $this->results['cart_line_adjustments'] = $cartLineIds->isNotEmpty()
            ? $this->fixTable(
                fn (): int => DB::table(shopper_table('cart_line_adjustments'))
                    ->whereIn('cart_line_id', $cartLineIds)
                    ->update([
                        'amount' => DB::raw('FLOOR(amount / 100)'),
                    ]),
            )
            : 0;

        $this->results['discounts'] = $this->fixTable(
            fn (): int => DB::table(shopper_table('discounts'))
                ->where('type', 'fixed_amount')
                ->whereNotNull('zone_id')
                ->whereIn('zone_id', $zoneIds)
                ->update([
                    'value' => DB::raw('FLOOR(value / 100)'),
                ]),
        );
    }

    /**
     * @param  array<int, string>  $currencyCodes
     * @return Collection<int, int>
     */
    private function orderIdsForCurrencies(array $currencyCodes): Collection
    {
        return DB::table(shopper_table('orders'))
            ->whereIn('currency_code', $currencyCodes)
            ->pluck('id');
    }

    /**
     * @param  array<int, int>  $currencyIds
     * @return \Illuminate\Database\Query\Builder
     */
    private function zoneIdsForCurrencies(array $currencyIds)
    {
        return DB::table(shopper_table('zones'))
            ->whereIn('currency_id', $currencyIds)
            ->select('id');
    }

    /**
     * @param  array<int, string>  $currencyCodes
     * @return Collection<int, int>
     */
    private function cartLineIdsForCurrencies(array $currencyCodes): Collection
    {
        return DB::table(shopper_table('cart_lines'))
            ->whereIn(
                'cart_id',
                DB::table(shopper_table('carts'))
                    ->whereIn('currency_code', $currencyCodes)
                    ->select('id')
            )
            ->pluck('id');
    }

    private function fixTable(Closure $update): int
    {
        return DB::transaction(fn (): int => $update());
    }
}
