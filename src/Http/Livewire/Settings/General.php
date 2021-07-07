<?php

namespace Shopper\Framework\Http\Livewire\Settings;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Models\System\Currency;
use Shopper\Framework\Models\System\Setting;

class General extends Component
{
    use WithFileUploads;

    public $shop_name = '';
    public $shop_legal_name = '';
    public $shop_email = '';
    public $shop_phone_number = '';
    public $shop_about = '';
    public $shop_street_address = '';
    public $shop_zipcode = '';
    public $shop_city = '';
    public $shop_facebook_link = '';
    public $shop_instagram_link = '';
    public $shop_twitter_link = '';
    public $shop_logo;
    public $logo;
    public $shop_cover;
    public $cover;
    public $shop_country_id;
    public $shop_currency_id;

    public function mount()
    {
        $this->shop_name = ($name = Setting::query()->where('key', 'shop_name')->first()) ? $name->value: '';
        $this->shop_legal_name = ($legalName = Setting::query()->where('key', 'shop_legal_name')->first()) ? $legalName->value: '';
        $this->shop_name = ($name = Setting::query()->where('key', 'shop_name')->first()) ? $name->value: '';
        $this->shop_email = ($email = Setting::query()->where('key', 'shop_email')->first()) ? $email->value: '';
        $this->shop_about = ($about = Setting::query()->where('key', 'shop_about')->first()) ? $about->value: '';
        $this->shop_phone_number = ($phone = Setting::query()->where('key', 'shop_phone_number')->first()) ? $phone->value: '';
        $this->logo = ($logo = Setting::query()->where('key', 'shop_logo')->first()) ? $logo->value: '';
        $this->cover = ($cover = Setting::query()->where('key', 'shop_cover')->first()) ? $cover->value: '';
        $this->shop_street_address = ($street = Setting::query()->where('key', 'shop_street_address')->first()) ? $street->value: '';
        $this->shop_zipcode = ($zipcode = Setting::query()->where('key', 'shop_zipcode')->first()) ? $zipcode->value: '';
        $this->shop_city = ($city = Setting::query()->where('key', 'shop_city')->first()) ? $city->value: '';
        $this->shop_country_id = ($country = Setting::query()->where('key', 'shop_country_id')->first()) ? $country->value: '';
        $this->shop_currency_id = ($currency = Setting::query()->where('key', 'shop_currency_id')->first()) ? $currency->value: '';
        $this->shop_facebook_link = ($facebook = Setting::query()->where('key', 'shop_facebook_link')->first()) ? $facebook->value: '';
        $this->shop_instagram_link = ($instagram = Setting::query()->where('key', 'shop_instagram_link')->first()) ? $instagram->value: '';
        $this->shop_twitter_link = ($twitter = Setting::query()->where('key', 'shop_twitter_link')->first()) ? $twitter->value: '';
    }

    public function updatedShopCountryId($value)
    {
        $country = Country::query()->find($value);
        $countryCurrency = array_slice($country->currencies, 0, 1);

        foreach ($countryCurrency as $code => $name) {
            if ($currency = Currency::query()->where('code', $code)->first()) {
                $this->shop_currency_id = $currency->id;
            }
        }
    }

    /**
     * Real-Time validation.
     *
     * @param  string  $field
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Update setting to the storage.
     *
     * @return void
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
                'value'  => $this->{$key},
                'display_name' => Setting::lockedAttributesDisplayName($key),
                'locked' => true,
            ]);
        }

        if ($this->shop_logo) {
            Setting::query()->updateOrCreate(['key' => 'shop_logo',], [
                'value'  => $this->shop_logo->store('/', config('shopper.system.storage.disks.uploads')),
                'display_name' => Setting::lockedAttributesDisplayName('shop_logo'),
                'locked' => true,
            ]);
        }

        if ($this->shop_cover) {
            Setting::query()->updateOrCreate(['key' => 'shop_cover',], [
                'value'  => $this->shop_cover->store('/', config('shopper.system.storage.disks.uploads')),
                'display_name' => Setting::lockedAttributesDisplayName('shop_cover'),
                'locked' => true,
            ]);
        }

        $this->notify([
            'title' => __('Updated'),
            'message' => __("Shop informations have been correctly updated!")
        ]);
    }

    /**
     * Validation rules.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'shop_name'           => 'required|max:100',
            'shop_legal_name'     => 'required|max:100',
            'shop_email'          => 'required|email',
            'shop_logo'           => 'nullable|image|max:1024',
            'shop_cover'          => 'nullable|image|max:1024',
            'shop_country_id'     => 'required',
            'shop_currency_id'    => 'required',
            'shop_street_address' => 'required|string',
            'shop_zipcode'        => 'required',
            'shop_city'           => 'required',
        ];
    }

    /**
     * Remove cover.
     *
     * @return void
     */
    public function removeCover()
    {
        $this->shop_cover = null;
    }

    /**
     * Remove cover from the storage.
     *
     * @return void
     */
    public function deleteCover()
    {
        Setting::query()->updateOrCreate(['key' => 'shop_cover'], [
            'value'  => null,
            'display_name' => Setting::lockedAttributesDisplayName('shop_cover'),
            'locked' => true,
        ]);

        $this->cover = null;

        $this->notify([
            'title' => __('Deleted'),
            'message' => __("Shop cover have been correctly removed!")
        ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.general', [
            'countries'  => Country::query()->orderBy('name')->get(),
            'currencies' => Currency::all(),
        ]);
    }
}
