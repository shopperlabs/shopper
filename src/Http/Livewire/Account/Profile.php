<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Shopper\Framework\Rules\RealEmailValidator;

class Profile extends Component
{
    use WithFileUploads;

    /**
     * Logged User first name attribute.
     *
     * @var string
     */
    public $first_name;

    /**
     * Logged User last name attribute.
     *
     * @var string
     */
    public $last_name;

    /**
     * Logged User email attribute.
     *
     * @var string
     */
    public $email;

    /**
     * Logged User phone number attribute.
     *
     * @var string
     */
    public $phone_number;

    /**
     * Logged User profile picture.
     */
    public $picture;

    /**
     * Component mount instance.
     */
    public function mount()
    {
        $this->first_name = auth()->user()->first_name;
        $this->last_name = auth()->user()->last_name;
        $this->email = auth()->user()->email;
        $this->phone_number = auth()->user()->phone_number;
    }

    /**
     * Real-Time picture validation.
     */
    public function updatedPicture()
    {
        $this->validate([
            'picture' => 'nullable|image|max:1024', // 1MB Max
        ]);
    }

    /**
     * Update user profile to storage.
     */
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
            'picture' => 'nullable|image|max:1024', // 1MB Max
        ]);

        auth()->user()->update([
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
        ]);

        if ($this->picture) {
            $filename = $this->picture->store('/', config('shopper.system.storage.disks.avatars'));

            auth()->user()->update([
                'avatar_type' => 'storage',
                'avatar_location' => $filename,
            ]);
        }

        $this->emit('updatedProfile');

        $this->notify([
            'title' => __('Profile Updated'),
            'message' => __('Your profile have been successfully updated!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.account.profile');
    }
}
