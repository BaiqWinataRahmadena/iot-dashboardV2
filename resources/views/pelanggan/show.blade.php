@extends('layouts.app')

@section('title', 'Detail Pelanggan: ' . $pelanggan->nama)

@section('content')
<style>
    /* Reset & Base */
    * { box-sizing: border-box; }

    /* Tambahan: Pastikan viewport responsif (tambahkan ini di <head> layouts.app jika belum ada: <meta name="viewport" content="width=device-width, initial-scale=1.0">) */

    /* Tombol Kembali */
    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: var(--text-link);
        text-decoration: none;
        font-weight: bold;
        padding: 8px 12px;
        border-radius: 5px;
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        font-size: 0.9rem;
    }
    .back-link:hover { text-decoration: none; background-color: var(--row-hover-bg); }

    /* Container Kartu */
    .container {
        background-color: var(--bg-secondary);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        
        /* Responsif: Pastikan container fit layar HP, tapi jangan gunakan overflow: hidden yang menghalangi scroll tabel */
        width: 100%;
        max-width: 100%;
        /* Hapus overflow: hidden di sini agar scroll tabel bekerja; gunakan hanya di elemen spesifik jika perlu */
    }

    h1, h2 { margin-top: 0; color: var(--text-primary); }
    h1 { font-size: 1.5rem; margin-bottom: 20px; }
    h2 { font-size: 1.25rem; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }

    /* Grid Info Pelanggan */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    .info-item { display: flex; flex-direction: column; }
    .info-item strong { color: var(--text-primary); opacity: 0.7; margin-bottom: 5px; font-size: 0.9rem; }
    .info-item span { font-size: 1.1rem; font-weight: 500; }

    /* Peta */
    #map {
        height: 350px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        z-index: 1;
    }

    /* --- CSS TABEL SCROLLABLE --- */
    .table-responsive {
        width: 100%;
        display: block;
        overflow-x: auto; /* Scroll horizontal wajib aktif */
        -webkit-overflow-scrolling: touch; /* Smooth scroll di iOS */
        margin-bottom: 10px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        max-width: 100%; /* Pastikan wrapper tidak melebar */
        /* Tambahan: Bayangan untuk indikasi scroll */
        box-shadow: inset -10px 0 10px -10px rgba(0,0,0,0.1);
        /* Pastikan wrapper ini yang menangani scroll, bukan container */
        position: relative; /* Jika perlu, untuk kontrol posisi */
    }
    
    table { 
        width: 100%; 
        border-collapse: collapse; 
        /* Kurangi min-width agar scroll aktif lebih cepat di HP kecil */
        min-width: 600px; /* Lebih kecil dari sebelumnya; sesuaikan jika tabel masih lebar */
        max-width: 100%; /* Pastikan tabel tidak melebar melebihi wrapper */
    }
    
    th, td { 
        padding: 12px 15px; 
        border-bottom: 1px solid var(--border-color); 
        text-align: left; 
        white-space: nowrap; /* Jangan wrap agar kolom tetap rapi dan scroll aktif */
    }
    
    th { background-color: var(--bg-primary); font-size: 0.9rem; color: var(--text-primary); opacity: 0.8; }
    td { font-size: 0.95rem; }
    tr:last-child td { border-bottom: none; }

    /* Status Badges */
    .status-akurat { color: #28a745; font-weight: bold; background: rgba(40, 167, 69, 0.1); padding: 4px 8px; border-radius: 4px; }
    .status-tidak-akurat { color: #dc3545; font-weight: bold; background: rgba(220, 53, 69, 0.1); padding: 4px 8px; border-radius: 4px; }

    /* Media Query HP (max-width: 768px) */
    @media (max-width: 768px) {
        .container { 
            padding: 15px; /* Padding lebih kecil */
            width: 100%;
            /* Hapus overflow: hidden di sini juga, agar scroll tabel bekerja */
        }
        h1 { font-size: 1.3rem; }
        h2 { font-size: 1.1rem; }
        
        .info-grid { 
            grid-template-columns: 1fr; 
            gap: 15px; 
        }
        
        .back-link { 
            font-size: 0.8rem; 
            padding: 6px 10px; 
        }
        
        #map { 
            height: 250px; 
        }
        
        /* Tabel di HP: Pastikan scroll horizontal bekerja dengan baik */
        .table-responsive {
            box-shadow: inset -15px 0 15px -15px rgba(0,0,0,0.2); /* Bayangan lebih tebal untuk indikasi */
            overflow-x: auto; /* Pastikan aktif */
            -webkit-overflow-scrolling: touch;
            /* Tambahan: Jika perlu, tambahkan padding kecil untuk memastikan tabel tidak keluar */
            padding: 0; /* Reset padding jika ada */
        }
        
        table {
            min-width: 600px; /* Tetap kecil agar scroll aktif di HP sempit */
            max-width: none; /* Biarkan tabel melebar dalam wrapper, tapi scroll akan menangani */
        }
        
        th, td {
            padding: 8px 10px; /* Padding lebih kecil */
            font-size: 0.85rem;
            /* Pastikan white-space: nowrap tetap aktif untuk memaksa scroll */
        }
    }
</style>

    <!-- Konten HTML tetap sama -->
    <a href="{{ route('pelanggan.index') }}" class="back-link">&larr; Kembali</a>

    <!-- DETAIL UTAMA -->
    <div class="container">
        <h1>{{ $pelanggan->nama }}</h1>
        
        <div class="info-grid">
            <div class="info-item">
                <strong>Alamat Lengkap:</strong>
                <span style="white-space: normal;">{{ $pelanggan->alamat_rumah }}</span>
            </div>
            <div class="info-item">
                <strong>No. Telepon:</strong>
                <span>{{ $pelanggan->telepon ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>No. KTP:</strong>
                <span>{{ $pelanggan->no_ktp ?? '-' }}</span>
            </div>
            <div class="info-item">
                <strong>Koordinat:</strong>
                <span style="font-family: monospace; font-size: 0.9rem;">
                    {{ $pelanggan->latitude ?? '0' }}, {{ $pelanggan->longitude ?? '0' }}
                </span>
            </div>
        </div>
    </div>

    <!-- PETA LOKASI -->
    <div class="container">
        <h2>Lokasi Pemasangan Alat</h2>
        <div id="map"></div>
    </div>

    <!-- RIWAYAT PENGECEKAN -->
    <div class="container">
        <h2>Riwayat Pengecekan Akurasi</h2>
        <p style="font-size: 0.85rem; color: var(--text-primary); opacity: 0.6; margin-bottom: 10px; font-style: italic;">
            <span style="display: inline-block; transform: rotate(90deg);">⇵</span> Geser tabel ke kiri/kanan untuk melihat data lengkap
        </p>
        
        <div class="table-responsive">
            <table>
                <!-- Isi tabel tetap sama -->
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Petugas</th>
                        <th>Flowrate (L/m)</th>
                        <th>Meter Awal</th>
                        <th>Meter Akhir</th>
                        <th>Selisih (L)</th>
                        <th>Vol. Alat (L)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggan->pelaporanHasilBaca as $cek) 
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($cek->tanggal_baca)->format('d/m/y H:i') }}</td>
                            <td>{{ $cek->nama_petugas }}</td>
                            <td>-</td>
                            <td>{{ $cek->volume_awal_1 }}</td>
                            <td>{{ $cek->volume_akhir_1 }}</td>
                            
                            @php
                                $selisih = ($cek->volume_akhir_1 ?? 0) - ($cek->volume_awal_1 ?? 0);
                            @endphp
                            <td>{{ number_format($selisih, 2) }}</td>
                            
                            <td>{{ $cek->volume_wmt_1 }}</td>
                            <td>
                                @if(str_contains($cek->tingkat_akurasi, 'Tidak Akurat'))
                                    <span class="status-tidak-akurat">Tidak Akurat</span>
                                @else
                                    <span class="status-akurat">Akurat</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 20px; opacity: 0.6;">
                                Belum ada riwayat pengecekan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Leaflet JS tetap sama -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
     
    <script>
        const lat = Number('{{ $pelanggan->latitude }}') || -8.5833;
        const lon = Number('{{ $pelanggan->longitude }}') || 116.1167;

        const map = L.map('map').setView([lat, lon], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        if (lat !== 0 && lon !== 0) {
            const marker = L.marker([lat, lon]).addTo(map);
            marker.bindPopup(
                `<b>{{ $pelanggan->nama }}</b><br>{{ $pelanggan->alamat_rumah }}`
            ).openPopup();
        }
    </script>
@endsection
