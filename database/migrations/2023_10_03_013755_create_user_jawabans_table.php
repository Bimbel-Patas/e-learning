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
        Schema::create('user_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('multiple_id')->nullable();
            $table->foreignId('essay_id')->nullable();
            $table->foreignId('user_id');
            $table->string('user_jawaban')->nullable();
            $table->double('nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_jawabans');
    }
};
