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
        Schema::table('list_villa', function (Blueprint $table) {
            $table->string('lokasi_villa', 255)->after('harga_villa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_villa', function (Blueprint $table) {
            $table->dropColumn('lokasi_villa');
        });
    }
};