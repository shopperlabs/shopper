<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\Shop\Discount;
use Shopper\Framework\Models\Shop\DiscountDetail;
use Shopper\Framework\Models\Traits\HasPrice;
use Shopper\Framework\Models\User\User;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\UserRepository;

class Edit extends AbstractBaseComponent
{
    use HasPrice, WithDiscountAttributes, WithDiscountActions;

    public Discount $discount;
    public int $discountId;

    protected $listeners = ['addSelectedProducts', 'addSelectedCustomers'];

    public function mount(Discount $discount)
    {
        $this->discount = $discount->load('items');
        $this->discountId = $discount->id;

        $this->code = $discount->code;
        $this->type = $discount->type;
        $this->value = $discount->value;
        $this->apply = $discount->apply_to;
        $this->eligibility = $discount->eligibility;
        $this->usage_limit = $discount->usage_limit;
        $this->usage_number = $discount->usage_limit !== null;
        $this->usage_limit_per_user = $discount->usage_limit_per_user;
        $this->is_active = $discount->is_active;
        $this->dateStart = $discount->start_at->format('Y-m-d H:m');

        if ($discount->end_at) {
            $this->dateEnd = $discount->end_at->format('Y-m-d H:m');
        }

        if ($discount->items()->where('condition', 'eligibility')->exists()) {
            $customerConditions = $discount->items()
                ->with('discountable')
                ->where('condition', 'eligibility')
                ->get();
            $customers = collect();
            foreach ($customerConditions as $customerCondition) {
                $customers->push($customerCondition->discountable);
            }
            $this->selectedCustomers = $customers->pluck('id')->all();

            $this->customers = (new  UserRepository())
                ->makeModel()
                ->whereIn('id', $this->selectedCustomers)
                ->get();
        }

        if ($discount->items()->where('condition', 'apply_to')->exists()) {
            $productConditions = $discount->items()
                ->with('discountable')
                ->where('condition', 'apply_to')
                ->get();
            $products = collect();
            foreach ($productConditions as $productCondition) {
                $products->push($productCondition->discountable);
            }
            $this->selectedProducts = $products->pluck('id')->all();

            $this->products = (new ProductRepository())
                ->makeModel()
                ->whereIn('id', $this->selectedProducts)
                ->get();
        }
    }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'required',
                Rule::unique(shopper_table('discounts'), 'code')->ignore($this->discountId),
            ],
            'type' => 'sometimes|required',
            'value' => 'sometimes|required',
            'apply' => 'sometimes|required',
            'dateStart' => 'sometimes|required',
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

        Discount::query()->find($this->discountId)->update([
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
            $this->discount->items()
                ->where('condition', 'apply_to')
                ->whereNotIn('discountable_id', $this->selectedProducts)
                ->delete();

            foreach ($this->selectedProducts as $productId) {
                DiscountDetail::query()->updateOrCreate([
                    'discount_id' => $this->discountId,
                    'discountable_id' => $productId,
                ], [
                    'condition' => 'apply_to',
                    'discountable_type' => config('shopper.system.models.product'),
                ]);
            }
        } else {
            $this->discount->items()->where('condition', 'apply_to')->delete();
        }

        if ($this->eligibility === 'customers') {
            $this->discount->items()
                ->where('condition', 'eligibility')
                ->whereNotIn('discountable_id', $this->selectedCustomers)
                ->delete();

            foreach ($this->selectedCustomers as $customerId) {
                DiscountDetail::query()->updateOrCreate([
                    'discount_id' => $this->discountId,
                    'discountable_id' => $customerId,
                ], [
                    'condition' => 'eligibility',
                    'discountable_type' => config('auth.providers.users.model', User::class),
                ]);
            }
        } else {
            $this->discount->items()->where('condition', 'eligibility')->delete();
        }

        session()->flash('success', __('shopper::pages/discounts.update_message', ['code' => $this->code]));

        $this->redirectRoute('shopper.discounts.index');
    }

    public function render()
    {
        return view('shopper::livewire.discounts.edit');
    }
}
