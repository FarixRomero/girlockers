<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-cream mb-2">Gestión del Perfil</h1>
        <p class="text-cream/70">Administra tu información personal y configuración de cuenta</p>
    </div>

    <!-- Profile Information Section -->
    <div class="bg-purple-deeper rounded-lg border border-pink-vibrant/20 overflow-hidden mb-6">
        <div class="p-6 md:p-8">
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-pink flex items-center justify-center text-cream text-2xl md:text-3xl font-bold mr-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-xl md:text-2xl font-bold text-cream">{{ $user->name }}</h2>
                    <p class="text-cream/70">{{ $user->email }}</p>
                    <span class="inline-block px-3 py-1 mt-2 bg-pink-vibrant text-cream text-xs font-bold rounded-full">
                        Administrador
                    </span>
                </div>
            </div>

            <form wire:submit="updateProfile" class="space-y-6">
                <!-- Success Message -->
                @if (session()->has('profile-updated'))
                    <div class="p-4 bg-green-500/20 border border-green-500/30 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                        <p class="text-green-300 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('profile-updated') }}
                        </p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-cream mb-2">Nombre</label>
                        <input
                            wire:model="name"
                            type="text"
                            id="name"
                            class="w-full px-4 py-3 bg-purple-dark border border-pink-vibrant/30 rounded-lg text-cream placeholder-cream/50 focus:ring-2 focus:ring-pink-vibrant focus:border-transparent transition"
                            required
                        >
                        @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-cream mb-2">Email</label>
                        <input
                            wire:model="email"
                            type="email"
                            id="email"
                            class="w-full px-4 py-3 bg-purple-dark border border-pink-vibrant/30 rounded-lg text-cream placeholder-cream/50 focus:ring-2 focus:ring-pink-vibrant focus:border-transparent transition"
                            required
                        >
                        @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end pt-4 border-t border-pink-vibrant/20">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-3 bg-pink-vibrant text-cream font-semibold rounded-lg hover:bg-pink-light transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="updateProfile">Guardar Cambios</span>
                        <span wire:loading wire:target="updateProfile" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Guardando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Section -->
    <div class="bg-purple-deeper rounded-lg border border-pink-vibrant/20 overflow-hidden mb-6">
        <div class="p-6 md:p-8">
            <h3 class="text-xl font-bold text-cream mb-2">Cambiar Contraseña</h3>
            <p class="text-cream/70 mb-6">Asegúrate de usar una contraseña segura para mantener tu cuenta protegida</p>

            <form wire:submit="updatePassword" class="space-y-6">
                <!-- Success Message -->
                @if (session()->has('password-updated'))
                    <div class="p-4 bg-green-500/20 border border-green-500/30 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                        <p class="text-green-300 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('password-updated') }}
                        </p>
                    </div>
                @endif

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-cream mb-2">Contraseña Actual</label>
                    <input
                        wire:model="current_password"
                        type="password"
                        id="current_password"
                        class="w-full px-4 py-3 bg-purple-dark border border-pink-vibrant/30 rounded-lg text-cream placeholder-cream/50 focus:ring-2 focus:ring-pink-vibrant focus:border-transparent transition"
                        autocomplete="current-password"
                    >
                    @error('current_password') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-cream mb-2">Nueva Contraseña</label>
                        <input
                            wire:model="password"
                            type="password"
                            id="password"
                            class="w-full px-4 py-3 bg-purple-dark border border-pink-vibrant/30 rounded-lg text-cream placeholder-cream/50 focus:ring-2 focus:ring-pink-vibrant focus:border-transparent transition"
                            autocomplete="new-password"
                        >
                        @error('password') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-cream mb-2">Confirmar Contraseña</label>
                        <input
                            wire:model="password_confirmation"
                            type="password"
                            id="password_confirmation"
                            class="w-full px-4 py-3 bg-purple-dark border border-pink-vibrant/30 rounded-lg text-cream placeholder-cream/50 focus:ring-2 focus:ring-pink-vibrant focus:border-transparent transition"
                            autocomplete="new-password"
                        >
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end pt-4 border-t border-pink-vibrant/20">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-3 bg-pink-vibrant text-cream font-semibold rounded-lg hover:bg-pink-light transition flex items-center gap-2">
                        <span wire:loading.remove wire:target="updatePassword">Actualizar Contraseña</span>
                        <span wire:loading wire:target="updatePassword" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Actualizando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logout Section -->
    <div class="bg-purple-deeper rounded-lg border border-pink-vibrant/20 overflow-hidden">
        <div class="p-6 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-cream mb-1">Cerrar Sesión</h3>
                    <p class="text-sm text-cream/70">Termina tu sesión en este dispositivo</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
