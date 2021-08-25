<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

use Carbon\Carbon;
use Shopper\Framework\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Models\Shop\Discount;
use Shopper\Framework\Models\Traits\HasPrice;
use Shopper\Framework\Models\Shop\DiscountDetail;
use Shopper\Framework\Repositories\UserRepository;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Create extends AbstractBaseComponent
{
    use WithDiscountAttributes;
    use WithDiscountActions;
    use HasPrice;

    /**
     * Component Mount instance.
     */
    public function mount()
    {
        $this->dateStart = now()->format('Y-m-d H:i');
    }

    public function rules(): array
    {
        return [
            'code' => 'required|unique:' . shopper_table('discounts'),
            'type' => 'required',
            'value' => 'required',
            'apply' => 'required',
            'dateStart' => 'required',
        ];
    }

    public function store()
    {
        if ($this->minRequired !== 'none') {
            $this->validate(['minRequiredValue' => 'required']);
        }

        if ($this->usage_number) {
            $this->validate(['usage_limit' => 'required|integer']);
        }

        $this->validate($this->rules());

        $discount = Discount::query()->create([
            'is_active' => $this->is_active,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'apply_to' => $this->apply,
            'min_required' => $this->minRequired,
            'min_required_value' => $this->minRequired !== 'none' ? $this->minRequiredValue : null,
            'eligibility' => $this->eligibility,
            'usage_limit' => $this->usage_limit ?? null,
            'usage_limit_per_user' => $this->usage_limit_per_user,
            'start_at' => Carbon::createFromFormat('Y-m-d H:i', $this->dateStart)->toDateTimeString(),
            'end_at' => $this->dateEnd ? Carbon::createFromFormat('Y-m-d H:i', $this->dateEnd)->toDateTimeString() : null,
        ]);

        if ($this->apply === 'products') {
            // Associate the discount to all the selected products.
            foreach ($this->productsIds as $productId) {
                DiscountDetail::query()->create([
                    'discount_id' => $discount->id,
                    'condition' => 'apply_to',
                    'discountable_type' => config('shopper.system.models.product'),
                    'discountable_id' => $productId,
                ]);
            }
        }

        if ($this->eligibility === 'customers') {
            // Associate the discount to all the selected users.
            foreach ($this->customersIds as $customerId) {
                DiscountDetail::query()->create([
                    'discount_id' => $discount->id,
                    'condition' => 'eligibility',
                    'discountable_type' => config('auth.providers.users.model', User::class),
                    'discountable_id' => $customerId,
                ]);
            }
        }

        session()->flash('success', __('Discount code :code created successfully', ['code' => $discount->code]));

        $this->redirectRoute('shopper.discounts.index');
    }

    /**
     * Render the component.
     *
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        $this->products = (new ProductRepository())
            ->where('name', '%' . $this->searchProduct . '%', 'like')
            ->orderBy('name')
            ->get(['name', 'price_amount', 'id'])
            ->except($this->productsIds);

        $this->customers = (new UserRepository())
            ->makeModel()
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', config('shopper.system.users.default_role'));
            })
            ->where(function (Builder $query) {
                $query->where('first_name', 'like', '%' . $this->searchCustomer . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchCustomer . '%');
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->except($this->customersIds);

        return view('shopper::livewire.discounts.create');
    }
}
