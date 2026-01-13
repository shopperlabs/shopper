<?php

declare(strict_types=1);

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Helpers\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $teams = config('permission.teams');
        /** @var array<string, string> $tableNames */
        $tableNames = config('permission.table_names');
        /** @var array<string, string|null> $columnNames */
        $columnNames = config('permission.column_names');

        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $teamForeignKey = $columnNames['team_foreign_key'] ?? 'team_id';
        $modelMorphKey = $columnNames['model_morph_key'] ?? 'model_id';

        throw_if(blank($tableNames), Exception::class, 'Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');

        throw_if($teams && blank($columnNames['team_foreign_key'] ?? null), Exception::class, 'Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');

        Schema::create($tableNames['permissions'], function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->string('group_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('can_be_removed')->default(true);
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $teamForeignKey): void {
            $table->bigIncrements('id');

            if ($teams || config('permission.testing')) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger($teamForeignKey)->nullable();
                $table->index($teamForeignKey, 'roles_team_foreign_key_index');
            }

            $table->string('name');
            $table->string('guard_name');
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('can_be_removed')->default(true);
            $table->timestamps();

            if ($teams || config('permission.testing')) {
                $table->unique([$teamForeignKey, 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $modelMorphKey, $teamForeignKey, $pivotPermission, $teams): void {
            if ($teams) {
                $table->id();
            }

            $table->unsignedBigInteger($pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($modelMorphKey);
            $table->index([$modelMorphKey, 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($teamForeignKey)->nullable();
                $table->index($teamForeignKey, 'model_has_permissions_team_foreign_key_index');

                $table->unique(
                    [$teamForeignKey, $pivotPermission, $modelMorphKey, 'model_type'],
                    'model_has_permissions_team_fk_permission_model_type_unique'
                );
            } else {
                $table->primary(
                    [$pivotPermission, $modelMorphKey, 'model_type'],
                    'model_has_permissions_permission_model_type_primary'
                );
            }
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $modelMorphKey, $teamForeignKey, $pivotRole, $teams): void {
            if ($teams) {
                $table->id();
            }

            $table->unsignedBigInteger($pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($modelMorphKey);
            $table->index([$modelMorphKey, 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($teamForeignKey)->nullable();
                $table->index($teamForeignKey, 'model_has_roles_team_foreign_key_index');

                $table->unique(
                    [$teamForeignKey, $pivotRole, $modelMorphKey, 'model_type'],
                    'model_has_roles_team_foreign_key_role_model_type_unique'
                );
            } else {
                $table->primary(
                    [$pivotRole, $modelMorphKey, 'model_type'],
                    'model_has_roles_role_model_type_primary'
                );
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission): void {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);

            $table->foreign($pivotPermission)
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign($pivotRole)
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        $cacheStore = config('permission.cache.store');
        $cacheKey = config('permission.cache.key');

        resolve(Factory::class)
            ->store($cacheStore !== 'default' ? (is_string($cacheStore) ? $cacheStore : null) : null)
            ->forget(is_string($cacheKey) ? $cacheKey : 'spatie.permission.cache');
    }

    public function down(): void
    {
        /** @var array<string, string> $tableNames */
        $tableNames = config('permission.table_names');

        throw_if(blank($tableNames), Exception::class, 'Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
};
