<div>
    <h3 class="font-display text-xl text-cream mb-6 flex items-center">
        <svg class="w-6 h-6 mr-2 text-pink-vibrant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        Comentarios ({{ $comments->count() }})
    </h3>

    <!-- Success Message -->
    @if(session()->has('comment-success'))
        <div class="mb-4 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
            <p class="text-green-400 text-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('comment-success') }}
            </p>
        </div>
    @endif

    <!-- Comment Form -->
    <form wire:submit="postComment" class="mb-8">
        <div class="mb-4">
            <label for="comment-content" class="block text-cream/80 text-sm mb-2">Deja tu comentario</label>
            <textarea
                wire:model="content"
                id="comment-content"
                rows="4"
                class="w-full bg-purple-darker border border-pink-vibrant/20 rounded-lg px-4 py-3 text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition resize-none"
                placeholder="Comparte tus pensamientos sobre esta lección..."></textarea>
            @error('content')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <span class="text-cream/60 text-sm">
                {{ strlen($content) }}/1000 caracteres
            </span>
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="btn-primary">
                <span wire:loading.remove wire:target="postComment">Publicar Comentario</span>
                <span wire:loading wire:target="postComment" class="flex items-center">
                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Publicando...
                </span>
            </button>
        </div>
    </form>

    <!-- Comments List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="card-glass p-4" wire:key="comment-{{ $comment->id }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-pink flex items-center justify-center text-cream font-bold mr-3">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-cream font-medium">{{ $comment->user->name }}</p>
                            <p class="text-cream/60 text-xs">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                        <div class="flex items-center space-x-2">
                            @if(auth()->id() === $comment->user_id && $editingCommentId !== $comment->id)
                                <button
                                    wire:click="startEditing({{ $comment->id }}, '{{ addslashes($comment->content) }}')"
                                    class="text-cream/60 hover:text-pink-vibrant transition p-2"
                                    title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                            @endif

                            <button
                                wire:click="deleteComment({{ $comment->id }})"
                                wire:confirm="¿Estás seguro de que quieres eliminar este comentario?"
                                class="text-cream/60 hover:text-red-400 transition p-2"
                                title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                @if($editingCommentId === $comment->id)
                    <!-- Edit Form -->
                    <form wire:submit="updateComment({{ $comment->id }})" class="space-y-3">
                        <textarea
                            wire:model="editingContent"
                            rows="3"
                            class="w-full bg-purple-darker border border-pink-vibrant/20 rounded-lg px-3 py-2 text-cream placeholder-cream/40 focus:outline-none focus:border-pink-vibrant transition resize-none text-sm"></textarea>
                        @error('editingContent')
                            <p class="text-red-400 text-sm">{{ $message }}</p>
                        @enderror
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="px-4 py-2 bg-pink-vibrant text-cream rounded-lg text-sm font-medium hover:bg-pink-dark transition">
                                Guardar
                            </button>
                            <button
                                type="button"
                                wire:click="cancelEditing"
                                class="px-4 py-2 bg-purple-deep text-cream rounded-lg text-sm font-medium hover:bg-purple-darker transition">
                                Cancelar
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Comment Content -->
                    <p class="text-cream/90 leading-relaxed whitespace-pre-wrap">{{ $comment->content }}</p>

                    @if($comment->created_at != $comment->updated_at)
                        <p class="text-cream/50 text-xs mt-2 italic">(editado)</p>
                    @endif
                @endif
            </div>
        @empty
            <div class="card-glass text-center py-12">
                <svg class="w-16 h-16 text-cream/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-cream/70 mb-2">Aún no hay comentarios</p>
                <p class="text-cream/50 text-sm">¡Sé la primera en comentar!</p>
            </div>
        @endforelse
    </div>
</div>
