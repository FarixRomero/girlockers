<?php

namespace App\Livewire\Landing;

use App\Models\Course;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        $featuredCourses = Course::where('is_published', true)
            ->with('modules')
            ->take(3)
            ->get();

        return view('livewire.landing.home-page', [
            'featuredCourses' => $featuredCourses,
        ])->layout('layouts.master');
    }
}
