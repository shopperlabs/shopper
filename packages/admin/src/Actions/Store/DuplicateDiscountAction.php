<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;

final readonly class DuplicateDiscountAction
{
    public function __invoke(Discount $original): Discount
    {
        return DB::transaction(function () use ($original): Discount {
            $original->loadMissing('items');

            $clone = $original->replicate([
                'id',
                'created_at',
                'updated_at',
                'total_use',
            ]);

            $clone->forceFill([
                'total_use' => 0,
                'is_active' => false,
                'code' => $this->resolveUniqueCode($original->code),
            ])->save();

            $original->items->each(function (DiscountDetail $item) use ($clone): void {
                $copy = $item->replicate([
                    'id',
                    'discount_id',
                    'created_at',
                    'updated_at',
                    'total_use',
                ]);

                $copy->forceFill([
                    'total_use' => 0,
                    'discount_id' => $clone->id,
                ])->save();
            });

            return $clone->refresh();
        });
    }

    private function resolveUniqueCode(string $original): string
    {
        $base = mb_substr($original, 0, 240);
        $candidate = $base.'_COPY';
        $attempt = 1;
        $maxAttempts = 50;

        while ($attempt <= $maxAttempts) {
            try {
                if (! Discount::query()->where('code', $candidate)->exists()) {
                    return $candidate;
                }
            } catch (QueryException) {
                // fall through, try next suffix
            }

            $attempt++;
            $candidate = $base.'_COPY_'.$attempt;
        }

        return $base.'_COPY_'.uniqid();
    }
}
