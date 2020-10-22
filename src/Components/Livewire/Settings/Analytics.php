<?php

namespace Shopper\Framework\Components\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Shopper\Framework\Models\System\Setting;

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

    public function mount() {
        $ga_add_js = Setting::where('key', 'google_analytics_add_js')->first();
        $this->google_analytics_tracking_id = env('GOOGLE_ANALYTICS_TRACKING_ID');
        $this->google_analytics_view_id = env('GOOGLE_ANALYTICS_VIEW_ID');
        $this->google_analytics_add_js = $ga_add_js->value;
        $this->google_tag_manager_account_id = env('GOOGLE_TAG_MANAGER_ACCOUNT_ID');
        $this->facebook_pixel_account_id = env('FACEBOOK_PIXEL_ACCOUNT_ID');
    }

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
            'google_tag_manager_account_id' => $this->google_tag_manager_account_id,
            'facebook_pixel_account_id'     => $this->facebook_pixel_account_id,
        ]);

        Setting::updateOrCreate(['key' => 'google_analytics_add_js'], [
            'key' => 'google_analytics_add_js',
            'value'  => $this->google_analytics_add_js,
            'locked' => true,
            'display_name' => Setting::lockedAttributesDisplayName('google_analytics_add_js'),
        ]);

        Artisan::call('config:clear');
    }
}
