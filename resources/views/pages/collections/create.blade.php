@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Create collection'))

@section('content')

    <x:breadcrumb back="shopper.collections.index">
        <a href="{{ route('shopper.collections.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Collections') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-primary-text">{{ __('Create collection') }}</span>
    </x:breadcrumb>

    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate pb-4">{{ __('Create collection') }}</h2>
        </div>
    </div>

    {!! Form::open(['route' => 'shopper.collections.store', 'files' => true]) !!}
        <div class="flex flex-col lg:flex-row mt-4">
            <div class="w-full lg:w-2/3 space-y-4">
                <div class="bg-white p-4 shadow rounded-md">
                    <div class="w-full mb-2">
                        <label for="name" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Name') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            {!! Form::text('name', null, [
                                    'class' => 'form-input form-input-shopper block w-full sm:text-sm sm:leading-5',
                                    'placeholder' => 'e.g.: Summer collection, Winter collection',
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
                        <label for="description" class="block text-sm font-medium leading-5 text-gray-700">Description</label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <div id="editor"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 shadow rounded-md">
                    <h4 class="text-gray-500 font-medium pb-8 text-base">{{ __('Type of collection') }}</h4>
                    <div class="relative flex items-start">
                        <div class="absolute flex items-center h-5">
                            <input id="manuel" type="radio" name="type" value="manual" class="form-radio h-4 w-4 text-brand-400 transition duration-150 ease-in-out" checked />
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <label for="manuel" class="font-medium text-gray-700 cursor-pointer">{{ __('Manual') }}</label>
                            <p class="text-gray-500 mt-1">{{ __('Add the products to this collection one by one.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="relative flex items-start">
                            <div class="absolute flex items-center h-5">
                                <input id="auto" type="radio" name="type" value="auto" class="form-radio h-4 w-4 text-brand-400 transition duration-150 ease-in-out" />
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <label for="auto" class="font-medium text-gray-700 cursor-pointer">{{ __('Automatic') }}</label>
                                <p class="text-gray-500 mt-1">{{ __('The products will be added automatically to this collection according to the conditions that you will have defined.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/3 space-y-4 mt-4 lg:mt-0 lg:ml-4">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="bg-white p-4">
                        <h4 class="text-gray-500 font-medium text-base">{{ __('Collection availability') }}</h4>
                        <p class="text-gray-400 text-sm mt-2">{{ __('When you create a new collection it will be directly available for your online store if you don\'t specify a date of publication.') }}</p>
                    </div>
                    <x:datetime-picker />
                </div>
                <div class="bg-white p-4 shadow rounded-md">
                    <h4 class="text-gray-500 font-medium pb-8 text-base">{{ __('Collection image') }}</h4>
                    <div id="dropzone-simple"></div>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t pt-5 border-gray-200">
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    {!! Form::close() !!}

@endsection
