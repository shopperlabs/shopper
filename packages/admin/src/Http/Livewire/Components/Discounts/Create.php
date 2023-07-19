<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Discounts;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\User;
use Shopper\Core\Traits\HasPrice;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent
{
    use HasPrice;
    use WithDiscountActions;
    use WithDiscountAttributes;

    protected $listeners = ['addSelectedProducts', 'addSelectedCustomers'];

    public function mount(): void
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

    public function store(): void
    {
        if ('none' !== $this->minRequired) {
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
            'min_required_value' => 'none' !== $this->minRequired ? $this->minRequiredValue : null,
            'eligibility' => $this->eligibility,
            'usage_limit' => $this->usage_limit ?? null,
            'usage_limit_per_user' => $this->usage_limit_per_user,
            'start_at' => Carbon::createFromFormat('Y-m-d H:i', $this->dateStart)->toDateTimeString(),
            'end_at' => $this->dateEnd ? Carbon::createFromFormat('Y-m-d H:i', $this->dateEnd)->toDateTimeString() : null,
        ]);

        if ('products' === $this->apply) {
            // Associate the discount to all the selected products.
            foreach ($this->selectedProducts as $productId) {
                DiscountDetail::query()->create([
                    'discount_id' => $discount->id,
                    'condition' => 'apply_to',
                    'discountable_type' => config('shopper.models.product'),
                    'discountable_id' => $productId,
                ]);
            }
        }

        if ('customers' === $this->eligibility) {
            // Associate the discount to all the selected users.
            foreach ($this->selectedCustomers as $customerId) {
                DiscountDetail::query()->create([
                    'discount_id' => $discount->id,
                    'condition' => 'eligibility',
                    'discountable_type' => config('auth.providers.users.model', User::class),
                    'discountable_id' => $customerId,
                ]);
            }
        }

        session()->flash('success', __('shopper::pages/discounts.add_message', ['code' => $discount->code]));

        $this->redirectRoute('shopper.discounts.index');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.discounts.create');
    }
}
