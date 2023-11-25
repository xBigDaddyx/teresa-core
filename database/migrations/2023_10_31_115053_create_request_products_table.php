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
        Schema::create('request_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id');
            $table->foreignId('product_id');
            $table->string('contract_no')->nullable();
            $table->integer('quantity')->default(0);
            $table->timestamp('delivery_date');
            $table->text('remark')->nullable();
            $table->integer('stock')->default(0);
            $table->string('style_no')->nullable();
            $table->boolean('is_rejected')->default(false);
            $table->foreignId('state_id')->nullable();
            $table->foreignId('last_state_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_item');
    }
};
