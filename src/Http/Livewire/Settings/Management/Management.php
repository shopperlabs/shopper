<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Repositories\UserRepository;
use WireUi\Traits\Actions;

class Management extends Component
{
    use Actions;
    use WithPagination;

    protected $listeners = ['onRoleAdded' => '$refresh'];

    public function removeUser(int $id): void
    {
        (new UserRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('user-removed');

        $this->notification()->success(__('Deleted'), __('Admin deleted successfully!'));
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.management.index', [
            'roles' => Role::query()
                ->with('users')
                ->whereIn('name', [config('shopper.system.users.admin_role'), 'manager'])
                ->limit(3)
                ->orderBy('created_at')
                ->get(),
            'users' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->where('name', '<>', config('shopper.system.users.default_role'));
                })
                ->orderBy('created_at', 'desc')
                ->paginate(3),
        ]);
    }
}
