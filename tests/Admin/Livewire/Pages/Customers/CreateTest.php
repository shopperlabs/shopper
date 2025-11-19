<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\User;
use Shopper\Livewire\Pages\Customers\Create;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_customers');
    $this->actingAs($this->user);
});

describe(Create::class, function (): void {
    it('can render customer create component', function (): void {
        Livewire::test(Create::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.customers.create');
    });

    it('passes countries to view', function (): void {
        $component = Livewire::test(Create::class);
        $countries = $component->viewData('countries');

        expect($countries)->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($countries->count())->toBeGreaterThan(0);
    });

    it('initializes form data', function (): void {
        $component = Livewire::test(Create::class);

        expect($component->get('data'))->toBeArray();
    });
})->group('livewire', 'customers');
