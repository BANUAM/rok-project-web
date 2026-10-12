<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\HeroSlide;
use App\Models\Lsm;
use App\Models\MlTier;
use App\Models\Player;
use App\Models\TrialCandidate;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
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
        $response->assertSee('ROK REVAMP');
        $response->assertSee('COMMUNITY');
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
}
