@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Customer').' - '. $customer->full_name)

@section('content')

	<x:breadcrumb back="shopper.customers.index">
		<svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>	
		<a href="{{ route('shopper.customers.index') }}" class="text-gray-600 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Customers') }}</a>
	</x:breadcrumb>

	<div class="lg:flex lg:items-center lg:justify-between mt-4">
		<div class="flex-1 min-w-0 flex items-center">
			<div class="flex-shrink-0 mr-4 hidden sm:block">
				<img class="h-14 w-14 rounded-md object-cover" src="{{ $customer->picture }}" alt="{{ $customer->full_name }}">
			</div>
			<div class="sm:truncate">
				<h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
					{{  $customer->full_name }}
				</h2>
				<div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap">
					<div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mr-6">
					  	<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
							<path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
					  	</svg>
					  	{{ $customer->phone_number ?? __("N/A") }}
					</div>
					<div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mr-6">
					  	<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z" clip-rule="evenodd"/>
					  	</svg>
					  	{{ $customer->email }}
					</div>
					<div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
					  	<svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
					  	</svg>
					  	{{  __("Joined on") }} {{ $customer->created_at->format('F j, Y') }}
					</div>
				</div>
			</div>
		</div>
		<div class="mt-5 flex items-center lg:mt-0 lg:ml-4 space-x-4">
		  	<span class="hidden sm:block shadow-sm rounded-md">
				<button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-brand focus:border-brand-400 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
			  		{{  __("Edit") }}
				</button>
			</span>
	  
		  	<x:delete-action
				:title="$customer->full_name"
				action="customer"
				message="Are you sure you want to delete this customer? All this data will be removed. This action cannot be undone."
				:url="route('shopper.customers.destroy', $customer)"
			/>
	  
		</div>
	</div>

	<div x-data="{ tab: 'orders' }" class="bg-white rounded-md overflow-hidden shadow-md px-4 py-4 sm:px-6 mt-8">
		<div>
			<div class="sm:hidden">
			  	<select aria-label="Selected tab" class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
					<option selected>{{ __("Orders") }}</option>
					<option>{{ __("Address") }}</option>
					<option>{{ __("Activities") }}</option>
			  	</select>
			</div>
			<div class="hidden sm:block">
			  	<div class="border-b border-gray-200">
					<nav class="-mb-px flex space-x-8">
				  		<button type="button" @click="tab = 'orders' " class="whitespace-no-wrap py-4 px-2 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'orders' }">
							{{ __("Orders") }}
				  		</button>
				  		<button type="button" @click="tab = 'addresses' " class="whitespace-no-wrap py-4 px-2 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'addresses' }">
							{{ __("Address") }}
				  		</button>
				  		<button type="button" @click="tab = 'activities' " class="whitespace-no-wrap py-4 px-2 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : tab === 'activities' }">
							{{ __("Activities") }}
				  		</button>
					</nav>
			  	</div>
			</div>
		</div>

		@include('shopper::pages.customers.partials.orders')
		@include('shopper::pages.customers.partials.addresses', ['addresses' => $customer->addresses])
		@include('shopper::pages.customers.partials.activities')

	</div>

@endsection
