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
        Schema::create('user_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id');
            $table->foreignId('user_id');
            $table->string('status');
            $table->bigInteger('nilai')->nullable();
            $table->bigInteger('jumlah_kolom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ujians');
    }
};
