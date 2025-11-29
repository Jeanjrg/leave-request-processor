<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    /**
     * Menampilkan daftar semua divisi.
     */
    public function index(Request $request)
    {
        $query = Division::with('leader');

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Sort
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $divisions = $query->paginate(10);

        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Menampilkan formulir untuk membuat divisi baru.
     */
    public function create()
    {
        $leaders = User::where('role', 'Ketua Divisi')->get();
        return view('admin.divisions.create', compact('leaders'));
    }

    /**
     * Menyimpan divisi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
        ]);

        Division::create($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail divisi dengan anggota.
     */
    public function show(Division $division)
    {
        $division->load('leader');
        $members = User::where('division_id', $division->id)->get();
        return view('admin.divisions.show', compact('division', 'members'));
    }

    /**
     * Menampilkan form untuk edit divisi.
     */
    public function edit(Division $division)
    {
        $leaders = User::where('role', 'Ketua Divisi')->get();
        return view('admin.divisions.edit', compact('division', 'leaders'));
    }

    /**
     * Update divisi di database.
     */
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('divisions', 'name')->ignore($division->id)],
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
        ]);

        $division->update($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Menghapus divisi.
     */
    public function destroy(Division $division)
    {
        // Cegah penghapusan jika divisi memiliki anggota
        if (User::where('division_id', $division->id)->exists()) {
            return redirect()->route('admin.divisions.index')
                ->with('error', 'Tidak dapat menghapus divisi yang memiliki anggota.');
        }

        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}
