<?php

declare(strict_types=1);

namespace Shopper\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Order;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:orders:reclaim')]
final class ReclaimPendingOrdersCommand extends Command
{
    protected $signature = 'shopper:orders:reclaim
                            {--hours= : Cancel unpaid pending orders older than this many hours}';

    protected $description = 'Cancel unpaid pending orders past the configured window so their reserved stock returns to sale';

    public function handle(): int
    {
        $hours = $this->option('hours') !== null
            ? (int) $this->option('hours')
            : config('shopper.orders.reclaim_pending_after_hours');

        if (! $hours) {
            $this->components->info('Pending order reclaim is disabled. Set `shopper.orders.reclaim_pending_after_hours` or pass --hours.');

            return self::SUCCESS;
        }

        $reclaimed = 0;

        resolve(OrderContract::class)::query()
            ->where('payment_status', PaymentStatus::Pending)
            ->where('status', OrderStatus::New)
            ->where('created_at', '<', now()->subHours($hours))
            ->whereHas('paymentMethod', function ($query): void {
                $query->whereNotNull('driver')->where('driver', '<>', 'manual');
            })
            ->chunkById(100, function (Collection $orders) use (&$reclaimed): void {
                /** @var Order $order */
                foreach ($orders as $order) {
                    if (! $order->canBeCancelled() || ! $order->canTransitionTo(OrderStatus::Cancelled)) {
                        continue;
                    }

                    $order->transitionTo(OrderStatus::Cancelled);

                    event(new OrderCancelled($order));

                    $reclaimed++;
                }
            });

        $this->components->info("{$reclaimed} unpaid pending order(s) cancelled, their stock is back on sale.");

        return self::SUCCESS;
    }
}
