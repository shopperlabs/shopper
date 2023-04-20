<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Routing\Controller;

class ForgotPasswordController extends Controller
{
    use ValidatesRequests;
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage())
                ->view('shopper::mails.email')
                ->line(__('shopper::pages/auth.email.mail.content'))
                ->action(__('shopper::pages/auth.email.mail.action'), url(config('app.url') . route('shopper.password.reset', $token, false)))
                ->line(__('shopper::pages/auth.email.mail.message'));
        });
    }

    public function showLinkRequestForm(): View
    {
        return view('shopper::auth.passwords.forgot-password');
    }

    protected function sendResetLinkResponse(Request $request, string $response): RedirectResponse
    {
        session()->flash('success', trans($response));

        return back();
    }

    protected function sendResetLinkFailedResponse(Request $request, string $response): RedirectResponse
    {
        session()->flash('error', trans($response));

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
