@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
<style>
    /* Reset Box Sizing */
    * { box-sizing: border-box; }

    /* Container Utama */
    .user-container {
        background: var(--bg-secondary);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden; 
        border: 1px solid var(--border-color);
        width: 100%;
    }
    
    /* Header (Judul + Search + Tombol) */
    .user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
        flex-wrap: wrap; /* Agar turun ke bawah di HP */
        gap: 15px;
    }
    .user-header h1 { 
        margin: 0; 
        font-size: 1.5rem; 
        color: var(--text-primary);
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
        flex-grow: 1;
        justify-content: flex-end;
    }

    .user-search { width: 300px; max-width: 100%; }
    .user-search input {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        background-color: var(--bg-primary);
        color: var(--text-primary);
    }

    /* Tombol Tambah */
    #btnTambahUser {
        background: #28a745; 
        color: white; 
        border: none; 
        padding: 10px 20px; 
        border-radius: 5px; 
        cursor: pointer; 
        font-weight: bold;
        white-space: nowrap; /* Teks jangan turun baris */
    }

    /* --- STYLE TABEL (DEFAULT DESKTOP) --- */
    .user-table {
        width: 100%;
        border-collapse: collapse;
    }
    .user-table th,
    .user-table td {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }
    .user-table th {
        background-color: var(--bg-primary);
        font-size: 0.9rem;
        color: var(--text-primary);
        opacity: 0.8;
    }
    .user-table tr:last-child td { border-bottom: none; }
    
    /* Baris User (Hover & Expand) */
    .user-table tr.user-row { cursor: pointer; transition: background-color 0.2s; }
    .user-table tr.user-row:hover { background-color: var(--row-hover-bg); }
    .user-table tr.user-row.expanded {
        background-color: var(--row-active-bg);
        color: var(--row-active-text);
    }
    .user-table tr.user-row.expanded a { color: var(--row-active-text); }

    /* Baris Detail (Hidden) */
    .detail-row { display: none; }
    .detail-row td { padding: 0; }
    .detail-content {
        background-color: var(--bg-primary);
        padding: 20px 40px;
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 10px;
        border-bottom: 1px solid var(--border-color);
    }
    .detail-content strong { opacity: 0.7; }
    .password-warning { color: #fd7e14; font-style: italic; }

    /* Status Badges */
    .status-verified { color: #28a745; font-weight: bold; }
    .status-pending { color: #fd7e14; font-weight: bold; }
    .action-buttons a { color: var(--text-link); text-decoration: none; margin-right: 10px; }
    .action-buttons a:hover { text-decoration: underline; }

    /* --- MEDIA QUERY (RESPONSIF HP) --- */
    @media (max-width: 768px) {
        /* Header tumpuk ke bawah */
        .user-header { flex-direction: column; align-items: stretch; }
        .header-actions { flex-direction: column; }
        .user-search { width: 100%; }
        #btnTambahUser { width: 100%; }

        /* --- TABEL JADI KARTU (Teknik Responsive Table) --- */
        .user-table, .user-table tbody, .user-table tr, .user-table td {
            display: block; width: 100%;
        }
        .user-table thead { display: none; } /* Sembunyikan Header Tabel Asli */

        /* PERBAIKAN: Pastikan Detail Row tetap Sembunyi by Default di HP */
        .user-table tr.detail-row {
            display: none; 
        }

        .user-table tr.user-row {
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            background: var(--bg-secondary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .user-table td {
            padding: 5px 0;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
        }

        /* Tambahkan Label di Kiri (Pseudo-element) */
        .user-table td::before {
            content: attr(data-label); /* Ambil teks dari atribut data-label */
            font-weight: bold;
            text-align: left;
            opacity: 0.7;
            margin-right: 10px;
        }

        /* Detail Row di HP */
        .detail-content {
            grid-template-columns: 1fr; /* Tumpuk vertikal */
            padding: 15px;
        }
    }
</style>

<div class="user-container">
    
    <!-- HEADER -->
    <div class="user-header">
        <h1>Manajemen Karyawan (Users)</h1>
        
        <div class="header-actions">
            <div class="user-search">
                <form id="searchForm">
                    <input type="text" placeholder="Cari nama atau email..." id="searchInput" value="{{ $searchQuery ?? '' }}">
                </form>
            </div>
            <button id="btnTambahUser">
                + Karyawan Baru
            </button>
        </div>
    </div>

    <!-- TABEL -->
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Password</th>
                <th>Tgl Dibuat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            @forelse ($userList as $user)
                <!-- Tambahkan data-label untuk tampilan HP -->
                <tr class="user-row" data-target="detail-{{ $user->id }}">
                    <td data-label="ID">{{ $user->id }}</td>
                    <td data-label="Nama"><strong>{{ $user->name }}</strong></td>
                    <td data-label="Email">{{ $user->email }}</td>
                    <td data-label="Password">********</td>
                    <td data-label="Tgl Dibuat">{{ \Carbon\Carbon::parse($user->created_at)->format('d-M-Y') }}</td>
                    <td data-label="Status">
                        @if ($user->email_verified_at)
                            <span class="status-verified">Terverifikasi</span>
                        @else
                            <span class="status-pending">Pending</span>
                        @endif
                    </td>
                    <td data-label="Aksi" class="action-buttons">
                        <a href="#">Edit</a>
                        <a href="#" style="color: #dc3545;">Hapus</a>
                    </td>
                </tr>
                
                <!-- DETAIL ROW (EXPANDABLE) -->
                <tr class="detail-row" id="detail-{{ $user->id }}">
                    <td colspan="7">
                        <div class="detail-content">
                            <strong>Password:</strong>
                            <span class="password-warning">(Password ter-enkripsi dan TIDAK boleh ditampilkan)</span>
                            
                            <strong>Remember Token:</strong>
                            <span style="word-break: break-all;">{{ $user->remember_token ?? 'N/A' }}</span>
                            
                            <strong>Email Verified At:</strong>
                            <span>
                                {{ $user->email_verified_at ? \Carbon\Carbon::parse($user->email_verified_at)->format('d-M-Y H:i:s') : 'N/A' }}
                            </span>
                            
                            <strong>Updated At:</strong>
                            <span>
                                {{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d-M-Y H:i:s') : 'N/A' }}
                            </span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data karyawan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH USER (Sama seperti sebelumnya) -->
<div id="modalUser" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Karyawan Baru</h2>
            <span class="close-btn">&times;</span>
        </div>
        
        <form id="formTambahUser">
            @csrf 
            <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" name="name" placeholder="Nama Lengkap" required>
            </div>
            <div class="form-group">
                <label>Email Login</label>
                <input type="email" name="email" placeholder="email@pdam.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
            </div>
            <div class="form-group">
                <label>Jabatan / Role</label>
                <select name="role">
                    <option>Petugas Lapangan</option>
                    <option>Admin Kantor</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="btnBatalUser">Batal</button>
                <button type="submit" class="btn-submit">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT (Sama seperti sebelumnya) -->
<script>
(function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const tableBody = document.getElementById('userTableBody');
    const rows = tableBody.getElementsByTagName('tr');

    if(searchForm) {
        searchForm.addEventListener('submit', function(e) { e.preventDefault(); });
    }
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                if (row.classList.contains('user-row')) {
                    const nameTd = row.querySelector('td[data-label="Nama"]');
                    const emailTd = row.querySelector('td[data-label="Email"]');
                    if (nameTd || emailTd) {
                        const name = (nameTd) ? nameTd.textContent : '';
                        const email = (emailTd) ? emailTd.textContent : '';
                        
                        if (name.toLowerCase().indexOf(filter) > -1 || email.toLowerCase().indexOf(filter) > -1) {
                            row.style.display = ""; 
                        } else {
                            row.style.display = "none";
                            const detailRow = document.getElementById(row.dataset.target);
                            if (detailRow) detailRow.style.display = "none";
                        }
                    }
                }
            }
        });
    }

    // EXPANDABLE ROW LOGIC
    tableBody.addEventListener('click', function(e) {
        const clickedRow = e.target.closest('.user-row');
        if (!clickedRow || e.target.closest('a')) return;

        const targetId = clickedRow.dataset.target;
        const detailRow = document.getElementById(targetId);
        if (!detailRow) return;

        // Cek display secara eksplisit
        const isAlreadyOpen = detailRow.style.display === 'block' || detailRow.style.display === 'table-row';

        // Tutup semua
        tableBody.querySelectorAll('.detail-row').forEach(row => row.style.display = 'none');
        tableBody.querySelectorAll('.user-row').forEach(row => row.classList.remove('expanded'));

        if (!isAlreadyOpen) {
            // Di HP kita pakai 'block', di Desktop 'table-row'
            // Tapi karena struktur CSS HP sudah ubah semua jadi block, pakai display block aman
            if (window.innerWidth <= 768) {
                detailRow.style.display = 'block';
            } else {
                detailRow.style.display = 'table-row';
            }
            clickedRow.classList.add('expanded');
        }
    });

    // MODAL LOGIC
    const modalUser = document.getElementById("modalUser");
    const btnUser = document.getElementById("btnTambahUser");
    const btnBatalUser = document.getElementById("btnBatalUser");

    btnUser.onclick = function() { modalUser.style.display = "block"; }
    function tutupModalUser() { modalUser.style.display = "none"; }
    modalUser.querySelector('.close-btn').onclick = tutupModalUser;
    btnBatalUser.onclick = tutupModalUser;
    window.onclick = function(event) { if (event.target == modalUser) tutupModalUser(); }

    // AJAX SIMPAN USER
    document.getElementById('formTambahUser').addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector('.btn-submit');
        submitBtn.innerText = "Menyimpan...";

        fetch("{{ route('users.store') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Gagal menyimpan');
            return data;
        })
        .then(data => {
            alert(data.message);
            tutupModalUser();
            location.reload();
        })
        .catch(error => {
            alert(error.message); 
            submitBtn.innerText = "Simpan Data";
        });
    });
})();
</script>
@endsection
