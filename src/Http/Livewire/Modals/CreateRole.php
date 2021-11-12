<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\User\Role;

class CreateRole extends ModalComponent
{
    public string $name = '';
    public string $display_name = '';
    public string $description = '';

    public function save()
    {
        $this->validate(['name' => 'required|unique:roles'], [
            'name.required' => __('The role name is required.'),
            'name.unique' => __('This name is already used.'),
        ]);

        Role::create([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        $this->emit('onRoleAdded');

        $this->closeModal();
    }

    public function render()
    {
        return view('shopper::livewire.modals.create-role');
    }
}
