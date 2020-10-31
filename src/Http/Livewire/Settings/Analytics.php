<?php

namespace Shopper\Framework\Http\Livewire\Settings;

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

    public function mount() {
        $ga_add_js = Setting::query()->where('key', 'google_analytics_add_js')->first();
        $this->google_analytics_tracking_id = env('GOOGLE_ANALYTICS_TRACKING_ID');
        $this->google_analytics_view_id = env('GOOGLE_ANALYTICS_VIEW_ID');
        $this->google_analytics_add_js = $ga_add_js->value ?? null;
        $this->google_tag_manager_account_id = env('GOOGLE_TAG_MANAGER_ACCOUNT_ID');
        $this->facebook_pixel_account_id = env('FACEBOOK_PIXEL_ACCOUNT_ID');
    }

    public function store()
    {
        setEnvironmentValue([
            'google_analytics_tracking_id'  => $this->google_analytics_tracking_id,
            'google_analytics_view_id'      => $this->google_analytics_view_id,
            'google_tag_manager_account_id' => $this->google_tag_manager_account_id,
            'facebook_pixel_account_id'     => $this->facebook_pixel_account_id,
        ]);

        Setting::query()->updateOrCreate(['key' => 'google_analytics_add_js'], [
            'key' => 'google_analytics_add_js',
            'value'  => $this->google_analytics_add_js,
            'locked' => true,
            'display_name' => Setting::lockedAttributesDisplayName('google_analytics_add_js'),
        ]);

        Artisan::call('config:clear');

        $this->dispatchBrowserEvent('notify', [
            'title' => __('Updated'),
            'message' => __("Your analytics configurations have been correctly updated")
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.settings.analytics');
    }
}
