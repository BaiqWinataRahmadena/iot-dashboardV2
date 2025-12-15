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
    Schema::create('riwayat_pemasangan', function (Blueprint $table) {
        $table->id('id_riwayat');
        $table->unsignedBigInteger('id_pelanggan');
        $table->string('tipe_meteran')->nullable();
        $table->decimal('diameter_meteran', 5, 2)->nullable();
        $table->date('tanggal_pasang')->nullable();
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pemasangan');
    }
};
