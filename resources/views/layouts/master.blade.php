<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('images/girlslockers.jpg') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dynamic Brand Color -->
        <style>
            :root {
                --color-primary: {{ $brandingColor ?? '#9333ea' }};
            }
            .bg-purple-primary, .bg-purple-500, .bg-purple-600 {
                background-color: var(--color-primary) !important;
            }
            .text-purple-primary, .text-purple-500, .text-purple-600 {
                color: var(--color-primary) !important;
            }
            .border-purple-primary, .border-purple-500, .border-purple-600 {
                border-color: var(--color-primary) !important;
            }
            .from-purple-primary, .to-purple-primary, .via-purple-primary {
                --tw-gradient-stops: var(--color-primary), var(--tw-gradient-to, rgba(147, 51, 234, 0)) !important;
            }
            .hover\:bg-purple-primary:hover, .hover\:bg-purple-600:hover {
                background-color: var(--color-primary) !important;
            }
            /* Ensure text is white on primary color backgrounds */
            .bg-purple-primary, .bg-purple-500, .bg-purple-600,
            .hover\:bg-purple-600:hover {
                color: white !important;
            }
        </style>

        @stack('styles')
    </head>
    <body class="font-body antialiased bg-purple-deep text-cream">
        <div class="min-h-screen">
            {{ $slot }}
        </div>

        @stack('scripts')
    </body>
</html>
