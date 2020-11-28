<?php

namespace Shopper\Framework\Http\Livewire\Discount;

use Carbon\Carbon;
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
    use WithDiscountAttributes, WithDiscountActions, HasPrice;

    /**
     * Current updated discount.
     *
     * @var Discount
     */
    public Discount $discount;

    /**
     * Discount id for validation.
     *
     * @var int
     */
    public $discountId;

    /**
     * Component Mount instance.
     *
     * @param  Discount  $discount
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
            $customers = $discount->items()->where('condition', 'eligibility')->get();
            foreach ($customers as $customer) {
                $customerArray['id'] = $customer->discountable->id;
                $customerArray['name'] = $customer->discountable->full_name;
                $customerArray['email'] = $customer->discountable->email;

                array_push($this->customersDetails, $customerArray);
                array_push($this->customersIds, $customer->discountable->id);
            }
        }

        if ($discount->items()->where('condition', 'apply_to')->count() > 0) {
            $products = $discount->items()->where('condition', 'apply_to')->get();
            foreach ($products as $product) {
                $productArray['id'] = $product->discountable->id;
                $productArray['name'] = $product->discountable->name;
                $productArray['image'] = $product->discountable->preview_image_link;

                array_push($this->productsDetails, $productArray);
                array_push($this->productsIds, $product->discountable->id);
            }
        }
    }

    /**
     * Store a newly entry to the database.
     *
     * @return void
     */
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
            'usage_limit_per_user'  => $this->usage_limit_per_user,
            'start_at' => Carbon::createFromFormat('Y-m-d H:i', $this->dateStart)->toDateTimeString(),
            'end_at'  => $this->dateEnd ? Carbon::createFromFormat('Y-m-d H:i', $this->dateEnd)->toDateTimeString() : null,
        ]);

        if ($this->apply === 'products') {
            // Associate the discount to all the selected products.
            foreach ($this->productsIds as $productId) {
                DiscountDetail::query()->create([
                   'discount_id' => $this->discountId,
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
                    'discount_id' => $this->discountId,
                    'condition' => 'eligibility',
                    'discountable_type' => config('auth.providers.users.model', User::class),
                    'discountable_id' => $customerId,
                ]);
            }
        }

        session()->flash('success', __("Discount code {$this->code} updated successfully!"));
        $this->redirectRoute('shopper.discounts.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'code' => [
                'sometimes',
                'required',
                Rule::unique(shopper_table('discounts'), 'code')->ignore($this->discountId)
            ],
            'type' => 'sometimes|required',
            'value' => 'sometimes|required',
            'apply' => 'sometimes|required',
            'dateStart' => 'sometimes|required',
        ];
    }

    /**
     * Remove a entry to the storage.
     *
     * @throws \Exception
     */
    public function destroy()
    {
        Discount::query()->find($this->discountId)->delete();

        session()->flash('success', __("Remove discount successfully"));
        $this->redirectRoute('shopper.discounts.index');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        $this->products = (new ProductRepository())
            ->orderBy('name', 'asc')
            ->where('name', '%'. $this->searchProduct .'%', 'like')
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
