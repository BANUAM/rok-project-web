<?php

namespace App\Http\Controllers;

use App\Models\Lsm;
use App\Models\LsmAttempt;
use App\Models\LsmAttemptAnswer;
use App\Models\LsmOption;
use App\Models\MlTier;
use App\Models\PageHeader;
use App\Models\TrialCandidate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TrialController extends Controller
{
    public function index(): View
    {
        $pageHeader = PageHeader::where('page_key', 'trial')->where('is_active', true)->first();
        return view('pages.trial', compact('pageHeader'));
    }

    public function showNextStage(string $token): View
    {
        $candidate = TrialCandidate::where('next_stage_token', $token)->firstOrFail();
        return view('pages.trial-stage', compact('candidate'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $token = 'rok_' . Str::random(32);

        $candidate = TrialCandidate::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'access_token' => $token,
            'token_expires_at' => now()->addDays(30),
            'status' => 'registered',
        ]);

        return redirect()->route('trial.profile', ['token' => $token])
            ->with('success', 'Pendaftaran awal berhasil! Silakan lengkapi profil dan unggah berkas persyaratan di bawah.');
    }

    public function showProfile(string $token): View|RedirectResponse
    {
        $candidate = TrialCandidate::where('access_token', $token)->firstOrFail();
        $tiers = MlTier::orderBy('sort_order')->get();

        return view('pages.trial-profile', compact('candidate', 'tiers'));
    }

    public function saveProfile(Request $request, string $token): RedirectResponse
    {
        $candidate = TrialCandidate::where('access_token', $token)->firstOrFail();

        $validated = $request->validate([
            'nickname' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:14', 'max:35'],
            'birthplace' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
            'role' => ['required', 'in:Gold Laner,Mid Laner,Jungler,EXP Laner,Roamer'],
            'ml_tier_id' => ['required', 'exists:ml_tiers,id'],
            'stars' => ['required', 'integer', 'min:0', 'max:999'],
            'profile_screenshot' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'hero_screenshot' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'tier_screenshot' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('profile_screenshot')) {
            $validated['profile_screenshot_path'] = $request->file('profile_screenshot')->store('trial-uploads', 'public');
        }
        if ($request->hasFile('hero_screenshot')) {
            $validated['favorite_hero_screenshot_path'] = $request->file('hero_screenshot')->store('trial-uploads', 'public');
        }
        if ($request->hasFile('tier_screenshot')) {
            $validated['tier_screenshot_path'] = $request->file('tier_screenshot')->store('trial-uploads', 'public');
        }

        $validated['status'] = 'profile_completed';
        $validated['profile_completed_at'] = now();

        $candidate->update($validated);

        return redirect()->route('trial.lsm', ['token' => $token])
            ->with('success', 'Profil dan berkas tersimpan! Tahap selanjutnya: kerjakan Tes LSM untuk menguji pemahaman taktismu.');
    }

    public function showLsm(string $token): View|RedirectResponse
    {
        $candidate = TrialCandidate::where('access_token', $token)->firstOrFail();

        // Check if candidate already completed LSM
        if ($candidate->lsmAttempt()->exists()) {
            return redirect()->route('trial.status', ['token' => $token]);
        }

        $lsm = Lsm::where('is_published', true)->with(['questions.options'])->first();

        if (!$lsm) {
            return redirect()->route('trial.status', ['token' => $token])
                ->with('error', 'Tes LSM belum dipublikasikan oleh pelatih. Mohon tunggu informasi selanjutnya.');
        }

        return view('pages.trial-lsm', compact('candidate', 'lsm'));
    }

    public function submitLsm(Request $request, string $token): RedirectResponse
    {
        $candidate = TrialCandidate::where('access_token', $token)->firstOrFail();

        if ($candidate->lsmAttempt()->exists()) {
            return redirect()->route('trial.status', ['token' => $token]);
        }

        $lsm = Lsm::where('is_published', true)->firstOrFail();
        $answers = $request->input('answers', []); // question_id => option_id

        $questions = $lsm->questions()->with('options')->get();
        $totalQuestions = $questions->count();
        $correctAnswers = 0;

        $attempt = LsmAttempt::create([
            'lsm_id' => $lsm->id,
            'trial_candidate_id' => $candidate->id,
            'total_questions' => $totalQuestions,
            'correct_answers' => 0,
            'score' => 0,
            'is_passed' => false,
            'completed_at' => now(),
        ]);

        foreach ($questions as $q) {
            $selectedOptionId = $answers[$q->id] ?? null;
            $selectedOption = $selectedOptionId ? LsmOption::find($selectedOptionId) : null;
            $isCorrect = $selectedOption ? (bool) $selectedOption->is_correct : false;

            if ($isCorrect) {
                $correctAnswers++;
            }

            LsmAttemptAnswer::create([
                'lsm_attempt_id' => $attempt->id,
                'lsm_question_id' => $q->id,
                'selected_option_id' => $selectedOptionId,
                'is_correct' => $isCorrect,
            ]);
        }

        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $isPassed = $score >= $lsm->passing_score;

        $attempt->update([
            'correct_answers' => $correctAnswers,
            'score' => $score,
            'is_passed' => $isPassed,
        ]);

        $candidate->update([
            'status' => 'lsm_completed',
            'lsm_completed_at' => now(),
        ]);

        return redirect()->route('trial.status', ['token' => $token])
            ->with('success', 'Tes LSM selesai! Jawaban kamu telah tersimpan dan tim pelatih akan segera mengevaluasi.');
    }

    public function showStatus(string $token): View
    {
        $candidate = TrialCandidate::where('access_token', $token)
            ->with(['mlTier', 'lsmAttempt'])
            ->firstOrFail();

        return view('pages.trial-status', compact('candidate'));
    }
}
