@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Dashboard Karyawan</h2>
            <a href="{{ route('leaves.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                + Ajukan Cuti Baru
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Kuota Cuti Tahunan</div>
                <div class="text-4xl font-bold text-indigo-600 mt-2">{{ $totalLeaveQuota }} hari</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Sisa Kuota</div>
                <div class="text-4xl font-bold text-green-600 mt-2">{{ $remainingQuota }} hari</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Kuota Terpakai</div>
                <div class="text-4xl font-bold text-orange-600 mt-2">{{ $usedQuota }} hari</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Total Pengajuan</div>
                <div class="text-4xl font-bold text-blue-600 mt-2">{{ $totalApplications }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Cuti Sakit</div>
                <div class="text-4xl font-bold text-red-600 mt-2">{{ $sickLeave }}</div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Pengajuan Cuti Anda</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('leaves.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                    📋 Lihat Riwayat Pengajuan
                </a>
                <a href="{{ route('leaves.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                    ➕ Ajukan Cuti Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
