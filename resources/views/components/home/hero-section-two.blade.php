
<section class="team-intro" id="teamIntro" aria-labelledby="teamIntroTitle">
    <div class="team-intro__inner">

        {{-- Left: Introduction --}}
        <div class="team-intro__copy">
            <p class="team-intro__eyebrow">RULER OF KINGS</p>

            <h2 class="team-intro__title" id="teamIntroTitle">
                LET INTRODUCE
                <br>
                OUR TEAM
            </h2>

            <p class="team-intro__description">
                Meet the people behind ROK.
            </p>

            <span class="team-intro__accent" aria-hidden="true"></span>
        </div>

        {{-- Right: Stacked team cards --}}
        <div class="team-intro__visual">

            @if ($teamMembers->isNotEmpty())
                <div
                    class="team-stack"
                    id="teamStack"
                    data-active="0"
                    aria-label="Team member profiles"
                >
                    @foreach ($teamMembers as $index => $member)
                        <button
                            class="team-card {{ $index === 0 ? 'is-active' : '' }}"
                            type="button"
                            data-member="{{ $index }}"
                            data-member-name="{{ $member->name }}"
                            aria-label="Lihat profil {{ $member->name }}"
                            aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                            @if ($index !== 0) tabindex="-1" @endif
                        >
                            <span class="team-card__number">
                                {{ sprintf('%02d', $index + 1) }}
                            </span>

                            @if ($member->photo_path)
                                <img
                                    class="team-card__photo"
                                    src="{{ \Illuminate\Support\Facades\Storage::url($member->photo_path) }}"
                                    alt="Foto {{ $member->name }}"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                >
                            @else
                                <span class="team-card__placeholder">
                                    Foto belum tersedia
                                </span>
                            @endif

                            <span class="team-card__caption">ROK TEAM</span>
                        </button>
                    @endforeach
                </div>

                {{-- Member name stays outside the cards --}}
                <div class="team-intro__member">
                    <p class="team-intro__member-label">MEET THE TEAM</p>

                    <h3
                        class="team-intro__member-name"
                        id="teamMemberName"
                        aria-live="polite"
                        aria-atomic="true"
                    >
                        {{ $teamMembers->first()->name }}
                    </h3>

                    <div class="team-intro__member-controls">
                        <button
                            type="button"
                            id="teamPrevious"
                            aria-label="Anggota sebelumnya"
                        >
                            &#8592;
                        </button>

                        <span id="teamCounter">
                            01 / {{ sprintf('%02d', $teamMembers->count()) }}
                        </span>

                        <button
                            type="button"
                            id="teamNext"
                            aria-label="Anggota berikutnya"
                        >
                            &#8594;
                        </button>
                    </div>
                </div>
            @else
                <div class="team-intro__empty">
                    <p>Data anggota tim belum tersedia.</p>
                </div>
            @endif

        </div>
    </div>
</section>
