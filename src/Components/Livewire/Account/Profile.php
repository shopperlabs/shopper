<?php

namespace Shopper\Framework\Components\Livewire\Account;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone_number = '';
    public $picture;

    public function mount()
    {
        $this->first_name = auth()->user()->first_name;
        $this->last_name = auth()->user()->last_name;
        $this->email = auth()->user()->email;
        $this->phone_number = auth()->user()->phone_number;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'picture' => 'nullable|image|max:1024', // 1MB Max
        ]);
    }

    public function save()
    {
        $this->validate([
            'email' => ['required', 'email', Rule::unique(shopper_table('users'), 'email')->ignore(auth()->id())],
            'first_name' => 'required',
            'last_name' => 'required',
            'picture' => 'nullable|image|max:1024', // 1MB Max
        ]);

        auth()->user()->update([
            'last_name'     => $this->last_name,
            'first_name'    => $this->first_name,
            'email'         => $this->email,
            'phone_number'  => $this->phone_number,
        ]);

        if ($this->picture) {
            $filename = $this->picture->store('/', config('shopper.system.storage.disks.avatars'));

            auth()->user()->update([
                'avatar_type'   => 'storage',
                'avatar_location' => $filename,
            ]);

            $this->emit('updatedProfile');
        }

        $this->dispatchBrowserEvent('notify', [
            'title' => __('Profile Updated'),
            'message' => __("Your profile have been successfully updated!")
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.account.profile');
    }
}
