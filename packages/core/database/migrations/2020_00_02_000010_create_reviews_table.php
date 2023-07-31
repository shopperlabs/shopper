<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('reviews'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->boolean('is_recommended')->default(false);
            $table->integer('rating');
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->boolean('approved')->default(false);
            $table->morphs('reviewrateable');
            $table->morphs('author');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('reviews'));
    }
};
