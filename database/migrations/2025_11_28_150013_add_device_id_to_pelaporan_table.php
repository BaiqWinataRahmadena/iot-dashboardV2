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
    Schema::table('pelaporan_hasil_baca', function (Blueprint $table) {
        // Menambah kolom device_id setelah kolom id_pelanggan
        $table->string('device_id', 50)->nullable()->after('id_pelanggan');
    });
}

public function down()
{
    Schema::table('pelaporan_hasil_baca', function (Blueprint $table) {
        $table->dropColumn('device_id');
    });
}
};
