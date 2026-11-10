<section class="home-section teams-preview" id="teamsPreview" aria-labelledby="teamsPreviewTitle">
    <div class="home-section__inner">
        <header class="section-header">
            <span class="section-kicker">DIRECTORY // 01</span>
            <h2 class="section-title" id="teamsPreviewTitle">DIVISI KOMPETITIF</h2>
            <p class="section-description">
                Kenali divisi dan atlet profesional Ruler Of Kings yang berjuang membawa panji kerajaan di panggung esports nasional.
            </p>
        </header>

        <div class="editorial-division-ledger">
            @forelse ($divisions as $index => $division)
                <a href="{{ url('/teams') }}#div-{{ $division->slug }}" class="division-ledger-row">
                    <span class="division-ledger-index">0{{ $index + 1 }}</span>
                    <div class="division-ledger-main">
                        <h3 class="division-ledger-title">{{ $division->name }}</h3>
                        <span class="division-ledger-game">{{ $division->game_name }}</span>
                    </div>
                    <div class="division-ledger-roster">
                        @forelse ($division->activePlayers->take(5) as $player)
                            <span class="roster-player-pill">
                                <strong>{{ $player->nickname }}</strong> &bull; {{ $player->role }}
                            </span>
                        @empty
                            <span class="roster-player-pill">
                                <strong>ROSTER READY</strong> &bull; Active Lineup
                            </span>
                        @endforelse
                    </div>
                    <span class="division-ledger-action">
                        <span>Roster</span>
                        <span style="font-size: 16px;">&rarr;</span>
                    </span>
                </a>
            @empty
                <div class="empty-state-box">
                    <p>Divisi belum ditambahkan. Kelola divisi melalui panel Admin.</p>
                </div>
            @endforelse
        </div>

        <div class="section-footer-cta" style="margin-top: 36px; text-align: center;">
            <a href="{{ url('/teams') }}" class="btn-rok btn-rok--outline">
                Jelajahi Seluruh Struktur Tim &amp; Founders &rarr;
            </a>
        </div>
    </div>
</section>
