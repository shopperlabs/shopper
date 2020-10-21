<?php

namespace Shopper\Framework\Components\Livewire\Settings;

use Livewire\Component;

class Analytics extends Component
{
    public $google_analytics_tracking_id;
    public $google_analytics_view_id;
    public $google_analytics_add_js;
    public $google_tag_manager_account_id;
    public $facebook_pixel_account_id;

    protected $rules = [
        'google_analytics_tracking_id'  => 'nullable|string',
        'google_analytics_view_id'      => 'nullable|numeric',
        'google_analytics_add_js'       => 'nullable|string',
        'google_tag_manager_account_id' => 'nullable|string',
        'facebook_pixel_account_id'     => 'nullable|string',
    ];

    public function render()
    {
        return view('shopper::livewire.settings.analytics');
    }

    public function submit()
    {
        $this->validate();

        setEnvironmentValue([
            'google_analytics_tracking_id'  => $this->google_analytics_tracking_id,
            'google_analytics_view_id'      => $this->google_analytics_view_id,
            'google_analytics_add_js'       => $this->google_analytics_add_js,
            'google_tag_manager_account_id' => $this->google_tag_manager_account_id,
            'facebook_pixel_account_id'     => $this->facebook_pixel_account_id,
        ]);
    }
}
