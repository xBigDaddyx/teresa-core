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

        Schema::create('harmony_approval_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historable_id')->nullable();
            $table->string('historable_type')->nullable();
            $table->string('subject');
            $table->string('action');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harmony_approval_histories');
    }
};
