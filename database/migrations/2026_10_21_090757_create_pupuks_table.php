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
        Schema::create('pupuks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis'); // Contoh: Organik, Kimia, Hayati
            $table->integer('stok');
            $table->string('satuan'); // Contoh: kg, karung, liter
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupuks');
    }
};
