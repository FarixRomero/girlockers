<div class="bg-white">
    <!-- Comments Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            {{ $comments->count() }} {{ $comments->count() === 1 ? 'comentario' : 'comentarios' }}
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
        <div class="flex gap-4">
            <!-- User Avatar -->
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>

            <!-- Comment Input -->
            <div class="flex-1">
                <form wire:submit="postComment" class="space-y-3" x-data="{ commentText: '{{ $content }}' }">
                    <textarea
                        wire:model="content"
                        id="comment-content"
                        rows="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition"
                        placeholder="Añade un comentario..."
                        onfocus="this.rows=4"
                        onblur="if(this.value === '') this.rows=1"
                        x-model="commentText"
                        @input="$wire.set('content', commentText)"></textarea>

                    @error('content')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Action Buttons - YouTube Style -->
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center gap-2">
                            <!-- Character counter -->
                            <span x-text="commentText.length + '/1000'" class="text-gray-500 text-xs" :class="commentText.length > 1000 ? 'text-red-500' : ''"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Cancel button (shown when there's content) -->
                            <button
                                x-show="commentText !== ''"
                                type="button"
                                @click="commentText = ''; $wire.set('content', '')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-full transition">
                                Cancelar
                            </button>
                            <!-- Comment button - YouTube style -->
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-full transition disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center gap-2"
                                :disabled="commentText === '' || commentText.length > 1000">
                                <span wire:loading.remove wire:target="postComment">Comentar</span>
                                <span wire:loading wire:target="postComment" class="flex items-center">
                                    <svg class="animate-spin h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Publicando...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Comments List -->
    <div class="space-y-6">
        @forelse($comments as $comment)
            <div class="flex gap-4" wire:key="comment-{{ $comment->id }}">
                <!-- User Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                </div>

                <!-- Comment Content -->
                <div class="flex-1 min-w-0">
                    @if($editingCommentId === $comment->id)
                        <!-- Edit Form -->
                        <form wire:submit="updateComment({{ $comment->id }})" class="space-y-3">
                            <textarea
                                wire:model="editingContent"
                                rows="3"
                                class="w-full border border-gray-300 focus:border-gray-900 focus:border-2 rounded px-3 py-2 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 resize-none text-sm"></textarea>
                            @error('editingContent')
                                <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                            <div class="flex items-center gap-2">
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-full transition">
                                    Guardar
                                </button>
                                <button
                                    type="button"
                                    wire:click="cancelEditing"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-full transition">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Comment Header -->
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-medium text-sm text-gray-900">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            @if($comment->created_at != $comment->updated_at)
                                <span class="text-xs text-gray-500">(editado)</span>
                            @endif
                        </div>

                        <!-- Comment Text -->
                        <p class="text-sm text-gray-900 leading-relaxed mb-2 whitespace-pre-wrap">{{ $comment->content }}</p>

                        <!-- Comment Actions -->
                        <div class="flex items-center gap-2">
                            @if(auth()->id() === $comment->user_id && $editingCommentId !== $comment->id)
                                <button
                                    wire:click="startEditing({{ $comment->id }}, '{{ addslashes($comment->content) }}')"
                                    class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-full transition"
                                    title="Editar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar
                                </button>
                            @endif

                            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                                <button
                                    wire:click="deleteComment({{ $comment->id }})"
                                    wire:confirm="¿Estás seguro de que quieres eliminar este comentario?"
                                    class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-full transition"
                                    title="Eliminar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Eliminar
                                </button>
                            @endif
                        </div>
                    @endif
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
