@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Update product'). ' '. $product->name)

@section('content')

    <x:breadcrumb back="shopper.products.index">
        <a href="{{ route('shopper.products.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Products') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-primary-text">{{ __('Edit product') }}</span>
    </x:breadcrumb>

    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate pb-4">{{ $product->name }}</h2>
        </div>
    </div>

    {!! Form::model($product, ['route' => ['shopper.products.update', $product], 'method' => 'put', 'files' => true]) !!}

        <div class="mt-8 border-t pt-5 border-gray-200">
            <div class="flex justify-between">
                <x:delete-action
                    :title="$product->name"
                    action="product"
                    message="Are you sure you want to delete this product? All this data will be removed. This action cannot be undone."
                    :url="route('shopper.products.destroy', $product)"
                />
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </div>
    {!! Form::close() !!}

@endsection
