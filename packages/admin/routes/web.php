<?php

declare(strict_types=1);

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Shopper\Http\Controllers\AssetController;
use Shopper\Http\Controllers\Auth\PasskeyRegistrationController;
use Shopper\Http\Middleware\Authenticate;
use Shopper\Http\Middleware\Dashboard;
use Shopper\Http\Middleware\DispatchShopper;
use Shopper\Http\Middleware\HasConfiguration;
use Shopper\Http\Middleware\RedirectIfAuthenticated;
use Shopper\Http\Middleware\SetLocale;
use Shopper\Livewire\Pages\Forbidden;
use Shopper\Livewire\Pages\Initialization;
use Shopper\Sidebar\Middleware\ResolveSidebars;

$csrfMiddleware = class_exists(PreventRequestForgery::class)
    ? PreventRequestForgery::class
    : VerifyCsrfToken::class;

Route::domain(config('shopper.admin.domain'))
    ->middleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        $csrfMiddleware,
        SubstituteBindings::class,
        DispatchShopper::class,
        SetLocale::class,
    ])
    ->prefix(shopper()->prefix())
    ->group(function (): void {
        Route::middleware([RedirectIfAuthenticated::class])
            ->as('shopper.')->group(function (): void {
                require __DIR__.'/auth.php';
            });

        Route::get('/assets/{file}', AssetController::class)
            ->where('file', '.*')
            ->name('shopper.asset');

        Route::post('/logout', function (Request $request): RedirectResponse {
            shopper()->auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('shopper.login');
        })->name('shopper.logout');

        Route::get('/csrf-token', fn (): JsonResponse => response()->json([
            'token' => csrf_token(),
        ]))->name('shopper.csrf-token');

        Route::middleware([
            Authenticate::class,
            HasConfiguration::class,
            ResolveSidebars::class,
        ])->group(function (): void {
            Route::livewire('/initialize', Initialization::class)->name('shopper.initialize');
        });

        Route::middleware([
            Authenticate::class,
            ResolveSidebars::class,
        ])->group(function (): void {
            Route::livewire('/forbidden', Forbidden::class)->name('shopper.forbidden');
        });

        if (config('shopper.auth.passkeys_enabled')) {
            Route::middleware(Authenticate::class)->as('shopper.')->group(function (): void {
                Route::get('/passkeys/options', [PasskeyRegistrationController::class, 'options'])
                    ->name('passkeys.registration-options');
                Route::post('/passkeys', [PasskeyRegistrationController::class, 'store'])
                    ->name('passkeys.store');
            });
        }

        Route::middleware(array_merge([
            Authenticate::class,
            Dashboard::class,
            ResolveSidebars::class,
        ], config('shopper.routes.middleware', [])))->group(function (): void {
            Route::as('shopper.')->group(function (): void {
                require __DIR__.'/cpanel.php';
            });

            if (config('shopper.routes.custom_file')) {
                Route::as('shopper.')->group(config('shopper.routes.custom_file'));
            }

            foreach (shopper()->addonManager()->getRoutes() as $addonRoutes) {
                Route::as('shopper.')->group($addonRoutes);
            }
        });
    });
