<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Discounts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Models\Discount;

class Browse extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $isActive = null;

    public ?string $date = null;

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function resetActiveFilter(): void
    {
        $this->isActive = null;
    }

    public function render(): View
    {
        return view('shopper::livewire.components.discounts.browse', [
            'total' => Discount::query()->count(),
            'discounts' => Discount::query()->where('code', 'like', '%' . $this->search . '%')
                ->where(function (Builder $query): void {
                    if (null !== $this->isActive) {
                        $query->where('is_active', (bool) ($this->isActive));
                    }

                    if (null !== $this->date) {
                        $query->whereDate('start_at', $this->date)
                            ->orWhereDate('end_at', $this->date);
                    }
                })
                ->orderBy('created_at')
                ->paginate(8),
        ]);
    }
}
