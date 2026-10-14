@extends('layouts.app')

@section('title', 'Tournament Bracket Generator | ROK Web')
@section('description', 'Platform pembuatan bagan turnamen esports, generator bracket single elimination, seeding tim, dan live match scoring.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge">TOURNAMENT ENGINE</span>
            <h1 class="page-title">BUILD. COMPETE. CONQUER.</h1>
            <p class="page-subtitle-gold">
                Your Tournament. Your Bracket. Your Champion.
            </p>
            <p class="page-subtitle">
                Kelola turnamen esports komunitasmu dengan bagan pertandingan otomatis, seeding tim berimbang, dan sistem live score real-time.
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
                                <option value="round_robin">Round Robin</option>
                                <option value="league">League System</option>
                                <option value="swiss">Swiss Stage</option>
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="teamCount" class="form-label">Jumlah Tim Peserta</label>
                            <select id="teamCount" class="form-select">
                                <option value="4">4 Tim</option>
                                <option value="8" selected>8 Tim</option>
                                <option value="16">16 Tim</option>
                            </select>
                            <p class="bracket-format-warning" id="doubleEliminationWarning" role="alert" aria-live="polite" hidden>
                                Double Elimination mengikuti format turnamen Mobile Legends di ROK Web dan dibatasi maksimal 6 tim. Pilih 4 atau 6 tim untuk format ini.
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

    {{-- Membership Organizer Info --}}
    <section class="organizer-membership-banner">
        <div class="section-inner">
            <div class="branding-card-box">
                <div class="branding-content">
                    <span class="badge-mini">ORGANIZER MEMBERSHIP</span>
                    <h3>Ingin Menggunakan Logo Tim Resmi &amp; Custom Banner?</h3>
                    <p>
                        Free Organizer dapat membuat bagan pertandingan tanpa logo. Untuk menyematkan logo tim resmi, banner turnamen kustom, dan branding turnamen eksklusif, aktifkan paket <strong>ROK Challenger</strong> atau <strong>ROK Immortal</strong>.
                    </p>
                </div>
                <div class="branding-action">
                    <a href="{{ url('/learning-development') }}#membership" class="btn-rok btn-rok--outline">
                        Lihat Ketentuan Membership &rarr;
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
    'Iron Blood', 'Alpha Strike', 'Omega Reign', 'Zenith Hunters'
];
const formatLabels = {
    single: 'Single Elimination', double: 'Double Elimination',
    round_robin: 'Round Robin', league: 'League System', swiss: 'Swiss Stage'
};
let tournamentState = null;

function renderTeamInputs() {
    const count = Number(document.getElementById('teamCount').value);
    const container = document.getElementById('teamInputsGrid');
    container.replaceChildren();
    for (let i = 0; i < count; i++) {
        const wrapper = document.createElement('div');
        wrapper.className = 'team-input-item';
        const seed = document.createElement('span');
        seed.className = 'seed-badge';
        seed.textContent = `Seed #${i + 1}`;
        const input = document.createElement('input');
        input.type = 'text'; input.className = 'form-input team-name-input';
        input.id = `team_input_${i}`; input.value = defaultTeamNames[i] || `Team ${i + 1}`;
        input.placeholder = `Nama Tim ${i + 1}`; input.maxLength = 80;
        wrapper.append(seed, input); container.appendChild(wrapper);
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
    const a = resolveSource(match.source1, matches), b = resolveSource(match.source2, matches);
    match.team1 = a; match.team2 = b; match.winner = null; match.loser = null;
    if (a && !b && match.autoAdvance) { match.winner = a; return; }
    if (b && !a && match.autoAdvance) { match.winner = b; return; }
    if (a && b && Number.isInteger(match.score1) && Number.isInteger(match.score2) && match.score1 !== match.score2) {
        match.winner = match.score1 > match.score2 ? a : b;
        match.loser = match.score1 > match.score2 ? b : a;
    }
}
function makeElimination(teams) {
    const matches = [], rounds = [];
    let previous = teams.map(teamSource), roundNo = 1, matchCount = previous.length / 2;
    const roundTitles = [];
    while (matchCount >= 1) {
        const current = [];
        for (let i = 0; i < matchCount; i++) {
            const m = newMatch(`S${roundNo}M${i+1}`, roundNo, i+1,
                previous[i*2], previous[i*2+1]);
            matches.push(m); current.push(m);
        }
        rounds.push({ title: eliminationRoundTitle(matchCount, teams.length, roundNo), matches: current });
        previous = current.map(m => matchSource(m)); matchCount /= 2; roundNo++;
    }
    return { matches, rounds, championSource: previous[0] };
}
function eliminationRoundTitle(matchCount, totalTeams, roundNo) {
    if (matchCount === 1) return totalTeams === 2 ? 'Grand Final' : 'Final';
    if (matchCount === 2) return 'Semi Finals';
    if (matchCount === 4) return 'Quarter Finals';
    return `Round ${roundNo} (${matchCount} matches)`;
}
function makeDoubleElimination(teams) {
    // MPL-inspired 6-team structure:
    // Deciding round losers are eliminated immediately (they do NOT enter Lower Bracket).
    // The two top seeds receive byes into the first Upper Bracket round.
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

        // M1-M2: only four teams play. Losers are eliminated immediately.
        const deciding1 = add('M1', 1, 1, teamSource(teams[2]), teamSource(teams[5]), 'Babak Penentuan', { eliminateLoser: true });
        const deciding2 = add('M2', 1, 2, teamSource(teams[3]), teamSource(teams[4]), 'Babak Penentuan', { eliminateLoser: true });

        // M3-M4: top two seeds enter here and face the deciding-round winners.
        const upper1a = add('M3', 2, 1, teamSource(teams[0]), matchSource(deciding1), 'Upper Bracket — Round 1');
        const upper1b = add('M4', 2, 2, teamSource(teams[1]), matchSource(deciding2), 'Upper Bracket — Round 1');

        // M5: losers of M3 and M4 meet. The loser is eliminated.
        const lower1 = add('M5', 3, 1, matchSource(upper1a, 'loser'), matchSource(upper1b, 'loser'), 'Lower Bracket — Round 1');

        // M6: Upper Final. Its winner goes to Grand Final; loser drops to Lower Final.
        const upperFinal = add('M6', 4, 1, matchSource(upper1a), matchSource(upper1b), 'Upper Bracket Final');

        // M7: winner of M5 faces loser of M6. Loser is eliminated.
        const lowerFinal = add('M7', 5, 1, matchSource(lower1), matchSource(upperFinal, 'loser'), 'Lower Bracket — Round 2');

        // M8: Grand Final.
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
    // Consolidate remaining lower-bracket survivors until one remains.
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
        rounds.push({title: `${league ? 'League' : 'Round Robin'} — Round ${r+1}`, matches: list});
        rotating = [rotating[0], rotating[n-1], ...rotating.slice(1, n-1)]; id++;
    }
    return {matches, rounds, championSource: null};
}
function buildSwissRound(teams, previousRounds, roundNumber) {
    const matches = [];
    const records = new Map(teams.map(t => [t.id, {team:t, wins:(t.byeRounds || []).filter(r => r <= previousRounds.length).length, losses:0, points:(t.byeRounds || []).filter(r => r <= previousRounds.length).length, played:new Set()}]));
    previousRounds.flatMap(r => r.matches).forEach(m => {
        if (!m.winner || !m.loser) return;
        const w = records.get(m.winner.id), l = records.get(m.loser.id);
        if (w) { w.wins++; w.points += 1; w.played.add(m.loser.id); }
        if (l) { l.losses++; l.played.add(m.winner.id); }
    });
    const eligible = [...records.values()].filter(x => x.wins < 3 && x.losses < 3)
        .sort((a,b) => b.points-a.points || b.wins-a.wins || a.team.seed-b.team.seed);
    const paired = new Set(); let matchNo = 1;
    for (let i=0; i<eligible.length; i++) {
        const a = eligible[i]; if (paired.has(a.team.id)) continue;
        let opponent = null;
        for (let j=i+1; j<eligible.length; j++) {
            const b = eligible[j];
            if (!paired.has(b.team.id) && !a.played.has(b.team.id)) { opponent = b; break; }
        }
        if (!opponent) for (let j=i+1; j<eligible.length; j++) { if (!paired.has(eligible[j].team.id)) { opponent=eligible[j]; break; } }
        if (opponent) {
            const m = newMatch(`SW${roundNumber}M${matchNo}`, roundNumber, matchNo++, teamSource(a.team), teamSource(opponent.team));
            matches.push(m); paired.add(a.team.id); paired.add(opponent.team.id);
        } else {
            // A bye counts as a win; represented separately and not as a fake match.
            a.wins++; a.points++;
            a.team.byeRounds = [...(a.team.byeRounds || []), roundNumber];
            paired.add(a.team.id);
        }
    }
    return {title:`Swiss Stage — Round ${roundNumber}`, matches};
}
function syncTeamCountOptions(showWarning = false) {
    const format = document.getElementById('tourneyFormat').value;
    const select = document.getElementById('teamCount');
    const warning = document.getElementById('doubleEliminationWarning');
    const previous = Number(select.value);
    const isDouble = format === 'double';
    const allowed = isDouble ? [4, 6] : [4, 8, 16];
    select.replaceChildren();
    allowed.forEach(count => {
        const option = document.createElement('option');
        option.value = String(count);
        option.textContent = isDouble ? `${count} Tim${count === 6 ? ' (2 Seed Teratas Mendapat Bye)' : ''}` : `${count} Tim`;
        select.appendChild(option);
    });
    if (allowed.includes(previous)) {
        select.value = String(previous);
    } else {
        select.value = String(isDouble ? 6 : 8);
        if (showWarning && isDouble && previous > 6) {
            warning.hidden = false;
        }
    }
    if (!isDouble) warning.hidden = true;
}

function buildTournament() {
    const title = document.getElementById('tourneyName').value.trim() || 'Turnamen ROK';
    const count = Number(document.getElementById('teamCount').value);
    const format = document.getElementById('tourneyFormat').value;
    if (format === 'double' && count > 6) {
        document.getElementById('doubleEliminationWarning').hidden = false;
        alert('Double Elimination di ROK Web dibatasi maksimal 6 tim.');
        return;
    }
    const teams = Array.from({length:count}, (_,i) => ({id:i+1, name:(document.getElementById(`team_input_${i}`)?.value || '').trim() || `Team ${i+1}`, seed:i+1, byeRounds:[]}));
    if (new Set(teams.map(t=>t.name.toLocaleLowerCase())).size !== teams.length) {
        alert('Nama tim harus unik agar hasil pertandingan tidak membingungkan.'); return;
    }
    let engine;
    if (format === 'single') engine = makeElimination(teams);
    else if (format === 'double') engine = makeDoubleElimination(teams);
    else if (format === 'round_robin') engine = makeRoundRobin(teams, false);
    else if (format === 'league') engine = makeRoundRobin(teams, true);
    else {
        const rounds = [buildSwissRound(teams, [], 1)];
        engine = {matches:rounds[0].matches, rounds, championSource:null, swissRounds:rounds};
    }
    tournamentState = {title, format, teams, ...engine};
    renderTournament();
}
function renderTournament() {
    const state = tournamentState; if (!state) return;
    state.matches.forEach(m => calculateMatch(m, state.matches));
    document.getElementById('outputTourneyTitle').textContent = state.title;
    document.getElementById('outputFormatBadge').textContent = formatLabels[state.format];
    const deck = document.getElementById('generatedTreeDeck'); deck.replaceChildren();
    if (state.format === 'round_robin' || state.format === 'league') renderStandings(deck, state);
    if (state.format === 'double') {
        renderDoubleBracket(deck, state);
    } else {
        state.rounds.forEach((round, roundIndex) => {
            const col = document.createElement('div');
            col.className = 'gen-round-col';
            col.dataset.roundTitle = round.title;
            const heading = document.createElement('span'); heading.className = 'gen-round-title'; heading.textContent = round.title; col.appendChild(heading);
            const list = document.createElement('div'); list.className = 'gen-matches-list';
            round.matches.forEach(match => list.appendChild(renderMatch(match, state)));
            if (!round.matches.length) { const empty=document.createElement('p'); empty.textContent='Tidak ada pertandingan pada ronde ini.'; list.appendChild(empty); }
            col.appendChild(list); deck.appendChild(col);
        });
    }
    if (state.format === 'swiss') renderSwissProgress(deck, state);
    // Always show the start of a newly generated bracket, not a stale horizontal scroll position.
    deck.scrollLeft = 0;
    if (state.format === 'single' || state.format === 'double') {
        const champion = state.championSource ? resolveSource(state.championSource, state.matches) : null;
        const col=document.createElement('div'); col.className='gen-round-col gen-round-col--champ';
        const heading=document.createElement('span'); heading.className='gen-round-title text-gold'; heading.textContent='CHAMPION';
        const box=document.createElement('div'); box.className='champ-display-box';
        const icon=document.createElement('span'); icon.className='champ-icon'; icon.textContent='🏆';
        const name=document.createElement('strong'); name.className='champ-crown-name'; name.textContent=champion ? champion.name : 'Belum ditentukan';
        const sub=document.createElement('p'); sub.className='champ-sub'; sub.textContent=champion ? 'Tournament Winner' : 'Selesaikan pertandingan';
        box.append(icon,name,sub); col.append(heading,box); deck.appendChild(col);
    }
}
function renderDoubleBracket(deck, state) {
    // In the six-team MPL-inspired layout, the opening matches are a deciding
    // round: winners enter the upper path and losers enter the lower path.
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

    // Keep the lower-bracket presentation to two visible columns as requested.
    // Do not delete real stages: later lower-bracket matches remain in sequence
    // inside the second column so their winner/loser dependencies stay intact.
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
    const card=document.createElement('div'); card.className='gen-match-box';
    const a=resolveSource(match.source1,state.matches), b=resolveSource(match.source2,state.matches);
    const scoreReady = Number.isInteger(match.score1) && Number.isInteger(match.score2) && match.score1 !== match.score2;
    [a,b].forEach((team,i)=>{
        const row=document.createElement('div'); row.className='gen-team-row';
        const name=document.createElement('span'); name.className='gen-team-name'; name.textContent=team ? team.name : 'TBD';
        const input=document.createElement('input'); input.type='number'; input.min='0'; input.max='999'; input.step='1'; input.className='gen-score-input'; input.setAttribute('aria-label',`Skor ${team?.name || 'TBD'}`);
        input.value = match[i===0?'score1':'score2'] ?? ''; input.disabled=!(a && b) || (match.autoAdvance === true);
        input.placeholder='–'; input.addEventListener('change',()=>{
            // Editing an earlier Swiss result invalidates every later pairing.
            if (state.format === 'swiss' && match.round < state.rounds.length) {
                state.rounds = state.rounds.slice(0, match.round);
                state.matches = state.matches.filter(item => item.round <= match.round);
            }
            const previousWinner = match.winner?.id ?? null;
            const key=i===0?'score1':'score2'; const other=i===0?'score2':'score1';
            const value=input.value.trim();
            match[key] = value === '' ? null : Number.parseInt(value, 10);
            if (match[key] !== null && (!Number.isInteger(match[key]) || match[key] < 0 || match[key] > 999)) match[key] = null;
            if (match[key] !== null && match[other] !== null && match[key] === match[other]) {
                alert('Skor tidak boleh seri untuk pertandingan yang membutuhkan pemenang.'); match[key]=null;
            }
            calculateMatch(match, state.matches);
            if (previousWinner !== (match.winner?.id ?? null)) clearDependentScores(state, match.id);
            if (state.format === 'swiss') advanceSwiss(state);
            else renderTournament();
        });
        if (team && match.winner?.id === team.id) row.classList.add('is-winner');
        row.append(name,input); card.appendChild(row);
    });
    if (match.winner) {
        const result=document.createElement('small');
        result.textContent=`Pemenang: ${match.winner.name}`;
        card.appendChild(result);
        if (state.format === 'double' && match.eliminateLoser && match.loser) {
            const eliminated=document.createElement('small');
            eliminated.className='bracket-eliminated-note';
            eliminated.textContent=`Tereliminasi: ${match.loser.name}`;
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

function renderStandings(deck,state) {
    const table=document.createElement('div'); table.className='league-standings';
    const heading=document.createElement('h3'); heading.textContent=state.format==='league'?'Klasemen Liga':'Klasemen Round Robin'; table.appendChild(heading);
    const records=new Map(state.teams.map(t=>[t.id,{team:t,played:0,wins:0,losses:0,points:0,scored:0,conceded:0}]));
    state.matches.forEach(m=>{ if(!m.winner||!m.loser)return; const a=records.get(m.team1.id),b=records.get(m.team2.id); a.played++;b.played++;a.scored+=m.score1;a.conceded+=m.score2;b.scored+=m.score2;b.conceded+=m.score1; if(m.score1>m.score2){a.wins++;b.losses++;a.points+=state.format==='league'?3:1;}else{b.wins++;a.losses++;b.points+=state.format==='league'?3:1;} });
    const tableEl=document.createElement('table'); tableEl.className='standings-table';
    const thead=document.createElement('thead'), hr=document.createElement('tr');
    ['#','Tim','P','W','L','Skor +/-','Pts'].forEach(t=>{const th=document.createElement('th');th.textContent=t;hr.appendChild(th);}); thead.appendChild(hr);tableEl.appendChild(thead);
    const tbody=document.createElement('tbody');
    [...records.values()].sort((a,b)=>b.points-a.points||b.wins-a.wins||(b.scored-b.conceded)-(a.scored-a.conceded)||a.team.seed-b.team.seed).forEach((r,i)=>{const tr=document.createElement('tr');[i+1,r.team.name,r.played,r.wins,r.losses,`${r.scored-r.conceded}`,r.points].forEach(v=>{const td=document.createElement('td');td.textContent=String(v);tr.appendChild(td);});tbody.appendChild(tr);});
    tableEl.appendChild(tbody);table.appendChild(tableEl);deck.appendChild(table);
}
function advanceSwiss(state) {
    state.matches.forEach(m=>calculateMatch(m,state.matches));
    const current=state.rounds[state.rounds.length-1];
    if (current.matches.length && current.matches.every(m=>m.winner && m.loser) && state.rounds.length < 5) {
        const next=buildSwissRound(state.teams,state.rounds,state.rounds.length+1);
        state.rounds.push(next); state.matches.push(...next.matches);
    }
    renderTournament();
}
function renderSwissProgress(deck,state) {
    const overview = document.createElement('div');
    overview.className = 'swiss-overview';
    const note=document.createElement('p'); note.className='swiss-overview__note';
    note.textContent='Swiss Stage demo: pairing ronde berikutnya dibuat setelah seluruh pertandingan ronde sebelumnya memiliki skor valid. Tiga kemenangan untuk lolos, tiga kekalahan untuk eliminasi.';
    overview.appendChild(note);
    const summary=document.createElement('div'); summary.className='league-standings swiss-records';
    const h=document.createElement('h3');h.textContent='Rekor Swiss';summary.appendChild(h);
    const body=document.createElement('div');
    state.teams.forEach(team=>{let w=(team.byeRounds||[]).filter(r=>r<=state.rounds.length).length,l=0;state.matches.forEach(m=>{if(m.winner?.id===team.id)w++;if(m.loser?.id===team.id)l++;});const row=document.createElement('p');row.textContent=`${team.name}: ${w}-${l} · ${w>=3?'LOLOS':l>=3?'TERELIMINASI':'AKTIF'}`;body.appendChild(row);});summary.appendChild(body);overview.appendChild(summary);
    deck.prepend(overview);
}

document.addEventListener('DOMContentLoaded', () => {
    syncTeamCountOptions();
    renderTeamInputs();
    document.getElementById('tourneyFormat').addEventListener('change', () => {
        const wasOverLimit = Number(document.getElementById('teamCount').value) > 6;
        syncTeamCountOptions(wasOverLimit);
        renderTeamInputs();
        buildTournament();
    });
    document.getElementById('teamCount').addEventListener('change', () => { renderTeamInputs(); buildTournament(); });
    document.querySelector('.generator-actions button')?.addEventListener('click', buildTournament);
    buildTournament();
});
</script>
@endsection
