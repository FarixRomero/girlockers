<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('images/girlslockers.jpg') }}">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@400;500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&family=Satisfy&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-body antialiased bg-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 py-12">
            <!-- Logo Section -->
            <div class="mb-10">
                <a href="/" wire:navigate class="group flex flex-col items-center">
                    <img
                        src="{{ asset('images/girls_lockers_logo.png') }}"
                        alt="Girls Lockers Logo"
                        class="h-20 w-auto transition-all duration-300 group-hover:scale-105"
                    >
                </a>
            </div>

            <!-- Card Container -->
            <div class="w-full sm:max-w-md">
                <div class="bg-white rounded-2xl shadow-xl shadow-gray-200 p-8 sm:p-10 border border-gray-100">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Girls Lockers. Todos los derechos reservados.</p>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
