<?php

declare(strict_types=1);

namespace Shopper\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\StockLevel;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:stock:reconcile')]
final class ReconcileStockLevelsCommand extends Command
{
    protected $signature = 'shopper:stock:reconcile
                            {--fix : Rewrite the drifted snapshot rows from the ledger}';

    protected $description = 'Compare the stock level snapshot against the inventory ledger and report any drift';

    public function handle(): int
    {
        $ledger = DB::table(shopper_table('inventory_histories'))
            ->selectRaw('stockable_type, stockable_id, inventory_id, SUM(quantity) as quantity')
            ->groupBy('stockable_type', 'stockable_id', 'inventory_id')
            ->get()
            ->keyBy(fn (object $row): string => "{$row->stockable_type}:{$row->stockable_id}:{$row->inventory_id}");

        $levels = StockLevel::query()
            ->get()
            ->keyBy(fn (StockLevel $level): string => "{$level->stockable_type}:{$level->stockable_id}:{$level->inventory_id}");

        $drifted = 0;

        foreach ($ledger as $key => $row) {
            $level = $levels->get($key);
            $snapshot = $level instanceof StockLevel ? $level->quantity : 0;

            if ($snapshot === (int) $row->quantity) {
                continue;
            }

            $drifted++;
            $this->components->warn("[{$key}] snapshot {$snapshot}, ledger {$row->quantity}");

            if ($this->option('fix')) {
                StockLevel::query()->updateOrCreate(
                    [
                        'stockable_type' => $row->stockable_type,
                        'stockable_id' => $row->stockable_id,
                        'inventory_id' => $row->inventory_id,
                    ],
                    ['quantity' => (int) $row->quantity],
                );
            }
        }

        foreach ($levels as $key => $level) {
            if ($ledger->has($key) || $level->quantity === 0) {
                continue;
            }

            $drifted++;
            $this->components->warn("[{$key}] snapshot {$level->quantity}, ledger 0");

            if ($this->option('fix')) {
                $level->update(['quantity' => 0]);
            }
        }

        if ($drifted === 0) {
            $this->components->info('The stock snapshot matches the ledger.');

            return self::SUCCESS;
        }

        $this->components->{$this->option('fix') ? 'info' : 'error'}(
            $this->option('fix')
                ? "{$drifted} drifted rows rewritten from the ledger."
                : "{$drifted} drifted rows found. Run with --fix to rewrite them from the ledger.",
        );

        return $this->option('fix') ? self::SUCCESS : self::FAILURE;
    }
}
