<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Historial - Girls Lockers')]
class WatchHistory extends Component
{
    public function render()
    {
        // Get user's viewed lessons from lesson_views table
        $watchedLessons = auth()->user()->viewedLessons()
            ->with(['module.course', 'instructor', 'tags'])
            ->get();

        return view('livewire.student.watch-history', [
            'watchedLessons' => $watchedLessons,
        ]);
    }
}
