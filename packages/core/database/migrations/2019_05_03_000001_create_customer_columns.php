<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'name')) {
                $table->renameColumn('name', 'last_name');
            } else {
                $table->string('last_name');
            }

            $table->string('password')->nullable()->change();

            $table->after('id', static function (Blueprint $table): void {
                $table->string('first_name')->nullable();
                $table->string('gender');
                $table->string('phone_number')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('avatar_type')->default('avatar_ui');
                $table->string('avatar_location')->nullable();
                $table->string('timezone')->nullable();
                $table->boolean('opt_in')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->string('last_login_ip')->nullable();
            });
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropColumn([
                'first_name',
                'gender',
                'phone_number',
                'birth_date',
                'avatar_type',
                'avatar_location',
                'timezone',
                'opt_in',
                'last_login_at',
                'last_login_ip',
            ]);

            if (Schema::hasColumn('users', 'last_name')) {
                $table->renameColumn('last_name', 'name');
            } else {
                $table->string('name');
            }

            $table->string('password')->change();
        });
    }
};
