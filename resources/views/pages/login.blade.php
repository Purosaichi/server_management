<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - sim SPBE</title>
        
        [-- tail wind --]
        <script src="https://cdn.tailwindcss.com"></script>

        [-- font --]
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    </head>
    <body>
        <div class="main screen">
            [-- map --]
            <div class="map background">
                <img src="{{ asset('images/public/images/map indo.png') }}" alt="map" class="map-image">
            </div>

            [-- card login --]
            <div class="card login">

            [-- kolom kiri --]
            <div class="logo kemendik">
                <img src="{{ asset('images/public/images/logo kemendik.png') }}" alt="logo kemendik" class="logo-image">
                    <p class="title">DITJEN GTK</p>
            </div>

            [-- login form --]
            <div class="form login">

            <h2 class="title">LOGIN</h2>
            <p class="subtitle">Masuk ke dashboard monitoring</p>
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
    </body>
</html>