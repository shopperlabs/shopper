<?php

declare(strict_types=1);

describe('Debug test', function (): void {
    test('there is not any dd or dump left')
        ->expect(['dd', 'dump'])
        ->not->toBeUsed();
})->group('architecture');
