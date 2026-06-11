<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $table = $this->usersTable();

        if (! Schema::hasTable($table) || Schema::hasColumn($table, 'public_id')) {
            return;
        }

        Schema::table($table, static function (Blueprint $blueprint): void {
            $blueprint->ulid('public_id')->nullable()->unique()->after('id');
        });

        DB::table($table)->whereNull('public_id')->orderBy('id')->lazyById()->each(
            fn (object $row) => DB::table($table)
                ->where('id', $row->id)
                ->update(['public_id' => (string) Str::ulid()])
        );
    }

    private function usersTable(): string
    {
        /** @var class-string<Model> $model */
        $model = (string) config('auth.providers.users.model');

        return class_exists($model) ? (new $model)->getTable() : 'users';
    }
};
