<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pelaporan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengukuranController extends Controller
{
    /**
     * Halaman Input: Petugas memilih pelanggan dan alat
     */
    public function create()
    {
        // 1. Ambil data pelanggan aktif
        $pelanggan = Pelanggan::where('status', 'aktif')->get();
        
        // 2. Cek apakah USER INI sedang melakukan pengukuran?
        // Kita filter berdasarkan 'nama_petugas' agar Petugas A tidak melihat sesi Petugas B
        $user = Auth::user();
        $pendingMeasurement = null;

        if ($user) {
            $pendingMeasurement = Pelaporan::with('pelanggan')
                ->where('status_pengukuran', 'Menunggu')
                ->where('nama_petugas', $user->name) // <-- Hanya cek punya user ini
                ->latest('tanggal_baca')
                ->first();
        }

        return view('pengukuran.create', compact('pelanggan', 'pendingMeasurement'));
    }

    /**
     * Logika Mulai: Simpan slot kosong dengan Device ID
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'device_id'    => 'required|string', // Wajib ada ID Alat
        ]);

        // 2. Cek Login (Security)
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Sesi habis, silakan login kembali.');
        }

        // 3. LOGIKA PENGAMAN (DEVICE LOCK)
        // Cek apakah ALAT INI sedang sibuk?
        // Kita cari di DB: Ada gak status 'Menunggu' untuk device_id ini?
        $alatSedangDipakai = Pelaporan::where('status_pengukuran', 'Menunggu')
                                      ->where('device_id', $request->device_id)
                                      ->first();

        if ($alatSedangDipakai) {
            // Jika alat sibuk, tolak!
            return back()->with('error', 'Alat ini (' . $request->device_id . ') sedang dipakai sesi lain! Tunggu selesai atau gunakan alat lain.');
        }

        // 4. Cek apakah USER INI sudah punya sesi aktif?
        // (Opsional: Agar 1 orang tidak menangani 2 alat sekaligus)
        $userSedangMengukur = Pelaporan::where('status_pengukuran', 'Menunggu')
                                       ->where('nama_petugas', $user->name)
                                       ->first();
        
        if ($userSedangMengukur) {
            return back()->with('error', 'Anda masih memiliki sesi pengukuran aktif. Selesaikan dulu.');
        }

        // 5. SIMPAN DATA (Buat Slot Menunggu)
        Pelaporan::create([
            'id_pelanggan'      => $request->id_pelanggan,
            'device_id'         => $request->device_id, // Simpan ID Alat
            'nama_petugas'      => $user->name,         // Otomatis dari Auth
            'tanggal_baca'      => Carbon::now(),
            'kondisi_perangkat' => 'Baik',              // Default
            'status_pengukuran' => 'Menunggu',          // Status Awal
            // Volume dll biarkan NULL (menunggu IoT)
        ]);

        return redirect()->route('pengukuran.create')->with('success', 'Sesi dimulai untuk ' . $request->device_id . '. Menunggu data IoT...');
    }

    /**
     * Batalkan Pengukuran
     */
    public function cancel($id)
    {
        $laporan = Pelaporan::findOrFail($id);
        
        // Pastikan yang menghapus adalah pemilik sesi (Opsional, demi keamanan)
        if ($laporan->nama_petugas !== Auth::user()->name) {
            return back()->with('error', 'Anda tidak berhak membatalkan sesi orang lain.');
        }

        $laporan->delete(); // Hapus data
        
        return back()->with('success', 'Pengukuran dibatalkan.');
    }
}