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
                    <div class="p-4 bg-gray-50" x-data="{ show: false }">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">{{ __('Publication date') }}</span>
                            <button type="button" @click="show = true">
                                <svg class="h-5 w-5 text-gray-700 hover:text-gray-500" fill="currentColor" viewBox="0 0 32 32">
                                    <path d="M28.25 2.5A3.754 3.754 0 0 1 32 6.25v22A3.754 3.754 0 0 1 28.25 32H3.75A3.754 3.754 0 0 1 0 28.25v-22A3.754 3.754 0 0 1 3.75 2.5h1.5V0h2.5v2.5h16.5V0h2.5v2.5h1.5zm1.25 25.75v-16.5h-27v16.5c0 .689.561 1.25 1.25 1.25h24.5c.689 0 1.25-.561 1.25-1.25zm0-19v-3c0-.689-.561-1.25-1.25-1.25h-1.5v2.5h-2.5V5H7.75v2.5h-2.5V5h-1.5c-.689 0-1.25.561-1.25 1.25v3h27zM4.75 16.875v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm-20 5v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm-15 5v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5 0v-2.5h2.5v2.5h-2.5zm5-5v-2.5h2.5v2.5h-2.5z" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex space-x-2 items-center mt-3" x-show="show">
                            <span id="date-picker" data-label="{{ __('Date') }}" data-value="{{ $collection->published_at !== null ? $collection->published_at->format('Y-M-D') : '' }}">&nbsp;</span>
                            <span id="time-picker" data-label="{{ __('Hour') }}" data-value="{{ $collection->published_at !== null ? $collection->published_at->format('H:i') : '' }}">&nbsp;</span>
                            <button type="button" @click="show = false;">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
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
