<?php

namespace App\Livewire\Student;

use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
#[Title('Instructores - Girls Lockers')]
class InstructorCatalog extends Component
{
    #[Url(as: 'instructor')]
    public $selectedInstructorId = null;

    public function selectInstructor($instructorId)
    {
        $this->selectedInstructorId = $instructorId;
    }

    public function clearSelection()
    {
        $this->selectedInstructorId = null;
    }

    public function render()
    {
        $instructors = Instructor::withCount('lessons')
            ->orderBy('name')
            ->get();

        $selectedInstructor = null;
        $instructorLessons = collect();
        $instructorCourses = collect();

        if ($this->selectedInstructorId) {
            $user = auth()->user();

            $selectedInstructor = Instructor::withCount('lessons')->find($this->selectedInstructorId);

            if ($selectedInstructor) {
                // Get instructor's lessons
                $instructorLessons = Lesson::with(['module.course', 'tags'])
                    ->where('instructor_id', $selectedInstructor->id)
                    ->accessibleBy($user)
                    ->whereHas('module.course', function ($q) {
                        $q->where('is_published', true);
                    })
                    ->withCount('likes')
                    ->withExists(['likes as is_liked' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }])
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Get instructor's courses
                $instructorCourses = Course::where('instructor_id', $selectedInstructor->id)
                    ->where('is_published', true)
                    ->withCount('modules')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('livewire.student.instructor-catalog', [
            'instructors' => $instructors,
            'selectedInstructor' => $selectedInstructor,
            'instructorLessons' => $instructorLessons,
            'instructorCourses' => $instructorCourses,
        ]);
    }

    public function toggleLike($lessonId)
    {
        $user = auth()->user();
        $lesson = Lesson::findOrFail($lessonId);

        if ($lesson->isLikedBy($user)) {
            // Remove like
            $user->likes()->detach($lessonId);
            $lesson->decrementLikes();
        } else {
            // Add like
            $user->likes()->attach($lessonId, ['created_at' => now()]);
            $lesson->incrementLikes();
        }
    }
}
