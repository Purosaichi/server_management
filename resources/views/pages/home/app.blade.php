<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GTK Monitoring')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        * { font-family: 'Inter', sans-serif; }
        html, body, aside, aside nav {
            scrollbar-width: none;
            scroll-behavior: smooth;
        }
        html::-webkit-scrollbar,
        body::-webkit-scrollbar,
        aside::-webkit-scrollbar,
        aside nav::-webkit-scrollbar {
            display: none;
        }
        .sidebar-active { background: #2563eb; color: #fff; }
        .sidebar-link:hover { background: #f3f4f6; }
        .sidebar-submenu { display: none; }
        .sidebar-dropdown:hover .sidebar-submenu,
        .sidebar-dropdown:focus-within .sidebar-submenu { display: block; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div class="flex flex-1">
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100">
                <p class="text-sm font-bold text-gray-800">sim SPBE</p>
                <p class="text-xs text-gray-400">Monitoring GTK</p>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg sidebar-active text-sm font-medium">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-1">Beranda</span>
                </a>
                <div class="pt-2 space-y-1">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Monitoring</p>
                    <div class="sidebar-dropdown">
                        <a href="{{ route('aset.index') }}" aria-haspopup="true"
                           class="flex items-center justify-between px-4 py-2 rounded-lg {{ request()->routeIs('aset.*', 'server.*', 'network-device.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }} text-sm font-medium">
                            <span class="flex items-center">
                                <i class="fas fa-boxes-stacked w-5 text-gray-400"></i>
                                <span class="ml-2">Aset</span>
                            </span>
                            <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                        </a>
                        <div class="sidebar-submenu ml-5 border-l border-gray-200 pl-3">
                            <a href="{{ route('server.index') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('server.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-server w-5 text-gray-400" aria-hidden="true"></i>
                                <span class="ml-2">Server</span>
                            </a>
                            <a href="{{ route('network-device.router') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('network-device.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-network-wired w-5 text-gray-400" aria-hidden="true"></i>
                                <span class="ml-2">Network Device</span>
                            </a>
                            <a href="{{ route('aset.lokasi') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('aset.lokasi') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-location-dot w-5 text-gray-400" aria-hidden="true"></i>
                                <span class="ml-2">Lokasi Aset</span>
                            </a>
                            <a href="{{ route('aset.storage') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('aset.storage') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-hard-drive w-5 text-gray-400" aria-hidden="true"></i>
                                <span class="ml-2">Storage</span>
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="px-4 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 mb-2">{{ session('user_name') }}</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 p-6">
            <div class="mb-6 flex justify-center items-center border-b border-gray-200 pb-4 position-sticky">
                <img src="{{ asset('images/logo kemendik2.png') }}" alt="Logo Kemendik" class="h-16 w-auto object-contain">
            </div>
            @yield('content')
        </main>
    </div>
</body>
</html>
