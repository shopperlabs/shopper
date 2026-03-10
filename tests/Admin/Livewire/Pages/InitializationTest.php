<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Initialization;

uses(Tests\Admin\TestCase::class);

describe(Initialization::class, function (): void {
    it('can render initialization component', function (): void {
        Livewire::test(Initialization::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.initialization');
    });
})->group('livewire', 'pages');
