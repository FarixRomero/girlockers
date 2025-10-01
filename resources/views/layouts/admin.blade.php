<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin - {{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('images/girllockers.jpg') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body antialiased bg-purple-darkest">
        <div class="min-h-screen flex">
            <!-- Sidebar (Desktop) -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-purple-darker border-r border-pink-vibrant/20">
                <div class="p-6">
                    <h1 class="font-accent text-3xl text-pink-vibrant">Girls Lockers</h1>
                    <p class="text-cream/60 text-sm mt-1">Admin Panel</p>
                </div>

                <nav class="flex-1 px-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.courses.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.courses.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Cursos
                    </a>

                    <a href="{{ route('admin.users.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Usuarios
                    </a>

                    <a href="{{ route('admin.access-requests.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.access-requests.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        Solicitudes
                    </a>
                </nav>

                <div class="p-4 border-t border-pink-vibrant/20">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 text-cream/70 hover:text-pink-vibrant rounded-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Bar -->
                <header class="bg-purple-darker border-b border-pink-vibrant/20 lg:px-8 px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            @if (isset($header))
                                <h2 class="font-display text-2xl text-cream">{{ $header }}</h2>
                            @endif
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-cream/70 text-sm hidden sm:block">{{ auth()->user()->name }}</span>
                            <div class="w-10 h-10 bg-gradient-pink rounded-full flex items-center justify-center text-cream font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                    {{ $slot }}
                </main>
            </div>

            <!-- Mobile Bottom Navigation -->
            <div class="lg:hidden nav-mobile">
                <div class="flex justify-around items-center">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </a>
                    <a href="{{ route('admin.courses.index') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </a>
                    <a href="{{ route('admin.users.index') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </a>
                    <a href="{{ route('admin.access-requests.index') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.access-requests.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
