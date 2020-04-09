@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Create collection'))

@section('content')

    <x:breadcrumb>
        <a href="{{ route('shopper.collections.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('collections') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-primary-text">{{ __('Edit collection') }}</span>
    </x:breadcrumb>

    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate pb-4">{{ $collection->name }}</h2>
        </div>
    </div>

    {!! Form::model($collection, ['route' => ['shopper.collections.update', $collection], 'method' => 'put', 'files' => true]) !!}
        <div class="flex flex-col lg:flex-row mt-4">
            <div class="w-full lg:w-2/3 space-y-4">
                <div class="bg-white p-4 shadow rounded-md">
                    <div class="w-full mb-2">
                        <label for="name" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Name') }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            {!! Form::text('name', null, [
                                    'class' => 'form-input form-input-shopper block w-full sm:text-sm sm:leading-5',
                                    'placeholder' => 'e.g.: Adidas, Apple',
                                    'id' => 'name'
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
                            <div id="editor" data-content="{{ $collection->description }}"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 shadow rounded-md">
                    <h4 class="text-gray-500 font-medium pb-8 text-base">{{ __('Type of collection') }}</h4>
                    <div class="relative flex items-start">
                        <div class="absolute flex items-center h-5">
                            <input id="manuel" type="radio" name="type" value="manual" class="form-radio h-4 w-4 text-brand-400 transition duration-150 ease-in-out" {{ $collection->type === 'manual' ? 'checked="checked' : '' }} />
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <label for="manuel" class="font-medium text-gray-700 cursor-pointer">{{ __('Manual') }}</label>
                            <p class="text-gray-500 mt-1">{{ __('Add the products to this collection one by one.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="relative flex items-start">
                            <div class="absolute flex items-center h-5">
                                <input id="auto" type="radio" name="type" value="auto" class="form-radio h-4 w-4 text-brand-400 transition duration-150 ease-in-out" {{ $collection->type === 'auto' ? 'checked="checked' : '' }} />
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
                    <x:datetime-picker :published-at="$collection->published_at" :show="$collection->published_at !== null" />
                </div>
                <div class="bg-white p-4 shadow rounded-md">
                    <h4 class="text-gray-500 font-medium pb-8 text-base">{{ __('Collection image') }}</h4>
                    <div id="dropzone-simple" data-preview="{{ $collection->preview_image_link }}" data-id="{{ $collection->preview_image_id }}"></div>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t pt-5 border-gray-200">
            <div class="flex justify-between">
                <x:delete-action
                    :title="$collection->name"
                    action="collection"
                    message="Are you sure you want to delete this collection? All this data will be removed. This action cannot be undone."
                    :url="route('shopper.collections.destroy', $collection)"
                />
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
            </div>
        </div>
    {!! Form::close() !!}

@endsection
