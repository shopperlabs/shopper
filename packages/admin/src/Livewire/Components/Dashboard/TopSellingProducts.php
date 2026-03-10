<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\ProductVariant;

/**
 * @property-read Collection $products
 */
final class TopSellingProducts extends Component
{
    #[Computed]
    public function products(): Collection
    {
        /** @var array<int, array<string, mixed>> $cached */
        $cached = Cache::flexible('dashboard:top-selling-products', [300, 1800], fn (): array => $this->buildTopProducts());

        return collect($cached);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.dashboard.top-selling-products');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildTopProducts(): array
    {
        $variantClass = resolve(ProductVariant::class)::class;

        $topProducts = DB::table(shopper_table('order_items').' as oi')
            ->join(shopper_table('orders').' as o', 'o.id', '=', 'oi.order_id')
            ->leftJoin(shopper_table('product_variants').' as pv', function (JoinClause $join) use ($variantClass): void {
                $join->on('pv.id', '=', 'oi.product_id')
                    ->where('oi.product_type', '=', $variantClass);
            })
            ->where('o.payment_status', PaymentStatus::Paid->value)
            ->selectRaw('COALESCE(pv.product_id, oi.product_id) as parent_product_id, SUM(oi.quantity) as total_sales')
            ->groupBy('parent_product_id')
            ->orderByDesc('total_sales')
            ->limit(6)
            ->get();

        $productIds = $topProducts->pluck('parent_product_id')->filter()->all();

        /** @var \Illuminate\Database\Eloquent\Collection<int, Model> $products */
        // @phpstan-ignore-next-line
        $products = resolve(ProductContract::class)::query()
            ->whereIn('id', $productIds)
            ->with([
                'media',
                'ratings' => fn ($query) => $query->where('approved', true),
            ])
            ->get()
            ->keyBy('id');

        return $topProducts
            ->map(function (object $row) use ($products): array {
                $product = $products->get((int) $row->parent_product_id);
                $approvedRatings = $product?->getRelation('ratings');

                return [
                    'product' => $product,
                    'sales' => (int) $row->total_sales,
                    'reviews_count' => $approvedRatings?->count() ?? 0,
                    'average_rating' => $approvedRatings?->isNotEmpty()
                        ? round($approvedRatings->avg('rating'), 1)
                        : null,
                ];
            })
            ->all();
    }
}
