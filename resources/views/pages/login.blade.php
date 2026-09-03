<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Sistem</title>
    
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>

    <div class="min-h-screen flex items-center justify-center relative bg-gray-50">
        
        {{-- Background Peta --}}
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('public/images/map indo.png') }}" alt="Peta Indonesia" class="w-full h-full object-cover">
        </div>

        {{-- Card Login --}}
        <div class="relative z-10 w-full max-w-md bg-white rounded-xl shadow-lg p-8 mx-4">
            
            {{-- Logo --}}
            <div class="flex justify-center mb-4">
                <img src="{{ asset('public/images/logo kemendik.png') }}" alt="Logo Kemendikdasmen" class="h-16 w-auto">
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-800">Monitoring Sistem</h2>
            <p class="text-center text-gray-500 text-sm mb-6">Direktorat Jenderal Guru dan Tenaga Kependidikan</p>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="admin@kemendik.go.id"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition font-medium"
                >
                    Masuk
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-6">
                © 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen
            </p>
        </div>
    </div>

</body>
</html>