@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-6">Detail Pengajuan Cuti - Persetujuan HRD</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Karyawan</label>
                        <p class="mt-2 text-lg font-semibold">{{ $leave->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Divisi</label>
                        <p class="mt-2">{{ $leave->user->division->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                        <p class="mt-2 text-lg font-semibold">{{ $leave->leave_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-2"><span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">{{ $leave->status }}</span></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai - Selesai</label>
                        <p class="mt-2">{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Hari</label>
                        <p class="mt-2 text-lg font-semibold">{{ $leave->total_days }} hari</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alasan Cuti</label>
                        <p class="mt-2">{{ $leave->reason }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Persetujuan Ketua Divisi</label>
                        <p class="mt-2 text-green-600 font-semibold">✓ Disetujui pada {{ $leave->leader_approved_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold mb-4">Keputusan Persetujuan Final</h4>

                    <div class="grid grid-cols-2 gap-4">
                        <form action="{{ route('hrd.approvals.approve', $leave) }}" method="POST">
                            @csrf
                            <div>
                                <label for="hrd_note" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <textarea name="hrd_note" id="hrd_note" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                                Setujui
                            </button>
                        </form>

                        <form action="{{ route('hrd.approvals.reject', $leave) }}" method="POST">
                            @csrf
                            <div>
                                <label for="hrd_note" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                                <textarea name="hrd_note" id="hrd_note" rows="2" required class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('hrd.approvals.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
