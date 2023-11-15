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
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->foreignId('approval_flow_id')->nullable();
            $table->unsignedBigInteger('approvable_id');
            $table->string('approvable_type');
            $table->string('last_status')->nullable();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->string('action');
            $table->foreignId('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};
