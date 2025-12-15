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
    Schema::create('pelaporan_hasil_baca', function (Blueprint $table) {
        $table->id('id_pelaporan'); // Primary Key
        
        // Foreign Key ke pelanggan
        $table->unsignedBigInteger('id_pelanggan');
        $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');

        $table->string('nama_petugas')->nullable();
        $table->dateTime('tanggal_baca')->useCurrent();
        $table->string('kondisi_perangkat')->nullable();
        $table->string('lokasi_pengukuran')->nullable();
        
        // Data Teknis (Boleh null karena diisi belakangan via MQTT)
        $table->float('volume_awal_1')->nullable();
        $table->float('volume_akhir_1')->nullable();
        $table->float('volume_wmt_1')->nullable(); // Volume Alat
        $table->float('deviasi_persen_1')->nullable();
        $table->string('tingkat_akurasi')->nullable();

        // PENTING: Status untuk logika "Menunggu"
        $table->string('status_pengukuran')->default('Menunggu'); 

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaporan_hasil_baca');
    }
};
