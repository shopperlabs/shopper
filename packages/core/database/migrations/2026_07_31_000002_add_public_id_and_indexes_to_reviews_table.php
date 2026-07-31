<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $reviews = $this->getTableName('reviews');

        if (! Schema::hasColumn($reviews, 'public_id')) {
            Schema::table($reviews, static function (Blueprint $table): void {
                $table->ulid('public_id')
                    ->nullable()
                    ->unique()
                    ->after('id');
            });

            DB::table($reviews)->whereNull('public_id')->orderBy('id')->lazyById()->each(
                fn (object $row) => DB::table($reviews)
                    ->where('id', $row->id)
                    ->update(['public_id' => (string) Str::ulid()])
            );
        }

        Schema::table($reviews, static function (Blueprint $table): void {
            $table->index(
                ['reviewrateable_type', 'reviewrateable_id', 'approved', 'created_at'],
                'reviews_reviewrateable_approved_created_index',
            );
        });
    }
};
