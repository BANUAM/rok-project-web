@extends('layouts.app')

@section('title', 'Tournament Bracket Generator | ROK Revamp')
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
                    Coba generator instan di bawah ini. Masukkan nama turnamen, jumlah tim, dan nama tim peserta untuk menghasilkan bracket turnamen secara otomatis!
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
                            </select>
                        </div>

                        <div class="form-field">
                            <label for="teamCount" class="form-label">Jumlah Tim Peserta</label>
                            <select id="teamCount" class="form-select" onchange="renderTeamInputs()">
                                <option value="4">4 Tim (Semi Finals &rarr; Final)</option>
                                <option value="8" selected>8 Tim (Quarter Finals &rarr; Final)</option>
                                <option value="16">16 Tim (Round of 16 &rarr; Final)</option>
                            </select>
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
                        <button type="button" class="btn-rok btn-rok--gold btn-rok--lg" onclick="generateInteractiveBracket()">
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

function renderTeamInputs() {
    const count = parseInt(document.getElementById('teamCount').value, 10);
    const container = document.getElementById('teamInputsGrid');
    container.innerHTML = '';

    for (let i = 0; i < count; i++) {
        const defaultName = defaultTeamNames[i] || `Team ${i + 1}`;
        const inputDiv = document.createElement('div');
        inputDiv.className = 'team-input-item';
        inputDiv.innerHTML = `
            <span class="seed-badge">Seed #${i + 1}</span>
            <input type="text" class="form-input team-name-input" id="team_input_${i}" value="${defaultName}" placeholder="Nama Tim ${i + 1}">
        `;
        container.appendChild(inputDiv);
    }
}

function generateInteractiveBracket() {
    const tourneyTitle = document.getElementById('tourneyName').value || 'Turnamen ROK';
    const count = parseInt(document.getElementById('teamCount').value, 10);
    document.getElementById('outputTourneyTitle').textContent = tourneyTitle;

    const teamNames = [];
    for (let i = 0; i < count; i++) {
        const val = document.getElementById(`team_input_${i}`)?.value;
        teamNames.push(val ? val.trim() : `Team ${i + 1}`);
    }

    const deck = document.getElementById('generatedTreeDeck');
    deck.innerHTML = '';

    // Calculate Rounds
    const totalRounds = Math.log2(count);
    const roundNames = (count === 4)
        ? ['Semi Finals', 'Grand Finals']
        : (count === 8)
            ? ['Quarter Finals', 'Semi Finals', 'Grand Finals']
            : ['Round of 16', 'Quarter Finals', 'Semi Finals', 'Grand Finals'];

    let currentMatchCount = count / 2;
    let roundTeams = [...teamNames];

    for (let r = 0; r < totalRounds; r++) {
        const roundCol = document.createElement('div');
        roundCol.className = 'gen-round-col';
        roundCol.innerHTML = `<span class="gen-round-title">${roundNames[r]}</span>`;

        const matchesList = document.createElement('div');
        matchesList.className = 'gen-matches-list';

        for (let m = 0; m < currentMatchCount; m++) {
            const t1 = (r === 0) ? roundTeams[m * 2] : 'Winner of R' + r;
            const t2 = (r === 0) ? roundTeams[m * 2 + 1] : 'Winner of R' + r;

            const matchEl = document.createElement('div');
            matchEl.className = 'gen-match-box';
            matchEl.innerHTML = `
                <div class="gen-team-row">
                    <span class="gen-team-name">${t1}</span>
                    <input type="number" min="0" max="9" class="gen-score-input" value="${r === 0 ? Math.floor(Math.random() * 2) + 1 : 0}">
                </div>
                <div class="gen-team-row">
                    <span class="gen-team-name">${t2}</span>
                    <input type="number" min="0" max="9" class="gen-score-input" value="${r === 0 ? 0 : 0}">
                </div>
            `;
            matchesList.appendChild(matchEl);
        }

        roundCol.appendChild(matchesList);
        deck.appendChild(roundCol);
        currentMatchCount = currentMatchCount / 2;
    }

    // Champion Stage
    const champCol = document.createElement('div');
    champCol.className = 'gen-round-col gen-round-col--champ';
    champCol.innerHTML = `
        <span class="gen-round-title text-gold">CHAMPION</span>
        <div class="champ-display-box">
            <span class="champ-icon">&#x1F3C6;</span>
            <strong class="champ-crown-name">${teamNames[0]}</strong>
            <p class="champ-sub">Tournament Winner</p>
        </div>
    `;
    deck.appendChild(champCol);
}

document.addEventListener('DOMContentLoaded', () => {
    renderTeamInputs();
    generateInteractiveBracket();
});
</script>
@endsection
