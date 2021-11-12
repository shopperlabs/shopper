<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dropdown extends Component
{
    public string $full_name = '';
    public string $email = '';
    public ?string $picture = null;

    protected $listeners = ['updatedProfile'];

    public function mount()
    {
        $user = Auth::user();

        $this->full_name = $user->full_name;
        $this->picture = $user->picture;
        $this->email = $user->email;
    }

    public function updatedProfile()
    {
        $user = Auth::user();

        $this->picture = $user->picture;
        $this->full_name = $user->full_name;
        $this->picture = $user->picture;
        $this->email = $user->email;
    }

    public function render()
    {
        return view('shopper::livewire.account.dropdown');
    }
}
