<?php

namespace App\Livewire\Student;

use App\Models\Instructor;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.student')]
#[Title('Instructores - Girls Lockers')]
class InstructorCatalog extends Component
{
    public function render()
    {
        $instructors = Instructor::withCount('lessons')
            ->orderBy('name')
            ->get();

        return view('livewire.student.instructor-catalog', [
            'instructors' => $instructors,
        ]);
    }
}
