<?php

namespace App\Livewire\Landing;

use App\Models\Course;
use App\Models\LandingConfig;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        $featuredCourses = Course::where('is_published', true)
            ->with('modules')
            ->take(3)
            ->get();

        // Load all landing configs
        $config = [];
        $allConfigs = LandingConfig::all();

        foreach ($allConfigs as $item) {
            $value = $item->value;

            // Decode JSON
            if ($item->type === 'json' && is_string($value)) {
                $value = json_decode($value, true);
            }

            $config[$item->key] = $value;
        }

        return view('livewire.landing.home-page', [
            'featuredCourses' => $featuredCourses,
            'config' => $config,
        ])->layout('layouts.master');
    }
}
