<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Mi Perfil</h1>
            <p class="text-gray-600">Administra tu información personal y configuración de cuenta</p>
        </div>

        <!-- Profile Information Section -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="p-6 md:p-8">
                <div class="mb-6">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        @if($user->hasFullAccess())
                            <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold rounded-full">
                                Premium
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-bold rounded-full">
                                Gratis
                            </span>
                        @endif
                        <span class="text-xs text-gray-500">
                            Miembro desde {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                <form wire:submit="updateProfile" class="space-y-6">
                    <!-- Success Message -->
                    @if (session()->has('profile-updated'))
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                            <p class="text-green-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('profile-updated') }}
                            </p>
                        </div>
                    @endif

                    <!-- Avatar Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Foto de Perfil</label>
                        <div class="flex items-start gap-4">
                            <!-- Preview -->
                            <div class="flex-shrink-0">
                                @if ($avatar)
                                    <img src="{{ $avatar->temporaryUrl() }}" class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                                @elseif($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" class="w-24 h-24 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-3xl font-bold border-2 border-gray-200">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Upload Button -->
                            <div class="flex-1">
                                <label for="avatar-upload" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Seleccionar imagen
                                </label>
                                <input
                                    type="file"
                                    id="avatar-upload"
                                    wire:model="avatar"
                                    accept="image/*"
                                    class="hidden"
                                >
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG o GIF. Tamaño máximo 10MB.</p>
                                @error('avatar') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror

                                <!-- Loading indicator -->
                                <div wire:loading wire:target="avatar" class="text-sm text-gray-600 mt-2 flex items-center">
                                    <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Cargando imagen...
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Nombre</label>
                            <input
                                wire:model="name"
                                type="text"
                                id="name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                required
                            >
                            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                            <input
                                wire:model="email"
                                type="email"
                                id="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                required
                            >
                            @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
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
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="p-6 md:p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Cambiar Contraseña</h3>
                <p class="text-gray-600 mb-6">Asegúrate de usar una contraseña segura para mantener tu cuenta protegida</p>

                <form wire:submit="updatePassword" class="space-y-6">
                    <!-- Success Message -->
                    @if (session()->has('password-updated'))
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                            <p class="text-green-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('password-updated') }}
                            </p>
                        </div>
                    @endif

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">Contraseña Actual</label>
                        <input
                            wire:model="current_password"
                            type="password"
                            id="current_password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            autocomplete="current-password"
                        >
                        @error('current_password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Nueva Contraseña</label>
                            <input
                                wire:model="password"
                                type="password"
                                id="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                autocomplete="new-password"
                            >
                            @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Confirmar Contraseña</label>
                            <input
                                wire:model="password_confirmation"
                                type="password"
                                id="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                autocomplete="new-password"
                            >
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition flex items-center gap-2">
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

        <!-- Account Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $user->likes()->count() }}</p>
                        <p class="text-sm text-gray-600">Clases Guardadas</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-pink-100 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $user->likes()->sum('duration') ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Minutos Totales</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $user->comments()->count() }}</p>
                        <p class="text-sm text-gray-600">Comentarios</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Membership Status (if premium user) -->
        @if($user->hasFullAccess() && $user->membership_expires_at)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="p-6 md:p-8">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Estado de Membresía</h3>
                        <p class="text-gray-600">Información sobre tu suscripción premium</p>
                    </div>
                    <div class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-sm font-bold rounded-lg">
                        {{ $user->membership_type === 'quarterly' ? 'Trimestral' : 'Mensual' }}
                    </div>
                </div>

                <!-- Renewal Messages -->
                @if(session()->has('renewal-success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <p class="text-green-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('renewal-success') }}
                        </p>
                    </div>
                @endif

                @if(session()->has('renewal-error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ session('renewal-error') }}
                        </p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Miembro desde</p>
                        <p class="text-lg font-bold text-gray-900">{{ $user->access_granted_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $user->access_granted_at->diffForHumans() }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Expira el</p>
                        <p class="text-lg font-bold text-gray-900">{{ $user->membership_expires_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $user->membership_expires_at->diffForHumans() }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Días restantes</p>
                        @php
                            // Calcular días restantes correctamente
                            if ($user->membership_expires_at->isFuture()) {
                                // Contar días completos desde hoy hasta la fecha de expiración
                                $daysRemaining = now()->startOfDay()->diffInDays($user->membership_expires_at->startOfDay());
                            } else {
                                $daysRemaining = 0;
                            }
                        @endphp
                        <p class="text-lg font-bold {{ $daysRemaining <= 7 && $daysRemaining > 0 ? 'text-orange-600' : ($daysRemaining == 0 ? 'text-red-600' : 'text-gray-900') }}">
                            {{ $daysRemaining }} días
                        </p>
                        @if($daysRemaining == 0)
                            <p class="text-xs text-red-600 mt-1 font-medium">❌ Expirada</p>
                        @elseif($daysRemaining <= 7)
                            <p class="text-xs text-orange-600 mt-1 font-medium">⚠️ Expira pronto</p>
                        @else
                            <p class="text-xs text-green-600 mt-1 font-medium">✓ Activa</p>
                        @endif
                    </div>
                </div>

                <!-- Timeline de membresía -->
                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-gray-700 font-medium">Inicio</span>
                            <span class="text-gray-500 ml-2">{{ $user->access_granted_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex-1 mx-4 relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t-2 border-dashed {{ $user->isMembershipExpired() ? 'border-red-300' : 'border-purple-300' }}"></div>
                            </div>
                            @php
                                $totalDays = max(1, ceil($user->access_granted_at->diffInDays($user->membership_expires_at)));
                                $daysElapsed = max(0, floor($user->access_granted_at->diffInDays(now())));
                                $progress = $totalDays > 0 ? min(100, round(($daysElapsed / $totalDays) * 100, 2)) : 0;
                            @endphp
                            <div class="relative flex justify-start">
                                <div class="w-4 h-4 rounded-full {{ $user->isMembershipExpired() ? 'bg-red-500' : 'bg-purple-500' }} border-2 border-white shadow" style="margin-left: {{ $progress }}%;"></div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 mr-2">{{ $user->membership_expires_at->format('d M Y') }}</span>
                            <span class="text-gray-700 font-medium">Fin</span>
                            <div class="w-3 h-3 rounded-full {{ $user->isMembershipExpired() ? 'bg-red-500' : 'bg-orange-500' }} ml-2"></div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <p class="text-xs text-gray-600">
                            @if($user->isMembershipExpired())
                                Tu membresía expiró hace {{ ceil(abs($user->membership_expires_at->diffInDays(now()))) }} días
                            @else
                                Llevas {{ $daysElapsed }} días de {{ $totalDays }} días totales ({{ round($progress, 1) }}%)
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Renewal Button -->
                @if($user->isMembershipExpiringSoon() || $user->isMembershipExpired())
                    @if($hasPendingRenewal)
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                            <svg class="w-12 h-12 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-yellow-800 font-semibold">Solicitud de renovación pendiente</p>
                            <p class="text-yellow-600 text-sm mt-1">El equipo te contactará pronto para procesar tu renovación</p>
                        </div>
                    @else
                        <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg text-center">
                            <svg class="w-12 h-12 text-purple-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">¿Deseas renovar tu membresía?</h4>
                            <p class="text-gray-600 mb-4">Continúa disfrutando de todo el contenido premium</p>
                            <button
                                wire:click="showRenewalForm"
                                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:from-purple-700 hover:to-pink-700 transition">
                                Solicitar Renovación
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @endif

        <!-- Upgrade CTA (if free user) -->
        @if(!$user->hasFullAccess())
        <div class="bg-gradient-to-br from-purple-600 via-pink-600 to-purple-700 rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="p-8 text-center text-white">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                <h3 class="text-2xl font-bold mb-2">Mejora a Premium</h3>
                <p class="text-white/90 mb-6">Desbloquea todo el contenido y accede a coreografías exclusivas</p>
                <a href="{{ route('request-access') }}" wire:navigate class="inline-flex items-center px-6 py-3 bg-white text-purple-600 font-bold rounded-lg hover:bg-gray-100 transition">
                    Solicitar Acceso Premium
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endif

        <!-- Logout Section -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Cerrar Sesión</h3>
                        <p class="text-sm text-gray-600">Termina tu sesión en este dispositivo</p>
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

    <!-- Renewal Modal -->
    <div x-data="{ show: @entangle('showRenewalModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Overlay -->
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900 bg-opacity-75"
            @click="show = false"
        ></div>

        <!-- Modal Panel -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative bg-white rounded-lg shadow-xl max-w-md w-full"
                @click.away="show = false"
            >
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Renovar Membresía</h3>
                    <button @click="show = false" class="p-1 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <p class="text-gray-600 mb-6">Selecciona el tipo de membresía que deseas renovar:</p>

                    <div class="space-y-3 mb-6">
                        <!-- Monthly Option -->
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ $selectedMembershipType === 'monthly' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input
                                type="radio"
                                wire:model="selectedMembershipType"
                                value="monthly"
                                class="w-4 h-4 text-purple-600 focus:ring-purple-500"
                            >
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-gray-900">Mensual</span>
                                    <span class="text-sm text-gray-600">1 mes</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Renovación por 30 días</p>
                            </div>
                        </label>

                        <!-- Quarterly Option -->
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ $selectedMembershipType === 'quarterly' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input
                                type="radio"
                                wire:model="selectedMembershipType"
                                value="quarterly"
                                class="w-4 h-4 text-purple-600 focus:ring-purple-500"
                            >
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-gray-900">Trimestral</span>
                                    <span class="text-sm text-purple-600 font-bold">¡Popular!</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Renovación por 90 días</p>
                            </div>
                        </label>
                    </div>

                    <!-- Phone Number Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Teléfono <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <!-- Country Code Selector -->
                            <select
                                wire:model="countryCode"
                                class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                                <option value="+51">🇵🇪 +51</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+52">🇲🇽 +52</option>
                                <option value="+54">🇦🇷 +54</option>
                                <option value="+55">🇧🇷 +55</option>
                                <option value="+56">🇨🇱 +56</option>
                                <option value="+57">🇨🇴 +57</option>
                                <option value="+58">🇻🇪 +58</option>
                                <option value="+593">🇪🇨 +593</option>
                                <option value="+34">🇪🇸 +34</option>
                                <option value="+44">🇬🇧 +44</option>
                            </select>

                            <!-- Phone Number Input -->
                            <input
                                type="tel"
                                wire:model="phoneNumber"
                                placeholder="999 999 999"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                        </div>
                        @error('phoneNumber')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">El equipo se pondrá en contacto contigo a este número</p>
                    </div>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Nota:</strong> El equipo de Girls Lockers se pondrá en contacto contigo para coordinar el pago y activar tu renovación.
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button
                        @click="show = false"
                        class="px-4 py-2 text-gray-700 font-medium hover:bg-gray-100 rounded-lg transition"
                    >
                        Cancelar
                    </button>
                    <button
                        wire:click="requestRenewal"
                        wire:loading.attr="disabled"
                        class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:from-purple-700 hover:to-pink-700 transition disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="requestRenewal">Enviar Solicitud</span>
                        <span wire:loading wire:target="requestRenewal">Enviando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
