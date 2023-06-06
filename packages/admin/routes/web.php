<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Sidebar\Middleware\ResolveSidebars;
use Shopper\Http\Livewire\Pages\Initialization;
use Shopper\Http\Middleware\Authenticate;
use Shopper\Http\Middleware\Dashboard;
use Shopper\Http\Middleware\HasConfiguration;
use Shopper\Http\Middleware\RedirectIfAuthenticated;

Route::domain(config('shopper.admin.domain'))
    ->middleware('web')
    ->name('shopper.')
    ->group(function () {
        Route::prefix(shopper_prefix())->group(function () {
            Route::middleware([RedirectIfAuthenticated::class])->group(function () {
                require __DIR__ . '/auth.php';
            });

            Route::post('/logout', function (Request $request) {
                Auth::guard(config('shopper.auth.guard'))->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('shopper.login');
            })->name('logout');

            Route::middleware([
                Authenticate::class,
                ResolveSidebars::class,
                HasConfiguration::class,
            ])->get('/initialize', Initialization::class)->name('initialize');

            Route::middleware(array_merge([
                Authenticate::class,
                ResolveSidebars::class,
                Dashboard::class,
            ], config('shopper.routes.middleware', [])))->group(function () {
                require __DIR__ . '/cpanel.php';

                if (config('shopper.routes.custom_file')) {
                    Route::namespace(config('shopper.system.controllers.namespace'))
                        ->group(config('shopper.routes.custom_file'));
                }
            });
        });
    });
