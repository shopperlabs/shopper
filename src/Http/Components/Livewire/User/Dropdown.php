<?php

namespace Shopper\Framework\Http\Components\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;

class Dropdown extends Component
{
    public $full_name = '';
    public $picture = '';
    public $email = '';

    protected $listeners = ['profileUpdated' => 'updateUserPicture'];

    public function mount()
    {
        $this->full_name = auth()->user()->full_name;
        $this->picture = auth()->user()->picture;
        $this->email = auth()->user()->email;
    }

    public function updateUserPicture()
    {
        $this->picture = auth()->user()->picture;
    }

    public function render()
    {
        return view('shopper::components.livewire.user.dropdown');
    }
}
