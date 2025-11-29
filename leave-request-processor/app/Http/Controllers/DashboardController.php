<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Main dashboard - routes to role-specific dashboard
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminIndex();
        } elseif ($user->isHRD()) {
            return $this->hrdIndex();
        } elseif ($user->isLeader()) {
            return $this->leaderIndex();
        } else {
            return $this->employeeIndex();
        }
    }

    /**
     * Admin Dashboard - Overall statistics
     */
    public function adminIndex()
    {
        $activeEmployees = User::where('role', 'Karyawan')->count();
        $totalLeaveThisMonth = LeaveApplication::whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count();
        $pendingApprovals = LeaveApplication::where('status', 'Pending')->count();
        $totalDivisions = Division::count();
        $employeesMassEligible = User::where('role', 'Karyawan')
            ->where('current_leave_quota', '>=', 0)
            ->count();

        return view('dashboard.admin', [
            'activeEmployees' => $activeEmployees,
            'totalLeaveThisMonth' => $totalLeaveThisMonth,
            'pendingApprovals' => $pendingApprovals,
            'totalDivisions' => $totalDivisions,
            'employeesMassEligible' => $employeesMassEligible,
        ]);
    }

    /**
     * HRD Dashboard - Final approvals & reporting
     */
    public function hrdIndex()
    {
        $totalLeaveThisMonth = LeaveApplication::whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count();
        $pendingFinalApproval = LeaveApplication::where('status', 'Approved by Leader')->count();
        $employeePending = User::where('role', 'Karyawan')->count();

        return view('dashboard.hrd', [
            'totalLeaveThisMonth' => $totalLeaveThisMonth,
            'pendingFinalApproval' => $pendingFinalApproval,
            'employeePending' => $employeePending,
        ]);
    }

    /**
     * Ketua Divisi Dashboard - Leader approvals
     */
    public function leaderIndex()
    {
        $user = auth()->user();
        $division = $user->leaderOfDivision()->first();
        $totalLeaveThisMonth = 0;
        $pendingApprovals = 0;
        $employees = [];

        if ($division) {
            $employees = User::where('division_id', $division->id)->get();
            $totalLeaveThisMonth = LeaveApplication::whereIn('user_id', $employees->pluck('id'))
                ->whereMonth('start_date', now()->month)
                ->whereYear('start_date', now()->year)
                ->count();
            $pendingApprovals = LeaveApplication::whereIn('user_id', $employees->pluck('id'))
                ->where('status', 'Pending')
                ->count();
        }

        return view('dashboard.leader', [
            'division' => $division,
            'totalLeaveThisMonth' => $totalLeaveThisMonth,
            'pendingApprovals' => $pendingApprovals,
            'employeeCount' => count($employees),
        ]);
    }

    /**
     * Employee Dashboard - Personal leave quota & applications
     */
    public function employeeIndex()
    {
        $user = auth()->user();
        $totalLeaveQuota = $user->initial_leave_quota;
        $remainingQuota = $user->current_leave_quota;
        $usedQuota = $totalLeaveQuota - $remainingQuota;
        $totalApplications = LeaveApplication::where('user_id', $user->id)->count();
        $sickLeave = LeaveApplication::where('user_id', $user->id)
            ->where('leave_type', 'Cuti Sakit')
            ->count();

        return view('dashboard.employee', [
            'totalLeaveQuota' => $totalLeaveQuota,
            'remainingQuota' => $remainingQuota,
            'usedQuota' => $usedQuota,
            'totalApplications' => $totalApplications,
            'sickLeave' => $sickLeave,
        ]);
    }
}
