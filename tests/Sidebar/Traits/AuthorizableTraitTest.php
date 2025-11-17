<?php

declare(strict_types=1);

use Tests\Sidebar\Stubs\AuthorizableStub;

it('can be authorized', function (): void {
    $routeable = new AuthorizableStub;
    $routeable->setAuthorized();
    expect($routeable->isAuthorized())->toBeTrue();

    $routeable->setAuthorized(false);
    expect($routeable->isAuthorized())->toBeFalse();

})->group('Traits');
