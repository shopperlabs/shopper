@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Create category'))

@section('content')

    <x:breadcrumb back="shopper.categories.index">
        <a href="{{ route('shopper.categories.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Categories') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-primary-text">{{ __('Create category') }}</span>
    </x:breadcrumb>

    <div class="mt-4 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate pb-4">{{ __('Create Category') }}</h2>
        </div>
    </div>

    {!! Form::open(['route' => 'shopper.categories.store', 'files' => true]) !!}
        <div class="flex flex-col lg:flex-row mt-4">
            <div class="w-full lg:w-2/3 space-y-4">
                <div class="bg-white p-4 shadow rounded-md">
                    <div class="w-full mb-2">
                        <label for="name" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Name') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            {!! Form::text('name', null, [
                                    'class' => 'form-input form-input-shopper block w-full sm:text-sm sm:leading-5',
                                    'placeholder' => 'e.g.: Womens Shoes, Baby clothes',
                                    'id' => 'name',
                                    'autocomplete' => 'off'
                                ])
                            !!}
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 shadow rounded-md">
                    <h4 class="text-gray-500 font-medium pb-8 text-base">{{ __('Optional informations') }}</h4>
                    <div class="w-full mb-3">
                        <label for="paren_id" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Parent') }}</label>
                        <div class="mt-1 rounded-md shadow-sm">
                            {!! Form::select('parent_id', $categories, null, [
                                    'placeholder' => __('Choose a parent'),
                                    'id' => 'parent_id',
                                    'class' => 'block form-select form-select-shopper w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5'
                                ])
                            !!}
                        </div>
                    </div>
                    <div class="w-full mb-3">
                        <label for="description" class="block text-sm font-medium leading-5 text-gray-700">Description</label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <div id="editor"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/3">
                <div class="bg-white p-4 shadow rounded-md mt-4 lg:mt-0 lg:ml-4">
                    <h4 class="text-gray-500 font-medium pb-8 text-base">{{ __('Category image') }}</h4>
                    <div id="dropzone-simple"></div>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-200 pt-5">
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    {!! Form::close() !!}

@endsection
