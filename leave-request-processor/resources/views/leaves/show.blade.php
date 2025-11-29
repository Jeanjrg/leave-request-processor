@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-6">Detail Pengajuan Cuti</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                        <p class="mt-2 text-lg font-semibold">{{ $leave->leave_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-2"><span class="px-2 py-1 rounded text-xs font-semibold
                        @if($leave->status === 'Pending') bg-yellow-100 text-yellow-800
                        @elseif($leave->status === 'Approved by Leader') bg-blue-100 text-blue-800
                        @elseif($leave->status === 'Approved') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">{{ $leave->status }}</span></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <p class="mt-2">{{ $leave->start_date->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <p class="mt-2">{{ $leave->end_date->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Hari</label>
                        <p class="mt-2 text-lg font-semibold">{{ $leave->total_days }} hari</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Darurat</label>
                        <p class="mt-2">{{ $leave->emergency_contact ?? '-' }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alasan Cuti</label>
                        <p class="mt-2">{{ $leave->reason }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat Selama Cuti</label>
                        <p class="mt-2">{{ $leave->address_during_leave ?? '-' }}</p>
                    </div>

                    @if($leave->attachment)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Lampiran</label>
                        <a href="{{ asset('storage/' . $leave->attachment) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                            Lihat Lampiran
                        </a>
                    </div>
                    @endif
                </div>

                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold mb-4">Timeline Approval</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-700">Disetujui oleh Ketua Divisi</p>
                            <p class="text-lg font-semibold">
                                {{ $leave->leader_approved_at ? $leave->leader_approved_at->format('d M Y H:i') : 'Menunggu...' }}
                            </p>
                            @if($leave->leader_rejection_note)
                            <p class="text-red-600 mt-2">Alasan penolakan: {{ $leave->leader_rejection_note }}</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-gray-700">Disetujui oleh HRD</p>
                            <p class="text-lg font-semibold">
                                {{ $leave->hrd_approved_at ? $leave->hrd_approved_at->format('d M Y H:i') : 'Menunggu...' }}
                            </p>
                            @if($leave->hrd_note)
                            <p class="text-gray-600 mt-2">Catatan: {{ $leave->hrd_note }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    @if($leave->status === 'Pending' && auth()->id() === $leave->user_id)
                    <a href="{{ route('leaves.edit', $leave) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">Edit</a>
                    <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan pengajuan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">Batalkan</button>
                    </form>
                    @endif
                    <a href="{{ route('leaves.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
