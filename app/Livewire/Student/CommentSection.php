<?php

namespace App\Livewire\Student;

use App\Models\Comment;
use App\Models\Lesson;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentSection extends Component
{
    use AuthorizesRequests;

    public Lesson $lesson;
    public $body = '';
    public $editingCommentId = null;
    public $editingBody = '';

    protected $rules = [
        'body' => 'required|string|min:3|max:1000',
    ];

    protected $messages = [
        'body.required' => 'El comentario no puede estar vacío.',
        'body.min' => 'El comentario debe tener al menos 3 caracteres.',
        'body.max' => 'El comentario no puede exceder 1000 caracteres.',
    ];

    public function mount(Lesson $lesson)
    {
        $this->authorize('comment', $lesson);
        $this->lesson = $lesson;
    }

    public function postComment()
    {
        $this->validate();

        Comment::create([
            'lesson_id' => $this->lesson->id,
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->dispatch('comment-posted');

        session()->flash('comment-success', 'Comentario publicado exitosamente.');
    }

    public function deleteComment(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        session()->flash('comment-success', 'Comentario eliminado.');
    }

    public function startEditing($commentId, $body)
    {
        $this->editingCommentId = $commentId;
        $this->editingBody = $body;
    }

    public function cancelEditing()
    {
        $this->editingCommentId = null;
        $this->editingBody = '';
    }

    public function updateComment(Comment $comment)
    {
        $this->authorize('update', $comment);

        $this->validate([
            'editingBody' => 'required|string|min:3|max:1000',
        ]);

        $comment->update([
            'body' => $this->editingBody,
        ]);

        $this->editingCommentId = null;
        $this->editingBody = '';

        session()->flash('comment-success', 'Comentario actualizado.');
    }

    public function render()
    {
        $comments = $this->lesson->comments()
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.student.comment-section', [
            'comments' => $comments,
        ]);
    }
}
