<section class="home-section home-section--alt teams-preview" id="teamsPreview" aria-labelledby="teamsPreviewTitle">
    <div class="home-section__inner">
        <div class="section-head-row">
            <header class="section-header">
                <span class="eyebrow">Divisi</span>
                <h2 class="section-title" id="teamsPreviewTitle">DIVISI KOMPETITIF</h2>
                <p class="section-description">
                    Setiap divisi dibina oleh staf pelatih dan diperkuat atlet pilihan yang membawa nama ROK di turnamen nasional.
                </p>
            </header>
            <a href="{{ url('/teams') }}" class="text-link">Lihat semua tim <span aria-hidden="true">&rarr;</span></a>
        </div>

        <div class="division-list">
            @forelse ($divisions as $index => $division)
                <a href="{{ url('/teams') }}#div-{{ $division->slug }}" class="division-row" data-rok-animate="scroll-reveal">
                    <span class="division-row__index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <h3 class="division-row__name">{{ $division->name }}</h3>
                        <span class="division-row__game">{{ $division->game_name }}</span>
                    </div>
                    <div class="division-row__players">
                        @forelse ($division->activePlayers->take(6) as $player)
                            <span>{{ $player->nickname }}</span>
                        @empty
                            <span>Roster segera diumumkan</span>
                        @endforelse
                    </div>
                    <span class="division-row__go" aria-hidden="true">&rarr;</span>
                </a>
            @empty
                <div class="empty-state-box">Divisi belum ditambahkan. Kelola divisi melalui panel Admin.</div>
            @endforelse
        </div>
    </div>
</section>
