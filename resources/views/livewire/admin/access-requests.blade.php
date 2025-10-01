<div>
    <x-slot name="header">
        Solicitudes de Acceso
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Pendientes</div>
            <div class="text-2xl font-bold text-orange-400">{{ $stats['pending'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Aprobadas</div>
            <div class="text-2xl font-bold text-green-400">{{ $stats['approved'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Rechazadas</div>
            <div class="text-2xl font-bold text-red-400">{{ $stats['rejected'] }}</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-premium mb-6">
        <div class="flex items-center space-x-2">
            <button
                wire:click="$set('statusFilter', 'pending')"
                class="px-4 py-2 rounded-lg transition {{ $statusFilter === 'pending' ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                Pendientes
            </button>
            <button
                wire:click="$set('statusFilter', 'approved')"
                class="px-4 py-2 rounded-lg transition {{ $statusFilter === 'approved' ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                Aprobadas
            </button>
            <button
                wire:click="$set('statusFilter', 'rejected')"
                class="px-4 py-2 rounded-lg transition {{ $statusFilter === 'rejected' ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                Rechazadas
            </button>
            <button
                wire:click="$set('statusFilter', 'all')"
                class="px-4 py-2 rounded-lg transition {{ $statusFilter === 'all' ? 'bg-pink-vibrant text-cream' : 'text-cream/70 hover:bg-purple-deep' }}">
                Todas
            </button>
        </div>
    </div>

    <!-- Requests List -->
    <div class="space-y-4">
        @forelse($requests as $request)
            <div class="card-premium" wire:key="request-{{ $request->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start flex-1">
                        <div class="w-12 h-12 rounded-full bg-gradient-pink flex items-center justify-center text-cream font-bold mr-4">
                            {{ strtoupper(substr($request->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-display text-lg text-cream mb-1">{{ $request->user->name }}</h3>
                            <p class="text-cream/70 text-sm mb-2">{{ $request->user->email }}</p>

                            <div class="flex flex-wrap gap-4 text-sm text-cream/60">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Solicitado: {{ $request->created_at->format('d/m/Y H:i') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Registrado: {{ $request->user->created_at->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="mt-3">
                                @if($request->status === 'pending')
                                    <span class="px-3 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full font-bold">
                                        ⏳ Pendiente
                                    </span>
                                @elseif($request->status === 'approved')
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full font-bold">
                                        ✓ Aprobada
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/20 text-red-400 text-xs rounded-full font-bold">
                                        ✗ Rechazada
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($request->status === 'pending')
                        <div class="flex items-center space-x-2 ml-4">
                            <button
                                wire:click="approveRequest({{ $request->id }})"
                                wire:confirm="¿Aprobar solicitud de {{ $request->user->name }}?"
                                class="btn-primary text-sm px-4 py-2">
                                Aprobar
                            </button>
                            <button
                                wire:click="rejectRequest({{ $request->id }})"
                                wire:confirm="¿Rechazar solicitud de {{ $request->user->name }}?"
                                class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg text-sm hover:bg-red-500/30 transition">
                                Rechazar
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card-premium text-center py-12">
                <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-cream/70">No hay solicitudes {{ $statusFilter !== 'all' ? 'con estado: ' . $statusFilter : '' }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    @endif
</div>
