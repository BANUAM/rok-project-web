<section class="home-section achievements-preview" id="achievementsPreview" aria-labelledby="achievementsTitle">
    <div class="home-section__inner">
        <header class="section-header">
            <span class="section-kicker">HALL OF ACCLAIM // 02</span>
            <h2 class="section-title" id="achievementsTitle">PRESTASI &amp; KEJAYAAN</h2>
            <p class="section-description">
                Jejak pencapaian dan trofi yang membuktikan dedikasi serta konsistensi Ruler Of Kings.
            </p>
        </header>

        <div class="editorial-accolade-ledger">
            @forelse ($achievements as $achievement)
                <div class="accolade-ledger-row">
                    <div class="accolade-year-rank">
                        <span class="accolade-year">{{ $achievement->event_year }}</span>
                        <span class="accolade-rank-badge">{{ $achievement->placement_rank ?? 'CHAMPION' }}</span>
                    </div>
                    <div class="accolade-title-cell">
                        <h3>{{ $achievement->title }}</h3>
                        <span class="accolade-division-tag">{{ $achievement->team_name ?? 'Ruler Of Kings' }}</span>
                    </div>
                    <div class="accolade-record-cell">{{ $achievement->caption }}</div>
                    <div class="accolade-status-badge">VERIFIED TITLE</div>
                </div>
            @empty
                <div class="empty-state-box">
                    <p>Prestasi belum ditambahkan. Kelola pencapaian melalui database/panel Admin.</p>
                </div>
            @endforelse
        </div>

        <div class="section-footer-cta" style="margin-top: 36px; text-align: center;">
            <a href="{{ url('/achievements') }}" class="btn-rok btn-rok--outline">
                Lihat Semua Prestasi &amp; Dokumentasi &rarr;
            </a>
        </div>
    </div>
</section>
