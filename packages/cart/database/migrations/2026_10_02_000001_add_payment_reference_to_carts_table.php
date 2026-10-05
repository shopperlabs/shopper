<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $carts = $this->getTableName('carts');

        Schema::table($carts, static function (Blueprint $table): void {
            $table->string('payment_reference')->nullable()->index();
        });

        DB::table($carts)
            ->whereNull('completed_at')
            ->whereNotNull('payment_session->reference')
            ->update(['payment_reference' => DB::raw(DB::getQueryGrammar()->wrap('payment_session->reference'))]);
    }

    public function down(): void
    {
        Schema::table($this->getTableName('carts'), static function (Blueprint $table): void {
            $table->dropIndex(['payment_reference']);
            $table->dropColumn('payment_reference');
        });
    }
};
