<?php

namespace Shopper\Framework\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Models\System\Currency;
use Shopper\Framework\Models\System\Setting;
use Shopper\Framework\Repositories\ChannelRepository;
use Shopper\Framework\Repositories\InventoryRepository;

class Initialization extends Component
{
    use WithFileUploads;

    public string $shop_name = '';
    public string $shop_email = '';
    public string $shop_street_address = '';
    public string $shop_zipcode = '';
    public string $shop_city = '';
    public ?string $shop_phone_number = null;
    public string $shop_about = '';
    public ?int $shop_country_id = null;
    public ?string $shop_facebook_link = null;
    public ?string $shop_instagram_link = null;
    public ?string $shop_twitter_link = null;
    public bool $isDefault = true;
    public ?string $shop_lng = null;
    public ?string $shop_lat = null;
    public int $shop_currency_id;
    public $logo;

    protected $rules = [
        'shop_name' => 'required|max:100',
        'shop_email' => 'required|email',
        'logo' => 'nullable|image|max:1024',
        'shop_country_id' => 'required',
        'shop_street_address' => 'required|string',
        'shop_zipcode' => 'required',
        'shop_city' => 'required',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function mount()
    {
        $defaultCurrency = Currency::where('code', shopper_currency())->first();
        $this->shop_currency_id = (int) $defaultCurrency->id;
    }

    public function onTrixValueUpdate($value)
    {
        $this->shop_about = $value;
    }

    public function stepOneState(): bool
    {
        if ($this->shop_email && $this->shop_name && $this->shop_country_id) {
            return true;
        }

        return false;
    }

    public function stepTwoState(): bool
    {
        if ($this->shop_street_address && $this->shop_city && $this->shop_zipcode) {
            return true;
        }

        return false;
    }

    public function stepTreeState(): bool
    {
        if ($this->shop_facebook_link || $this->shop_instagram_link || $this->shop_twitter_link) {
            return true;
        }

        return false;
    }

    public function updatedShopCountryId($value)
    {
        $country = Country::find($value);
        $countryCurrency = array_slice($country->currencies, 0, 1);

        foreach ($countryCurrency as $code => $name) {
            if ($currency = Currency::where('code', $code)->first()) {
                $this->shop_currency_id = $currency->id;
            }
        }
    }

    public function messages(): array
    {
        return [
            'shop_country_id.required' => __('The country is required'),
            'shop_name.required' => __('The store name is required'),
        ];
    }

    public function store()
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

        if ($this->logo) {
            Setting::query()->updateOrCreate(['key' => 'shop_logo'], [
                'value' => $this->logo->store('/', config('shopper.system.storage.disks.uploads')),
                'display_name' => Setting::lockedAttributesDisplayName('shop_logo'),
                'locked' => true,
            ]);
        }

        $this->storeHasSetup();

        session()->flash('success', __('Store successfully setup, you can now manage everything.'));

        $this->redirectRoute('shopper.dashboard');
    }

    public function storeHasSetup()
    {
        (new InventoryRepository())->create([
            'name' => $this->shop_name,
            'code' => str_slug($this->shop_name),
            'email' => $this->shop_email,
            'street_address' => $this->shop_street_address,
            'zipcode' => $this->shop_zipcode,
            'city' => $this->shop_city,
            'phone_number' => $this->shop_phone_number,
            'country_id' => $this->shop_country_id,
            'longitude' => $this->shop_lng,
            'latitude' => $this->shop_lat,
            'is_default' => $this->isDefault,
        ]);

        (new ChannelRepository())->create([
            'name' => $name = __('Web Store'),
            'slug' => str_slug($name),
            'url' => env('APP_URL'),
            'is_default' => true,
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.initialization', [
            'countries' => Cache::rememberForever('countries-settings', fn () => Country::select('name', 'id')->orderBy('name')->get()),
            'currencies' => Cache::rememberForever('currencies-setting', fn () => Currency::select('name', 'code', 'id')->orderBy('name')->get()),
        ]);
    }
}
