@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">Manajemen Divisi</h3>
                    <a href="{{ route('admin.divisions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-black font-bold py-2 px-4 rounded-lg shadow-md">
                        + Tambah Divisi
                    </a>
                </div>

                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Divisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ketua Divisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($divisions as $division)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $division->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $division->leader?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.divisions.edit', $division) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.divisions.destroy', $division) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada divisi ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $divisions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
