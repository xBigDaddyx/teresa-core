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
        Schema::connection('teresa_purchase')->create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->unsignedBigInteger('orderable_id');
            $table->string("orderable_type");
            $table->foreignId('supplier_id');
            $table->foreignId('category_id');
            $table->timestamp('delivery_date');
            $table->string('payment_term');
            $table->boolean('included_tax');
            $table->string('tax_type')->nullable();
            $table->string('capex_code')->nullable();
            $table->text('comment')->nullable();
            $table->softDeletes();
            $table->foreignId('processed_by')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('teresa_purchase')->dropIfExists('orders');
    }
};
