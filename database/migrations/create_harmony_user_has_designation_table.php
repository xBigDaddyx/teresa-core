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
        $department = config('harmony-flow.column_names.department_pivot_key');
        Schema::create('harmony_user_has_designation', function (Blueprint $table) use ($department) {
            $table->id();
            $table->unsignedBigInteger('chargeable_id');
            $table->string('chargeable_type');
            $table->foreignId('designation_id');
            $table->foreignId($department);
            $table->softDeletes();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->foreignId('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harmony_user_has_designation');
    }
};
