<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Livewire;
use Shopper\Traits\HandlesAuthorizationExceptions;

uses(Tests\Admin\TestCase::class);

it('lets errors that are not exceptions propagate', function (): void {
    $component = new class extends Component
    {
        use HandlesAuthorizationExceptions;

        public function explode(): void
        {
            throw new Error('Boom');
        }

        public function render(): string
        {
            return '<div></div>';
        }
    };

    Livewire::test($component::class)->call('explode');
})->throws(Error::class, 'Boom');
