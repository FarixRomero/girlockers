<?php

namespace App\Livewire\Student;

use App\Models\Lesson;
use Livewire\Component;

class LikeButton extends Component
{
    public Lesson $lesson;
    public bool $isLiked = false;
    public int $likesCount = 0;

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->likesCount = $lesson->likes_count;
        $this->isLiked = $lesson->likes()->where('user_id', auth()->id())->exists();
    }

    public function toggleLike()
    {
        if ($this->isLiked) {
            // Unlike
            $this->lesson->likes()->detach(auth()->id());
            $this->lesson->decrement('likes_count');
            $this->likesCount--;
            $this->isLiked = false;
        } else {
            // Like
            $this->lesson->likes()->attach(auth()->id(), [
                'created_at' => now(),
            ]);
            $this->lesson->increment('likes_count');
            $this->likesCount++;
            $this->isLiked = true;
        }

        // Dispatch event for potential analytics
        $this->dispatch('like-toggled', lessonId: $this->lesson->id, isLiked: $this->isLiked);
    }

    public function render()
    {
        return view('livewire.student.like-button');
    }
}
