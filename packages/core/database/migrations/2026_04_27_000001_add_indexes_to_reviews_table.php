<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->getTableName('reviews'), static function (Blueprint $table): void {
            $table->index('approved');
            $table->index('rating');
            $table->index('is_recommended');
            $table->index('created_at');
            $table->index(['approved', 'created_at']);
        });
    }
};
