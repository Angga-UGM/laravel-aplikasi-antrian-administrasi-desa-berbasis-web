<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Antrian - Kantor Desa Selokromo')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo-desa-selokromo.png') }}">

    <!-- PWA Head -->
    @PwaHead

    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
        .blinking { animation: blink 1s ease-in-out infinite; }
        .card-hover:hover { transform: translateY(-5px); transition: all 0.3s ease; }

        @media (max-width: 768px) {
            .desktop-menu { display: none !important; }
            .mobile-menu-btn { display: block !important; }
            .nav-brand-text { font-size: 0.75rem !important; }
            .nav-brand-sub { font-size: 0.6rem !important; }
            .nav-logo-icon { width: 28px !important; height: 28px !important; }
        }
        @media (min-width: 769px) {
            .desktop-menu { display: flex !important; }
            .mobile-menu-btn { display: none !important; }
            .mobile-menu { display: none !important; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr !important; }
            .nav-brand-text { font-size: 0.7rem !important; }
            .nav-brand-sub { font-size: 0.55rem !important; }
        }
        .mobile-menu { display: none; }
        .navbar-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-green-700 text-white shadow-lg sticky top-0 z-50 w-full">
        <div class="px-3 sm:px-4 py-2 sm:py-3">
            <div class="navbar-container">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="nav-logo-icon w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center">
                        <img src="{{ asset('logo-desa-selokromo.png') }}" alt="Logo Desa" class="w-full h-full object-cover">
                    </div>
                    <div class="nav-brand">
                        <h1 class="nav-brand-text text-white font-bold text-xs sm:text-lg md:text-xl leading-tight">Kantor Desa Selokromo</h1>
                        <p class="nav-brand-sub text-green-200 text-xs sm:text-sm"><i class="fas fa-ticket-alt mr-1"></i> Antrian Online</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="desktop-menu items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('antrian.form') }}" class="px-2 py-1 sm:px-3 sm:py-2 bg-green-600 rounded hover:bg-green-800 transition text-xs sm:text-base">Daftar Baru</a>
                    <a href="{{ route('antrian.layar') }}" class="text-white hover:bg-green-600 px-2 py-1 sm:px-3 sm:py-2 rounded transition text-xs sm:text-base">Layar</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-green-600 px-2 py-1 sm:px-3 sm:py-2 rounded transition text-xs sm:text-base">Admin</a>
                    <a href="{{ route('admin.kalender') }}" class="text-white hover:bg-green-600 px-2 py-1 sm:px-3 sm:py-2 rounded transition text-xs sm:text-base">Kalender</a>
                    <a href="{{ route('profile.edit') }}" class="text-white hover:bg-green-600 px-2 py-1 sm:px-3 sm:py-2 rounded transition text-xs sm:text-base">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 sm:px-3 sm:py-2 rounded transition text-xs sm:text-base">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="mobile-menu-btn text-white focus:outline-none">
                    <i class="fas fa-bars text-xl sm:text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu mt-3 pb-2 flex flex-col space-y-2">
                <a href="{{ route('antrian.form') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition text-sm">Daftar Antrian</a>
                <a href="{{ route('antrian.layar') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition text-sm">Layar Antrian</a>
                <a href="{{ route('admin.dashboard') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition text-sm">Admin Panel</a>
                <a href="{{ route('admin.kalender') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition text-sm">Kalender Antrian</a>
                <a href="{{ route('profile.edit') }}" class="text-white hover:bg-green-600 px-3 py-2 rounded transition text-sm">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded transition text-sm w-full text-left">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full px-3 sm:px-4 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8 py-3 sm:py-4 w-full">
        <div class="px-3 sm:px-4 text-center text-gray-500">
            <p class="text-xs sm:text-sm"><i class="fas fa-copyright"></i> {{ date('Y') }} Kantor Desa Selokromo - Antrian Online</p>
            <p class="text-xs mt-1">Jl. Banyumsa Km. 10, Kec. Leksono, Kab. Wonosobo</p>
        </div>
    </footer>

    <!-- PWA Service Worker -->
    @RegisterServiceWorkerScript

    <script>
        $(document).ready(function() {
            $('#mobile-menu-btn').click(function() {
                var menu = $('#mobile-menu');
                if (menu.css('display') === 'none' || menu.css('display') === '') {
                    menu.css('display', 'flex');
                } else {
                    menu.css('display', 'none');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
