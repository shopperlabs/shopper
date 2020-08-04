<?php

namespace Shopper\Framework\Http\Components\Livewire\Discount;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Framework\Models\DiscountDetail;
use Shopper\Framework\Models\User;
use Shopper\Framework\Repositories\DiscountRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\UserRepository;

class Edit extends Component
{
    use Actions;

    public $discountId;

    public function mount($discountId)
    {
        $discount = (new DiscountRepository())->getById($discountId);
        $this->discountId = $discountId;

        $this->code = $discount->code;
        $this->type = $discount->type;
        $this->value = $discount->value;
        $this->apply = $discount->apply_to;
        $this->is_active = $discount->is_active;
        $this->dateStart = $discount->date_start->format('Y-m-d');
        $this->timeStart = $discount->date_start->format('H:m');

        if ($discount->date_end) {
            $this->dateEnd = $discount->date_end->format('Y-m-d');
            $this->timeEnd = $discount->date_end->format('H:m');
        }
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
            'apply' => 'required',
            'dateStart' => 'required',
            'timeStart' => 'required',
        ];
    }

    public function store()
    {
        $validationRules = $this->rules();

        if ($this->minRequired !== 'none') {
            array_merge($this->rules(), ['minRequiredValue' => 'required']);
        }

        if ($this->usage_number) {
            array_merge($validationRules, ['usage_limit' => 'required']);
        }

        $this->validate($validationRules);
        $dateStart = $this->dateStart. " ".$this->timeStart;
        $dateEnd   = $this->dateEnd. " ".$this->timeEnd;

        $discount = (new DiscountRepository())->create([
            'is_active' => $this->is_active,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'apply_to' => $this->apply,
            'min_required' => $this->minRequired,
            'min_required_value' => $this->minRequired !== 'none' ? $this->minRequiredValue : null,
            'eligibility' => $this->eligibility,
            'usage_limit' => $this->usage_number ? $this->usage_limit: null,
            'usage_limit_per_user'  => $this->usage_limit_per_user === 'ONCE_PER_CUSTOMER_LIMIT',
            'date_start' => Carbon::createFromFormat('Y-m-d H:i', $dateStart)->toDateTimeString(),
            'date_end'  => $this->set_end_date ? Carbon::createFromFormat('Y-m-d H:i', $dateEnd)->toDateTimeString() : null,
        ]);

        if ($this->apply === 'products') {
            // Associate the discount to all the selected products.
            foreach ($this->productsIds as $productId) {
                DiscountDetail::create([
                   'discount_id' => $discount->id,
                    'condition' => 'apply_to',
                    'discountable_type' => config('shopper.models.product'),
                    'discountable_id' => $productId,
                ]);
            }
        }

        if ($this->eligibility === 'customers') {
            // Associate the discount to all the selected users.
            foreach ($this->customersIds as $customerId) {
                DiscountDetail::create([
                    'discount_id' => $discount->id,
                    'condition' => 'eligibility',
                    'discountable_type' => config('auth.providers.users.model', User::class),
                    'discountable_id' => $customerId,
                ]);
            }
        }

        session()->flash('success', __("Discount code {$discount->code} updated successfully"));
        $this->redirectRoute('shopper.discounts.index');
    }

    public function render()
    {
        $this->products = (new ProductRepository())
            ->orderBy('name', 'asc')
            ->where('name', '%'. $this->searchProduct .'%', 'like')
            ->get(['name', 'price', 'id'])
            ->except($this->productsIds);

        $this->customers = (new UserRepository())
            ->makeModel()
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', config('shopper.users.default_role'));
            })
            ->orderBy('created_at', 'asc')
            ->where('first_name', 'like', '%' . $this->searchCustomer . '%')
            ->get()
            ->except($this->customersIds);

        return view('shopper::components.livewire.discounts.edit');
    }
}
