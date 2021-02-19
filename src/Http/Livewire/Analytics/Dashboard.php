<?php

namespace Shopper\Framework\Http\Livewire\Analytics;

use Livewire\Component;

class Dashboard extends Component
{
    /**
     * Remove a record to the database.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function remove(int $id)
    {
        
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.analytics.dashboard', [
            
        ]);
    }
}
