@extends('layouts.app')

@section('title', 'Home - PDAM Monitor')

@section('content')

<style>
    /* Reset Box Sizing agar padding tidak membuat elemen melebar keluar */
    * {
        box-sizing: border-box;
    }

    /* Container Utama */
    .dashboard-header {
        margin-bottom: 30px;
    }
    .dashboard-header h1 {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: var(--text-primary);
    }
    .dashboard-header p {
        color: var(--text-primary);
        opacity: 0.8;
        font-size: 1rem;
    }

    /* Statistik Container (Responsif) */
    .stats-container {
        display: flex;
        flex-wrap: wrap; /* Biarkan kartu turun ke bawah jika sempit */
        gap: 20px;
        margin-top: 20px;
        width: 100%; /* Pastikan container penuh */
    }

    /* Kartu Statistik */
    .stats-card {
        background: var(--bg-secondary);
        color: var(--text-primary);
        padding: 25px;
        border-radius: 12px; /* Lebih bulat modern */
        box-shadow: 0 4px 6px rgba(0,0,0,0.05); /* Bayangan lebih halus */
        flex: 1 1 300px; /* Basis lebar 300px, tapi bisa membesar/mengecil */
        min-width: 250px; /* Jangan lebih kecil dari ini */
        border: 1px solid var(--border-color);
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        
        /* PENTING: Mencegah overflow */
        box-sizing: border-box; 
        width: 100%;
    }

    /* Efek Hover Sedikit */
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    .stats-card h2 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        opacity: 0.9;
        margin-bottom: 10px;
    }

    .stats-card p {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .stats-card small {
        margin-top: 15px;
        display: block;
        font-size: 0.85rem;
        opacity: 0.6;
    }

    /* Warna Angka */
    .stats-card .text-blue { color: #3b82f6; }
    .stats-card .text-green { color: #10b981; }
    .stats-card .text-red { color: #ef4444; }

    /* Media Query Khusus HP */
    @media (max-width: 768px) {
        .dashboard-header h1 {
            font-size: 1.5rem;
        }
        .stats-container {
            flex-direction: column; /* Paksa tumpuk vertikal di HP */
        }
        .stats-card {
            width: 100%; /* Penuhi lebar layar */
            flex: none; /* Matikan flex basis */
        }
    }
</style>

<div class="dashboard-header">
    <h1>Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}! 👋</h1>
    <p>Ini adalah halaman utama dasbor aplikasi monitoring PDAM Anda.</p>
</div>

<div class="stats-container">
    <!-- Kartu 1: Total Pelanggan -->
    <div class="stats-card">
        <div>
            <h2>Total Pelanggan</h2>
            <p class="text-blue">150</p>
        </div>
        <small>Terdaftar dalam sistem</small>
    </div>

    <!-- Kartu 2: Pengecekan Hari Ini -->
    <div class="stats-card">
        <div>
            <h2>Pengecekan Hari Ini</h2>
            <p class="text-green">10</p>
        </div>
        <small>Data masuk terbaru</small>
    </div>

    <!-- Kartu 3: Meteran Error -->
    <div class="stats-card">
        <div>
            <h2>Meteran Error</h2>
            <p class="text-red">1</p>
        </div>
        <small>Perlu tindakan segera</small>
    </div>
</div>

@endsection