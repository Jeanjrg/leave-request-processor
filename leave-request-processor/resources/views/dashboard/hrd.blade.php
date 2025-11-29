@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Dashboard HRD</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Pengajuan Bulan Ini</div>
                <div class="text-4xl font-bold text-indigo-600 mt-2">{{ $totalLeaveThisMonth }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Persetujuan Final Pending</div>
                <div class="text-4xl font-bold text-yellow-600 mt-2">{{ $pendingFinalApproval }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Total Karyawan</div>
                <div class="text-4xl font-bold text-blue-600 mt-2">{{ $employeePending }}</div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Akses Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('hrd.approvals.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                    Persetujuan Final Pengajuan Cuti
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
