@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-6">Detail Pengajuan Cuti - Persetujuan Ketua Divisi</h3>

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
                        <p class="mt-2"><span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">{{ $leave->status }}</span></p>
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
                    @if($leave->attachment)
                    <div class="md:col-span-2">
                        <a href="{{ asset('storage/' . $leave->attachment) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Lihat Lampiran</a>
                    </div>
                    @endif
                </div>

                @if($leave->status === 'Pending')
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold mb-4">Keputusan Persetujuan</h4>

                    <div class="grid grid-cols-2 gap-4">
                        <form action="{{ route('leader.approvals.approve', $leave) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                                Setujui
                            </button>
                        </form>

                        <button onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">
                            Tolak
                        </button>
                    </div>

                    <form action="{{ route('leader.approvals.reject', $leave) }}" method="POST" id="rejectForm" class="hidden mt-4">
                        @csrf
                        <div>
                            <label for="leader_rejection_note" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="leader_rejection_note" id="leader_rejection_note" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                            @error('leader_rejection_note')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <button type="submit" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">
                            Kirim Penolakan
                        </button>
                    </form>
                </div>
                @else
                <div class="border-t pt-6 bg-gray-50 p-4 rounded">
                    <p class="text-gray-700">Pengajuan cuti ini sudah diproses.</p>
                </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('leader.approvals.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
