<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Pages\Customers\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->assignRole(config('shopper.admin.roles.admin'));
    $this->user->givePermissionTo('customers.browse');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render customers index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.customers.index');
    });

    it('requires customers.browse permission', function (): void {
        $unprivileged = User::factory()->create();
        $this->actingAs($unprivileged);

        Livewire::test(Index::class)
            ->assertForbidden();
    });

    it('can list customers in table', function (): void {
        User::factory()->count(3)->create();

        Livewire::test(Index::class)
            ->assertOk();
    });

    it('returns zero stats with no customers', function (): void {
        $stats = Livewire::test(Index::class)->instance()->stats;

        expect($stats)->toMatchArray([
            'total' => 0,
            'new_count' => 0,
            'active_count' => 0,
            'active_percent' => 0,
            'avg_ltv' => 0,
        ]);
    });

    it('counts users that are not administrators', function (): void {
        $customer = User::factory()->create();
        $customer->assignRole(config('shopper.admin.roles.user'));

        $stats = Livewire::test(Index::class)->instance()->stats;

        expect($stats['total'])->toBe(1);
    });

    it('counts new customers created in the last 30 days', function (): void {
        $recent = User::factory()->create(['created_at' => now()->subDays(5)]);
        $recent->assignRole(config('shopper.admin.roles.user'));

        $old = User::factory()->create(['created_at' => now()->subDays(60)]);
        $old->assignRole(config('shopper.admin.roles.user'));

        $stats = Livewire::test(Index::class)->instance()->stats;

        expect($stats['total'])->toBe(2)
            ->and($stats['new_count'])->toBe(1);
    });

    it('marks customers active only when they have a paid order', function (): void {
        $active = User::factory()->create();
        $active->assignRole(config('shopper.admin.roles.user'));

        Order::factory()->create([
            'customer_id' => $active->id,
            'status' => OrderStatus::Completed->value,
            'payment_status' => PaymentStatus::Paid->value,
            'price_amount' => 50_00,
            'currency_code' => shopper_currency(),
        ]);

        $passive = User::factory()->create();
        $passive->assignRole(config('shopper.admin.roles.user'));

        $stats = Livewire::test(Index::class)->instance()->stats;

        expect($stats['active_count'])->toBe(1)
            ->and($stats['active_percent'])->toBe(50);
    });

    it('computes the average lifetime value across active customers', function (): void {
        $first = User::factory()->create();
        $first->assignRole(config('shopper.admin.roles.user'));

        Order::factory()->count(2)->sequence(
            ['price_amount' => 100_00],
            ['price_amount' => 100_00],
        )->create([
            'customer_id' => $first->id,
            'status' => OrderStatus::Completed->value,
            'payment_status' => PaymentStatus::Paid->value,
            'currency_code' => shopper_currency(),
        ]);

        $second = User::factory()->create();
        $second->assignRole(config('shopper.admin.roles.user'));

        Order::factory()->create([
            'customer_id' => $second->id,
            'price_amount' => 400_00,
            'status' => OrderStatus::Processing->value,
            'payment_status' => PaymentStatus::Paid->value,
            'currency_code' => shopper_currency(),
        ]);

        $stats = Livewire::test(Index::class)->instance()->stats;

        expect($stats['avg_ltv'])->toBe(300_00);
    });

    it('ignores unpaid or cancelled orders when computing the average lifetime value', function (): void {
        $customer = User::factory()->create();
        $customer->assignRole(config('shopper.admin.roles.user'));

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Cancelled->value,
            'payment_status' => PaymentStatus::Paid->value,
            'price_amount' => 999_00,
            'currency_code' => shopper_currency(),
        ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Completed->value,
            'payment_status' => PaymentStatus::Pending->value,
            'price_amount' => 999_00,
            'currency_code' => shopper_currency(),
        ]);

        $stats = Livewire::test(Index::class)->instance()->stats;

        expect($stats['active_count'])->toBe(0)
            ->and($stats['avg_ltv'])->toBe(0);
    });
})->group('livewire', 'customers');
