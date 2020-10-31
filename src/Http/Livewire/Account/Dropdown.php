<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Livewire\Component;

class Dropdown extends Component
{
    public $full_name = '';
    public $picture = '';
    public $email = '';

    protected $listeners = ['updatedProfile'];

    public function mount()
    {
        $this->full_name = auth()->user()->full_name;
        $this->picture = auth()->user()->picture;
        $this->email = auth()->user()->email;
    }

    public function updatedProfile()
    {
        $this->picture = auth()->user()->picture;
        $this->full_name = auth()->user()->full_name;
        $this->picture = auth()->user()->picture;
        $this->email = auth()->user()->email;
    }

    public function render()
    {
        return view('shopper::livewire.account.dropdown');
    }
}
