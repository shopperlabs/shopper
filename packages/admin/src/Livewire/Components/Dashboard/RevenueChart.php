<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Dashboard;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Currency;

final class RevenueChart extends ApexChartWidget
{
    public ?string $filter = null;

    protected static ?string $chartId = 'revenueChart';

    public function mount(): void
    {
        $this->filter = shopper_currency();

        parent::mount();

        $symbol = Currency::query()->where('code', $this->filter)->value('symbol') ?? $this->filter;
        $locale = str_replace('_', '-', app()->getLocale());

        $this->js('window.__shopperRevenueChart = '.Js::from(['symbol' => $symbol, 'locale' => $locale]));
    }

    public function updatedFilter(): void
    {
        $this->options = $this->processOptions($this->getOptions());

        $currency = $this->filter ?? shopper_currency();
        $symbol = Currency::query()->where('code', $currency)->value('symbol') ?? $currency;
        $locale = str_replace('_', '-', app()->getLocale());
        $options = Js::from($this->options);

        $this->js('window.__shopperRevenueChart = '.Js::from(['symbol' => $symbol, 'locale' => $locale])."; \$wire.dispatchSelf('updateOptions', { options: {$options} });");
    }

    protected function getHeading(): string
    {
        return __('shopper::pages/dashboard.chart.heading');
    }

    protected function getContentHeight(): int
    {
        return 320;
    }

    protected function getFilters(): array
    {
        /** @var array<int> $currencyIds */
        $currencyIds = shopper_setting('currencies') ?? [];

        return Currency::query()
            ->whereIn('id', $currencyIds)
            ->pluck('code', 'code')
            ->all();
    }

    protected function extraJsOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            yaxis: {
                labels: {
                    formatter: function (val) {
                        const s = window.__shopperRevenueChart || {};
                        return new Intl.NumberFormat(s.locale || 'en', { notation: 'compact' }).format(val) + ' ' + (s.symbol || '')
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        const s = window.__shopperRevenueChart || {};
                        return new Intl.NumberFormat(s.locale || 'en').format(val) + ' ' + (s.symbol || '')
                    }
                }
            }
        }
        JS);
    }

    protected function getOptions(): array
    {
        $currency = $this->filter ?? shopper_currency();
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $months = collect(CarbonPeriod::create($startDate, '1 month', $endDate));

        /** @var array{select: string, groupBy: string} $expr */
        $expr = $this->revenueExpressions();

        /** @var array<string, float> $revenueByMonth */
        $revenueByMonth = Cache::flexible(
            "dashboard:revenue:{$currency}",
            [300, 1800],
            fn () => resolve(Order::class)::query()
                ->selectRaw($expr['select'])
                ->where('payment_status', PaymentStatus::Paid)
                ->where('currency_code', $currency)
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->groupByRaw($expr['groupBy'])
                ->get()
                ->keyBy(fn ($row): string => $row->getAttribute('year').'-'.$row->getAttribute('month'))
                ->map(fn ($row): float => (float) $row->getAttribute('total') / 100)
                ->all(),
        );

        $data = $months->map(fn (Carbon $month): float => $revenueByMonth[$month->year.'-'.$month->month] ?? 0.0);

        $labels = $months->map(fn (Carbon $month): string => $month->translatedFormat('M'));

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 320,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => __('shopper::pages/dashboard.chart.series_label'),
                    'data' => $data->values()->all(),
                ],
            ],
            'xaxis' => [
                'categories' => $labels->values()->all(),
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'yaxis' => [
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'colors' => ['#10b981'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'columnWidth' => '60%',
                ],
            ],
            'dataLabels' => ['enabled' => false],
            'grid' => [
                'borderColor' => 'rgba(0,0,0,0.05)',
            ],
        ];
    }

    /**
     * @return array{select: string, groupBy: string}
     */
    private function revenueExpressions(): array
    {
        return match (DB::connection()->getDriverName()) {
            'pgsql' => [
                'select' => 'EXTRACT(YEAR FROM created_at)::int as year, EXTRACT(MONTH FROM created_at)::int as month, SUM(price_amount) as total',
                'groupBy' => 'EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)',
            ],
            'sqlite' => [
                'select' => "CAST(strftime('%Y', created_at) AS INTEGER) as year, CAST(strftime('%m', created_at) AS INTEGER) as month, SUM(price_amount) as total",
                'groupBy' => "strftime('%Y', created_at), strftime('%m', created_at)",
            ],
            default => [
                'select' => 'YEAR(created_at) as year, MONTH(created_at) as month, SUM(price_amount) as total',
                'groupBy' => 'YEAR(created_at), MONTH(created_at)',
            ],
        };
    }
}
