<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Repositories\UserRepository;

class Management extends Component
{
    use WithPagination;

    /**
     * Role name.
     *
     * @var string
     */
    public $name;

    /**
     * Role display name.
     *
     * @var string
     */
    public $role_display_name;

    /**
     * Role description.
     *
     * @var string
     */
    public $role_description;

    /**
     * Toggle Modal to create a new role.
     *
     * @var bool
     */
    public $displayModal = false;

    /**
     * Add new role.
     *
     * @return void
     */
    public function addNewRole()
    {
        $this->validate([
            'name' => 'required|unique:roles',
        ], [
            'name.required' => __('The role name is required.'),
            'name.unique' => __('This name is already used.')
        ]);

        Role::create([
            'name' => $this->name,
            'display_name' => $this->role_display_name,
            'description' => $this->role_description,
        ]);

        $this->dispatchBrowserEvent('modal-close');

        $this->notify([
            'title' => __('Saved!'),
            'message' => __("Role saved successfully!"),
        ]);

        $this->displayModal = false;

        $this->name = '';
        $this->role_display_name = '';
        $this->role_description = '';
    }

    /**
     * Action provide to launch the modal.
     *
     * @return void
     */
    public function launchCreateModal()
    {
        $this->displayModal = true;
    }

    /**
     * Remove a user from the storage.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function removeUser(int $id)
    {
        (new UserRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('user-removed');
        $this->notify([
            'title' => __('Deleted'),
            'message' => __("Admin deleted successfully!"),
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        return view('shopper::livewire.settings.management.index', [
            'roles' => Role::query()
                ->with('users')
                ->where('name', '<>', config('shopper.system.users.default_role'))
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
