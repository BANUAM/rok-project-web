<section class="home-section achievements-preview" id="achievementsPreview" aria-labelledby="achievementsTitle">
    <div class="home-section__inner">
        <div class="section-head-row">
            <header class="section-header">
                <span class="eyebrow">Prestasi</span>
                <h2 class="section-title" id="achievementsTitle">PRESTASI &amp; KEJAYAAN</h2>
                <p class="section-description">
                    Pencapaian ROK di berbagai kompetisi, dari turnamen komunitas hingga kejuaraan resmi.
                </p>
            </header>
            <a href="{{ url('/achievements') }}" class="text-link">Semua prestasi <span aria-hidden="true">&rarr;</span></a>
        </div>

        <div class="achv-list">
            @forelse ($achievements as $achievement)
                <div class="achv-row" data-rok-animate="scroll-reveal">
                    <span class="achv-year">{{ $achievement->event_year }}</span>
                    <h3 class="achv-title">{{ $achievement->title }}</h3>
                    <span class="achv-team">{{ $achievement->team_name ?? 'Ruler Of Kings' }}</span>
                    <span class="achv-note">{{ $achievement->caption }}</span>
                </div>
            @empty
                <div class="empty-state-box">Prestasi belum ditambahkan. Kelola pencapaian melalui panel Admin.</div>
            @endforelse
        </div>
    </div>
</section>
