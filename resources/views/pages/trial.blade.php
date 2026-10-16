@extends('layouts.app')

@section('title', 'Pendaftaran Trial Atlet Esports | ROK Revamp')
@section('description', 'Pendaftaran seleksi pro player Ruler Of Kings (ROK). Tahap awal pengisian nama dan email untuk mendapatkan tautan trial personal.')

@section('content')
<div class="page-container">
    <section class="page-hero {{ !empty($pageHeader?->image_url) ? 'page-hero--has-banner' : '' }}">
        @if (!empty($pageHeader?->image_url))
            <div class="page-hero__bg">
                <img src="{{ $pageHeader->image_url }}" alt="{{ $pageHeader->title ?? 'ROK Trial & Selection' }}">
                <div class="page-hero__overlay"></div>
            </div>
        @endif
        <div class="page-hero__inner">
            <span class="section-badge">PLAYER RECRUITMENT 2026</span>
            <h1 class="page-title">{{ $pageHeader?->title ?? 'ROK TRIAL & SELECTION' }}</h1>
            <p class="page-subtitle">
                {{ $pageHeader?->subtitle ?? 'Bergabunglah bersama keluarga besar Ruler Of Kings. Proses seleksi terbuka untuk menemukan atlet berpotensi tinggi dengan kedisiplinan dan kecerdasan taktis luar biasa.' }}
            </p>
        </div>
    </section>

    <section class="trial-flow-section">
        <div class="section-inner">
            {{-- Workflow Diagram --}}
            <div class="pipeline-card">
                <h2 class="pipeline-card__title">ALUR PENDAFTARAN &amp; SELEKSI RESMI</h2>
                <div class="pipeline-steps">
                    <div class="pipeline-step is-active">
                        <div class="pipeline-step__num">1</div>
                        <div class="pipeline-step__info">
                            <strong>Pendaftaran Awal</strong>
                            <p>Nama &amp; Email</p>
                        </div>
                    </div>
                    <div class="pipeline-step">
                        <div class="pipeline-step__num">2</div>
                        <div class="pipeline-step__info">
                            <strong>Link Personal</strong>
                            <p>Token Keamanan</p>
                        </div>
                    </div>
                    <div class="pipeline-step">
                        <div class="pipeline-step__num">3</div>
                        <div class="pipeline-step__info">
                            <strong>Lengkapi Profil</strong>
                            <p>Identitas &amp; Upload</p>
                        </div>
                    </div>
                    <div class="pipeline-step">
                        <div class="pipeline-step__num">4</div>
                        <div class="pipeline-step__info">
                            <strong>Tes LSM Online</strong>
                            <p>Evaluasi Taktik</p>
                        </div>
                    </div>
                    <div class="pipeline-step">
                        <div class="pipeline-step__num">5</div>
                        <div class="pipeline-step__info">
                            <strong>Review &amp; Kontrak</strong>
                            <p>Penerimaan Roster</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="trial-grid">
                {{-- Form Pendaftaran Awal --}}
                <div class="trial-form-box">
                    <div class="form-header">
                        <span class="badge-mini">TAHAP 01</span>
                        <h3 class="form-title">PENDAFTARAN AWAL</h3>
                        <p class="form-desc">
                            Calon pemain belum perlu membuat akun permanen. Cukup masukkan nama lengkap dan email aktif untuk membuat token trial pribadimu.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('trial.register') }}" class="rok-form">
                        @csrf

                        <div class="form-field">
                            <label for="name" class="form-label">Nama Lengkap Sesuai KTP/Identitas <span class="text-gold">*</span></label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Farhan Alamsyah"
                                required
                            >
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="email" class="form-label">Alamat Email Aktif <span class="text-gold">*</span></label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-input @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Contoh: farhan@gmail.com"
                                required
                            >
                            @error('email')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <small class="form-help">
                                Tautan link personal trial kamu akan dibuatkan secara instan dan aman.
                            </small>
                        </div>

                        <button type="submit" class="btn-rok btn-rok--gold btn-rok--full">
                            Lanjutkan ke Pengisian Berkas &rarr;
                        </button>
                    </form>
                </div>

                {{-- Ketentuan & Persyaratan Seleksi --}}
                <div class="trial-rules-box">
                    <h3 class="rules-title">PERSYARATAN MINIMAL</h3>
                    <ul class="rules-list">
                        <li>
                            <span class="rules-bullet">&#x2713;</span>
                            <div>
                                <strong>Rank Mobile Legends</strong>
                                <p>Minimal Mythical Glory atau Mythical Immortal musim saat ini/terakhir.</p>
                            </div>
                        </li>
                        <li>
                            <span class="rules-bullet">&#x2713;</span>
                            <div>
                                <strong>Kesiapan Waktu &amp; Disiplin</strong>
                                <p>Siap mengikuti sesi latihan (scrim), evaluasi taktis, dan coaching rutin.</p>
                            </div>
                        </li>
                        <li>
                            <span class="rules-bullet">&#x2713;</span>
                            <div>
                                <strong>Dokumentasi Profil Game</strong>
                                <p>Menyiapkan 3 screenshot: profil MLBB, win rate hero favorit, dan tier stars terkini.</p>
                            </div>
                        </li>
                        <li>
                            <span class="rules-bullet">&#x2713;</span>
                            <div>
                                <strong>Tes LSM (Logika Strategi &amp; Makro)</strong>
                                <p>Wajib menyelesaikan ujian pilihan ganda secara objektif tanpa bantuan pihak ketiga.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="rules-note">
                        <span class="note-icon">&#9432;</span>
                        <p>Kontrak fisik atlet dilakukan secara resmi di luar sistem setelah calon pemain dinyatakan lulus seleksi dan evaluasi tatap muka.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
