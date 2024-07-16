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
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignid('kelas_mapel_id');
            $table->string('name');
            $table->bigInteger('isHidden')->default(0);
            $table->string('tipe');
            $table->datetime('due');
            $table->bigInteger('time');
            $table->bigInteger('jumlah_kolom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
