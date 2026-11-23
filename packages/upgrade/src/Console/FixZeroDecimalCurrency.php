<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

#[AsCommand('shopper:upgrade:fix-zero-decimal-currencies')]
final class FixZeroDecimalCurrency extends Command
{
    protected $signature = 'shopper:upgrade:fix-zero-decimal-currencies
        {--dry-run : Show what would be changed without modifying data}
        {--force : Skip confirmation prompt}';

    protected $description = 'Fix monetary values for zero-decimal currencies that were incorrectly multiplied by 100 in previous versions';

    /** @var array<string, int> */
    private array $results = [];

    public function handle(): int
    {
        if ($this->hasAlreadyRun()) {
            warning('This fix has already been applied. Delete the `zero_decimal_fix_applied` setting to run it again (WARNING: this will divide values again).');

            return self::SUCCESS;
        }

        $currencies = DB::table(shopper_table('currencies'))
            ->whereIn('code', zero_decimal_currencies())
            ->pluck('id', 'code');

        if ($currencies->isEmpty()) {
            info('No zero-decimal currencies found in your database. Nothing to fix.');

            $this->markAsRunUnlessDryRun();

            return self::SUCCESS;
        }

        info('Zero-decimal currencies detected: '.$currencies->keys()->implode(', '));

        $counts = $this->analyze($currencies);

        if (array_sum($counts) === 0) {
            info('All tables are clean — no rows need fixing.');

            $this->markAsRunUnlessDryRun();

            return self::SUCCESS;
        }

        table(
            headers: ['Table', 'Rows in zero-decimal currencies'],
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
            callback: fn () => DB::transaction(function () use ($currencies): void {
                $this->markAsRun();
                $this->applyFixes($currencies);
            }),
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
        $zoneIds = $this->zoneIdsForCurrencies($currencyIds);

        return [
            'prices' => DB::table(shopper_table('prices'))
                ->whereIn('currency_id', $currencyIds)
                ->count(),
            'orders' => DB::table(shopper_table('orders'))
                ->whereIn('currency_code', $currencyCodes)
                ->count(),
            'order_items' => DB::table(shopper_table('order_items'))
                ->whereIn('order_id', $this->orderIdsForCurrencies($currencyCodes))
                ->count(),
            'carrier_options' => DB::table(shopper_table('carrier_options'))
                ->whereIn('zone_id', $zoneIds)
                ->count(),
            'cart_lines' => DB::table(shopper_table('cart_lines'))
                ->whereIn('cart_id', $this->cartIdsForCurrencies($currencyCodes))
                ->count(),
            'cart_line_tax_lines' => DB::table(shopper_table('cart_line_tax_lines'))
                ->whereIn('cart_line_id', $this->cartLineIdsForCurrencies($currencyCodes))
                ->count(),
            'cart_line_adjustments' => DB::table(shopper_table('cart_line_adjustments'))
                ->whereIn('cart_line_id', $this->cartLineIdsForCurrencies($currencyCodes))
                ->count(),
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

        $this->results['prices'] = DB::table(shopper_table('prices'))
            ->whereIn('currency_id', $currencyIds)
            ->update([
                'amount' => DB::raw('ROUND(amount / 100.0)'),
                'compare_amount' => DB::raw('CASE WHEN compare_amount IS NOT NULL THEN ROUND(compare_amount / 100.0) ELSE NULL END'),
                'cost_amount' => DB::raw('CASE WHEN cost_amount IS NOT NULL THEN ROUND(cost_amount / 100.0) ELSE NULL END'),
            ]);

        $this->results['orders'] = DB::table(shopper_table('orders'))
            ->whereIn('currency_code', $currencyCodes)
            ->update([
                'price_amount' => DB::raw('ROUND(price_amount / 100.0)'),
                'tax_amount' => DB::raw('CASE WHEN tax_amount IS NOT NULL THEN ROUND(tax_amount / 100.0) ELSE NULL END'),
            ]);

        $this->results['order_items'] = DB::table(shopper_table('order_items'))
            ->whereIn('order_id', $this->orderIdsForCurrencies($currencyCodes))
            ->update([
                'unit_price_amount' => DB::raw('ROUND(unit_price_amount / 100.0)'),
                'tax_amount' => DB::raw('ROUND(tax_amount / 100.0)'),
                'discount_amount' => DB::raw('ROUND(discount_amount / 100.0)'),
            ]);

        $this->results['carrier_options'] = DB::table(shopper_table('carrier_options'))
            ->whereIn('zone_id', $zoneIds)
            ->update([
                'price' => DB::raw('ROUND(price / 100.0)'),
            ]);

        $this->results['cart_lines'] = DB::table(shopper_table('cart_lines'))
            ->whereIn('cart_id', $this->cartIdsForCurrencies($currencyCodes))
            ->update([
                'unit_price_amount' => DB::raw('ROUND(unit_price_amount / 100.0)'),
            ]);

        $this->results['cart_line_tax_lines'] = DB::table(shopper_table('cart_line_tax_lines'))
            ->whereIn('cart_line_id', $this->cartLineIdsForCurrencies($currencyCodes))
            ->update([
                'amount' => DB::raw('ROUND(amount / 100.0)'),
            ]);

        $this->results['cart_line_adjustments'] = DB::table(shopper_table('cart_line_adjustments'))
            ->whereIn('cart_line_id', $this->cartLineIdsForCurrencies($currencyCodes))
            ->update([
                'amount' => DB::raw('ROUND(amount / 100.0)'),
            ]);

        $this->results['discounts'] = DB::table(shopper_table('discounts'))
            ->where('type', 'fixed_amount')
            ->whereNotNull('zone_id')
            ->whereIn('zone_id', $zoneIds)
            ->update([
                'value' => DB::raw('ROUND(value / 100.0)'),
            ]);
    }

    /**
     * @param  array<int, string>  $currencyCodes
     */
    private function orderIdsForCurrencies(array $currencyCodes): Builder
    {
        return DB::table(shopper_table('orders'))
            ->whereIn('currency_code', $currencyCodes)
            ->select('id');
    }

    /**
     * @param  array<int, int>  $currencyIds
     */
    private function zoneIdsForCurrencies(array $currencyIds): Builder
    {
        return DB::table(shopper_table('zones'))
            ->whereIn('currency_id', $currencyIds)
            ->select('id');
    }

    /**
     * @param  array<int, string>  $currencyCodes
     */
    private function cartIdsForCurrencies(array $currencyCodes): Builder
    {
        return DB::table(shopper_table('carts'))
            ->whereIn('currency_code', $currencyCodes)
            ->select('id');
    }

    /**
     * @param  array<int, string>  $currencyCodes
     */
    private function cartLineIdsForCurrencies(array $currencyCodes): Builder
    {
        return DB::table(shopper_table('cart_lines'))
            ->whereIn('cart_id', $this->cartIdsForCurrencies($currencyCodes))
            ->select('id');
    }

    private function hasAlreadyRun(): bool
    {
        return DB::table(shopper_table('settings'))
            ->where('key', 'zero_decimal_fix_applied')
            ->exists();
    }

    private function markAsRunUnlessDryRun(): void
    {
        if (! $this->option('dry-run')) {
            $this->markAsRun();
        }
    }

    private function markAsRun(): void
    {
        DB::table(shopper_table('settings'))->insert([
            'key' => 'zero_decimal_fix_applied',
            'value' => json_encode(now()->toIso8601String()),
        ]);
    }
}
