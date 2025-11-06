<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
#[Title('Guardados - Girls Lockers')]
class SavedContent extends Component
{
    #[Url]
    public string $tab = 'favoritos';

    public function render()
    {
        // Get user's liked lessons (Favoritos)
        $savedLessons = auth()->user()->likes()
            ->with(['module.course', 'instructor', 'tags'])
            ->withCount('likes')
            ->orderBy('lesson_likes.created_at', 'desc')
            ->get();

        // Get user's viewed lessons (Historial)
        $watchedLessons = auth()->user()->viewedLessons()
            ->with(['module.course', 'instructor', 'tags'])
            ->orderBy('lesson_views.created_at', 'desc')
            ->get();

        return view('livewire.student.saved-content', [
            'savedLessons' => $savedLessons,
            'watchedLessons' => $watchedLessons,
        ]);
    }
}
