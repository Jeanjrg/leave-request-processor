<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderApprovalController extends Controller
{
    /**
     * Display list of pending leave applications for leader's division.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $division = $user->leaderOfDivision()->first();

        if (!$division) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda bukan ketua divisi.');
        }

        // Get all leave applications from users in this division
        $query = LeaveApplication::whereHas('user', function ($q) use ($division) {
            $q->where('division_id', $division->id);
        })->with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('leader.approvals.index', compact('leaves', 'division'));
    }

    /**
     * Show leave application detail for approval.
     */
    public function show(LeaveApplication $leave)
    {
        $user = auth()->user();
        $division = $user->leaderOfDivision()->first();

        // Verify ownership
        if (!$division || $leave->user->division_id !== $division->id) {
            abort(403);
        }

        return view('leader.approvals.show', compact('leave'));
    }

    /**
     * Approve leave application.
     */
    public function approve(Request $request, LeaveApplication $leave)
    {
        $user = auth()->user();
        $division = $user->leaderOfDivision()->first();

        // Verify ownership
        if (!$division || $leave->user->division_id !== $division->id) {
            abort(403);
        }

        if ($leave->status !== 'Pending') {
            return back()->with('error', 'Pengajuan cuti ini tidak bisa disetujui.');
        }

        $leave->update([
            'status' => 'Approved by Leader',
            'leader_approved_at' => now(),
        ]);

        return redirect()->route('leader.approvals.index')
            ->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    /**
     * Reject leave application.
     */
    public function reject(Request $request, LeaveApplication $leave)
    {
        $validated = $request->validate([
            'leader_rejection_note' => 'required|string|min:10|max:500',
        ]);

        $user = auth()->user();
        $division = $user->leaderOfDivision()->first();

        // Verify ownership
        if (!$division || $leave->user->division_id !== $division->id) {
            abort(403);
        }

        if ($leave->status !== 'Pending') {
            return back()->with('error', 'Pengajuan cuti ini tidak bisa ditolak.');
        }

        $leave->update([
            'status' => 'Rejected',
            'leader_rejection_note' => $validated['leader_rejection_note'],
        ]);

        return redirect()->route('leader.approvals.index')
            ->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
