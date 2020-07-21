@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Settings'))

@section('content')

    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-600 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Settings') }}</h2>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-md shadow-md px-5 py-6 sm:p-8">
      <div class="z-20 relative grid gap-6 sm:gap-8 lg:grid-cols-2 xl:grid-cols-3">
        <a href="{{ route('shopper.settings.shop') }}" class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
          <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-brand-500 text-white sm:h-12 sm:w-12 ">
            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
              <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div class="space-y-1">
            <p class="text-base leading-6 font-medium text-gray-900">
              {{ __("General") }}
            </p>
            <p class="text-sm leading-5 text-gray-500">
              {{ __("View and update your store informations.") }}
            </p>
          </div>
        </a>
        <a href="{{ route('shopper.users.access') }}" class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
          <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-brand-500 text-white sm:h-12 sm:w-12 ">
            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <div class="space-y-1">
            <p class="text-base leading-6 font-medium text-gray-900">
              {{ __("Staff & permissions") }}
            </p>
            <p class="text-sm leading-5 text-gray-500">
              {{ __("View and manage what staff can see or do in your store.") }}
            </p>
          </div>
        </a>
        <a href="{{ route('shopper.settings.inventories.index') }}" class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
          <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-brand-500 text-white sm:h-12 sm:w-12 ">
            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div class="space-y-1">
            <p class="text-base leading-6 font-medium text-gray-900">
              {{ __("Locations") }}
            </p>
            <p class="text-sm leading-5 text-gray-500">
              {{ __("Manage the places you stock inventory and sell products.") }}
            </p>
          </div>
        </a>
        <a href="#" class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
          <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-brand-500 text-white sm:h-12 sm:w-12 ">
            <svg class="h-6 w-6" stroke="none" fill="currentColor" viewBox="0 0 24 24">
              <path clip-rule="evenodd" fill-rule="evenodd" d="M17.765 5.453c2.905 0 5.226 2.405 5.235 5.36v6.042c-.06 1.055-1.028.955-1.028.955v.008c0 1.726-1.431 3.182-3.128 3.182-1.743 0-3.127-1.408-3.127-3.182v-.008h-5.54v.008c0 1.726-1.43 3.182-3.126 3.182-1.73 0-3.106-1.39-3.127-3.143H2.895a.905.905 0 01-.895-.91V2.906A.9.9 0 012.895 2h10.672c2.784.122 2.48 2.881 2.454 3.452h1.744zm0 1.818H16.02v3.726h5.227v-.135c0-2-1.564-3.591-3.483-3.591zM7.046 19.227c.758 0 1.384-.637 1.384-1.409 0-.771-.625-1.408-1.384-1.408-.759 0-1.384.637-1.384 1.408 0 .772.625 1.409 1.384 1.409zm7.236-3.235V4.542a.712.712 0 00-.715-.728H3.743v12.222h.703a3.099 3.099 0 012.6-1.4c1.071 0 2.01.532 2.57 1.356h4.666zm4.554 3.235c.763 0 1.388-.637 1.384-1.409-.043-.77-.625-1.408-1.384-1.408-.759 0-1.384.637-1.384 1.408 0 .772.625 1.409 1.384 1.409zm0-4.59c.977 0 1.842.444 2.412 1.142V12.81H16.02v3.225h.214a3.099 3.099 0 012.6-1.4h.001zm-7.634-7.641c.488 0 .895.41.895.91 0 .501-.403.911-.895.911H6.51a.905.905 0 01-.896-.91c0-.501.403-.911.896-.911h4.69zm0 3.727c.488 0 .89.41.895.91-.047.502-.403.911-.895.911H6.51a.905.905 0 01-.896-.91c0-.501.403-.911.896-.911h4.69z"/>
            </svg>
          </div>
          <div class="space-y-1">
            <p class="text-base leading-6 font-medium text-gray-900">
              {{ __("Shipping and delivery") }}
            </p>
            <p class="text-sm leading-5 text-gray-500">
              {{ __("Manage how you ship orders to customers.") }}
            </p>
          </div>
        </a>
        <a href="#" class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
          <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-brand-500 text-white sm:h-12 sm:w-12 ">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
          </div>
          <div class="space-y-1">
            <p class="inline-flex items-center text-base leading-6 font-medium text-gray-900">
              <span>{{ __("Integrations") }}</span>
              <span class="ml-1 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-brand-100 text-brand-800">
                {{ __("Soon") }}
              </span>
            </p>
            <p class="text-sm leading-5 text-gray-500">
              {{ __("Connect with third-party tools that you’re already using.") }}
            </p>
          </div>
        </a>
        <a href="#" class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
          <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-brand-500 text-white sm:h-12 sm:w-12 ">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
          <div class="space-y-1">
            <p class="inlinex-flex items-center text-base leading-6 font-medium text-gray-900">
              <span>{{ __('Analytics') }}</span>
              <span class="ml-1 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-brand-100 text-brand-800">
                {{ __("Soon") }}
              </span>
            </p>
            <p class="text-sm leading-5 text-gray-500">
              {{ __("Get a better understanding of where your traffic is coming from.") }}
            </p>
          </div>
        </a>
        <a href="#" class="-m-3 p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
          <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-brand-500 text-white sm:h-12 sm:w-12 ">
            <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
          <div class="space-y-1">
            <p class="text-base leading-6 font-medium text-gray-900">
              {{ __("Legal") }}
            </p>
            <p class="text-sm leading-5 text-gray-500">
              {{ __("Manage your store's legal pages such as privacy, terms.") }}
            </p>
          </div>
        </a>
      </div>
    </div>

@endsection
