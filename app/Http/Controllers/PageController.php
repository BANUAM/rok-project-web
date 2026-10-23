<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Division;
use App\Models\Founder;
use App\Models\LearningModule;
use App\Models\MembershipPlan;
use App\Models\PageHeader;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
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
        $pageHeader = PageHeader::where('page_key', 'bracket')->where('is_active', true)->first();
        $membershipPlans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();

        // Standalone showcase demo with neutral default teams (Nama Tim 1..8)
        // Decoupled from live database tournaments so playing with the demo on /bracket
        // never modifies or overwrites real member tournaments.
        $demoTeams = (new \App\Http\Controllers\BracketStudioController())->defaultTeamsPayload(8);
        $activeTournament = new Tournament([
            'id' => 0,
            'name' => 'ROK Invitational Showcase',
            'slug' => 'rok-invitational-season-1',
            'format' => 'single_elimination',
            'team_count' => 8,
            'status' => 'live',
            'organizer_name' => 'ROK Esports Demo',
            'tier_required' => 'immortal',
            'is_public' => true,
            'bracket_state' => [
                'title' => 'ROK Invitational Showcase',
                'name' => 'ROK Invitational Showcase',
                'format' => 'single',
                'teamCount' => 8,
                'teams' => $demoTeams,
                'scores' => [
                    'R1-M1' => ['score1' => 2, 'score2' => 1],
                    'R1-M2' => ['score1' => 2, 'score2' => 0],
                    'R1-M3' => ['score1' => 1, 'score2' => 2],
                    'R1-M4' => ['score1' => 2, 'score2' => 0],
                ],
                'updatedAt' => now()->toIso8601String(),
            ],
        ]);
        $tournaments = collect([$activeTournament]);

        return view('pages.bracket', compact('tournaments', 'activeTournament', 'pageHeader', 'membershipPlans'));
    }

    public function bracketFree(Request $request, ?string $slug = null): View|RedirectResponse
    {
        // 1. If user visits /bracket/free without slug:
        if (empty($slug)) {
            $isExplicitNew = $request->boolean('new') || $request->has('create');
            $cookieSlug = $request->cookie('rok_last_free_slug');

            // If not requesting a new one and has an existing valid free tournament in cookie:
            if (!$isExplicitNew && $cookieSlug) {
                $existing = Tournament::where('slug', $cookieSlug)->first();
                if ($existing) {
                    return redirect()->route('bracket.free', ['slug' => $existing->slug]);
                }
            }

            // Otherwise, generate a fresh new unique free tournament
            $uniqueSlug = 'rok-free-' . strtolower(Str::random(6));
            $defaultTeams = (new \App\Http\Controllers\BracketStudioController())->defaultTeamsPayload(8);
            foreach ($defaultTeams as &$t) {
                $t['logo'] = null;
                $t['is_claimed'] = false;
            }
            unset($t);

            $freeTournament = Tournament::create([
                'name' => 'ROK Free Community Tournament',
                'slug' => $uniqueSlug,
                'format' => 'single_elimination',
                'team_count' => 8,
                'status' => 'live',
                'organizer_name' => 'Komunitas ROK Web',
                'tier_required' => 'free',
                'is_public' => true,
                'bracket_state' => [
                    'title' => 'ROK Free Community Tournament',
                    'name' => 'ROK Free Community Tournament',
                    'format' => 'single',
                    'teamCount' => 8,
                    'teams' => $defaultTeams,
                    'scores' => [],
                    'updatedAt' => now()->toIso8601String(),
                ],
            ]);

            return redirect()->route('bracket.free', ['slug' => $uniqueSlug])
                ->cookie('rok_last_free_slug', $uniqueSlug, 60 * 24 * 30);
        }

        // 2. Specific slug provided in URL:
        $freeTournament = Tournament::where('slug', $slug)->first();

        // If not found in DB (e.g. valid link shared from another device or newly accessed)
        if (!$freeTournament) {
            $defaultTeams = (new \App\Http\Controllers\BracketStudioController())->defaultTeamsPayload(8);
            foreach ($defaultTeams as &$t) {
                $t['logo'] = null;
                $t['is_claimed'] = false;
            }
            unset($t);

            $freeTournament = Tournament::create([
                'name' => 'ROK Free Community Tournament',
                'slug' => $slug,
                'format' => 'single_elimination',
                'team_count' => 8,
                'status' => 'live',
                'organizer_name' => 'Komunitas ROK Web',
                'tier_required' => 'free',
                'is_public' => true,
                'bracket_state' => [
                    'title' => 'ROK Free Community Tournament',
                    'name' => 'ROK Free Community Tournament',
                    'format' => 'single',
                    'teamCount' => 8,
                    'teams' => $defaultTeams,
                    'scores' => [],
                    'updatedAt' => now()->toIso8601String(),
                ],
            ]);
        }

        $activeTournament = $freeTournament;
        $pageHeader = PageHeader::where('page_key', 'bracket')->where('is_active', true)->first();
        $membershipPlans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();
        $tournaments = collect([$freeTournament]);

        // Keep cookie updated with current active free tournament
        Cookie::queue('rok_last_free_slug', $freeTournament->slug, 60 * 24 * 30);

        return view('pages.bracket-free', compact('tournaments', 'activeTournament', 'pageHeader', 'membershipPlans'));
    }
}
