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
        Schema::create('soal_ujian_multiples', function (Blueprint $table) {
            $table->id();
            $table->foreignid('ujian_id');
            $table->longtext('soal');
            $table->longtext('a');
            $table->longtext('b');
            $table->longtext('c');
            $table->longtext('d')->nullable();
            $table->longtext('e')->nullable();
            $table->text('jawaban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_ujian_multiples');
    }
};
