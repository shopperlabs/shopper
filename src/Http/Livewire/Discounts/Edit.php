<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Component Mount instance.
     */
    public function mount(Discount $discount)
    {
        $this->discount = $discount;
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

        if ($discount->items()->where('condition', 'eligibility')->count() > 0) {
            $customerConditions = $discount->items()->where('condition', 'eligibility')->get();
            foreach ($customerConditions as $customerCondition) {
                $customerArray['id'] = $customerCondition->discountable->id;
                $customerArray['name'] = $customerCondition->discountable->full_name;
                $customerArray['email'] = $customerCondition->discountable->email;

                array_push($this->customersDetails, $customerArray);
                array_push($this->customersIds, $customerCondition->discountable->id);
            }
        }

        if ($discount->items()->where('condition', 'apply_to')->count() > 0) {
            $productConditions = $discount->items()->where('condition', 'apply_to')->get();
            foreach ($productConditions as $productCondition) {
                $productArray['id'] = $productCondition->discountable->id;
                $productArray['name'] = $productCondition->discountable->name;
                $productArray['image'] = $productCondition->discountable->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'));

                array_push($this->productsDetails, $productArray);
                array_push($this->productsIds, $productCondition->discountable->id);
            }
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
                ->whereNotIn('discountable_id', $this->productsIds)
                ->delete();

            foreach ($this->productsIds as $productId) {
                DiscountDetail::query()->updateOrCreate([
                    'discount_id' => $this->discountId,
                    'discountable_id' => $productId,
                ], [
                    'condition' => 'apply_to',
                    'discountable_type' => config('shopper.system.models.product'),
                ]);
            }
        } else {
            $this->discount->items()->where('condition', 'apply')->delete();
        }

        if ($this->eligibility === 'customers') {
            $this->discount->items()
                ->where('condition', 'eligibility')
                ->whereNotIn('discountable_id', $this->customersIds)
                ->delete();

            foreach ($this->customersIds as $customerId) {
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

        session()->flash('success', __("Discount code {$this->code} updated successfully!"));

        $this->redirectRoute('shopper.discounts.index');
    }

    /**
     * Remove a entry to the storage.
     *
     * @throws Exception
     */
    public function remove()
    {
        Discount::query()->find($this->discountId)->delete();

        session()->flash('success', __('Remove discount successfully'));

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
            ->orderBy('name', 'asc')
            ->where('name', '%' . $this->searchProduct . '%', 'like')
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

        return view('shopper::livewire.discounts.edit');
    }
}
