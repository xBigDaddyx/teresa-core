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
        Schema::connection('teresa_box')->create('packing_lists', function (Blueprint $table) {
            $table->id();
            $table->string('doc_id')->index()->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('type')->default('SOLID');
            $table->string('po');
            $table->string('style_no')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('batch')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('teresa_box')->dropIfExists('packing_lists');
    }
};
