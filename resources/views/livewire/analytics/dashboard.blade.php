<div>

    <div class="mt-4 pb-5 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <div>
            <h3 class="text-xl leading-6 sm:text-2xl sm:leading-7 sm:truncate font-bold text-gray-900">
                {{ __('Analytics') }}
            </h3>
            <p class="max-w-4xl text-sm text-gray-500">
                {{ __('Here are your stats for the period') }}
                <span class="font-medium text-gray-600">{{ $fromDate->format('Y-m-d') . ' - '. $toDate->format('Y-m-d') }}</span>
            </p>
        </div>
    </div>


@if($can_display_analytics)
        <h4 class="text-md leading-6 font-medium text-gray-900">
            {{ __('Display analytics from ') }}
        </h4>
        <select id="country" name="country" autocomplete="country" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option>{{ __('Last 7 days') }}</option>
            <option>{{ __('Last Month') }}</option>
            <option>{{ __('Last 3 Months') }}</option>
            <option>{{ __('Last Year') }}</option>
            <option>{{ __('All time') }}</option>
            <option>{{ __('Custom date') }}</option>
        </select>
        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">
            {{ __('From') }} {{ $period->startDate->format('d/m/Y') }} {{ __('to') }} {{ $period->endDate->format('d/m/Y') }}
        </h3>
        <dl class="mt-5 grid grid-cols-1 rounded-lg bg-white overflow-hidden shadow divide-y divide-gray-200 md:grid-cols-3 md:divide-y-0 md:divide-x">
            <div>
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-base font-normal text-gray-900">
                        {{ __('Total Visitors') }}
                    </dt>
                    <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                            {{ $analytics['total_visitors'] }}
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ __('from XX XXX') }}
                            </span>
                        </div>

                        <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">
                                {{ __('Increased by') }}
                            </span>
                            12%
                        </div>
                    </dd>
                </div>
            </div>

            <div>
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-base font-normal text-gray-900">
                        {{ __('Total PageViews') }}
                    </dt>
                    <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                            {{ $analytics['total_pageviews'] }}
                            <span class="ml-2 text-sm font-medium text-gray-500">
                                {{ __('from XX XXX') }}
                            </span>
                        </div>

                        <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                            <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">
                                {{ __('Increased by') }}
                            </span>
                            2.02%
                        </div>
                    </dd>
                </div>
            </div>
        </dl>
        <div class="mt-5">
            <dt class="text-base font-normal text-gray-900">
                {{ __('Top referrers') }}
            </dt>
        </div>
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-5">
            <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200">
                <div class="w-full flex items-center justify-between p-6 space-x-6">
                    <div class="flex-1 truncate">
                        @foreach($analytics['top_referrers'] as $referrer)
                            <div class="flex items-center space-x-3">
                                <h3 class="text-gray-900 text-sm font-medium truncate">{{ $referrer['url'] }}</h3>
                                <span class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">{{ $referrer['pageViews'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </li>
        </ul>
    @else
        <div class="relative w-full flex flex-col items-center justify-center py-12 lg:py-16">
            <div class="flex-shrink-0 w-1/2">
                <img class="w-auto h-64 lg:h-auto" src="{{ asset('shopper/images/analytics.svg') }}" alt="Analytics Placeholder">
            </div>
            <div class="mt-5 w-full sm:max-w-3xl text-center">
                <div class="rounded-md bg-primary-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-primary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p class="text-sm text-primary-700 text-left">
                                {{ __('To see Google Analytics Data, you’ll need to get some credentials to use') }} <a href="https://console.developers.google.com/apis" target="_blank" class="underline font-medium text-primary-800">Google API’s</a>. {{ __('This section use Spatie/analytics package. To finally display analytics chart you have to update your analytics setting') }}
                                <a href="{{ route('shopper.settings.analytics') }}" class="underline text-primary-800 font-medium">{{ __('here') }}</a>.
                            </p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-500">

                </p>
            </div>
        </div>
    @endif
</div>
