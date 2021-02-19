<?php

namespace Shopper\Framework\Http\Livewire\Analytics;

use Livewire\Component;
use Analytics;
use Spatie\Analytics\Period;

class Dashboard extends Component
{
    /**
     * Analytics data.
     *
     * @var array
     */
    protected $analyticsData;

    /**
     * Component mount instance.
     *
     * @return void
     */
    public function mount()
    {
        //retrieve visitors and pageview data for the current day and the last seven days
        // $this->analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));
        $this->analyticsData = [
            'visitors_pageviews' => Analytics::fetchTotalVisitorsAndPageViews(Period::days(30)),
            'top_referrers'      => Analytics::fetchTopReferrers(Period::days(30)),
        ];

        //retrieve visitors and pageviews since the 6 months ago
        // $this->analyticsData = Analytics::fetchVisitorsAndPageViews(Period::months(6));

        //retrieve sessions and pageviews with yearMonth dimension since 1 year ago
        // $this->analyticsData = Analytics::performQuery(
        //     Period::years(1),
        //     'ga:sessions',
        //     [
        //         'metrics' => 'ga:sessions, ga:pageviews',
        //         'dimensions' => 'ga:yearMonth'
        //     ]
        // );
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.analytics.dashboard', [
            'analytics' => $this->analyticsData
        ]);
    }
}
