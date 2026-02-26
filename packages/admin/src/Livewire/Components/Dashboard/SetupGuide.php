<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Dashboard;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Collection as CollectionContract;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\TaxZone as TaxZoneContract;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;
use Shopper\Traits\SaveSettings;

/**
 * @property-read array<int, array{key: string, completed: bool, icon: string, route: string, permission: string}> $steps
 * @property-read int $completedCount
 * @property-read int $totalSteps
 * @property-read bool $isComplete
 */
final class SetupGuide extends Component
{
    use SaveSettings;

    public function mount(): void
    {
        if ($this->isComplete) {
            $this->complete();
        }
    }

    /**
     * @return array<int, array{key: string, completed: bool, icon: string, route: string, permission: string}>
     */
    #[Computed]
    public function steps(): array
    {
        return [
            [
                'key' => 'add_product',
                'completed' => resolve(ProductContract::class)::query()->exists(),
                'icon' => 'untitledui-package',
                'route' => 'shopper.products.index',
                'permission' => 'add_products',
            ],
            [
                'key' => 'create_collection',
                'completed' => resolve(CollectionContract::class)::query()->exists(),
                'icon' => 'untitledui-layers-three',
                'route' => 'shopper.collections.index',
                'permission' => 'add_collections',
            ],
            [
                'key' => 'setup_zones',
                'completed' => Zone::query()->where('is_enabled', true)->exists(),
                'icon' => 'untitledui-globe-05',
                'route' => 'shopper.settings.zones',
                'permission' => 'access_setting',
            ],
            [
                'key' => 'setup_payments',
                'completed' => PaymentMethod::query()->where('is_enabled', true)->exists(),
                'icon' => 'untitledui-credit-card-02',
                'route' => 'shopper.settings.payment-methods',
                'permission' => 'access_setting',
            ],
            [
                'key' => 'setup_taxes',
                'completed' => resolve(TaxZoneContract::class)::query()->exists(),
                'icon' => 'untitledui-receipt-check',
                'route' => 'shopper.settings.taxes',
                'permission' => 'access_setting',
            ],
        ];
    }

    #[Computed]
    public function completedCount(): int
    {
        return collect($this->steps)->where('completed', true)->count();
    }

    #[Computed]
    public function totalSteps(): int
    {
        return count($this->steps);
    }

    #[Computed]
    public function isComplete(): bool
    {
        return $this->completedCount === $this->totalSteps;
    }

    public function complete(): void
    {
        $this->saveSettings(['setup_guide_done' => true]);

        $this->dispatch('setup-guide-completed');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.dashboard.setup-guide');
    }
}
