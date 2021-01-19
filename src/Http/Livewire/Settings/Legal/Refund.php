<?php

namespace Shopper\Framework\Http\Livewire\Settings\Legal;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Legal;
use Shopper\Framework\Traits\WithLegalAttributes;

class Refund extends Component
{
    use WithLegalAttributes;

    /**
     * Component mount instance.
     *
     * @return void
     */
    public function mount()
    {
        $legal = Legal::query()->where('slug', str_slug('Refund policy'))->first();

        $this->initializeValues($legal);
    }

    /**
     * Store/Update data from storage.
     *
     * @return void
     */
    public function store()
    {
        Legal::query()->updateOrCreate(['slug' => str_slug('Refund policy')], [
            'title' => $title = 'Refund policy',
            'slug' => str_slug($title),
            'content' => $this->content,
            'is_enabled' => $this->isEnabled,
        ]);

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Your refund policy has been successfully updated!'),
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.legal.refund');
    }
}
