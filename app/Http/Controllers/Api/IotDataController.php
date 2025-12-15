<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelaporan;
use Carbon\Carbon;

class IotDataController extends Controller
{
    /**
     * Endpoint ini akan dipanggil oleh Python Script saat ada data MQTT masuk.
     */
    public function receiveData(Request $request)
    {
        // 1. Tangkap Device ID dari kiriman IoT/Python
        $deviceIdFromIot = $request->input('device_id');

        if (!$deviceIdFromIot) {
            return response()->json(['status'=>'error', 'message'=>'Device ID wajib dikirim!'], 400);
        }

        // 2. Cari sesi Menunggu KHUSUS untuk alat ini
        $laporan = Pelaporan::where('status_pengukuran', 'Menunggu')
                            ->where('device_id', $deviceIdFromIot) // <--- KUNCI PENGAMAN
                            ->latest()
                            ->first();

        if (!$laporan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada sesi aktif untuk alat: ' . $deviceIdFromIot
            ], 200);
        }

        // 3. Update Data (Sama seperti sebelumnya)
        $laporan->volume_awal_1 = $request->input('volume_awal', 0);
        $laporan->volume_akhir_1 = $request->input('volume_akhir', 0);
        $laporan->volume_wmt_1 = $request->input('volume_alat', 0);
        
        $laporan->status_pengukuran = 'Selesai';
        $laporan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data tersimpan untuk Pelanggan ' . $laporan->id_pelanggan . ' via ' . $deviceIdFromIot
        ]);
    }
}