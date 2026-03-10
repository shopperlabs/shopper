<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Legal;
use Shopper\Livewire\Pages\Settings\LegalPage;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('access_setting');
    $this->actingAs($this->user);
});

describe(LegalPage::class, function (): void {
    it('can render legal page settings component', function (): void {
        Livewire::test(LegalPage::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.legal');
    });

    it('passes legals to view', function (): void {
        $component = Livewire::test(LegalPage::class);
        $legalsCount = Legal::query()->count();

        expect($component->viewData('legals'))->toBeInstanceOf(Illuminate\Support\Collection::class)
            ->and($component->viewData('legals')->count())->toBe($legalsCount);
    });
})->group('livewire', 'settings', 'legal');
