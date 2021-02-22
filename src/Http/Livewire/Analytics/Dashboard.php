<?php

namespace Shopper\Framework\Http\Livewire\Analytics;

use Illuminate\Support\Facades\File;
use Livewire\Component;
use Analytics;
use Spatie\Analytics\Period;
use Illuminate\Support\Carbon;

class Dashboard extends Component
{
    /**
     * From starting date
     *
     * @var string
     */
    public $fromDate;

    /**
     * To ending date
     *
     * @var string
     */
    public $toDate;

    /**
     * Date period
     *
     * @var string
     */
    protected $period;

    /**
     * Analytics data.
     *
     * @var array
     */
    protected $analytics;

    /**
     * Component mount instance.
     *
     * @return void
     * @throws \Spatie\Analytics\Exceptions\InvalidPeriod
     */
    public function mount()
    {
        $this->fromDate = Carbon::now()->subDays(7);
        $this->toDate = Carbon::now();

        if (File::exists(storage_path('app/analytics/service-account-credentials.json')) && env('ANALYTICS_VIEW_ID')) {
            $this->enabledAnalytics();
        }
    }

    /**
     * Enabled Google Analytics.
     *
     * @throws \Spatie\Analytics\Exceptions\InvalidPeriod
     * @return void
     */
    public function enabledAnalytics()
    {
        $this->period = new Period(new Carbon($this->fromDate), new Carbon($this->toDate));

        $period_visitors_pviews = Analytics::fetchVisitorsAndPageViews($this->period);

        $total_visitors = $period_visitors_pviews->sum('visitors');
        $total_pageviews = $period_visitors_pviews->sum('pageViews');

        $visitors_pviews = Analytics::performQuery($this->period, 'ga:visitors, ga:pageviews', ['dimensions' => 'ga:date', 'max-results' => 5, 'sort' => '-ga:date']);

        $top_referrers = Analytics::fetchTopReferrers($this->period, 5);
        $top_browsers = Analytics::fetchTopBrowsers($this->period, 5);
        $most_visited_pages = Analytics::fetchMostVisitedPages($this->period, 5);
        $top_medium = Analytics::performQuery($this->period, 'ga:sessions', ['dimensions' => 'ga:sourceMedium', 'max-results' => 5, 'sort' => '-ga:sessions']);
        if (isset($top_medium->rows)) $top_medium = collect($top_medium->rows)->map(function ($el, $i) {
            return ['name' => $el[0], 'sessions' => number_format($el[1], 0, '.', ' ')];
        });

        $top_devices = Analytics::performQuery($this->period, 'ga:sessions', ['dimensions' => 'ga:deviceCategory, ga:operatingSystem', 'max-results' => 5, 'sort' => '-ga:sessions']);
        if (isset($top_devices) && isset($top_devices->rows)) $top_devices = collect($top_devices->rows)->map(function ($el, $i) {
            return ['name' => $el[0], 'os' => $el[1], 'sessions' => number_format($el[2], 0, '.', ' ')];
        });

        $top_geolocations = Analytics::performQuery($this->period, 'ga:sessions', ['dimensions' => 'ga:country', 'max-results' => 5, 'sort' => '-ga:sessions']);
        if (isset($top_geolocations->rows)) $top_geolocations = collect($top_geolocations->rows)->map(function ($el, $i) {
            return ['name' => $el[0], 'sessions' => number_format($el[1], 0, '.', ' ')];
        });

        $top_socials = Analytics::performQuery($this->period, 'ga:sessions', ['dimensions' => 'ga:socialNetwork', 'max-results' => 5, 'sort' => '-ga:sessions']);
        if (isset($top_socials->rows)) $top_socials = collect($top_socials->rows)->map(function ($el, $i) {
            return ['name' => $el[0], 'sessions' => number_format($el[1], 0, '.', ' ')];
        });

        $this->analytics = [
            'visitors'                => isset($visitors_pviews) ? $visitors_pviews->rows : [],
            'total_visitors'          => isset($total_visitors) ? number_format($total_visitors, 0, '.', ' ') : '-',
            'total_pageviews'         => isset($total_pageviews) ? number_format($total_pageviews, 0, '.', ' ') : '-',
            'most_visited_pages'      => $most_visited_pages,
            'top_browsers'            => $top_browsers,
            'top_geolocations'        => $top_geolocations,
            'top_referrers'           => $top_referrers,
            'top_devices'             => isset($top_devices) ? $top_devices : [],
            'top_socials'             => $top_socials,
            'top_medium'              => $top_medium,
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.analytics.dashboard', [
            'period'    => $this->period,
            'analytics' => $this->analytics,
            'can_display_analytics' => File::exists(storage_path('app/analytics/service-account-credentials.json')) && env('ANALYTICS_VIEW_ID')
        ]);
    }
}
