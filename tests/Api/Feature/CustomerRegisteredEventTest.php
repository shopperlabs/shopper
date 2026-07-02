<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Events\Customers\CustomerRegistered;

uses(Tests\Api\TestCase::class);

it('dispatches `CustomerRegistered` when a customer registers through the Store API', function (): void {
    Event::fake([CustomerRegistered::class]);

    $this->postJson('/store/auth/register', [
        'first_name' => 'Amadou',
        'last_name' => 'Diallo',
        'email' => 'amadou@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ])->assertCreated();

    Event::assertDispatched(
        CustomerRegistered::class,
        fn (CustomerRegistered $event): bool => $event->customer->email === 'amadou@example.com',
    );
});
