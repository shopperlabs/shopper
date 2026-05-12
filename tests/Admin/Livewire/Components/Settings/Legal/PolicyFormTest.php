<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Legal;
use Shopper\Livewire\Components\Settings\Legal\PolicyForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(PolicyForm::class, function (): void {
    it('blocks `store` for users without `system.settings`', function (): void {
        $unauthorized = User::factory()->create();
        $this->actingAs($unauthorized);

        $legal = Legal::query()->create([
            'title' => 'Privacy',
            'slug' => 'privacy',
            'content' => 'Original body',
            'is_enabled' => true,
        ]);

        Livewire::test(PolicyForm::class, ['legal' => $legal])
            ->fillForm(['content' => 'Tampered body'])
            ->call('store');

        expect($legal->fresh()->content)->toBe('Original body');
    });
})->group('livewire', 'components', 'settings', 'security');
