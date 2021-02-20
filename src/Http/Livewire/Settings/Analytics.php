<?php

namespace Shopper\Framework\Http\Livewire\Settings;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\System\Setting;

class Analytics extends Component
{
    use WithFileUploads;

    /**
     * Google Analytics tracking iD.
     *
     * @var string
     */
    public $google_analytics_tracking_id;

    /**
     * Google Analytics view id.
     *
     * @var string
     */
    public $google_analytics_view_id;

    /**
     * Google Analytics custom js.
     *
     * @var string
     */
    public $google_analytics_add_js;

    /**
     * Google Tag Manager id.
     *
     * @var string
     */
    public $google_tag_manager_account_id;

    /**
     * Facebook Pixel Id.
     *
     * @var string
     */
    public $facebook_pixel_account_id;

    /**
     * Json Credential file.
     *
     * @var mixed
     */
    public $json_file;

    /**
     * Analytics credentials.
     *
     * @var bool
     */
    public $credentials_json = false;

    /**
     * Component Mount Method.
     *
     * @return void
     */
    public function mount()
    {
        $ga_add_js = Setting::query()->where('key', 'google_analytics_add_js')->first();
        $this->google_analytics_tracking_id = env('ANALYTICS_TRACKING_ID');
        $this->google_analytics_view_id = env('ANALYTICS_VIEW_ID');
        $this->google_analytics_add_js = $ga_add_js->value ?? null;
        $this->google_tag_manager_account_id = env('GOOGLE_TAG_MANAGER_ACCOUNT_ID');
        $this->facebook_pixel_account_id = env('FACEBOOK_PIXEL_ACCOUNT_ID');
        $this->credentials_json = File::exists(storage_path('app/analytics/service-account-credentials.json'));
    }

    /**
     * Sav/Update a entry on the storage.
     *
     * @return void
     */
    public function store()
    {
        setEnvironmentValue([
            'analytics_tracking_id'         => $this->google_analytics_tracking_id,
            'analytics_view_id'             => $this->google_analytics_view_id,
            'google_tag_manager_account_id' => $this->google_tag_manager_account_id,
            'facebook_pixel_account_id'     => $this->facebook_pixel_account_id,
        ]);

        Setting::query()->updateOrCreate(['key' => 'google_analytics_add_js'], [
            'key' => 'google_analytics_add_js',
            'value'  => $this->google_analytics_add_js,
            'locked' => true,
            'display_name' => Setting::lockedAttributesDisplayName('google_analytics_add_js'),
        ]);

        if ($this->json_file) {
            $this->json_file->storeAs('analytics', 'service-account-credentials.json');
        }

        Artisan::call('config:clear');

        $this->notify([
            'title' => __('Updated'),
            'message' => __("Your analytics configurations have been correctly updated")
        ]);
    }

    public function downloadJson()
    {
        //storage_path('app/analytics/service-account-credentials.json')
        return Storage::url('app/analytics/service-account-credentials.json');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.analytics');
    }
}
