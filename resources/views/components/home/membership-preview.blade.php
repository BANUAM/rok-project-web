<section class="home-section membership-preview-section" id="homeMembershipTiers" aria-labelledby="membershipHeading">
    <div class="home-section__inner">
        <header class="section-header" style="text-align: center; margin-bottom: 40px;">
            <h2 class="section-title" id="membershipHeading">PAKET AKSES &amp; KEANGGOTAAN ORGANIZER</h2>
            <p class="section-description" style="max-width: 720px; margin: 0 auto;">
                Tingkatkan kualitas turnamen Anda dengan akses halaman khusus <strong>Bracket Studio</strong>, logo tim interaktif dengan tautan WhatsApp &amp; poin pertandingan, serta kurikulum taktik pro esports Ruler of Kings.
            </p>
        </header>

        <div class="membership-pricing-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
            @foreach ($membershipPlans as $plan)
                <div class="pricing-card {{ $plan->is_featured ? 'pricing-card--featured' : '' }}" style="background: var(--rok-card-bg, #111622); border: 1px solid {{ $plan->is_featured ? 'var(--rok-gold)' : 'var(--rok-surface-border)' }}; border-radius: var(--radius-md); padding: 32px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="pricing-header" style="margin-bottom: 16px;">
                            <span class="pricing-badge {{ $plan->is_featured ? 'pricing-badge--gold' : '' }}" style="font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: var(--rok-gold); display: inline-block; margin-bottom: 4px;">{{ $plan->badge_title }}</span>
                            <h3 class="pricing-title" style="font-size: 24px; font-weight: 800; margin: 6px 0;">{{ $plan->name }}</h3>
                            <p class="pricing-sub" style="font-size: 13px; color: var(--rok-text-muted); line-height: 1.5; margin: 0;">{{ $plan->description }}</p>
                        </div>

                        <div style="margin: 16px 0; padding: 12px 0; border-top: 1px solid var(--rok-surface-border); border-bottom: 1px solid var(--rok-surface-border);">
                            <div style="font-size: 28px; font-weight: 900; font-family: var(--font-sub);">
                                {{ $plan->formatted_price }}
                                <span style="font-size: 13px; font-weight: 600; color: var(--rok-text-muted); font-family: var(--font-main);">/ {{ $plan->billing_cycle }}</span>
                            </div>
                        </div>

                        <ul class="pricing-features" style="list-style: none; padding: 0; margin: 0 0 24px 0; font-size: 13px; color: var(--rok-text-secondary); display: grid; gap: 10px;">
                            @if (is_array($plan->features))
                                @foreach ($plan->features as $feature)
                                    <li style="display: flex; gap: 8px; align-items: flex-start;">
                                        <span style="color: #4ade80; font-weight: bold; flex-shrink: 0;"><i data-lucide="check" style="width:14px;height:14px;"></i></span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="pricing-footer">
                        <a href="{{ route('membership.payment', ['plan' => $plan->slug]) }}" class="btn-rok {{ $plan->is_featured ? 'btn-rok--gold' : 'btn-rok--outline' }} btn-rok--full" style="text-align: center; text-decoration: none;">
                            @if($plan->slug === 'challenger')
                                Buka Fitur Bracket
                            @elseif($plan->slug === 'immortal')
                                Pilih Paket Immortal
                            @else
                                Pelajari Akses
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
