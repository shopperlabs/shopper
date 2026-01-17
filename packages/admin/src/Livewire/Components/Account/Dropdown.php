<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Traits\HasAuthenticated;

class Dropdown extends Component
{
    use HasAuthenticated;

    /**
     * @var Model&ShopperUser
     */
    public $user;

    public function mount(): void
    {
        $this->user = $this->getUser();
    }

    #[On('profile.updated')]
    public function updatedProfile(): void
    {
        $this->user = $this->getUser();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.account.dropdown');
    }
}
