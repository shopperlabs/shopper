<div>
    <h4 class="text-md leading-6 font-medium text-gray-900">
        Display analytics from 
    </h4>
    <select id="country" name="country" autocomplete="country" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <option>Last 7 days</option>
        <option>Last Month</option>
        <option>Last 3 Months </option>
        <option>Last Year </option>
        <option>All time </option>
        <option>Custom date</option>
      </select>

    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">
        Last 30 days
      </h3>
      <dl class="mt-5 grid grid-cols-1 rounded-lg bg-white overflow-hidden shadow divide-y divide-gray-200 md:grid-cols-3 md:divide-y-0 md:divide-x">
        <div>
          <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
              Total Visitors
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
              <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                {{ $analytics['visitors_pageviews']->sum('visitors') }}
                <span class="ml-2 text-sm font-medium text-gray-500">
                  from XX XXX
                </span>
              </div>
    
              <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">
                  Increased by
                </span>
                12%
              </div>
            </dd>
          </div>
        </div>
    
        <div>
          <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
              Total PageViews
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
              <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                {{ $analytics['visitors_pageviews']->sum('pageViews') }}
                <span class="ml-2 text-sm font-medium text-gray-500">
                  from XX XXX
                </span>
              </div>
    
              <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">
                  Increased by
                </span>
                2.02%
              </div>
            </dd>
          </div>
        </div>       
      </dl>
      <div class="mt-5">
        <dt class="text-base font-normal text-gray-900">
          Top referrers
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
</div>
