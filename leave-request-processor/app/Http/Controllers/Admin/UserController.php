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
        $query = User::with('division');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by division
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $users = $query->paginate(15);
        $divisions = Division::all();

        return view('admin.users.index', compact('users', 'divisions'));
    }

    /**
     * Menampilkan formulir untuk membuat pengguna baru.
     */
    public function create()
    {
        $roles = ['Karyawan', 'Ketua Divisi', 'HRD', 'Admin'];
        $divisions = Division::all();

        return view('admin.users.create', compact('roles', 'divisions'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'role' => ['required', 'string', Rule::in(['Karyawan', 'Ketua Divisi', 'HRD', 'Admin'])],
            'initial_leave_quota' => 'nullable|integer|min:0',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $initialQuota = $validated['initial_leave_quota'] ?? 12;

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'division_id' => $validated['division_id'],
            'initial_leave_quota' => $initialQuota,
            'current_leave_quota' => $initialQuota,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengguna.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan form untuk edit pengguna.
     */
    public function edit(User $user)
    {
        $roles = ['Karyawan', 'Ketua Divisi', 'HRD', 'Admin'];
        $divisions = Division::all();

        return view('admin.users.edit', compact('user', 'roles', 'divisions'));
    }

    /**
     * Update pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|max:255',
            'role' => ['required', 'string', Rule::in(['Karyawan', 'Ketua Divisi', 'HRD', 'Admin'])],
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        // Cegah penghapusan jika user adalah leader
        if ($user->leaderOfDivision()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus user yang menjadi ketua divisi.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}