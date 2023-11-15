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
        Schema::connection('teresa_box')->create('inspection_finals', function (Blueprint $table) {
            $table->id();
            $table->string('carton_box_id')->index();
            $table->string('inspector');
            $table->boolean('is_finish')->default('false');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('teresa_box')->dropIfExists('inspection_finals');
    }
};
