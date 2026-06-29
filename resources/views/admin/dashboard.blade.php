@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Tombol Buka Layar Antrian Admin -->
    <div class="flex justify-end no-print">
        <a href="{{ route('antrian.layar') }}?admin=true" target="_blank" 
           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
            <i class="fas fa-tv mr-2"></i> Buka Layar Antrian (Kontrol Admin)
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menunggu</p>
                    <p class="text-2xl font-bold" id="count-menunggu">0</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Dipanggil</p>
                    <p class="text-2xl font-bold" id="count-dipanggil">0</p>
                </div>
                <i class="fas fa-bullhorn text-green-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Selesai</p>
                    <p class="text-2xl font-bold" id="count-selesai">0</p>
                </div>
                <i class="fas fa-check-circle text-blue-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Hari Ini</p>
                    <p class="text-2xl font-bold" id="count-total">0</p>
                </div>
                <i class="fas fa-users text-purple-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Current Called -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white" id="current-called">
        <div class="text-center">
            <p class="text-lg">Sedang Dilayani</p>
            <p class="text-6xl font-bold blinking" id="nomor-sekarang">-</p>
            <p class="text-xl mt-2" id="nama-sekarang">-</p>
            <p class="text-md" id="keperluan-sekarang">-</p>
        </div>
    </div>

    <!-- Queue Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-xl font-semibold">Daftar Antrian Hari Ini</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keperluan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="antrian-body">
                    <tr><td colspan="6" class="text-center py-4">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function formatWaktu(waktu) {
        if(!waktu) return '-';
        let date = new Date(waktu);
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    function playSound() {
        let audio = new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3');
        audio.play().catch(e => console.log('Suara diblokir'));
    }

    function panggil(id) {
        $.ajax({
            url: '/admin/panggil/' + id,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    loadData();
                    playSound();
                } else {
                    alert('Gagal: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    }

    function selesai(id) {
        $.ajax({
            url: '/admin/selesai/' + id,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) loadData();
            }
        });
    }

    function batal(id) {
        if(confirm('Yakin ingin membatalkan antrian ini?')) {
            $.ajax({
                url: '/admin/batal/' + id,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if(response.success) loadData();
                }
            });
        }
    }

    function loadData() {
        $.get('/admin/api/antrian-realtime', function(data) {
            // Update stats
            $('#count-menunggu').text(data.statistik.menunggu);
            $('#count-dipanggil').text(data.statistik.dipanggil);
            $('#count-selesai').text(data.statistik.selesai);
            $('#count-total').text(data.statistik.total);
            
            // Update current called
            if(data.antrian_sekarang) {
                $('#nomor-sekarang').text(data.antrian_sekarang.nomor_antrian);
                $('#nama-sekarang').text(data.antrian_sekarang.nama);
                $('#keperluan-sekarang').text(data.antrian_sekarang.keperluan);
            } else {
                $('#nomor-sekarang').text('-');
                $('#nama-sekarang').text('Tidak ada antrian');
                $('#keperluan-sekarang').text('-');
            }
            
            // Update table
            let html = '';
            data.antrian.forEach(antrian => {
                let statusHtml = '';
                let statusClass = '';
                if(antrian.status == 'menunggu') {
                    statusHtml = 'Menunggu';
                    statusClass = 'bg-yellow-100 text-yellow-800';
                } else if(antrian.status == 'dipanggil') {
                    statusHtml = 'Dipanggil';
                    statusClass = 'bg-green-100 text-green-800';
                } else if(antrian.status == 'selesai') {
                    statusHtml = 'Selesai';
                    statusClass = 'bg-blue-100 text-blue-800';
                } else {
                    statusHtml = 'Batal';
                    statusClass = 'bg-red-100 text-red-800';
                }
                
                let actions = '';
                if(antrian.status == 'menunggu') {
                    actions = `<button onclick="panggil(${antrian.id})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"><i class="fas fa-bullhorn"></i> Panggil</button>
                               <button onclick="batal(${antrian.id})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"><i class="fas fa-times"></i> Batal</button>`;
                } else if(antrian.status == 'dipanggil') {
                    actions = `<button onclick="selesai(${antrian.id})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"><i class="fas fa-check"></i> Selesai</button>
                               <button onclick="batal(${antrian.id})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"><i class="fas fa-times"></i> Batal</button>`;
                }
                
                html += `
                    <tr>
                        <td class="px-6 py-4 font-medium">${antrian.nomor_antrian}</td>
                        <td class="px-6 py-4">${antrian.nama}</td>
                        <td class="px-6 py-4">${antrian.keperluan}</td>
                        <td class="px-6 py-4">${formatWaktu(antrian.waktu_daftar)}</td>
                        <td class="px-6 py-4"><span class="${statusClass} px-2 py-1 rounded">${statusHtml}</span></td>
                        <td class="px-6 py-4 space-x-2">${actions}</td>
                    </tr>
                `;
            });
            $('#antrian-body').html(html);
        });
    }

    // Load data setiap 2 detik
    setInterval(loadData, 2000);
    loadData();
</script>
@endsection