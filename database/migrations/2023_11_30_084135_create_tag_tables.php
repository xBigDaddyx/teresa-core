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
            $table->string('tagable_id');
            $table->string('tagable_type');
            $table->string('tag');
            $table->string('type');
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->string('attributable_id')->nullable();
            $table->string('attributable_type')->nullable();
            $table->unsignedBigInteger('company_id');
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
