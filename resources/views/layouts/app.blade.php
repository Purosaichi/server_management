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
        .sidebar-submenu.is-open { display: block; }
        .sidebar-dropdown-row { display: flex; align-items: center; border-radius: 0.5rem; }
        .sidebar-dropdown-row:not(.sidebar-active):hover { background: #f3f4f6; }
        .sidebar-dropdown-link { flex: 1; min-width: 0; }
        .sidebar-dropdown-row.sidebar-active .sidebar-dropdown-link { color: #fff; }
        .sidebar-dropdown-toggle { display: flex; width: 2.5rem; height: 2.5rem; flex: 0 0 auto; align-items: center; justify-content: center; margin-right: 0.25rem; border: 0; border-radius: 0.5rem; background: transparent; color: inherit; cursor: pointer; }
        .sidebar-dropdown-row:not(.sidebar-active) .sidebar-dropdown-toggle:hover { background: #e5e7eb; }
        .sidebar-dropdown-row.sidebar-active .sidebar-dropdown-toggle:hover { background: rgba(255, 255, 255, 0.14); }
        .sidebar-dropdown-toggle i { transition: transform 0.15s ease; }
        .sidebar-dropdown-toggle[aria-expanded="true"] i { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen">
        <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100">
                <p class="text-sm font-bold text-gray-800">sim SPBE</p>
                <p class="text-xs text-gray-400">Monitoring GTK</p>
            </div>
            <nav class="flex-1 px-3 py-5 space-y-5">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-1">Beranda</span>
                </a>
                <div class="pt-2 space-y-2  ">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Monitoring</p>
                    <div class="sidebar-dropdown">
                           <div class="sidebar-dropdown-row {{ request()->routeIs('aset.*', 'server.*', 'network-device.*') ? 'sidebar-active' : '' }}">
                            <a href="{{ route('aset.index') }}"
                               class="sidebar-dropdown-link flex items-center px-4 py-5 rounded-lg text-sm font-medium {{ request()->routeIs('aset.*', 'server.*', 'network-device.*') ? 'text-white' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-boxes-stacked w-5"></i>
                                <span class="ml-2">Aset</span>
                            </a>
                            <button type="button" class="sidebar-dropdown-toggle" data-sidebar-toggle aria-expanded="false" aria-controls="asetSubmenu" aria-label="Buka submenu Aset">
                                <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div id="asetSubmenu" class="sidebar-submenu ml-5 border-l border-gray-200 pl-3">
                            <a href="{{ route('server.index') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('server.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-server w-5" aria-hidden="true"></i>
                                <span class="ml-2">Server</span>
                            </a>
                            <a href="{{ route('network-device.router') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('network-device.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-network-wired w-5" aria-hidden="true"></i>
                                <span class="ml-2">Network Device</span>
                            </a>
                            <a href="{{ route('aset.lokasi') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('aset.lokasi') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-location-dot w-5" aria-hidden="true"></i>
                                <span class="ml-2">Lokasi Aset</span>
                            </a>
                            <a href="{{ route('aset.storage') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('aset.storage') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-hard-drive w-5" aria-hidden="true"></i>
                                <span class="ml-2">Storage</span>
                            </a>
                        </div>
                    </div>
                    <div class="sidebar-dropdown">
                           <div class="sidebar-dropdown-row {{ request()->routeIs('application.*', 'domain.*') ? 'sidebar-active' : '' }}">
                            <a href="{{ route('application.index') }}"
                               class="sidebar-dropdown-link flex items-center px-4 py-5 rounded-lg text-sm font-medium {{ request()->routeIs('application.*', 'domain.*') ? 'text-white' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-window-maximize w-5"></i>
                                <span class="ml-2">Application</span>
                            </a>
                            <button type="button" class="sidebar-dropdown-toggle" data-sidebar-toggle aria-expanded="false" aria-controls="applicationSubmenu" aria-label="Buka submenu Application">
                                <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div id="applicationSubmenu" class="sidebar-submenu ml-5 border-l border-gray-200 pl-3">
                            <a href="{{ route('domain.domain') }}"
                               class="mt-1 flex items-center rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('domain.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                                <i class="fas fa-globe w-5" aria-hidden="true"></i>
                                <span class="ml-2">Domain</span>
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('licenses.licenses') }}" class="flex items-center px-4 py-5 rounded-lg text-sm font-medium {{ request()->routeIs('licenses.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-key w-5"></i>
                        <span class="ml-2">Licenses</span>
                    </a>
                    <a href="{{ route('maintenance.maintenance') }}" class="flex items-center px-4 py-5 rounded-lg text-sm font-medium {{ request()->routeIs('maintenance.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-screwdriver-wrench w-5"></i>
                        <span class="ml-2">Maintenance</span>
                    </a>
                    <a href="{{ route('alerts.alerts') }}" class="flex items-center px-4 py-5 rounded-lg text-sm font-medium {{ request()->routeIs('alerts.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-bell w-5"></i>
                        <span class="ml-2">Alerts</span>
                    </a>
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
        <main class="ml-64 flex min-h-screen min-w-0 flex-col p-6">
                <div class="mb-6 flex justify-center items-center border-b border-gray-200 pb-4">
                    <img src="{{ asset('images/logo kemendik2.png') }}" alt="Logo Kemendik" class="h-16 w-auto object-contain">
                </div>
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        document.querySelectorAll('[data-sidebar-toggle]').forEach(function(button) {
            button.addEventListener('click', function() {
                const submenu = document.getElementById(button.getAttribute('aria-controls'));
                const isOpen = button.getAttribute('aria-expanded') === 'true';

                button.setAttribute('aria-expanded', String(!isOpen));
                button.setAttribute('aria-label', (isOpen ? 'Buka' : 'Tutup') + ' submenu ' + (button.getAttribute('aria-controls') === 'asetSubmenu' ? 'Aset' : 'Application'));
                submenu.classList.toggle('is-open', !isOpen);
            });
        });
    </script>
</body>
</html>
