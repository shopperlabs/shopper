<?php

declare(strict_types=1);

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Shopper\Http\Controllers\AssetController;
use Shopper\Http\Middleware\Authenticate;
use Shopper\Http\Middleware\Dashboard;
use Shopper\Http\Middleware\DispatchShopper;
use Shopper\Http\Middleware\HasConfiguration;
use Shopper\Http\Middleware\RedirectIfAuthenticated;
use Shopper\Livewire\Pages\Initialization;
use Shopper\Sidebar\Middleware\ResolveSidebars;

Route::domain(config('shopper.admin.domain'))
    ->middleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        DispatchShopper::class,
    ])
    ->group(function (): void {
        Route::prefix(shopper()->prefix())->group(function (): void {
            Route::middleware([RedirectIfAuthenticated::class])
                ->as('shopper.')->group(function (): void {
                    require __DIR__ . '/auth.php';
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

            Route::middleware([
                Authenticate::class,
                HasConfiguration::class,
                ResolveSidebars::class,
            ])->get('/initialize', Initialization::class)->name('shopper.initialize');

            Route::middleware(array_merge([
                Authenticate::class,
                Dashboard::class,
                ResolveSidebars::class,
            ], config('shopper.routes.middleware', [])))->group(function (): void {
                Route::as('shopper.')->group(function (): void {
                    require __DIR__ . '/cpanel.php';
                });

                if (config('shopper.routes.custom_file')) {
                    Route::as('shopper.')->group(config('shopper.routes.custom_file'));
                }
            });
        });
    });
