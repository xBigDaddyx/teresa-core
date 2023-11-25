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
        $pivotFlow = config('harmony-flow.column_names.flow_pivot_key');
        $pivotDepartment = config('harmony-flow.column_names.department_pivot_key');
        Schema::create('harmony_approvals', function (Blueprint $table) use ($pivotFlow, $pivotDepartment) {
            $table->id();
            $table->string('type');
            $table->foreignId($pivotDepartment);
            $table->foreignId($pivotFlow);
            $table->unsignedBigInteger('approvable_id');
            $table->string('approvable_type');
            $table->unsignedBigInteger('chargeable_id');
            $table->string('chargeable_type');
            $table->string('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->string('is_rejected')->default(false);
            $table->timestamp('rejected_at')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->string('last_status')->nullable();
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
        Schema::dropIfExists('harmony_approvals');
    }
};