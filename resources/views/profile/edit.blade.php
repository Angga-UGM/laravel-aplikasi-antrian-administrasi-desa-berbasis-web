@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="w-full px-2 sm:px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-3 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-center text-green-700">
            <i class="fas fa-user-circle"></i> Profil Administrator
        </h1>

        @if(session('status') == 'profile-updated')
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm sm:text-base">
                <i class="fas fa-check-circle mr-2"></i> Profil berhasil diperbarui!
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm sm:text-base">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm sm:text-base">
                @foreach($errors->all() as $error)
                    <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Grid Responsif: 1 kolom di HP, 2 kolom di desktop -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            
            <!-- Bagian Kiri: Informasi Akun -->
            <div class="border rounded-lg p-3 sm:p-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-700 mb-3 sm:mb-4 border-b pb-2">
                    <i class="fas fa-info-circle text-green-600"></i> Informasi Akun
                </h2>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-600 text-xs sm:text-sm font-medium mb-1">Nama Lengkap</label>
                    <p class="text-gray-800 font-semibold text-sm sm:text-base">{{ Auth::user()->name }}</p>
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-600 text-xs sm:text-sm font-medium mb-1">Alamat Email</label>
                    <p class="text-gray-800 font-semibold text-sm sm:text-base">{{ Auth::user()->email }}</p>
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-600 text-xs sm:text-sm font-medium mb-1">Terdaftar Sejak</label>
                    <p class="text-gray-800 text-sm sm:text-base">{{ Auth::user()->created_at->format('d F Y, H:i') }}</p>
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-600 text-xs sm:text-sm font-medium mb-1">Terakhir Login</label>
                    <p class="text-gray-800 text-sm sm:text-base">
                        @if(Auth::user()->last_login_at)
                            {{ \Carbon\Carbon::parse(Auth::user()->last_login_at)->format('d F Y, H:i') }}
                        @else
                            Belum tercatat
                        @endif
                    </p>
                </div>
            </div>

            <!-- Bagian Kanan: Statistik Antrian -->
            <div class="border rounded-lg p-3 sm:p-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-700 mb-3 sm:mb-4 border-b pb-2">
                    <i class="fas fa-chart-line text-green-600"></i> Statistik Antrian
                </h2>

                <?php
                    // Statistik keseluruhan
                    $totalAntrian = \App\Models\Antrian::count();
                    $totalSelesai = \App\Models\Antrian::where('status', 'selesai')->count();
                    $totalBatal = \App\Models\Antrian::where('status', 'batal')->count();
                    
                    // Statistik hari ini
                    $antrianHariIni = \App\Models\Antrian::whereDate('tanggal_antrian', now())->count();
                    $selesaiHariIni = \App\Models\Antrian::whereDate('tanggal_antrian', now())->where('status', 'selesai')->count();
                    
                    // Statistik bulan ini
                    $antrianBulanIni = \App\Models\Antrian::whereMonth('tanggal_antrian', now()->month)
                        ->whereYear('tanggal_antrian', now()->year)
                        ->count();
                    $selesaiBulanIni = \App\Models\Antrian::whereMonth('tanggal_antrian', now()->month)
                        ->whereYear('tanggal_antrian', now()->year)
                        ->where('status', 'selesai')
                        ->count();
                    
                    // Statistik tahun ini
                    $antrianTahunIni = \App\Models\Antrian::whereYear('tanggal_antrian', now()->year)->count();
                    $selesaiTahunIni = \App\Models\Antrian::whereYear('tanggal_antrian', now()->year)
                        ->where('status', 'selesai')
                        ->count();
                ?>

                <!-- Ringkasan Keseluruhan -->
                <div class="bg-green-50 rounded-lg p-2 sm:p-3 mb-4">
                    <h3 class="font-semibold text-green-700 text-sm sm:text-base mb-2">📊 Ringkasan Keseluruhan</h3>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div>
                            <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $totalAntrian }}</p>
                            <p class="text-xs text-gray-500">Total Antrian</p>
                        </div>
                        <div>
                            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $totalSelesai }}</p>
                            <p class="text-xs text-gray-500">Selesai</p>
                        </div>
                        <div>
                            <p class="text-xl sm:text-2xl font-bold text-red-600">{{ $totalBatal }}</p>
                            <p class="text-xs text-gray-500">Batal</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik per Periode -->
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded flex-wrap gap-2">
                        <div class="text-sm sm:text-base">
                            <i class="fas fa-sun text-yellow-500 mr-2"></i>
                            <span class="text-gray-700">Hari Ini</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-green-600 text-sm sm:text-base">{{ $antrianHariIni }}</span>
                            <span class="text-xs text-gray-400 mx-1">|</span>
                            <span class="text-xs sm:text-sm text-blue-600">Selesai: {{ $selesaiHariIni }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded flex-wrap gap-2">
                        <div class="text-sm sm:text-base">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            <span class="text-gray-700">Bulan {{ now()->format('F Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-green-600 text-sm sm:text-base">{{ $antrianBulanIni }}</span>
                            <span class="text-xs text-gray-400 mx-1">|</span>
                            <span class="text-xs sm:text-sm text-blue-600">Selesai: {{ $selesaiBulanIni }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded flex-wrap gap-2">
                        <div class="text-sm sm:text-base">
                            <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                            <span class="text-gray-700">Tahun {{ now()->year }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-green-600 text-sm sm:text-base">{{ $antrianTahunIni }}</span>
                            <span class="text-xs text-gray-400 mx-1">|</span>
                            <span class="text-xs sm:text-sm text-blue-600">Selesai: {{ $selesaiTahunIni }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <div class="border rounded-lg p-3 sm:p-4 mt-4 sm:mt-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-700 mb-3 sm:mb-4 border-b pb-2">
                <i class="fas fa-edit text-green-600"></i> Edit Informasi Akun
            </h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm sm:text-base font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm sm:text-base font-bold mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded transition text-sm sm:text-base">
                    <i class="fas fa-save"></i> Update Profil
                </button>
            </form>
        </div>

        <!-- Form Ganti Password -->
        <div class="border rounded-lg p-3 sm:p-4 mt-4 sm:mt-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-700 mb-3 sm:mb-4 border-b pb-2">
                <i class="fas fa-key text-green-600"></i> Ganti Password
            </h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm sm:text-base font-bold mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                    <p class="text-xs text-gray-500 mt-1">Wajib diisi untuk mengganti password</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm sm:text-base font-bold mb-2">Password Baru</label>
                    <input type="password" name="password" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm sm:text-base font-bold mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                </div>

                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded transition text-sm sm:text-base">
                    <i class="fas fa-key"></i> Ganti Password
                </button>
            </form>
        </div>

        <!-- Hapus Akun (Opsional) -->
        <div class="border border-red-300 rounded-lg p-3 sm:p-4 mt-4 sm:mt-6 bg-red-50">
            <h2 class="text-base sm:text-lg font-semibold text-red-700 mb-3 sm:mb-4 border-b border-red-200 pb-2">
                <i class="fas fa-trash-alt text-red-600"></i> Hapus Akun
            </h2>
            <p class="text-xs sm:text-sm text-gray-600 mb-4">Setelah akun Anda dihapus, semua data akan hilang permanen.</p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm sm:text-base font-bold mb-2">Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password untuk konfirmasi"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500 text-sm sm:text-base">
                </div>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded transition text-sm sm:text-base"
                        onclick="return confirm('Yakin ingin menghapus akun ini? Semua data akan hilang!')">
                    <i class="fas fa-trash-alt"></i> Hapus Akun
                </button>
            </form>
        </div>
    </div>
</div>
@endsection