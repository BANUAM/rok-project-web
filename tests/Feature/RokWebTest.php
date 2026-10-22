<?php

namespace Tests\Feature;

use App\Mail\TrialPassedMail;
use App\Mail\TrialRejectedMail;
use App\Models\Division;
use App\Models\HeroSlide;
use App\Models\LearningLesson;
use App\Models\LearningModule;
use App\Models\Lsm;
use App\Models\MembershipOrder;
use App\Models\MembershipPlan;
use App\Models\MlTier;
use App\Models\PageHeader;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TrialCandidate;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RokWebTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('RULER OF KINGS');
        $response->assertSee('DIVISI KOMPETITIF');
        $response->assertSee('PRESTASI &amp; KEJAYAAN', false);
        $response->assertSee('LEARNING');
        $response->assertSee('BUILD. COMPETE. CONQUER.');
        $response->assertSee('SIAP JADI BAGIAN DARI');
        $response->assertSee('OFFICIAL PARTNERS &amp; SPONSORS', false);
        $response->assertSee('Ruler of Kings');
        $response->assertSee('Community');
    }

    public function test_teams_page_renders_founders_and_divisions(): void
    {
        $response = $this->get('/teams');
        $response->assertStatus(200);
        $response->assertSee('THE FOUNDERS');
        $response->assertSee('STRUKTUR DIVISI &amp; ROSTER AKTIF', false);
        $response->assertSee('ROK Prime (MLBB Men)');
    }

    public function test_achievements_page_renders(): void
    {
        $response = $this->get('/achievements');
        $response->assertStatus(200);
        $response->assertSee('ACHIEVEMENTS &amp; TROPHIES', false);
        $response->assertSee('National Esports League Season 4');
    }

    public function test_learning_development_page_renders_membership_matrix(): void
    {
        $response = $this->get('/learning-development');
        $response->assertStatus(200);
        $response->assertSee('LEARNING &amp; DEVELOPMENT', false);
        $response->assertSee('ROK Learning');
        $response->assertSee('ROK Challenger');
        $response->assertSee('ROK Immortal');
        $response->assertSee('MATRIKS HAK AKSES SISTEM');
    }

    public function test_bracket_page_renders_simulator_and_tournaments(): void
    {
        $response = $this->get('/bracket');
        $response->assertStatus(200);
        $response->assertSee('BUILD. COMPETE. CONQUER.');
        $response->assertSee('Your Tournament. Your Bracket. Your Champion.');
        $response->assertSee('BUAT BAGAN TURNAMEN SENDIRI');
    }

    public function test_bracket_free_page_renders(): void
    {
        $response = $this->get('/bracket/free');
        $response->assertStatus(200);
        $response->assertSee('BAGAN TURNAMEN GRATIS');
        $response->assertSee('FREE TOURNAMENT GENERATOR');
        $response->assertSee('SEMUA FITUR BAGAN AKTIF');
    }

    public function test_trial_registration_flow(): void
    {
        // 1. Initial Registration
        $registerResponse = $this->post('/trial/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
        ]);

        $candidate = TrialCandidate::where('email', 'budi.santoso@example.com')->first();
        $this->assertNotNull($candidate);
        $registerResponse->assertRedirect(route('trial.profile', ['token' => $candidate->access_token]));

        // 2. Profile Completion
        $tier = MlTier::first();
        Storage::fake('public');

        $profileResponse = $this->post(route('trial.save-profile', ['token' => $candidate->access_token]), [
            'nickname' => 'BudiKing',
            'address' => 'Jl. Merdeka No. 10',
            'age' => 19,
            'birthplace' => 'Surabaya',
            'birthdate' => '2007-01-01',
            'role' => 'Jungler',
            'ml_tier_id' => $tier->id,
            'stars' => 150,
            'profile_screenshot' => UploadedFile::fake()->create('profile.png', 100, 'image/png'),
            'hero_screenshot' => UploadedFile::fake()->create('hero.png', 100, 'image/png'),
            'tier_screenshot' => UploadedFile::fake()->create('tier.png', 100, 'image/png'),
        ]);

        $profileResponse->assertRedirect(route('trial.lsm', ['token' => $candidate->access_token]));
        $candidate->refresh();
        $this->assertEquals('profile_completed', $candidate->status);

        // 3. Submit LSM Test
        $lsm = Lsm::where('is_published', true)->first();
        $question = $lsm->questions()->first();
        $correctOption = $question->options()->where('is_correct', true)->first();

        $lsmResponse = $this->post(route('trial.submit-lsm', ['token' => $candidate->access_token]), [
            'answers' => [
                $question->id => $correctOption->id,
            ],
        ]);

        $lsmResponse->assertRedirect(route('trial.status', ['token' => $candidate->access_token]));
        $candidate->refresh();
        $this->assertEquals('lsm_completed', $candidate->status);
        $this->assertNotNull($candidate->lsmAttempt);
    }

    public function test_admin_management_login_and_promote_player(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        // Login
        $response = $this->post('/management/login', [
            'email' => $admin->email,
            'password' => 'admin123',
        ]);
        $response->assertRedirect(route('management.dashboard'));

        // Promote candidate to active player
        $candidate = TrialCandidate::first();
        $division = Division::first();

        $promoteResponse = $this->actingAs($admin)->post(route('management.candidates.promote', $candidate), [
            'division_id' => $division->id,
            'role' => 'Gold Laner',
        ]);

        $promoteResponse->assertSessionHas('success');
        $candidate->refresh();
        $this->assertEquals('passed', $candidate->status);
        $this->assertNotNull($candidate->created_player_id);

        $newPlayer = Player::find($candidate->created_player_id);
        $this->assertNotNull($newPlayer);
        $this->assertEquals('Gold Laner', $newPlayer->role);
        $this->assertEquals('active', $newPlayer->status);
    }

    public function test_user_registration_creates_account_and_requires_email_verification(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'role' => 'user',
            'email_verified_at' => null,
        ]);
        $this->assertAuthenticated();
    }

    public function test_user_can_login_with_email_and_username(): void
    {
        $user = User::create([
            'name' => 'Gamer One',
            'email' => 'gamerone@example.com',
            'username' => 'gamerone',
            'password' => Hash::make('gamerpass123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Login using email
        $emailLogin = $this->post('/login', [
            'login' => 'gamerone@example.com',
            'password' => 'gamerpass123',
        ]);
        $emailLogin->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);

        // Logout
        $this->post('/logout');
        $this->assertGuest();

        // Login using username
        $userLogin = $this->post('/login', [
            'login' => 'gamerone',
            'password' => 'gamerpass123',
        ]);
        $userLogin->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_unverified_user_cannot_access_membership_payment_page(): void
    {
        $unverifiedUser = User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'username' => 'unverified',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($unverifiedUser)->get(route('membership.payment'));
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_regular_user_is_forbidden_from_admin_management_routes(): void
    {
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'username' => 'regularuser',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($regularUser)->get(route('management.dashboard'));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    public function test_membership_payment_flow_creates_pending_order_and_invoice(): void
    {
        $user = User::create([
            'name' => 'Verified Buyer',
            'email' => 'buyer@example.com',
            'username' => 'verifiedbuyer',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $plan = MembershipPlan::where('slug', 'challenger')->first();
        $this->assertNotNull($plan);

        // Access payment page with pre-selected plan
        $pageResponse = $this->actingAs($user)->get(route('membership.payment', ['plan' => 'challenger']));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('PEMBAYARAN MEMBERSHIP ROK');
        $pageResponse->assertSee('RINGKASAN TRANSAKSI');
        $pageResponse->assertSee('ROK Challenger');

        // Submit payment order
        $orderResponse = $this->actingAs($user)->post(route('membership.process'), [
            'membership_plan_id' => $plan->id,
            'payment_method' => 'qris',
            'notes' => 'Permintaan aktivasi akun tim',
        ]);

        $order = MembershipOrder::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals('qris', $order->payment_method);
        $this->assertEquals($plan->price, $order->amount);

        $orderResponse->assertRedirect(route('membership.invoice', ['order' => $order->order_number]));

        // Check invoice view
        $invoiceResponse = $this->actingAs($user)->get(route('membership.invoice', ['order' => $order->order_number]));
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee('INVOICE PESANAN');
        $invoiceResponse->assertSee('MENUNGGU PEMBAYARAN');
        $invoiceResponse->assertSee($order->order_number);
    }

    public function test_admin_can_update_and_delete_page_header_banners(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->first();

        // 1. View page headers management
        $indexResponse = $this->actingAs($admin)->get(route('management.page-headers'));
        $indexResponse->assertSee('BANNER HEADER HALAMAN PUBLIK');

        // 2. Update page header for teams
        $updateResponse = $this->actingAs($admin)->post(route('management.page-headers.update', 'teams'), [
            'title' => 'DIVISI JUARA ROK ESPORTS',
            'subtitle' => 'Struktur organisasi dan jajaran pro player unggulan',
            'is_active' => 1,
            'image' => UploadedFile::fake()->create('teams-banner.png', 200, 'image/png'),
        ]);

        $updateResponse->assertSessionHas('success');

        $header = PageHeader::where('page_key', 'teams')->first();
        $this->assertNotNull($header);
        $this->assertEquals('DIVISI JUARA ROK ESPORTS', $header->title);
        $this->assertNotNull($header->image_path);
        Storage::disk('public')->assertExists($header->image_path);

        // 3. Frontend teams page reflects updated header
        $teamsResponse = $this->get('/teams');
        $teamsResponse->assertStatus(200);
        $teamsResponse->assertSee('DIVISI JUARA ROK ESPORTS');

        $deleteResponse = $this->actingAs($admin)->delete(route('management.page-headers.delete', 'teams'));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('page_headers', ['page_key' => 'teams']);
    }

    public function test_trial_candidate_decision_email_dispatch_and_exclusive_stage_page(): void
    {
        Mail::fake();
        $admin = User::where('role', 'admin')->first();
        $candidate = TrialCandidate::first();

        // 1. Mark as passed -> Triggers TrialPassedMail and generates next_stage_token
        $passResponse = $this->actingAs($admin)->post(route('management.candidates.status', $candidate), [
            'status' => 'passed',
            'reviewer_notes' => 'Performa LSM dan mechanical gameplay luar biasa.',
            'send_decision_email' => 1,
        ]);

        $passResponse->assertSessionHas('success');
        $candidate->refresh();
        $this->assertEquals('passed', $candidate->status);
        $this->assertNotNull($candidate->next_stage_token);
        $this->assertEquals('passed', $candidate->decision_email_type);
        $this->assertNotNull($candidate->decision_email_sent_at);

        Mail::assertSent(TrialPassedMail::class, function ($mail) use ($candidate) {
            return $mail->hasTo($candidate->email);
        });

        // 2. Access secret stage page with valid token
        $stageResponse = $this->get(route('trial.stage', ['token' => $candidate->next_stage_token]));
        $stageResponse->assertStatus(200);
        $stageResponse->assertSee('AGENDA TAHAP BERIKUTNYA');
        $stageResponse->assertSee('SELAMAT DATANG DI SESI LANJUTAN');
        $stageResponse->assertSee($candidate->name);

        // 3. Re-saving status does not send duplicate email unless explicitly requested
        Mail::fake();
        $this->actingAs($admin)->post(route('management.candidates.status', $candidate), [
            'status' => 'passed',
            'reviewer_notes' => 'Catatan diperbarui tanpa kirim ulang email.',
            'send_decision_email' => 0,
        ]);
        Mail::assertNothingSent();

        // 4. Another candidate marked as rejected sends TrialRejectedMail
        Mail::fake();
        $rejectedCandidate = TrialCandidate::create([
            'name' => 'Rejected Player',
            'email' => 'rejected@example.com',
            'access_token' => 'token-rej-' . uniqid(),
            'status' => 'lsm_completed',
        ]);

        $this->actingAs($admin)->post(route('management.candidates.status', $rejectedCandidate), [
            'status' => 'rejected',
            'reviewer_notes' => 'Kriteria skor LSM belum mencukupi batas minimum.',
            'send_decision_email' => 1,
        ]);

        Mail::assertSent(TrialRejectedMail::class, function ($mail) use ($rejectedCandidate) {
            return $mail->hasTo($rejectedCandidate->email);
        });
    }

    public function test_learning_lesson_pdf_upload_and_display(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->first();
        $module = LearningModule::first();
        $this->assertNotNull($module);

        $fakePdf = UploadedFile::fake()->create('macro-guide.pdf', 300, 'application/pdf');

        // Admin uploads PDF lesson
        $uploadResponse = $this->actingAs($admin)->post(route('management.learning.lessons.store', $module), [
            'title' => 'Buku Panduan Makro & Rotasi',
            'content_type' => 'pdf',
            'pdf_file' => $fakePdf,
            'is_preview' => 1,
            'sort_order' => 10,
        ]);

        $uploadResponse->assertSessionHas('success');

        $lesson = LearningLesson::where('title', 'Buku Panduan Makro & Rotasi')->first();
        $this->assertNotNull($lesson);
        $this->assertEquals('pdf', $lesson->content_type);
        $this->assertNotNull($lesson->pdf_path);
        Storage::disk('public')->assertExists($lesson->pdf_path);

        // Frontend learning development page renders the PDF item
        $frontendResponse = $this->get('/learning-development');
        $frontendResponse->assertStatus(200);
        $frontendResponse->assertSee('Buku Panduan Makro &amp; Rotasi', false);
        $frontendResponse->assertSee('PDF');

        // Delete lesson
        $deleteResponse = $this->actingAs($admin)->delete(route('management.learning.lessons.delete', $lesson));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('learning_lessons', ['id' => $lesson->id]);
    }

    public function test_bracket_formats_membership_tiers_and_no_round_robin(): void
    {
        $response = $this->get('/bracket');
        $response->assertStatus(200);

        // Membership Tiers Section present on Bracket page
        $response->assertSee('ROK Learning');
        $response->assertSee('ROK Challenger');
        $response->assertSee('ROK Immortal');
        $response->assertSee('ORGANIZER MEMBERSHIP TIERS');
        $response->assertSee('Buka Fitur Bracket');

        // Verify format selector options: Round Robin is removed
        $response->assertDontSee('value="round_robin"', false);
        $response->assertDontSee('Round Robin', false);

        // Single elimination bye notification text
        $response->assertSee('Lolos Otomatis ke Babak Berikutnya');

        // Supported formats
        $response->assertSee('Single Elimination');
        $response->assertSee('Swiss Stage');
        $response->assertSee('Double Elimination');
        $response->assertSee('League System');
    }

    public function test_immortal_account_bracket_studio_realtime_sync_whatsapp_and_theme_toggle(): void
    {
        // 1. Verify pre-seeded ROK Immortal user account exists and has active Immortal membership
        $immortalUser = User::where('email', 'immortal@rok.com')->first();
        $this->assertNotNull($immortalUser);
        $this->assertTrue($immortalUser->hasBracketAccess());
        $this->assertTrue($immortalUser->hasLearningAccess());
        $this->assertEquals('immortal', $immortalUser->membershipTierSlug());

        // 2. Verify regular user without membership is redirected when accessing /bracket-studio
        $freeUser = User::create([
            'name' => 'Free Player',
            'email' => 'freeplayer@rok.com',
            'username' => 'freeplayer',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
        $freeAccess = $this->actingAs($freeUser)->get(route('bracket.studio.index'));
        $freeAccess->assertRedirect(route('membership.payment', ['plan' => 'challenger']));

        // 3. Verify ROK Immortal user can access dedicated Bracket Studio page (/bracket-studio)
        $studioDashboard = $this->actingAs($immortalUser)->get(route('bracket.studio.index'));
        $studioDashboard->assertStatus(200);
        $studioDashboard->assertSee('BRACKET STUDIO');
        $studioDashboard->assertSee('BUAT TURNAMEN LIVE BARU');

        // 4. Create a new live tournament from Bracket Studio
        $createResponse = $this->actingAs($immortalUser)->post(route('bracket.studio.store'), [
            'name' => 'ROK Immortal Masters 2026',
            'format' => 'single',
            'team_count' => 8,
            'description' => 'Turnamen resmi member ROK Immortal',
        ]);

        $newTourney = \App\Models\Tournament::where('name', 'ROK Immortal Masters 2026')->first();
        $this->assertNotNull($newTourney);
        $createResponse->assertRedirect(route('bracket.studio.editor', ['slug' => $newTourney->slug]));

        // 5. Open dedicated Bracket Studio editor and verify modern bracket features (SVG arrows, WA modal, theme toggle)
        $editorResponse = $this->actingAs($immortalUser)->get(route('bracket.studio.editor', ['slug' => $newTourney->slug]));
        $editorResponse->assertStatus(200);
        $editorResponse->assertSee('ROK Immortal Masters 2026');
        $editorResponse->assertSee('drawAllBracketArrows', false);
        $editorResponse->assertSee('rokTeamModal', false);
        $editorResponse->assertSee('data-theme-toggle', false);

        // 6. Test real-time sync API (/api/bracket/{slug}/sync) with WhatsApp numbers, custom logos, and scores
        $syncPayload = [
            'title' => 'ROK Immortal Masters 2026 — Finals',
            'format' => 'single',
            'teamCount' => 4,
            'teams' => [
                ['name' => 'ROK Prime', 'whatsapp' => '6281299990001', 'logo' => '/img/rok-logo.png'],
                ['name' => 'Aether Esports', 'whatsapp' => '6281299990002', 'logo' => '/img/dummy-division.svg'],
                ['name' => 'Valkyrie Horizon', 'whatsapp' => '6281299990003', 'logo' => '/img/rok-logo.png'],
                ['name' => 'Shadow Legion', 'whatsapp' => '6281299990004', 'logo' => '/img/dummy-division.svg'],
            ],
            'scores' => [
                'r0_m0' => ['s1' => '2', 's2' => '0'],
                'r0_m1' => ['s1' => '1', 's2' => '2'],
            ],
        ];

        $syncResponse = $this->actingAs($immortalUser)->postJson(route('api.bracket.sync', $newTourney->slug), $syncPayload);
        $syncResponse->assertStatus(200)->assertJson(['ok' => true]);

        // 7. Verify state endpoint and public shareable link (/bracket/live/{slug}) reflect the real-time update
        $stateResponse = $this->getJson(route('api.bracket.state', $newTourney->slug));
        $stateResponse->assertStatus(200)
            ->assertJsonPath('bracket_state.title', 'ROK Immortal Masters 2026 — Finals')
            ->assertJsonPath('bracket_state.teams.0.whatsapp', '6281299990001');

        $liveSharePage = $this->get(route('bracket.live', $newTourney->slug));
        $liveSharePage->assertStatus(200);
        $liveSharePage->assertSee('ROK Immortal Masters 2026 — Finals');
    }

    public function test_pra_tournament_checkin_and_slot_claiming(): void
    {
        $tourney = Tournament::where('slug', 'rok-invitational-season-1')->first();
        $this->assertNotNull($tourney);

        // 1. Visit Pra-Tournament page
        $praPage = $this->get(route('bracket.pra-tournament', $tourney->slug));
        $praPage->assertStatus(200);
        $praPage->assertSee('PORTAL PRA-TURNAMEN');
        $praPage->assertSee('Daftar Slot Peserta Turnamen');

        // 2. Claim a vacant slot (e.g. slot index 4)
        $claimPayload = [
            'slot_index' => 4,
            'team_name' => 'Bintang Esports',
            'whatsapp' => '087712345678',
            'logo' => '/storage/tournaments/team-apex.svg',
        ];

        $claimResponse = $this->postJson(route('api.bracket.claim-slot', $tourney->slug), $claimPayload);
        $claimResponse->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'slot_index' => 4,
            ])
            ->assertJsonPath('team.name', 'Bintang Esports')
            ->assertJsonPath('team.whatsapp', '6287712345678')
            ->assertJsonPath('team.is_claimed', true);

        // Verify Database Persistence
        $this->assertDatabaseHas('tournament_teams', [
            'tournament_id' => $tourney->id,
            'team_name' => 'Bintang Esports',
            'whatsapp_number' => '6287712345678',
            'seed' => 5,
        ]);

        // 3. Attempt to claim the same slot #5 again -> Should fail with 409
        $conflictResponse = $this->postJson(route('api.bracket.claim-slot', $tourney->slug), [
            'slot_index' => 4,
            'team_name' => 'Penyusup Team',
            'whatsapp' => '08999999999',
        ]);
        $conflictResponse->assertStatus(409)
            ->assertJson(['ok' => false]);

        // 4. Attempt to claim another slot with duplicate WhatsApp -> Should fail with 422
        $duplicateWaResponse = $this->postJson(route('api.bracket.claim-slot', $tourney->slug), [
            'slot_index' => 5,
            'team_name' => 'Tim Kloning',
            'whatsapp' => '087712345678', // Same number
        ]);
        $duplicateWaResponse->assertStatus(422)
            ->assertJson(['ok' => false]);

        // 5. Verify Pra-Tournament portal displays Bintang Esports as claimed
        $updatedPraPage = $this->get(route('bracket.pra-tournament', $tourney->slug));
        $updatedPraPage->assertStatus(200);
        $updatedPraPage->assertSee('Bintang Esports');
        $updatedPraPage->assertSee('TERDAFTAR');
    }
}

