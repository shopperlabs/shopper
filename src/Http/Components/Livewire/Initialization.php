<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Events\ShopCreated;
use Shopper\Framework\Models\Currency;
use Shopper\Framework\Models\Shop\ShopSize;
use Shopper\Framework\Repositories\Shop\ShopRepository;

class Initialization extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $description = '';
    public $facebook = '';
    public $instagram = '';
    public $twitter = '';
    public $currency_id = '';
    public $size_id;
    public $logo;

    public function mount()
    {
        $defaultCurrency = Currency::where('code', shopper_currency())->first();
        $this->currency_id = (string) $defaultCurrency->id;
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    public function stepOneState()
    {
        if ($this->size_id) {
            return true;
        }

        return false;
    }

    public function stepTwoState()
    {
        if ($this->email && $this->name) {
            return true;
        }

        return false;
    }

    public function stepTreeState()
    {
        if ($this->facebook || $this->instagram || $this->twitter) {
            return true;
        }

        return false;
    }

    public function store()
    {
        $this->validate($this->rules());

        $shop = (new ShopRepository())->create([
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
            'size_id' => $this->size_id,
            'currency_id' => $this->currency_id,
            'owner_id' => auth()->id(),
            'phone_number' => $this->phone_number,
            'facebook_url' => $this->facebook,
            'instagram_url' => $this->instagram,
            'twitter_url' => $this->twitter,
        ]);

        if ($this->logo) {
            $filename = $this->logo->store('/', config('shopper.storage.disks.uploads'));

            $shop->update(['logo_url' => $filename]);
        }

        event(new ShopCreated($shop));

        notify()->success(__('Your Shop has been successfully created. Have fun !'));
        $this->redirectRoute('shopper.dashboard');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required|unique:'. shopper_table('shops'),
            'email' => 'required|unique:'. shopper_table('shops'),
            'size_id' => 'required',
            'currency_id' => 'required',
            'logo' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    public function render()
    {
        $sizes = ShopSize::all();
        $currencies = Currency::all();

        return view('shopper::components.livewire.initialization', compact('sizes', 'currencies'));
    }
}