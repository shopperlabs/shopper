<?php

namespace Shopper\Framework\Http\Livewire\Account;

use Livewire\Component;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class Devices extends Component
{
    /**
     * Get the current sessions.
     */
    public function getSessionsProperty(): \Illuminate\Support\Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::table('sessions')
                ->where('user_id', auth()->user()->getKey())
                ->orderBy('last_activity', 'desc')
                ->limit(3)
                ->get()
        )->map(function ($session) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'location' => Location::get($session->ip_address),
            ];
        });
    }

    public function render()
    {
        return view('shopper::livewire.account.devices');
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @return \Jenssegers\Agent\Agent
     */
    protected function createAgent($session)
    {
        return tap(new Agent(), function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }
}
