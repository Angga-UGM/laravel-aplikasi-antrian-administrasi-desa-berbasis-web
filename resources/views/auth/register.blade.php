<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Kantor Desa Selokromo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">
        
    <style>
        body {
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
        }
        .register-card {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
            border-color: #059669;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="register-card bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-landmark text-white text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Kantor Desa Selokromo</h1>
                <p class="text-gray-500 text-sm mt-1">Sistem Antrian Pendaftaran Online</p>
                <div class="border-t mt-4 pt-4">
                    <p class="text-gray-600 font-medium">Registrasi Akun Baru</p>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <p class="text-sm"><i class="fas fa-exclamation-triangle mr-2"></i>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" autocomplete="off">
                @csrf

                <!-- Name Field -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-user text-green-600 mr-1"></i> Nama Lengkap
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 input-focus transition">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-envelope text-green-600 mr-1"></i> Alamat Email
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="username"
                           placeholder="admin@desaselokromo.id"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 input-focus transition">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor HP -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-phone text-green-600 mr-1"></i> Nomor HP (Opsional)
                    </label>
                    <input type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="081234567890"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 input-focus transition">
                </div>

                <!-- Password Field -->
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-lock text-green-600 mr-1"></i> Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               id="password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 input-focus transition pr-12">
                        <button type="button" onclick="togglePassword('password', 'eyeIcon')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-green-600">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-lock text-green-600 mr-1"></i> Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Ulangi password"
                               id="password_confirmation"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 input-focus transition pr-12">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eyeIconConfirm')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-green-600">
                            <i id="eyeIconConfirm" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit & Login Link -->
                <div class="flex items-center justify-between mb-6">
                    <a href="{{ route('login') }}" class="text-sm text-green-600 hover:text-green-700 hover:underline">
                        <i class="fas fa-arrow-left mr-1"></i> Sudah punya akun?
                    </a>
                    <button type="submit"
                            class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-2 px-6 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-md">
                        <i class="fas fa-user-plus mr-2"></i> Registrasi
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Kantor Desa Selokromo. All rights reserved.</p>
                <p class="mt-1">Sistem Informasi Administrasi Desa</p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
