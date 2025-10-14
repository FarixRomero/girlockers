<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.student')]
#[Title('Historial - Girls Lockers')]
class WatchHistory extends Component
{
    public function render()
    {
        // Get user's commented lessons (as a proxy for watch history)
        $watchedLessons = auth()->user()->comments()
            ->with(['lesson.module.course', 'lesson.instructor'])
            ->latest()
            ->get()
            ->pluck('lesson')
            ->unique('id');

        return view('livewire.student.watch-history', [
            'watchedLessons' => $watchedLessons,
        ]);
    }
}
