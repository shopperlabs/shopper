<?php

declare(strict_types=1);

use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $table = $this->getTableName('zone_has_relations');

        $types = [
            'Shopper\Core\Models\Country' => 'country',
            'Shopper\Core\Models\Carrier' => 'carrier',
            'Shopper\Core\Models\PaymentMethod' => 'payment_method',
        ];

        foreach ($types as $class => $alias) {
            $this->deleteRowsReplacedByAlias($table, $class, $alias, 'zone_id');
        }

        $this->deleteRowsReplacedByAlias($table, 'Shopper\Core\Models\Country', 'country', 'zonable_id');

        foreach ($types as $class => $alias) {
            DB::table($table)
                ->where('zonable_type', $class)
                ->update(['zonable_type' => $alias]);
        }
    }

    private function deleteRowsReplacedByAlias(string $table, string $class, string $alias, string $column): void
    {
        DB::table($table)
            ->join("{$table} as twin", function (JoinClause $join) use ($table, $alias, $column): void {
                $join->on("twin.{$column}", '=', "{$table}.{$column}")
                    ->where('twin.zonable_type', '=', $alias);
            })
            ->where("{$table}.zonable_type", $class)
            ->delete();
    }
};
