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
    public $photo;

    public function mount()
    {
        $defaultCurrency = Currency::where('code', shopper_currency())->first();
        $this->currency_id = $defaultCurrency ? $defaultCurrency->id : 1;
    }

    public function render()
    {
        $sizes = ShopSize::all();
        $currencies = Currency::all();

        return view('shopper::components.livewire.initialization', compact('sizes', 'currencies'));
    }
}