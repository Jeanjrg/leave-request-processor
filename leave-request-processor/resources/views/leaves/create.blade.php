@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-xl font-semibold mb-6">Ajukan Cuti Baru</h3>

                <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Cuti</label>
                            <select name="leave_type" id="leave_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih Jenis Cuti --</option>
                                <option value="Cuti Tahunan" {{ old('leave_type') === 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                <option value="Cuti Sakit" {{ old('leave_type') === 'Cuti Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                            </select>
                            @error('leave_type')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('start_date')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('end_date')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">Nomor Darurat</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="Nomor hp" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('emergency_contact')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Cuti</label>
                            <textarea name="reason" id="reason" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('reason') }}</textarea>
                            @error('reason')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address_during_leave" class="block text-sm font-medium text-gray-700 mb-2">Alamat Selama Cuti</label>
                            <textarea name="address_during_leave" id="address_during_leave" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('address_during_leave') }}</textarea>
                            @error('address_during_leave')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Lampiran (Surat Dokter untuk Cuti Sakit) - Maks 2MB</label>
                            <input type="file" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('attachment')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">Ajukan</button>
                        <a href="{{ route('leaves.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-lg">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
