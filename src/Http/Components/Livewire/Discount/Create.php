<?php

namespace Shopper\Framework\Http\Components\Livewire\Discount;

use Livewire\Component;

class Create extends Component
{
    public $code = '';
    public $type = 'percentage';
    public $value = '';

    /**
     * Determine if the discount information are empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        if (empty($this->code)) {
            return true;
        }

        return false;
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'code' => 'required|unique:'. shopper_table('discounts'),
            'type' => 'required',
            'value' => 'required',
        ];
    }

    public function generate()
    {
        $this->code = substr(strtoupper(uniqid(str_random(10))), 0, 10);
    }

    public function store()
    {

    }

    public function render()
    {
        return view('shopper::components.livewire.discounts.create');
    }
}