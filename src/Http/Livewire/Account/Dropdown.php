<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Livewire\Component;

class Dropdown extends Component
{
    /**
     * Components custom Listeners.
     *
     * @var string[]
     */
    protected $listeners = ['updatedProfile'];

    /**
     * Logged user full name.
     *
     * @var string
     */
    public $full_name;

    /**
     * Logged user picture url.
     *
     * @var string
     */
    public $picture;

    /**
     * Logged user email address.
     *
     * @var string
     */
    public $email;

    /**
     * Component mount instance.
     *
     * @return void
     */
    public function mount()
    {
        $this->full_name = auth()->user()->full_name;
        $this->picture = auth()->user()->picture;
        $this->email = auth()->user()->email;
    }

    /**
     * Update profile listener implementation.
     *
     * @return void
     */
    public function updatedProfile()
    {
        $this->picture = auth()->user()->picture;
        $this->full_name = auth()->user()->full_name;
        $this->picture = auth()->user()->picture;
        $this->email = auth()->user()->email;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.account.dropdown');
    }
}
