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
        Schema::connection('teresa_purchase')->create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_number')->nullable();
            $table->string('part_number')->nullable();
            $table->string('name');
            $table->json('specification')->nullable();
            $table->foreignId('product_category_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->text('remark')->nullable();
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
        Schema::connection('teresa_purchase')->dropIfExists('products');
    }
};
