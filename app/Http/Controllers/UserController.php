<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // <-- Import Model User
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar karyawan (Users) dari DATABASE SQLite.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');

        // 1. Mulai Query ke Database
        $query = User::query();

        // 2. Filter jika ada pencarian
        if ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $searchQuery . '%');
        }

        // 3. Ambil data (Urutkan terbaru dulu)
        $userList = $query->orderByDesc('created_at')->get();

        return view('users.index', [
            'userList' => $userList,
            'searchQuery' => $searchQuery
        ]);
    }
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Email harus unik
            'password' => 'required|min:6|confirmed', // 'confirmed' berarti harus cocok dengan field 'password_confirmation'
            // 'role' => 'required' (Jika nanti tabel users punya kolom role)
        ]);

        // 2. Simpan User
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'email_verified_at' => now(), // Anggap langsung verified
        ]);

        return response()->json(['message' => 'Karyawan berhasil ditambahkan!']);
    }
}