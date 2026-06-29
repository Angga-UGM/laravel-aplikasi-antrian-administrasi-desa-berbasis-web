@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-700">Kalender Antrian</h1>
        <div>
            <input type="date" id="tanggal-picker" value="{{ $tanggal instanceof \Carbon\Carbon ? $tanggal->format('Y-m-d') : $tanggal }}"
                   class="px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-4 text-green-600">
            Tanggal:
            @if($tanggal instanceof \Carbon\Carbon)
                {{ $tanggal->format('d F Y') }}
            @else
                {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
            @endif
        </h2>

        @if($antrian->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keperluan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($antrian as $item)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $item->nomor_antrian }}</td>
                        <td class="px-6 py-4">{{ $item->nama }}</td>
                        <td class="px-6 py-4">{{ $item->nik }}</td>
                        <td class="px-6 py-4">{{ $item->keperluan }}</td>
                        <td class="px-6 py-4">
                            @if($item->status == 'menunggu')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Menunggu</span>
                            @elseif($item->status == 'dipanggil')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Dipanggil</span>
                            @elseif($item->status == 'selesai')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Selesai</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Batal</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <i class="fas fa-calendar-day text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-500">Tidak ada antrian pada tanggal ini</p>
        </div>
        @endif
    </div>

<script>
    $('#tanggal-picker').on('change', function() {
        window.location.href = '/admin/kalender?tanggal=' + $(this).val();
    });
</script>
@endsection
