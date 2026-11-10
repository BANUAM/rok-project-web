<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Division;
use App\Models\HeroSlide;
use App\Models\LearningModule;
use App\Models\MembershipPlan;
use App\Models\Partner;
use App\Models\TeamMember;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View|InertiaResponse
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

        $achievementsCount = Achievement::query()->where('is_active', true)->count();

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

        $membershipPlans = MembershipPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $data = compact(
            'heroSlides',
            'divisions',
            'achievements',
            'achievementsCount',
            'modules',
            'demoTournament',
            'partners',
            'teamMembers',
            'membershipPlans'
        );

        if ($request->header('X-Inertia') || $request->wantsJson() || $request->boolean('inertia')) {
            return Inertia::render('Home', $data);
        }

        return view('welcome', $data);
    }
}
