<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Enum\GenderType;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('name');
            $table->string('password')->nullable()->change();

            $table->after('id', function (Blueprint $table): void {
                $table->string('first_name')->nullable();
                $table->string('last_name');
                $table->string('gender')->default(GenderType::Male());
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
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'first_name',
                'last_name',
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

            $table->string('name');
            $table->string('password')->change();
        });
    }
};
