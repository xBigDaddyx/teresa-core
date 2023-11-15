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
        Schema::connection('teresa_box')->create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('doc_id')->index()->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('attributable_id')->index()->nullable();
            $table->string('attributable_type')->nullable();
            $table->string('taggable_id')->index();
            $table->string('taggable_type');
            $table->string('tag');
            $table->string('type')->default('RATIO');
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
        Schema::connection('teresa_box')->dropIfExists('tags');
    }
};
