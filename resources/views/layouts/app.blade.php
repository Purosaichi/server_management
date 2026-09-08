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
        .sidebar-active { background: #2563eb; color: #fff; }
        .sidebar-link:hover { background: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen flex-col">
        <div class="flex flex-1">
            <aside class="w-64 bg-white border-r border-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100">
                <p class="text-sm font-bold text-gray-800">sim SPBE</p>
                <p class="text-xs text-gray-400">Monitoring GTK</p>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-1">Beranda</span>
                </a>
                <div class="pt-2">
                    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Monitoring</p>
                    <a href="{{ route('server.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('server.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-server w-5"></i>
                        <span class="ml-2">Server</span>
                    </a>
                    <a href="{{ route('application.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('application.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-window-maximize w-5"></i>
                        <span class="ml-2">Application</span>
                    </a>
                    <a href="{{ route('domain.domain') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('domain.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-globe w-5"></i>
                        <span class="ml-2">Domain</span>
                    </a>
                    <a href="{{ route('licenses.licenses') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('licenses.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-key w-5"></i>
                        <span class="ml-2">Licenses</span>
                    </a>
                    <a href="{{ route('maintenance.maintenance') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('maintenance.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
                        <i class="fas fa-screwdriver-wrench w-5"></i>
                        <span class="ml-2">Maintenance</span>
                    </a>
                    <a href="{{ route('alerts.alerts') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('alerts.*') ? 'sidebar-active' : 'sidebar-link text-gray-500' }}">
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
            <main class="flex flex-1 flex-col p-6">
                <div class="mb-6 flex justify-center items-center border-b border-gray-200 pb-4">
                    <img src="{{ asset('images/logo kemendik2.png') }}" alt="Logo Kemendik" class="h-16 w-auto object-contain">
                </div>
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
