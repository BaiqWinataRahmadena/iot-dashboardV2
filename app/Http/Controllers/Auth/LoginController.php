<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ManagesDummyUsers; // <-- Gunakan Trait
use Illuminate\Support\Facades\Session; // <-- Gunakan Session

class LoginController extends Controller
{
    use ManagesDummyUsers; // <-- Impor data dummy

    /**
     * Tampilkan halaman form login
     */
    public function showLoginForm()
    {
        // Jika sudah login, lempar ke home
        if (Session::has('user')) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Proses data login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $users = $this->getDummyUsers();

        // Cari user berdasarkan email
        $user = $users->firstWhere('email', $request->email);

        // !! PERINGATAN: Cek password tidak aman, HANYA untuk dummy !!
        if ($user && $user->password === $request->password) {
            // Sukses! Simpan data user ke Session
            Session::put('user', $user);
            return redirect()->route('home');
        }

        // Gagal, kembali ke login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Proses Logout
     */
    public function logout()
    {
        Session::forget('user'); // Hapus data user dari session
        return redirect()->route('login');
    }
}