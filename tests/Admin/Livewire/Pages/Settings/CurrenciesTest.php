<?php

declare(strict_types=1);

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Livewire\Livewire;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Pages\Settings\Currencies;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);

    setupCurrencies(['USD', 'EUR', 'GBP'], 'USD');

    $this->default = Currency::query()->where('code', 'USD')->sole();
    $this->zoneCurrency = Currency::query()->where('code', 'EUR')->sole();
    $this->other = Currency::query()->where('code', 'GBP')->sole();

    Zone::factory()->create(['currency_id' => $this->zoneCurrency->id]);
});

describe(Currencies::class, function (): void {
    it('locks the status toggle of the default currency and of a zone currency', function (): void {
        Livewire::test(Currencies::class)
            ->loadTable()
            ->assertTableColumnExists('is_enabled', fn (ToggleColumn $column): bool => $column->isDisabled(), $this->default)
            ->assertTableColumnExists('is_enabled', fn (ToggleColumn $column): bool => $column->isDisabled(), $this->zoneCurrency)
            ->assertTableColumnExists('is_enabled', fn (ToggleColumn $column): bool => ! $column->isDisabled(), $this->other)
            ->assertTableColumnExists('name', fn (TextColumn $column): bool => $column->getDescriptionBelow() === __('shopper::pages/settings/currencies.locked'), $this->zoneCurrency)
            ->assertTableColumnExists('name', fn (TextColumn $column): bool => $column->getDescriptionBelow() === null, $this->other)
            ->call('updateTableColumnState', 'is_enabled', (string) $this->default->id, false)
            ->call('updateTableColumnState', 'is_enabled', (string) $this->zoneCurrency->id, false)
            ->call('updateTableColumnState', 'is_enabled', (string) $this->other->id, false);

        expect($this->default->fresh()->is_enabled)->toBeTrue()
            ->and($this->zoneCurrency->fresh()->is_enabled)->toBeTrue()
            ->and($this->other->fresh()->is_enabled)->toBeFalse();
    });

    it('leaves the default currency and zone currencies out of the bulk disable', function (): void {
        Livewire::test(Currencies::class)
            ->loadTable()
            ->callTableBulkAction('disable', [$this->default, $this->zoneCurrency, $this->other]);

        expect($this->default->fresh()->is_enabled)->toBeTrue()
            ->and($this->zoneCurrency->fresh()->is_enabled)->toBeTrue()
            ->and($this->other->fresh()->is_enabled)->toBeFalse();
    });

    it('lets a disabled zone currency be enabled again', function (): void {
        $this->zoneCurrency->update(['is_enabled' => false]);

        Livewire::test(Currencies::class)
            ->loadTable()
            ->call('updateTableColumnState', 'is_enabled', (string) $this->zoneCurrency->id, true);

        expect($this->zoneCurrency->fresh()->is_enabled)->toBeTrue();
    });
})->group('livewire', 'settings', 'currencies');
