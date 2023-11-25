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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number');
            $table->foreignId('department_id')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->text('note')->nullable();
            $table->string('customer')->nullable();
            $table->string('contract_no')->nullable();
            $table->boolean('is_submited')->default(false);
            $table->boolean('is_processed')->default(false);
            $table->foreignId('company_id');
            $table->softDeletes();
            $table->foreignId('processed_by')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
