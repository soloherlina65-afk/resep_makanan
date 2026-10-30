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
    Schema::create('bahans', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel recipes (jika resep dihapus, bahan ikut terhapus otomatis)
        $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
        $table->string('nama_bahan'); // Contoh: "2 siung bawang putih" atau "1 sendok teh garam"
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};
