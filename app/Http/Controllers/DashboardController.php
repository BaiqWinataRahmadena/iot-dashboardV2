<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard/home.
     */
    public function index()
    {
        // Nanti Anda bisa mengirim data statistik ke sini
        // $totalPelanggan = 150;
        // $pengecekanHariIni = 10;
        // return view('home', compact('totalPelanggan', 'pengecekanHariIni'));

        return view('home');
    }
}