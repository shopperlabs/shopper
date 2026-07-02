<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Shopper\Core\Models\Order;

uses(Tests\Core\TestCase::class);

describe('Order number generation', function (): void {
    it('fills a formatted number from the order own id on creation', function (): void {
        $order = Order::factory()->create(['number' => null]);

        $generator = config('shopper.orders.generator');
        $sequence = mb_str_pad(
            (string) ($generator['start_sequence_from'] + $order->id),
            $generator['pad_length'],
            $generator['pad_string'],
            STR_PAD_LEFT,
        );

        expect($order->refresh()->number)
            ->toBe(implode($generator['separator'], [$generator['prefix'], date($generator['date_format']), $sequence]));
    });

    it('generates distinct numbers for successive orders', function (): void {
        $first = Order::factory()->create(['number' => null]);
        $second = Order::factory()->create(['number' => null]);

        expect($first->refresh()->number)->not->toBe($second->refresh()->number);
    });

    it('keeps an explicitly provided number untouched', function (): void {
        $order = Order::factory()->create(['number' => 'CUSTOM-001']);

        expect($order->refresh()->number)->toBe('CUSTOM-001');
    });

    it('rejects a duplicate number at the database level', function (): void {
        Order::factory()->create(['number' => 'DUP-001']);

        expect(fn () => Order::factory()->create(['number' => 'DUP-001']))
            ->toThrow(QueryException::class);
    });
})->group('core', 'orders');
