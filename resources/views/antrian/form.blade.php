@extends('layouts.app')

@section('content')
    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-6 text-center text-green-700">
                    <i class="fas fa-file-alt"></i> Form Pendaftaran Antrian
                </h1>

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('antrian.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Lengkap *</label>
                            <input type="text" name="nama" required class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">NIK (16 digit) *</label>
                            <input type="text" name="nik" maxlength="16" required class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Jenis Kelamin *</label>
                            <select name="jenis_kelamin" required class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Alamat *</label>
                            <textarea name="alamat" rows="3" required class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Keperluan *</label>
                            <select name="keperluan" required class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih</option>
                                <option value="SKCK">SKCK</option>
                                <option value="Surat Keterangan Tidak Mampu (SKTM)">Surat Keterangan Tidak Mampu (SKTM)</option>
                                <option value="Konsultasi">Konsultasi</option>
                                <option value="Kehilangan">Kehilangan</option>
                                <option value="Administrasi">Administrasi</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
                            <i class="fas fa-ticket-alt"></i> Ambil Antrian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
