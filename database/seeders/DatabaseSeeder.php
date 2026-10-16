<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Division;
use App\Models\Founder;
use App\Models\HeroSlide;
use App\Models\LearningLesson;
use App\Models\LearningModule;
use App\Models\Lsm;
use App\Models\LsmAttempt;
use App\Models\LsmAttemptAnswer;
use App\Models\LsmOption;
use App\Models\LsmQuestion;
use App\Models\MembershipOrder;
use App\Models\MembershipPlan;
use App\Models\MlTier;
use App\Models\PageHeader;
use App\Models\Partner;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\TournamentTeam;
use App\Models\TrialCandidate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->generateDummySvgFiles();

        // 1. Admin & Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@rok.com'],
            [
                'name' => 'ROK Super Admin',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'coach@rok.com'],
            [
                'name' => 'Coach Alpha',
                'username' => 'coach',
                'password' => Hash::make('coach123'),
                'role' => 'coach',
                'email_verified_at' => now(),
            ]
        );

        // 2. Hero Slides
        $slides = [
            [
                'title' => 'WELCOME TO THE NEW KINGDOM',
                'description' => 'Organisasi esports kompetitif yang berfokus pada kedisiplinan, pengembangan taktik, dan kejayaan bersama di kancah nasional maupun internasional.',
                'image_path' => 'hero-slides/hero-1.svg',
                'cta_primary_text' => 'Join Our Trial',
                'cta_primary_url' => '/trial',
                'cta_secondary_text' => 'Explore Teams',
                'cta_secondary_url' => '/teams',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'FORGING FUTURE CHAMPIONS',
                'description' => 'Melalui proses seleksi LSM ketat dan program Learning & Development terstruktur, kami mencetak pro player bermental juara.',
                'image_path' => 'hero-slides/hero-2.svg',
                'cta_primary_text' => 'Daftar Trial',
                'cta_primary_url' => '/trial',
                'cta_secondary_text' => 'Lihat Prestasi',
                'cta_secondary_url' => '/achievements',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'BUILD. COMPETE. CONQUER.',
                'description' => 'Sistem turnamen dan bracket profesional untuk komunitas esports Indonesia. Buat bagan pertandinganmu sekarang!',
                'image_path' => 'hero-slides/hero-3.svg',
                'cta_primary_text' => 'Coba Bracket',
                'cta_primary_url' => '/bracket',
                'cta_secondary_text' => 'Learning Hub',
                'cta_secondary_url' => '/learning-development',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($slides as $slideData) {
            HeroSlide::updateOrCreate(
                ['title' => $slideData['title']],
                $slideData
            );
        }

        // 3. Founders
        $founders = [
            [
                'name' => 'Reynaldi "King" Pratama',
                'role_title' => 'Founder & CEO',
                'photo_path' => 'founders/founder-1.svg',
                'bio' => 'Visioner di balik Ruler Of Kings dengan pengalaman 7 tahun di industri esports nasional.',
                'instagram_url' => 'https://instagram.com/rok_esport',
                'linkedin_url' => 'https://linkedin.com',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Daffa "Vanguard" Arkan',
                'role_title' => 'Co-Founder & Head of Esports',
                'photo_path' => 'founders/founder-2.svg',
                'bio' => 'Mantan pro player dan arsitek strategi divisi kompetitif Ruler Of Kings.',
                'instagram_url' => 'https://instagram.com/rok_esport',
                'linkedin_url' => 'https://linkedin.com',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($founders as $fData) {
            Founder::updateOrCreate(
                ['name' => $fData['name']],
                $fData
            );
        }

        // 4. Divisions
        $divMen = Division::updateOrCreate(
            ['slug' => 'mlbb-men'],
            [
                'name' => 'ROK Prime (MLBB Men)',
                'game_name' => 'Mobile Legends: Bang Bang',
                'icon_path' => 'divisions/mlbb-icon.svg',
                'banner_path' => 'divisions/mlbb-banner.svg',
                'description' => 'Divisi utama Mobile Legends pria yang berlaga di kompetisi tier 1 & 2 nasional.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $divLadies = Division::updateOrCreate(
            ['slug' => 'mlbb-ladies'],
            [
                'name' => 'ROK Valkyrie (MLBB Ladies)',
                'game_name' => 'Mobile Legends: Bang Bang',
                'icon_path' => 'divisions/mlbb-ladies-icon.svg',
                'banner_path' => 'divisions/mlbb-ladies-banner.svg',
                'description' => 'Divisi Mobile Legends wanita berprestasi tinggi di skena turnamen semi-pro.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 5. Players for Div Men
        $playersMen = [
            // Management
            ['division_id' => $divMen->id, 'real_name' => 'Bagus Wicaksono', 'nickname' => 'Bagus', 'role' => 'Manager', 'age' => 27, 'domicile' => 'Jakarta Barat', 'birthplace' => 'Jakarta', 'birthdate' => '1999-04-12', 'photo_path' => 'players/player-manager.svg', 'status' => 'active', 'sort_order' => 1],
            ['division_id' => $divMen->id, 'real_name' => 'Ferry Santoso', 'nickname' => 'Coach Zen', 'role' => 'Coach', 'age' => 29, 'domicile' => 'Bandung', 'birthplace' => 'Bandung', 'birthdate' => '1997-08-20', 'photo_path' => 'players/player-coach.svg', 'status' => 'active', 'sort_order' => 2],
            ['division_id' => $divMen->id, 'real_name' => 'Rian Hidayat', 'nickname' => 'DataMind', 'role' => 'Analyst', 'age' => 25, 'domicile' => 'Surabaya', 'birthplace' => 'Surabaya', 'birthdate' => '2001-01-15', 'photo_path' => 'players/player-analyst.svg', 'status' => 'active', 'sort_order' => 3],
            // 5 MLBB Roster
            ['division_id' => $divMen->id, 'real_name' => 'Mohammad Tegar', 'nickname' => 'Tegar', 'role' => 'Jungler', 'age' => 20, 'domicile' => 'Bekasi', 'birthplace' => 'Bekasi', 'birthdate' => '2006-03-10', 'photo_path' => 'players/player-jungler.svg', 'status' => 'active', 'sort_order' => 4],
            ['division_id' => $divMen->id, 'real_name' => 'Arya Kusuma', 'nickname' => 'Ares', 'role' => 'Gold Laner', 'age' => 19, 'domicile' => 'Tangerang', 'birthplace' => 'Tangerang', 'birthdate' => '2007-06-18', 'photo_path' => 'players/player-gold.svg', 'status' => 'active', 'sort_order' => 5],
            ['division_id' => $divMen->id, 'real_name' => 'Dimas Pratama', 'nickname' => 'Spectre', 'role' => 'Mid Laner', 'age' => 21, 'domicile' => 'Jakarta Selatan', 'birthplace' => 'Jakarta', 'birthdate' => '2005-11-04', 'photo_path' => 'players/player-mid.svg', 'status' => 'active', 'sort_order' => 6],
            ['division_id' => $divMen->id, 'real_name' => 'Kevin Sanjaya', 'nickname' => 'Titan', 'role' => 'EXP Laner', 'age' => 20, 'domicile' => 'Semarang', 'birthplace' => 'Semarang', 'birthdate' => '2006-02-28', 'photo_path' => 'players/player-exp.svg', 'status' => 'active', 'sort_order' => 7],
            ['division_id' => $divMen->id, 'real_name' => 'Ilham Ramadhan', 'nickname' => 'Guardian', 'role' => 'Roamer', 'age' => 22, 'domicile' => 'Yogyakarta', 'birthplace' => 'Yogyakarta', 'birthdate' => '2004-09-15', 'photo_path' => 'players/player-roamer.svg', 'status' => 'active', 'sort_order' => 8],
        ];

        foreach ($playersMen as $pData) {
            Player::updateOrCreate(
                ['nickname' => $pData['nickname'], 'division_id' => $pData['division_id']],
                $pData
            );
        }

        // 6. Achievements
        $achievements = [
            [
                'division_id' => $divMen->id,
                'title' => 'Juara 1 — National Esports League Season 4',
                'team_name' => 'ROK Prime',
                'image_path' => 'achievements/trophy-1.svg',
                'caption' => 'Memenangkan babak Grand Final dengan skor dramatis 3-2 dan mengamankan slot turnamen regional.',
                'event_year' => 2026,
                'event_date' => '2026-08-15',
                'is_highlight' => true,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'division_id' => $divMen->id,
                'title' => 'Runner Up — MLBB University Masters Cup',
                'team_name' => 'ROK Prime',
                'image_path' => 'achievements/trophy-2.svg',
                'caption' => 'Performa solid sepanjang 8 ronde babak kualifikasi hingga mencapai podium kejuaraan.',
                'event_year' => 2025,
                'event_date' => '2025-11-20',
                'is_highlight' => true,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'division_id' => $divLadies->id,
                'title' => 'Juara 1 — Queen of Dawn Invitational',
                'team_name' => 'ROK Valkyrie',
                'image_path' => 'achievements/trophy-3.svg',
                'caption' => 'Dominasi tanpa kekalahan (undefeated streak) dari babak group stage hingga grand final.',
                'event_year' => 2025,
                'event_date' => '2025-06-10',
                'is_highlight' => true,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'division_id' => $divMen->id,
                'title' => 'Best Rookie Esports Organization 2024',
                'team_name' => 'Ruler Of Kings',
                'image_path' => 'achievements/trophy-4.svg',
                'caption' => 'Penghargaan bergengsi atas manajemen tim terbaik dan regenerasi talenta muda tercepat.',
                'event_year' => 2024,
                'event_date' => '2024-12-18',
                'is_highlight' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achData) {
            Achievement::updateOrCreate(
                ['title' => $achData['title']],
                $achData
            );
        }

        // 7. Partners / Sponsors
        $partners = [
            ['name' => 'AORUS Gaming', 'logo_path' => 'partners/partner-1.svg', 'category' => 'Official Gaming Partner', 'website_url' => 'https://aorus.com', 'description' => 'Penyedia hardware gaming performa tinggi untuk ROK Bootcamp.', 'sort_order' => 1],
            ['name' => 'Secretlab', 'logo_path' => 'partners/partner-2.svg', 'category' => 'Official Gaming Chair', 'website_url' => 'https://secretlab.co', 'description' => 'Kursi gaming ergonomis resmi skuad Ruler Of Kings.', 'sort_order' => 2],
            ['name' => 'Monster Energy', 'logo_path' => 'partners/partner-3.svg', 'category' => 'Official Energy Drink', 'website_url' => 'https://monsterenergy.com', 'description' => 'Bahan bakar energi para roster saat sesi latihan dan turnamen.', 'sort_order' => 3],
            ['name' => 'RevivalTV', 'logo_path' => 'partners/partner-4.svg', 'category' => 'Official Media Partner', 'website_url' => 'https://revivaltv.id', 'description' => 'Liputan eksklusif dan siaran berita resmi ROK Revamp.', 'sort_order' => 4],
            ['name' => 'SteelSeries', 'logo_path' => 'partners/partner-5.svg', 'category' => 'Official Peripheral Partner', 'website_url' => 'https://steelseries.com', 'description' => 'Headset dan audio gear kompetitif turnamen.', 'sort_order' => 5],
            ['name' => 'Telkomsel Esports Hub', 'logo_path' => 'partners/partner-6.svg', 'category' => 'Community Partner', 'website_url' => 'https://telkomsel.com', 'description' => 'Dukungan jaringan internet ultra low-latency untuk seluruh ekosistem ROK.', 'sort_order' => 6],
        ];

        foreach ($partners as $partData) {
            Partner::updateOrCreate(
                ['name' => $partData['name']],
                $partData
            );
        }

        // 8. ML Tiers
        $tiers = [
            ['name' => 'Warrior', 'sort_order' => 1],
            ['name' => 'Elite', 'sort_order' => 2],
            ['name' => 'Master', 'sort_order' => 3],
            ['name' => 'Grandmaster', 'sort_order' => 4],
            ['name' => 'Epic', 'sort_order' => 5],
            ['name' => 'Legend', 'sort_order' => 6],
            ['name' => 'Mythic', 'sort_order' => 7],
            ['name' => 'Mythical Honor', 'sort_order' => 8],
            ['name' => 'Mythical Glory', 'sort_order' => 9],
            ['name' => 'Mythical Immortal', 'sort_order' => 10],
        ];

        foreach ($tiers as $t) {
            MlTier::updateOrCreate(['name' => $t['name']], $t);
        }

        $immortalTier = MlTier::where('name', 'Mythical Immortal')->first();
        $gloryTier = MlTier::where('name', 'Mythical Glory')->first();

        // 9. LSM Test & Questions
        $lsm = Lsm::updateOrCreate(
            ['title' => 'LSM Seleksi Calon Roster ROK 2026'],
            [
                'description' => 'Ujian tertulis taktik dan pemahaman strategi (Macro & Micro) untuk calon atlet esports Ruler Of Kings. Harap jawab dengan jujur dan teliti.',
                'question_limit' => 6,
                'duration_minutes' => 15,
                'passing_score' => 70,
                'is_published' => true,
            ]
        );

        $questionsData = [
            [
                'question' => 'Kapan waktu terbaik bagi seorang Mid Laner untuk melakukan rotasi ke arah Gold Lane saat early game?',
                'image_path' => 'lsm/q-rotasi.svg',
                'sort_order' => 1,
                'options' => [
                    ['option_text' => 'Langsung setelah membersihkan wave minion wave 1 tanpa memperhatikan posisi roamer musuh', 'is_correct' => false],
                    ['option_text' => 'Setelah membersihkan mid wave pertama bersama Roamer dengan memastikan informasi jungle musuh aman', 'is_correct' => true],
                    ['option_text' => 'Menunggu hingga turtle pertama muncul di menit ke-2', 'is_correct' => false],
                    ['option_text' => 'Hanya saat Gold Laner tim terbunuh oleh musuh', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Pada skenario perebutan Lord menit ke-12, tugas utama seorang EXP Laner dengan hero Frontline Crowd Control adalah:',
                'image_path' => null,
                'sort_order' => 2,
                'options' => [
                    ['option_text' => 'Fokus memukul Lord bersama Jungler', 'is_correct' => false],
                    ['option_text' => 'Membuka bush di arah masuk musuh dan menzoning jungler lawan agar tidak bisa melakukan retribution', 'is_correct' => true],
                    ['option_text' => 'Melakukan split push sendirian di turret musuh yang terjauh', 'is_correct' => false],
                    ['option_text' => 'Menunggu di base hingga pertempuran selesai', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Dalam fase Draft Pick, musuh memilih komposisi dive-in agresif (Fanny, Martis, Chou). Hero Roamer yang paling efektif untuk counter-pick adalah:',
                'image_path' => null,
                'sort_order' => 3,
                'options' => [
                    ['option_text' => 'Khufra atau Ruby yang memiliki skill anti-dash dan hard crowd control instan', 'is_correct' => true],
                    ['option_text' => 'Angela yang hanya fokus healing', 'is_correct' => false],
                    ['option_text' => 'Saber damage full assassin', 'is_correct' => false],
                    ['option_text' => 'Estes tanpa item defense', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Apa arti istilah "Wave Management - Freezing Lane" dan tujuannya?',
                'image_path' => null,
                'sort_order' => 4,
                'options' => [
                    ['option_text' => 'Mendorong minion secepat mungkin hingga menabrak tower lawan', 'is_correct' => false],
                    ['option_text' => 'Hanya melakukan last hit pada minion lawan untuk menahan titik temu minion di dekat tower sendiri sehingga musuh kehilangan exp/gold', 'is_correct' => true],
                    ['option_text' => 'Membeli item ice/slow agar minion berjalan lambat', 'is_correct' => false],
                    ['option_text' => 'Meninggalkan lane kosong selama 3 menit', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Ketika tim kamu tertinggal 5.000 gold di menit ke-8, keputusan makro paling rasional adalah:',
                'image_path' => null,
                'sort_order' => 5,
                'options' => [
                    ['option_text' => 'Memaksakan team fight 5v5 di river jungle musuh', 'is_correct' => false],
                    ['option_text' => 'Bermain disiplin di area high ground / defensive positioning, prioritaskan clear minion dan cari pick-off tunggal saat musuh overextend', 'is_correct' => true],
                    ['option_text' => 'Semua pemain fokus jungle dan membiarkan tower hancur', 'is_correct' => false],
                    ['option_text' => 'Surrender sebelum menit ke-10', 'is_correct' => false],
                ],
            ],
            [
                'question' => 'Bagaimana cara komunikasi paling efisien di in-game voice chat saat team fight krusial berlangsung?',
                'image_path' => null,
                'sort_order' => 6,
                'options' => [
                    ['option_text' => 'Menyalahkan rekan tim yang salah positioning', 'is_correct' => false],
                    ['option_text' => 'Panggilan informasi padat & jelas (fokus target/focussing, cooldown spell/retri musuh, posisi flanking)', 'is_correct' => true],
                    ['option_text' => 'Berbicara hal di luar pertandingan agar suasana santai', 'is_correct' => false],
                    ['option_text' => 'Mematikan mikrofon dan bermain solo', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questionsData as $qData) {
            $question = LsmQuestion::updateOrCreate(
                ['lsm_id' => $lsm->id, 'sort_order' => $qData['sort_order']],
                [
                    'question' => $qData['question'],
                    'image_path' => $qData['image_path'],
                ]
            );

            // Options
            foreach ($qData['options'] as $opt) {
                LsmOption::updateOrCreate(
                    ['lsm_question_id' => $question->id, 'option_text' => $opt['option_text']],
                    ['is_correct' => $opt['is_correct']]
                );
            }
        }

        // 10. Sample Trial Candidates
        $candidate1 = TrialCandidate::updateOrCreate(
            ['email' => 'farhan.trials@example.com'],
            [
                'name' => 'Farhan Alamsyah',
                'access_token' => 'rok_test_token_sample_candidate_01',
                'token_expires_at' => now()->addDays(30),
                'nickname' => 'ViperX',
                'address' => 'Jl. Tebet Raya No. 42',
                'age' => 19,
                'birthplace' => 'Jakarta',
                'birthdate' => '2007-05-14',
                'role' => 'Gold Laner',
                'ml_tier_id' => $immortalTier?->id,
                'stars' => 125,
                'profile_screenshot_path' => 'trial-uploads/sample-profile-1.svg',
                'favorite_hero_screenshot_path' => 'trial-uploads/sample-hero-1.svg',
                'tier_screenshot_path' => 'trial-uploads/sample-tier-1.svg',
                'status' => 'lsm_completed',
                'profile_completed_at' => now()->subDays(2),
                'lsm_completed_at' => now()->subDays(1),
                'reviewed_at' => null,
                'reviewer_notes' => 'Mekanik Claude & Harith sangat stabil, nilai LSM memuaskan.',
            ]
        );

        // Attempt for Candidate 1
        $attempt1 = LsmAttempt::updateOrCreate(
            ['trial_candidate_id' => $candidate1->id, 'lsm_id' => $lsm->id],
            [
                'total_questions' => 6,
                'correct_answers' => 5,
                'score' => 83.33,
                'is_passed' => true,
                'completed_at' => now()->subDays(1),
            ]
        );

        $candidate2 = TrialCandidate::updateOrCreate(
            ['email' => 'danu.jungler@example.com'],
            [
                'name' => 'Danu Prasetyo',
                'access_token' => 'rok_test_token_sample_candidate_02',
                'token_expires_at' => now()->addDays(30),
                'nickname' => 'GhostHunter',
                'address' => 'Jl. Dago Asri No. 15',
                'age' => 20,
                'birthplace' => 'Bandung',
                'birthdate' => '2006-08-22',
                'role' => 'Jungler',
                'ml_tier_id' => $gloryTier?->id,
                'stars' => 85,
                'profile_screenshot_path' => 'trial-uploads/sample-profile-2.svg',
                'favorite_hero_screenshot_path' => 'trial-uploads/sample-hero-2.svg',
                'tier_screenshot_path' => 'trial-uploads/sample-tier-2.svg',
                'status' => 'under_review',
                'profile_completed_at' => now()->subDays(3),
                'lsm_completed_at' => now()->subDays(2),
                'reviewed_at' => now()->subDay(),
                'reviewer_notes' => 'Hero pool assassin luas (Ling, Hayabusa). Masuk tahap wawancara offline.',
            ]
        );

        LsmAttempt::updateOrCreate(
            ['trial_candidate_id' => $candidate2->id, 'lsm_id' => $lsm->id],
            [
                'total_questions' => 6,
                'correct_answers' => 6,
                'score' => 100.0,
                'is_passed' => true,
                'completed_at' => now()->subDays(2),
            ]
        );

        // 11. Learning & Development Modules
        $mod1 = LearningModule::updateOrCreate(
            ['slug' => 'macro-mastery-and-rotation'],
            [
                'title' => 'Macro Mastery & Early Game Rotation',
                'category' => 'Macro Strategy',
                'description' => 'Panduan komprehensif rotasi 3 lane, penguasaan river bush, dan sinkronisasi tempo antara Jungler dan Roamer.',
                'thumbnail_path' => 'modules/module-macro.svg',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        LearningLesson::updateOrCreate(
            ['learning_module_id' => $mod1->id, 'title' => 'Fundamental Rotasi 90 Detik Pertama'],
            [
                'video_url' => 'https://www.youtube-nocookie.com/embed/qgc7QE04ELs',
                'duration' => '14:20',
                'summary' => 'Memahami pergerakan minion wave awal dan kalkulasi level 2 rush.',
                'content' => 'Dalam bab ini, analis ROK membedah cara membaca start buff musuh dan mengeksekusi invasi aman.',
                'is_free_preview' => true,
                'sort_order' => 1,
            ]
        );

        LearningLesson::updateOrCreate(
            ['learning_module_id' => $mod1->id, 'title' => 'Zoning & Setup Kontrol Turtle'],
            [
                'video_url' => 'https://www.youtube-nocookie.com/embed/qgc7QE04ELs',
                'duration' => '18:45',
                'summary' => 'Formasi trap dan positioning crowd control di pit turtle.',
                'content' => 'Pelajari cara mendirikan perimeter aman 15 detik sebelum objektif turtle muncul di map.',
                'is_free_preview' => false,
                'sort_order' => 2,
            ]
        );

        $mod2 = LearningModule::updateOrCreate(
            ['slug' => 'draft-pick-and-counter-meta'],
            [
                'title' => 'Draft Pick & Counter Metagame',
                'category' => 'Draft Strategy',
                'description' => 'Menganalisis komposisi power pick lawan, flex pick, dan menyusun ban prioritas turnamen.',
                'thumbnail_path' => 'modules/module-draft.svg',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        LearningLesson::updateOrCreate(
            ['learning_module_id' => $mod2->id, 'title' => 'Prinsip Rock-Paper-Scissors Komposisi Hero'],
            [
                'content_type' => 'video',
                'video_url' => 'https://www.youtube-nocookie.com/embed/qgc7QE04ELs',
                'duration' => '22:10',
                'summary' => 'Mengenali kelemahan teamfight comp, pick-off comp, dan split-push comp.',
                'content' => 'Cara mengidentifikasi win condition tim kamu sejak draft pick fase kedua.',
                'is_free_preview' => false,
                'sort_order' => 1,
            ]
        );

        LearningLesson::updateOrCreate(
            ['learning_module_id' => $mod2->id, 'title' => 'Panduan Strategi Draft & Ban Turnamen (PDF Playbook)'],
            [
                'content_type' => 'pdf',
                'video_url' => null,
                'pdf_path' => 'lessons/draft-pick-bible.pdf',
                'pdf_filename' => 'ROK_Draft_Pick_Strategy_Manual_2026.pdf',
                'file_size' => 2450000,
                'duration' => '15 Hal PDF',
                'summary' => 'Dokumen panduan komprehensif draft & counter hero tier S turnamen resmi.',
                'content' => 'Playbook taktik format PDF yang dirancang oleh tim analis pro ROK.',
                'is_free_preview' => true,
                'sort_order' => 2,
            ]
        );

        // 12. Tournaments & Bracket
        $tourney = Tournament::updateOrCreate(
            ['slug' => 'rok-invitational-season-1'],
            [
                'name' => 'ROK National Invitational Season 1',
                'format' => 'single_elimination',
                'banner_path' => 'tournaments/tourney-banner.svg',
                'status' => 'live',
                'organizer_name' => 'ROK Organizer Official',
                'tier_required' => 'challenger',
                'description' => 'Turnamen bergengsi 8 tim terbaik komunitas esports dengan hadiah total Rp 25.000.000.',
                'rules' => 'Format Single Elimination BO3, Grand Final BO5. Aturan resmi MPL Indonesia diterapkan.',
            ]
        );

        $teams = [
            ['team_name' => 'ROK Prime', 'logo_path' => 'tournaments/team-rok.svg', 'whatsapp_number' => '6281234567890', 'seed' => 1],
            ['team_name' => 'Aether Esports', 'logo_path' => 'tournaments/team-aether.svg', 'whatsapp_number' => '6281234567891', 'seed' => 2],
            ['team_name' => 'Valkyrie Horizon', 'logo_path' => 'tournaments/team-valkyrie.svg', 'whatsapp_number' => '6281234567892', 'seed' => 3],
            ['team_name' => 'Shadow Legion', 'logo_path' => 'tournaments/team-shadow.svg', 'whatsapp_number' => '6281234567893', 'seed' => 4],
            ['team_name' => 'Apex Titans', 'logo_path' => 'tournaments/team-apex.svg', 'whatsapp_number' => '6281234567894', 'seed' => 5],
            ['team_name' => 'Celestial Wolves', 'logo_path' => 'tournaments/team-wolves.svg', 'whatsapp_number' => '6281234567895', 'seed' => 6],
            ['team_name' => 'Kitsune Vanguard', 'logo_path' => 'tournaments/team-kitsune.svg', 'whatsapp_number' => '6281234567896', 'seed' => 7],
            ['team_name' => 'Solar Eclipse', 'logo_path' => 'tournaments/team-eclipse.svg', 'whatsapp_number' => '6281234567897', 'seed' => 8],
        ];

        $teamModels = [];
        foreach ($teams as $tData) {
            $teamModels[] = TournamentTeam::updateOrCreate(
                ['tournament_id' => $tourney->id, 'team_name' => $tData['team_name']],
                $tData
            );
        }

        // Bracket Matches: Quarter Finals (Round 1)
        $m1 = TournamentMatch::updateOrCreate(
            ['tournament_id' => $tourney->id, 'round_number' => 1, 'match_number' => 1],
            ['round_name' => 'Quarter Finals', 'team1_id' => $teamModels[0]->id, 'team2_id' => $teamModels[7]->id, 'score1' => 2, 'score2' => 0, 'winner_id' => $teamModels[0]->id, 'status' => 'completed']
        );
        $m2 = TournamentMatch::updateOrCreate(
            ['tournament_id' => $tourney->id, 'round_number' => 1, 'match_number' => 2],
            ['round_name' => 'Quarter Finals', 'team1_id' => $teamModels[3]->id, 'team2_id' => $teamModels[4]->id, 'score1' => 2, 'score2' => 1, 'winner_id' => $teamModels[3]->id, 'status' => 'completed']
        );
        $m3 = TournamentMatch::updateOrCreate(
            ['tournament_id' => $tourney->id, 'round_number' => 1, 'match_number' => 3],
            ['round_name' => 'Quarter Finals', 'team1_id' => $teamModels[1]->id, 'team2_id' => $teamModels[6]->id, 'score1' => 2, 'score2' => 0, 'winner_id' => $teamModels[1]->id, 'status' => 'completed']
        );
        $m4 = TournamentMatch::updateOrCreate(
            ['tournament_id' => $tourney->id, 'round_number' => 1, 'match_number' => 4],
            ['round_name' => 'Quarter Finals', 'team1_id' => $teamModels[2]->id, 'team2_id' => $teamModels[5]->id, 'score1' => 2, 'score2' => 1, 'winner_id' => $teamModels[2]->id, 'status' => 'completed']
        );

        // Semi Finals (Round 2)
        $m5 = TournamentMatch::updateOrCreate(
            ['tournament_id' => $tourney->id, 'round_number' => 2, 'match_number' => 1],
            ['round_name' => 'Semi Finals', 'team1_id' => $teamModels[0]->id, 'team2_id' => $teamModels[3]->id, 'score1' => 2, 'score2' => 1, 'winner_id' => $teamModels[0]->id, 'status' => 'completed']
        );
        $m6 = TournamentMatch::updateOrCreate(
            ['tournament_id' => $tourney->id, 'round_number' => 2, 'match_number' => 2],
            ['round_name' => 'Semi Finals', 'team1_id' => $teamModels[1]->id, 'team2_id' => $teamModels[2]->id, 'score1' => 1, 'score2' => 2, 'winner_id' => $teamModels[2]->id, 'status' => 'completed']
        );

        // Grand Finals (Round 3)
        TournamentMatch::updateOrCreate(
            ['tournament_id' => $tourney->id, 'round_number' => 3, 'match_number' => 1],
            ['round_name' => 'Grand Finals', 'team1_id' => $teamModels[0]->id, 'team2_id' => $teamModels[2]->id, 'score1' => null, 'score2' => null, 'winner_id' => null, 'status' => 'live']
        );

        // 13. Page Header Banners (Manageable via Admin)
        $headers = [
            [
                'page_key' => 'teams',
                'title' => 'TEAMS & ROSTER',
                'subtitle' => 'Mengenal kepemimpinan, jajaran manajerial, pelatih, analis, serta atlet profesional di balik kejayaan Ruler Of Kings.',
                'image_path' => 'page-headers/teams-banner.svg',
            ],
            [
                'page_key' => 'trial',
                'title' => 'JOIN OUR TRIAL',
                'subtitle' => 'Gerbang resmi menuju pro-scene bersama Ruler Of Kings. Daftarkan diri, ikuti tes seleksi LSM, dan buktikan kemampuanmu.',
                'image_path' => 'page-headers/trial-banner.svg',
            ],
            [
                'page_key' => 'achievements',
                'title' => 'ACHIEVEMENTS & TROPHIES',
                'subtitle' => 'Pencapaian dan trofi yang membuktikan dedikasi serta konsistensi Ruler Of Kings di kancah turnamen kompetitif.',
                'image_path' => 'page-headers/achievements-banner.svg',
            ],
            [
                'page_key' => 'learning',
                'title' => 'LEARNING & DEVELOPMENT',
                'subtitle' => 'Tingkatkan skill mikro, pemahaman makro, dan mentalitas berkompetisi melalui modul terstruktur dan materi strategi PDF resmi.',
                'image_path' => 'page-headers/learning-banner.svg',
            ],
            [
                'page_key' => 'bracket',
                'title' => 'BUILD. COMPETE. CONQUER.',
                'subtitle' => 'Your Tournament. Your Bracket. Your Champion. Kelola bagan turnamen esports komunitasmu dengan mudah.',
                'image_path' => 'page-headers/bracket-banner.svg',
            ],
        ];

        foreach ($headers as $hData) {
            PageHeader::updateOrCreate(
                ['page_key' => $hData['page_key']],
                $hData
            );
        }

        // 14. Membership Plans
        $plans = [
            [
                'slug' => 'learning',
                'name' => 'ROK Learning',
                'badge_title' => 'ACADEMY ACCESS',
                'price' => 49000,
                'billing_cycle' => 'per bulan',
                'description' => 'Akses penuh ke modul taktik, bedah gameplay turnamen pro, dan materi bacaan PDF eksklusif.',
                'features' => [
                    'Akses seluruh modul pembelajaran taktik pro',
                    'Akses tayangan video strategi breakdown',
                    'Unduh materi kurikulum & playbook dalam format PDF',
                    'Evaluasi interaktif pemahaman game',
                    'Forum diskusi dan tanya jawab akademi',
                ],
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'slug' => 'challenger',
                'name' => 'ROK Challenger',
                'badge_title' => 'ORGANIZER PREMIUM',
                'price' => 99000,
                'billing_cycle' => 'per turnamen',
                'description' => 'Solusi lengkap organizer untuk menggelar turnamen profesional dengan kustomisasi logo tim dan banner resmi.',
                'features' => [
                    'Buat bagan turnamen tanpa batasan jumlah tim',
                    'Sematkan Logo Resmi seluruh Tim peserta di bagan',
                    'Gunakan Banner & Kustomisasi Branding turnamen',
                    'Tampilan Live Bracket interaktif untuk penonton',
                    'Export hasil bagan ke format visual resolusi tinggi',
                ],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'immortal',
                'name' => 'ROK Immortal',
                'badge_title' => 'ALL ACCESS KINGDOM',
                'price' => 199000,
                'billing_cycle' => 'per bulan',
                'description' => 'Paket terlengkap menggabungkan seluruh fitur Organizer Challenger dan Akademi Pro Learning.',
                'features' => [
                    'Seluruh fitur Organizer ROK Challenger',
                    'Seluruh materi & video eksklusif ROK Learning',
                    'Akses bebas ke modul strategi PDF terlengkap',
                    'Prioritas konsultasi operasional turnamen',
                    'Badge eksklusif Immortal di ekosistem ROK',
                ],
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $pData) {
            MembershipPlan::updateOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );
        }

        // 15. Dedicated ROK Immortal & Challenger User Accounts (Pre-activated Membership)
        $immortalPlan = MembershipPlan::where('slug', 'immortal')->first();
        $challengerPlan = MembershipPlan::where('slug', 'challenger')->first();

        $immortalUser = User::updateOrCreate(
            ['email' => 'immortal@rok.com'],
            [
                'name' => 'Sultan ROK Immortal',
                'username' => 'immortal',
                'password' => Hash::make('immortal123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        if ($immortalPlan) {
            MembershipOrder::updateOrCreate(
                ['order_number' => 'ROK-ORD-IMMO-0001'],
                [
                    'user_id' => $immortalUser->id,
                    'membership_plan_id' => $immortalPlan->id,
                    'amount' => $immortalPlan->price,
                    'payment_method' => 'qris',
                    'payment_status' => 'paid',
                    'payment_reference' => 'TRX-IMMORTALVIP01',
                    'notes' => 'Akun Khusus Member Resmi ROK Immortal (All Access)',
                    'paid_at' => now(),
                ]
            );
        }

        $challengerUser = User::updateOrCreate(
            ['email' => 'challenger@rok.com'],
            [
                'name' => 'Organizer ROK Challenger',
                'username' => 'challenger',
                'password' => Hash::make('challenger123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        if ($challengerPlan) {
            MembershipOrder::updateOrCreate(
                ['order_number' => 'ROK-ORD-CHAL-0002'],
                [
                    'user_id' => $challengerUser->id,
                    'membership_plan_id' => $challengerPlan->id,
                    'amount' => $challengerPlan->price,
                    'payment_method' => 'bank_bca',
                    'payment_status' => 'paid',
                    'payment_reference' => 'TRX-CHALLENGER02',
                    'notes' => 'Akun Khusus Member Resmi ROK Challenger (Bracket Organizer)',
                    'paid_at' => now(),
                ]
            );
        }

        // Populate rich initial live bracket_state on the main tournament
        $initialTeamsState = [
            ['id' => 1, 'seed' => 1, 'name' => 'ROK Prime', 'whatsapp' => '6281234567890', 'logo' => '/storage/tournaments/team-rok.svg'],
            ['id' => 2, 'seed' => 2, 'name' => 'Aether Esports', 'whatsapp' => '6281234567891', 'logo' => '/storage/tournaments/team-aether.svg'],
            ['id' => 3, 'seed' => 3, 'name' => 'Valkyrie Horizon', 'whatsapp' => '6281234567892', 'logo' => '/storage/tournaments/team-valkyrie.svg'],
            ['id' => 4, 'seed' => 4, 'name' => 'Shadow Legion', 'whatsapp' => '6281234567893', 'logo' => '/storage/tournaments/team-shadow.svg'],
            ['id' => 5, 'seed' => 5, 'name' => 'Apex Titans', 'whatsapp' => '6281234567894', 'logo' => '/storage/tournaments/team-apex.svg'],
            ['id' => 6, 'seed' => 6, 'name' => 'Celestial Wolves', 'whatsapp' => '6281234567895', 'logo' => '/storage/tournaments/team-wolves.svg'],
            ['id' => 7, 'seed' => 7, 'name' => 'Kitsune Vanguard', 'whatsapp' => '6281234567896', 'logo' => '/storage/tournaments/team-kitsune.svg'],
            ['id' => 8, 'seed' => 8, 'name' => 'Solar Eclipse', 'whatsapp' => '6281234567897', 'logo' => '/storage/tournaments/team-eclipse.svg'],
        ];

        $tourney->update([
            'user_id' => $immortalUser->id,
            'team_count' => 8,
            'is_public' => true,
            'bracket_state' => [
                'title' => 'ROK National Invitational Season 1',
                'format' => 'single',
                'teamCount' => 8,
                'teams' => $initialTeamsState,
                'scores' => [
                    'R1-M1' => ['score1' => 2, 'score2' => 0],
                    'R1-M2' => ['score1' => 2, 'score2' => 1],
                    'R1-M3' => ['score1' => 2, 'score2' => 0],
                    'R1-M4' => ['score1' => 2, 'score2' => 1],
                    'R2-M1' => ['score1' => 2, 'score2' => 1],
                    'R2-M2' => ['score1' => 1, 'score2' => 2],
                ],
                'updatedAt' => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * Helper to write crisp esports SVG placeholders
     */
    protected function generateDummySvgFiles(): void
    {
        $disk = Storage::disk('public');

        $svgDefinitions = [
            'hero-slides/hero-1.svg' => $this->createBannerSvg('RULER OF KINGS', 'ONE VISION. ONE TEAM. ONE KINGDOM', '#0b0e14', '#d4af37'),
            'hero-slides/hero-2.svg' => $this->createBannerSvg('ROK TALENT RECRUITMENT', 'LSM TRIAL SELECTION 2026', '#0a0d16', '#3b82f6'),
            'hero-slides/hero-3.svg' => $this->createBannerSvg('TOURNAMENT BRACKET HUB', 'BUILD. COMPETE. CONQUER.', '#0f0c14', '#8b5cf6'),
            'founders/founder-1.svg' => $this->createAvatarSvg('REYNALDI PRATAMA', 'CEO & Founder', '#d4af37'),
            'founders/founder-2.svg' => $this->createAvatarSvg('DAFFA ARKAN', 'Head of Esports', '#9ca3af'),
            'divisions/mlbb-icon.svg' => $this->createIconSvg('MLBB', '#d4af37'),
            'divisions/mlbb-banner.svg' => $this->createBannerSvg('ROK PRIME', 'MOBILE LEGENDS MEN', '#0d1117', '#d4af37'),
            'divisions/mlbb-ladies-icon.svg' => $this->createIconSvg('ROK V', '#ec4899'),
            'divisions/mlbb-ladies-banner.svg' => $this->createBannerSvg('ROK VALKYRIE', 'MOBILE LEGENDS LADIES', '#130c14', '#ec4899'),
            'players/player-manager.svg' => $this->createAvatarSvg('BAGUS', 'Manager', '#64748b'),
            'players/player-coach.svg' => $this->createAvatarSvg('COACH ZEN', 'Head Coach', '#f59e0b'),
            'players/player-analyst.svg' => $this->createAvatarSvg('DATAMIND', 'Analyst', '#3b82f6'),
            'players/player-jungler.svg' => $this->createPlayerSvg('TEGAR', 'Jungler', '#ef4444'),
            'players/player-gold.svg' => $this->createPlayerSvg('ARES', 'Gold Laner', '#eab308'),
            'players/player-mid.svg' => $this->createPlayerSvg('SPECTRE', 'Mid Laner', '#a855f7'),
            'players/player-exp.svg' => $this->createPlayerSvg('TITAN', 'EXP Laner', '#10b981'),
            'players/player-roamer.svg' => $this->createPlayerSvg('GUARDIAN', 'Roamer', '#06b6d4'),
            'achievements/trophy-1.svg' => $this->createTrophySvg('CHAMPION', 'National Esports League Season 4', '#d4af37'),
            'achievements/trophy-2.svg' => $this->createTrophySvg('RUNNER UP', 'MLBB University Masters Cup', '#94a3b8'),
            'achievements/trophy-3.svg' => $this->createTrophySvg('CHAMPION', 'Queen of Dawn Invitational', '#f43f5e'),
            'achievements/trophy-4.svg' => $this->createTrophySvg('AWARD', 'Best Rookie Organization 2024', '#38bdf8'),
            'partners/partner-1.svg' => $this->createPartnerSvg('AORUS', '#f97316'),
            'partners/partner-2.svg' => $this->createPartnerSvg('SECRETLAB', '#e2e8f0'),
            'partners/partner-3.svg' => $this->createPartnerSvg('MONSTER', '#84cc16'),
            'partners/partner-4.svg' => $this->createPartnerSvg('REVIVAL TV', '#ef4444'),
            'partners/partner-5.svg' => $this->createPartnerSvg('STEELSERIES', '#f97316'),
            'partners/partner-6.svg' => $this->createPartnerSvg('TELKOMSEL', '#dc2626'),
            'lsm/q-rotasi.svg' => $this->createDiagramSvg('LSM Tactical Question: MLBB Map Overview'),
            'trial-uploads/sample-profile-1.svg' => $this->createScreenshotSvg('MLBB Profile Screenshot (ViperX)'),
            'trial-uploads/sample-hero-1.svg' => $this->createScreenshotSvg('Favorite Hero: Claude 82% WR'),
            'trial-uploads/sample-tier-1.svg' => $this->createScreenshotSvg('Mythical Immortal 125 Stars'),
            'trial-uploads/sample-profile-2.svg' => $this->createScreenshotSvg('MLBB Profile Screenshot (GhostHunter)'),
            'trial-uploads/sample-hero-2.svg' => $this->createScreenshotSvg('Favorite Hero: Ling 78% WR'),
            'trial-uploads/sample-tier-2.svg' => $this->createScreenshotSvg('Mythical Glory 85 Stars'),
            'modules/module-macro.svg' => $this->createBannerSvg('MACRO STRATEGY', 'Module 01: Rotation & Tempo', '#091528', '#38bdf8'),
            'modules/module-draft.svg' => $this->createBannerSvg('DRAFT ANALYSIS', 'Module 02: Counter Pick Logic', '#1c0a2a', '#c084fc'),
            'tournaments/tourney-banner.svg' => $this->createBannerSvg('ROK INVITATIONAL', 'SEASON 1 GRAND CHAMPIONSHIP', '#0a0d14', '#d4af37'),
            'tournaments/team-rok.svg' => $this->createTeamBadgeSvg('ROK', '#d4af37'),
            'tournaments/team-aether.svg' => $this->createTeamBadgeSvg('AET', '#38bdf8'),
            'tournaments/team-valkyrie.svg' => $this->createTeamBadgeSvg('VLK', '#f43f5e'),
            'tournaments/team-shadow.svg' => $this->createTeamBadgeSvg('SHD', '#818cf8'),
            'tournaments/team-apex.svg' => $this->createTeamBadgeSvg('APX', '#fbbf24'),
            'tournaments/team-wolves.svg' => $this->createTeamBadgeSvg('WLV', '#34d399'),
            'tournaments/team-kitsune.svg' => $this->createTeamBadgeSvg('KTZ', '#fb7185'),
            'tournaments/team-eclipse.svg' => $this->createTeamBadgeSvg('ECL', '#94a3b8'),
            'page-headers/teams-banner.svg' => $this->createBannerSvg('TEAMS & ROSTER', 'RULER OF KINGS PRO ESPORTS DIVISIONS', '#0b0f19', '#cbd5e1'),
            'page-headers/trial-banner.svg' => $this->createBannerSvg('JOIN OUR TRIAL', 'BECOME A LEGENDARY ROK WARRIOR', '#090e17', '#94a3b8'),
            'page-headers/achievements-banner.svg' => $this->createBannerSvg('HALL OF FAME', 'ACHIEVEMENTS, TROPHIES & GLORY', '#0c101b', '#e2e8f0'),
            'page-headers/learning-banner.svg' => $this->createBannerSvg('ROK ACADEMY', 'LEARNING & TACTICAL DEVELOPMENT', '#0b121e', '#cbd5e1'),
            'page-headers/bracket-banner.svg' => $this->createBannerSvg('TOURNAMENT BRACKET HUB', 'BUILD. COMPETE. CONQUER.', '#0a0d15', '#e2e8f0'),
        ];

        // Sample PDF Playbook
        $samplePdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 595 842]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f \n0000000010 00000 n \n0000000053 00000 n \n0000000102 00000 n \ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n182\n%%EOF\n";
        $disk->put('lessons/draft-pick-bible.pdf', $samplePdfContent);

        foreach ($svgDefinitions as $path => $content) {
            $disk->put($path, $content);
        }

        // Also write public fallback images if not exist
        if (!file_exists(public_path('img/dummy-player.svg'))) {
            @mkdir(public_path('img'), 0755, true);
            file_put_contents(public_path('img/dummy-player.svg'), $this->createPlayerSvg('ROK', 'Player', '#d4af37'));
            file_put_contents(public_path('img/dummy-founder.svg'), $this->createAvatarSvg('ROK', 'Leadership', '#d4af37'));
            file_put_contents(public_path('img/dummy-division.svg'), $this->createIconSvg('ROK', '#d4af37'));
            file_put_contents(public_path('img/dummy-achievement.svg'), $this->createTrophySvg('TROPHY', 'ROK Achievement', '#d4af37'));
            file_put_contents(public_path('img/dummy-partner.svg'), $this->createPartnerSvg('PARTNER', '#94a3b8'));
            file_put_contents(public_path('img/dummy-module.svg'), $this->createBannerSvg('MODULE', 'Learning & Dev', '#0f172a', '#38bdf8'));
            file_put_contents(public_path('img/dummy-tournament.svg'), $this->createBannerSvg('TOURNAMENT', 'ROK Bracket', '#0f172a', '#d4af37'));
            file_put_contents(public_path('img/dummy-team-logo.svg'), $this->createTeamBadgeSvg('TEAM', '#94a3b8'));
        }
    }

    protected function createBannerSvg(string $title, string $subtitle, string $bgDark, string $accent): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="1920" height="1080">
  <defs>
    <linearGradient id="bgGrad" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$bgDark}"/>
      <stop offset="50%" stop-color="#121620"/>
      <stop offset="100%" stop-color="#07090d"/>
    </linearGradient>
    <linearGradient id="goldGrad" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0%" stop-color="{$accent}"/>
      <stop offset="100%" stop-color="#ffffff"/>
    </linearGradient>
  </defs>
  <rect width="1920" height="1080" fill="url(#bgGrad)"/>
  <g opacity="0.12" stroke="{$accent}" stroke-width="1.5" fill="none">
    <polygon points="960,100 1080,170 1080,310 960,380 840,310 840,170"/>
    <polygon points="1200,240 1320,310 1320,450 1200,520 1080,450 1080,310"/>
    <polygon points="720,240 840,310 840,450 720,520 600,450 600,310"/>
    <polygon points="960,380 1080,450 1080,590 960,660 840,590 840,450"/>
  </g>
  <circle cx="960" cy="540" r="320" fill="{$accent}" opacity="0.06"/>
  <path d="M 880 440 L 960 380 L 1040 440 L 1010 490 L 960 460 L 910 490 Z" fill="{$accent}" opacity="0.9"/>
  <text x="960" y="580" font-family="'Segoe UI', Roboto, sans-serif" font-size="56" font-weight="900" fill="url(#goldGrad)" text-anchor="middle" letter-spacing="4">{$title}</text>
  <text x="960" y="650" font-family="'Segoe UI', Roboto, sans-serif" font-size="24" font-weight="600" fill="#9ca3af" text-anchor="middle" letter-spacing="6">{$subtitle}</text>
  <rect x="860" y="690" width="200" height="3" fill="{$accent}" rx="1.5"/>
</svg>
SVG;
    }

    protected function createPlayerSvg(string $name, string $role, string $accent): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 800" width="600" height="800">
  <defs>
    <linearGradient id="pBg" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#181c26"/>
      <stop offset="100%" stop-color="#0a0c12"/>
    </linearGradient>
  </defs>
  <rect width="600" height="800" rx="16" fill="url(#pBg)"/>
  <rect x="4" y="4" width="592" height="792" rx="14" fill="none" stroke="{$accent}" stroke-width="2" opacity="0.4"/>
  <polygon points="300,120 420,190 420,330 300,400 180,330 180,190" fill="none" stroke="{$accent}" stroke-width="3" opacity="0.6"/>
  <circle cx="300" cy="240" r="60" fill="{$accent}" opacity="0.2"/>
  <path d="M 270 240 L 300 200 L 330 240 L 315 270 L 300 255 L 285 270 Z" fill="{$accent}"/>
  <text x="300" y="480" font-family="'Segoe UI', Roboto, sans-serif" font-size="40" font-weight="900" fill="#ffffff" text-anchor="middle" letter-spacing="3">{$name}</text>
  <rect x="200" y="520" width="200" height="36" rx="18" fill="{$accent}" opacity="0.2"/>
  <text x="300" y="544" font-family="'Segoe UI', Roboto, sans-serif" font-size="16" font-weight="800" fill="{$accent}" text-anchor="middle" letter-spacing="2">{$role}</text>
  <text x="300" y="700" font-family="'Segoe UI', Roboto, sans-serif" font-size="14" font-weight="700" fill="#475569" text-anchor="middle" letter-spacing="4">RULER OF KINGS ESPORTS</text>
</svg>
SVG;
    }

    protected function createAvatarSvg(string $name, string $title, string $accent): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="500" height="500">
  <rect width="500" height="500" rx="20" fill="#11141c"/>
  <circle cx="250" cy="200" r="90" fill="{$accent}" opacity="0.15"/>
  <circle cx="250" cy="180" r="50" fill="{$accent}"/>
  <path d="M 170 300 C 170 240 330 240 330 300 Z" fill="{$accent}" opacity="0.8"/>
  <text x="250" y="370" font-family="'Segoe UI', Roboto, sans-serif" font-size="24" font-weight="800" fill="#ffffff" text-anchor="middle">{$name}</text>
  <text x="250" y="405" font-family="'Segoe UI', Roboto, sans-serif" font-size="16" font-weight="600" fill="{$accent}" text-anchor="middle">{$title}</text>
</svg>
SVG;
    }

    protected function createTrophySvg(string $badge, string $title, string $accent): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="800" height="600">
  <rect width="800" height="600" rx="16" fill="#0f131a"/>
  <rect x="8" y="8" width="784" height="584" rx="12" fill="none" stroke="{$accent}" stroke-width="2" opacity="0.3"/>
  <polygon points="400,100 470,140 470,220 400,260 330,220 330,140" fill="{$accent}" opacity="0.15"/>
  <path d="M 370 170 L 400 130 L 430 170 L 415 210 L 400 195 L 385 210 Z" fill="{$accent}"/>
  <rect x="320" y="300" width="160" height="32" rx="16" fill="{$accent}"/>
  <text x="400" y="322" font-family="'Segoe UI', Roboto, sans-serif" font-size="16" font-weight="900" fill="#0b0e14" text-anchor="middle" letter-spacing="2">{$badge}</text>
  <text x="400" y="400" font-family="'Segoe UI', Roboto, sans-serif" font-size="28" font-weight="800" fill="#ffffff" text-anchor="middle">{$title}</text>
  <text x="400" y="450" font-family="'Segoe UI', Roboto, sans-serif" font-size="16" font-weight="600" fill="#94a3b8" text-anchor="middle">OFFICIAL COMPETITIVE ACHIEVEMENT CERTIFICATE</text>
</svg>
SVG;
    }

    protected function createPartnerSvg(string $name, string $accent): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 180" width="400" height="180">
  <rect width="400" height="180" rx="12" fill="#141822"/>
  <text x="200" y="105" font-family="'Segoe UI', Roboto, sans-serif" font-size="32" font-weight="900" fill="{$accent}" text-anchor="middle" letter-spacing="4">{$name}</text>
</svg>
SVG;
    }

    protected function createIconSvg(string $text, string $accent): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="200" height="200">
  <polygon points="100,10 180,55 180,145 100,190 20,145 20,55" fill="#141720" stroke="{$accent}" stroke-width="4"/>
  <text x="100" y="115" font-family="'Segoe UI', Roboto, sans-serif" font-size="32" font-weight="900" fill="{$accent}" text-anchor="middle">{$text}</text>
</svg>
SVG;
    }

    protected function createTeamBadgeSvg(string $code, string $accent): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="200" height="200">
  <circle cx="100" cy="100" r="90" fill="#141722" stroke="{$accent}" stroke-width="4"/>
  <text x="100" y="115" font-family="'Segoe UI', Roboto, sans-serif" font-size="36" font-weight="900" fill="{$accent}" text-anchor="middle">{$code}</text>
</svg>
SVG;
    }

    protected function createDiagramSvg(string $title): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450">
  <rect width="800" height="450" fill="#0d1117"/>
  <circle cx="400" cy="225" r="140" fill="none" stroke="#38bdf8" stroke-width="2" stroke-dasharray="8 8"/>
  <line x1="100" y1="100" x2="700" y2="350" stroke="#f59e0b" stroke-width="3"/>
  <text x="400" y="50" font-family="'Segoe UI', Roboto, sans-serif" font-size="20" font-weight="800" fill="#ffffff" text-anchor="middle">{$title}</text>
  <rect x="300" y="200" width="200" height="50" rx="8" fill="#1e293b"/>
  <text x="400" y="232" font-family="'Segoe UI', Roboto, sans-serif" font-size="16" font-weight="700" fill="#38bdf8" text-anchor="middle">MID RIVER ZONE</text>
</svg>
SVG;
    }

    protected function createScreenshotSvg(string $label): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="800" height="600">
  <rect width="800" height="600" fill="#111827"/>
  <rect x="20" y="20" width="760" height="560" rx="8" fill="#1f2937" stroke="#374151" stroke-width="2"/>
  <text x="400" y="280" font-family="'Segoe UI', Roboto, sans-serif" font-size="24" font-weight="800" fill="#60a5fa" text-anchor="middle">VERIFIED SCREENSHOT</text>
  <text x="400" y="330" font-family="'Segoe UI', Roboto, sans-serif" font-size="18" font-weight="600" fill="#9ca3af" text-anchor="middle">{$label}</text>
</svg>
SVG;
    }
}
