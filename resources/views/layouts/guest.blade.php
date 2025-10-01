<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body antialiased bg-purple-deep">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <div class="mb-8">
                <a href="/" wire:navigate>
                    <h1 class="font-accent text-4xl text-pink-vibrant">Girl Lockers</h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md card-premium">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
