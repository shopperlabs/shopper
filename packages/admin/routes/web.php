<?php

declare(strict_types=1);

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Maatwebsite\Sidebar\Middleware\ResolveSidebars;
use Shopper\Core\Shopper;
use Shopper\Http\Livewire\Pages\Initialization;
use Shopper\Http\Middleware\Authenticate;
use Shopper\Http\Middleware\Dashboard;
use Shopper\Http\Middleware\HasConfiguration;
use Shopper\Http\Middleware\RedirectIfAuthenticated;

Route::domain(config('shopper.admin.domain'))
    ->middleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
    ])
    ->name('shopper.')
    ->group(function () {
        Route::prefix(shopper_prefix())->group(function () {
            Route::middleware([RedirectIfAuthenticated::class])->group(function () {
                require __DIR__ . '/auth.php';
            });

            Route::post('/logout', function (Request $request) {
                Shopper::auth()->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('shopper.login');
            })->name('logout');

            Route::middleware([
                Authenticate::class,
                HasConfiguration::class,
                ResolveSidebars::class,
            ])->get('/initialize', Initialization::class)->name('initialize');

            Route::middleware(array_merge([
                Authenticate::class,
                Dashboard::class,
                ResolveSidebars::class,
            ], config('shopper.routes.middleware', [])))->group(function () {
                require __DIR__ . '/cpanel.php';

                if (config('shopper.routes.custom_file')) {
                    Route::namespace(config('shopper.admin.controllers.namespace'))
                        ->group(config('shopper.routes.custom_file'));
                }
            });
        });
    });
