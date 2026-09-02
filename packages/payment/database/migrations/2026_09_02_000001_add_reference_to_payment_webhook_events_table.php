<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $table = $this->getTableName('payment_webhook_events');

        Schema::table($table, static function (Blueprint $table): void {
            $table->string('reference')->nullable()->after('type')->index();
            $table->index('processed_at');
        });

        $this->backfill($table, $this->getTableName('payment_transactions'));
    }

    public function down(): void
    {
        Schema::table($this->getTableName('payment_webhook_events'), static function (Blueprint $table): void {
            $table->dropIndex(['processed_at']);
            $table->dropIndex(['reference']);
            $table->dropColumn('reference');
        });
    }

    /**
     * processed_at used to mean "received" and now means "applied to an
     * order". An event journalized before any transaction carried its
     * reference had no order to apply to, so it is put back in line for the
     * reconcile command; re-applying an already settled event is a no-op.
     */
    private function backfill(string $events, string $transactions): void
    {
        DB::table($events)
            ->whereNull('reference')
            ->orderBy('id')
            ->chunkById(500, static function (Collection $rows) use ($events, $transactions): void {
                foreach ($rows as $row) {
                    $payload = json_decode((string) $row->payload, true);
                    $reference = is_array($payload) ? ($payload['reference'] ?? null) : null;

                    if (! is_string($reference)) {
                        continue;
                    }

                    $journalized = DB::table($transactions)->where('reference', $reference)->min('created_at');

                    $applied = $journalized !== null
                        && Carbon::parse($journalized)->lt(Carbon::parse($row->created_at));

                    DB::table($events)->where('id', $row->id)->update([
                        'reference' => $reference,
                        'processed_at' => $applied ? $row->processed_at : null,
                    ]);
                }
            });
    }
};
