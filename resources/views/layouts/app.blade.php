<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PDAM Monitor')</title>

    <!-- CSS Leaflet untuk Peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

    <style>
        /* --- 1. DEFINISI CSS VARIABLES (LIGHT & DARK MODE) --- */
        :root {
            --bg-primary: #f4f7f6;
            --bg-secondary: #ffffff;
            --bg-sidebar: #2c3e50;
            --text-primary: #333333;
            --text-sidebar: #ecf0f1;
            --text-link: #007bff;
            --border-color: #ddd;
            --sidebar-hover: #34495e;
            --navbar-height: 60px;
            --sidebar-width: 240px;
        }

        .dark-mode {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --bg-sidebar: #111827;
            --text-primary: #e2e8f0;
            --text-sidebar: #d1d5db;
            --text-link: #60a5fa;
            --border-color: #4a5568;
            --sidebar-hover: #374151;
        }

        body, html {
            margin: 0; padding: 0;
            font-family: Arial, sans-serif;
            font-size: 14px;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
            overflow-x: hidden; /* Cegah scroll horizontal */
        }

        /* --- 2. TOP NAVBAR (Sekarang Memanjang Penuh) --- */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0; /* Ubah dari 240px ke 0 agar penuh */
            right: 0;
            height: var(--navbar-height);
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between; /* Space between untuk hamburger & auth */
            align-items: center;
            padding: 0 20px;
            z-index: 9999; /* Di atas sidebar */
            transition: left 0.3s;
        }

        /* Container Kiri Navbar (Hamburger & Judul Mobile) */
        .nav-left { display: flex; align-items: center; gap: 15px; }
        
        .hamburger-btn {
            display: none; /* Sembunyi di desktop */
            background: none; border: none;
            color: var(--text-primary);
            font-size: 24px; cursor: pointer;
            padding: 0;
        }

        .nav-brand-mobile {
            display: none; /* Judul hanya muncul di HP */
            font-weight: bold; font-size: 1.2rem;
            color: var(--text-primary);
        }

        .auth-links { display: flex; align-items: center; gap: 15px; }
        .auth-links a { color: var(--text-link); text-decoration: none; font-weight: bold; }
        .auth-links button { background: none; border: none; color: var(--text-link); font-weight: bold; cursor: pointer; font-size: 14px; }

        /* --- 3. SIDEBAR --- */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0; /* Mulai dari paling atas */
            width: var(--sidebar-width);
            height: 100%;
            background-color: var(--bg-sidebar);
            color: var(--text-sidebar);
            padding-top: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            z-index: 10000; /* Paling atas */
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-header {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.2);
        }
        .sidebar-header h3 { margin: 0; font-size: 1.2rem; }

        .sidebar nav { flex-grow: 1; overflow-y: auto; padding-top: 10px; }
        .sidebar a {
            display: block; padding: 15px 20px;
            color: var(--text-sidebar); text-decoration: none; font-size: 1rem;
            border-left: 4px solid transparent;
        }
        .sidebar a:hover { background-color: var(--sidebar-hover); border-left-color: var(--text-link); }
        
        .theme-toggle {
            padding: 15px; margin: 10px; text-align: center;
            background-color: rgba(255,255,255,0.1); border-radius: 5px; cursor: pointer; border: none; color: white;
        }

        /* --- 4. KONTEN UTAMA --- */
        .main-content {
            margin-left: var(--sidebar-width); /* Default Desktop */
            margin-top: var(--navbar-height);
            padding: 30px;
            transition: margin-left 0.3s;
        }

        /* --- 5. OVERLAY (Layar Gelap saat Menu Buka di HP) --- */
        .sidebar-overlay {
            display: none;
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: 90;
            backdrop-filter: blur(2px);
            z-index: 9998;
        }

        /* --- 6. MEDIA QUERIES (RESPONSIF HP/TABLET) --- */
        @media (max-width: 768px) {
            /* Navbar */
            .top-navbar { left: 0; padding: 0 15px; }
            .hamburger-btn { display: block; } /* Munculkan hamburger */
            .nav-brand-mobile { display: block; } /* Munculkan judul */

            /* Sidebar: Sembunyikan dengan digeser ke kiri */
            .sidebar { transform: translateX(-100%); }
            
            /* Sidebar Aktif (Kelas ini ditambahkan via JS) */
            .sidebar.active { transform: translateX(0); }

            /* Konten: Lebarkan penuh */
            .main-content { margin-left: 0; padding: 15px; }

            /* Tampilkan overlay saat sidebar aktif */
            .sidebar-overlay.active { display: block; }

            /* Penyesuaian Halaman Pelanggan (Master-Detail jadi Tumpuk) */
            .master-detail-container { flex-direction: column; }
            .master-pane { flex: none; width: 100%; margin-bottom: 20px; }
            .detail-pane { width: 100%; }
            
            /* Sembunyikan Tabel di Kanan Navbar agar tidak sempit */
            .auth-links span { display: none; } /* Sembunyikan "Halo, Admin" di HP */
        }

        /* --- 7. CSS MODAL (Tetap Sama) --- */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px); }
        .modal-content { background-color: var(--bg-secondary); margin: 15% auto; padding: 20px; border: 1px solid var(--border-color); border-radius: 8px; width: 90%; max-width: 500px; position: relative; color: var(--text-primary); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px; }
        .close-btn { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 5px; background-color: var(--bg-primary); color: var(--text-primary); box-sizing: border-box; }
        .modal-footer { text-align: right; margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border-color); }
        .btn-submit { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-cancel { background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px; }
    </style>
</head>

<body class="">

    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.body.className = 'dark-mode'; 
            }
        })();
    </script>

    <!-- OVERLAY (Layar Gelap) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- TOP NAVBAR -->
    <div class="top-navbar">
        <div class="nav-left">
            <!-- Tombol Hamburger -->
            <button class="hamburger-btn" id="hamburgerBtn">
                ☰
            </button>
            <span class="nav-brand-mobile">PDAM Monitor</span>
        </div>

        <div class="auth-links">
            @auth
                <!-- Nama User (Hidden di HP) -->
                <span>Halo, <strong>{{ Auth::user()->name }}</strong></span>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>PDAM Monitor</h3>
        </div>
        <nav>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('pelanggan.index') }}">Daftar Pelanggan</a>
            <a href="{{ route('users.index') }}">Manajemen Karyawan</a>
            <a href="{{ route('pengukuran.create') }}">Input Pengukuran</a>
            <a href="#">Riwayat Pengecekan</a>
            <a href="#">Pengaturan Alat</a>
        </nav>
        
        <!-- Toggle Dark Mode -->
        <button id="theme-toggle" class="theme-toggle">Ganti Mode</button>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- JAVASCRIPT GLOBAL -->
    <script>
        (function() {
            // --- Logic Dark Mode ---
            const toggleButton = document.getElementById('theme-toggle');
            const body = document.body;

            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark-mode');
                toggleButton.innerText = 'Mode Terang';
            } else {
                body.classList.remove('dark-mode');
                toggleButton.innerText = 'Mode Gelap';
            }

            toggleButton.addEventListener('click', function() {
                body.classList.toggle('dark-mode');
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    toggleButton.innerText = 'Mode Terang';
                } else {
                    localStorage.setItem('theme', 'light');
                    toggleButton.innerText = 'Mode Gelap';
                }
            });

            // --- Logic Sidebar Responsive ---
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

            // Klik Hamburger -> Buka Sidebar
            hamburgerBtn.addEventListener('click', toggleSidebar);

            // Klik Overlay (Luar Sidebar) -> Tutup Sidebar
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            // Tutup Sidebar jika link diklik (opsional, bagus untuk SPA tapi ini reload page)
            // const sidebarLinks = sidebar.querySelectorAll('a');
            // sidebarLinks.forEach(link => {
            //     link.addEventListener('click', () => {
            //         sidebar.classList.remove('active');
            //         overlay.classList.remove('active');
            //     });
            // });

        })();
    </script>
</body>
</html>