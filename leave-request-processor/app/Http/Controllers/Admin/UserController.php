<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index(Request $request)
    {
        // 1. Ambil data dengan relasi Divisi
        $users = User::with('division')
            ->orderBy('name')
            ->paginate(15);
        
        // 2. Ambil daftar divisi untuk filter
        $divisions = Division::all();

        // TODO: Implementasi Filter dan Sortir (Tahap lanjutan)

        return view('admin.users.index', compact('users', 'divisions'));
    }

    /**
     * Menampilkan formulir untuk membuat pengguna baru.
     */
    public function create()
    {
        // Role yang tersedia untuk pembuatan user:
        $roles = ['Karyawan', 'Ketua Divisi', 'HRD', 'Admin'];

        // Ambil daftar divisi (jika Admin ingin langsung menetapkan divisi)
        $divisions = Division::all();

        return view('admin.users.create', compact('roles', 'divisions'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input sesuai kebutuhan proyek
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'role' => ['required', 'string', Rule::in(['Karyawan', 'Ketua Divisi', 'HRD', 'Admin'])],
            'initial_leave_quota' => 'nullable|integer|min:0',
            'division_id' => 'nullable|exists:divisions,id', // Cek apakah ID Divisi ada
        ]);

        // 2. Tentukan kuota cuti awal
        $initialQuota = $validated['initial_leave_quota'] ?? 12;

        // 3. Buat pengguna
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'division_id' => $validated['division_id'],
            'initial_leave_quota' => $initialQuota,
            'current_leave_quota' => $initialQuota, // Kuota awal sama dengan kuota saat ini
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    // TODO: Implementasi show(), edit(), update(), dan destroy()
}