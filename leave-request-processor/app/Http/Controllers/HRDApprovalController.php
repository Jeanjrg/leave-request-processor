<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use Illuminate\Http\Request;

class HRDApprovalController extends Controller
{
    /**
     * Display list of leave applications awaiting HRD final approval.
     */
    public function index(Request $request)
    {
        $query = LeaveApplication::where('status', 'Approved by Leader')
            ->with('user', 'user.division');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->month);
        }

        $leaves = $query->orderBy('leader_approved_at', 'asc')->paginate(10);

        return view('hrd.approvals.index', compact('leaves'));
    }

    /**
     * Show leave application detail for HRD approval.
     */
    public function show(LeaveApplication $leave)
    {
        $leave->load('user', 'user.division');
        return view('hrd.approvals.show', compact('leave'));
    }

    /**
     * Approve leave application (final approval by HRD).
     */
    public function approve(Request $request, LeaveApplication $leave)
    {
        $validated = $request->validate([
            'hrd_note' => 'nullable|string|max:500',
        ]);

        if (!in_array($leave->status, ['Approved by Leader'])) {
            return back()->with('error', 'Pengajuan cuti ini tidak bisa disetujui pada tahap ini.');
        }

        // Deduct leave quota
        $user = $leave->user;
        $user->current_leave_quota -= $leave->total_days;
        $user->save();

        $leave->update([
            'status' => 'Approved',
            'hrd_approved_at' => now(),
            'hrd_note' => $validated['hrd_note'],
        ]);

        return redirect()->route('hrd.approvals.index')
            ->with('success', 'Pengajuan cuti berhasil disetujui dan kuota cuti telah diperbarui.');
    }

    /**
     * Reject leave application (final rejection by HRD).
     */
    public function reject(Request $request, LeaveApplication $leave)
    {
        $validated = $request->validate([
            'hrd_note' => 'required|string|min:10|max:500',
        ]);

        if (!in_array($leave->status, ['Approved by Leader'])) {
            return back()->with('error', 'Pengajuan cuti ini tidak bisa ditolak pada tahap ini.');
        }

        $leave->update([
            'status' => 'Rejected',
            'hrd_note' => $validated['hrd_note'],
        ]);

        return redirect()->route('hrd.approvals.index')
            ->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
