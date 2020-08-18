<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Shopper\Framework\Models\Currency;
use Shopper\Framework\Models\Shop\ShopSize;

class Initialization extends Component
{
    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $description = '';
    public $currency_id = '';
    public $size_id;
    public $logo;

    public function mount()
    {
        $defaultCurrency = Currency::where('code', shopper_currency())->first();
        $this->currency_id = $defaultCurrency ? $defaultCurrency->id : 1;
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    public function store()
    {
        dd($this->size_id);
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
            'phone_number' => 'required',
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