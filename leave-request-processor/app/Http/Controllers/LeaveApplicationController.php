<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveApplicationController extends Controller
{
    /**
     * Menampilkan daftar pengajuan cuti user.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = LeaveApplication::where('user_id', $user->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('leaves.index', compact('leaves'));
    }

    /**
     * Menampilkan formulir untuk membuat pengajuan cuti baru.
     */
    public function create()
    {
        $user = auth()->user();
        return view('leaves.create', compact('user'));
    }

    /**
     * Menyimpan pengajuan cuti baru.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'leave_type' => 'required|in:Cuti Tahunan,Cuti Sakit',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'address_during_leave' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        // Hitung total hari kerja
        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $totalDays = 0;
        $current = clone $startDate;

        while ($current <= $endDate) {
            // Hitung hanya hari kerja (Senin-Jumat)
            if ($current->format('N') < 6) {
                $totalDays++;
            }
            $current->modify('+1 day');
        }

        // Validasi kuota cuti
        if ($user->current_leave_quota < $totalDays) {
            return back()->with('error', 'Kuota cuti Anda tidak cukup.');
        }

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        LeaveApplication::create([
            'user_id' => $user->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'attachment' => $attachmentPath,
            'address_during_leave' => $validated['address_during_leave'],
            'emergency_contact' => $validated['emergency_contact'],
            'status' => 'Pending',
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat.');
    }

    /**
     * Menampilkan detail pengajuan cuti.
     */
    public function show(LeaveApplication $leave)
    {
        // Cegah user lain melihat detail pengajuan orang lain
        if (auth()->id() !== $leave->user_id && !auth()->user()->isAdmin() && !auth()->user()->isHRD() && !auth()->user()->isLeader()) {
            abort(403);
        }

        return view('leaves.show', compact('leave'));
    }

    /**
     * Menampilkan form edit pengajuan cuti (hanya untuk status Pending).
     */
    public function edit(LeaveApplication $leave)
    {
        if (auth()->id() !== $leave->user_id) {
            abort(403);
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Hanya pengajuan dengan status Pending yang dapat diedit.');
        }

        return view('leaves.edit', compact('leave'));
    }

    /**
     * Update pengajuan cuti.
     */
    public function update(Request $request, LeaveApplication $leave)
    {
        if (auth()->id() !== $leave->user_id) {
            abort(403);
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Hanya pengajuan dengan status Pending yang dapat diedit.');
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:Cuti Tahunan,Cuti Sakit',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'address_during_leave' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        // Recalculate total days
        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $totalDays = 0;
        $current = clone $startDate;

        while ($current <= $endDate) {
            if ($current->format('N') < 6) {
                $totalDays++;
            }
            $current->modify('+1 day');
        }

        // Handle file upload
        if ($request->hasFile('attachment')) {
            if ($leave->attachment) {
                \Storage::disk('public')->delete($leave->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $leave->update([
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'attachment' => $validated['attachment'] ?? $leave->attachment,
            'address_during_leave' => $validated['address_during_leave'],
            'emergency_contact' => $validated['emergency_contact'],
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Membatalkan pengajuan cuti (hanya jika Pending).
     */
    public function destroy(LeaveApplication $leave)
    {
        if (auth()->id() !== $leave->user_id) {
            abort(403);
        }

        if ($leave->status !== 'Pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Hanya pengajuan dengan status Pending yang dapat dibatalkan.');
        }

        $leave->delete();

        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }
}
