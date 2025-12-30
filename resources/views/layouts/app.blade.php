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
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Satisfy&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Livewire Styles -->
        @livewireStyles

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
            .hover\:bg-purple-primary:hover, .hover\:bg-purple-600:hover {
                background-color: var(--color-primary) !important;
            }
            .hover\:text-purple-primary:hover {
                color: var(--color-primary) !important;
            }
            /* Ensure text is white on primary color backgrounds */
            .bg-purple-primary, .bg-purple-500, .bg-purple-600,
            .hover\:bg-purple-600:hover {
                color: white !important;
            }
        </style>
    </head>
    <body class="font-body antialiased bg-white">
        <div class="flex h-screen overflow-hidden">
            <!-- Desktop Sidebar -->
            <aside x-data="{ collapsed: false }" :class="collapsed ? 'w-20' : 'w-64'" class="hidden lg:flex lg:flex-col bg-gray-900 border-r border-gray-800 transition-all duration-300">
                <!-- Logo & Toggle -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-800">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center overflow-hidden">
                        <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" x-show="!collapsed" x-transition class="h-14 w-auto object-contain">
                        <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" x-show="collapsed" x-transition class="h-10 w-10 object-contain">
                    </a>
                    <button @click="collapsed = !collapsed" class="p-1 hover:bg-gray-800 rounded transition flex-shrink-0" :title="collapsed ? 'Expandir' : 'Colapsar'">
                        <svg class="w-5 h-5 text-gray-400 hover:text-white transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <!-- Home -->
                    <a href="{{ route('dashboard') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Home' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span x-show="!collapsed" x-transition class="whitespace-nowrap">Home</span>
                    </a>

                    <!-- Librería (Todas las clases) -->
                    <a href="{{ route('lessons.index', ['nivel' => 'all', 'buscar' => '']) }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('lessons.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Librería' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span x-show="!collapsed" x-transition class="whitespace-nowrap">Librería</span>
                    </a>

                    <!-- Guardados -->
                    <a href="{{ route('saved.index') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('saved.*') || request()->routeIs('history.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Guardados' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        <span x-show="!collapsed" x-transition class="whitespace-nowrap">Guardados</span>
                    </a>

                    <!-- Mi Perfil -->
                    <a href="{{ route('profile') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('profile') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Mi Perfil' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span x-show="!collapsed" x-transition class="whitespace-nowrap">Mi Perfil</span>
                    </a>
                </nav>

                <!-- Bottom Section -->
                <div class="px-4 py-4 border-t border-gray-800">
                    <!-- User Info (when expanded) -->
                    <div x-show="!collapsed" x-transition class="mb-4">
                        <div class="flex items-center space-x-3 p-2 bg-gray-800 rounded-lg">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Cerrar Sesión' : ''">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span x-show="!collapsed" x-transition class="whitespace-nowrap">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Mobile Top Bar with Notifications (Not on Dashboard, Courses, or Lessons) -->
                @if(!request()->routeIs('dashboard') && !request()->routeIs('courses.*') && !request()->routeIs('lessons.*'))
                    <div class="lg:hidden bg-white border-b border-gray-light sticky top-0 z-40">
                        <div class="flex items-center justify-between px-4 py-3">
                            <a href="{{ route('dashboard') }}" wire:navigate>
                                <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-10 w-auto object-contain">
                            </a>
                            <div class="flex items-center space-x-3">
                                <!-- Notifications Icon -->
                                @livewire('student.notifications')
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Desktop Top Bar with Notifications -->
                <div class="hidden lg:flex items-center justify-between h-16 px-6 bg-white border-b border-gray-light">
                    <div class="flex-1">
                        <!-- Search or breadcrumbs can go here -->
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        @livewire('student.notifications')
                    </div>
                </div>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50 {{ request()->routeIs('courses.show') ? 'pb-0' : 'pb-20 lg:pb-0' }}">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Bottom Navigation -->
        @if(!request()->routeIs('courses.show'))
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-light shadow-lg z-50">
            <div class="flex justify-around items-center px-2 py-3">
                <!-- Home -->
                <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 space-y-1 {{ request()->routeIs('dashboard') ? 'text-purple-primary' : 'text-gray-dark' }} hover:text-purple-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs font-medium">Home</span>
                </a>

                <!-- Librería -->
                <a href="{{ route('lessons.index', ['nivel' => 'all', 'buscar' => '']) }}" wire:navigate class="flex flex-col items-center justify-center flex-1 space-y-1 {{ request()->routeIs('lessons.*') ? 'text-purple-primary' : 'text-gray-dark' }} hover:text-purple-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-xs font-medium">Librería</span>
                </a>

                <!-- Guardados -->
                <a href="{{ route('saved.index') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 space-y-1 {{ request()->routeIs('saved.*') || request()->routeIs('history.*') ? 'text-purple-primary' : 'text-gray-dark' }} hover:text-purple-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                    <span class="text-xs font-medium">Guardados</span>
                </a>

                <!-- Mi Perfil -->
                <a href="{{ route('profile') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 space-y-1 {{ request()->routeIs('profile') ? 'text-purple-primary' : 'text-gray-dark' }} hover:text-purple-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-xs font-medium">Mi Perfil</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>
