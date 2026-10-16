@extends('layouts.app')

@section('title', 'Tournament Bracket Generator | ROK Web')
@section('description', 'Platform pembuatan bagan turnamen esports, generator bracket single elimination, seeding tim, dan live match scoring.')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Tournament Engine' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <span class="section-badge">TOURNAMENT ENGINE</span>
            <h1 class="page-title">{{ $pageHeader?->title ?? 'BUILD. COMPETE. CONQUER.' }}</h1>
            <p class="page-subtitle-gold">
                Your Tournament. Your Bracket. Your Champion.
            </p>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Kelola turnamen esports komunitasmu dengan bagan pertandingan otomatis, seeding tim berimbang, dan sistem live score real-time.' }}
            </p>
        </div>
    </section>

    {{-- Live Tournament from Database --}}
    @if ($activeTournament)
        <section class="live-tournament-section">
            <div class="section-inner">
                <div class="tournament-hero-card">
                    <div class="tournament-info-col">
                        <span class="status-live-badge">&#x1F534; LIVE TOURNAMENT</span>
                        <h2 class="tournament-title">{{ $activeTournament->name }}</h2>
                        <p class="tournament-desc">{{ $activeTournament->description }}</p>
                        <div class="tournament-meta-list">
                            <span><strong>Penyelenggara:</strong> {{ $activeTournament->organizer_name }}</span>
                            <span><strong>Format:</strong> {{ ucwords(str_replace('_', ' ', $activeTournament->format)) }}</span>
                            <span><strong>Peserta:</strong> {{ $activeTournament->teams->count() }} Tim</span>
                        </div>
                    </div>
                </div>

                {{-- Bracket Tree from Database --}}
                <div class="live-bracket-viewer">
                    <div class="bracket-rounds-wrapper">
                        @php
                            $rounds = $activeTournament->matches->groupBy('round_name');
                        @endphp

                        @foreach ($rounds as $roundName => $matches)
                            <div class="bracket-round-column">
                                <div class="round-header">
                                    <span class="round-title">{{ strtoupper($roundName) }}</span>
                                </div>
                                <div class="round-matches-list">
                                    @foreach ($matches as $match)
                                        <div class="match-card {{ $match->status === 'live' ? 'is-live' : '' }}">
                                            <div class="match-team {{ $match->winner_id && $match->winner_id === $match->team1_id ? 'is-winner' : '' }}">
                                                <div class="team-meta">
                                                    @if ($match->team1 && $match->team1->logo_path)
                                                        <img src="{{ $match->team1->logo_url }}" alt="" class="team-logo-sm">
                                                    @endif
                                                    <span class="team-name">{{ $match->team1->team_name ?? 'TBD' }}</span>
                                                </div>
                                                <span class="team-score">{{ $match->score1 ?? '-' }}</span>
                                            </div>

                                            <div class="match-team {{ $match->winner_id && $match->winner_id === $match->team2_id ? 'is-winner' : '' }}">
                                                <div class="team-meta">
                                                    @if ($match->team2 && $match->team2->logo_path)
                                                        <img src="{{ $match->team2->logo_url }}" alt="" class="team-logo-sm">
                                                    @endif
                                                    <span class="team-name">{{ $match->team2->team_name ?? 'TBD' }}</span>
                                                </div>
                                                <span class="team-score">{{ $match->score2 ?? '-' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Interactive Tournament Generator Tool --}}
    <section class="generator-tool-section" id="generatorTool">
        <div class="section-inner">
            <header class="section-header">
                <span class="section-badge">INTERACTIVE BRACKET CREATOR</span>
                <h2 class="section-title">BUAT BAGAN TURNAMEN SENDIRI</h2>
                <p class="section-description">
                    Buat bagan dan jadwal pertandingan berdasarkan format kompetisi. Masukkan nama tim, lalu isi skor untuk memperbarui hasil pertandingan. Generator ini berjalan di browser; hasilnya belum disimpan ke database.
                </p>
            </header>

            <div class="generator-card">
                <div class="generator-inputs">
                    <div class="form-row-3">
                        <div class="form-field">
                            <label for="tourneyName" class="form-label">Nama Turnamen</label>
                            <input
                                type="text"
                                id="tourneyName"
                                class="form-input"
                                value="ROK Community Cup 2026"
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
                                    value="8"
                                    placeholder="Contoh: 5, 7, 8, 12, 16"
                                >
                                <span class="form-hint" id="teamCountHint" style="font-size: 11px; color: #94a3b8; margin-top: 4px; display: block;">
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

                    {{-- Dynamic Teams Input Container --}}
                    <div class="team-inputs-container">
                        <h4 class="inputs-subtitle">Daftar Tim &amp; Seeding:</h4>
                        <div class="team-inputs-grid" id="teamInputsGrid">
                            {{-- Populated by JavaScript --}}
                        </div>
                    </div>

                    <div class="generator-actions">
                        <button type="button" class="btn-rok btn-rok--gold btn-rok--lg" >
                            Generate Bagan Pertandingan &rarr;
                        </button>
                    </div>
                </div>

                {{-- Output Visualizer --}}
                <div class="generated-bracket-output" id="bracketOutputWrapper">
                    <div class="output-banner">
                        <h3 id="outputTourneyTitle">ROK Community Cup 2026</h3>
                        <span class="badge-mini" id="outputFormatBadge">Single Elimination</span>
                    </div>

                    <div class="generated-tree-deck" id="generatedTreeDeck">
                        {{-- Rendered Bracket Tree --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Membership Tiers Section for Organizers --}}
    <section class="membership-section" id="membership-tiers" style="padding: 60px 0;">
        <div class="section-inner">
            <header class="section-header" style="text-align: center; margin-bottom: 40px;">
                <span class="section-badge" style="letter-spacing: 2px;">ORGANIZER MEMBERSHIP TIERS</span>
                <h2 class="section-title">PAKET AKSES &amp; KEANGGOTAAN ORGANIZER</h2>
                <p class="section-description" style="max-width: 700px; margin: 0 auto; color: #94a3b8;">
                    Tingkatkan kualitas turnamen Anda dengan logo tim resmi, banner turnamen kustom, serta kurikulum taktik pro esports Ruler of Kings.
                </p>
            </header>

            <div class="membership-pricing-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
                @foreach ($membershipPlans as $plan)
                    <div class="pricing-card {{ $plan->is_featured ? 'pricing-card--featured' : '' }}" style="background: #111622; border: 1px solid {{ $plan->is_featured ? 'var(--rok-gold)' : 'var(--rok-surface-border)' }}; border-radius: var(--radius-md); padding: 32px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div class="pricing-header" style="margin-bottom: 16px;">
                                <span class="pricing-badge {{ $plan->is_featured ? 'pricing-badge--gold' : '' }}">{{ $plan->badge_title }}</span>
                                <h3 class="pricing-title" style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 6px 0;">{{ $plan->name }}</h3>
                                <p class="pricing-sub" style="font-size: 13px; color: #94a3b8;">{{ $plan->description }}</p>
                            </div>

                            <div style="margin: 16px 0; padding: 12px 0; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
                                <div style="font-size: 28px; font-weight: 900; color: #ffffff; font-family: var(--font-sub);">
                                    {{ $plan->formatted_price }}
                                    <span style="font-size: 13px; font-weight: 600; color: #94a3b8; font-family: var(--font-main);">/ {{ $plan->billing_cycle }}</span>
                                </div>
                            </div>

                            <ul class="pricing-features" style="list-style: none; padding: 0; margin: 0 0 24px 0; font-size: 13px; color: #cbd5e1; display: grid; gap: 10px;">
                                @if (is_array($plan->features))
                                    @foreach ($plan->features as $feature)
                                        <li style="display: flex; gap: 8px; align-items: flex-start;">
                                            <span style="color: #4ade80; font-weight: bold;">&#10003;</span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="pricing-footer">
                            <a href="{{ route('membership.payment', ['plan' => $plan->slug]) }}" class="btn-rok {{ $plan->is_featured ? 'btn-rok--gold' : 'btn-rok--outline' }} btn-rok--full" style="text-align: center; text-decoration: none;">
                                @if($plan->slug === 'challenger')
                                    Buka Fitur Bracket &rarr;
                                @elseif($plan->slug === 'immortal')
                                    Pilih Paket Immortal &rarr;
                                @else
                                    Pelajari Akses &rarr;
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Organizer Branding Highlight Box --}}
            <div class="branding-card-box" style="margin-top: 30px;">
                <div class="branding-content">
                    <span class="badge-mini">ORGANIZER MEMBERSHIP PRIVILEGES</span>
                    <h3>Ingin Menggunakan Logo Tim Resmi &amp; Custom Banner Turnamen?</h3>
                    <p>
                        Free Organizer dapat membuat bagan pertandingan tanpa logo. Untuk menyematkan logo tim resmi, banner turnamen kustom, dan branding turnamen eksklusif, aktifkan paket <strong>ROK Challenger</strong> atau <strong>ROK Immortal</strong>.
                    </p>
                </div>
                <div class="branding-action">
                    <a href="{{ route('membership.payment', ['plan' => 'challenger']) }}" class="btn-rok btn-rok--gold">
                        Buka Fitur Bracket &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
const defaultTeamNames = [
    'ROK Prime', 'Aether Esports', 'Valkyrie Horizon', 'Shadow Legion',
    'Apex Titans', 'Celestial Wolves', 'Kitsune Vanguard', 'Solar Eclipse',
    'Phoenix Force', 'Nexus Gaming', 'Vortex Dynasty', 'Titanium Squad',
    'Iron Blood', 'Alpha Strike', 'Omega Reign', 'Zenith Hunters',
    'Mythic Dawn', 'Cyber Dragons', 'Ghost Protocol', 'Thunder Fleet'
];
const formatLabels = {
    single: 'Single Elimination',
    double: 'Double Elimination',
    league: 'League System',
    swiss: 'Swiss Stage (Format M7 MLBB)'
};
let tournamentState = null;

function getTeamCount() {
    const format = document.getElementById('tourneyFormat').value;
    if (format === 'double') {
        return Number(document.getElementById('teamCountSelect').value);
    }
    const val = Number(document.getElementById('teamCountInput').value);
    return Math.max(3, Math.min(64, isNaN(val) ? 8 : val));
}

function syncTeamCountOptions(showWarning = false) {
    const format = document.getElementById('tourneyFormat').value;
    const inputWrapper = document.getElementById('teamCountInputWrapper');
    const select = document.getElementById('teamCountSelect');
    const warning = document.getElementById('doubleEliminationWarning');
    const hint = document.getElementById('teamCountHint');

    if (format === 'double') {
        inputWrapper.style.display = 'none';
        select.style.display = 'block';
        warning.hidden = false;
    } else {
        inputWrapper.style.display = 'block';
        select.style.display = 'none';
        warning.hidden = true;
        if (format === 'swiss') {
            document.getElementById('teamCountInput').value = '16';
            hint.textContent = 'Format Swiss Stage turnamen resmi M7 MLBB menggunakan 16 tim peserta.';
        } else if (format === 'league') {
            hint.textContent = 'Format League System (kompetisi poin klasemen). Rekomendasi 4–16 tim.';
        } else {
            hint.textContent = 'Bebas masukkan jumlah tim (3–64). Tim seed tertinggi otomatis lolos jika bukan pangkat 2.';
        }
    }
}

function renderTeamInputs() {
    const count = getTeamCount();
    const container = document.getElementById('teamInputsGrid');
    container.replaceChildren();
    for (let i = 0; i < count; i++) {
        const wrapper = document.createElement('div');
        wrapper.className = 'team-input-item';
        const seed = document.createElement('span');
        seed.className = 'seed-badge';
        seed.textContent = `Seed #${i + 1}`;
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-input team-name-input';
        input.id = `team_input_${i}`;
        input.value = defaultTeamNames[i] || `Team ${i + 1}`;
        input.placeholder = `Nama Tim ${i + 1}`;
        input.maxLength = 80;
        wrapper.append(seed, input);
        container.appendChild(wrapper);
    }
}

function newMatch(id, round, number, source1, source2, extra = {}) {
    return { id, round, number, source1, source2, score1: null, score2: null, winner: null, loser: null, ...extra };
}
function teamSource(team) { return { type: 'team', team }; }
function matchSource(match, outcome = 'winner') { return { type: outcome, matchId: match.id }; }
function resolveSource(source, matches) {
    if (!source) return null;
    if (source.type === 'team') return source.team;
    const parent = matches.find(m => m.id === source.matchId);
    if (!parent) return null;
    return source.type === 'loser' ? parent.loser : parent.winner;
}

function calculateMatch(match, matches) {
    const a = resolveSource(match.source1, matches);
    const b = resolveSource(match.source2, matches);
    match.team1 = a;
    match.team2 = b;
    match.winner = null;
    match.loser = null;

    if (a && !b && match.autoAdvance) {
        match.winner = a;
        return;
    }
    if (b && !a && match.autoAdvance) {
        match.winner = b;
        return;
    }
    if (a && b && Number.isInteger(match.score1) && Number.isInteger(match.score2) && match.score1 !== match.score2) {
        match.winner = match.score1 > match.score2 ? a : b;
        match.loser = match.score1 > match.score2 ? b : a;
    }
}

function getSeededPairs(size) {
    if (size <= 2) return [[1, 2]];
    let seeds = [1, 2];
    while (seeds.length < size) {
        const next = [];
        const sum = seeds.length * 2 + 1;
        for (const s of seeds) {
            next.push(s);
            next.push(sum - s);
        }
        seeds = next;
    }
    const pairs = [];
    for (let i = 0; i < size; i += 2) {
        pairs.push([seeds[i], seeds[i + 1]]);
    }
    return pairs;
}

function makeElimination(teams) {
    const N = teams.length;
    let P = 1;
    while (P < N) {
        P *= 2;
    }

    const pairs = getSeededPairs(P);
    const matches = [];
    const rounds = [];

    // Round 1
    const round1Matches = [];
    let matchIdx = 1;

    for (let i = 0; i < pairs.length; i++) {
        const [seed1, seed2] = pairs[i];
        const team1 = teams[seed1 - 1] || null;
        const team2 = (seed2 <= N) ? teams[seed2 - 1] : null;

        if (team1 && !team2) {
            // Top seed gets auto-advance ("Lolos Otomatis ke Babak Berikutnya")
            const m = newMatch(`R1M${matchIdx++}`, 1, matchIdx, teamSource(team1), null, {
                autoAdvance: true,
                autoAdvanceNote: 'Lolos Otomatis ke Babak Berikutnya'
            });
            matches.push(m);
            round1Matches.push(m);
        } else if (team1 && team2) {
            const m = newMatch(`R1M${matchIdx++}`, 1, matchIdx, teamSource(team1), teamSource(team2));
            matches.push(m);
            round1Matches.push(m);
        }
    }

    const roundTitle1 = eliminationRoundTitle(pairs.length, P, 1);
    rounds.push({ title: roundTitle1, matches: round1Matches });

    // Subsequent rounds
    let prevMatches = round1Matches;
    let roundNo = 2;
    let matchCount = prevMatches.length / 2;

    while (matchCount >= 1) {
        const currentMatches = [];
        for (let i = 0; i < matchCount; i++) {
            const m = newMatch(`R${roundNo}M${i + 1}`, roundNo, i + 1,
                matchSource(prevMatches[i * 2]),
                matchSource(prevMatches[i * 2 + 1])
            );
            matches.push(m);
            currentMatches.push(m);
        }
        rounds.push({
            title: eliminationRoundTitle(matchCount, P, roundNo),
            matches: currentMatches
        });
        prevMatches = currentMatches;
        matchCount /= 2;
        roundNo++;
    }

    return {
        matches,
        rounds,
        championSource: prevMatches[0] ? matchSource(prevMatches[0]) : null
    };
}

function eliminationRoundTitle(matchCount, totalSize, roundNo) {
    if (matchCount === 1) return 'Grand Final';
    if (matchCount === 2) return 'Semi Finals';
    if (matchCount === 4) return 'Quarter Finals';
    if (matchCount === 8) return 'Round of 16';
    return `Babak Penyisihan (Ronde ${roundNo})`;
}

function makeDoubleElimination(teams) {
    if (teams.length === 6) {
        const matches = [], rounds = [];
        const add = (id, round, number, a, b, title, extra = {}) => {
            const match = newMatch(id, round, number, a, b, extra);
            matches.push(match);
            let group = rounds.find(item => item.title === title);
            if (!group) { group = { title, matches: [] }; rounds.push(group); }
            group.matches.push(match);
            return match;
        };

        const deciding1 = add('M1', 1, 1, teamSource(teams[2]), teamSource(teams[5]), 'Babak Penentuan', { eliminateLoser: true });
        const deciding2 = add('M2', 1, 2, teamSource(teams[3]), teamSource(teams[4]), 'Babak Penentuan', { eliminateLoser: true });

        const upper1a = add('M3', 2, 1, teamSource(teams[0]), matchSource(deciding1), 'Upper Bracket — Round 1');
        const upper1b = add('M4', 2, 2, teamSource(teams[1]), matchSource(deciding2), 'Upper Bracket — Round 1');

        const lower1 = add('M5', 3, 1, matchSource(upper1a, 'loser'), matchSource(upper1b, 'loser'), 'Lower Bracket — Round 1');
        const upperFinal = add('M6', 4, 1, matchSource(upper1a), matchSource(upper1b), 'Upper Bracket Final');

        const lowerFinal = add('M7', 5, 1, matchSource(lower1), matchSource(upperFinal, 'loser'), 'Lower Bracket — Round 2');
        const grandFinal = add('M8', 6, 1, matchSource(upperFinal), matchSource(lowerFinal), 'Grand Final');
        return { matches, rounds, championSource: matchSource(grandFinal) };
    }

    const matches = [], rounds = [];
    let upperPrev = teams.map(teamSource), upperRound = 1, upperCount = teams.length / 2;
    const upperRounds = [];
    while (upperCount >= 1) {
        const current = [];
        for (let i = 0; i < upperCount; i++) {
            const m = newMatch(`U${upperRound}M${i+1}`, upperRound, i+1, upperPrev[i*2], upperPrev[i*2+1]);
            matches.push(m); current.push(m);
        }
        upperRounds.push(current); rounds.push({title: upperCount === 1 ? 'Upper Bracket Final' : `Upper Bracket — Round ${upperRound}`, matches: current});
        upperPrev = current.map(m => matchSource(m)); upperCount /= 2; upperRound++;
    }
    let lowerSources = upperRounds[0].map(m => matchSource(m, 'loser'));
    let lowerRoundNo = 1;
    let lowerMatches = [];
    for (let ur = 1; ur < upperRounds.length; ur++) {
        const incomingLosers = upperRounds[ur].map(m => matchSource(m, 'loser'));
        while (lowerSources.length > incomingLosers.length) {
            const consolidated = [];
            for (let i = 0; i < lowerSources.length; i += 2) {
                const m = newMatch(`L${lowerRoundNo}M${i/2+1}`, lowerRoundNo, i/2+1, lowerSources[i], lowerSources[i+1]);
                matches.push(m); consolidated.push(m);
            }
            rounds.push({title: `Lower Bracket — Round ${lowerRoundNo}`, matches: consolidated});
            lowerSources = consolidated.map(m => matchSource(m)); lowerRoundNo++;
        }
        const combined = [...lowerSources, ...incomingLosers];
        lowerMatches = [];
        for (let i = 0; i < combined.length; i += 2) {
            const m = newMatch(`L${lowerRoundNo}M${i/2+1}`, lowerRoundNo, i/2+1, combined[i], combined[i+1]);
            matches.push(m); lowerMatches.push(m);
        }
        rounds.push({title: `Lower Bracket — Round ${lowerRoundNo}`, matches: lowerMatches});
        lowerSources = lowerMatches.map(m => matchSource(m)); lowerRoundNo++;
    }
    while (lowerSources.length > 1) {
        const consolidated = [];
        for (let i = 0; i < lowerSources.length; i += 2) {
            const m = newMatch(`L${lowerRoundNo}M${i/2+1}`, lowerRoundNo, i/2+1, lowerSources[i], lowerSources[i+1]);
            matches.push(m); consolidated.push(m);
        }
        rounds.push({title: `Lower Bracket — Round ${lowerRoundNo}`, matches: consolidated});
        lowerSources = consolidated.map(m => matchSource(m)); lowerRoundNo++;
    }
    const grandFinal = newMatch('GF1', 99, 1, upperPrev[0], lowerSources[0], { grandFinal: true });
    matches.push(grandFinal); rounds.push({title: 'Grand Final', matches: [grandFinal]});
    return {matches, rounds, championSource: matchSource(grandFinal)};
}

function makeRoundRobin(teams, league = false) {
    const matches = [], rounds = [];
    const roster = [...teams]; if (roster.length % 2) roster.push(null);
    const n = roster.length, totalRounds = n - 1;
    let rotating = [...roster]; let id = 1;
    for (let r = 0; r < totalRounds; r++) {
        const list = [];
        for (let i = 0; i < n/2; i++) {
            const a = rotating[i], b = rotating[n-1-i];
            if (a && b) { const m = newMatch(`R${r+1}M${list.length+1}`, r+1, list.length+1, teamSource(a), teamSource(b)); matches.push(m); list.push(m); }
        }
        rounds.push({title: `League System — Pekan ${r+1}`, matches: list});
        rotating = [rotating[0], rotating[n-1], ...rotating.slice(1, n-1)]; id++;
    }
    return {matches, rounds, championSource: null};
}

// ========================================================
// M7 SWISS STAGE IMPLEMENTATION
// 16 Teams -> 5 Rounds -> 3 Wins Qualifies, 3 Losses Eliminates
// ========================================================

function getSwissTeamRecords(teams, allMatches) {
    const records = new Map();
    teams.forEach(t => {
        records.set(t.id, {
            team: t,
            wins: 0,
            losses: 0,
            playedOpponents: new Set(),
            pathHistory: [],
            status: 'active' // 'active', 'qualified', 'eliminated'
        });
    });

    allMatches.forEach(m => {
        if (!m.winner || !m.loser) return;
        const w = records.get(m.winner.id);
        const l = records.get(m.loser.id);
        if (w && l) {
            w.wins++;
            w.playedOpponents.add(l.team.id);
            w.pathHistory.push({ round: m.round, opponent: l.team.name, result: 'W', score: `${m.score1}-${m.score2}` });

            l.losses++;
            l.playedOpponents.add(w.team.id);
            l.pathHistory.push({ round: m.round, opponent: w.team.name, result: 'L', score: `${m.score1}-${m.score2}` });
        }
    });

    // Check status
    records.forEach(rec => {
        if (rec.wins >= 3) rec.status = 'qualified';
        else if (rec.losses >= 3) rec.status = 'eliminated';
        else rec.status = 'active';
    });

    return records;
}

function buildSwissRoundM7(teams, allMatches, roundNumber) {
    const records = getSwissTeamRecords(teams, allMatches);
    const matches = [];

    if (roundNumber === 1) {
        // Round 1: All 16 teams are 0-0
        // Standard seeding draw: Seed 1 vs Seed 9, 2 vs 10, etc. (or 1 vs 16, 2 vs 15)
        const sorted = [...teams].sort((a, b) => a.seed - b.seed);
        const half = sorted.length / 2;
        for (let i = 0; i < half; i++) {
            const teamA = sorted[i];
            const teamB = sorted[i + half];
            matches.push(newMatch(`SW1M${i + 1}`, 1, i + 1, teamSource(teamA), teamSource(teamB), {
                poolLabel: 'POOL 0-0 (RONDE 1)'
            }));
        }
        return {
            title: `Swiss Stage — Ronde 1 (Rekor 0-0)`,
            roundNumber: 1,
            matches
        };
    }

    // Active teams that have not qualified (< 3 wins) and not eliminated (< 3 losses)
    const activeTeams = [...records.values()].filter(r => r.status === 'active');

    // Group teams by [wins-losses]
    const groups = new Map();
    activeTeams.forEach(r => {
        const key = `${r.wins}-${r.losses}`;
        if (!groups.has(key)) groups.set(key, []);
        groups.get(key).push(r);
    });

    let matchIdx = 1;

    // Sort group keys by wins descending
    const sortedKeys = [...groups.keys()].sort((a, b) => {
        const [wA, lA] = a.split('-').map(Number);
        const [wB, lB] = b.split('-').map(Number);
        return wB - wA || lA - lB;
    });

    sortedKeys.forEach(key => {
        const pool = groups.get(key);
        // Pool pairing within record group, avoiding rematch
        const poolTeams = pool.map(p => p.team).sort((a, b) => a.seed - b.seed);
        const unassigned = [...poolTeams];

        let poolTitle = `POOL ${key}`;
        if (key === '2-0' || key === '2-1') poolTitle += ' (MATCH KUALIFIKASI)';
        else if (key === '0-2' || key === '1-2') poolTitle += ' (MATCH ELIMINASI)';
        else if (key === '2-2') poolTitle += ' (MATCH PENENTUAN FINAL)';

        while (unassigned.length >= 2) {
            const teamA = unassigned.shift();
            const recA = records.get(teamA.id);

            let opponentIdx = -1;
            for (let i = 0; i < unassigned.length; i++) {
                const teamB = unassigned[i];
                if (!recA.playedOpponents.has(teamB.id)) {
                    opponentIdx = i;
                    break;
                }
            }
            if (opponentIdx === -1) opponentIdx = 0;

            const teamB = unassigned.splice(opponentIdx, 1)[0];
            matches.push(newMatch(`SW${roundNumber}M${matchIdx++}`, roundNumber, matchIdx, teamSource(teamA), teamSource(teamB), {
                poolLabel: poolTitle
            }));
        }
    });

    return {
        title: `Swiss Stage — Ronde ${roundNumber}`,
        roundNumber,
        matches
    };
}

function advanceSwiss(state) {
    state.matches.forEach(m => calculateMatch(m, state.matches));
    const current = state.rounds[state.rounds.length - 1];

    if (current && current.matches.length && current.matches.every(m => m.winner && m.loser) && state.rounds.length < 5) {
        const nextRoundNo = state.rounds.length + 1;
        const next = buildSwissRoundM7(state.teams, state.matches, nextRoundNo);
        if (next.matches.length > 0) {
            state.rounds.push(next);
            state.matches.push(...next.matches);
        }
    }
    renderTournament();
}

function renderSwissProgress(deck, state) {
    const records = getSwissTeamRecords(state.teams, state.matches);

    const qualifiedTeams = [...records.values()].filter(r => r.status === 'qualified');
    const eliminatedTeams = [...records.values()].filter(r => r.status === 'eliminated');
    const activeTeams = [...records.values()].filter(r => r.status === 'active')
        .sort((a, b) => b.wins - a.wins || a.losses - b.losses || a.team.seed - b.team.seed);

    const overview = document.createElement('div');
    overview.className = 'swiss-overview';
    overview.style.width = '340px';
    overview.style.flex = '0 0 340px';

    overview.innerHTML = `
        <div style="background: #111622; border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 20px; margin-bottom: 20px;">
            <span class="badge-mini" style="font-size: 10px; color: var(--rok-gold);">SISTEM RESMI M7 SWISS STAGE</span>
            <h4 style="font-size: 15px; font-weight: 800; color: #ffffff; margin: 6px 0 10px 0;">STATUS &amp; JALUR KOMPETISI</h4>
            <p style="font-size: 12px; color: #94a3b8; line-height: 1.5; margin: 0;">
                Target: <strong>3 Kemenangan</strong> untuk LOLOS ke Knockout Stage. <strong>3 Kekalahan</strong> dinyatakan TERELIMINASI. Tim dipasangkan dalam Pool rekor yang sama.
            </p>
        </div>

        {{-- Lolos ke Knockout --}}
        <div style="background: #111622; border: 1px solid rgba(34,197,94,0.4); border-radius: var(--radius-md); padding: 18px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="color: #4ade80; font-size: 13px; font-weight: 800; text-transform: uppercase;">&check; LOLOS PLAYOFF (${qualifiedTeams.length}/8)</strong>
                <span class="badge" style="background: rgba(34,197,94,0.15); color: #4ade80; font-size: 10px; padding: 2px 6px; border-radius: 4px;">3 Menang</span>
            </div>
            ${qualifiedTeams.length === 0 ? '<p style="font-size: 12px; color: #64748b; margin: 0;">Belum ada tim yang mencapai 3 kemenangan.</p>' : ''}
            <div style="display: grid; gap: 6px;">
                ${qualifiedTeams.map(q => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 10px; background: rgba(34,197,94,0.08); border-radius: 4px; font-size: 12px;">
                        <span style="color: #ffffff; font-weight: 700;">${q.team.name}</span>
                        <strong style="color: #4ade80; font-family: monospace;">${q.wins}-${q.losses}</strong>
                    </div>
                `).join('')}
            </div>
        </div>

        {{-- Tereliminasi --}}
        <div style="background: #111622; border: 1px solid rgba(239,68,68,0.4); border-radius: var(--radius-md); padding: 18px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="color: #f87171; font-size: 13px; font-weight: 800; text-transform: uppercase;">&times; TERELIMINASI (${eliminatedTeams.length}/8)</strong>
                <span class="badge" style="background: rgba(239,68,68,0.15); color: #f87171; font-size: 10px; padding: 2px 6px; border-radius: 4px;">3 Kalah</span>
            </div>
            ${eliminatedTeams.length === 0 ? '<p style="font-size: 12px; color: #64748b; margin: 0;">Belum ada tim yang tereliminasi.</p>' : ''}
            <div style="display: grid; gap: 6px;">
                ${eliminatedTeams.map(e => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 10px; background: rgba(239,68,68,0.08); border-radius: 4px; font-size: 12px;">
                        <span style="color: #94a3b8;">${e.team.name}</span>
                        <strong style="color: #f87171; font-family: monospace;">${e.wins}-${e.losses}</strong>
                    </div>
                `).join('')}
            </div>
        </div>

        {{-- Masih Bertanding --}}
        <div style="background: #111622; border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 18px;">
            <strong style="color: #cbd5e1; font-size: 13px; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 8px;">AKTIF BERKOMPETISI (${activeTeams.length})</strong>
            <div style="display: grid; gap: 4px; max-height: 240px; overflow-y: auto;">
                ${activeTeams.map(a => `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 4px 8px; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,0.04);">
                        <span style="color: #e2e8f0;">${a.team.name}</span>
                        <span style="color: var(--rok-gold); font-family: monospace; font-weight: 700;">${a.wins}W - ${a.losses}L</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    deck.prepend(overview);
}

function buildTournament() {
    const title = document.getElementById('tourneyName').value.trim() || 'Turnamen ROK';
    const count = getTeamCount();
    const format = document.getElementById('tourneyFormat').value;

    if (format === 'double' && count > 6) {
        document.getElementById('doubleEliminationWarning').hidden = false;
        alert('Double Elimination di ROK Web dibatasi maksimal 6 tim.');
        return;
    }

    const teams = Array.from({ length: count }, (_, i) => ({
        id: i + 1,
        name: (document.getElementById(`team_input_${i}`)?.value || '').trim() || `Team ${i + 1}`,
        seed: i + 1
    }));

    if (new Set(teams.map(t => t.name.toLowerCase())).size !== teams.length) {
        alert('Nama tim harus unik agar bagan dan alur hasil pertandingan tidak membingungkan.');
        return;
    }

    let engine;
    if (format === 'single') {
        engine = makeElimination(teams);
    } else if (format === 'double') {
        engine = makeDoubleElimination(teams);
    } else if (format === 'league') {
        engine = makeRoundRobin(teams, true);
    } else {
        // Swiss Stage
        const round1 = buildSwissRoundM7(teams, [], 1);
        engine = {
            matches: round1.matches,
            rounds: [round1],
            championSource: null
        };
    }

    tournamentState = { title, format, teams, ...engine };
    renderTournament();
}

function renderTournament() {
    const state = tournamentState;
    if (!state) return;

    state.matches.forEach(m => calculateMatch(m, state.matches));
    document.getElementById('outputTourneyTitle').textContent = state.title;
    document.getElementById('outputFormatBadge').textContent = formatLabels[state.format];
    const deck = document.getElementById('generatedTreeDeck');
    deck.replaceChildren();

    if (state.format === 'league') {
        renderStandings(deck, state);
    }

    if (state.format === 'double') {
        renderDoubleBracket(deck, state);
    } else {
        state.rounds.forEach((round, roundIndex) => {
            const col = document.createElement('div');
            col.className = 'gen-round-col';
            col.dataset.roundTitle = round.title;

            const heading = document.createElement('span');
            heading.className = 'gen-round-title';
            heading.textContent = round.title;
            col.appendChild(heading);

            const list = document.createElement('div');
            list.className = 'gen-matches-list';

            round.matches.forEach(match => list.appendChild(renderMatch(match, state)));
            if (!round.matches.length) {
                const empty = document.createElement('p');
                empty.textContent = 'Tidak ada pertandingan pada ronde ini.';
                list.appendChild(empty);
            }
            col.appendChild(list);
            deck.appendChild(col);
        });
    }

    if (state.format === 'swiss') {
        renderSwissProgress(deck, state);
    }

    deck.scrollLeft = 0;

    if (state.format === 'single' || state.format === 'double') {
        const champion = state.championSource ? resolveSource(state.championSource, state.matches) : null;
        const col = document.createElement('div');
        col.className = 'gen-round-col gen-round-col--champ';
        const heading = document.createElement('span');
        heading.className = 'gen-round-title text-gold';
        heading.textContent = 'CHAMPION';
        const box = document.createElement('div');
        box.className = 'champ-display-box';
        const icon = document.createElement('span');
        icon.className = 'champ-icon';
        icon.textContent = '🏆';
        const name = document.createElement('strong');
        name.className = 'champ-crown-name';
        name.textContent = champion ? champion.name : 'Belum ditentukan';
        const sub = document.createElement('p');
        sub.className = 'champ-sub';
        sub.textContent = champion ? 'Tournament Champion' : 'Isi skor pertandingan';
        box.append(icon, name, sub);
        col.append(heading, box);
        deck.appendChild(col);
    }
}

function renderDoubleBracket(deck, state) {
    const decidingRounds = state.rounds.filter(round => round.title === 'Babak Penentuan');
    const upperRounds = state.rounds.filter(round => round.title.startsWith('Upper Bracket'));
    const lowerRounds = state.rounds.filter(round => round.title.startsWith('Lower Bracket'));
    const grandFinalRounds = state.rounds.filter(round => round.title === 'Grand Final');

    const layout = document.createElement('div');
    layout.className = 'gen-double-layout';

    const makeColumns = (rounds, trackClass) => {
        const columns = document.createElement('div');
        columns.className = `gen-double-columns ${trackClass}`;
        rounds.forEach(round => {
            const col = document.createElement('div');
            col.className = 'gen-round-col gen-round-col--double';
            col.dataset.roundTitle = round.title;
            const heading = document.createElement('span');
            heading.className = 'gen-round-title';
            heading.textContent = round.title;
            col.appendChild(heading);
            const list = document.createElement('div');
            list.className = 'gen-matches-list';
            round.matches.forEach(match => list.appendChild(renderMatch(match, state)));
            col.appendChild(list);
            columns.appendChild(col);
        });
        return columns;
    };

    if (decidingRounds.length) {
        layout.classList.add('gen-double-layout--with-deciding');
        const deciding = document.createElement('section');
        deciding.className = 'gen-double-deciding';
        const heading = document.createElement('div');
        heading.className = 'gen-double-track-label';
        heading.textContent = 'BABAK PENENTUAN';
        deciding.appendChild(heading);
        const note = document.createElement('p');
        note.className = 'gen-double-deciding-note';
        note.textContent = 'Pemenang lanjut ke Upper Bracket; peserta yang kalah langsung tereliminasi.';
        deciding.appendChild(note);
        deciding.appendChild(makeColumns(decidingRounds, 'gen-double-deciding-columns'));
        layout.appendChild(deciding);
    }

    const upper = document.createElement('section');
    upper.className = 'gen-double-track gen-double-track--upper';
    const upperLabel = document.createElement('div');
    upperLabel.className = 'gen-double-track-label';
    upperLabel.textContent = 'UPPER BRACKET — JALUR PEMENANG';
    upper.appendChild(upperLabel);
    upper.appendChild(makeColumns(upperRounds, 'gen-double-upper-columns'));
    layout.appendChild(upper);

    const lower = document.createElement('section');
    lower.className = 'gen-double-track gen-double-track--lower';
    const lowerLabel = document.createElement('div');
    lowerLabel.className = 'gen-double-track-label';
    lowerLabel.textContent = 'LOWER BRACKET — JALUR BERTAHAN';
    lower.appendChild(lowerLabel);

    let displayedLowerRounds = lowerRounds;
    if (lowerRounds.length > 2) {
        displayedLowerRounds = [
            { title: 'Lower Bracket — Round 1', matches: lowerRounds[0].matches },
            {
                title: 'Lower Bracket — Round 2 & Final',
                matches: lowerRounds.slice(1).flatMap(round => round.matches)
            }
        ];
    }
    lower.appendChild(makeColumns(displayedLowerRounds, 'gen-double-lower-columns'));
    layout.appendChild(lower);

    if (grandFinalRounds.length) {
        const finalTrack = document.createElement('section');
        finalTrack.className = 'gen-double-grand-final';
        const heading = document.createElement('span');
        heading.className = 'gen-round-title';
        heading.textContent = 'GRAND FINAL';
        finalTrack.appendChild(heading);
        const list = document.createElement('div');
        list.className = 'gen-matches-list';
        grandFinalRounds.forEach(round => round.matches.forEach(match => list.appendChild(renderMatch(match, state))));
        finalTrack.appendChild(list);
        layout.appendChild(finalTrack);
    }
    deck.appendChild(layout);
}

function renderMatch(match, state) {
    const card = document.createElement('div');
    card.className = 'gen-match-box';

    if (match.poolLabel) {
        const poolBadge = document.createElement('div');
        poolBadge.className = 'pool-tag-badge';
        poolBadge.style.fontSize = '10px';
        poolBadge.style.fontWeight = '800';
        poolBadge.style.padding = '2px 6px';
        poolBadge.style.marginBottom = '6px';
        poolBadge.style.borderRadius = '3px';
        poolBadge.style.letterSpacing = '0.5px';
        if (match.poolLabel.includes('KUALIFIKASI')) {
            poolBadge.style.background = 'rgba(34,197,94,0.15)';
            poolBadge.style.color = '#4ade80';
        } else if (match.poolLabel.includes('ELIMINASI')) {
            poolBadge.style.background = 'rgba(239,68,68,0.15)';
            poolBadge.style.color = '#f87171';
        } else {
            poolBadge.style.background = 'rgba(148,163,184,0.15)';
            poolBadge.style.color = '#cbd5e1';
        }
        poolBadge.textContent = match.poolLabel;
        card.appendChild(poolBadge);
    }

    const a = resolveSource(match.source1, state.matches);
    const b = resolveSource(match.source2, state.matches);

    // If auto-advance (BYE slot in Single Elimination)
    if (match.autoAdvance) {
        const row = document.createElement('div');
        row.className = 'gen-team-row is-winner';
        const name = document.createElement('span');
        name.className = 'gen-team-name';
        name.textContent = a ? a.name : 'TBD';

        const advBadge = document.createElement('span');
        advBadge.className = 'badge-auto-advance';
        advBadge.style.background = 'rgba(34, 197, 94, 0.15)';
        advBadge.style.color = '#4ade80';
        advBadge.style.border = '1px solid #22c55e';
        advBadge.style.fontSize = '10px';
        advBadge.style.fontWeight = '800';
        advBadge.style.padding = '3px 8px';
        advBadge.style.borderRadius = '4px';
        advBadge.style.whiteSpace = 'nowrap';
        advBadge.textContent = 'Lolos Otomatis ke Babak Berikutnya';

        row.append(name, advBadge);
        card.appendChild(row);
        return card;
    }

    [a, b].forEach((team, i) => {
        const row = document.createElement('div');
        row.className = 'gen-team-row';
        const name = document.createElement('span');
        name.className = 'gen-team-name';
        name.textContent = team ? team.name : 'TBD';
        const input = document.createElement('input');
        input.type = 'number';
        input.min = '0';
        input.max = '999';
        input.step = '1';
        input.className = 'gen-score-input';
        input.setAttribute('aria-label', `Skor ${team?.name || 'TBD'}`);
        input.value = match[i === 0 ? 'score1' : 'score2'] ?? '';
        input.disabled = !(a && b);
        input.placeholder = '–';

        input.addEventListener('change', () => {
            if (state.format === 'swiss' && match.round < state.rounds.length) {
                state.rounds = state.rounds.slice(0, match.round);
                state.matches = state.matches.filter(item => item.round <= match.round);
            }
            const previousWinner = match.winner?.id ?? null;
            const key = i === 0 ? 'score1' : 'score2';
            const other = i === 0 ? 'score2' : 'score1';
            const value = input.value.trim();
            match[key] = value === '' ? null : Number.parseInt(value, 10);
            if (match[key] !== null && (!Number.isInteger(match[key]) || match[key] < 0 || match[key] > 999)) match[key] = null;
            if (match[key] !== null && match[other] !== null && match[key] === match[other]) {
                alert('Skor tidak boleh seri untuk pertandingan yang membutuhkan pemenang.');
                match[key] = null;
            }
            calculateMatch(match, state.matches);
            if (previousWinner !== (match.winner?.id ?? null)) clearDependentScores(state, match.id);
            if (state.format === 'swiss') advanceSwiss(state);
            else renderTournament();
        });

        if (team && match.winner?.id === team.id) row.classList.add('is-winner');
        row.append(name, input);
        card.appendChild(row);
    });

    if (match.winner) {
        const result = document.createElement('small');
        result.textContent = `Pemenang: ${match.winner.name}`;
        card.appendChild(result);
        if (state.format === 'double' && match.eliminateLoser && match.loser) {
            const eliminated = document.createElement('small');
            eliminated.className = 'bracket-eliminated-note';
            eliminated.textContent = `Tereliminasi: ${match.loser.name}`;
            card.appendChild(eliminated);
        }
    }
    return card;
}

function clearDependentScores(state, parentMatchId) {
    const cleared = new Set([parentMatchId]);
    let changed = true;
    while (changed) {
        changed = false;
        state.matches.forEach(match => {
            const dependsOnCleared = [match.source1, match.source2].some(source => source && source.type !== 'team' && cleared.has(source.matchId));
            if (dependsOnCleared && !cleared.has(match.id)) {
                match.score1 = null; match.score2 = null; match.winner = null; match.loser = null;
                cleared.add(match.id); changed = true;
            }
        });
    }
}

function renderStandings(deck, state) {
    const table = document.createElement('div');
    table.className = 'league-standings';
    const heading = document.createElement('h3');
    heading.textContent = 'Klasemen Liga (League System)';
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
    ['#', 'Tim', 'P', 'W', 'L', 'Skor +/-', 'Pts'].forEach(t => { const th = document.createElement('th'); th.textContent = t; hr.appendChild(th); });
    thead.appendChild(hr); tableEl.appendChild(thead);
    const tbody = document.createElement('tbody');
    [...records.values()].sort((a, b) => b.points - a.points || b.wins - a.wins || (b.scored - b.conceded) - (a.scored - a.conceded) || a.team.seed - b.team.seed).forEach((r, i) => {
        const tr = document.createElement('tr');
        [i + 1, r.team.name, r.played, r.wins, r.losses, `${r.scored - r.conceded}`, r.points].forEach(v => { const td = document.createElement('td'); td.textContent = String(v); tr.appendChild(td); });
        tbody.appendChild(tr);
    });
    tableEl.appendChild(tbody);
    table.appendChild(tableEl);
    deck.appendChild(table);
}

document.addEventListener('DOMContentLoaded', () => {
    syncTeamCountOptions();
    renderTeamInputs();

    document.getElementById('tourneyFormat').addEventListener('change', () => {
        syncTeamCountOptions();
        renderTeamInputs();
        buildTournament();
    });

    document.getElementById('teamCountInput').addEventListener('input', () => {
        renderTeamInputs();
        buildTournament();
    });

    document.getElementById('teamCountSelect').addEventListener('change', () => {
        renderTeamInputs();
        buildTournament();
    });

    document.querySelector('.generator-actions button')?.addEventListener('click', buildTournament);
    buildTournament();
});
</script>
@endsection
