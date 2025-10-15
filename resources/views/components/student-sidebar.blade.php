<!-- Sidebar -->
<aside x-data="{ collapsed: false }" :class="collapsed ? 'w-20' : 'w-64'" class="hidden lg:flex lg:flex-col bg-gray-900 border-r border-gray-800 transition-all duration-300">
    <!-- Logo & Toggle -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-800">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-2 overflow-hidden">
            <div class="flex items-center space-x-1 flex-shrink-0">
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
            </div>
            <span x-show="!collapsed" x-transition class="font-script text-xl font-bold text-white tracking-wider whitespace-nowrap">Girls Lockers</span>
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

        <!-- Clases (Lecciones) -->
        <a href="{{ route('lessons.index') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('lessons.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Clases' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-show="!collapsed" x-transition class="whitespace-nowrap">Clases</span>
        </a>

        <!-- Cursos -->
        <a href="{{ route('courses.index') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('courses.*') && !request()->routeIs('lessons.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Cursos' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <span x-show="!collapsed" x-transition class="whitespace-nowrap">Cursos</span>
        </a>

        <!-- Instructores -->
        <a href="{{ route('instructors.index') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('instructors.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Instructores' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span x-show="!collapsed" x-transition class="whitespace-nowrap">Instructores</span>
        </a>

        <!-- Guardados -->
        <a href="{{ route('saved.index') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('saved.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Guardados' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            <span x-show="!collapsed" x-transition class="whitespace-nowrap">Guardados</span>
        </a>

        <!-- Historial -->
        <a href="{{ route('history.index') }}" wire:navigate class="sidebar-nav-item {{ request()->routeIs('history.*') ? 'active' : '' }}" :class="collapsed ? 'justify-center' : ''" :title="collapsed ? 'Historial' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-show="!collapsed" x-transition class="whitespace-nowrap">Historial</span>
        </a>
    </nav>

    <!-- Bottom Section - Followed Categories -->
    <div x-show="!collapsed" x-transition class="px-4 py-4 border-t border-gray-800">
        <!-- More From GIRLSLOCKERS -->
        <div class="mt-6 pt-4 border-t border-gray-800">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Más de GIRLSLOCKERS</h3>
            <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                Blog
            </a>
        </div>
    </div>

    <!-- Collapsed Bottom Icons -->
    <div x-show="collapsed" x-transition class="px-4 py-4 border-t border-gray-800 space-y-2">
        <a href="#" class="flex items-center justify-center p-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition" title="Categorías">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </a>
        <a href="#" class="flex items-center justify-center p-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition" title="Blog">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
        </a>
    </div>
</aside>
