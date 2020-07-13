@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Locations'))

@section('content')

	<x:breadcrumb back="shopper.settings.shop">
		<svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
			<path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
		</svg>
		<a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
	</x:breadcrumb>

	<div class="mt-6 md:flex md:items-center md:justify-between">
		<div class="flex-1 min-w-0">
			<h2 class="text-2xl font-bold leading-7 text-gray-500 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Inventories') }}</h2>
		</div>
		<div class="mt-4 flex md:mt-0 md:ml-4">
			<a href="{{ route('shopper.settings.inventories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150">{{ __('Add inventory') }}</a>
		</div>
	</div>

	<div class="bg-white shadow overflow-hidden sm:rounded-md my-6">
		<ul class="divide-y divide-gray-200">
			@foreach($inventories as $inventory)
			<li>
				<a href="{{ route('shopper.settings.inventories.edit', $inventory) }}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
				  	<div class="px-4 py-4 sm:px-6">
						<div class="flex items-center">
							<div class="flex-shrink-0 hidden lg:block">
								<span class="flex items-center justify-center h-12 w-12 bg-gray-100 text-gray-400 rounded-md">
									<svg class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
										<path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
										<path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
									</svg>
								</span>
							</div>
							<div class="flex-1 lg:ml-4">
								<div class="flex items-center justify-between">
									<div class="text-sm leading-5 font-medium text-brand-600 truncate">
									  	{{ $inventory->name }}
									</div>
									@if($inventory->is_default)
										<div class="ml-2 flex-shrink-0 flex">
											<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
											  {{ __("Default") }}
											  </span>
										  </div>
									@endif
								</div>
								<div class="mt-2 sm:flex sm:justify-between">
									<div class="sm:flex sm:space-x-4">
										  <div class="flex items-center text-sm leading-5 text-gray-500">
											  <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
												  <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd" />
											  </svg>
											  {{ $inventory->country ?? __("Country not set") }}
										  </div>
										  <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
											  <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
											  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
											  </svg>
											  {{ $inventory->city ?? __("City not set") }}
										  </div>
										  <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
											  <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
											  <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
											  </svg>
											  {{ $inventory->phone_number ?? __("Number not set") }}
										  </div>
									</div>
									<div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
										<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
											<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
										</svg>
										<span>
											{{ __('Added on') }}
											<time datetime="{{ $inventory->created_at->format('d-m-Y') }}">{{ $inventory->created_at->formatLocalized('%d %B %G') }}</time>
										</span>
									</div>
								</div>
							</div>
						</div>
				  	</div>
				</a>
			</li>
			@endforeach
		</ul>
	</div>

@endsection
