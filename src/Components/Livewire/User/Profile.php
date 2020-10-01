<?php

namespace Shopper\Framework\Components\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $picture;

    public function mount()
    {
        $this->first_name = auth()->user()->first_name;
        $this->last_name = auth()->user()->last_name;
        $this->email = auth()->user()->email;
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
            'first_name' => 'required',
            'last_name' => 'required',
            'picture' => 'nullable|image|max:1024', // 1MB Max
        ]);

        auth()->user()->update([
            'last_name'     => $this->last_name,
            'first_name'    => $this->first_name,
            'email'         => $this->email,
        ]);

        if ($this->picture) {
            $filename = $this->picture->store('/', config('shopper.storage.disks.avatars'));

            auth()->user()->update([
                'avatar_type'   => 'storage',
                'avatar_location' => $filename,
            ]);

            $this->emit('updatedProfile');
        }

        session()->flash('success', __("Profile successfully updated."));
    }

    public function render()
    {
        return view('shopper::components.livewire.user.profile');
    }
}
