<section class="home-section teams-preview" id="teamsPreview" aria-labelledby="teamsPreviewTitle">
    <div class="home-section__inner">
        <header class="section-header">
            <span class="section-badge">ROK TEAMS &amp; ROSTER</span>
            <h2 class="section-title" id="teamsPreviewTitle">DIVISI KOMPETITIF</h2>
            <p class="section-description">
                Kenali divisi dan atlet profesional Ruler Of Kings yang berjuang membawa panji kerajaan di panggung esports nasional.
            </p>
        </header>

        <div class="divisions-grid">
            @forelse ($divisions as $division)
                <article class="division-card">
                    <div class="division-card__visual">
                        <img
                            src="{{ $division->icon_url }}"
                            alt="{{ $division->name }} Icon"
                            class="division-card__icon"
                            loading="lazy"
                        >
                        <span class="division-card__game">{{ $division->game_name }}</span>
                    </div>

                    <div class="division-card__content">
                        <h3 class="division-card__title">{{ $division->name }}</h3>
                        <p class="division-card__desc">{{ $division->description }}</p>

                        <div class="division-card__roster-strip">
                            <span class="roster-count">
                                <strong>{{ $division->activePlayers->count() }}</strong> Anggota Aktif
                            </span>
                            <div class="roster-avatars">
                                @foreach ($division->activePlayers->take(5) as $player)
                                    <span class="avatar-chip" title="{{ $player->nickname }} ({{ $player->role }})">
                                        {{ substr($player->nickname, 0, 2) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ url('/teams') }}#div-{{ $division->slug }}" class="btn-card-link">
                            Lihat Roster Lengkap &rarr;
                        </a>
                    </div>
                </article>
            @empty
                <div class="empty-state-box">
                    <p>Divisi belum ditambahkan. Kelola divisi melalui panel Admin.</p>
                </div>
            @endforelse
        </div>

        <div class="section-footer-cta">
            <a href="{{ url('/teams') }}" class="btn-rok btn-rok--outline">
                Jelajahi Seluruh Struktur Tim &amp; Founders
            </a>
        </div>
    </div>
</section>
