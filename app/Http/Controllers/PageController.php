<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Division;
use App\Models\Founder;
use App\Models\LearningModule;
use App\Models\MembershipPlan;
use App\Models\PageHeader;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function teams(): View
    {
        $founders = Founder::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $divisions = Division::query()
            ->with(['activePlayers'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $pageHeader = PageHeader::where('page_key', 'teams')->where('is_active', true)->first();

        return view('pages.teams', compact('founders', 'divisions', 'pageHeader'));
    }

    public function achievements(Request $request): View
    {
        $query = Achievement::query()
            ->with('division')
            ->where('is_active', true);

        if ($request->filled('year')) {
            $query->where('event_year', $request->year);
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        $achievements = $query->orderByDesc('event_year')
            ->orderBy('sort_order')
            ->get();

        $years = Achievement::query()
            ->where('is_active', true)
            ->whereNotNull('event_year')
            ->select('event_year')
            ->distinct()
            ->orderByDesc('event_year')
            ->pluck('event_year');

        $divisions = Division::query()
            ->where('is_active', true)
            ->get();

        $pageHeader = PageHeader::where('page_key', 'achievements')->where('is_active', true)->first();

        return view('pages.achievements', compact('achievements', 'years', 'divisions', 'pageHeader'));
    }

    public function learningDevelopment(): View
    {
        $modules = LearningModule::query()
            ->with(['lessons'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $pageHeader = PageHeader::where('page_key', 'learning')->where('is_active', true)->first();
        $membershipPlans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.learning-development', compact('modules', 'pageHeader', 'membershipPlans'));
    }

    public function bracket(): View
    {
        $tournaments = Tournament::query()
            ->with(['teams', 'matches.team1', 'matches.team2', 'matches.winner'])
            ->latest()
            ->get();

        $activeTournament = $tournaments->firstWhere('status', 'live') ?? $tournaments->first();
        $pageHeader = PageHeader::where('page_key', 'bracket')->where('is_active', true)->first();
        $membershipPlans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.bracket', compact('tournaments', 'activeTournament', 'pageHeader', 'membershipPlans'));
    }

    public function bracketFree(): View
    {
        $tournaments = Tournament::query()
            ->with(['teams', 'matches.team1', 'matches.team2', 'matches.winner'])
            ->latest()
            ->get();

        $activeTournament = $tournaments->firstWhere('status', 'live') ?? $tournaments->first();
        $pageHeader = PageHeader::where('page_key', 'bracket')->where('is_active', true)->first();
        $membershipPlans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.bracket-free', compact('tournaments', 'activeTournament', 'pageHeader', 'membershipPlans'));
    }
}
