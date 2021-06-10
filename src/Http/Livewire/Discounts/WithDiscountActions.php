<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

use Carbon\Carbon;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\UserRepository;

trait WithDiscountActions
{
    /**
     * Determine if the discount information are empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
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

    /**
     * Return Apply offer product.
     *
     * @return string
     */
    public function getProductSize(): string
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
     * @return string|null
     */
    public function getCustomSize(): ?string
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
    public function getDateWord(): ?string
    {
        $today = Carbon::today();
        $startDate = new Carbon($this->dateStart);
        $endDate = new Carbon($this->dateEnd);

        if ($today->equalTo($startDate) && $today->equalTo($endDate) && $this->dateEnd !== null) {
            return __('Active today');
        }

        if ($today->equalTo($startDate) && $this->dateEnd === null) {
            return __('Active from today');
        }

        if ($today->notEqualTo($startDate) && $this->dateEnd === null) {
            return __('Active from :date', ['date' => $startDate->format('d M')]);
        }

        if ($today->notEqualTo($startDate) && $startDate->equalTo($endDate)) {
            return __('Active :date', ['date' => $startDate->format('d M')]);
        }

        if ($startDate->notEqualTo($endDate) && $startDate->lessThan($endDate) && $this->dateEnd !== null) {
            return __('Active from :startDate to :endDate', [
                'startDate' => $startDate->format('d M'),
                'endDate'   => $endDate->format('d M'),
            ]);
        }

        if ($startDate->greaterThan($endDate) && $this->dateEnd !== null) {
            $this->dateEnd = Carbon::createFromFormat('Y-m-d H:i', $this->dateStart)->toDateString();

            return __('Active :date', ['date' => $startDate->format('d M')]);
        }

        return null;
    }

    /**
     * Return Usage limit message.
     *
     * @return string|null
     */
    public function getUsageLimitMessage(): ?string
    {
        if ($this->usage_number && $this->usage_limit !== null && (int) $this->usage_limit > 0) {
            $message = trans_choice('shopper::messages.discount_use', $this->usage_limit, ['count' => $this->usage_limit]);
            $message .= $this->usage_limit_per_user ? ', '. __('one per customer') : '';

            return $message;
        }

        if ($this->usage_limit_per_user && ! $this->usage_number) {
            return __('One per customer');
        }

        return null;
    }

    /**
     * Remove product to the products list and the productIds restriction.
     *
     * @param  int  $key
     * @param  int  $id
     */
    public function removeProduct(int $key, int $id): void
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
    public function removeCustomer(int $key, int $id): void
    {
        unset($this->customersDetails[$key]);

        foreach (array_keys($this->customersIds, $id) as $key) {
            unset($this->customersIds[$key]);
        }
    }

    /**
     * Add product to list of products that can be apply on the discount.
     *
     * @return void
     */
    public function addProducts(): void
    {
        if (count($this->selectedProducts) > 0) {
            foreach ($this->selectedProducts as $selectedProduct) {
                $product = (new ProductRepository())->getById($selectedProduct);
                $productArray['id'] = $product->id;
                $productArray['name'] = $product->name;
                $productArray['image'] = $product->files->first()->file_path ?? null;

                array_push($this->productsDetails, $productArray);
                array_push($this->productsIds, $product->id);
            }

            $this->selectedProducts = [];
            $this->dispatchBrowserEvent('products-added');
        }
    }

    /**
     * Add customer to the list of customers that already can use this discount.
     *
     * @return void
     */
    public function addCustomers(): void
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
}
