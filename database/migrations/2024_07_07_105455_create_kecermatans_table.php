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
        Schema::create('kecermatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ujian_id");
            $table->string("a");
            $table->string("b");
            $table->string("c");
            $table->string("d")->nullable();
            $table->string("e")->nullable();
            $table->bigInteger("jumlah_soal")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecermatans');
    }
};
