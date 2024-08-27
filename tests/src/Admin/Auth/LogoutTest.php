<?php

declare(strict_types=1);

use Shopper\Core\Models\User;
use Shopper\Facades\Shopper;
use Shopper\Tests\TestCase;

uses(TestCase::class);

it('can log a user out', function (): void {
    $prefix = Shopper::prefix();

    $this
        ->actingAs(User::factory()->create())
        ->post($prefix . '/logout')
        ->assertRedirect($prefix . '/login');

    $this->assertGuest();
});
