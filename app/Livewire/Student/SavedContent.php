<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.student')]
#[Title('Guardados - Girls Lockers')]
class SavedContent extends Component
{
    public function render()
    {
        // Get user's liked lessons
        $savedLessons = auth()->user()->likes()
            ->with(['module.course', 'instructor', 'tags'])
            ->withCount('likes')
            ->orderBy('lesson_likes.created_at', 'desc')
            ->get();

        return view('livewire.student.saved-content', [
            'savedLessons' => $savedLessons,
        ]);
    }
}
