<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use App\Livewire\Traits\HasSearchableQueries;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Moderación de Comentarios - Admin')]
class CommentModeration extends Component
{
    use WithPagination, HasSearchableQueries;

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $userName = $comment->user->name;

        $comment->delete();

        session()->flash('success', "Comentario de {$userName} eliminado exitosamente");
    }

    public function render()
    {
        $query = Comment::with(['user', 'lesson.module.course']);

        // Apply search using HasSearchableQueries trait
        $query = $this->applySearch($query, ['content', 'user.name']);

        $comments = $query->latest()->paginate(20);

        $stats = [
            'total' => Comment::count(),
            'today' => Comment::whereDate('created_at', today())->count(),
        ];

        return view('livewire.admin.comment-moderation', [
            'comments' => $comments,
            'stats' => $stats,
        ]);
    }
}
