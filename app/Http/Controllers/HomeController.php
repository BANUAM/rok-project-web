<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\TeamMember;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroSlides = HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $teamMembers = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('welcome', compact(
            'heroSlides',
            'teamMembers'
        ));
    }
}
