<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin - {{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('images/girlslockers.jpg') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body antialiased bg-purple-darkest">
        <div class="min-h-screen flex">
            <!-- Sidebar (Desktop) -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-purple-darker border-r border-pink-vibrant/20">
                <div class="p-6">
                    <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="w-full mb-2">
                    <p class="text-cream/60 text-sm text-center">Admin Panel</p>
                </div>

                <nav class="flex-1 px-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.courses.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.modules.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Cursos
                    </a>

                    <a href="{{ route('admin.instructors.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.instructors.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Instructores
                    </a>

                    <a href="{{ route('admin.tags.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.tags.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Tags
                    </a>

                    <a href="{{ route('admin.users.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Usuarios
                    </a>

                    <a href="{{ route('admin.comments.index') }}" wire:navigate class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.comments.*') ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Comentarios
                    </a>
                </nav>

                <div class="p-4 border-t border-pink-vibrant/20 space-y-2">
                    <!-- User Info -->
                    <div class="flex items-center px-4 py-3 bg-purple-deep/50 rounded-lg">
                        <div class="w-10 h-10 bg-gradient-pink rounded-full flex items-center justify-center text-cream font-bold mr-3 flex-shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-cream font-medium text-sm truncate">{{ auth()->user()->name }}</p>
                            <p class="text-cream/50 text-xs truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 text-cream/70 hover:text-pink-vibrant rounded-lg hover:bg-purple-deep transition-colors">
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
                <!-- Top Navbar -->
                @if(!request()->routeIs('admin.lessons.create-new'))
                    <div class="bg-purple-darker border-b border-pink-vibrant/20 px-4 lg:px-8 py-3 flex items-center justify-between">
                        <!-- Left: Nueva Lección -->
                        <a href="{{ route('admin.lessons.create-global') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 bg-pink-vibrant hover:bg-pink-light text-cream font-semibold rounded-lg transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="hidden md:inline">Nueva Lección</span>
                        </a>

                        <!-- Center: Girls Lockers Logo (solo en mobile) -->
                        <div class="lg:hidden">
                            <img src="{{ asset('images/girls_lockers_logo.png') }}" alt="Girls Lockers" class="h-12">
                        </div>

                        <!-- Right: Notifications -->
                        @livewire('admin.notification-bell')
                    </div>
                @endif

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto {{ request()->routeIs('admin.lessons.create-new') ? 'p-0' : 'p-4 lg:p-8' }}">
                    @yield('content', $slot ?? '')
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
                    <a href="{{ route('admin.comments.index') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="nav-item">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full h-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
