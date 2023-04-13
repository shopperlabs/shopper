<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Account;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dropdown extends Component
{
    public string $full_name = '';

    public string $email = '';

    public ?string $picture = null;

    protected $listeners = ['updatedProfile'];

    public function mount(): void
    {
        $user = Auth::user();

        $this->full_name = $user->full_name;
        $this->picture = $user->picture;
        $this->email = $user->email;
    }

    public function updatedProfile(): void
    {
        $user = Auth::user();

        $this->full_name = $user->full_name;
        $this->picture = $user->picture;
        $this->email = $user->email;
    }

    public function render(): View
    {
        return view('shopper::livewire.account.dropdown');
    }
}
