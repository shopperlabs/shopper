<?php

declare(strict_types=1);

use Shopper\Facades\Shopper;
use Tests\Core\Stubs\User;
use Tests\TestCase;

uses(TestCase::class);

it('can log a user out', function (): void {
    $prefix = Shopper::prefix();

    $this
        ->actingAs(User::factory()->create())
        ->post($prefix.'/logout')
        ->assertRedirect($prefix.'/login');

    $this->assertGuest();
});
