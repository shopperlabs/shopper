<?php

declare(strict_types=1);

use Shopper\Upgrade\Rector\ClassRenames;

function symbolExists(string $fqcn): bool
{
    return class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn);
}

describe('ClassRenames::MAP', function (): void {
    it('maps every removed 2.x symbol to an existing 3.x symbol', function (): void {
        foreach (ClassRenames::MAP as $old => $new) {
            expect(symbolExists($new))->toBeTrue("3.x target [{$new}] does not exist");
            expect(symbolExists($old))->toBeFalse("2.x source [{$old}] still exists on 3.x, it is not a clean rename");
        }
    });

    it('never points two different sources at the same target', function (): void {
        $targets = array_values(ClassRenames::MAP);

        expect($targets)->toHaveCount(count(array_unique($targets)));
    });

    it('keeps the source and target fully qualified without a leading slash', function (): void {
        foreach (ClassRenames::MAP as $old => $new) {
            expect($old)->not->toStartWith('\\')
                ->and($new)->not->toStartWith('\\')
                ->and($old)->not->toBe($new);
        }
    });
})
    ->group('upgrade');
