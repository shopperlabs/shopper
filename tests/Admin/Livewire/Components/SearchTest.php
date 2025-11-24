<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Search;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Search::class, function (): void {
    it('can render search component', function (): void {
        Livewire::test(Search::class)
            ->assertOk();
    });

    it('initializes with empty search string', function (): void {
        $component = Livewire::test(Search::class);

        expect($component->get('search'))->toBe('');
    });

    it('can update search string', function (): void {
        $component = Livewire::test(Search::class)
            ->set('search', 'test query');

        expect($component->get('search'))->toBe('test query');
    });

    it('can clear search string', function (): void {
        $component = Livewire::test(Search::class)
            ->set('search', 'test query')
            ->set('search', '');

        expect($component->get('search'))->toBe('');
    });
})->group('livewire', 'components');
