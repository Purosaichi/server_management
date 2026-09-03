<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Sistem</title>
    
    <!--tailwindcss-->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!--font-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>

    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-4 relative">
        
        <!--map indo-->
        <div class="absolute inset-0 opacity-25">
            <img src="{{ asset('images/map indo.png') }}" alt="Peta Indonesia" class="w-full h-full object-cover">
        </div>

        <!--card login--> 
        <div class="relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row">
            
            <!--kolom kiri-->
            <div class="w-full md:w-1/2 bg-white p-8 md:p-12 flex flex-col justify-center items-center text-center">
                
                <!--logo kemendik-->
                <div class="mb-4">
                    <img src="{{ asset('images/logo kemendik.png') }}" alt="Logo Kemendikdasmen" class="h-70 w-auto drop-shadow-lg">
                </div>
                
                <!-- ditjen GTK -->
                <p class="text-xl md:text-lg font-extrabold text-gray-900 tracking-wide">
                    DITJEN GTK
                </p>
            </div>
            
            <!--form login-->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white">
                
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Login</h2>
                <p class="text-gray-500 text-sm mb-6">Masuk ke dashboard monitoring</p>
                
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                            placeholder="admin@kemendik.go.id"
                            required>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                            placeholder="••••••••"
                            required>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition font-medium">
                        Masuk
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 mt-6">
                    © 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen
                </p>
            </div>
        </div>
    </div>

</body>
</html>