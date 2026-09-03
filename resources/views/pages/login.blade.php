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

        {{-- Logo --}}
            <div class="flex justify-center mb-4">
                <img src="{{ asset('public/image/logo kemendik.png') }}" alt="Logo Kemendikdasmen" class="h-16 w-auto">
            </div>
