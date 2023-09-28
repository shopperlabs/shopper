<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Setting;
use Shopper\Core\Repositories\ChannelRepository;
use Shopper\Traits\HasAuthenticated;

final class Initialization extends Component
{
    use HasAuthenticated;

    public string $shop_name = '';

    public string $shop_email = '';

    public string $shop_street_address = '';

    public string $shop_zipcode = '';

    public string $shop_city = '';

    public ?string $shop_phone_number = null;

    public string $shop_about = '';

    public ?int $shop_country_id = null;

    public $selectedCountryId = [];

    public ?string $shop_facebook_link = null;

    public ?string $shop_instagram_link = null;

    public ?string $shop_twitter_link = null;

    public ?string $shop_lng = null;

    public ?string $shop_lat = null;

    public int $shop_currency_id;

    protected $rules = [
        'shop_name' => 'required|max:100',
        'shop_email' => 'required|email',
        'shop_country_id' => 'required',
        'shop_street_address' => 'required|string',
        'shop_zipcode' => 'required',
        'shop_city' => 'required',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function mount(): void
    {
        $defaultCurrency = Currency::query()->where('code', shopper_currency())->first();
        $this->shop_currency_id = (int) $defaultCurrency->id;
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->shop_about = $value;
    }

    public function stepOneState(): bool
    {
        return $this->shop_email && $this->shop_name && $this->shop_country_id;
    }

    public function stepTwoState(): bool
    {
        return $this->shop_street_address && $this->shop_city && $this->shop_zipcode;
    }

    public function stepTreeState(): bool
    {
        return $this->shop_facebook_link || $this->shop_instagram_link || $this->shop_twitter_link;
    }

    public function messages(): array
    {
        return [
            'shop_country_id.required' => __('shopper::pages/settings.validations.country'),
            'shop_name.required' => __('shopper::pages/settings.validations.shop_name'),
        ];
    }

    public function store(): void
    {
        $this->validate();

        $keys = [
            'shop_name',
            'shop_email',
            'shop_about',
            'shop_country_id',
            'shop_currency_id',
            'shop_street_address',
            'shop_zipcode',
            'shop_city',
            'shop_phone_number',
            'shop_lng',
            'shop_lat',
            'shop_facebook_link',
            'shop_instagram_link',
            'shop_twitter_link',
        ];

        foreach ($keys as $key) {
            Setting::query()->updateOrCreate(['key' => $key], [
                'value' => $this->{$key},
                'display_name' => Setting::lockedAttributesDisplayName($key),
                'locked' => true,
            ]);
        }

        $this->storeHasSetup();

        session()->flash('success', __('shopper::notifications.initialize'));

        $this->redirectRoute('shopper.dashboard');
    }

    public function storeHasSetup(): void
    {
        Inventory::query()->create([
            'name' => $this->shop_name,
            'code' => Str::slug($this->shop_name),
            'email' => $this->shop_email,
            'street_address' => $this->shop_street_address,
            'zipcode' => $this->shop_zipcode,
            'city' => $this->shop_city,
            'phone_number' => $this->shop_phone_number,
            'country_id' => $this->shop_country_id,
            'longitude' => $this->shop_lng,
            'latitude' => $this->shop_lat,
            'is_default' => true,
        ]);

        (new ChannelRepository())->create([
            'name' => $name = 'Web Store',
            'slug' => $name,
            'url' => config('app.url'),
            'is_default' => true,
        ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.initialization', [
            'countries' => Cache::get('countries-settings', fn () => Country::query()->orderBy('name')->get()),
            'currencies' => Cache::get('currencies-setting', fn () => Currency::query()->orderBy('name')->get()),
        ])->layout('shopper::components.layouts.base', [
            'title' => __('shopper::pages/settings.initialization.title'),
        ]);
    }
}
