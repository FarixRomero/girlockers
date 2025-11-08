<div class="pb-20 lg:pb-0">
    <x-slot name="header">
        Gestión de Estudiantes
    </x-slot>

    <!-- Success Message -->
    @if(session()->has('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
            <p class="text-green-400 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- Users Management -->
    <!-- Stats - Mobile: Compact horizontal scroll, Desktop: Grid -->
    <div class="mb-6 overflow-x-auto scrollbar-hide">
        <div class="flex md:grid md:grid-cols-4 gap-2 md:gap-4 pb-2">
            <div class="card-premium min-w-[110px] md:min-w-0 flex-shrink-0 py-3 px-4">
                <div class="text-xs md:text-sm mb-0.5 md:mb-1 text-gray-500">Total</div>
                <div class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
            </div>
            <div class="card-premium min-w-[110px] md:min-w-0 flex-shrink-0 py-3 px-4">
                <div class="text-xs md:text-sm mb-0.5 md:mb-1 text-gray-500">Completo</div>
                <div class="text-lg md:text-2xl font-bold text-green-600">{{ $stats['premium'] }}</div>
            </div>
            <div class="card-premium min-w-[110px] md:min-w-0 flex-shrink-0 py-3 px-4">
                <div class="text-xs md:text-sm mb-0.5 md:mb-1 text-gray-500">Prueba</div>
                <div class="text-lg md:text-2xl font-bold text-orange-500">{{ $stats['trial'] }}</div>
            </div>
            <div class="card-premium min-w-[110px] md:min-w-0 flex-shrink-0 py-3 px-4">
                <div class="text-xs md:text-sm mb-0.5 md:mb-1 text-gray-500">Pendientes</div>
                <div class="text-lg md:text-2xl font-bold text-purple-600">{{ $stats['pending'] }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-premium mb-4 md:mb-6 p-3 md:p-8">
        <div class="flex flex-col md:flex-row gap-2 md:gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar nombre o email..."
                    class="w-full text-sm md:text-base px-3 py-2 md:px-4 md:py-2">
            </div>

            <!-- Access Filter -->
            <div class="md:min-w-[200px]">
                <select
                    wire:model.live="filterAccess"
                    class="w-full text-sm md:text-base px-3 py-2 md:px-4 md:py-2">
                    <option value="all">Todos</option>
                    <option value="premium">Acceso Completo</option>
                    <option value="trial">Solo Prueba</option>
                    <option value="pending">Con Solicitud Pendiente</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="space-y-3 md:space-y-0">
        <!-- Mobile View -->
        <div class="block md:hidden space-y-3">
            @forelse($students as $student)
                <div class="card-premium p-3" wire:key="student-mobile-{{ $student->id }}">
                    <div class="flex items-start justify-between gap-3">
                        <!-- Left: Student Info -->
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $student->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $student->email }}</p>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                @if($student->has_full_access)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                        ✓ Completo
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-600 text-xs rounded-full font-medium">
                                        Prueba
                                    </span>
                                @endif
                                @if($student->pending_requests_count > 0)
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-600 text-xs rounded-full">
                                        Pendiente
                                    </span>
                                @endif

                                @if($student->has_full_access && $student->membership_expires_at)
                                    @if($student->isMembershipExpired())
                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">
                                            ⏰ Expirado
                                        </span>
                                    @elseif($student->isMembershipExpiringSoon())
                                        @php $days = $student->getDaysUntilExpiration(); @endphp
                                        <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                            ⏰ {{ $days }} {{ $days == 1 ? 'día' : 'días' }}
                                        </span>
                                    @else
                                        @php $days = $student->getDaysUntilExpiration(); @endphp
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">
                                            ⏰ {{ $days }} {{ $days == 1 ? 'día' : 'días' }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                            @if($student->has_full_access && $student->membership_expires_at)
                                <div class="mt-2 text-xs text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Desde: {{ $student->access_granted_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 {{ $student->membership_expires_at->isPast() ? 'text-red-600' : ($student->membership_expires_at->diffInDays(now()) <= 7 ? 'text-orange-600' : '') }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Expira: {{ $student->membership_expires_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Action Buttons -->
                        <div class="flex-shrink-0 flex flex-col gap-1">
                            @if($student->has_full_access)
                                <button
                                    wire:click="showApproveModal({{ $student->id }})"
                                    class="px-3 py-1.5 bg-blue-100 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-200 transition active:scale-95 whitespace-nowrap">
                                    + Tiempo
                                </button>
                                <button
                                    wire:click="revokeAccess({{ $student->id }})"
                                    wire:confirm="¿Estás seguro de revocar el acceso completo a {{ $student->name }}?"
                                    class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-xs font-medium hover:bg-red-200 transition active:scale-95 whitespace-nowrap">
                                    Revocar
                                </button>
                            @else
                                <button
                                    wire:click="showApproveModal({{ $student->id }})"
                                    class="px-3 py-2 bg-green-100 text-green-600 rounded-lg text-xs font-medium hover:bg-green-200 transition active:scale-95 whitespace-nowrap">
                                    Dar Acceso
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card-premium py-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-gray-500">No se encontraron estudiantes</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop View (Table) -->
        <div class="hidden md:block card-premium overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-4 px-4 text-gray-500 font-medium text-sm">Estudiante</th>
                            <th class="text-left py-4 px-4 text-gray-500 font-medium text-sm">Estado</th>
                            <th class="text-left py-4 px-4 text-gray-500 font-medium text-sm">Membresía</th>
                            <th class="text-left py-4 px-4 text-gray-500 font-medium text-sm">Actividad</th>
                            <th class="text-left py-4 px-4 text-gray-500 font-medium text-sm">Registro</th>
                            <th class="text-right py-4 px-4 text-gray-500 font-medium text-sm">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="border-b border-gray-100 hover:bg-purple-50 transition" wire:key="student-desktop-{{ $student->id }}">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold mr-3">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-gray-900 font-medium">{{ $student->name }}</p>
                                            <p class="text-gray-500 text-sm">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($student->has_full_access)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold">
                                            ✓ Acceso Completo
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-orange-100 text-orange-600 text-xs rounded-full font-bold">
                                            Prueba
                                        </span>
                                    @endif
                                    @if($student->pending_requests_count > 0)
                                        <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-600 text-xs rounded-full">
                                            Solicitud pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($student->has_full_access && $student->membership_expires_at)
                                        <div class="text-sm">
                                            <div class="flex items-center gap-1 text-gray-700 mb-1">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-xs">Desde: {{ $student->access_granted_at->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 {{ $student->membership_expires_at->isPast() ? 'text-red-600' : ($student->membership_expires_at->diffInDays(now()) <= 7 ? 'text-orange-600' : 'text-gray-700') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-xs">Expira: {{ $student->membership_expires_at->format('d/m/Y') }}</span>
                                            </div>
                                            @if($student->membership_expires_at->isPast())
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">
                                                    Expirado
                                                </span>
                                            @elseif($student->membership_expires_at->diffInDays(now()) <= 7)
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full font-medium">
                                                    Por expirar
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3 text-xs text-gray-500">
                                        <span title="Comentarios">💬 {{ $student->comments_count }}</span>
                                        <span title="Likes">❤️ {{ $student->likes_count }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-gray-700 text-sm">{{ $student->created_at->format('d/m/Y') }}</span>
                                    <p class="text-gray-400 text-xs">{{ $student->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($student->has_full_access)
                                            <button
                                                wire:click="showApproveModal({{ $student->id }})"
                                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-sm hover:bg-blue-200 transition">
                                                Extender
                                            </button>
                                            <button
                                                wire:click="revokeAccess({{ $student->id }})"
                                                wire:confirm="¿Estás seguro de revocar el acceso completo a {{ $student->name }}?"
                                                class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-sm hover:bg-red-200 transition">
                                                Revocar
                                            </button>
                                        @else
                                            <button
                                                wire:click="showApproveModal({{ $student->id }})"
                                                class="px-3 py-1 bg-green-100 text-green-600 rounded-lg text-sm hover:bg-green-200 transition">
                                                Otorgar Acceso
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-gray-500">No se encontraron estudiantes</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="mt-6">
            {{ $students->links() }}
        </div>
    @endif

    <!-- Membership Selection Modal -->
    <div x-data="{ show: @entangle('showMembershipModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
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
                    <h3 class="text-xl font-bold text-gray-900">Otorgar Acceso Premium</h3>
                    <button @click="show = false" class="p-1 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <p class="text-gray-600 mb-6">Selecciona la duración de la membresía:</p>

                    <div class="space-y-3">
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
                                    <span class="font-semibold text-gray-900">1 Mes</span>
                                    <span class="text-sm text-gray-600">30 días</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Membresía mensual</p>
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
                                    <span class="font-semibold text-gray-900">3 Meses</span>
                                    <span class="text-sm text-purple-600 font-bold">¡Recomendado!</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Membresía trimestral (90 días)</p>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Nota:</strong> El acceso se otorgará inmediatamente y expirará según la duración seleccionada.
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
                        wire:click="confirmApproval"
                        wire:loading.attr="disabled"
                        class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-lg hover:from-green-700 hover:to-emerald-700 transition disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="confirmApproval">Otorgar Acceso</span>
                        <span wire:loading wire:target="confirmApproval">Otorgando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
