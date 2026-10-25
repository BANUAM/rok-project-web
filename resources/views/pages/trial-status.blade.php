@extends('layouts.app')

@section('title', 'Status Seleksi Trial | ROK Revamp')
@section('description', 'Status perkembangan seleksi dan hasil evaluasi ujian LSM calon atlet esports Ruler Of Kings.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge">STATUS SELEKSI RESMI</span>
            <h1 class="page-title">PROGRESS SELEKSI TRIAL</h1>
            <p class="page-subtitle">
                Pantau status evaluasi berkas dan penilaian tes LSM kamu secara berkala melalui halaman personal ini.
            </p>
        </div>
    </section>

    <section class="status-tracking-section">
        <div class="section-inner">
            {{-- Candidate Profile Card --}}
            <div class="candidate-summary-card">
                <div class="summary-left">
                    <span class="candidate-avatar-icon"><i data-lucide="medal" style="width:24px;height:24px;color:#f59e0b;"></i></span>
                    <div>
                        <span class="badge-mini">KANDIDAT TRIAL</span>
                        <h2 class="candidate-name">{{ $candidate->name }}</h2>
                        <p class="candidate-ign">IGN: <strong>{{ $candidate->nickname ?? 'Belum diisi' }}</strong> &bull; Role: <strong>{{ $candidate->role ?? '-' }}</strong></p>
                        <p class="candidate-rank">Rank: <strong>{{ $candidate->rank_display }}</strong></p>
                    </div>
                </div>

                <div class="summary-right">
                    <span class="status-pill status-pill--{{ $candidate->status }}">
                        {{ $candidate->status_label }}
                    </span>
                    <small class="token-footnote">Token: <code>{{ $candidate->access_token }}</code></small>
                </div>
            </div>

            {{-- Progress Stepper --}}
            <div class="progress-stepper-card">
                <h3 class="stepper-title">TAHAPAN PROSES SELEKSI</h3>
                <div class="stepper-steps">
                    @php
                        $statuses = ['registered', 'profile_completed', 'lsm_completed', 'under_review', 'passed'];
                        $currentIndex = array_search($candidate->status, $statuses);
                        if ($candidate->status === 'rejected') $currentIndex = 3;
                    @endphp

                    <div class="step-node {{ $currentIndex >= 0 ? 'is-done' : '' }}">
                        <div class="step-bubble"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
                        <span class="step-label">Pendaftaran Awal</span>
                    </div>

                    <div class="step-line {{ $currentIndex >= 1 ? 'is-done' : '' }}"></div>

                    <div class="step-node {{ $currentIndex >= 1 ? 'is-done' : '' }}">
                        <div class="step-bubble"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
                        <span class="step-label">Profil &amp; Berkas</span>
                    </div>

                    <div class="step-line {{ $currentIndex >= 2 ? 'is-done' : '' }}"></div>

                    <div class="step-node {{ $currentIndex >= 2 ? 'is-done' : '' }}">
                        <div class="step-bubble"><i data-lucide="check" style="width:14px;height:14px;"></i></div>
                        <span class="step-label">Ujian LSM Selesai</span>
                    </div>

                    <div class="step-line {{ $currentIndex >= 3 ? 'is-done' : '' }}"></div>

                    <div class="step-node {{ $currentIndex >= 3 ? 'is-done' : '' }} {{ $candidate->status === 'rejected' ? 'is-rejected' : '' }}">
                        <div class="step-bubble">
                            @if ($candidate->status === 'rejected')
                                <i data-lucide="x" style="width:14px;height:14px;"></i>
                            @else
                                <i data-lucide="settings" style="width:14px;height:14px;"></i>
                            @endif
                        </div>
                        <span class="step-label">Review Coach</span>
                    </div>

                    <div class="step-line {{ $currentIndex >= 4 ? 'is-done' : '' }}"></div>

                    <div class="step-node {{ $candidate->status === 'passed' ? 'is-done is-passed' : '' }}">
                        <div class="step-bubble"><i data-lucide="trophy" style="width:14px;height:14px;"></i></div>
                        <span class="step-label">Penerimaan Roster</span>
                    </div>
                </div>
            </div>

            {{-- LSM Score Results --}}
            @if ($candidate->lsmAttempt)
                <div class="lsm-score-card">
                    <div class="score-card-header">
                        <div>
                            <span class="badge-mini">HASIL UJIAN TAKTIK</span>
                            <h3 class="score-card-title">NILAI EVALUASI LSM</h3>
                        </div>
                        <div class="score-badge {{ $candidate->lsmAttempt->is_passed ? 'score-badge--pass' : 'score-badge--fail' }}">
                            {{ $candidate->lsmAttempt->is_passed ? 'LULUS AMBANG BATAS' : 'DI BAWAH AMBANG BATAS' }}
                        </div>
                    </div>

                    <div class="score-stats-grid">
                        <div class="score-stat-box">
                            <span class="stat-number">{{ number_format($candidate->lsmAttempt->score, 1) }}%</span>
                            <span class="stat-title">SKOR AKHIR</span>
                        </div>
                        <div class="score-stat-box">
                            <span class="stat-number">{{ $candidate->lsmAttempt->correct_answers }} / {{ $candidate->lsmAttempt->total_questions }}</span>
                            <span class="stat-title">JAWABAN BENAR</span>
                        </div>
                        <div class="score-stat-box">
                            <span class="stat-number">{{ $candidate->lsmAttempt->completed_at ? $candidate->lsmAttempt->completed_at->format('d M Y, H:i') : '-' }}</span>
                            <span class="stat-title">WAKTU PENYELESAIAN</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Notes & Next Steps --}}
            <div class="info-guidelines-box">
                <h4>INFORMASI TAHAP SELANJUTNYA</h4>
                <p>
                    Tim Pelatih dan Manajemen ROK akan meninjau kombinasi antara skor LSM, rekam jejak rank Mobile Legends, dan hero pool kamu.
                </p>
                @if ($candidate->status === 'passed')
                    <div class="alert-box alert-box--success">
                        <strong>SELAMAT! KAMU DINYATAKAN LOLOS SELEKSI TRIAL ROK!</strong>
                        <p>Manajemen akan menghubungi kamu melalui kontak email/WhatsApp untuk proses wawancara akhir dan penandatanganan kontrak resmi atlet di luar sistem.</p>
                    </div>
                @elseif ($candidate->status === 'rejected')
                    <div class="alert-box alert-box--warning">
                        <strong>Status: Belum Memenuhi Kualifikasi</strong>
                        <p>Terima kasih atas partisipasimu. Jangan patah semangat, asah kembali mekanik dan pemahaman makro untuk kesempatan trial berikutnya!</p>
                    </div>
                @else
                    <div class="alert-box alert-box--info">
                        <strong>Status: Sedang Dalam Proses Peninjauan</strong>
                        <p>Mohon menunggu konfirmasi berkala. Pengumuman seleksi akan diperbarui langsung pada halaman ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
