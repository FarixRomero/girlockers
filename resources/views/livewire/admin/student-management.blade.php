<div>
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

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Total Estudiantes</div>
            <div class="text-2xl font-bold text-cream">{{ $stats['total'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Acceso Completo</div>
            <div class="text-2xl font-bold text-green-400">{{ $stats['premium'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Solo Prueba</div>
            <div class="text-2xl font-bold text-orange-400">{{ $stats['trial'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Solicitudes Pendientes</div>
            <div class="text-2xl font-bold text-pink-vibrant">{{ $stats['pending'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-premium mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre o email..."
                    class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition">
            </div>

            <!-- Access Filter -->
            <div>
                <select
                    wire:model.live="filterAccess"
                    class="bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream focus:outline-none focus:border-pink-vibrant transition">
                    <option value="all">Todos</option>
                    <option value="premium">Acceso Completo</option>
                    <option value="trial">Solo Prueba</option>
                    <option value="pending">Con Solicitud Pendiente</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-pink-vibrant/20">
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Estudiante</th>
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Estado</th>
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Actividad</th>
                        <th class="text-left py-4 px-4 text-cream/70 font-medium text-sm">Registro</th>
                        <th class="text-right py-4 px-4 text-cream/70 font-medium text-sm">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr class="border-b border-pink-vibrant/10 hover:bg-purple-deep/50 transition" wire:key="student-{{ $student->id }}">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-pink flex items-center justify-center text-cream font-bold mr-3">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-cream font-medium">{{ $student->name }}</p>
                                        <p class="text-cream/60 text-sm">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($student->has_full_access)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full font-bold">
                                        ✓ Acceso Completo
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full font-bold">
                                        Prueba
                                    </span>
                                @endif
                                @if($student->access_requests_count > 0)
                                    <span class="ml-2 px-2 py-1 bg-pink-vibrant/20 text-pink-vibrant text-xs rounded-full">
                                        Solicitud pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3 text-xs text-cream/60">
                                    <span title="Comentarios">💬 {{ $student->comments_count }}</span>
                                    <span title="Likes">❤️ {{ $student->likes_count }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-cream/70 text-sm">{{ $student->created_at->format('d/m/Y') }}</span>
                                <p class="text-cream/50 text-xs">{{ $student->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="py-4 px-4 text-right">
                                @if($student->has_full_access)
                                    <button
                                        wire:click="revokeAccess({{ $student->id }})"
                                        wire:confirm="¿Estás seguro de revocar el acceso completo a {{ $student->name }}?"
                                        class="px-3 py-1 bg-red-500/20 text-red-400 rounded-lg text-sm hover:bg-red-500/30 transition">
                                        Revocar Acceso
                                    </button>
                                @else
                                    <button
                                        wire:click="approveAccess({{ $student->id }})"
                                        wire:confirm="¿Otorgar acceso completo a {{ $student->name }}?"
                                        class="px-3 py-1 bg-green-500/20 text-green-400 rounded-lg text-sm hover:bg-green-500/30 transition">
                                        Otorgar Acceso
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-cream/70">No se encontraron estudiantes</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="mt-6">
            {{ $students->links() }}
        </div>
    @endif
</div>
