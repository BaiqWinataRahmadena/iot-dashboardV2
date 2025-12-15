@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
    <style>
    /* CSS untuk layout 2 kolom */
    .master-detail-container {
        display: flex;
        gap: 20px;
    }
    
    /* Kolom Kiri (Master) */
    .master-pane {
        flex: 0 0 350px;
        background: var(--bg-secondary);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    /* Kolom Kanan (Detail) */
    .detail-pane {
        flex: 1;
        min-width: 0;
    }
    
    /* -- Style Tabel Kiri -- */
    .pelanggan-table {
        width: 100%;
        border-collapse: collapse;
    }
    .pelanggan-table th,
    .pelanggan-table td {
        padding: 10px 15px;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }
    .pelanggan-table th {
        background-color: var(--bg-primary);
        font-size: 0.9rem;
        color: var(--text-primary);
        opacity: 0.8;
    }
    .pelanggan-table tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .pelanggan-table tr:not(.active):hover {
        background-color: var(--row-hover-bg);
    }

    /* Style untuk baris yang aktif */
    .pelanggan-table tr.active {
        background-color: var(--row-active-bg);
        color: var(--row-active-text);
        font-weight: bold;
    }
    .pelanggan-table tr.active a {
        color: var(--row-active-text);
    }
    .pelanggan-table a {
        text-decoration: none;
        color: var(--text-primary);
        display: block;
    }
    .pelanggan-table .col-nomor {
        width: 1%;
        text-align: center;
        padding-left: 10px;
        padding-right: 10px;
    }
    
    /* -- Style Kolom Kanan -- */
    .detail-placeholder {
        padding: 40px;
        text-align: center;
        background: var(--bg-secondary);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        color: var(--text-primary);
        opacity: 0.7;
        font-size: 1.1rem;
        border: 1px solid var(--border-color);
    }
    .detail-container {
        background: var(--bg-secondary);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
    }
    #map { height: 300px; width: 100%; border-radius: 8px; margin-bottom: 20px; }
    
    /* Style tabel riwayat */
    .riwayat-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    .riwayat-table th, .riwayat-table td { 
        padding: 8px; 
        border: 1px solid var(--border-color);
        text-align: left; 
    }
    .riwayat-table th { background-color: var(--bg-primary); }
    .status-akurat { color: #28a745; font-weight: bold; }
    .status-tidak-akurat { color: #dc3545; font-weight: bold; }

    /* Mode gelap untuk peta */
    .dark-mode .leaflet-tile-pane {
        filter: invert(1) hue-rotate(180deg) brightness(0.9) contrast(0.9);
    }
    .dark-mode .leaflet-popup-content-wrapper,
    .dark-mode .leaflet-popup-tip {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    /* Style form pencarian */
    .search-form {
        padding: 10px 15px;
        border-bottom: 1px solid var(--border-color);
    }
    .search-form input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        background-color: var(--bg-primary);
        color: var(--text-primary);
        box-sizing: border-box;
    }

    /* Style untuk info pelanggan */
    .info-grid {
        display: grid;
        grid-template-columns: 150px 1fr; /* Label dan Value */
        gap: 8px;
    }
    .info-grid strong {
        color: var(--text-primary);
        opacity: 0.7;
    }
    .status-aktif { color: #28a745; font-weight: bold; }
    .status-tidak-aktif { color: #dc3545; font-weight: bold; }
</style>

    <h1>Daftar Pelanggan</h1>
    <div class="master-detail-container">

        <div class="master-pane">
            
            <div style="padding: 15px; border-bottom: 1px solid var(--border-color); display: flex; gap: 10px;">
                <form class="search-form" id="searchForm" style="flex-grow: 1; padding: 0; border: none;">
                    <input type="text" name="search" placeholder="Cari nama pelanggan..." id="searchInput">
                </form>
                <button id="btnTambahPelanggan" style="background: #007bff; color: white; border: none; padding: 0 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    + Tambah
                </button>
            </div>

            <table class="pelanggan-table">
                <thead>
                    <tr>
                        <th class="col-nomor">No.</th> 
                        <th>Nama</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody id="pelangganTableBody"> 
                    @forelse ($pelangganList as $pelanggan)
                        <tr class="{{ ($selectedPelanggan && $selectedPelanggan->id_pelanggan == $pelanggan->id_pelanggan) ? 'active' : '' }}">
                            
                            <td class="col-nomor">{{ $loop->iteration }}</td>

                            <td>
                                <a href="{{ route('pelanggan.index', ['selected' => $pelanggan->id_pelanggan]) }}">
                                    {{ $pelanggan->nama }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('pelanggan.index', ['selected' => $pelanggan->id_pelanggan]) }}">
                                    {{ $pelanggan->alamat_rumah }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center;">Tidak ada data pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="detail-pane">
            @if ($selectedPelanggan)
                
                <div class="detail-container">
                    <h3>Detail Pelanggan: {{ $selectedPelanggan->nama }}</h3>
                    <div class="info-grid">
                        <strong>ID Pelanggan:</strong>
                        <span>{{ $selectedPelanggan->id_pelanggan }}</span>
                        
                        <strong>Status:</strong>
                        <span>
                            @if($selectedPelanggan->status == 'aktif')
                                <span class="status-aktif">Aktif</span>
                            @else
                                <span class="status-tidak-aktif">Tidak Aktif</span>
                            @endif
                        </span>

                        <strong>No. KTP:</strong>
                        <span>{{ $selectedPelanggan->no_ktp }}</span>

                        <strong>Telepon:</strong>
                        <span>{{ $selectedPelanggan->telepon }}</span>
                        
                        <strong>Pekerjaan:</strong>
                        <span>{{ $selectedPelanggan->pekerjaan }}</span>

                        <strong>Alamat:</strong>
                        <span>{{ $selectedPelanggan->alamat_rumah }}</span>

                        <strong>Keterangan:</strong>
                        <span>{{ $selectedPelanggan->keterangan ?? '-' }}</span>
                    </div>
                </div>

                <div class="detail-container">
                    <h3>Peta Lokasi</h3>
                    <div id="map"></div>
                </div>

                <div class="detail-container">
                    <h3>Riwayat Pemasangan Meteran</h3>
                    <table class="riwayat-table">
                        <thead>
                            <tr>
                                <th>Tanggal Pasang</th>
                                <th>Tipe Meteran</th>
                                <th>Diameter</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatPemasangan as $riwayat)
                                <tr>
                                    <td>{{ $riwayat->tanggal_pasang }}</td>
                                    <td>{{ $riwayat->tipe_meteran }}</td>
                                    <td>{{ $riwayat->diameter_meteran }}</td>
                                    <td>{{ $riwayat->keterangan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center;">Tidak ada riwayat pemasangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="detail-container">
                    <h3>Laporan Hasil Baca</h3>
                    <table class="riwayat-table">
                        <thead>
                            <tr>
                                <th>Tgl Baca</th>
                                <th>Petugas</th>
                                <th>Kondisi</th>
                                <th>Akurasi</th>
                                <th>Vol. Awal (1)</th>
                                <th>Vol. Akhir (1)</th>
                                <th>Vol. Alat (1)</th>
                                <th>Deviasi % (1)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporanHasilBaca as $laporan)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($laporan->tanggal_baca)->format('d-M-Y H:i') }}</td>
                                    <td>{{ $laporan->nama_petugas }}</td>
                                    <td>{{ $laporan->kondisi_perangkat }}</td>
                                    <td>
                                        @if($laporan->tingkat_akurasi == 'Tidak Akurat')
                                            <span class="status-tidak-akurat">Tidak Akurat</span>
                                        @else
                                            <span class="status-akurat">Akurat</span>
                                        @endif
                                    </td>
                                    <td>{{ $laporan->volume_awal_1 }}</td>
                                    <td>{{ $laporan->volume_akhir_1 }}</td>
                                    <td>{{ $laporan->volume_wmt_1 }}</td>
                                    <td>{{ $laporan->deviasi_persen_1 }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center;">Belum ada laporan hasil baca.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                <script>
                    const lat = {{ $selectedPelanggan->latitude }};
                    const lon = {{ $selectedPelanggan->longitude }};
                    const map = L.map('map').setView([lat, lon], 17);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
                    const marker = L.marker([lat, lon]).addTo(map);
                    marker.bindPopup(`<b>{{ $selectedPelanggan->nama }}</b><br>{{ $selectedPelanggan->alamat_rumah }}`).openPopup();
                </script>

            @else
                <div class="detail-placeholder">
                    <p>Silakan pilih pelanggan di tabel sebelah kiri untuk melihat detailnya.</p>
                </div>
            @endif
        </div>
    </div>

    <div id="modalPelanggan" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Pelanggan Baru</h2>
            <span class="close-btn">&times;</span>
        </div>
        
        <form id="formTambahPelanggan">
            @csrf 
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div class="form-group">
                <label>Nomor KTP</label>
                <input type="text" name="no_ktp" placeholder="16 digit NIK" required>
            </div>
            <div class="form-group">
                <label>Alamat Rumah</label>
                <textarea name="alamat_rumah" rows="3" placeholder="Alamat lengkap..." required></textarea>
            </div>
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="telepon" placeholder="08..." required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="btnBatalPelanggan">Batal</button>
                <button type="submit" class="btn-submit">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

    <script>
        (function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            const tableBody = document.getElementById('pelangganTableBody');
            const rows = tableBody.getElementsByTagName('tr');

            if(searchForm) {
                searchForm.addEventListener('submit', function(e) { e.preventDefault(); });
            }

            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    const filter = searchInput.value.toLowerCase();
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const namaTd = row.getElementsByTagName('td')[1]; // Kolom "Nama"
                        const alamatTd = row.getElementsByTagName('td')[2]; // Kolom "Alamat"

                        if (namaTd || alamatTd) {
                            const nama = namaTd.textContent || namaTd.innerText;
                            const alamat = alamatTd.textContent || alamatTd.innerText;
                            
                            // Modifikasi: Cari berdasarkan nama ATAU alamat
                            if (nama.toLowerCase().indexOf(filter) > -1 || alamat.toLowerCase().indexOf(filter) > -1) {
                                row.style.display = ""; 
                            } else {
                                row.style.display = "none"; 
                            }
                        }
                    }
                });
            }
        })();

        const modal = document.getElementById("modalPelanggan");
        const btn = document.getElementById("btnTambahPelanggan");
        const span = document.getElementsByClassName("close-btn")[0];
        const btnBatal = document.getElementById("btnBatalPelanggan");

        // Buka Modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Tutup Modal (Tombol X, Tombol Batal, atau Klik Luar)
        function tutupModal() { modal.style.display = "none"; }
        
        span.onclick = tutupModal;
        btnBatal.onclick = tutupModal;
        window.onclick = function(event) {
            if (event.target == modal) {
                tutupModal();
            }
        }

        // LOGIKA SIMPAN PELANGGAN (AJAX)
        document.getElementById('formTambahPelanggan').addEventListener('submit', function(e){
            e.preventDefault(); // Cegah reload halaman

            const formData = new FormData(this); // Ambil semua data form
            const submitBtn = this.querySelector('.btn-submit');
            submitBtn.innerText = "Menyimpan..."; // Ubah teks tombol

            fetch("{{ route('pelanggan.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    // CSRF Token tidak perlu manual di header jika sudah ada input @csrf di form,
                    // tapi untuk fetch kadang lebih aman pakai header 'X-CSRF-TOKEN'
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                }
            })
            .then(response => {
                if (!response.ok) { throw new Error('Gagal menyimpan data'); }
                return response.json();
            })
            .then(data => {
                alert(data.message); // Tampilkan pesan sukses
                tutupModal();
                location.reload(); // Refresh halaman untuk melihat data baru
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Terjadi kesalahan! Pastikan semua data terisi.");
                submitBtn.innerText = "Simpan Data"; // Kembalikan teks tombol
            });
        });
    </script>
@endsection