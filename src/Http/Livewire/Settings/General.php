<?php

namespace Shopper\Framework\Http\Livewire\Settings;

use function array_slice;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Models\System\Currency;
use Shopper\Framework\Models\System\Setting;

class General extends Component
{
    use WithFileUploads;

    public string $shop_name;
    public ?string $shop_legal_name = null;
    public string $shop_email;
    public ?string $shop_phone_number = null;
    public ?string $shop_about = null;
    public string $shop_street_address;
    public string $shop_zipcode;
    public string $shop_city;
    public ?string $shop_facebook_link = null;
    public ?string $shop_instagram_link = null;
    public ?string $shop_twitter_link = null;
    public $shop_country_id;
    public $shop_currency_id;
    public $shop_logo;
    public $logo;
    public $shop_cover;
    public $cover;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function mount()
    {
        $this->shop_name = ($name = Setting::where('key', 'shop_name')->first()) ? $name->value : '';
        $this->shop_legal_name = ($legalName = Setting::where('key', 'shop_legal_name')->first()) ? $legalName->value : '';
        $this->shop_name = ($name = Setting::where('key', 'shop_name')->first()) ? $name->value : '';
        $this->shop_email = ($email = Setting::where('key', 'shop_email')->first()) ? $email->value : '';
        $this->shop_about = ($about = Setting::where('key', 'shop_about')->first()) ? $about->value : '';
        $this->shop_phone_number = ($phone = Setting::where('key', 'shop_phone_number')->first()) ? $phone->value : '';
        $this->logo = ($logo = Setting::where('key', 'shop_logo')->first()) ? $logo->value : '';
        $this->cover = ($cover = Setting::where('key', 'shop_cover')->first()) ? $cover->value : '';
        $this->shop_street_address = ($street = Setting::where('key', 'shop_street_address')->first()) ? $street->value : '';
        $this->shop_zipcode = ($zipcode = Setting::where('key', 'shop_zipcode')->first()) ? $zipcode->value : '';
        $this->shop_city = ($city = Setting::where('key', 'shop_city')->first()) ? $city->value : '';
        $this->shop_country_id = ($country = Setting::where('key', 'shop_country_id')->first()) ? $country->value : '';
        $this->shop_currency_id = ($currency = Setting::where('key', 'shop_currency_id')->first()) ? $currency->value : '';
        $this->shop_facebook_link = ($facebook = Setting::where('key', 'shop_facebook_link')->first()) ? $facebook->value : '';
        $this->shop_instagram_link = ($instagram = Setting::where('key', 'shop_instagram_link')->first()) ? $instagram->value : '';
        $this->shop_twitter_link = ($twitter = Setting::where('key', 'shop_twitter_link')->first()) ? $twitter->value : '';
    }

    public function onTrixValueUpdate($value)
    {
        $this->shop_about = $value;
    }

    public function updatedShopCountryId($value)
    {
        $country = Country::query()->find($value);
        $countryCurrency = array_slice($country->currencies, 0, 1);

        foreach ($countryCurrency as $code => $name) {
            $currency = Currency::where('code', $code)->first();
            if ($currency->exists()) {
                $this->shop_currency_id = $currency->id;
            }
        }
    }

    /**
     * Real-Time validation.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updated(string $field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Update setting to the storage.
     */
    public function store()
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
            Setting::query()->updateOrCreate(['key' => $key], [
                'value' => $this->{$key},
                'display_name' => Setting::lockedAttributesDisplayName($key),
                'locked' => true,
            ]);
        }

        if ($this->shop_logo) {
            Setting::query()->updateOrCreate(['key' => 'shop_logo'], [
                'value' => $this->shop_logo->store('/', config('shopper.system.storage.disks.uploads')),
                'display_name' => Setting::lockedAttributesDisplayName('shop_logo'),
                'locked' => true,
            ]);
        }

        if ($this->shop_cover) {
            Setting::query()->updateOrCreate(['key' => 'shop_cover'], [
                'value' => $this->shop_cover->store('/', config('shopper.system.storage.disks.uploads')),
                'display_name' => Setting::lockedAttributesDisplayName('shop_cover'),
                'locked' => true,
            ]);
        }

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Shop informations have been correctly updated!'),
        ]);
    }

    /**
     * Validation rules.
     *
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            'shop_name' => 'required|max:100',
            'shop_legal_name' => 'required|max:100',
            'shop_email' => 'required|email',
            'shop_logo' => 'nullable|image|size:1024',
            'shop_cover' => 'nullable|image|size:1024',
            'shop_country_id' => 'required',
            'shop_currency_id' => 'required',
            'shop_street_address' => 'required|string',
            'shop_zipcode' => 'required',
            'shop_city' => 'required',
        ];
    }

    /**
     * Remove cover.
     */
    public function removeCover()
    {
        $this->shop_cover = null;
    }

    /**
     * Remove cover from the storage.
     */
    public function deleteCover()
    {
        Setting::query()->updateOrCreate(['key' => 'shop_cover'], [
            'value' => null,
            'display_name' => Setting::lockedAttributesDisplayName('shop_cover'),
            'locked' => true,
        ]);

        $this->cover = null;

        $this->notify([
            'title' => __('Deleted'),
            'message' => __('Shop cover have been correctly removed!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.settings.general', [
            'countries' => Country::query()->orderBy('name')->get(),
            'currencies' => Currency::all(),
        ]);
    }
}
