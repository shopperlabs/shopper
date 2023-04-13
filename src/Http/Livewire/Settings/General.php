<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Models\System\Currency;
use Shopper\Framework\Models\System\Setting;
use WireUi\Traits\Actions;

class General extends Component
{
    use Actions;
    use WithFileUploads;

    public string $shop_name;

    public string $shop_email;

    public string $shop_street_address;

    public string $shop_zipcode;

    public string $shop_city;

    public ?string $shop_legal_name = null;

    public ?string $shop_phone_number = null;

    public ?string $shop_about = null;

    public ?string $shop_facebook_link = null;

    public ?string $shop_instagram_link = null;

    public ?string $shop_twitter_link = null;

    public ?string $shop_logo = null;

    public ?string $shop_cover = null;

    public ?int $shop_country_id = null;

    public ?int $shop_currency_id = null;

    public $logo;

    public $cover;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function mount(): void
    {
        $settings = Setting::whereIn('key', [
            'shop_name',
            'shop_legal_name',
            'shop_email',
            'shop_about',
            'shop_phone_number',
            'shop_logo',
            'shop_cover',
            'shop_street_address',
            'shop_zipcode',
            'shop_city',
            'shop_country_id',
            'shop_currency_id',
            'shop_facebook_link',
            'shop_instagram_link',
            'shop_twitter_link',
        ])->select('value', 'key')
            ->get()
            ->toArray();

        foreach ($settings as $setting) {
            $this->{$setting['key']} = $setting['value'] ?? null;
        }
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->shop_about = $value;
    }

    public function store(): void
    {
        $this->validate($this->rules());

        $keys = [
            'shop_name',
            'shop_legal_name',
            'shop_email',
            'shop_about',
            'shop_country_id',
            'shop_currency_id',
            'shop_street_address',
            'shop_zipcode',
            'shop_city',
            'shop_phone_number',
            'shop_facebook_link',
            'shop_instagram_link',
            'shop_twitter_link',
        ];

        foreach ($keys as $key) {
            $this->createUpdateSetting(key: $key, value: $this->{$key});
        }

        if ($this->logo) {
            $this->createUpdateSetting(
                key: 'shop_logo',
                value: $this->logo->store('/', config('shopper.system.storage.disks.uploads'))
            );
        }

        if ($this->cover) {
            $this->createUpdateSetting(
                key: 'shop_cover',
                value: $this->cover->store('/', config('shopper.system.storage.disks.uploads'))
            );
        }

        $this->notification()->success(__('shopper::layout.status.updated'), __('Shop informations have been correctly updated!'));
    }

    public function createUpdateSetting(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], [
            'value' => $value,
            'display_name' => Setting::lockedAttributesDisplayName($key),
            'locked' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'cover' => 'nullable|image|max:1024',
            'logo' => 'nullable|image|max:1024',
            'shop_name' => 'required|max:100',
            'shop_legal_name' => 'required|max:100',
            'shop_email' => 'required|email',
            'shop_country_id' => 'required',
            'shop_currency_id' => 'required',
            'shop_street_address' => 'required|string',
            'shop_zipcode' => 'required',
            'shop_city' => 'required',
        ];
    }

    public function removeCover(): void
    {
        $this->cover = null;
    }

    public function deleteCover(): void
    {
        Setting::query()->updateOrCreate(['key' => 'shop_cover'], [
            'value' => null,
            'display_name' => Setting::lockedAttributesDisplayName('shop_cover'),
            'locked' => true,
        ]);

        $this->shop_cover = null;

        $this->notification()->success(__('shopper::layout.status.delete'), __('Shop cover have been correctly removed!'));
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.general', [
            'countries' => Cache::rememberForever('countries', fn () => Country::query()->orderBy('name')->get()),
            'currencies' => Cache::rememberForever('currencies', fn () => Currency::all()),
        ]);
    }
}
