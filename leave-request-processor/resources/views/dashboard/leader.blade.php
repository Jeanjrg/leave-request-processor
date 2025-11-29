@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Dashboard Ketua Divisi</h2>

        @if($division)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Divisi</div>
                <div class="text-2xl font-bold text-indigo-600 mt-2">{{ $division->name }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Jumlah Anggota</div>
                <div class="text-4xl font-bold text-blue-600 mt-2">{{ $employeeCount }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Pengajuan Bulan Ini</div>
                <div class="text-4xl font-bold text-green-600 mt-2">{{ $totalLeaveThisMonth }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-600 text-sm font-semibold">Persetujuan Pending</div>
                <div class="text-4xl font-bold text-yellow-600 mt-2">{{ $pendingApprovals }}</div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Akses Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('leader.approvals.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                    Persetujuan Pengajuan Cuti
                </a>
            </div>
        </div>
        @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            Anda belum menjadi ketua divisi mana pun.
        </div>
        @endif
    </div>
</div>
@endsection
