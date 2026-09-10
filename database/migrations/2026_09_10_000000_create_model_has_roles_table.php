<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        if (Schema::hasTable($tableNames['model_has_roles'])) {
            return;
        }

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                $table->primary(
                    [$columnNames['team_foreign_key'], $columnNames['model_morph_key'], 'model_type', 'role_id'],
                    'model_has_roles_role_model_type_primary'
                );
            } else {
                $table->primary(
                    [$columnNames['model_morph_key'], 'model_type', 'role_id'],
                    'model_has_roles_role_model_type_primary'
                );
            }
        });

        app('cache')->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        Schema::drop($tableNames['model_has_roles']);
    }
};
