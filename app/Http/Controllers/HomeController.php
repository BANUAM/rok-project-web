<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Division;
use App\Models\HeroSlide;
use App\Models\LearningModule;
use App\Models\Partner;
use App\Models\TeamMember;
use App\Models\Tournament;
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

        $divisions = Division::query()
            ->with(['activePlayers'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $achievements = Achievement::query()
            ->where('is_active', true)
            ->where('is_highlight', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $modules = LearningModule::query()
            ->with(['lessons'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        $demoTournament = Tournament::query()
            ->with(['teams', 'matches.team1', 'matches.team2', 'matches.winner'])
            ->where('status', 'live')
            ->first();

        $partners = Partner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $teamMembers = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('welcome', compact(
            'heroSlides',
            'divisions',
            'achievements',
            'modules',
            'demoTournament',
            'partners',
            'teamMembers'
        ));
    }
}
