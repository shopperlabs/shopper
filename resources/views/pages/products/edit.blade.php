@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Update product'). ' '. $product->name)

@section('content')

    <x:breadcrumb back="shopper.products.index">
        <a href="{{ route('shopper.products.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Products') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-gray-500">{{ __('Edit product') }}</span>
    </x:breadcrumb>

    <div class="mt-6 flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-500 sm:text-3xl sm:leading-9 sm:truncate">{{ $product->name }}</h2>
            <div class="md:my-4 mt-2">
                <a href="#" class="text-gray-600 text-base inline-flex items-center hover:text-gray-500 focus:text-gray-700 leading-5 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('Duplicate') }}</span>
                </a>
            </div>
        </div>
        <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
            <span>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-brand focus:border-brand-400 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
                    {{ __("Add Variant") }}
                </button>
            </span>
        </div>
    </div>

    <div x-data="{ tab: 'detail' }">
        <div class="sm:hidden mt-4">
            <select aria-label="Selected tab" class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                <option selected>{{ __("Detail") }}</option>
                <option>{{ __("Variants") }}</option>
                <option>{{ __("Attributes") }}</option>
                <option>{{ __("Inventory") }}</option>
                <option>{{ __("Files") }}</option>
                <option>{{ __("Related Products") }}</option>
            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button type="button" @click="tab = 'detail' " class="whitespace-no-wrap py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'detail' }">
                        {{ __("Detail") }}
                    </button>
                    <button type="button" @click="tab = 'variants' " class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'variants' }">
                        {{ __("Variants") }}
                    </button>
                    <button type="button" @click="tab = 'attributes' " class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'attributes' }">
                        {{ __("Attributes") }}
                    </button>
                    <button type="button" @click="tab = 'inventory' " class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'inventory' }">
                        {{ __("Inventory") }}
                    </button>
                    <button type="button" @click="tab = 'files' " class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'files' }">
                        {{ __("Files") }}
                    </button>
                    <button type="button" @click="tab = 'related' " class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'related' }">
                        {{ __("Related Products") }}
                    </button>
                </nav>
            </div>
        </div>

        @include('shopper::pages.products.partials.form-edit')
        @include('shopper::pages.products.partials.inventory')
        @include('shopper::pages.products.partials.files')

    </div>

@endsection
