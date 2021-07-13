<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use LivewireUI\Modal\ModalComponent;

class LogoutOthersBrowser extends ModalComponent
{
    public string $password = '';

    protected array $rules = [
        'password' => 'required',
    ];

    /**
     * Logout from other browser sessions.
     *
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function logoutOtherBrowserSessions(StatefulGuard $guard)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        $guard->logoutOtherDevices($this->password);

        $this->deleteOtherSessionRecords();

        $this->emit('loggedOut');

        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render()
    {
        return view('shopper::livewire.modals.logout-others-browser');
    }

    /**
     * Delete the other browser session records from storage.
     *
     * @return void
     */
    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::table('sessions')
            ->where('user_id', auth()->user()->getKey())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }
}
