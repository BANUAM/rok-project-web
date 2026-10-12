<section class="home-section partners-preview" id="partnersSection" aria-labelledby="partnersHeading">
    <div class="home-section__inner">
        <header class="section-header">
            <span class="section-badge">COLLABORATION</span>
            <h2 class="section-title" id="partnersHeading">OFFICIAL PARTNERS &amp; SPONSORS</h2>
            <p class="section-description">
                Didukung oleh brand dan media partner terkemuka yang bersama-sama memajukan ekosistem Ruler Of Kings.
            </p>
        </header>

        <div class="partners-grid">
            @forelse ($partners as $partner)
                <a
                    href="{{ $partner->website_url ?? '#' }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="partner-card"
                    title="{{ $partner->name }} - {{ $partner->category }}"
                >
                    <img
                        src="{{ $partner->logo_url }}"
                        alt="{{ $partner->name }} Logo"
                        class="partner-card__logo"
                        loading="lazy"
                    >
                    <span class="partner-card__name">{{ $partner->name }}</span>
                    <span class="partner-card__category">{{ $partner->category }}</span>
                </a>
            @empty
                <div class="empty-state-box">
                    <p>Partner dan sponsor dikelola melalui database/panel Admin.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
