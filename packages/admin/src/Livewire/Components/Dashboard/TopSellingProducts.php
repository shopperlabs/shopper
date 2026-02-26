<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\OrderItem;

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

        $items = OrderItem::query()
            ->selectRaw('product_id, product_type, SUM(quantity) as total_sales')
            ->whereHas('order', fn ($query) => $query->where('payment_status', PaymentStatus::Paid))
            ->groupBy('product_id', 'product_type')
            ->orderByDesc('total_sales')
            ->with(['product'])
            ->get();

        $items->each(function (OrderItem $item) use ($variantClass): void {
            if ($item->getAttribute('product_type') === $variantClass) {
                $item->product->load('product');
            }
        });

        return $items
            ->map(function ($item) use ($variantClass): array {
                /** @var OrderItem $item */
                $product = $item->getAttribute('product_type') === $variantClass
                    ? $item->product->getAttribute('product')
                    : $item->product;

                return [
                    'product_id' => $product?->id,
                    'product' => $product,
                    'sales' => (int) $item->getAttribute('total_sales'),
                ];
            })
            ->groupBy('product_id')
            ->map(fn (Collection $group): array => [
                'product' => $group->first()['product'],
                'sales' => $group->sum('sales'),
            ])
            ->sortByDesc('sales')
            ->take(6)
            ->values()
            ->map(function (array $item): array {
                $product = $item['product'];

                $product?->load([
                    'media',
                    'ratings' => fn ($query) => $query->where('approved', true),
                ]);

                $approvedRatings = $product?->ratings;

                return [
                    'product' => $product,
                    'sales' => $item['sales'],
                    'reviews_count' => $approvedRatings?->count() ?? 0,
                    'average_rating' => $approvedRatings?->isNotEmpty()
                        ? round($approvedRatings->avg('rating'), 1)
                        : null,
                ];
            })
            ->all();
    }
}
