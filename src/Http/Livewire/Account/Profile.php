<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Rules\RealEmailValidator;
use WireUi\Traits\Actions;

class Profile extends Component
{
    use Actions, WithFileUploads;

    public string $first_name;
    public string $last_name;
    public string $email;
    public ?string $phone_number = null;
    public $picture;

    public function mount()
    {
        $user = Auth::user();

        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
    }

    public function updatedPicture()
    {
        $this->validate(['picture' => 'nullable|image|max:1024']);
    }

    public function save()
    {
        $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique(shopper_table('users'), 'email')->ignore(auth()->id()),
                new RealEmailValidator(),
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'picture' => 'nullable|image|max:1024',
        ]);

        $user = Auth::user();

        $user->update([
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
        ]);

        if ($this->picture) {
            $filename = $this->picture->store('/', config('shopper.system.storage.disks.avatars'));

            $user->update([
                'avatar_type' => 'storage',
                'avatar_location' => $filename,
            ]);
        }

        $this->emit('updatedProfile');

        $this->notification()->success(__('Profile Updated'), __('Your profile have been successfully updated!'));
    }

    public function render()
    {
        return view('shopper::livewire.account.profile');
    }
}
