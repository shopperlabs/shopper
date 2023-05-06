<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateReviewsTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('reviews'), function (Blueprint $table) {
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
}
