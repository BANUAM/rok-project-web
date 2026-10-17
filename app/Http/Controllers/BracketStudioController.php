<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentTeam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BracketStudioController extends Controller
{
    /**
     * Dedicated Bracket Studio Dashboard (for ROK Challenger, ROK Immortal, and Admin).
     */
    public function index(): View
    {
        $user = Auth::user();

        $myTournaments = Tournament::withCount(['teams', 'matches'])
            ->when(!$user->isAdmin(), function ($q) use ($user) {
                $q->where(function ($sub) use ($user) {
                    $sub->where('user_id', $user->id)
                        ->orWhereNull('user_id');
                });
            })
            ->latest('updated_at')
            ->get();

        // Ensure at least one tournament exists for immediate exploration
        if ($myTournaments->isEmpty()) {
            $anyTourney = Tournament::withCount(['teams', 'matches'])->latest('updated_at')->first();
            if ($anyTourney) {
                $myTournaments = collect([$anyTourney]);
            } else {
                $defaultTourney = $this->ensureDefaultTournament($user->id, $user->name);
                $myTournaments = collect([$defaultTourney]);
            }
        }

        return view('bracket-studio.index', [
            'tournaments' => $myTournaments,
            'activePlan' => $user->activeMembershipPlan(),
            'tierSlug' => $user->membershipTierSlug(),
        ]);
    }

    /**
     * Create a new Tournament Bracket in the Studio.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'format' => ['required', 'in:single,double,league,swiss,single_elimination,double_elimination,swiss_stage'],
            'team_count' => ['required', 'integer', 'min:3', 'max:64'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug . '-' . strtolower(Str::random(5));

        $jsFormat = $this->mapJsFormat($validated['format']);
        $teamCount = (int) $validated['team_count'];
        if ($jsFormat === 'double' && !in_array($teamCount, [4, 6], true)) {
            $teamCount = 6;
        } elseif ($jsFormat === 'swiss') {
            $teamCount = 16;
        }

        $defaultPresets = $this->defaultTeamsPayload($teamCount);

        $tournament = Tournament::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'format' => $this->mapDbFormat($jsFormat),
            'team_count' => $teamCount,
            'banner_path' => 'tournaments/tourney-banner.svg',
            'status' => 'live',
            'organizer_name' => $user->name,
            'tier_required' => $user->membershipTierSlug(),
            'description' => $validated['description'] ?? 'Turnamen resmi yang dikelola secara real-time melalui ROK Bracket Studio.',
            'rules' => 'Pertandingan menggunakan sistem real-time scoring ROK Esports.',
            'is_public' => true,
            'bracket_state' => [
                'title' => $validated['name'],
                'name' => $validated['name'],
                'format' => $jsFormat,
                'teamCount' => $teamCount,
                'teams' => $defaultPresets,
                'scores' => [],
                'updatedAt' => now()->toIso8601String(),
            ],
        ]);

        $this->syncTeamsTable($tournament, $defaultPresets);

        return redirect()->route('bracket.studio.editor', ['slug' => $tournament->slug])
            ->with('success', 'Turnamen "' . $tournament->name . '" berhasil dibuat! Anda dapat langsung mengatur tim, nomor WhatsApp, logo, dan skor secara real-time.');
    }

    /**
     * Dedicated Bracket Studio Live Editor Page.
     */
    public function editor(?string $slug = null): View
    {
        $user = Auth::user();

        if ($slug) {
            $tournament = Tournament::where('slug', $slug)->firstOrFail();
        } else {
            $tournament = Tournament::where('user_id', $user->id)->latest('updated_at')->first()
                ?? Tournament::latest('updated_at')->first()
                ?? $this->ensureDefaultTournament($user->id, $user->name);
        }

        if (empty($tournament->bracket_state)) {
            $defaultTeams = $this->defaultTeamsPayload($tournament->team_count ?: 8);
            $tournament->update([
                'bracket_state' => [
                    'title' => $tournament->name,
                    'format' => $this->mapJsFormat($tournament->format),
                    'teamCount' => count($defaultTeams),
                    'teams' => $defaultTeams,
                    'scores' => [],
                    'updatedAt' => now()->toIso8601String(),
                ],
            ]);
            $tournament->refresh();
        }

        $allTournaments = Tournament::orderByDesc('updated_at')->limit(20)->get();

        return view('bracket-studio.editor', [
            'tournament' => $tournament,
            'allTournaments' => $allTournaments,
            'activePlan' => $user->activeMembershipPlan(),
            'tierSlug' => $user->membershipTierSlug(),
        ]);
    }

    /**
     * Delete a tournament from Bracket Studio.
     */
    public function destroy(Tournament $tournament): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $tournament->user_id && $tournament->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus turnamen ini.');
        }

        $name = $tournament->name;
        $tournament->delete();

        return redirect()->route('bracket.studio.index')
            ->with('success', "Turnamen \"{$name}\" berhasil dihapus.");
    }

    /**
     * Public Shareable Real-Time Bracket Page (/bracket/live/{slug}).
     */
    public function liveShare(string $slug): View
    {
        $tournament = Tournament::with(['teams', 'matches'])->where('slug', $slug)->firstOrFail();

        if (empty($tournament->bracket_state)) {
            $defaultTeams = $this->defaultTeamsPayload($tournament->team_count ?: 8);
            $tournament->update([
                'bracket_state' => [
                    'title' => $tournament->name,
                    'format' => $this->mapJsFormat($tournament->format),
                    'teamCount' => count($defaultTeams),
                    'teams' => $defaultTeams,
                    'scores' => [],
                    'updatedAt' => now()->toIso8601String(),
                ],
            ]);
            $tournament->refresh();
        }

        $canEdit = Auth::check() && (Auth::user()->hasBracketAccess() || Auth::user()->isAdmin());

        return view('pages.bracket-live', [
            'tournament' => $tournament,
            'canEdit' => $canEdit,
        ]);
    }

    /**
     * Real-Time API Endpoint: Get latest bracket state JSON for polling viewers.
     */
    public function getState(string $slug): JsonResponse
    {
        $tournament = Tournament::where('slug', $slug)->first();

        if (!$tournament) {
            return response()->json(['error' => 'Tournament not found'], 404);
        }

        return response()->json([
            'slug' => $tournament->slug,
            'name' => $tournament->name,
            'status' => $tournament->status,
            'organizer_name' => $tournament->organizer_name,
            'bracket_state' => $tournament->bracket_state,
            'updated_at' => $tournament->updated_at?->toIso8601String(),
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Real-Time API Endpoint: Save & broadcast bracket state updates.
     */
    public function syncState(Request $request, string $slug): JsonResponse
    {
        $tournament = Tournament::where('slug', $slug)->first();

        if (!$tournament) {
            $tournament = Tournament::create([
                'user_id' => Auth::id(),
                'name' => $request->input('title', 'ROK Live Tournament'),
                'slug' => $slug,
                'format' => $this->mapDbFormat($request->input('format', 'single')),
                'team_count' => (int) $request->input('teamCount', 8),
                'status' => 'live',
                'organizer_name' => Auth::user()?->name ?? 'ROK Organizer',
                'tier_required' => Auth::user()?->membershipTierSlug() ?? 'challenger',
                'is_public' => true,
            ]);
        }

        $title = (string) ($request->input('title') ?: $request->input('name', $tournament->name));
        $format = (string) $request->input('format', 'single');
        $teamCount = (int) $request->input('teamCount', 8);
        $teams = $request->input('teams', []);
        $scores = $request->input('scores', []);
        $matchesSummary = $request->input('matchesSummary', []);

        $statePayload = [
            'title' => $title,
            'name' => $title,
            'format' => $format,
            'teamCount' => $teamCount,
            'teams' => $teams,
            'scores' => $scores,
            'matchesSummary' => $matchesSummary,
            'updatedAt' => now()->toIso8601String(),
        ];

        $tournament->update([
            'name' => $title,
            'format' => $this->mapDbFormat($format),
            'team_count' => $teamCount,
            'bracket_state' => $statePayload,
            'updated_at' => now(),
        ]);

        if (is_array($teams) && count($teams) > 0) {
            $this->syncTeamsTable($tournament, $teams);
        }

        return response()->json([
            'ok' => true,
            'slug' => $tournament->slug,
            'share_url' => route('bracket.live', ['slug' => $tournament->slug]),
            'updated_at' => $tournament->updated_at->toIso8601String(),
            'bracket_state' => $statePayload,
        ]);
    }

    protected function syncTeamsTable(Tournament $tournament, array $teams): void
    {
        try {
            $existingIds = [];
            foreach ($teams as $idx => $team) {
                $seed = (int) ($team['seed'] ?? ($idx + 1));
                $record = TournamentTeam::updateOrCreate(
                    [
                        'tournament_id' => $tournament->id,
                        'seed' => $seed,
                    ],
                    [
                        'team_name' => $team['name'] ?? ('Tim ' . $seed),
                        'whatsapp_number' => $team['whatsapp'] ?? null,
                        'logo_path' => $team['logo'] ?? null,
                    ]
                );
                $existingIds[] = $record->id;
            }
            TournamentTeam::where('tournament_id', $tournament->id)
                ->whereNotIn('id', $existingIds)
                ->delete();
        } catch (\Throwable $e) {
            // Non-blocking table sync
        }
    }

    protected function ensureDefaultTournament(?int $userId, string $organizerName): Tournament
    {
        $existing = Tournament::where('slug', 'rok-invitational-season-1')->first();
        if ($existing) {
            return $existing;
        }

        $baseSlug = 'rok-invitational-season-1';
        $slug = Tournament::where('slug', $baseSlug)->exists()
            ? $baseSlug . '-' . strtolower(Str::random(5))
            : $baseSlug;

        $teams = $this->defaultTeamsPayload(8);
        return Tournament::create([
            'user_id' => $userId,
            'name' => 'ROK National Invitational Season 1',
            'slug' => $slug,
            'format' => 'single_elimination',
            'team_count' => 8,
            'banner_path' => 'tournaments/tourney-banner.svg',
            'status' => 'live',
            'organizer_name' => $organizerName,
            'tier_required' => 'immortal',
            'description' => 'Turnamen bergengsi 8 tim terbaik komunitas esports dengan sistem Real-Time Live Bracket.',
            'rules' => 'Format Single Elimination BO3, Grand Final BO5.',
            'is_public' => true,
            'bracket_state' => [
                'title' => 'ROK National Invitational Season 1',
                'format' => 'single',
                'teamCount' => 8,
                'teams' => $teams,
                'scores' => [
                    'R1-M1' => ['score1' => 2, 'score2' => 0],
                    'R1-M2' => ['score1' => 2, 'score2' => 1],
                    'R1-M3' => ['score1' => 2, 'score2' => 0],
                    'R1-M4' => ['score1' => 2, 'score2' => 1],
                ],
                'updatedAt' => now()->toIso8601String(),
            ],
        ]);
    }

    protected function defaultTeamsPayload(int $count): array
    {
        $presets = [
            ['name' => 'ROK Prime', 'whatsapp' => '6281234567890', 'logo' => '/storage/tournaments/team-rok.svg'],
            ['name' => 'Aether Esports', 'whatsapp' => '6281234567891', 'logo' => '/storage/tournaments/team-aether.svg'],
            ['name' => 'Valkyrie Horizon', 'whatsapp' => '6281234567892', 'logo' => '/storage/tournaments/team-valkyrie.svg'],
            ['name' => 'Shadow Legion', 'whatsapp' => '6281234567893', 'logo' => '/storage/tournaments/team-shadow.svg'],
            ['name' => 'Apex Titans', 'whatsapp' => '6281234567894', 'logo' => '/storage/tournaments/team-apex.svg'],
            ['name' => 'Celestial Wolves', 'whatsapp' => '6281234567895', 'logo' => '/storage/tournaments/team-wolves.svg'],
            ['name' => 'Kitsune Vanguard', 'whatsapp' => '6281234567896', 'logo' => '/storage/tournaments/team-kitsune.svg'],
            ['name' => 'Solar Eclipse', 'whatsapp' => '6281234567897', 'logo' => '/storage/tournaments/team-eclipse.svg'],
            ['name' => 'Nova Sovereign', 'whatsapp' => '6281234567898', 'logo' => '/storage/tournaments/team-rok.svg'],
            ['name' => 'Phantom Syndicate', 'whatsapp' => '6281234567899', 'logo' => '/storage/tournaments/team-shadow.svg'],
            ['name' => 'Crimson Vortex', 'whatsapp' => '6281234567800', 'logo' => '/storage/tournaments/team-apex.svg'],
            ['name' => 'Obsidian Crown', 'whatsapp' => '6281234567801', 'logo' => '/storage/tournaments/team-aether.svg'],
            ['name' => 'Thunder Monarch', 'whatsapp' => '6281234567802', 'logo' => '/storage/tournaments/team-wolves.svg'],
            ['name' => 'Iron Valkyrie', 'whatsapp' => '6281234567803', 'logo' => '/storage/tournaments/team-valkyrie.svg'],
            ['name' => 'Astral Knights', 'whatsapp' => '6281234567804', 'logo' => '/storage/tournaments/team-kitsune.svg'],
            ['name' => 'Zenith Dragons', 'whatsapp' => '6281234567805', 'logo' => '/storage/tournaments/team-eclipse.svg'],
        ];

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $preset = $presets[$i % count($presets)];
            $result[] = [
                'id' => $i + 1,
                'seed' => $i + 1,
                'name' => $i < count($presets) ? $preset['name'] : ('Tim Esports ' . ($i + 1)),
                'whatsapp' => $preset['whatsapp'],
                'logo' => $preset['logo'],
            ];
        }

        return $result;
    }

    protected function mapDbFormat(string $jsFormat): string
    {
        return match ($jsFormat) {
            'double' => 'double_elimination',
            'league' => 'league',
            'swiss' => 'swiss_stage',
            default => 'single_elimination',
        };
    }

    protected function mapJsFormat(string $dbFormat): string
    {
        return match ($dbFormat) {
            'double_elimination', 'double' => 'double',
            'league' => 'league',
            'swiss_stage', 'swiss' => 'swiss',
            default => 'single',
        };
    }
}
