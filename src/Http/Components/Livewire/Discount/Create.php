<?php

namespace Shopper\Framework\Http\Components\Livewire\Discount;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\UserRepository;

class Create extends Component
{
    public $code = '';
    public $type = 'percentage';
    public $value = '';
    public $apply = 'order';
    public $minRequired = 'none';
    public $minRequiredValue = '';
    public $is_visible = false;
    public $eligibility = 'everyone';

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

    /**
     * Determine if the discount information are empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        if (empty($this->code) && empty($this->minRequiredValue) && empty($this->value)) {
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
        ];
    }

    /**
     * Generate Discount code.
     */
    public function generate()
    {
        $this->code = substr(strtoupper(uniqid(str_random(10))), 0, 10);
    }

    public function store()
    {

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