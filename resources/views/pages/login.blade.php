<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - sim SPBE</title>
    
    <!-- tailwindcss -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <!-- Container Utama -->
    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-4 relative">
        
        <!-- Background map -->
        <div class="map-background">
            <img src="{{ asset('images/map indo.png') }}" alt="Peta Indonesia">
        </div>

        <!-- Card Login -->
        <div class="login-card">
            
            <!-- Kolom Kiri: Branding -->
            <div class="logo-kemendik">
                <img src="{{ asset('images/logo kemendik.png') }}" alt="Logo Kemendikdasmen" class="branding-logo">
                <p class="branding-title">DITJEN GTK</p>
            </div>
            
            <!-- Kolom Kanan: Form Login -->
            <div class="form-login">
                
                <h2 class="form-title">Login</h2>
                <p class="form-subtitle">Masuk ke dashboard monitoring</p>

                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="form-input"
                            placeholder="admin@kemendik.go.id"
                            value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-input"
                            placeholder="••••••••"
                            required>
                    </div>

                    <button type="submit" class="btn-login">
                        Masuk
                    </button>
                </form>

                <p class="form-footer">
                    © 2026 Direktorat Jenderal Guru, Tenaga Kependidikan dan Pendidikan Guru - Kemendikdasmen
                </p>
            </div>
        </div>
    </div>

</body>
</html>