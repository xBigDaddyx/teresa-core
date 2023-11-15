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
        Schema::connection('teresa_box')->create('carton_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('doc_id')->index()->nullable();
            $table->string('box_code');
            $table->unsignedBigInteger('packing_list_id')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('carton_number')->default(0);
            $table->integer('quantity')->default(0);
            $table->string('type')->default('SOLID');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('locked_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('teresa_box')->dropIfExists('carton_boxes');
    }
};
