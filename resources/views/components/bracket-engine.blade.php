@php
    $activeSlug = $tournamentSlug ?? ($activeTournament->slug ?? 'rok-invitational-season-1');
    $initialState = $initialBracketState ?? ($activeTournament->bracket_state ?? null);
    $isReadOnly = $readOnly ?? false;
    $hasLogoAccess = $unlockLogos ?? (Auth::check() && Auth::user()->hasBracketAccess());
    $shareUrl = route('bracket.live', ['slug' => $activeSlug]);
@endphp

<div class="rok-bracket-workspace" id="rokBracketWorkspace" data-slug="{{ $activeSlug }}" data-readonly="{{ $isReadOnly ? '1' : '0' }}" data-logo-access="{{ $hasLogoAccess ? '1' : '0' }}">

    {{-- Top Live Sync & Shareable Link Bar --}}
    <div class="bracket-realtime-bar">
        <div class="bracket-realtime-bar__status">
            <span class="realtime-pulse-dot" id="realtimePulseDot"></span>
            <div>
                <strong class="realtime-status-title" id="realtimeStatusText">
                    {{ $isReadOnly ? 'LIVE REAL-TIME VIEWER MODE (AUTO-SYNC)' : 'REAL-TIME BRACKET ENGINE AKTIF' }}
                </strong>
                <span class="realtime-status-sub" id="realtimeLastSync">
                    Setiap perubahan skor, tim, &amp; poin langsung tersinkronisasi secara real-time ke seluruh penonton.
                </span>
            </div>
        </div>

        <div class="bracket-realtime-bar__actions">
            <div class="share-url-box">
                <span class="share-url-label">&#x1F517; Link Bagikan Live Bracket:</span>
                <input type="text" id="shareBracketUrlInput" class="share-url-input" value="{{ $shareUrl }}" readonly onclick="this.select()">
            </div>
            <button type="button" class="btn-rok btn-rok--gold btn-rok--sm" id="copyShareLinkBtn">
                &#x1F4CB; Salin Link Live
            </button>
            <a href="https://wa.me/?text={{ urlencode('Pantau Live Bracket Turnamen secara Real-Time di ROK Web: ' . $shareUrl) }}" target="_blank" rel="noopener noreferrer" class="btn-rok btn-rok--outline btn-rok--sm" id="waShareLinkBtn">
                &#x1F4AC; Share WA
            </a>
            @if (!$isReadOnly)
                <a href="{{ $shareUrl }}" target="_blank" class="btn-rok btn-rok--outline btn-rok--sm" title="Buka tampilan penonton di tab baru">
                    &#x1F441; Buka Live View &nearr;
                </a>
            @endif
        </div>
    </div>

    <div class="generator-card">
        @if (!$isReadOnly)
            <div class="generator-inputs">
                <div class="form-row-3">
                    <div class="form-field">
                        <label for="tourneyName" class="form-label">Nama Turnamen</label>
                        <input
                            type="text"
                            id="tourneyName"
                            class="form-input"
                            value="{{ $initialState['title'] ?? ($activeTournament->name ?? 'ROK Community Cup 2026') }}"
                            placeholder="Nama Turnamen"
                        >
                    </div>

                    <div class="form-field">
                        <label for="tourneyFormat" class="form-label">Format Pertandingan</label>
                        <select id="tourneyFormat" class="form-select">
                            <option value="single">Single Elimination</option>
                            <option value="double">Double Elimination</option>
                            <option value="league">League System</option>
                            <option value="swiss">Swiss Stage (Format M7 MLBB)</option>
                        </select>
                    </div>

                    <div class="form-field" id="teamCountField">
                        <label for="teamCountInput" class="form-label" id="teamCountLabel">Jumlah Tim Peserta</label>

                        {{-- Number input for arbitrary team counts (Single Elimination, League, Swiss) --}}
                        <div id="teamCountInputWrapper">
                            <input
                                type="number"
                                id="teamCountInput"
                                class="form-input"
                                min="3"
                                max="64"
                                value="{{ $initialState['teamCount'] ?? 8 }}"
                                placeholder="Contoh: 5, 7, 8, 12, 16"
                            >
                            <span class="form-hint" id="teamCountHint" style="font-size: 11px; color: var(--rok-muted-light); margin-top: 4px; display: block;">
                                Bebas masukkan jumlah tim (3–64). Tim dengan seed tertinggi otomatis lolos jika bukan pangkat 2.
                            </span>
                        </div>

                        {{-- Select for Double Elimination (4 or 6 teams) --}}
                        <select id="teamCountSelect" class="form-select" style="display: none;">
                            <option value="4">4 Tim</option>
                            <option value="6" selected>6 Tim (2 Seed Teratas Mendapat Bye)</option>
                        </select>

                        <p class="bracket-format-warning" id="doubleEliminationWarning" role="alert" aria-live="polite" hidden>
                            Double Elimination mengikuti format resmi Mobile Legends di ROK Web dan dibatasi 4 atau 6 tim.
                        </p>
                    </div>
                </div>

                {{-- Dynamic Teams Input Container (Team Name + WhatsApp + Team Logo) --}}
                <div class="team-inputs-container">
                    <div class="team-inputs-header">
                        <div>
                            <h4 class="inputs-subtitle" style="margin-bottom: 2px;">Daftar Tim, Seeding, Kontak WhatsApp &amp; Logo Tim:</h4>
                            <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0;">
                                Klik logo pada bagan pertandingan untuk melihat <strong>Poin Pertandingan</strong> dan tombol <strong>Chat Lawan Tanding via WhatsApp</strong>.
                            </p>
                        </div>
                        @if ($hasLogoAccess)
                            <span class="membership-unlocked-pill">
                                &#x1F451; Fitur Logo Tim &amp; Kontak WA Terbuka ({{ strtoupper(Auth::user()?->membershipTierSlug() ?? 'PRO') }})
                            </span>
                        @else
                            <a href="{{ route('membership.payment', ['plan' => 'challenger']) }}" class="membership-locked-pill">
                                &#x1F512; Aktifkan ROK Challenger / Immortal untuk Kustomisasi Logo Tim &rarr;
                            </a>
                        @endif
                    </div>

                    <div class="team-inputs-grid" id="teamInputsGrid">
                        {{-- Populated by JavaScript --}}
                    </div>
                </div>

                <div class="generator-actions" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                    <div style="font-size: 12px; color: var(--rok-muted-light);">
                        &#x1F4A1; <strong>Tips Pro:</strong> Ubah nama tim, nomor WhatsApp, logo, atau skor pertandingan — semua perubahan langsung tersimpan &amp; tampil real-time di link share!
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-rok btn-rok--outline" id="resetScoresBtn">
                            &#x21BA; Reset Skor
                        </button>
                        <button type="button" class="btn-rok btn-rok--gold btn-rok--lg" id="generateBracketBtn">
                            Generate &amp; Simpan Live Bracket &rarr;
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- Hidden inputs for Read-Only Viewer Mode so JS engine works identically --}}
            <input type="hidden" id="tourneyName" value="{{ $initialState['title'] ?? ($activeTournament->name ?? 'ROK Community Cup 2026') }}">
            <input type="hidden" id="tourneyFormat" value="{{ $initialState['format'] ?? 'single' }}">
            <input type="hidden" id="teamCountInput" value="{{ $initialState['teamCount'] ?? 8 }}">
            <input type="hidden" id="teamCountSelect" value="{{ $initialState['teamCount'] ?? 6 }}">
            <div id="teamCountInputWrapper" style="display:none;"></div>
            <div id="teamCountHint" style="display:none;"></div>
            <div id="doubleEliminationWarning" hidden></div>
            <div id="teamInputsGrid" style="display:none;"></div>
        @endif

        {{-- Output Visualizer --}}
        <div class="generated-bracket-output" id="bracketOutputWrapper">
            <div class="output-banner">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <span class="live-indicator-badge">&#x1F534; LIVE BRACKET</span>
                    <h3 id="outputTourneyTitle" style="margin: 0;">{{ $initialState['title'] ?? 'ROK Community Cup 2026' }}</h3>
                </div>
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <span class="bracket-hint-ClickLogo">&#x1F446; Klik Logo Tim di bagan untuk Poin &amp; Chat WhatsApp Lawan</span>
                    <span class="badge-mini" id="outputFormatBadge">Single Elimination</span>
                </div>
            </div>

            <div class="generated-tree-deck" id="generatedTreeDeck">
                {{-- Rendered Bracket Tree + Directional SVG Arrows --}}
            </div>
        </div>
    </div>
</div>

{{-- Interactive Team Info, Match Points & Direct WhatsApp Modal --}}
<div class="rok-team-modal-backdrop" id="rokTeamModal" hidden aria-modal="true" role="dialog">
    <div class="rok-team-modal">
        <button type="button" class="rok-team-modal__close" id="closeTeamModalBtn" aria-label="Tutup">&times;</button>

        <div class="rok-team-modal__header">
            <div class="rok-team-modal__logo-wrap">
                <img src="/storage/tournaments/team-rok.svg" alt="Team Logo" id="modalTeamLogo" class="rok-team-modal__logo">
                <span class="rok-team-modal__seed" id="modalTeamSeed">#1</span>
            </div>
            <div>
                <span class="rok-team-modal__badge" id="modalTeamStatusBadge">PESERTA AKTIF</span>
                <h3 class="rok-team-modal__name" id="modalTeamName">ROK Prime</h3>
                <p class="rok-team-modal__tourney" id="modalTeamTourney">ROK Community Cup 2026</p>
            </div>
        </div>

        <div class="rok-team-modal__stats-grid">
            <div class="rok-team-stat-box rok-team-stat-box--highlight">
                <span class="rok-team-stat-box__label">TOTAL POIN PERTANDINGAN</span>
                <strong class="rok-team-stat-box__value" id="modalTeamPoints">0 Pts</strong>
                <small class="rok-team-stat-box__sub">3 Poin/Menang + Skor Game</small>
            </div>
            <div class="rok-team-stat-box">
                <span class="rok-team-stat-box__label">REKOR PERTANDINGAN</span>
                <strong class="rok-team-stat-box__value" id="modalTeamRecord">0W - 0L</strong>
                <small class="rok-team-stat-box__sub" id="modalTeamPlayed">0 Match Dimainkan</small>
            </div>
            <div class="rok-team-stat-box">
                <span class="rok-team-stat-box__label">SKOR GAME (+/-)</span>
                <strong class="rok-team-stat-box__value" id="modalTeamGameScore">0 - 0 (0)</strong>
                <small class="rok-team-stat-box__sub">Akumulasi Game Menang</small>
            </div>
        </div>

        <div class="rok-team-modal__wa-card">
            <div class="rok-team-modal__wa-info">
                <span class="rok-team-modal__wa-label">&#x1F4F1; KONTAK RESMI KAPTEN / MANAJER TIM (WHATSAPP)</span>
                <strong class="rok-team-modal__wa-number" id="modalTeamWaNumber">+62 812-3456-7890</strong>
                <p class="rok-team-modal__wa-desc">
                    Hubungi tim ini secara langsung melalui WhatsApp untuk koordinasi lobby custom, jadwal tanding (janjian scrim/match), atau verifikasi hasil pertandingan.
                </p>
            </div>
            <a href="#" target="_blank" rel="noopener noreferrer" class="rok-wa-direct-btn" id="modalTeamWaBtn">
                <span class="rok-wa-direct-btn__icon">&#x1F4AC;</span>
                <span>Chat Tim via WhatsApp Sekarang &rarr;</span>
            </a>
        </div>
    </div>
</div>

{{-- Team Logo Selector & Custom Upload Modal (For Challenger / Immortal Members) --}}
<div class="rok-team-modal-backdrop" id="rokLogoPickerModal" hidden aria-modal="true" role="dialog">
    <div class="rok-team-modal" style="max-width: 520px;">
        <button type="button" class="rok-team-modal__close" id="closeLogoPickerBtn" aria-label="Tutup">&times;</button>
        <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 4px;" id="logoPickerTitle">Pilih / Unggah Logo Tim</h3>
        <p style="font-size: 13px; color: var(--rok-muted-light); margin-bottom: 16px;">
            Fitur eksklusif Membership ROK Challenger &amp; Immortal. Pilih emblem esports resmi atau unggah file logo tim Anda sendiri.
        </p>

        <div style="margin-bottom: 18px;">
            <label class="form-label" style="display: block; margin-bottom: 8px; font-size: 12px;">Unggah File Logo Tim Kustom (PNG, JPG, SVG, WebP):</label>
            <input type="file" id="customLogoFileInput" class="form-input" accept="image/png,image/jpeg,image/webp,image/svg+xml" style="padding: 8px;">
        </div>

        <div>
            <span class="form-label" style="display: block; margin-bottom: 8px; font-size: 12px;">Atau Pilih Emblem Esports Resmi ROK:</span>
            <div class="preset-logos-grid" id="presetLogosGrid">
                {{-- Populated by JS --}}
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const INITIAL_SERVER_STATE = @json($initialState);
    const ACTIVE_SLUG = @json($activeSlug);
    const SYNC_API_URL = @json(route('api.bracket.sync', ['slug' => $activeSlug]));
    const STATE_API_URL = @json(route('api.bracket.state', ['slug' => $activeSlug]));
    const IS_READ_ONLY = @json($isReadOnly);
    const HAS_LOGO_ACCESS = @json($hasLogoAccess);
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const defaultTeamNames = [
        'ROK Prime', 'Aether Esports', 'Valkyrie Horizon', 'Shadow Legion',
        'Apex Titans', 'Celestial Wolves', 'Kitsune Vanguard', 'Solar Eclipse',
        'Nova Sovereign', 'Phantom Syndicate', 'Crimson Vortex', 'Obsidian Crown',
        'Thunder Monarch', 'Iron Valkyrie', 'Astral Knights', 'Zenith Dragons',
        'Stormbreakers', 'Silver Fang', 'Vanguard Zero', 'Omega Protocol',
        'Royal Knights', 'Cyber Samurai', 'Blaze Phoenix', 'Frost Giants',
        'Dark Matter', 'Golden Griffin', 'Emerald Viper', 'Titan Slayers',
        'Shadow Hunters', 'Lunar Eclipse', 'Solar Flare', 'Starlight Rebels'
    ];

    const presetLogos = [
        '/storage/tournaments/team-rok.svg',
        '/storage/tournaments/team-aether.svg',
        '/storage/tournaments/team-valkyrie.svg',
        '/storage/tournaments/team-shadow.svg',
        '/storage/tournaments/team-apex.svg',
        '/storage/tournaments/team-wolves.svg',
        '/storage/tournaments/team-kitsune.svg',
        '/storage/tournaments/team-eclipse.svg'
    ];

    const formatNames = {
        single: 'Single Elimination',
        double: 'Double Elimination (MPL)',
        league: 'League System',
        swiss: 'Swiss Stage (M7 Format)'
    };

    let currentTournament = null;
    let savedScoresMap = {};
    let lastKnownUpdatedAt = INITIAL_SERVER_STATE?.updatedAt || null;
    let isApplyingRemoteUpdate = false;
    let syncDebounceTimer = null;
    let activeLogoPickSeed = null;

    // Cross-tab instant broadcast channel
    const broadcastChannel = ('BroadcastChannel' in window) ? new BroadcastChannel('rok_bracket_' + ACTIVE_SLUG) : null;

    function normalizeWaNumber(raw) {
        let digits = String(raw || '').replace(/[^0-9]/g, '');
        if (!digits) return '6281234567890';
        if (digits.startsWith('0')) {
            digits = '62' + digits.slice(1);
        } else if (digits.startsWith('8')) {
            digits = '62' + digits;
        }
        return digits;
    }

    function formatWaDisplay(raw) {
        const digits = normalizeWaNumber(raw);
        if (digits.length >= 10) {
            return '+' + digits.slice(0, 2) + ' ' + digits.slice(2, 5) + '-' + digits.slice(5, 9) + '-' + digits.slice(9);
        }
        return '+' + digits;
    }

    function getSelectedFormat() {
        return document.getElementById('tourneyFormat')?.value || 'single';
    }

    function getTeamCount() {
        const format = getSelectedFormat();
        if (format === 'double') {
            const val = Number.parseInt(document.getElementById('teamCountSelect')?.value, 10);
            return [4, 6].includes(val) ? val : 6;
        }
        if (format === 'swiss') {
            return 16;
        }
        const raw = Number.parseInt(document.getElementById('teamCountInput')?.value, 10);
        if (Number.isNaN(raw) || raw < 3) return 3;
        if (raw > 64) return 64;
        return raw;
    }

    function syncTeamCountOptions() {
        if (IS_READ_ONLY) return;
        const format = getSelectedFormat();
        const inputWrapper = document.getElementById('teamCountInputWrapper');
        const inputEl = document.getElementById('teamCountInput');
        const hintEl = document.getElementById('teamCountHint');
        const selectEl = document.getElementById('teamCountSelect');
        const warning = document.getElementById('doubleEliminationWarning');

        if (!inputWrapper || !selectEl) return;

        if (format === 'double') {
            inputWrapper.style.display = 'none';
            selectEl.style.display = 'block';
            if (!['4', '6'].includes(selectEl.value)) {
                selectEl.value = '6';
            }
            if (warning) warning.hidden = false;
        } else if (format === 'swiss') {
            inputWrapper.style.display = 'block';
            selectEl.style.display = 'none';
            inputEl.value = '16';
            inputEl.readOnly = true;
            if (hintEl) hintEl.textContent = 'Swiss Stage (Format M7 MLBB) menggunakan standar 16 tim (3 Menang = Lolos, 3 Kalah = Gugur).';
            if (warning) warning.hidden = true;
        } else {
            inputWrapper.style.display = 'block';
            selectEl.style.display = 'none';
            inputEl.readOnly = false;
            if (Number.parseInt(inputEl.value, 10) < 3) inputEl.value = '8';
            if (hintEl) {
                hintEl.textContent = format === 'single'
                    ? 'Bebas masukkan jumlah tim (3–64). Jika bukan kelipatan pangkat 2, tim dengan seed teratas otomatis mendapat hak lolos babak awal.'
                    : 'Masukkan jumlah tim peserta liga (3–32 tim).';
            }
            if (warning) warning.hidden = true;
        }
    }

    function collectCurrentTeamInputs() {
        const map = new Map();
        if (currentTournament && Array.isArray(currentTournament.teams)) {
            currentTournament.teams.forEach(t => {
                map.set(t.seed, {
                    name: t.name,
                    whatsapp: t.whatsapp,
                    logo: t.logo
                });
            });
        }
        document.querySelectorAll('.team-entry-card').forEach(card => {
            const seed = Number.parseInt(card.dataset.seed, 10);
            const nameInput = card.querySelector('.team-seed-input');
            const waInput = card.querySelector('.team-wa-input');
            const logoImg = card.querySelector('.team-entry-logo');
            if (seed) {
                map.set(seed, {
                    name: nameInput?.value?.trim() || map.get(seed)?.name || `Tim ${seed}`,
                    whatsapp: waInput?.value?.trim() || map.get(seed)?.whatsapp || `628123456789${seed % 10}`,
                    logo: logoImg?.getAttribute('src') || map.get(seed)?.logo || presetLogos[(seed - 1) % presetLogos.length]
                });
            }
        });
        return map;
    }

    function renderTeamInputs(existingTeams = null) {
        const grid = document.getElementById('teamInputsGrid');
        if (!grid) return;

        const count = getTeamCount();
        const currentMap = collectCurrentTeamInputs();

        if (Array.isArray(existingTeams)) {
            existingTeams.forEach((t, idx) => {
                const seed = t.seed || (idx + 1);
                currentMap.set(seed, {
                    name: t.name || defaultTeamNames[idx] || `Tim ${seed}`,
                    whatsapp: t.whatsapp || `628123456789${idx % 10}`,
                    logo: t.logo || presetLogos[idx % presetLogos.length]
                });
            });
        }

        grid.innerHTML = '';

        for (let i = 0; i < count; i++) {
            const seed = i + 1;
            const saved = currentMap.get(seed);
            const teamName = saved?.name || defaultTeamNames[i] || `Tim ${seed}`;
            const teamWa = saved?.whatsapp || `628123456789${i % 10}`;
            const teamLogo = saved?.logo || presetLogos[i % presetLogos.length];

            const card = document.createElement('div');
            card.className = 'team-entry-card';
            card.dataset.seed = String(seed);

            // Seed + Logo trigger
            const topRow = document.createElement('div');
            topRow.className = 'team-entry-card__top';

            const logoBtn = document.createElement('button');
            logoBtn.type = 'button';
            logoBtn.className = 'team-entry-logo-btn';
            logoBtn.title = HAS_LOGO_ACCESS ? 'Klik untuk mengganti Logo Tim' : 'Fitur kustomisasi logo untuk member Challenger/Immortal';
            logoBtn.innerHTML = `
                <img src="${teamLogo}" alt="Logo" class="team-entry-logo" onerror="this.src='/img/dummy-division.svg'">
                <span class="team-entry-logo-edit">${HAS_LOGO_ACCESS ? '&#x270E;' : '&#x1F512;'}</span>
            `;
            logoBtn.addEventListener('click', () => {
                if (!HAS_LOGO_ACCESS) {
                    openTeamModalBySeed(seed);
                    return;
                }
                openLogoPicker(seed);
            });

            const seedBadge = document.createElement('span');
            seedBadge.className = 'seed-number';
            seedBadge.textContent = `#${seed}`;

            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.className = 'form-input team-seed-input';
            nameInput.dataset.seed = String(seed);
            nameInput.value = teamName;
            nameInput.placeholder = `Nama Tim ${seed}`;
            nameInput.addEventListener('input', () => {
                updateTeamDataInPlace(seed);
            });

            topRow.append(seedBadge, logoBtn, nameInput);

            // WhatsApp row
            const waRow = document.createElement('div');
            waRow.className = 'team-entry-card__wa';

            const waIcon = document.createElement('span');
            waIcon.className = 'team-entry-wa-icon';
            waIcon.innerHTML = '&#x1F4F1; WA:';

            const waInput = document.createElement('input');
            waInput.type = 'text';
            waInput.className = 'form-input team-wa-input';
            waInput.dataset.seed = String(seed);
            waInput.value = teamWa;
            waInput.placeholder = '62812xxxxxxx';
            waInput.addEventListener('input', () => {
                updateTeamDataInPlace(seed);
            });

            const waQuickLink = document.createElement('a');
            waQuickLink.href = `https://wa.me/${normalizeWaNumber(teamWa)}`;
            waQuickLink.target = '_blank';
            waQuickLink.rel = 'noopener noreferrer';
            waQuickLink.className = 'team-entry-wa-chat';
            waQuickLink.title = 'Tes Hubungi WhatsApp Tim ini';
            waQuickLink.innerHTML = '&#x1F4AC; Chat';

            waInput.addEventListener('input', () => {
                waQuickLink.href = `https://wa.me/${normalizeWaNumber(waInput.value)}`;
            });

            waRow.append(waIcon, waInput, waQuickLink);
            card.append(topRow, waRow);
            grid.appendChild(card);
        }
    }

    function updateTeamDataInPlace(seed) {
        if (!currentTournament) return;
        const card = document.querySelector(`.team-entry-card[data-seed="${seed}"]`);
        if (!card) return;
        const team = currentTournament.teams.find(t => t.seed === seed);
        if (team) {
            team.name = card.querySelector('.team-seed-input')?.value?.trim() || `Tim ${seed}`;
            team.whatsapp = card.querySelector('.team-wa-input')?.value?.trim() || '';
            team.logo = card.querySelector('.team-entry-logo')?.getAttribute('src') || presetLogos[(seed - 1) % presetLogos.length];
            renderTournament(false);
            scheduleRealtimeSync();
        }
    }

    function getTeamsFromInputs() {
        const count = getTeamCount();
        const cards = document.querySelectorAll('.team-entry-card');
        if (cards.length > 0) {
            return Array.from(cards).slice(0, count).map((card, index) => {
                const seed = index + 1;
                const name = card.querySelector('.team-seed-input')?.value?.trim() || defaultTeamNames[index] || `Tim ${seed}`;
                const whatsapp = card.querySelector('.team-wa-input')?.value?.trim() || `628123456789${index % 10}`;
                const logo = card.querySelector('.team-entry-logo')?.getAttribute('src') || presetLogos[index % presetLogos.length];
                return { id: seed, seed, name, whatsapp, logo };
            });
        }

        if (INITIAL_SERVER_STATE && Array.isArray(INITIAL_SERVER_STATE.teams)) {
            return INITIAL_SERVER_STATE.teams.slice(0, count).map((t, index) => ({
                id: index + 1,
                seed: index + 1,
                name: t.name || defaultTeamNames[index] || `Tim ${index + 1}`,
                whatsapp: t.whatsapp || `628123456789${index % 10}`,
                logo: t.logo || presetLogos[index % presetLogos.length]
            }));
        }

        return Array.from({ length: count }, (_, index) => ({
            id: index + 1,
            seed: index + 1,
            name: defaultTeamNames[index] || `Tim ${index + 1}`,
            whatsapp: `628123456789${index % 10}`,
            logo: presetLogos[index % presetLogos.length]
        }));
    }

    function teamSource(team) {
        return team ? { type: 'team', team } : null;
    }

    function winnerSource(matchId, label = `Pemenang ${matchId}`) {
        return { type: 'winner', matchId, label };
    }

    function loserSource(matchId, label = `Kalah ${matchId}`) {
        return { type: 'loser', matchId, label };
    }

    function createMatch(config) {
        const saved = savedScoresMap[config.id];
        return {
            id: config.id,
            round: config.round,
            section: config.section || 'Main',
            title: config.title,
            source1: config.source1 || null,
            source2: config.source2 || null,
            team1: null,
            team2: null,
            score1: saved && saved.score1 !== undefined ? saved.score1 : null,
            score2: saved && saved.score2 !== undefined ? saved.score2 : null,
            winner: null,
            loser: null,
            eliminateLoser: Boolean(config.eliminateLoser),
            note: config.note || ''
        };
    }

    function resolveSource(source, matchMap) {
        if (!source) return null;
        if (source.type === 'team') return source.team;
        const ref = matchMap.get(source.matchId);
        if (!ref) return null;
        return source.type === 'loser' ? ref.loser : ref.winner;
    }

    function sourcePlaceholder(source) {
        return source?.label || 'Menunggu Pemenang';
    }

    function calculateMatch(match, matches) {
        const matchMap = new Map(matches.map(item => [item.id, item]));
        match.team1 = resolveSource(match.source1, matchMap);
        match.team2 = resolveSource(match.source2, matchMap);

        if (!match.team1 || !match.team2) {
            match.winner = null;
            match.loser = null;
            return;
        }

        if (!Number.isInteger(match.score1) || !Number.isInteger(match.score2) || match.score1 < 0 || match.score2 < 0 || match.score1 === match.score2) {
            match.winner = null;
            match.loser = null;
            return;
        }

        if (match.score1 > match.score2) {
            match.winner = match.team1;
            match.loser = match.team2;
        } else {
            match.winner = match.team2;
            match.loser = match.team1;
        }
    }

    // Compute live points and match stats for each team across all played matches
    function computeAllTeamStats(state) {
        const statsMap = new Map();
        if (!state || !Array.isArray(state.teams)) return statsMap;

        state.teams.forEach(t => {
            statsMap.set(t.id, {
                team: t,
                played: 0,
                wins: 0,
                losses: 0,
                scored: 0,
                conceded: 0,
                points: 0,
                isEliminated: false,
                isChampion: false
            });
        });

        state.matches.forEach(m => {
            const a = m.team1 ? statsMap.get(m.team1.id) : null;
            const b = m.team2 ? statsMap.get(m.team2.id) : null;
            if (a && Number.isInteger(m.score1)) a.scored += m.score1;
            if (a && Number.isInteger(m.score2)) a.conceded += m.score2;
            if (b && Number.isInteger(m.score2)) b.scored += m.score2;
            if (b && Number.isInteger(m.score1)) b.conceded += m.score1;

            if (m.winner && m.loser) {
                const winStat = statsMap.get(m.winner.id);
                const loseStat = statsMap.get(m.loser.id);
                if (winStat) {
                    winStat.played++;
                    winStat.wins++;
                }
                if (loseStat) {
                    loseStat.played++;
                    loseStat.losses++;
                    if (state.format === 'single' || m.eliminateLoser) {
                        loseStat.isEliminated = true;
                    }
                }
            }
        });

        statsMap.forEach(stat => {
            stat.points = (stat.wins * 3) + stat.scored;
        });

        return statsMap;
    }

    // ====================================================
    // 1. SINGLE ELIMINATION (Arbitrary N Teams, Byes)
    // ====================================================
    function getSeedOrder(bracketSize) {
        let rounds = Math.log2(bracketSize);
        let pls = [1, 2];
        for (let i = 0; i < rounds - 1; i++) {
            let out = [];
            let length = pls.length * 2 + 1;
            pls.forEach(d => {
                out.push(d);
                out.push(length - d);
            });
            pls = out;
        }
        return pls;
    }

    function singleRoundName(roundIndex, totalRounds) {
        const remaining = totalRounds - roundIndex;
        if (remaining === 0) return 'Grand Final';
        if (remaining === 1) return 'Semi Finals';
        if (remaining === 2) return 'Quarter Finals';
        if (remaining === 3) return 'Babak 16 Besar';
        return `Babak ${roundIndex}`;
    }

    function buildSingleElimination(teams) {
        const N = teams.length;
        const M = Math.pow(2, Math.ceil(Math.log2(Math.max(2, N))));
        const totalRounds = Math.log2(M);
        const seedOrder = getSeedOrder(M);

        const rounds = [];
        const matches = [];
        const autoAdvancedTeams = [];

        const r1Name = singleRoundName(1, totalRounds);
        const r1Slots = [];
        let r1MatchCounter = 1;

        for (let i = 0; i < M; i += 2) {
            const s1 = seedOrder[i];
            const s2 = seedOrder[i + 1];
            const t1 = s1 <= N ? teams[s1 - 1] : null;
            const t2 = s2 <= N ? teams[s2 - 1] : null;

            if (t1 && t2) {
                const matchId = `R1-M${r1MatchCounter++}`;
                const match = createMatch({
                    id: matchId,
                    round: 1,
                    section: r1Name,
                    title: `${r1Name} #${r1MatchCounter - 1}`,
                    source1: teamSource(t1),
                    source2: teamSource(t2)
                });
                matches.push(match);
                r1Slots.push({ type: 'match', match });
            } else if (t1 && !t2) {
                autoAdvancedTeams.push(t1);
                r1Slots.push({ type: 'advance', team: t1 });
            } else if (!t1 && t2) {
                autoAdvancedTeams.push(t2);
                r1Slots.push({ type: 'advance', team: t2 });
            }
        }

        const r1RealMatches = r1Slots.filter(s => s.type === 'match').map(s => s.match);
        if (r1RealMatches.length > 0) {
            rounds.push({
                number: 1,
                title: r1Name,
                matches: r1RealMatches,
                autoAdvanced: autoAdvancedTeams
            });
        }

        let prevSlots = r1Slots;

        for (let r = 2; r <= totalRounds; r++) {
            const rName = singleRoundName(r, totalRounds);
            const currentSlots = [];
            const rMatches = [];
            let mCounter = 1;

            for (let i = 0; i < prevSlots.length; i += 2) {
                const left = prevSlots[i];
                const right = prevSlots[i + 1];

                const src1 = left.type === 'advance'
                    ? teamSource(left.team)
                    : winnerSource(left.match.id, `Pemenang ${left.match.id}`);

                const src2 = right.type === 'advance'
                    ? teamSource(right.team)
                    : winnerSource(right.match.id, `Pemenang ${right.match.id}`);

                const matchId = `R${r}-M${mCounter}`;
                const match = createMatch({
                    id: matchId,
                    round: r,
                    section: rName,
                    title: r === totalRounds ? 'Grand Final' : `${rName} #${mCounter}`,
                    source1: src1,
                    source2: src2
                });

                matches.push(match);
                rMatches.push(match);
                currentSlots.push({ type: 'match', match });
                mCounter++;
            }

            rounds.push({
                number: r,
                title: rName,
                matches: rMatches
            });

            prevSlots = currentSlots;
        }

        return { format: 'single', teams, rounds, matches, autoAdvancedTeams };
    }

    // ====================================================
    // 2. DOUBLE ELIMINATION (MPL Format - UNTOUCHED LOGIC)
    // ====================================================
    function buildDoubleElimination(teams) {
        if (teams.length === 4) {
            const ub1 = createMatch({ id: 'UB-M1', round: 1, section: 'Upper Bracket', title: 'Upper Semifinal 1', source1: teamSource(teams[0]), source2: teamSource(teams[3]) });
            const ub2 = createMatch({ id: 'UB-M2', round: 1, section: 'Upper Bracket', title: 'Upper Semifinal 2', source1: teamSource(teams[1]), source2: teamSource(teams[2]) });
            const ubFinal = createMatch({ id: 'UB-F', round: 2, section: 'Upper Bracket', title: 'Upper Final', source1: winnerSource('UB-M1'), source2: winnerSource('UB-M2') });
            const lbSemi = createMatch({ id: 'LB-S1', round: 2, section: 'Lower Bracket', title: 'Lower Semifinal', source1: loserSource('UB-M1'), source2: loserSource('UB-M2'), eliminateLoser: true });
            const lbFinal = createMatch({ id: 'LB-F', round: 3, section: 'Lower Bracket', title: 'Lower Final', source1: loserSource('UB-F'), source2: winnerSource('LB-S1'), eliminateLoser: true });
            const grandFinal = createMatch({ id: 'GF', round: 4, section: 'Grand Final', title: 'Grand Final', source1: winnerSource('UB-F'), source2: winnerSource('LB-F'), eliminateLoser: true });

            const matches = [ub1, ub2, ubFinal, lbSemi, lbFinal, grandFinal];
            return {
                format: 'double',
                teams,
                matches,
                rounds: [
                    { number: 1, title: 'Ronde 1 · Upper Semifinal', matches: [ub1, ub2] },
                    { number: 2, title: 'Ronde 2 · Upper Final & Lower Semifinal', matches: [ubFinal, lbSemi] },
                    { number: 3, title: 'Ronde 3 · Lower Final', matches: [lbFinal] },
                    { number: 4, title: 'Ronde 4 · Grand Final', matches: [grandFinal] }
                ]
            };
        }

        const qf1 = createMatch({
            id: 'UB-R1-M1',
            round: 1,
            section: 'Upper Bracket',
            title: 'Play-In / Upper R1 #1',
            source1: teamSource(teams[2]),
            source2: teamSource(teams[5]),
            eliminateLoser: true,
            note: 'Kalah langsung gugur (Format MPL 6 Tim)'
        });
        const qf2 = createMatch({
            id: 'UB-R1-M2',
            round: 1,
            section: 'Upper Bracket',
            title: 'Play-In / Upper R1 #2',
            source1: teamSource(teams[3]),
            source2: teamSource(teams[4]),
            eliminateLoser: true,
            note: 'Kalah langsung gugur (Format MPL 6 Tim)'
        });
        const ubSemi1 = createMatch({
            id: 'UB-S1',
            round: 2,
            section: 'Upper Bracket',
            title: 'Upper Semifinal #1',
            source1: teamSource(teams[0]),
            source2: winnerSource('UB-R1-M2'),
            note: 'Kalah turun ke Lower Semifinal'
        });
        const ubSemi2 = createMatch({
            id: 'UB-S2',
            round: 2,
            section: 'Upper Bracket',
            title: 'Upper Semifinal #2',
            source1: teamSource(teams[1]),
            source2: winnerSource('UB-R1-M1'),
            note: 'Kalah turun ke Lower Semifinal'
        });
        const ubFinal = createMatch({
            id: 'UB-F',
            round: 3,
            section: 'Upper Bracket',
            title: 'Upper Final',
            source1: winnerSource('UB-S1'),
            source2: winnerSource('UB-S2'),
            note: 'Pemenang ke Grand Final, kalah ke Lower Final'
        });
        const lbSemi = createMatch({
            id: 'LB-S1',
            round: 3,
            section: 'Lower Bracket',
            title: 'Lower Semifinal',
            source1: loserSource('UB-S1'),
            source2: loserSource('UB-S2'),
            eliminateLoser: true
        });
        const lbFinal = createMatch({
            id: 'LB-F',
            round: 4,
            section: 'Lower Bracket',
            title: 'Lower Final',
            source1: loserSource('UB-F'),
            source2: winnerSource('LB-S1'),
            eliminateLoser: true
        });
        const grandFinal = createMatch({
            id: 'GF',
            round: 5,
            section: 'Grand Final',
            title: 'Grand Final',
            source1: winnerSource('UB-F'),
            source2: winnerSource('LB-F'),
            eliminateLoser: true
        });

        const matches = [qf1, qf2, ubSemi1, ubSemi2, ubFinal, lbSemi, lbFinal, grandFinal];
        return {
            format: 'double',
            teams,
            matches,
            rounds: [
                { number: 1, title: 'Ronde 1 · Play-In (Kalah Gugur)', matches: [qf1, qf2] },
                { number: 2, title: 'Ronde 2 · Upper Semifinal (Seed #1 & #2 Masuk)', matches: [ubSemi1, ubSemi2] },
                { number: 3, title: 'Ronde 3 · Upper Final & Lower Semifinal', matches: [ubFinal, lbSemi] },
                { number: 4, title: 'Ronde 4 · Lower Final', matches: [lbFinal] },
                { number: 5, title: 'Ronde 5 · Grand Final', matches: [grandFinal] }
            ]
        };
    }

    // ====================================================
    // 3. LEAGUE SYSTEM (UNTOUCHED LOGIC)
    // ====================================================
    function buildRoundRobinSchedule(teams) {
        const list = [...teams];
        if (list.length % 2 === 1) list.push(null);
        const totalRounds = list.length - 1;
        const half = list.length / 2;
        const rounds = [];
        const matches = [];

        for (let round = 1; round <= totalRounds; round++) {
            const roundMatches = [];
            for (let i = 0; i < half; i++) {
                const a = list[i];
                const b = list[list.length - 1 - i];
                if (a && b) {
                    const match = createMatch({
                        id: `RR-R${round}-M${roundMatches.length + 1}`,
                        round,
                        section: 'League Matchday',
                        title: `Matchday ${round} · Match ${roundMatches.length + 1}`,
                        source1: teamSource(a),
                        source2: teamSource(b)
                    });
                    roundMatches.push(match);
                    matches.push(match);
                }
            }
            rounds.push({ number: round, title: `Pekan Pertandingan ${round}`, matches: roundMatches });
            list.splice(1, 0, list.pop());
        }

        return { format: 'league', teams, rounds, matches };
    }

    // ====================================================
    // 4. SWISS STAGE (M7 MLBB 16-Team 5-Round System)
    // ====================================================
    function buildSwissTournament(teams) {
        const state = {
            format: 'swiss',
            teams,
            rounds: [],
            matches: [],
            maxRounds: 5,
            targetWins: 3,
            targetLosses: 3
        };
        generateSwissRound(state, 1);
        return state;
    }

    function getSwissRecords(state, upToRound = Infinity) {
        const records = new Map(state.teams.map(team => [team.id, {
            team,
            wins: 0,
            losses: 0,
            points: 0,
            diff: 0,
            opponents: new Set(),
            status: 'active',
            qualifiedRound: null,
            eliminatedRound: null
        }]));

        state.matches.filter(m => m.round <= upToRound).forEach(match => {
            if (!match.team1 || !match.team2 || !match.winner || !match.loser) return;
            const rec1 = records.get(match.team1.id);
            const rec2 = records.get(match.team2.id);
            rec1.opponents.add(match.team2.id);
            rec2.opponents.add(match.team1.id);
            rec1.diff += (match.score1 - match.score2);
            rec2.diff += (match.score2 - match.score1);

            if (match.winner.id === match.team1.id) {
                rec1.wins += 1;
                rec1.points += 3;
                rec2.losses += 1;
            } else {
                rec2.wins += 1;
                rec2.points += 3;
                rec1.losses += 1;
            }

            if (rec1.wins >= 3 && rec1.status === 'active') {
                rec1.status = 'qualified';
                rec1.qualifiedRound = match.round;
            }
            if (rec2.wins >= 3 && rec2.status === 'active') {
                rec2.status = 'qualified';
                rec2.qualifiedRound = match.round;
            }
            if (rec1.losses >= 3 && rec1.status === 'active') {
                rec1.status = 'eliminated';
                rec1.eliminatedRound = match.round;
            }
            if (rec2.losses >= 3 && rec2.status === 'active') {
                rec2.status = 'eliminated';
                rec2.eliminatedRound = match.round;
            }
        });

        return records;
    }

    function generateSwissRound(state, roundNumber) {
        if (roundNumber > state.maxRounds || state.rounds.some(r => r.number === roundNumber)) return;

        const recordsMap = getSwissRecords(state, roundNumber - 1);
        const activeList = [...recordsMap.values()].filter(r => r.wins < 3 && r.losses < 3);

        if (activeList.length < 2) return;

        const poolsMap = new Map();
        activeList.forEach(item => {
            const key = `${item.wins}-${item.losses}`;
            if (!poolsMap.has(key)) poolsMap.set(key, []);
            poolsMap.get(key).push(item);
        });

        const sortedPoolKeys = [...poolsMap.keys()].sort((a, b) => {
            const [wa, la] = a.split('-').map(Number);
            const [wb, lb] = b.split('-').map(Number);
            return (wb - wa) || (la - lb);
        });

        const roundMatches = [];
        const poolsRenderData = [];
        let matchCounter = 1;

        sortedPoolKeys.forEach(poolKey => {
            const group = poolsMap.get(poolKey);
            group.sort((a, b) => a.team.seed - b.team.seed);

            const used = new Set();
            const poolMatches = [];

            for (let i = 0; i < group.length; i++) {
                const first = group[i];
                if (used.has(first.team.id)) continue;

                let partner = null;
                if (roundNumber === 1) {
                    const half = group.length / 2;
                    const candidate = group[i + half];
                    if (candidate && !used.has(candidate.team.id)) {
                        partner = candidate;
                    }
                }

                if (!partner) {
                    partner = group.slice(i + 1).find(item => !used.has(item.team.id) && !first.opponents.has(item.team.id))
                        || group.slice(i + 1).find(item => !used.has(item.team.id));
                }

                if (!partner) continue;

                used.add(first.team.id);
                used.add(partner.team.id);

                const [w, l] = poolKey.split('-').map(Number);
                let poolType = 'normal';
                let poolLabel = `Pool ${poolKey}`;
                if (w === 2 && l < 2) { poolType = 'qualification'; poolLabel = `Pool ${poolKey} (Match Penentuan Lolos)`; }
                else if (l === 2 && w < 2) { poolType = 'elimination'; poolLabel = `Pool ${poolKey} (Match Eliminasi)`; }
                else if (w === 2 && l === 2) { poolType = 'decider'; poolLabel = `Pool 2-2 (Hidup / Mati)`; }

                const match = createMatch({
                    id: `SW-R${roundNumber}-M${matchCounter}`,
                    round: roundNumber,
                    section: poolLabel,
                    title: `${poolKey} · Match #${matchCounter}`,
                    source1: teamSource(first.team),
                    source2: teamSource(partner.team)
                });
                match.poolKey = poolKey;
                match.poolType = poolType;

                roundMatches.push(match);
                poolMatches.push(match);
                state.matches.push(match);
                matchCounter++;
            }

            poolsRenderData.push({
                poolKey,
                matches: poolMatches
            });
        });

        state.rounds.push({
            number: roundNumber,
            title: `Swiss Stage ${roundNumber}`,
            matches: roundMatches,
            pools: poolsRenderData
        });
    }

    function advanceSwiss(state) {
        state.matches.forEach(match => calculateMatch(match, state.matches));

        for (const round of state.rounds) {
            const complete = round.matches.length > 0 && round.matches.every(match => Boolean(match.winner));
            if (!complete) break;
            if (round.number < state.maxRounds && !state.rounds.some(item => item.number === round.number + 1)) {
                generateSwissRound(state, round.number + 1);
                state.matches.forEach(match => calculateMatch(match, state.matches));
            }
        }
    }

    // ====================================================
    // BUILD & RENDER ORCHESTRATION
    // ====================================================
    function buildTournament(syncToServer = true) {
        const format = getSelectedFormat();
        const teams = getTeamsFromInputs();
        const title = document.getElementById('tourneyName')?.value?.trim() || 'ROK Community Cup 2026';

        const titleBanner = document.getElementById('outputTourneyTitle');
        const badgeBanner = document.getElementById('outputFormatBadge');
        if (titleBanner) titleBanner.textContent = title;
        if (badgeBanner) badgeBanner.textContent = `${formatNames[format]} · ${teams.length} Tim`;

        if (format === 'double') {
            currentTournament = buildDoubleElimination(teams);
        } else if (format === 'league') {
            currentTournament = buildRoundRobinSchedule(teams);
        } else if (format === 'swiss') {
            currentTournament = buildSwissTournament(teams);
        } else {
            currentTournament = buildSingleElimination(teams);
        }

        renderTournament(syncToServer);
    }

    function renderTournament(syncToServer = false) {
        const deck = document.getElementById('generatedTreeDeck');
        if (!deck || !currentTournament) return;

        if (currentTournament.format === 'swiss') {
            advanceSwiss(currentTournament);
        } else {
            currentTournament.matches.forEach(match => calculateMatch(match, currentTournament.matches));
        }

        deck.innerHTML = '';

        const statsMap = computeAllTeamStats(currentTournament);

        if (currentTournament.format === 'swiss') {
            renderSwissM7Layout(deck, currentTournament, statsMap);
        } else if (currentTournament.format === 'double') {
            renderDoubleEliminationLayout(deck, currentTournament, statsMap);
        } else {
            renderStandardBracketLayout(deck, currentTournament, statsMap);
        }

        if (currentTournament.format === 'league') {
            renderStandings(deck, currentTournament);
        }

        // Draw directional SVG connector arrows after DOM layout settles
        requestAnimationFrame(() => {
            drawAllBracketArrows();
        });

        if (syncToServer && !IS_READ_ONLY && !isApplyingRemoteUpdate) {
            scheduleRealtimeSync();
        }
    }

    // ====================================================
    // SVG DIRECTIONAL ARROWS ENGINE
    // ====================================================
    function createArrowSvgElement() {
        const svgNS = 'http://www.w3.org/2000/svg';
        const svg = document.createElementNS(svgNS, 'svg');
        svg.setAttribute('class', 'bracket-connectors-svg');
        svg.setAttribute('aria-hidden', 'true');
        svg.innerHTML = `
            <defs>
                <marker id="rok-arrow-default" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                    <path d="M 0 1.5 L 8.5 5 L 0 8.5 z" fill="#64748b"/>
                </marker>
                <marker id="rok-arrow-active" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6.5" markerHeight="6.5" orient="auto-start-reverse">
                    <path d="M 0 1 L 9 5 L 0 9 z" fill="#38bdf8"/>
                </marker>
                <marker id="rok-arrow-loser" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                    <path d="M 0 1.5 L 8.5 5 L 0 8.5 z" fill="#f59e0b"/>
                </marker>
            </defs>
        `;
        return svg;
    }

    function drawAllBracketArrows() {
        document.querySelectorAll('.bracket-tree-canvas').forEach(canvas => {
            const oldSvg = canvas.querySelector('.bracket-connectors-svg');
            if (oldSvg) oldSvg.remove();

            if (!currentTournament) return;

            const svg = createArrowSvgElement();
            const width = Math.max(canvas.scrollWidth, canvas.clientWidth);
            const height = Math.max(canvas.scrollHeight, canvas.clientHeight);
            svg.setAttribute('width', String(width));
            svg.setAttribute('height', String(height));
            svg.style.width = width + 'px';
            svg.style.height = height + 'px';
            canvas.prepend(svg);

            const canvasRect = canvas.getBoundingClientRect();
            const matchMap = new Map(currentTournament.matches.map(m => [m.id, m]));

            currentTournament.matches.forEach(targetMatch => {
                const targetEl = canvas.querySelector(`.bracket-match[data-match-id="${targetMatch.id}"]`);
                if (!targetEl) return;

                [
                    { src: targetMatch.source1, slotIndex: 0 },
                    { src: targetMatch.source2, slotIndex: 1 }
                ].forEach(({ src, slotIndex }) => {
                    if (!src || src.type === 'team' || !src.matchId) return;

                    const sourceEl = canvas.querySelector(`.bracket-match[data-match-id="${src.matchId}"]`);
                    if (!sourceEl) return;

                    const sourceMatch = matchMap.get(src.matchId);
                    const sRect = sourceEl.getBoundingClientRect();
                    const tRect = targetEl.getBoundingClientRect();

                    // Calculate coordinates relative to canvas
                    const startX = (sRect.right - canvasRect.left) + canvas.scrollLeft;
                    const startY = (sRect.top - canvasRect.top) + canvas.scrollTop + (sRect.height / 2);

                    const endX = (tRect.left - canvasRect.left) + canvas.scrollLeft - 4;
                    const rowEls = targetEl.querySelectorAll('.bracket-team-row');
                    let endY = (tRect.top - canvasRect.top) + canvas.scrollTop + (tRect.height * (slotIndex === 0 ? 0.36 : 0.72));
                    if (rowEls[slotIndex]) {
                        const rRect = rowEls[slotIndex].getBoundingClientRect();
                        endY = (rRect.top - canvasRect.top) + canvas.scrollTop + (rRect.height / 2);
                    }

                    if (endX <= startX + 8) return; // Only draw forward left-to-right connectors cleanly

                    const midX = startX + (endX - startX) * 0.52;
                    const pathData = `M ${startX} ${startY} L ${midX} ${startY} L ${midX} ${endY} L ${endX} ${endY}`;

                    const isWinnerResolved = src.type === 'winner' && Boolean(sourceMatch?.winner);
                    const isLoserResolved = src.type === 'loser' && Boolean(sourceMatch?.loser);

                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('d', pathData);
                    path.setAttribute('fill', 'none');
                    path.setAttribute('class', `bracket-arrow-path ${isWinnerResolved ? 'is-active' : (isLoserResolved ? 'is-loser' : '')}`);
                    path.setAttribute('marker-end', isWinnerResolved
                        ? 'url(#rok-arrow-active)'
                        : (isLoserResolved ? 'url(#rok-arrow-loser)' : 'url(#rok-arrow-default)')
                    );
                    if (src.type === 'loser') {
                        path.setAttribute('stroke-dasharray', '5 4');
                    }

                    svg.appendChild(path);
                });
            });
        });
    }

    window.addEventListener('resize', () => {
        requestAnimationFrame(drawAllBracketArrows);
    });

    // ====================================================
    // LAYOUT RENDERERS
    // ====================================================
    function renderStandardBracketLayout(deck, state, statsMap) {
        if (state.format === 'single' && state.autoAdvancedTeams && state.autoAdvancedTeams.length > 0) {
            const notice = document.createElement('div');
            notice.className = 'bracket-auto-advance-banner';
            const teamNames = state.autoAdvancedTeams.map(t => `<span class="auto-adv-chip">#${t.seed} ${t.name}</span>`).join(' ');
            notice.innerHTML = `
                <div class="bracket-auto-advance-banner__title">
                    <span>&#x2728;</span>
                    <strong>Lolos Otomatis ke Babak Berikutnya (Unggulan Seed Teratas):</strong>
                </div>
                <div class="bracket-auto-advance-banner__chips">${teamNames}</div>
            `;
            deck.appendChild(notice);
        }

        const canvas = document.createElement('div');
        canvas.className = 'bracket-tree-canvas';

        const grid = document.createElement('div');
        grid.className = 'bracket-rounds-grid';

        state.rounds.forEach((round, roundIdx) => {
            const column = document.createElement('section');
            column.className = 'bracket-round-col';

            const heading = document.createElement('div');
            heading.className = 'bracket-round-col__header';
            heading.innerHTML = `
                <span class="round-step-pill">STAGE ${roundIdx + 1}</span>
                <h4>${round.title}</h4>
                ${roundIdx < state.rounds.length - 1 ? '<span class="round-next-arrow" title="Melaju ke babak berikutnya">&rarr;</span>' : '<span class="round-trophy-icon">&#x1F3C6;</span>'}
            `;
            column.appendChild(heading);

            const body = document.createElement('div');
            body.className = 'bracket-round-col__body';

            if (round.autoAdvanced && round.autoAdvanced.length > 0) {
                round.autoAdvanced.forEach(t => {
                    const advCard = document.createElement('div');
                    advCard.className = 'bracket-match bracket-match--auto';
                    advCard.innerHTML = `
                        <div class="bracket-match__top">
                            <span class="match-code-tag">SEED #${t.seed}</span>
                            <span class="match-status-pill match-status-pill--auto">LOLOS OTOMATIS</span>
                        </div>
                        <div class="bracket-team-row is-winner">
                            <div class="bracket-team-main">
                                <button type="button" class="bracket-team-logo-btn" data-seed="${t.seed}" title="Klik untuk melihat Poin &amp; Nomor WhatsApp ${t.name}">
                                    <img src="${t.logo || presetLogos[0]}" alt="${t.name}" onerror="this.src='/img/dummy-division.svg'">
                                </button>
                                <span class="bracket-team-name">#${t.seed} ${t.name}</span>
                            </div>
                            <span class="auto-pass-badge">Lolos Otomatis ke Babak Berikutnya &rarr;</span>
                        </div>
                    `;
                    advCard.querySelector('.bracket-team-logo-btn')?.addEventListener('click', () => openTeamModalBySeed(t.seed));
                    body.appendChild(advCard);
                });
            }

            round.matches.forEach(match => {
                body.appendChild(renderMatchCard(match, state, statsMap));
            });

            column.appendChild(body);
            grid.appendChild(column);
        });

        canvas.appendChild(grid);
        deck.appendChild(canvas);

        // Champion spotlight banner if Grand Final has winner
        const lastRound = state.rounds[state.rounds.length - 1];
        const finalMatch = lastRound?.matches?.[0];
        if (state.format === 'single' && finalMatch?.winner) {
            renderChampionBanner(deck, finalMatch.winner, statsMap.get(finalMatch.winner.id));
        }
    }

    function renderDoubleEliminationLayout(deck, state, statsMap) {
        const sections = [
            { title: 'UPPER BRACKET (JALUR UTAMA)', key: 'Upper Bracket', accent: 'upper' },
            { title: 'LOWER BRACKET (KESEMPATAN KEDUA)', key: 'Lower Bracket', accent: 'lower' },
            { title: 'GRAND FINAL', key: 'Grand Final', accent: 'final' }
        ];

        sections.forEach(sec => {
            const secMatches = state.matches.filter(m => m.section === sec.key);
            if (secMatches.length === 0) return;

            const block = document.createElement('div');
            block.className = `double-elim-section double-elim-section--${sec.accent}`;

            const header = document.createElement('div');
            header.className = 'double-elim-section__header';
            header.innerHTML = `<h4>${sec.title}</h4>`;
            block.appendChild(header);

            const canvas = document.createElement('div');
            canvas.className = 'bracket-tree-canvas';

            const grid = document.createElement('div');
            grid.className = 'bracket-rounds-grid';

            const roundNums = [...new Set(secMatches.map(m => m.round))].sort((a, b) => a - b);
            roundNums.forEach((rNum, idx) => {
                const rMatches = secMatches.filter(m => m.round === rNum);
                const col = document.createElement('section');
                col.className = 'bracket-round-col';

                const h = document.createElement('div');
                h.className = 'bracket-round-col__header';
                h.innerHTML = `
                    <span class="round-step-pill">RONDE ${rNum}</span>
                    <h4>${rMatches[0]?.title?.replace(/#\d+$/, '')?.trim() || ('Babak ' + rNum)}</h4>
                    ${idx < roundNums.length - 1 ? '<span class="round-next-arrow">&rarr;</span>' : ''}
                `;
                col.appendChild(h);

                const body = document.createElement('div');
                body.className = 'bracket-round-col__body';
                rMatches.forEach(m => body.appendChild(renderMatchCard(m, state, statsMap)));

                col.appendChild(body);
                grid.appendChild(col);
            });

            canvas.appendChild(grid);
            block.appendChild(canvas);
            deck.appendChild(block);
        });

        const gf = state.matches.find(m => m.id === 'GF');
        if (gf?.winner) {
            renderChampionBanner(deck, gf.winner, statsMap.get(gf.winner.id));
        }
    }

    function renderSwissM7Layout(deck, state, statsMap) {
        const recordsMap = getSwissRecords(state);
        const qualifiedList = [...recordsMap.values()].filter(r => r.wins >= 3).sort((a, b) => a.qualifiedRound - b.qualifiedRound || b.diff - a.diff);
        const eliminatedList = [...recordsMap.values()].filter(r => r.losses >= 3).sort((a, b) => a.eliminatedRound - b.eliminatedRound || a.diff - b.diff);

        const banner = document.createElement('div');
        banner.className = 'swiss-m7-banner';
        banner.innerHTML = `
            <div>
                <span class="badge-mini">MLBB M7 WORLD CHAMPIONSHIP FORMAT</span>
                <h4>SWISS STAGE PROGRESSION (16 TIM &bull; 5 STAGES)</h4>
                <p>Raih <strong>3 Kemenangan</strong> untuk lolos ke Knockout Stage (&rarr;). Tim yang menelan <strong>3 Kekalahan</strong> langsung tereliminasi.</p>
            </div>
            <div class="swiss-m7-legend">
                <span class="swiss-legend-pill swiss-legend-pill--qual">&#10003; 3 Win = LOLOS PLAYOFF (${qualifiedList.length}/8)</span>
                <span class="swiss-legend-pill swiss-legend-pill--elim">&times; 3 Loss = ELIMINASI (${eliminatedList.length}/8)</span>
            </div>
        `;
        deck.appendChild(banner);

        const flowContainer = document.createElement('div');
        flowContainer.className = 'swiss-m7-flow-grid';

        for (let r = 1; r <= 5; r++) {
            const roundObj = state.rounds.find(item => item.number === r);
            const stageCol = document.createElement('div');
            stageCol.className = 'swiss-stage-col';

            const stageHeader = document.createElement('div');
            stageHeader.className = 'swiss-stage-col__header';
            stageHeader.innerHTML = `<span>STAGE ${r}</span> <span>&rarr;</span>`;
            stageCol.appendChild(stageHeader);

            if (!roundObj) {
                const lockedBox = document.createElement('div');
                lockedBox.className = 'swiss-stage-locked';
                lockedBox.innerHTML = `Selesaikan seluruh skor di <strong>Stage ${r - 1}</strong> untuk membuka bagan Stage ${r}.`;
                stageCol.appendChild(lockedBox);
            } else {
                roundObj.pools.forEach(pool => {
                    const [w, l] = pool.poolKey.split('-').map(Number);
                    let borderColor = 'var(--rok-surface-border)';
                    let headerBg = 'rgba(148, 163, 184, 0.12)';
                    let textColor = 'var(--rok-text-bright)';
                    let subtitle = 'REGULAR POOL';

                    if (w === 2 && l < 2) {
                        borderColor = '#22c55e';
                        headerBg = 'rgba(34, 197, 94, 0.16)';
                        textColor = '#4ade80';
                        subtitle = 'QUALIFICATION MATCH';
                    } else if (l === 2 && w < 2) {
                        borderColor = '#ef4444';
                        headerBg = 'rgba(239, 68, 68, 0.16)';
                        textColor = '#f87171';
                        subtitle = 'ELIMINATION MATCH';
                    } else if (w === 2 && l === 2) {
                        borderColor = '#eab308';
                        headerBg = 'rgba(234, 179, 8, 0.18)';
                        textColor = '#facc15';
                        subtitle = 'DECIDER (DO OR DIE)';
                    }

                    const poolBox = document.createElement('div');
                    poolBox.className = 'swiss-pool-box';
                    poolBox.style.borderColor = borderColor;

                    const poolHead = document.createElement('div');
                    poolHead.className = 'swiss-pool-box__head';
                    poolHead.style.background = headerBg;
                    poolHead.style.color = textColor;
                    poolHead.innerHTML = `<span>BRACKET ${pool.poolKey}</span><span style="font-size:10px;">${subtitle}</span>`;
                    poolBox.appendChild(poolHead);

                    const matchesContainer = document.createElement('div');
                    matchesContainer.className = 'swiss-pool-box__matches';
                    pool.matches.forEach(m => {
                        matchesContainer.appendChild(renderMatchCard(m, state, statsMap));
                    });

                    poolBox.appendChild(matchesContainer);
                    stageCol.appendChild(poolBox);
                });
            }

            flowContainer.appendChild(stageCol);
        }

        // Outcome Column
        const outcomeCol = document.createElement('div');
        outcomeCol.className = 'swiss-stage-col';
        outcomeCol.innerHTML = `<div class="swiss-stage-col__header" style="background: rgba(34,197,94,0.18); border-color:#22c55e; color:#4ade80;"><span>HASIL AKHIR</span> <span>&#x1F3C6;</span></div>`;

        const qualBox = document.createElement('div');
        qualBox.className = 'swiss-outcome-box swiss-outcome-box--qual';
        qualBox.innerHTML = `<div class="swiss-outcome-box__title">&#10003; LOLOS KNOCKOUT (3 WIN)</div>`;
        const qualListEl = document.createElement('div');
        qualListEl.className = 'swiss-outcome-list';
        if (qualifiedList.length === 0) {
            qualListEl.innerHTML = `<div class="swiss-outcome-empty">Menunggu tim mencapai 3 kemenangan...</div>`;
        } else {
            qualifiedList.forEach(r => {
                const row = document.createElement('div');
                row.className = 'swiss-outcome-item swiss-outcome-item--qual';
                row.innerHTML = `<span>#${r.team.seed} ${r.team.name}</span><strong>${r.wins}-${r.losses}</strong>`;
                row.addEventListener('click', () => openTeamModalBySeed(r.team.seed));
                qualListEl.appendChild(row);
            });
        }
        qualBox.appendChild(qualListEl);
        outcomeCol.appendChild(qualBox);

        const elimBox = document.createElement('div');
        elimBox.className = 'swiss-outcome-box swiss-outcome-box--elim';
        elimBox.innerHTML = `<div class="swiss-outcome-box__title">&times; TERELIMINASI (3 LOSS)</div>`;
        const elimListEl = document.createElement('div');
        elimListEl.className = 'swiss-outcome-list';
        if (eliminatedList.length === 0) {
            elimListEl.innerHTML = `<div class="swiss-outcome-empty">Belum ada tim gugur...</div>`;
        } else {
            eliminatedList.forEach(r => {
                const row = document.createElement('div');
                row.className = 'swiss-outcome-item swiss-outcome-item--elim';
                row.innerHTML = `<span>#${r.team.seed} ${r.team.name}</span><strong>${r.wins}-${r.losses}</strong>`;
                row.addEventListener('click', () => openTeamModalBySeed(r.team.seed));
                elimListEl.appendChild(row);
            });
        }
        elimBox.appendChild(elimListEl);
        outcomeCol.appendChild(elimBox);

        flowContainer.appendChild(outcomeCol);
        deck.appendChild(flowContainer);
    }

    // ====================================================
    // MATCH CARD COMPONENT (With Clickable Logo, Points, WA, Arrows)
    // ====================================================
    function renderMatchCard(match, state, statsMap) {
        const card = document.createElement('article');
        const hasBothTeams = Boolean(match.team1 && match.team2);
        const isCompleted = Boolean(match.winner);

        card.className = `bracket-match ${isCompleted ? 'is-completed' : (hasBothTeams ? 'is-ready' : 'is-waiting')}`;
        card.dataset.matchId = match.id;

        const topBar = document.createElement('div');
        topBar.className = 'bracket-match__top';

        const codeSpan = document.createElement('span');
        codeSpan.className = 'match-code-tag';
        codeSpan.textContent = match.id;

        const statusBadge = document.createElement('span');
        statusBadge.className = `match-status-pill ${isCompleted ? 'match-status-pill--done' : (hasBothTeams ? 'match-status-pill--live' : 'match-status-pill--wait')}`;
        statusBadge.textContent = isCompleted ? 'SELESAI' : (hasBothTeams ? 'LIVE / SIAP' : 'MENUNGGU');

        topBar.append(codeSpan, statusBadge);
        card.appendChild(topBar);

        const a = match.team1;
        const b = match.team2;

        [a, b].forEach((team, i) => {
            const row = document.createElement('div');
            const isRowWinner = Boolean(team && match.winner?.id === team.id);
            const isRowLoser = Boolean(team && match.loser?.id === team.id);
            row.className = `bracket-team-row ${isRowWinner ? 'is-winner' : ''} ${isRowLoser ? 'is-loser' : ''}`;

            const mainInfo = document.createElement('div');
            mainInfo.className = 'bracket-team-main';

            if (team) {
                const stat = statsMap.get(team.id);
                const pts = stat ? stat.points : 0;

                // Clickable Logo Button -> Opens Team Modal with WhatsApp & Points
                const logoBtn = document.createElement('button');
                logoBtn.type = 'button';
                logoBtn.className = 'bracket-team-logo-btn';
                logoBtn.title = `Klik untuk melihat Poin Pertandingan (${pts} Pts) & Chat WhatsApp ${team.name}`;
                logoBtn.innerHTML = `
                    <img src="${team.logo || presetLogos[(team.seed - 1) % presetLogos.length]}" alt="${team.name}" onerror="this.src='/img/dummy-division.svg'">
                `;
                logoBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openTeamModalBySeed(team.seed);
                });

                const textWrap = document.createElement('div');
                textWrap.className = 'bracket-team-text';
                textWrap.style.cursor = 'pointer';
                textWrap.title = 'Klik untuk detail tim, poin & kontak WhatsApp';
                textWrap.addEventListener('click', () => openTeamModalBySeed(team.seed));

                const nameLine = document.createElement('span');
                nameLine.className = 'bracket-team-name';
                nameLine.innerHTML = `<small class="team-seed-tag">#${team.seed}</small> ${team.name} ${isRowWinner ? '<span class="winner-crown" title="Pemenang Match">&#x1F451;</span>' : ''}`;

                const metaLine = document.createElement('span');
                metaLine.className = 'bracket-team-submeta';
                metaLine.innerHTML = `<strong class="team-pts-pill">${pts} Pts</strong> &bull; <span class="team-wa-hint">&#x1F4AC; Info &amp; WA</span>`;

                textWrap.append(nameLine, metaLine);
                mainInfo.append(logoBtn, textWrap);
            } else {
                const placeholderBadge = document.createElement('div');
                placeholderBadge.className = 'bracket-team-logo-placeholder';
                placeholderBadge.innerHTML = '&rarr;';

                const nameSpan = document.createElement('span');
                nameSpan.className = 'bracket-team-name is-tbd';
                nameSpan.textContent = sourcePlaceholder(i === 0 ? match.source1 : match.source2);

                mainInfo.append(placeholderBadge, nameSpan);
            }

            const input = document.createElement('input');
            input.type = 'number';
            input.min = '0';
            input.max = '99';
            input.step = '1';
            input.className = 'bracket-score-input';
            input.setAttribute('aria-label', `Skor ${team?.name || 'TBD'}`);
            input.value = match[i === 0 ? 'score1' : 'score2'] ?? '';
            input.disabled = IS_READ_ONLY || !(a && b);
            input.placeholder = '–';

            input.addEventListener('change', () => {
                if (IS_READ_ONLY) return;
                if (state.format === 'swiss' && match.round < state.rounds.length) {
                    state.rounds = state.rounds.slice(0, match.round);
                    state.matches = state.matches.filter(item => item.round <= match.round);
                }
                const previousWinner = match.winner?.id ?? null;
                const key = i === 0 ? 'score1' : 'score2';
                const other = i === 0 ? 'score2' : 'score1';
                const value = input.value.trim();
                match[key] = value === '' ? null : Number.parseInt(value, 10);
                if (match[key] !== null && (!Number.isInteger(match[key]) || match[key] < 0 || match[key] > 999)) {
                    match[key] = null;
                }
                if (match[key] !== null && match[other] !== null && match[key] === match[other]) {
                    alert('Skor tidak boleh seri untuk pertandingan yang membutuhkan pemenang.');
                    match[key] = null;
                }

                // Persist in savedScoresMap
                savedScoresMap[match.id] = {
                    score1: match.score1,
                    score2: match.score2
                };

                calculateMatch(match, state.matches);
                if (previousWinner !== (match.winner?.id ?? null)) {
                    clearDependentScores(state, match.id);
                }
                renderTournament(true);
            });

            row.append(mainInfo, input);
            card.appendChild(row);
        });

        if (match.winner) {
            const footer = document.createElement('div');
            footer.className = 'bracket-match__footer';
            footer.innerHTML = `<span class="match-adv-label">&#10003; Pemenang Melaju: <strong>${match.winner.name}</strong> &rarr;</span>`;
            if (state.format === 'double' && match.eliminateLoser && match.loser) {
                footer.innerHTML += `<span class="bracket-eliminated-note">&times; Gugur: ${match.loser.name}</span>`;
            }
            card.appendChild(footer);
        } else if (match.note) {
            const noteEl = document.createElement('div');
            noteEl.className = 'bracket-match__footer';
            noteEl.innerHTML = `<span style="color:var(--rok-muted-light);font-size:11px;">${match.note}</span>`;
            card.appendChild(noteEl);
        }

        return card;
    }

    function renderChampionBanner(deck, winnerTeam, stat) {
        const banner = document.createElement('div');
        banner.className = 'bracket-champion-banner';
        banner.innerHTML = `
            <div class="bracket-champion-banner__left">
                <img src="${winnerTeam.logo || presetLogos[0]}" alt="${winnerTeam.name}" class="champion-logo" onerror="this.src='/img/dummy-division.svg'">
                <div>
                    <span class="champion-badge">&#x1F3C6; GRAND CHAMPION TURNAMEN</span>
                    <h3>${winnerTeam.name} (Seed #${winnerTeam.seed})</h3>
                    <p>Total Poin Pertandingan: <strong>${stat ? stat.points : 0} Pts</strong> &bull; Rekor: <strong>${stat ? stat.wins : 0}W - ${stat ? stat.losses : 0}L</strong></p>
                </div>
            </div>
            <button type="button" class="btn-rok btn-rok--gold btn-rok--sm" id="championInfoBtn">
                &#x1F4AC; Lihat Poin &amp; Kontak WhatsApp Juara
            </button>
        `;
        banner.querySelector('#championInfoBtn')?.addEventListener('click', () => openTeamModalBySeed(winnerTeam.seed));
        deck.appendChild(banner);
    }

    function clearDependentScores(state, parentMatchId) {
        const cleared = new Set([parentMatchId]);
        let changed = true;
        while (changed) {
            changed = false;
            state.matches.forEach(match => {
                const dependsOnCleared = [match.source1, match.source2].some(source => source && source.type !== 'team' && cleared.has(source.matchId));
                if (dependsOnCleared && !cleared.has(match.id)) {
                    match.score1 = null;
                    match.score2 = null;
                    match.winner = null;
                    match.loser = null;
                    delete savedScoresMap[match.id];
                    cleared.add(match.id);
                    changed = true;
                }
            });
        }
    }

    function renderStandings(deck, state) {
        const table = document.createElement('div');
        table.className = 'league-standings';
        const heading = document.createElement('h3');
        heading.textContent = 'Klasemen Liga & Poin Pertandingan (League System)';
        table.appendChild(heading);

        const records = new Map(state.teams.map(t => [t.id, { team: t, played: 0, wins: 0, losses: 0, points: 0, scored: 0, conceded: 0 }]));
        state.matches.forEach(m => {
            if (!m.winner || !m.loser) return;
            const a = records.get(m.team1.id), b = records.get(m.team2.id);
            a.played++; b.played++;
            a.scored += m.score1; a.conceded += m.score2;
            b.scored += m.score2; b.conceded += m.score1;
            if (m.score1 > m.score2) { a.wins++; b.losses++; a.points += 3; }
            else { b.wins++; a.losses++; b.points += 3; }
        });

        const tableEl = document.createElement('table');
        tableEl.className = 'standings-table';
        const thead = document.createElement('thead'), hr = document.createElement('tr');
        ['#', 'Tim (Klik untuk WA)', 'P', 'W', 'L', 'Skor +/-', 'Pts'].forEach(t => {
            const th = document.createElement('th');
            th.textContent = t;
            hr.appendChild(th);
        });
        thead.appendChild(hr);
        tableEl.appendChild(thead);

        const tbody = document.createElement('tbody');
        [...records.values()].sort((a, b) => b.points - a.points || b.wins - a.wins || (b.scored - b.conceded) - (a.scored - a.conceded) || a.team.seed - b.team.seed).forEach((r, i) => {
            const tr = document.createElement('tr');
            tr.style.cursor = 'pointer';
            tr.addEventListener('click', () => openTeamModalBySeed(r.team.seed));
            [i + 1, r.team.name, r.played, r.wins, r.losses, `${r.scored - r.conceded}`, r.points].forEach(v => {
                const td = document.createElement('td');
                td.textContent = String(v);
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
        tableEl.appendChild(tbody);
        table.appendChild(tableEl);
        deck.appendChild(table);
    }

    // ====================================================
    // INTERACTIVE TEAM MODAL (WhatsApp + Match Points)
    // ====================================================
    function openTeamModalBySeed(seed) {
        if (!currentTournament) return;
        const team = currentTournament.teams.find(t => t.seed === Number(seed));
        if (!team) return;

        const statsMap = computeAllTeamStats(currentTournament);
        const stat = statsMap.get(team.id) || { played: 0, wins: 0, losses: 0, scored: 0, conceded: 0, points: 0, isEliminated: false };
        const tourneyTitle = document.getElementById('tourneyName')?.value || 'ROK Tournament';

        const modal = document.getElementById('rokTeamModal');
        if (!modal) return;

        document.getElementById('modalTeamLogo').src = team.logo || presetLogos[(team.seed - 1) % presetLogos.length];
        document.getElementById('modalTeamSeed').textContent = `#${team.seed}`;
        document.getElementById('modalTeamName').textContent = team.name;
        document.getElementById('modalTeamTourney').textContent = `${tourneyTitle} • ${formatNames[currentTournament.format]}`;

        const badgeEl = document.getElementById('modalTeamStatusBadge');
        if (stat.isEliminated) {
            badgeEl.textContent = 'TERELIMINASI';
            badgeEl.style.background = 'rgba(239, 68, 68, 0.18)';
            badgeEl.style.color = '#f87171';
        } else if (stat.wins > 0) {
            badgeEl.textContent = 'TIM UNGGULAN AKTIF';
            badgeEl.style.background = 'rgba(34, 197, 94, 0.18)';
            badgeEl.style.color = '#4ade80';
        } else {
            badgeEl.textContent = 'PESERTA AKTIF';
            badgeEl.style.background = 'rgba(56, 189, 248, 0.18)';
            badgeEl.style.color = '#38bdf8';
        }

        document.getElementById('modalTeamPoints').textContent = `${stat.points} Pts`;
        document.getElementById('modalTeamRecord').textContent = `${stat.wins}W - ${stat.losses}L`;
        document.getElementById('modalTeamPlayed').textContent = `${stat.played} Match Diselesaikan`;
        const diff = stat.scored - stat.conceded;
        document.getElementById('modalTeamGameScore').textContent = `${stat.scored} - ${stat.conceded} (${diff >= 0 ? '+' + diff : diff})`;

        const waNormalized = normalizeWaNumber(team.whatsapp);
        document.getElementById('modalTeamWaNumber').textContent = formatWaDisplay(team.whatsapp);
        const waMsg = encodeURIComponent(`Halo Kapten/Manajer Tim *${team.name}* (Seed #${team.seed}), kami ingin berkoordinasi mengenai jadwal/lobby pertandingan di turnamen *${tourneyTitle}* (ROK Esports).`);
        document.getElementById('modalTeamWaBtn').href = `https://wa.me/${waNormalized}?text=${waMsg}`;

        modal.hidden = false;
    }

    // ====================================================
    // LOGO PICKER & UPLOADER (Challenger / Immortal)
    // ====================================================
    function openLogoPicker(seed) {
        activeLogoPickSeed = seed;
        const modal = document.getElementById('rokLogoPickerModal');
        const grid = document.getElementById('presetLogosGrid');
        if (!modal || !grid) return;

        const team = currentTournament?.teams?.find(t => t.seed === seed);
        document.getElementById('logoPickerTitle').textContent = `Pilih Logo — ${team?.name || ('Tim #' + seed)}`;

        grid.innerHTML = '';
        presetLogos.forEach(logoPath => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'preset-logo-option';
            btn.innerHTML = `<img src="${logoPath}" alt="Emblem">`;
            btn.addEventListener('click', () => {
                applyLogoToSeed(seed, logoPath);
                modal.hidden = true;
            });
            grid.appendChild(btn);
        });

        modal.hidden = false;
    }

    function applyLogoToSeed(seed, logoSrc) {
        const card = document.querySelector(`.team-entry-card[data-seed="${seed}"]`);
        const img = card?.querySelector('.team-entry-logo');
        if (img) img.setAttribute('src', logoSrc);
        updateTeamDataInPlace(seed);
    }

    // ====================================================
    // REAL-TIME SERVER SYNC & POLLING
    // ====================================================
    function serializeCurrentState() {
        if (!currentTournament) return null;
        const scores = {};
        currentTournament.matches.forEach(m => {
            if (m.score1 !== null || m.score2 !== null) {
                scores[m.id] = { score1: m.score1, score2: m.score2 };
            }
        });

        return {
            title: document.getElementById('tourneyName')?.value?.trim() || 'ROK Community Cup 2026',
            format: currentTournament.format,
            teamCount: currentTournament.teams.length,
            teams: currentTournament.teams,
            scores
        };
    }

    function scheduleRealtimeSync() {
        if (IS_READ_ONLY || isApplyingRemoteUpdate) return;
        clearTimeout(syncDebounceTimer);
        syncDebounceTimer = setTimeout(() => {
            pushRealtimeStateToServer();
        }, 350);
    }

    async function pushRealtimeStateToServer() {
        const payload = serializeCurrentState();
        if (!payload) return;

        const statusText = document.getElementById('realtimeStatusText');
        const lastSyncEl = document.getElementById('realtimeLastSync');
        if (statusText) statusText.textContent = 'MENYIMPAN & MENYINKRONKAN LIVE BRACKET...';

        // Instant cross-tab sync
        if (broadcastChannel) {
            broadcastChannel.postMessage({ type: 'BRACKET_UPDATED', state: payload });
        }

        try {
            const response = await fetch(SYNC_API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                const data = await response.json();
                lastKnownUpdatedAt = data.updated_at || new Date().toISOString();
                if (statusText) statusText.textContent = 'LIVE REAL-TIME TERSINKRONISASI';
                if (lastSyncEl) {
                    const timeStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    lastSyncEl.textContent = `Tersimpan otomatis pada ${timeStr} • Penonton melihat perubahan ini secara real-time.`;
                }
            }
        } catch (err) {
            console.warn('Live sync warning:', err);
        }
    }

    function applyRemoteBracketState(remoteState, updatedAt = null) {
        if (!remoteState || typeof remoteState !== 'object') return;
        isApplyingRemoteUpdate = true;

        try {
            const nameInput = document.getElementById('tourneyName');
            const formatSelect = document.getElementById('tourneyFormat');
            const countInput = document.getElementById('teamCountInput');
            const countSelect = document.getElementById('teamCountSelect');

            if (nameInput && remoteState.title) nameInput.value = remoteState.title;
            if (formatSelect && remoteState.format) formatSelect.value = remoteState.format;

            const tCount = Number(remoteState.teamCount || remoteState.teams?.length || 8);
            if (countInput) countInput.value = String(tCount);
            if (countSelect && [4, 6].includes(tCount)) countSelect.value = String(tCount);

            savedScoresMap = remoteState.scores || {};

            syncTeamCountOptions();
            renderTeamInputs(remoteState.teams || null);
            buildTournament(false);

            if (updatedAt) {
                lastKnownUpdatedAt = updatedAt;
                const lastSyncEl = document.getElementById('realtimeLastSync');
                if (lastSyncEl) {
                    const timeStr = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    lastSyncEl.textContent = `Diperbarui secara real-time pada ${timeStr}`;
                }
            }
        } finally {
            isApplyingRemoteUpdate = false;
        }
    }

    async function pollServerState() {
        try {
            const res = await fetch(STATE_API_URL + '?_t=' + Date.now(), {
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });
            if (!res.ok) return;
            const data = await res.json();
            if (data.updated_at && data.updated_at !== lastKnownUpdatedAt && data.bracket_state) {
                // Avoid overwriting if user is actively typing inside an input
                const activeTag = document.activeElement?.tagName;
                if (!IS_READ_ONLY && (activeTag === 'INPUT' || activeTag === 'SELECT')) return;
                applyRemoteBracketState(data.bracket_state, data.updated_at);
            }
        } catch (err) {
            // Silent retry on next interval
        }
    }

    // Initialize on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        if (INITIAL_SERVER_STATE && typeof INITIAL_SERVER_STATE === 'object') {
            applyRemoteBracketState(INITIAL_SERVER_STATE, INITIAL_SERVER_STATE.updatedAt || null);
        } else {
            syncTeamCountOptions();
            renderTeamInputs();
            buildTournament(false);
        }

        document.getElementById('tourneyFormat')?.addEventListener('change', () => {
            savedScoresMap = {};
            syncTeamCountOptions();
            renderTeamInputs();
            buildTournament(true);
        });

        document.getElementById('teamCountInput')?.addEventListener('input', () => {
            savedScoresMap = {};
            renderTeamInputs();
            buildTournament(true);
        });

        document.getElementById('teamCountSelect')?.addEventListener('change', () => {
            savedScoresMap = {};
            renderTeamInputs();
            buildTournament(true);
        });

        document.getElementById('tourneyName')?.addEventListener('input', () => {
            const titleBanner = document.getElementById('outputTourneyTitle');
            if (titleBanner) titleBanner.textContent = document.getElementById('tourneyName').value || 'ROK Community Cup';
            scheduleRealtimeSync();
        });

        document.getElementById('generateBracketBtn')?.addEventListener('click', () => {
            buildTournament(true);
        });

        document.getElementById('resetScoresBtn')?.addEventListener('click', () => {
            savedScoresMap = {};
            buildTournament(true);
        });

        // Copy Shareable Link Button
        document.getElementById('copyShareLinkBtn')?.addEventListener('click', () => {
            const urlInput = document.getElementById('shareBracketUrlInput');
            if (!urlInput) return;
            urlInput.select();
            navigator.clipboard?.writeText(urlInput.value);
            const btn = document.getElementById('copyShareLinkBtn');
            const prev = btn.innerHTML;
            btn.innerHTML = '&#10003; Link Tersalin!';
            setTimeout(() => { btn.innerHTML = prev; }, 2000);
        });

        // Modal Close Handlers
        document.getElementById('closeTeamModalBtn')?.addEventListener('click', () => {
            document.getElementById('rokTeamModal').hidden = true;
        });
        document.getElementById('rokTeamModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'rokTeamModal') e.target.hidden = true;
        });

        document.getElementById('closeLogoPickerBtn')?.addEventListener('click', () => {
            document.getElementById('rokLogoPickerModal').hidden = true;
        });
        document.getElementById('rokLogoPickerModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'rokLogoPickerModal') e.target.hidden = true;
        });

        // Custom Logo File Upload -> Data URI so it persists everywhere including Vercel
        document.getElementById('customLogoFileInput')?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file || !activeLogoPickSeed) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                if (ev.target?.result) {
                    applyLogoToSeed(activeLogoPickSeed, String(ev.target.result));
                    document.getElementById('rokLogoPickerModal').hidden = true;
                }
            };
            reader.readAsDataURL(file);
        });

        // BroadcastChannel listener for instant cross-tab updates
        if (broadcastChannel) {
            broadcastChannel.onmessage = (event) => {
                if (event.data?.type === 'BRACKET_UPDATED' && event.data.state) {
                    applyRemoteBracketState(event.data.state, new Date().toISOString());
                }
            };
        }

        // Poll server every 2.5 seconds for real-time updates across devices/users
        setInterval(pollServerState, 2500);
    });
})();
</script>
