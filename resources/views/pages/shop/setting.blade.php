@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Store Setting'))

@section('content')

    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate pb-4 border-b border-gray-200">{{ __('Store Setting') }}</h2>
        </div>
    </div>

    <div class="py-8">
        {!! Form::model($store, ['route' => ['shopper.shop.update', $store], 'files' => true, 'method' => 'PUT']) !!}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Store details") }}</h3>
                        <p class="mt-4 text-sm leading-5 text-gray-600">
                            {{ __("Your customers will use this information to contact you.") }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="name" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Store name") }}</label>
                                    <div class="mt-1 relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1zm-7 2h2v3a1 1 0 1 1-2 0zm-4 0h2v3a1 1 0 1 1-2 0zM7 7h2v3a1 1 0 1 1-2 0zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1zm10 10h-4v-2a2 2 0 1 1 4 0zm5 0h-3v-2a4 4 0 1 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 4 0c.293.26.632.464 1 .6zm2-11a1 1 0 1 1-2 0V7h2zM4.3 3H20a1 1 0 1 0 0-2H4.3a1 1 0 1 0 0 2z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        {!! Form::text('name', null, [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 pl-10 transition ease-in-out duration-150',
                                                'id' => 'name',
                                                'autocomplete' => 'off'
                                            ])
                                        !!}
                                    </div>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="phone_number" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Store phone number") }}</label>
                                    <div class="mt-1">
                                        {!! Form::text('phone_number', null, [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150',
                                                'id' => 'last_name',
                                                'autocomplete' => 'off'
                                            ])
                                        !!}
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __("Your customers will use this phone number if they need to call you directly.") }}
                                    </p>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="email" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Email address") }}</label>
                                    {!! Form::email('email', null, [
                                            'class' => 'mt-1 form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150',
                                            'id' => 'email',
                                            'autocomplete' => 'off'
                                        ])
                                    !!}
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __("Your customers will use this address if they need to contact you.") }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Store assets") }}</h3>
                            <p class="mt-4 text-sm leading-5 text-gray-600">
                                {{ __("The logo and cover image of your store that will be visible on your site. This assets will appear on your invoices.") }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div>
                                    <label class="block text-sm leading-5 font-medium text-gray-700">{{ __("Logo") }}</label>
                                    <div class="mt-2 flex items-center">
                                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                            @if($store->logo_url)
                                                <img class="h-full w-full" src="{{ $store->logo_url }}" alt="{{ $store->name }}">
                                            @else
                                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            @endif
                                        </span>
                                        <span class="ml-5 rounded-md shadow-sm">
                                            <button type="button" class="py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-brand-400 focus:shadow-outline-brand active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                                {{ __("Change") }}
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <label class="block text-sm leading-5 font-medium text-gray-700">{{ __("Cover photo") }}</label>
                                    <div id="dropzone-simple" class="mt-1" data-preview="{{ $store->preview_image_link }}" data-id="{{ $store->preview_image_id }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Store address") }}</h3>
                            <p class="mt-4 text-sm leading-5 text-gray-600">
                                {{ __("This address will appear on your invoices. You can edit the address used.") }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 sm:gap-4">
                                    <div class="col-span-6">
                                        <label for="description" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Description") }}</label>
                                        <div class="mt-1 relative">
                                            {!! Form::textarea('description', null, [
                                                    'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150',
                                                    'id' => 'description',
                                                    'autocomplete' => 'off',
                                                    'rows' => 4,
                                                    'placeholder' => __("Your shop in some words, define your business...")
                                                ])
                                            !!}
                                        </div>
                                    </div>

                                    <div class="col-span-6">
                                        <label for="address" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Address") }}</label>
                                        <div class="mt-1 relative">
                                            {!! Form::text('address', null, [
                                                    'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150',
                                                    'id' => 'address',
                                                    'autocomplete' => 'off',
                                                    'placeholder' => __("Your shop full address...")
                                                ])
                                            !!}
                                        </div>
                                    </div>

                                    <div class="col-span-6">
                                        <label for="country" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Country / Region") }}</label>
                                        <div id="country-selector" class="mt-1" data-value="{{ $store->country }}"></div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="city" class="block text-sm font-medium leading-5 text-gray-700">{{ __("City") }}</label>
                                        <div class="mt-1">
                                            {!! Form::text('city', null, [
                                                    'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150',
                                                    'id' => 'city',
                                                    'autocomplete' => 'off'
                                                ])
                                            !!}
                                        </div>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="post_code" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Postal / ZIP code") }}</label>
                                        <div class="mt-1">
                                            {!! Form::text('post_code', null, [
                                                    'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition ease-in-out duration-150',
                                                    'id' => 'post_code',
                                                    'autocomplete' => 'off'
                                                ])
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Store currency") }}</h3>
                            <p class="mt-4 text-sm leading-5 text-gray-600">
                                {{ __("This is the currency your products are sold in. After your first sale, currency is locked in and can’t be changed.") }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 sm:gap-4">
                                    <div class="col-span-6">
                                        <label for="currency" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Store currency") }}</label>
                                        <select name="currency" id="currency" class="mt-1 block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency }}" @if($currency == shopper_currency()) selected @endif>
                                                    {{ $currency }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-sm mt-1 text-gray-400 font-italic">{{ __('Currency symbol for price.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="btn btn-primary">
                          {{ __("Update Information") }}
                        </button>
                    </span>
                </div>
            </div>
        {!! Form::close() !!}
    </div>

@endsection
