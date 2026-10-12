<section class="home-section achievements-preview" id="achievementsPreview" aria-labelledby="achievementsTitle">
    <div class="home-section__inner">
        <header class="section-header">
            <span class="section-badge">HALL OF FAME</span>
            <h2 class="section-title" id="achievementsTitle">PRESTASI &amp; KEJAYAAN</h2>
            <p class="section-description">
                Jejak pencapaian dan trofi yang membuktikan dedikasi serta konsistensi Ruler Of Kings.
            </p>
        </header>

        <div class="achievements-grid">
            @forelse ($achievements as $achievement)
                <article class="achievement-card">
                    <div class="achievement-card__media">
                        <img
                            src="{{ $achievement->image_url }}"
                            alt="{{ $achievement->title }}"
                            class="achievement-card__img"
                            loading="lazy"
                        >
                        <span class="achievement-card__year">{{ $achievement->event_year }}</span>
                    </div>

                    <div class="achievement-card__body">
                        <span class="achievement-card__team">{{ $achievement->team_name ?? 'Ruler Of Kings' }}</span>
                        <h3 class="achievement-card__title">{{ $achievement->title }}</h3>
                        <p class="achievement-card__caption">{{ $achievement->caption }}</p>
                    </div>
                </article>
            @empty
                <div class="empty-state-box">
                    <p>Prestasi belum ditambahkan. Kelola pencapaian melalui database/panel Admin.</p>
                </div>
            @endforelse
        </div>

        <div class="section-footer-cta">
            <a href="{{ url('/achievements') }}" class="btn-rok btn-rok--gold">
                Lihat Semua Prestasi &amp; Dokumentasi
            </a>
        </div>
    </div>
</section>
