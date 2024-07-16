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
        Schema::create('user_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignid('tugas_id');
            $table->foreignid('user_id');
            $table->string('status')->default('Belum Mengerjakan');
            $table->BigInteger('nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tugas');
    }
};
