<div>
    <!-- Comments Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            Comentarios ({{ $comments->count() }})
        </h2>
    </div>

    <!-- Success Message -->
    @if(session()->has('comment-success'))
        <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-700 text-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('comment-success') }}
            </p>
        </div>
    @endif

    <!-- Comment Form -->
    <div class="mb-8">
        <div class="flex gap-3">
            <!-- User Avatar -->
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>

            <!-- Comment Input -->
            <div class="flex-1">
                <form wire:submit="postComment" x-data="{ focused: false }">
                    <div class="relative">
                        <input
                            wire:model="content"
                            type="text"
                            class="w-full bg-gray-100 rounded-full px-5 py-3 text-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="Añade un comentario..."
                            @focus="focused = true"
                            @blur="if($wire.content === '') focused = false">

                        <!-- Send Button -->
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-purple-600 hover:bg-purple-700 flex items-center justify-center text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!$wire.content">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>

                    @error('content')
                        <p class="text-red-600 text-xs mt-2 ml-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        </div>
    </div>

    <!-- Comments List -->
    <div class="space-y-6">
        @forelse($comments as $comment)
            <div class="flex gap-3" wire:key="comment-{{ $comment->id }}">
                <!-- User Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                </div>

                <!-- Comment Content -->
                <div class="flex-1 min-w-0">
                    <!-- Comment Header -->
                    <div class="mb-1">
                        <span class="font-bold text-sm text-gray-900">{{ $comment->user->name }}</span>
                    </div>

                    <!-- Comment Text -->
                    <p class="text-sm text-gray-600 leading-relaxed mb-2">{{ $comment->content }}</p>

                    <!-- Comment Actions -->
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span>Hace {{ $comment->created_at->diffForHumans(null, true) }}</span>

                        @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                            <button
                                wire:click="deleteComment({{ $comment->id }})"
                                wire:confirm="¿Estás seguro de que quieres eliminar este comentario?"
                                class="hover:text-red-600 font-medium transition"
                                title="Eliminar">
                                Eliminar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-gray-600 font-medium mb-1">Sin comentarios aún</p>
                <p class="text-gray-500 text-sm">¡Sé la primera en comentar esta lección!</p>
            </div>
        @endforelse
    </div>
</div>
