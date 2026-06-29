<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Antrian - Kantor Desa Selokromo</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">

    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .blinking {
            animation: blink 1s ease-in-out infinite;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .container { padding-left: 1rem; padding-right: 1rem; }
            .nav-brand-text { font-size: 0.875rem !important; }
            .nav-brand-sub { font-size: 0.7rem !important; }
        }
        @media (max-width: 480px) {
            .nav-brand-icon { font-size: 1rem !important; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar with Desa Selokromo Identity -->
    <nav class="bg-green-700 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md">
                        <i class="fas fa-landmark text-green-700 text-xl"></i>
                    </div>
                    <div class="nav-brand">
                        <h1 class="nav-brand-text text-white font-bold text-lg md:text-xl">
                            Kantor Desa Selokromo
                        </h1>
                        <p class="nav-brand-sub text-green-200 text-xs md:text-sm">
                            <i class="fas fa-ticket-alt mr-1"></i> Sistem Antrian Online
                        </p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('antrian.form') }}" class="px-4 py-2 bg-green-600 rounded hover:bg-green-800 transition">
                        <i class="fas fa-plus"></i> Daftar Baru
                    </a>
                    <a href="{{ route('antrian.layar') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition">
                        <i class="fas fa-tv"></i> Layar
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition">
                        <i class="fas fa-user-shield"></i> Admin
                    </a>
                </div>

                <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <div id="mobile-menu" class="hidden md:hidden mt-3 pb-2 flex flex-col space-y-2">
                <a href="{{ route('antrian.form') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i class="fas fa-file-alt w-6"></i> Daftar Antrian
                </a>
                <a href="{{ route('antrian.layar') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i class="fas fa-tv w-6"></i> Layar Antrian
                </a>
                <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition">
                    <i class="fas fa-user-shield w-6"></i> Admin Panel
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        <div class="text-center py-12">
            <div class="inline-block w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-landmark text-green-700 text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Sistem Antrian Online</h1>
            <p class="text-xl text-gray-600 mb-8">Kantor Desa Selokromo</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('antrian.form') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-ticket-alt"></i> Ambil Antrian
                </a>
                <a href="{{ route('antrian.layar') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-tv"></i> Lihat Layar
                </a>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t mt-8 py-4">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            <p>
                <i class="fas fa-copyright"></i> {{ date('Y') }} Kantor Desa Selokromo - Sistem Antrian Online
            </p>
            <p class="text-xs mt-1">
                Jl. Raya Banyumas KM 10, Kec. Leksono, Kab. Wonosobo
            </p>
        </div>
    </footer>

    <script>
        $('#mobile-menu-btn').click(function() {
            $('#mobile-menu').toggleClass('hidden');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>