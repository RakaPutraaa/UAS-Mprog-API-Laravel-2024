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
        Schema::create('lokasi_villa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_villa');
            $table->string('lokasi_villa', 100);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_villa')->references('id')->on('list_villa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_villa');
    }
};
