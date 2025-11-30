@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Dashboard Admin</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Total Karyawan Aktif</div>
                <div class="text-4xl font-bold text-indigo-600 mt-2">{{ $activeEmployees }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Total Divisi</div>
                <div class="text-4xl font-bold text-blue-600 mt-2">{{ $totalDivisions }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Pengajuan Cuti Bulan Ini</div>
                <div class="text-4xl font-bold text-green-600 mt-2">{{ $totalLeaveThisMonth }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Persetujuan Pending</div>
                <div class="text-4xl font-bold text-[#f5ab00] mt-2">{{ $pendingApprovals }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Karyawan Eligible Cuti</div>
                <div class="text-4xl font-bold text-purple-600 mt-2">{{ $employeesMassEligible }}</div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Akses Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('admin.users.index') }}" role="button" class="inline-block w-1/2 bg-indigo-600 hover:bg-indigo-700 text-black font-bold py-4 px-8 rounded-lg text-center shadow">
                    <p class="inline-block w-1/2 bg-indigo-600 hover:bg-indigo-700 text-black font-bold py-50 px-8 rounded-lg text-center shadow">📋 Manajemen Pengguna</p>
                </a>
                <a href="{{ route('admin.divisions.index') }}" role="button" class="inline-block w-1/2 bg-blue-600 hover:bg-blue-700 text-black font-bold py-4 px-8 rounded-lg text-center shadow">
                    🗂️ Manajemen Divisi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
