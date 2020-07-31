<?php

namespace Shopper\Framework\Http\Components\Livewire\Discount;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Framework\Repositories\DiscountRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\UserRepository;

class Create extends Component
{
    public $code = '';
    public $type = 'percentage';
    public $value = '';
    public $apply = 'order';
    public $minRequired = 'none'; // price, quantity
    public $minRequiredValue = '';
    public $is_active = false;
    public $eligibility = 'everyone';
    public $usage_number = null;
    public $usage_limit = '';
    public $usage_limit_per_user = '';
    public $dateStart = '';
    public $timeStart = '';
    public $set_end_date = '';
    public $dateEnd = '';
    public $timeEnd = '';

    public $searchProduct = '';
    public $searchCustomer = '';

    public $products;
    public $customers;

    public $productsDetails = [];
    public $customersDetails = [];

    public $productsIds = [];
    public $customersIds = [];

    public $selectedProducts = [];
    public $selectedCustomers = [];

    public function mount()
    {
        $this->dateStart = now()->format('Y-m-d');
        $this->timeStart = now()->format('H:m');
        $this->dateEnd = now()->format('Y-m-d');
        $this->timeEnd = now()->format('H:m');
    }

    /**
     * Determine if the discount information are empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        if (
            empty($this->code) &&
            empty($this->minRequiredValue) &&
            empty($this->value) &&
            empty($this->usage_limit)
        ) {
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
            'apply' => 'required',
            'dateStart' => 'required',
            'timeStart' => 'required',
        ];
    }

    /**
     * Generate Discount code.
     */
    public function generate()
    {
        $this->code = substr(strtoupper(uniqid(str_random(10))), 0, 10);
    }

    /**
     * Return Apply offer product.
     *
     * @return string
     */
    public function getProductSize()
    {
        if (count($this->productsDetails) === 0) {
            return __('products');
        }

        if (count($this->productsDetails) === 1) {
            return array_slice($this->productsDetails, 0, 1)[0]['name'];
        }

        if (count($this->productsDetails) > 1) {
            return __(':count products', ['count' => count($this->productsDetails)]);
        }
    }

    /**
     * Return Apply offer product.
     *
     * @return string
     */
    public function getCustomSize()
    {
        if (count($this->customersDetails) === 0 || $this->eligibility === 'everyone') {
            return __('For everyone');
        }

        if (count($this->customersDetails) === 1) {
            return __('For :name', ['name' => array_slice($this->customersDetails, 0, 1)[0]['name']]);
        }

        if (count($this->customersDetails) > 1) {
            return __('For :count customers', ['count' => count($this->customersDetails)]);
        }

        return null;
    }

    /**
     * Return discount active date.
     *
     * @return string|null
     * @throws \Exception
     */
    public function getDateWord()
    {
        $today = Carbon::today();
        $startDate = new Carbon($this->dateStart);
        $endDate = new Carbon($this->dateEnd);

        if ($today->equalTo($startDate) && $today->equalTo($endDate) && $this->set_end_date === 'active') {
            return __("Active today");
        }

        if ($today->equalTo($startDate) && $this->set_end_date !== 'active') {
            return __("Active from today");
        }

        if ($today->notEqualTo($startDate) && $this->set_end_date !== 'active') {
            return __("Active from :date", ['date' => $startDate->format('d M')]);
        }

        if ($today->notEqualTo($startDate) && $startDate->equalTo($endDate)) {
            return __("Active :date", ['date' => $startDate->format('d M')]);
        }

        if ($startDate->notEqualTo($endDate) && $startDate->lessThan($endDate) && $this->set_end_date === 'active') {
            return __("Active from :startDate to :endDate", [
                'startDate' => $startDate->format('d M'),
                'endDate'   => $endDate->format('d M'),
            ]);
        }

        if ($startDate->greaterThan($endDate) && $this->set_end_date === 'active') {
            $this->dateEnd = Carbon::createFromFormat('Y-m-d', $this->dateStart)->toDateString();

            return __("Active :date", ['date' => $startDate->format('d M')]);
        }

        return null;
    }

    /**
     * Return Usage limit message.
     *
     * @return string|null
     */
    public function getUsageLimitMessage()
    {
        if ($this->usage_number && $this->usage_limit !== '' && (int) $this->usage_limit > 0) {
            $message = trans_choice('shopper::messages.discount_use', $this->usage_limit, ['count' => $this->usage_limit]);
            $message .= $this->usage_limit_per_user === 'ONCE_PER_CUSTOMER_LIMIT' ? ', '. __("one per customer") : '';

            return $message;
        }

        if ($this->usage_limit_per_user === 'ONCE_PER_CUSTOMER_LIMIT' && !$this->usage_number) {
            return __("One per customer");
        }

        return null;
    }

    /**
     * Remove product to the products list and the productIds restriction.
     *
     * @param  int  $key
     * @param  int  $id
     */
    public function removeProduct($key, $id)
    {
        unset($this->productsDetails[$key]);

        foreach (array_keys($this->productsIds, $id) as $key) {
            unset($this->productsIds[$key]);
        }
    }

    /**
     * Remove customer to the customer list and the customersIds restriction.
     *
     * @param  int  $key
     * @param  int  $id
     */
    public function removeCustomer($key, $id)
    {
        unset($this->customersDetails[$key]);

        foreach (array_keys($this->customersIds, $id) as $key) {
            unset($this->customersIds[$key]);
        }
    }

    public function addProducts()
    {
        if (count($this->selectedProducts) > 0) {
            foreach ($this->selectedProducts as $selectedProduct) {
                $product = (new ProductRepository())->getById($selectedProduct);
                $productArray['id'] = $product->id;
                $productArray['name'] = $product->name;
                $productArray['image'] = $product->preview_image_link;

                array_push($this->productsDetails, $productArray);
                array_push($this->productsIds, $product->id);
            }

            $this->selectedProducts = [];
            $this->dispatchBrowserEvent('products-added');
        }
    }

    public function addCustomers()
    {
        if (count($this->selectedCustomers) > 0) {
            foreach ($this->selectedCustomers as $selectedCustomer) {
                $customer = (new UserRepository())->getById($selectedCustomer);
                $customerArray['id'] = $customer->id;
                $customerArray['name'] = $customer->full_name;
                $customerArray['email'] = $customer->email;

                array_push($this->customersDetails, $customerArray);
                array_push($this->customersIds, $customer->id);
            }

            $this->selectedCustomers = [];
            $this->dispatchBrowserEvent('customers-added');
        }
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
        }

        if ($this->eligibility === 'customers') {
            // Associate the discount to all the selected users.
        }

        session()->flash('success', __("Discount code {$discount->code} created successfully"));
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

        return view('shopper::components.livewire.discounts.create');
    }
}