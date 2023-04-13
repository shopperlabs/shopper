<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

final class CreateInventoriesTable extends Migration
{
    use Database\Migration;

    public function up(): void
    {
        Schema::create($this->getTableName('inventories'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('email')->unique();
            $table->string('street_address');
            $table->string('street_address_plus')->nullable();
            $table->string('zipcode');
            $table->string('city');
            $table->string('phone_number')->nullable();
            $table->integer('priority')->default(0);
            $table->decimal('latitude', 10, 5)->nullable();
            $table->decimal('longitude', 10, 5)->nullable();
            $table->boolean('is_default')->default(false);

            $this->addForeignKey($table, 'country_id', $this->getTableName('system_countries'));
        });

        Schema::create($this->getTableName('inventory_histories'), function (Blueprint $table) {
            $this->addCommonFields($table);

            $table->morphs('stockable');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('quantity');
            $table->integer('old_quantity')->default(0);
            $table->text('event')->nullable();
            $table->text('description')->nullable();

            $this->addForeignKey($table, 'inventory_id', $this->getTableName('inventories'), false);
            $this->addForeignKey($table, 'user_id', $this->getTableName('users'), false);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->getTableName('inventory_histories'));
        Schema::dropIfExists($this->getTableName('inventories'));
    }
}
