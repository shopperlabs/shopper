<?php

namespace Shopper\Framework\Http\Components\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;

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
        return view('shopper::components.livewire.user.dropdown');
    }
}
