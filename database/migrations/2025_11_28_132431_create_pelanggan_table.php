<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pelanggan', function (Blueprint $table) {
        // Kita pakai nama kolom sesuai database lama Anda
        $table->id('id_pelanggan'); // Primary Key
        $table->string('nama');
        $table->string('no_ktp', 20)->nullable();
        $table->text('alamat_rumah')->nullable();
        $table->string('telepon', 20)->nullable();
        $table->string('pekerjaan')->nullable();
        $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
        $table->text('keterangan')->nullable();
        
        // Tambahan untuk Peta (Wajib ada agar fitur map jalan)
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        
        // SQLite butuh timestamps agar fitur Eloquent lancar
        $table->timestamps(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
