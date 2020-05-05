@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Customer').' - '. $customer->full_name)

@section('content')

	<x:breadcrumb back="shopper.customers.index">
		<svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>	
		<a href="{{ route('shopper.customers.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Customers') }}</a>
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
					<svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor" viewBox="0 0 32 32">
						<path d="M13.063 4c1.173 0 1.979.5 2.938.5.48 0 .962-.099 1.438-.219S18.355 4 18.939 4c.861 0 1.621.429 2.156 1s.912 1.287 1.25 2.063c.525 1.203.939 2.572 1.313 3.875 1.086.316 2.007.71 2.75 1.188.868.558 1.594 1.33 1.594 2.375 0 .908-.554 1.632-1.25 2.156-.593.446-1.324.816-2.188 1.125a3.873 3.873 0 0 1-.219.688c.844.477 2.178 1.397 3.469 3.156l.594.844-.844.594-3.281 2.25 1.344 2.688H6.377l1.344-2.688-3.281-2.25-.844-.594.594-.844c1.291-1.759 2.625-2.679 3.469-3.156a3.847 3.847 0 0 1-.219-.688c-.863-.309-1.595-.679-2.188-1.125-.696-.524-1.25-1.248-1.25-2.156 0-1.043.728-1.817 1.594-2.375.741-.477 1.665-.871 2.75-1.188.34-1.259.717-2.602 1.25-3.813.346-.786.74-1.513 1.281-2.094S12.191 4 13.065 4zm0 2c-.218 0-.421.087-.719.406s-.612.864-.906 1.531c-.488 1.109-.89 2.517-1.25 3.844v.032a1.18 1.18 0 0 1-.125.438.453.453 0 0 1-.031.031c-.002.009.002.022 0 .031v.032c.018.005.047-.011.125.031.256.137.77.308 1.406.406 1.272.197 3.007.219 4.438.219 1.438 0 3.168.002 4.438-.188.635-.095 1.155-.27 1.406-.406.147-.08.093-.033.094-.031-.45-1.561-.864-3.257-1.406-4.5-.287-.657-.622-1.197-.906-1.5s-.456-.375-.688-.375c-.086 0-.481.088-1 .219s-1.187.281-1.938.281c-1.501 0-2.732-.5-2.938-.5zm-4.875 7.094c-.631.23-1.169.485-1.531.719-.579.373-.656.621-.656.688 0 .059.052.249.469.563s1.137.674 2.063.969c1.851.589 4.517.969 7.469.969s5.618-.38 7.469-.969c.925-.294 1.646-.655 2.063-.969s.469-.504.469-.563c0-.066-.107-.345-.688-.719-.363-.233-.881-.468-1.5-.688a2.49 2.49 0 0 1-1.031 1.094c-.612.332-1.307.481-2.063.594-1.512.226-3.281.219-4.719.219-1.445 0-3.21-.016-4.719-.25-.755-.117-1.455-.269-2.063-.594a2.45 2.45 0 0 1-1.031-1.063zm2.593 5.469l.094.625c.328 1.651 1.051 3.146 1.969 4.188s1.98 1.608 3.156 1.625c1.145.016 2.232-.543 3.156-1.594s1.661-2.576 1.969-4.219l.094-.625c-.33.054-.654.144-1 .188-.13.877-.813 1.652-1.906 1.719-.842.052-1.791-.346-1.875-1.469-.148.002-.288 0-.438 0s-.289.002-.438 0c-.084 1.122-1.033 1.52-1.875 1.469-1.093-.067-1.777-.842-1.906-1.719-.346-.044-.67-.133-1-.188zM23 20c-.033.019-.06.044-.094.063-.412 1.768-1.15 3.407-2.25 4.656A7.603 7.603 0 0 1 19.218 26h3.156l-.281-.563-.375-.781.719-.469 3.031-2.094c-1.12-1.234-2.096-1.876-2.469-2.094zm-14.031.031c-.416.25-1.377.895-2.438 2.063l3.031 2.094.719.469-.375.781-.281.563h3.25a7.462 7.462 0 0 1-1.531-1.313c-1.085-1.231-1.822-2.849-2.25-4.594-.041-.022-.085-.039-.125-.063z" />
					</svg>
			  		{{  __("Impersonate") }}
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
		@include('shopper::pages.customers.partials.addresses')
		@include('shopper::pages.customers.partials.activities')

	</div>

@endsection
