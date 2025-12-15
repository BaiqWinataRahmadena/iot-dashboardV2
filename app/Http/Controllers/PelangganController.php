<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Import Model agar bisa baca Database
use App\Models\Pelanggan;
use App\Models\Pelaporan;
use App\Models\RiwayatPemasangan;

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dari DATABASE SQLite.
     */
    public function index(Request $request) 
    {
        $searchQuery = $request->input('search');

        // 1. Ambil data pelanggan dari Database (Bukan Dummy lagi!)
        $query = Pelanggan::query();

        if ($searchQuery) {
            $query->where('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('alamat_rumah', 'like', '%' . $searchQuery . '%');
        }

        // Ambil hasil query
        $pelangganList = $query->get();

        // Inisialisasi data detail
        $selectedPelanggan = null;
        $riwayatPemasangan = collect();
        $laporanHasilBaca = collect();

        // 2. Logika Klik Pelanggan
        if ($request->has('selected')) {
            $selectedId = $request->input('selected');
            
            // Cari pelanggan di DB berdasarkan ID
            $selectedPelanggan = Pelanggan::where('id_pelanggan', $selectedId)->first();

            if ($selectedPelanggan) {
                // Ambil Riwayat Pemasangan dari DB
                $riwayatPemasangan = RiwayatPemasangan::where('id_pelanggan', $selectedId)
                                        ->orderByDesc('tanggal_pasang')
                                        ->get();

                // Ambil Laporan Hasil Baca dari DB
                $laporanHasilBaca = Pelaporan::where('id_pelanggan', $selectedId)
                                        ->orderByDesc('tanggal_baca')
                                        ->get();
            }
        }

        return view('pelanggan.index', [
            'pelangganList' => $pelangganList,
            'selectedPelanggan' => $selectedPelanggan,
            'riwayatPemasangan' => $riwayatPemasangan,
            'laporanHasilBaca' => $laporanHasilBaca,
            'searchQuery' => $searchQuery,
        ]);
    }
    
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_ktp' => 'required|string|max:20',
            'alamat_rumah' => 'required|string',
            'telepon' => 'required|string|max:20',
            'status' => 'required|in:aktif,tidak aktif',
            // Latitude/Longitude boleh kosong dulu (nullable)
        ]);

        // 2. Simpan ke SQLite
        // Kita gunakan $request->all() atau $validated
        // Default koordinat kita set 0 jika tidak diisi, atau biarkan null
        $pelanggan = new Pelanggan();
        $pelanggan->nama = $request->nama;
        $pelanggan->no_ktp = $request->no_ktp;
        $pelanggan->alamat_rumah = $request->alamat_rumah;
        $pelanggan->telepon = $request->telepon;
        $pelanggan->status = $request->status;
        $pelanggan->save();

        // 3. Kembalikan respon JSON (agar JS bisa membacanya)
        return response()->json(['message' => 'Pelanggan berhasil ditambahkan!']);
    }
    
    // Fungsi 'show' bisa dihapus jika tidak dipakai, 
    // atau disesuaikan seperti di atas.
}