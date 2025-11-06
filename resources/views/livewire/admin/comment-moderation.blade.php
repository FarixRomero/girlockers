<div class="pb-20 lg:pb-0">
    <x-slot name="header">
        Moderación de Comentarios
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Total Comentarios</div>
            <div class="text-2xl font-bold text-cream">{{ $stats['total'] }}</div>
        </div>
        <div class="card-premium">
            <div class="text-cream/70 text-sm mb-1">Comentarios Hoy</div>
            <div class="text-2xl font-bold text-pink-vibrant">{{ $stats['today'] }}</div>
        </div>
    </div>

    <!-- Search -->
    <div class="card-premium mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar comentarios o usuarios..."
            class="w-full bg-purple-deeper border border-pink-vibrant/20 rounded-lg px-4 py-2 text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition">
    </div>

    <!-- Comments List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="card-premium" wire:key="comment-{{ $comment->id }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center flex-1">
                        <div class="w-10 h-10 rounded-full bg-gradient-pink flex items-center justify-center text-cream font-bold mr-3">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-cream font-medium">{{ $comment->user->name }}</p>
                            <p class="text-cream/60 text-xs">{{ $comment->created_at->format('d/m/Y H:i') }} ({{ $comment->created_at->diffForHumans() }})</p>
                        </div>
                    </div>
                    <button
                        wire:click="deleteComment({{ $comment->id }})"
                        wire:confirm="¿Estás seguro de eliminar este comentario?"
                        class="text-red-400 hover:text-red-300 transition p-2"
                        title="Eliminar comentario">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="bg-purple-deeper rounded-lg p-4 mb-3">
                    <p class="text-cream/90 whitespace-pre-wrap">{{ $comment->content }}</p>
                </div>

                <a href="{{ route('lessons.show', $comment->lesson) }}" wire:navigate class="inline-flex items-center text-pink-vibrant hover:text-pink-light text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $comment->lesson->module->course->title }} - {{ $comment->lesson->title }}
                </a>
            </div>
        @empty
            <div class="card-premium text-center py-12">
                <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-cream/70">No se encontraron comentarios</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($comments->hasPages())
        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    @endif
</div>
