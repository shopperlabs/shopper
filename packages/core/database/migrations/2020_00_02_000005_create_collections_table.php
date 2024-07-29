<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('collections'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->string('type');
            $table->string('sort')->nullable();
            $table->string('match_conditions')->nullable();
            $table->dateTimeTz('published_at')->default(now());

            $this->addSeoFields($table);
        });

        Schema::create($this->getTableName('collection_rules'), function (Blueprint $table): void {
            $this->addCommonFields($table);

            $table->string('rule');
            $table->string('operator');
            $table->string('value');

            $this->addForeignKey($table, 'collection_id', $this->getTableName('collections'), false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('collection_rules'));
        Schema::dropIfExists($this->getTableName('collections'));
    }
};
