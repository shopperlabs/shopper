<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Repositories\UserRepository;

class DiscountCustomers extends ModalComponent
{
    public string $search = '';

    public array $selectedCustomers = [];

    public array $excludesIds;

    public function mount(array $excludeIds): void
    {
        $this->excludesIds = $excludeIds;
    }

    public function addSelectedCustomers(): void
    {
        $this->excludesIds = $this->selectedCustomers;

        $this->emit('addSelectedCustomers', $this->selectedCustomers);

        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.discount-customers', [
            'customers' => (new UserRepository()) // @phpstan-ignore-line
                ->makeModel()
                ->whereHas('roles', function (Builder $query): void {
                    $query->where('name', config('shopper.core.users.default_role'));
                })
                ->where(function (Builder $query): void {
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'asc')
                ->get()
                ->except($this->excludesIds),
        ]);
    }
}
