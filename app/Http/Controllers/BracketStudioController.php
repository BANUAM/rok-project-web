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
     * Dedicated Pra-Tournament Free Portal (/bracket/pra-tournament-free/{slug?})
     * No logo input button allowed.
     */
    public function praTournamentFree(Request $request, ?string $slug = null): View
    {
        $tournament = null;
        if (!empty($slug)) {
            $tournament = Tournament::with(['teams', 'matches'])->where('slug', $slug)->first();
        }

        if (!$tournament) {
            $cookieSlug = $request->cookie('rok_last_free_slug');
            if ($cookieSlug) {
                $tournament = Tournament::with(['teams', 'matches'])->where('slug', $cookieSlug)->first();
            }
        }

        if (!$tournament) {
            $tournament = Tournament::where('tier_required', 'free')->latest('updated_at')->first();
        }

        if (!$tournament) {
            $defaultTeams = $this->defaultTeamsPayload(8);
            foreach ($defaultTeams as &$t) {
                $t['logo'] = null;
                $t['is_claimed'] = false;
            }
            unset($t);

            $uniqueSlug = 'rok-free-' . strtolower(Str::random(6));
            $tournament = Tournament::create([
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
        }

        return $this->renderPraTournamentView($request, $tournament, true);
    }

    /**
     * Dedicated Pra-Tournament Membership Portal (/bracket/pra-tournament-membership/{slug})
     * With custom logo input button for Challenger & Immortal.
     */
    public function praTournamentMembership(Request $request, string $slug): View
    {
        $tournament = Tournament::with(['teams', 'matches'])->where('slug', $slug)->firstOrFail();
        return $this->renderPraTournamentView($request, $tournament, false);
    }

    /**
     * Public Pra-Tournament Check-in Portal (/bracket/pra-tournament/{slug})
     * Auto-detects free or membership mode.
     */
    public function praTournament(Request $request, string $slug): View
    {
        $tournament = Tournament::with(['teams', 'matches'])->where('slug', $slug)->firstOrFail();
        $isFree = ($tournament->tier_required === 'free');
        return $this->renderPraTournamentView($request, $tournament, $isFree);
    }

    protected function renderPraTournamentView(Request $request, Tournament $tournament, bool $isFreeMode): View
    {
        $bracketState = $tournament->bracket_state ?? [];
        $teams = $bracketState['teams'] ?? [];

        if (empty($teams)) {
            $teams = $this->defaultTeamsPayload($tournament->team_count ?: 8);
            $bracketState = array_merge($bracketState, [
                'title' => $tournament->name,
                'format' => $this->mapJsFormat($tournament->format),
                'teamCount' => count($teams),
                'teams' => $teams,
                'scores' => $bracketState['scores'] ?? [],
                'updatedAt' => now()->toIso8601String(),
            ]);
            $tournament->update([
                'bracket_state' => $bracketState,
            ]);
            $tournament->refresh();
        }

        $totalSlots = count($teams) > 0 ? count($teams) : ($tournament->team_count ?: 8);
        $claimedCount = count(array_filter($teams, fn($t) => !empty($t['is_claimed'])));
        $canEdit = Auth::check() && ($tournament->user_id === Auth::id() || Auth::user()->isAdmin());
        $hasLogoAccess = !$isFreeMode;

        return view('pages.bracket-pra-tournament', [
            'tournament' => $tournament,
            'isFreeMode' => $isFreeMode,
            'hasLogoAccess' => $hasLogoAccess,
            'canEdit' => $canEdit,
            'totalSlots' => $totalSlots,
            'claimedCount' => $claimedCount,
            'shareUrl' => route('bracket.live', ['slug' => $tournament->slug]),
            'praTourneyFreeUrl' => route('bracket.pra-tournament.free', ['slug' => $tournament->slug]),
            'praTourneyMemberUrl' => route('bracket.pra-tournament.membership', ['slug' => $tournament->slug]),
        ]);
    }

    /**
     * API: Team captain claims a vacant slot in pra-tournament.
     * Enforces: 1 slot per captain, unique WhatsApp number per tournament, vacant slot check.
     */
    public function claimSlot(Request $request, string $slug): JsonResponse
    {
        $tournament = Tournament::where('slug', $slug)->first();
        if (!$tournament) {
            return response()->json(['ok' => false, 'message' => 'Turnamen tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'slot_index' => ['required', 'integer', 'min:0', 'max:63'],
            'team_name' => ['required', 'string', 'min:2', 'max:40'],
            'whatsapp' => ['required', 'string', 'min:8', 'max:25'],
            'logo' => ['nullable', 'string'],
        ]);

        $slotIdx = (int) $validated['slot_index'];
        $cleanWa = preg_replace('/[^0-9]/', '', $validated['whatsapp']);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        }

        $bracketState = $tournament->bracket_state ?? [];
        $teams = !empty($bracketState['teams']) ? $bracketState['teams'] : $this->defaultTeamsPayload($tournament->team_count ?: 8);

        if (!isset($teams[$slotIdx])) {
            $maxSlots = max(count($teams), $tournament->team_count ?: 8);
            if ($slotIdx >= 0 && $slotIdx < $maxSlots) {
                for ($k = count($teams); $k <= $slotIdx; $k++) {
                    $teams[$k] = [
                        'id' => $k + 1,
                        'seed' => $k + 1,
                        'name' => 'Nama Tim ' . ($k + 1),
                        'whatsapp' => '',
                        'logo' => null,
                        'is_claimed' => false,
                    ];
                }
            } else {
                return response()->json(['ok' => false, 'message' => 'Slot nomor ' . ($slotIdx + 1) . ' tidak valid.'], 422);
            }
        }

        // Check if slot is already claimed
        if (!empty($teams[$slotIdx]['is_claimed'])) {
            return response()->json(['ok' => false, 'message' => 'Slot #' . ($slotIdx + 1) . ' baru saja diklaim oleh tim lain. Silakan pilih slot lain.'], 409);
        }

        // Check if WhatsApp is already used by another claimed team in this tournament
        foreach ($teams as $idx => $t) {
            if ($idx !== $slotIdx && !empty($t['is_claimed']) && !empty($t['whatsapp'])) {
                $existingCleanWa = preg_replace('/[^0-9]/', '', $t['whatsapp']);
                if (str_starts_with($existingCleanWa, '0')) {
                    $existingCleanWa = '62' . substr($existingCleanWa, 1);
                }
                if ($existingCleanWa === $cleanWa) {
                    return response()->json(['ok' => false, 'message' => 'Nomor WhatsApp ' . $validated['whatsapp'] . ' sudah terdaftar pada tim "' . ($t['name'] ?? 'lain') . '" di turnamen ini.'], 422);
                }
            }
        }

        // Determine logo: On Free mode, force logo to null/empty (pure text & WhatsApp only)
        $isFree = ($tournament->tier_required === 'free');
        if ($isFree) {
            $logo = null;
        } else {
            $presets = [
                '/storage/tournaments/team-rok.svg',
                '/storage/tournaments/team-aether.svg',
                '/storage/tournaments/team-valkyrie.svg',
                '/storage/tournaments/team-shadow.svg',
                '/storage/tournaments/team-apex.svg',
                '/storage/tournaments/team-wolves.svg',
                '/storage/tournaments/team-kitsune.svg',
                '/storage/tournaments/team-eclipse.svg',
            ];
            $logo = $validated['logo'] ?? null;
            if (empty($logo)) {
                $logo = $teams[$slotIdx]['logo'] ?? $presets[$slotIdx % count($presets)];
            }
        }

        // Update the team in slot
        $teams[$slotIdx]['name'] = trim($validated['team_name']);
        $teams[$slotIdx]['whatsapp'] = $cleanWa;
        $teams[$slotIdx]['wa'] = $cleanWa;
        $teams[$slotIdx]['logo'] = $logo;
        $teams[$slotIdx]['is_claimed'] = true;
        $teams[$slotIdx]['claimed_at'] = now()->toIso8601String();

        $bracketState['teams'] = $teams;
        $bracketState['updatedAt'] = now()->toIso8601String();

        $tournament->update([
            'bracket_state' => $bracketState,
            'updated_at' => now(),
        ]);

        $this->syncTeamsTable($tournament, $teams);

        return response()->json([
            'ok' => true,
            'message' => 'Selamat! Tim "' . $validated['team_name'] . '" berhasil dikonfirmasi di Slot #' . ($slotIdx + 1) . '.',
            'slot_index' => $slotIdx,
            'team' => $teams[$slotIdx],
            'bracket_state' => $bracketState,
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
            'state' => $tournament->bracket_state,
            'updated_at' => $tournament->updated_at?->toIso8601String(),
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Real-Time API Endpoint: Save & broadcast bracket state updates.
     */
    public function syncState(Request $request, string $slug): JsonResponse
    {
        $tournament = Tournament::where('slug', $slug)->first();

        // Support both direct fields and nested { state: { ... } } payload from JS
        $rawState = $request->input('state');
        if (!is_array($rawState)) {
            $rawState = $request->all();
        }

        $title = (string) ($rawState['title'] ?? $rawState['name'] ?? ($tournament ? $tournament->name : 'ROK Live Tournament'));
        $format = (string) ($rawState['format'] ?? ($tournament ? $this->mapJsFormat($tournament->format) : 'single'));
        $teams = is_array($rawState['teams'] ?? null) ? $rawState['teams'] : [];
        $teamCount = (int) ($rawState['teamCount'] ?? (count($teams) > 0 ? count($teams) : 8));
        $scores = $rawState['scores'] ?? [];
        $matches = $rawState['matches'] ?? [];
        $rounds = $rawState['rounds'] ?? [];
        $championSource = $rawState['championSource'] ?? null;
        $matchesSummary = $rawState['matchesSummary'] ?? [];

        if (!$tournament) {
            $isFree = ($slug === 'rok-free-tournament' || str_starts_with($slug, 'rok-free-'));
            $tournament = Tournament::create([
                'user_id' => Auth::id(),
                'name' => $title,
                'slug' => $slug,
                'format' => $this->mapDbFormat($format),
                'team_count' => $teamCount,
                'status' => 'live',
                'organizer_name' => Auth::user()?->name ?? ($isFree ? 'Komunitas ROK Web' : 'ROK Organizer'),
                'tier_required' => $isFree ? 'free' : (Auth::user()?->membershipTierSlug() ?? 'challenger'),
                'is_public' => true,
            ]);
        }

        $statePayload = [
            'title' => $title,
            'name' => $title,
            'format' => $format,
            'teamCount' => $teamCount,
            'teams' => $teams,
            'scores' => $scores,
            'matches' => $matches,
            'rounds' => $rounds,
            'championSource' => $championSource,
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

        if (count($teams) > 0) {
            $this->syncTeamsTable($tournament, $teams);
        }

        return response()->json([
            'ok' => true,
            'slug' => $tournament->slug,
            'share_url' => route('bracket.live', ['slug' => $tournament->slug]),
            'updated_at' => $tournament->updated_at->toIso8601String(),
            'bracket_state' => $statePayload,
            'state' => $statePayload,
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

    public function defaultTeamsPayload(int $count): array
    {
        $presets = [
            '/storage/tournaments/team-rok.svg',
            '/storage/tournaments/team-aether.svg',
            '/storage/tournaments/team-valkyrie.svg',
            '/storage/tournaments/team-shadow.svg',
            '/storage/tournaments/team-apex.svg',
            '/storage/tournaments/team-wolves.svg',
            '/storage/tournaments/team-kitsune.svg',
            '/storage/tournaments/team-eclipse.svg',
        ];

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $preset = $presets[$i % count($presets)];
            $result[] = [
                'id' => $i + 1,
                'seed' => $i + 1,
                'name' => 'Nama Tim ' . ($i + 1),
                'whatsapp' => '',
                'logo' => $preset,
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
