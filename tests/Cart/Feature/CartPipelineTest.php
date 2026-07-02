<?php

declare(strict_types=1);

use Shopper\Cart\Pipelines\CalculateLines;
use Shopper\Cart\Pipelines\CalculateTax;
use Shopper\Cart\Pipelines\CartPipeline;

uses(Tests\Cart\TestCase::class);

const PIPELINE_BASE = [CalculateLines::class, CalculateTax::class];

describe(CartPipeline::class, function (): void {
    it('returns the base pipeline unchanged when no step is registered', function (): void {
        $pipeline = new CartPipeline;

        expect($pipeline->build(PIPELINE_BASE))->toBe(PIPELINE_BASE);
    });

    it('inserts a step after an anchor', function (): void {
        $pipeline = new CartPipeline;
        $pipeline->insertAfter(CalculateLines::class, 'App\\GiftCardStep');

        expect($pipeline->build(PIPELINE_BASE))
            ->toBe([CalculateLines::class, 'App\\GiftCardStep', CalculateTax::class]);
    });

    it('inserts a step before an anchor', function (): void {
        $pipeline = new CartPipeline;
        $pipeline->insertBefore(CalculateTax::class, 'App\\PriceListStep');

        expect($pipeline->build(PIPELINE_BASE))
            ->toBe([CalculateLines::class, 'App\\PriceListStep', CalculateTax::class]);
    });

    it('appends a step at the end', function (): void {
        $pipeline = new CartPipeline;
        $pipeline->append('App\\LoyaltyStep');

        expect($pipeline->build(PIPELINE_BASE))
            ->toBe([CalculateLines::class, CalculateTax::class, 'App\\LoyaltyStep']);
    });

    it('keeps a deterministic order when two add-ons anchor on the same step', function (): void {
        $pipeline = new CartPipeline;
        $pipeline->insertAfter(CalculateLines::class, 'App\\StepA');
        $pipeline->insertAfter(CalculateLines::class, 'App\\StepB');

        expect($pipeline->build(PIPELINE_BASE))
            ->toBe([CalculateLines::class, 'App\\StepB', 'App\\StepA', CalculateTax::class]);
    });

    it('throws when the anchor step is not in the pipeline', function (): void {
        $pipeline = new CartPipeline;
        $pipeline->insertAfter('App\\Missing', 'App\\Step');

        expect(fn (): array => $pipeline->build(PIPELINE_BASE))
            ->toThrow(InvalidArgumentException::class);
    });
})->group('cart', 'pipeline');
