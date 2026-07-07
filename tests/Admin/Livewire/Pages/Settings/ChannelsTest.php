<?php

declare(strict_types=1);

use Filament\Tables\Columns\ToggleColumn;
use Livewire\Livewire;
use Shopper\Core\Channel\Contracts\ChannelDriver;
use Shopper\Core\Channel\Facades\Channels;
use Shopper\Core\Models\Channel;
use Shopper\Livewire\Pages\Settings\Channels as ChannelsPage;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);
});

describe(ChannelsPage::class, function (): void {
    it('can render the channels settings component', function (): void {
        Livewire::test(ChannelsPage::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.channels');
    });

    it('lists channels in the table', function (): void {
        Channel::factory()->count(3)->create();

        Livewire::test(ChannelsPage::class)
            ->loadTable()
            ->assertCanSeeTableRecords(Channel::query()->limit(3)->get());
    });

    it('renders the web driver badge for a driverless channel', function (): void {
        $default = Channel::query()->where('is_default', true)->first();

        Livewire::test(ChannelsPage::class)
            ->loadTable()
            ->assertTableColumnStateSet('driver', 'web', $default);
    });

    it('shows the driver name for a channel bound to a registered driver', function (): void {
        Channels::extend('custom', fn (): ChannelDriver => new class implements ChannelDriver
        {
            public function code(): string
            {
                return 'custom';
            }

            public function name(): string
            {
                return 'Custom Channel';
            }

            public function logo(): ?string
            {
                return null;
            }

            public function isConfigured(): bool
            {
                return true;
            }
        });

        Channel::factory()->create(['driver' => 'custom', 'name' => 'Custom Store']);

        Livewire::test(ChannelsPage::class)
            ->loadTable()
            ->assertSee('Custom Channel');
    });

    it('forbids mount for users without `system.settings`', function (): void {
        $this->actingAs(User::factory()->create());

        Livewire::test(ChannelsPage::class)->assertForbidden();
    });

    it('renders a channel bound to an unregistered driver without crashing', function (): void {
        Channel::factory()->create(['driver' => 'shopify', 'name' => 'Ghost Store']);

        Livewire::test(ChannelsPage::class)
            ->loadTable()
            ->assertOk()
            ->assertSee('shopify');
    });

    it('disables the enabled toggle for the default channel but not for others', function (): void {
        $default = Channel::query()->where('is_default', true)->first();
        $other = Channel::factory()->create(['is_default' => false]);

        Livewire::test(ChannelsPage::class)
            ->loadTable()
            ->assertTableColumnExists('is_enabled', fn (ToggleColumn $column): bool => $column->isDisabled(), $default)
            ->assertTableColumnExists('is_enabled', fn (ToggleColumn $column): bool => ! $column->isDisabled(), $other);
    });
});
