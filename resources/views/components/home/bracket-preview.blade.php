<section class="home-section bracket-preview" id="bracketSection" aria-labelledby="bracketHeading">
    <div class="home-section__inner">
        <header class="section-header">
            <span class="section-badge">TOURNAMENT ENGINE</span>
            <h2 class="section-title" id="bracketHeading">BUILD. COMPETE. CONQUER.</h2>
            <p class="section-subtitle-gold">
                Your Tournament. Your Bracket. Your Champion.
            </p>
            <p class="section-description">
                Platform generator bracket profesional di dalam website ROK Revamp. Kelola turnamen komunitas, single elimination, seeding otomatis, hingga live score tanpa ribet.
            </p>
        </header>

        {{-- Interactive Bracket Preview Visual --}}
        <div class="bracket-stage-card">
            <div class="bracket-stage-header">
                <div class="stage-info">
                    <span class="status-live-dot"></span>
                    <strong>LIVE BRACKET PREVIEW:</strong>
                    <span>{{ $demoTournament->name ?? 'ROK National Invitational' }}</span>
                </div>
                <div class="stage-badge">
                    <span>Single Elimination (8 Teams)</span>
                </div>
            </div>

            <div class="bracket-tree-demo">
                {{-- Round 1: Quarter Finals --}}
                <div class="bracket-col">
                    <span class="round-label">QUARTER FINALS</span>
                    <div class="matchup-box">
                        <div class="team-slot team-slot--winner">
                            <span class="team-seed">#1</span>
                            <span class="team-name">ROK Prime</span>
                            <span class="team-score">2</span>
                        </div>
                        <div class="team-slot">
                            <span class="team-seed">#8</span>
                            <span class="team-name">Solar Eclipse</span>
                            <span class="team-score">0</span>
                        </div>
                    </div>
                    <div class="matchup-box">
                        <div class="team-slot team-slot--winner">
                            <span class="team-seed">#4</span>
                            <span class="team-name">Shadow Legion</span>
                            <span class="team-score">2</span>
                        </div>
                        <div class="team-slot">
                            <span class="team-seed">#5</span>
                            <span class="team-name">Apex Titans</span>
                            <span class="team-score">1</span>
                        </div>
                    </div>
                </div>

                {{-- Round 2: Semi Finals --}}
                <div class="bracket-col">
                    <span class="round-label">SEMI FINALS</span>
                    <div class="matchup-box matchup-box--spaced">
                        <div class="team-slot team-slot--winner">
                            <span class="team-seed">#1</span>
                            <span class="team-name">ROK Prime</span>
                            <span class="team-score">2</span>
                        </div>
                        <div class="team-slot">
                            <span class="team-seed">#4</span>
                            <span class="team-name">Shadow Legion</span>
                            <span class="team-score">1</span>
                        </div>
                    </div>
                    <div class="matchup-box matchup-box--spaced">
                        <div class="team-slot">
                            <span class="team-seed">#2</span>
                            <span class="team-name">Aether Esports</span>
                            <span class="team-score">1</span>
                        </div>
                        <div class="team-slot team-slot--winner">
                            <span class="team-seed">#3</span>
                            <span class="team-name">Valkyrie Horizon</span>
                            <span class="team-score">2</span>
                        </div>
                    </div>
                </div>

                {{-- Round 3: Grand Finals --}}
                <div class="bracket-col">
                    <span class="round-label">GRAND FINALS</span>
                    <div class="matchup-box matchup-box--finals">
                        <div class="team-slot team-slot--active">
                            <span class="team-seed">#1</span>
                            <span class="team-name">ROK Prime</span>
                            <span class="match-badge">FINALIST</span>
                        </div>
                        <div class="matchup-vs">VS</div>
                        <div class="team-slot team-slot--active">
                            <span class="team-seed">#3</span>
                            <span class="team-name">Valkyrie Horizon</span>
                            <span class="match-badge">FINALIST</span>
                        </div>
                    </div>
                </div>

                {{-- Champion --}}
                <div class="bracket-col bracket-col--champ">
                    <span class="round-label text-gold">CHAMPION</span>
                    <div class="champ-crown-box">
                        <span class="crown-icon">&#x1F451;</span>
                        <strong>TROPHY STAGE</strong>
                        <p class="champ-hint">BO5 Grand Final Match</p>
                    </div>
                </div>
            </div>

            <div class="bracket-stage-footer">
                <div class="membership-hint">
                    <span class="badge-mini">Organizer Free &amp; Premium</span>
                    <p>Free Organizer dapat membuat bracket tanpa logo. Upgrade ke ROK Challenger / Immortal untuk kustomisasi logo tim &amp; branding penuh.</p>
                </div>
                <a href="{{ url('/bracket') }}" class="btn-rok btn-rok--gold">
                    Buka Fitur Bracket Turnamen &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
