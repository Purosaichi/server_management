<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Font Inter -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body>  

    <!-- container utama -->
    <div class="min-h-screen flex items-center justify-center relative bg-gray-50">
        
        {{-- Background Peta Indonesia (pake PNG) --}}
        <div class="absolute inset-0 opacity-10">
            <img src="{{ asset('images/map-indonesia.png') }}" alt="Peta Indonesia" class="w-full h-full object-cover">
        </div>

         <!-- card login -->
        <div class="relative z-10 w-full max-w-md bg-white rounded-xl shadow-lg p-8 mx-4"></div>

        <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('public/image/logo kemendik.png') }}" alt="Logo Kemendikdasmen" class="h-16 w-auto">
            </div>

              <!-- Title -->
            <h2 class="text-2xl font-bold text-center text-gray-800">Monitoring Sistem</h2>
            <p class="text-center text-gray-500 text-sm mb-6">Direktorat Jenderal Guru dan Tenaga Kependidikan</p>

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="admin@kemendik.go.id"
                        required>
                </div>

                 <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="••••••••"
                        required>
                </div>

                 {{-- Password --}}
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




