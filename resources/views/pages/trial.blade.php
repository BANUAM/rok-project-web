@extends('layouts.app')

@section('title', 'Pendaftaran Trial Atlet Esports | ROK')
@section('description', 'Pendaftaran seleksi pro player Ruler Of Kings (ROK). Isi nama dan email untuk mendapatkan tautan trial personal.')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'Trial & seleksi' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <h1 class="page-title">{{ $pageHeader?->title ?? 'Bergabung dengan ROK' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Seleksi terbuka untuk calon atlet. Daftarkan identitasmu untuk mendapat akses tes LSM dan evaluasi gameplay oleh manajemen ROK.' }}
            </p>
        </div>
    </section>

    <section class="trial-section trial-flow-section">
        <div class="section-inner">
            <div class="trial-layout">
                <div>
                    <span class="eyebrow">Alur seleksi</span>
                    <h2 class="section-title" style="font-size: clamp(28px, 3.4vw, 42px);">Lima langkah menuju roster</h2>

                    <div class="trial-steps">
                        <div class="trial-step">
                            <span class="trial-step__num">1</span>
                            <div>
                                <h3>Pendaftaran awal</h3>
                                <p>Isi nama dan email aktif untuk membuat token trial pribadimu.</p>
                            </div>
                        </div>
                        <div class="trial-step">
                            <span class="trial-step__num">2</span>
                            <div>
                                <h3>Lengkapi profil</h3>
                                <p>Lewat tautan pribadi, isi identitas dan unggah tangkapan layar profil game.</p>
                            </div>
                        </div>
                        <div class="trial-step">
                            <span class="trial-step__num">3</span>
                            <div>
                                <h3>Tes LSM online</h3>
                                <p>Ujian pilihan ganda tentang logika, strategi, dan makro permainan, dikerjakan sendiri tanpa bantuan.</p>
                            </div>
                        </div>
                        <div class="trial-step">
                            <span class="trial-step__num">4</span>
                            <div>
                                <h3>Review manajemen</h3>
                                <p>Manajemen dan pelatih menilai rekaman gameplay serta hasil tes.</p>
                            </div>
                        </div>
                        <div class="trial-step">
                            <span class="trial-step__num">5</span>
                            <div>
                                <h3>Kontrak &amp; penerimaan roster</h3>
                                <p>Kontrak dilakukan resmi di luar sistem setelah kamu dinyatakan lulus dan melalui evaluasi tatap muka.</p>
                            </div>
                        </div>
                    </div>

                    <div class="trial-requirements">
                        <h3>Persyaratan minimal</h3>
                        <ul>
                            <li>Rank Mobile Legends minimal Mythical Glory atau Mythical Immortal musim ini atau terakhir.</li>
                            <li>Siap mengikuti scrim, evaluasi taktik, dan coaching rutin.</li>
                            <li>Menyiapkan 3 tangkapan layar: profil MLBB, win rate hero favorit, dan tier stars terkini.</li>
                        </ul>
                    </div>
                </div>

                <div class="trial-card">
                    <h2>Daftar trial</h2>
                    <p>Belum perlu membuat akun. Cukup nama lengkap dan email aktif untuk membuat token trial pribadimu.</p>

                    <form method="POST" action="{{ route('trial.register') }}">
                        @csrf

                        <div class="field">
                            <label for="name">Nama lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Sesuai identitas" required autocomplete="name">
                            @error('name')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="email">Email aktif</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email">
                            @error('email')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn-rok btn-rok--gold btn-rok--full" style="margin-top: 8px;">Lanjutkan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
