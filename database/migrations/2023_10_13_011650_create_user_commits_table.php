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
        Schema::create('user_commits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('ujian_id');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->datetime('due');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_commits');
    }
};
