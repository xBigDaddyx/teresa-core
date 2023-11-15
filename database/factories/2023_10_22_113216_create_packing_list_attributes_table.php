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
        Schema::connection('teresa_box')->create('packing_list_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('packing_list_id')->nullable();
            $table->string('doc_id')->index()->nullable();
            $table->string('tag');
            $table->string('size');
            $table->string('color');
            $table->integer('quantity')->default(0);
            $table->string('type')->default('RATIO');
            $table->unsignedBigInteger('company_id')->nullable();
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
        Schema::connection('teresa_box')->dropIfExists('packing_list_attributes');
    }
};
