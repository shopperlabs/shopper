<?php

namespace Shopper\Framework\Components\Livewire\Settings\Management;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Repositories\UserRepository;

class Management extends Component
{
    use WithPagination;

    public string $name = '';
    public string $role_display_name = '';
    public string $role_description = '';

    public function addNewRole()
    {
        $this->validate([
            'name' => 'required|unique:roles',
        ], [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This name is already used.'
        ]);

        Role::create([
            'name' => $this->name,
            'display_name' => $this->role_display_name,
            'description' => $this->role_description,
        ]);

        $this->dispatchBrowserEvent('role-added');
        $this->dispatchBrowserEvent('notify', [
            'title' => 'Saved',
            'message' => __("Role saved successfully"),
        ]);

        $this->name = '';
        $this->role_display_name = '';
        $this->role_description = '';
    }

    public function removeUser(int $id)
    {
        (new UserRepository())->getById($id)->delete();
        $this->dispatchBrowserEvent('user-removed');
        $this->dispatchBrowserEvent('notify', [
            'title' => __('Deleted'),
            'message' => __("Admin deleted successfully"),
        ]);
    }

    public function render()
    {
        $roles = Role::query()
            ->with('users')
            ->where('name', '<>', config('shopper.system.users.default_role'))
            ->limit(3)
            ->orderBy('created_at')
            ->get();

        $users = (new UserRepository())
            ->makeModel()
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', '<>', config('shopper.system.users.default_role'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        return view('shopper::livewire.settings.management.index', compact('roles', 'users'));
    }
}
