<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Division;
use App\Models\Founder;
use App\Models\HeroSlide;
use App\Models\LearningLesson;
use App\Models\LearningModule;
use App\Models\Lsm;
use App\Models\LsmOption;
use App\Models\LsmQuestion;
use App\Models\Partner;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\TrialCandidate;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ManagementController extends Controller
{
    // ==========================================
    // AUTHENTICATION
    // ==========================================

    public function login(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('management.dashboard');
        }
        return view('management.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (!Auth::user()->isAdmin()) {
                Auth::logout();
                return back()->with('error', 'Akses ditolak. Anda tidak memiliki izin administrator.');
            }

            return redirect()->intended(route('management.dashboard'))
                ->with('success', 'Selamat datang di Panel Manajemen ROK Revamp!');
        }

        return back()->with('error', 'Email atau kata sandi tidak valid.')->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('management.login')
            ->with('success', 'Anda telah berhasil keluar dari sistem manajemen.');
    }

    // ==========================================
    // DASHBOARD OVERVIEW
    // ==========================================

    public function dashboard(): View
    {
        $metrics = [
            'candidates_count' => TrialCandidate::count(),
            'candidates_pending' => TrialCandidate::whereIn('status', ['lsm_completed', 'under_review'])->count(),
            'players_count' => Player::where('is_active', true)->where('status', 'active')->count(),
            'divisions_count' => Division::where('is_active', true)->count(),
            'achievements_count' => Achievement::where('is_active', true)->count(),
            'partners_count' => Partner::where('is_active', true)->count(),
            'tournaments_count' => Tournament::count(),
        ];

        $latestCandidates = TrialCandidate::with(['mlTier', 'lsmAttempt'])
            ->latest()
            ->take(6)
            ->get();

        $activeDivisions = Division::withCount('players')->get();

        return view('management.dashboard', compact('metrics', 'latestCandidates', 'activeDivisions'));
    }

    // ==========================================
    // HERO SLIDES
    // ==========================================

    public function heroSlides(): View
    {
        $slides = HeroSlide::orderBy('sort_order')->get();
        return view('management.hero-slides', compact('slides'));
    }

    public function storeHeroSlide(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'image', 'max:5120'],
            'cta_primary_text' => ['required', 'string'],
            'cta_primary_url' => ['required', 'string'],
            'cta_secondary_text' => ['required', 'string'],
            'cta_secondary_url' => ['required', 'string'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = 'hero-slides/hero-1.svg';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hero-slides', 'public');
        }

        HeroSlide::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'cta_primary_text' => $validated['cta_primary_text'],
            'cta_primary_url' => $validated['cta_primary_url'],
            'cta_secondary_text' => $validated['cta_secondary_text'],
            'cta_secondary_url' => $validated['cta_secondary_url'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Banner hero berhasil ditambahkan!');
    }

    public function updateHeroSlide(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'image', 'max:5120'],
            'cta_primary_text' => ['required', 'string'],
            'cta_primary_url' => ['required', 'string'],
            'cta_secondary_text' => ['required', 'string'],
            'cta_secondary_url' => ['required', 'string'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('hero-slides', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $heroSlide->update($validated);

        return back()->with('success', 'Banner hero diperbarui!');
    }

    public function deleteHeroSlide(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->delete();
        return back()->with('success', 'Banner hero dihapus!');
    }

    // ==========================================
    // FOUNDERS
    // ==========================================

    public function founders(): View
    {
        $founders = Founder::orderBy('sort_order')->get();
        return view('management.founders', compact('founders'));
    }

    public function storeFounder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'file', 'image', 'max:5120'],
            'instagram_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $photoPath = 'founders/founder-1.svg';
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('founders', 'public');
        }

        Founder::create([
            'name' => $validated['name'],
            'role_title' => $validated['role_title'],
            'bio' => $validated['bio'],
            'photo_path' => $photoPath,
            'instagram_url' => $validated['instagram_url'],
            'linkedin_url' => $validated['linkedin_url'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Founder berhasil ditambahkan!');
    }

    public function deleteFounder(Founder $founder): RedirectResponse
    {
        $founder->delete();
        return back()->with('success', 'Founder berhasil dihapus!');
    }

    // ==========================================
    // DIVISIONS
    // ==========================================

    public function divisions(): View
    {
        $divisions = Division::withCount('players')->orderBy('sort_order')->get();
        return view('management.divisions', compact('divisions'));
    }

    public function storeDivision(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'game_name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'file', 'image', 'max:5120'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $iconPath = 'divisions/mlbb-icon.svg';
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('divisions', 'public');
        }

        Division::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'game_name' => $validated['game_name'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function deleteDivision(Division $division): RedirectResponse
    {
        $division->delete();
        return back()->with('success', 'Divisi berhasil dihapus!');
    }

    // ==========================================
    // PLAYERS & ROSTER
    // ==========================================

    public function players(Request $request): View
    {
        $query = Player::with('division');
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        $players = $query->orderBy('division_id')->orderBy('sort_order')->get();
        $divisions = Division::where('is_active', true)->get();

        return view('management.players', compact('players', 'divisions'));
    }

    public function storePlayer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'real_name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string'],
            'age' => ['nullable', 'integer'],
            'domicile' => ['nullable', 'string'],
            'birthplace' => ['nullable', 'string'],
            'birthdate' => ['nullable', 'date'],
            'photo' => ['nullable', 'file', 'image', 'max:5120'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $photoPath = 'players/player-jungler.svg';
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('players', 'public');
        }

        Player::create([
            'division_id' => $validated['division_id'],
            'real_name' => $validated['real_name'],
            'nickname' => $validated['nickname'],
            'role' => $validated['role'],
            'age' => $validated['age'],
            'domicile' => $validated['domicile'],
            'birthplace' => $validated['birthplace'],
            'birthdate' => $validated['birthdate'],
            'photo_path' => $photoPath,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Pemain/staff berhasil ditambahkan ke roster!');
    }

    public function updatePlayer(Request $request, Player $player): RedirectResponse
    {
        $validated = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'real_name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string'],
            'age' => ['nullable', 'integer'],
            'domicile' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'file', 'image', 'max:5120'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('players', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $player->update($validated);

        return back()->with('success', 'Data pemain diperbarui!');
    }

    public function deletePlayer(Player $player): RedirectResponse
    {
        $player->delete();
        return back()->with('success', 'Pemain dihapus dari roster!');
    }

    // ==========================================
    // ACHIEVEMENTS
    // ==========================================

    public function achievements(): View
    {
        $achievements = Achievement::with('division')->orderByDesc('event_year')->get();
        $divisions = Division::where('is_active', true)->get();
        return view('management.achievements', compact('achievements', 'divisions'));
    }

    public function storeAchievement(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'team_name' => ['nullable', 'string'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'caption' => ['nullable', 'string'],
            'event_year' => ['required', 'integer'],
            'image' => ['nullable', 'file', 'image', 'max:5120'],
            'is_highlight' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = 'achievements/trophy-1.svg';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('achievements', 'public');
        }

        Achievement::create([
            'title' => $validated['title'],
            'team_name' => $validated['team_name'] ?? 'Ruler Of Kings',
            'division_id' => $validated['division_id'],
            'caption' => $validated['caption'],
            'event_year' => $validated['event_year'],
            'image_path' => $imagePath,
            'is_highlight' => $request->boolean('is_highlight'),
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Prestasi baru berhasil ditambahkan!');
    }

    public function deleteAchievement(Achievement $achievement): RedirectResponse
    {
        $achievement->delete();
        return back()->with('success', 'Prestasi berhasil dihapus!');
    }

    // ==========================================
    // PARTNERS
    // ==========================================

    public function partners(): View
    {
        $partners = Partner::orderBy('sort_order')->get();
        return view('management.partners', compact('partners'));
    }

    public function storePartner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'website_url' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'file', 'image', 'max:5120'],
            'sort_order' => ['required', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $logoPath = 'partners/partner-1.svg';
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partners', 'public');
        }

        Partner::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'website_url' => $validated['website_url'],
            'description' => $validated['description'],
            'logo_path' => $logoPath,
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Partner/sponsor berhasil ditambahkan!');
    }

    public function deletePartner(Partner $partner): RedirectResponse
    {
        $partner->delete();
        return back()->with('success', 'Partner berhasil dihapus!');
    }

    // ==========================================
    // TRIAL CANDIDATES & EVALUATION
    // ==========================================

    public function candidates(Request $request): View
    {
        $query = TrialCandidate::with(['mlTier', 'lsmAttempt', 'createdPlayer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $candidates = $query->latest()->get();
        $divisions = Division::where('is_active', true)->get();

        return view('management.candidates', compact('candidates', 'divisions'));
    }

    public function showCandidate(TrialCandidate $candidate): View
    {
        $candidate->load(['mlTier', 'lsmAttempt.answers.question', 'lsmAttempt.answers.selectedOption', 'createdPlayer']);
        $divisions = Division::where('is_active', true)->get();

        return view('management.candidate-detail', compact('candidate', 'divisions'));
    }

    public function updateCandidateStatus(Request $request, TrialCandidate $candidate): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:registered,profile_completed,lsm_completed,under_review,passed,rejected'],
            'reviewer_notes' => ['nullable', 'string'],
            'contract_completed' => ['nullable', 'boolean'],
        ]);

        $candidate->update([
            'status' => $validated['status'],
            'reviewer_notes' => $validated['reviewer_notes'],
            'contract_completed' => $request->boolean('contract_completed'),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Status kandidat {$candidate->name} diperbarui menjadi: " . $candidate->status_label);
    }

    public function promoteCandidateToPlayer(Request $request, TrialCandidate $candidate): RedirectResponse
    {
        $validated = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'role' => ['required', 'string'],
        ]);

        if ($candidate->created_player_id) {
            return back()->with('error', 'Kandidat ini sudah dipromosikan ke roster sebelumnya.');
        }

        $player = Player::create([
            'division_id' => $validated['division_id'],
            'real_name' => $candidate->name,
            'nickname' => $candidate->nickname ?? $candidate->name,
            'domicile' => $candidate->address,
            'age' => $candidate->age,
            'birthplace' => $candidate->birthplace,
            'birthdate' => $candidate->birthdate,
            'role' => $validated['role'],
            'photo_path' => 'players/player-jungler.svg',
            'status' => 'active',
            'is_active' => true,
        ]);

        $candidate->update([
            'created_player_id' => $player->id,
            'status' => 'passed',
            'contract_completed' => true,
        ]);

        return back()->with('success', "Selamat! {$candidate->name} ({$candidate->nickname}) berhasil dipindahkan ke roster aktif tim.");
    }

    // ==========================================
    // LSM MANAGEMENT
    // ==========================================

    public function lsm(): View
    {
        $lsm = Lsm::with(['questions.options', 'attempts.candidate'])->first();
        return view('management.lsm', compact('lsm'));
    }

    public function updateLsmSettings(Request $request, Lsm $lsm): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'question_limit' => ['required', 'integer', 'min:1'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'passing_score' => ['required', 'integer', 'min:1', 'max:100'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $lsm->update($validated);

        return back()->with('success', 'Pengaturan tes LSM berhasil diperbarui!');
    }

    public function storeQuestion(Request $request, Lsm $lsm): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'image' => ['nullable', 'file', 'image', 'max:5120'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string'],
            'correct_option_index' => ['required', 'integer'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lsm', 'public');
        }

        $nextOrder = $lsm->questions()->max('sort_order') + 1;

        $question = LsmQuestion::create([
            'lsm_id' => $lsm->id,
            'question' => $validated['question'],
            'image_path' => $imagePath,
            'sort_order' => $nextOrder,
        ]);

        foreach ($validated['options'] as $index => $optionText) {
            LsmOption::create([
                'lsm_question_id' => $question->id,
                'option_text' => $optionText,
                'is_correct' => ($index === (int) $validated['correct_option_index']),
            ]);
        }

        return back()->with('success', 'Soal pilihan ganda baru berhasil ditambahkan ke LSM!');
    }

    public function deleteQuestion(LsmQuestion $question): RedirectResponse
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus!');
    }

    // ==========================================
    // LEARNING & DEVELOPMENT MANAGEMENT
    // ==========================================

    public function learning(): View
    {
        $modules = LearningModule::with('lessons')->orderBy('sort_order')->get();
        return view('management.learning', compact('modules'));
    }

    public function storeModule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer'],
            'thumbnail' => ['nullable', 'file', 'image', 'max:5120'],
        ]);

        $thumbnailPath = 'modules/module-macro.svg';
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('modules', 'public');
        }

        LearningModule::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'category' => $validated['category'],
            'description' => $validated['description'],
            'thumbnail_path' => $thumbnailPath,
            'sort_order' => $validated['sort_order'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Modul pembelajaran berhasil dibuat!');
    }

    public function storeLesson(Request $request, LearningModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'string'],
            'duration' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'is_free_preview' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer'],
        ]);

        LearningLesson::create([
            'learning_module_id' => $module->id,
            'title' => $validated['title'],
            'video_url' => $validated['video_url'],
            'duration' => $validated['duration'],
            'summary' => $validated['summary'],
            'is_free_preview' => $request->boolean('is_free_preview'),
            'sort_order' => $validated['sort_order'],
        ]);

        return back()->with('success', 'Materi/lesson berhasil ditambahkan ke modul!');
    }

    // ==========================================
    // TOURNAMENT MONITORING
    // ==========================================

    public function tournaments(): View
    {
        $tournaments = Tournament::with(['teams', 'matches.team1', 'matches.team2', 'matches.winner'])->latest()->get();
        return view('management.tournaments', compact('tournaments'));
    }
}
