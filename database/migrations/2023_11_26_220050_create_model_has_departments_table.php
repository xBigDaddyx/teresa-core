<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('harmony-flow.table_names');
        Schema::create('model_has_departments', function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('department_id');

            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_departments_model_id_model_type_index');

            $table->foreign('department_id')
                ->references('id')
                ->on($tableNames['departments'])
                ->onDelete('cascade');

            $table->primary(
                ['department_id', 'model_id', 'model_type'],
                'model_has_departments_role_model_type_primary'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_departments');
    }
};
