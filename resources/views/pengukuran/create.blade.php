@extends('layouts.app')

@section('title', 'Input Pengukuran')

@section('content')
<style>
    /* Reset Box Sizing */
    * { box-sizing: border-box; }

    /* Container Kartu */
    .measurement-card {
        background: var(--bg-secondary);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        max-width: 600px;
        width: 100%;
        margin: 0 auto;
        text-align: center;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .pulse-container { margin: 30px 0; }
    
    .status-badge {
        background: #ffc107; color: #000; padding: 5px 15px;
        border-radius: 20px; font-weight: bold; font-size: 0.9rem;
        display: inline-block; margin-top: 10px;
    }

    /* Animasi Radar */
    .radar {
        width: 80px; height: 80px; background-color: #007bff;
        border-radius: 50%; position: relative; margin: 0 auto;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(0, 123, 255, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); }
    }

    /* Form Elements */
    label { display: block; margin-bottom: 8px; font-size: 0.95rem; }
    
    select, input[type="text"] {
        width: 100%; padding: 12px; margin-bottom: 0; border-radius: 8px;
        border: 1px solid var(--border-color); background: var(--bg-primary); 
        color: var(--text-primary); font-size: 1rem;
        -webkit-appearance: none; 
    }

    .form-group { margin-bottom: 20px; text-align: left; position: relative; } /* Position relative untuk dropdown */
    .helper-text { display: block; font-size: 0.85rem; color: #666; margin-top: 5px; }

    /* --- DROPDOWN PENCARIAN KUSTOM --- */
    .custom-dropdown {
        position: relative;
    }
    .dropdown-input {
        cursor: pointer;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 15px top 50%;
        background-size: 12px auto;
    }
    .dropdown-list {
        display: none; /* Sembunyi default */
        position: absolute;
        top: 100%; left: 0; right: 0;
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin-top: 5px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 100;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .dropdown-list.show { display: block; }
    .dropdown-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }
    .dropdown-item:last-child { border-bottom: none; }
    .dropdown-item:hover { background-color: var(--row-hover-bg); }
    .no-result { padding: 10px; color: #999; font-style: italic; text-align: center; }

    /* Tombol */
    .btn-start {
        background: #28a745; color: white; padding: 12px 30px; border: none;
        border-radius: 8px; font-size: 1.1rem; cursor: pointer; width: 100%;
        font-weight: bold; transition: background-color 0.2s;
    }
    .btn-start:hover { background-color: #218838; }

    .btn-cancel {
        background: #dc3545; color: white; padding: 10px 20px; border: none;
        border-radius: 8px; cursor: pointer; margin-top: 20px; width: 100%;
        font-size: 1rem;
    }

    /* Judul & Teks */
    h1 { font-size: 1.8rem; margin-bottom: 25px; color: var(--text-primary); }
    h2 { font-size: 1.5rem; margin-bottom: 10px; color: var(--text-primary); }
    h3 { font-size: 1.3rem; margin: 10px 0; color: #007bff; word-break: break-word; }
    p { font-size: 1rem; line-height: 1.5; color: var(--text-primary); opacity: 0.8; }

    @media (max-width: 768px) {
        .container { padding: 15px; }
        .measurement-card { padding: 20px; }
        h1 { font-size: 1.5rem; }
    }
</style>

<div class="container">
    <h1 style="text-align: center;">Input Pengukuran Baru</h1>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <div class="measurement-card">
        
        @if($pendingMeasurement)
            <!-- MODE MENUNGGU -->
            <div class="pulse-container">
                <div class="radar"></div>
            </div>
            <h2>Menunggu Data IoT...</h2>
            <p>Sistem sedang mendengarkan data pengukuran untuk:</p>
            <h3>{{ $pendingMeasurement->pelanggan->nama }}</h3>
            <p style="margin-top: 15px; font-size: 0.9rem;">
                Silakan lakukan pengukuran pada alat sekarang.<br>
                Sistem akan otomatis menyimpan data jika masuk dalam 5 menit.
            </p>
            <div style="margin-top: 15px;">
                <span class="status-badge">Waktu Mulai: {{ \Carbon\Carbon::parse($pendingMeasurement->tanggal_baca)->format('H:i:s') }}</span>
            </div>
            <form action="{{ route('pengukuran.cancel', $pendingMeasurement->id_pelaporan) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn-cancel">Batalkan Sesi Ini</button>
            </form>
            <script>setTimeout(function(){ location.reload(); }, 5000);</script>

        @else
            <!-- MODE INPUT FORM -->
            <p style="margin-bottom: 25px;">Pilih pelanggan yang akan diukur meteran airnya.</p>

            <form action="{{ route('pengukuran.store') }}" method="POST">
                @csrf
                
                <!-- DROPDOWN PENCARIAN PELANGGAN -->
                <div class="form-group custom-dropdown">
                    <label><strong>Pilih Pelanggan:</strong></label>
                    
                    <!-- Input Teks untuk Pencarian & Tampilan -->
                    <input type="text" id="pelangganSearch" class="dropdown-input" placeholder="-- Cari Nama Pelanggan --" autocomplete="off">
                    
                    <!-- Input Hidden untuk menyimpan ID yang sebenarnya dikirim ke server -->
                    <input type="hidden" name="id_pelanggan" id="pelangganId" required>

                    <!-- Daftar Pilihan (Muncul saat diklik) -->
                    <div class="dropdown-list" id="pelangganList">
                        @foreach($pelanggan as $p)
                            <div class="dropdown-item" 
                                 data-id="{{ $p->id_pelanggan }}" 
                                 data-name="{{ $p->nama }} - {{ Str::limit($p->alamat_rumah, 20) }}">
                                <strong>{{ $p->nama }}</strong><br>
                                <small style="color: #666;">{{ Str::limit($p->alamat_rumah, 30) }}</small>
                            </div>
                        @endforeach
                        <div class="no-result" id="noResult" style="display: none;">Pelanggan tidak ditemukan</div>
                    </div>
                </div>

                <div class="form-group">
                    <label><strong>Pilih Alat Ukur:</strong></label>
                    <select name="device_id" required>
                        <option value="">-- Pilih ID Alat --</option>
                        <option value="ALAT_01">Alat 01</option>
                        <option value="ALAT_02">Alat 02</option>
                        <option value="ALAT_03">Alat 03</option>
                    </select>
                    <small class="helper-text">Pastikan sesuai dengan stiker di alat IoT Anda.</small>
                </div>

                <div class="form-group">
                    <label><strong>Petugas:</strong></label>
                    <input type="text" value="{{ Auth::user()?->name ?? Session::get('user')?->name ?? 'Belum Login' }}" readonly style="background-color: #e9ecef; cursor: not-allowed; opacity: 0.7;">
                    <small class="helper-text">*Terisi otomatis sesuai akun login</small>
                </div>

                <button type="submit" class="btn-start">Mulai Sesi Pengukuran</button>
            </form>
        @endif
    </div>
</div>

<!-- SCRIPT UNTUK DROPDOWN SEARCH -->
<script>
    const searchInput = document.getElementById('pelangganSearch');
    const hiddenInput = document.getElementById('pelangganId');
    const dropdownList = document.getElementById('pelangganList');
    const items = document.querySelectorAll('.dropdown-item');
    const noResult = document.getElementById('noResult');

    if (searchInput) {
        // 1. Tampilkan list saat input diklik
        searchInput.addEventListener('click', () => {
            dropdownList.classList.add('show');
        });

        // 2. Filter list saat mengetik
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            let hasResult = false;

            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                if (text.includes(filter)) {
                    item.style.display = 'block';
                    hasResult = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Tampilkan pesan jika tidak ada hasil
            if (!hasResult) {
                noResult.style.display = 'block';
            } else {
                noResult.style.display = 'none';
            }
            
            dropdownList.classList.add('show'); // Pastikan list tetap terbuka saat mengetik
        });

        // 3. Pilih item saat diklik
        items.forEach(item => {
            item.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                // Isi nilai ke input
                searchInput.value = name;
                hiddenInput.value = id;

                // Sembunyikan list
                dropdownList.classList.remove('show');
            });
        });

        // 4. Tutup list jika klik di luar area dropdown
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdownList.contains(e.target)) {
                dropdownList.classList.remove('show');
            }
        });
    }
</script>
@endsection
