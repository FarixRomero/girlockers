<div class="min-h-screen bg-gray-50 pb-20 lg:pb-8">
    <x-slot name="header">
        Moderación de Comentarios
    </x-slot>

    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <h1 class="font-bold text-xl md:text-2xl text-gray-900 mb-1">Comentarios</h1>
            <p class="text-gray-600 text-sm">{{ $stats['total'] }} total • {{ $stats['today'] }} hoy</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6">
        <!-- Success Message -->
        @if(session()->has('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl p-3 mb-4 border border-gray-200">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar comentarios..."
                class="w-full bg-transparent border-0 focus:outline-none focus:ring-0 text-sm text-gray-900 placeholder-gray-400">
        </div>

        <!-- Comments List -->
        <div class="space-y-3">
            @forelse($comments as $comment)
                <div class="bg-white rounded-2xl p-4 border border-gray-100" wire:key="comment-{{ $comment->id }}">
                    <!-- User Info & Delete Button -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm mr-3 flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-gray-900 font-semibold text-sm truncate">{{ $comment->user->name }}</p>
                                <p class="text-gray-500 text-xs">
                                    {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <button
                            wire:click="deleteComment({{ $comment->id }})"
                            wire:confirm="¿Estás seguro de eliminar este comentario?"
                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0"
                            title="Eliminar comentario">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Comment Content -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <p class="text-gray-900 text-sm whitespace-pre-wrap">{{ $comment->content }}</p>
                    </div>

                    <!-- Lesson Link -->
                    <a href="{{ route('lessons.show', $comment->lesson) }}" wire:navigate class="inline-flex items-center text-purple-600 hover:text-purple-700 text-sm transition">
                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="truncate font-medium">{{ $comment->lesson->title }}</span>
                    </a>
                </div>
            @empty
                <div class="bg-white rounded-2xl text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No se encontraron comentarios</p>
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
</div>
