<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('product_imports'), static function (Blueprint $table): void {
            $table->id();
            $table->string('public_id', 26)->nullable()->unique();
            $table->string('source');
            $table->string('disk');
            $table->string('file_path');
            $table->string('status');
            $table->unsignedInteger('total_products')->default(0);
            $table->unsignedInteger('imported_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->jsonb('mapping')->nullable();
            $table->jsonb('errors')->nullable();
            $table->string('batch_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('product_imports'));
    }
};
