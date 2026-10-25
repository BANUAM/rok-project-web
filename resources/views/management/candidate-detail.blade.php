@extends('layouts.management')

@section('title', 'Detail Evaluasi Kandidat | ROK Management')
@section('header_title', 'EVALUASI CALON PEMAIN: ' . strtoupper($candidate->name))

@section('content')
<div class="mgmt-page-deck">
    <div class="candidate-detail-header-card mgmt-card">
        <div class="cd-left">
            <span class="avatar-large"><i data-lucide="medal" style="width:36px;height:36px;"></i></span>
            <div>
                <span class="badge-mini">KANDIDAT TRIAL ID: #{{ $candidate->id }}</span>
                <h2 class="cd-name">{{ $candidate->name }}</h2>
                <div class="cd-tags">
                    <span class="cd-tag">IGN: <strong>{{ $candidate->nickname ?? '-' }}</strong></span>
                    <span class="cd-tag">Role: <strong>{{ $candidate->role ?? '-' }}</strong></span>
                    <span class="cd-tag">Rank: <strong>{{ $candidate->rank_display }}</strong></span>
                    <span class="cd-tag">Email: <strong>{{ $candidate->email }}</strong></span>
                </div>
            </div>
        </div>

        <div class="cd-right">
            <span class="status-badge status-badge--{{ $candidate->status }} status-badge--lg">
                {{ $candidate->status_label }}
            </span>
            <div class="text-xs text-muted mt-2">
                Token: <code>{{ $candidate->access_token }}</code>
            </div>
        </div>
    </div>

    <div class="cd-two-cols">
        {{-- Left: Identity & Uploads --}}
        <div class="cd-col">
            {{-- Data Identitas --}}
            <div class="mgmt-card">
                <div class="mgmt-card__header">
                    <h3>DATA IDENTITAS &amp; DOMISILI</h3>
                </div>
                <div class="cd-info-list">
                    <div class="cd-info-row">
                        <span class="cd-label">Nama Lengkap:</span>
                        <strong class="cd-val">{{ $candidate->name }}</strong>
                    </div>
                    <div class="cd-info-row">
                        <span class="cd-label">Nickname In-Game:</span>
                        <strong class="cd-val">{{ $candidate->nickname ?? '-' }}</strong>
                    </div>
                    <div class="cd-info-row">
                        <span class="cd-label">Umur:</span>
                        <strong class="cd-val">{{ $candidate->age ? $candidate->age . ' Tahun' : '-' }}</strong>
                    </div>
                    <div class="cd-info-row">
                        <span class="cd-label">Tempat &amp; Tgl Lahir:</span>
                        <strong class="cd-val">{{ $candidate->birthplace ?? '-' }}, {{ $candidate->birthdate ? $candidate->birthdate->format('d M Y') : '-' }}</strong>
                    </div>
                    <div class="cd-info-row">
                        <span class="cd-label">Alamat Domisili:</span>
                        <strong class="cd-val">{{ $candidate->address ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            {{-- Bukti Upload Tangkapan Layar --}}
            <div class="mgmt-card mt-4">
                <div class="mgmt-card__header">
                    <h3>BERKAS TANGKAPAN LAYAR GAME</h3>
                </div>
                <div class="cd-uploads-grid">
                    {{-- Upload 1: Profil --}}
                    <div class="upload-preview-box">
                        <span class="upload-box-title">1. Profil MLBB</span>
                        @if ($candidate->profile_screenshot_path)
                            <a href="{{ $candidate->profile_screenshot_url }}" target="_blank">
                                <img src="{{ $candidate->profile_screenshot_url }}" alt="Profil MLBB" class="cd-screenshot-img">
                            </a>
                            <span class="text-xs text-gold">Klik untuk memperbesar</span>
                        @else
                            <div class="upload-placeholder-box">Belum diunggah</div>
                        @endif
                    </div>

                    {{-- Upload 2: Hero Favorit --}}
                    <div class="upload-preview-box">
                        <span class="upload-box-title">2. Hero Favorit &amp; WR</span>
                        @if ($candidate->favorite_hero_screenshot_path)
                            <a href="{{ $candidate->favorite_hero_screenshot_url }}" target="_blank">
                                <img src="{{ $candidate->favorite_hero_screenshot_url }}" alt="Hero Favorit" class="cd-screenshot-img">
                            </a>
                            <span class="text-xs text-gold">Klik untuk memperbesar</span>
                        @else
                            <div class="upload-placeholder-box">Belum diunggah</div>
                        @endif
                    </div>

                    {{-- Upload 3: Tier & Stars --}}
                    <div class="upload-preview-box">
                        <span class="upload-box-title">3. Bukti Tier &amp; Stars</span>
                        @if ($candidate->tier_screenshot_path)
                            <a href="{{ $candidate->tier_screenshot_url }}" target="_blank">
                                <img src="{{ $candidate->tier_screenshot_url }}" alt="Tier & Stars" class="cd-screenshot-img">
                            </a>
                            <span class="text-xs text-gold">Klik untuk memperbesar</span>
                        @else
                            <div class="upload-placeholder-box">Belum diunggah</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: LSM Score & Review Actions --}}
        <div class="cd-col">
            {{-- Hasil Tes LSM --}}
            <div class="mgmt-card">
                <div class="mgmt-card__header">
                    <h3>HASIL TES TAKTIK LSM</h3>
                    @if ($candidate->lsmAttempt)
                        <span class="badge-mini {{ $candidate->lsmAttempt->is_passed ? 'badge-mini--gold' : '' }}">
                            {{ $candidate->lsmAttempt->is_passed ? 'LULUS AMBANG BATAS' : 'TIDAK LULUS' }}
                        </span>
                    @endif
                </div>

                @if ($candidate->lsmAttempt)
                    <div class="lsm-score-summary">
                        <div class="score-circle">
                            <strong>{{ number_format($candidate->lsmAttempt->score, 1) }}%</strong>
                            <span>SKOR AKHIR</span>
                        </div>
                        <div class="score-details">
                            <p><strong>Benar:</strong> {{ $candidate->lsmAttempt->correct_answers }} dari {{ $candidate->lsmAttempt->total_questions }} Soal</p>
                            <p><strong>Waktu Selesai:</strong> {{ $candidate->lsmAttempt->completed_at ? $candidate->lsmAttempt->completed_at->format('d M Y, H:i') : '-' }}</p>
                        </div>
                    </div>

                    {{-- Question-by-question breakdown --}}
                    <div class="qa-breakdown-list">
                        <h4>Rincian Jawaban Soal:</h4>
                        @foreach ($candidate->lsmAttempt->answers as $aIndex => $ans)
                            <div class="qa-item {{ $ans->is_correct ? 'qa-item--correct' : 'qa-item--wrong' }}">
                                <span class="qa-num">#{{ $aIndex + 1 }}</span>
                                <div class="qa-text">
                                    <p class="qa-q">{{ $ans->question?->question }}</p>
                                    <div class="qa-ans">
                                        Jawaban Kandidat: <strong>{{ $ans->selectedOption?->option_text ?? 'Tidak dijawab' }}</strong>
                                        <span class="qa-badge">{{ $ans->is_correct ? 'Benar (+1)' : 'Salah (0)' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state-box">
                        <p>Kandidat belum menyelesaikan tes tertulis LSM.</p>
                    </div>
                @endif
            </div>

            {{-- Form Review Coach / Manager --}}
            <div class="mgmt-card mt-4">
                <div class="mgmt-card__header">
                    <h3>KEPUTUSAN &amp; CATATAN PELATIH</h3>
                </div>
                <form method="POST" action="{{ route('management.candidates.status', $candidate) }}" class="cd-review-form">
                    @csrf
                    <div class="form-field">
                        <label class="form-label">Ubah Status Seleksi</label>
                        <select name="status" class="form-select" required>
                            <option value="registered" {{ $candidate->status === 'registered' ? 'selected' : '' }}>Pendaftaran Awal</option>
                            <option value="profile_completed" {{ $candidate->status === 'profile_completed' ? 'selected' : '' }}>Profil Terisi</option>
                            <option value="lsm_completed" {{ $candidate->status === 'lsm_completed' ? 'selected' : '' }}>LSM Selesai</option>
                            <option value="under_review" {{ $candidate->status === 'under_review' ? 'selected' : '' }}>Sedang Ditinjau (Under Review)</option>
                            <option value="passed" {{ $candidate->status === 'passed' ? 'selected' : '' }}>Lolos Seleksi (Passed)</option>
                            <option value="rejected" {{ $candidate->status === 'rejected' ? 'selected' : '' }}>Tidak Lolos (Rejected)</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Catatan Evaluasi Coach / Analyst</label>
                        <textarea name="reviewer_notes" class="form-textarea" rows="3" placeholder="Contoh: Mekanik bagus, pemahaman makro stabil, siap lanjut wawancara...">{{ old('reviewer_notes', $candidate->reviewer_notes) }}</textarea>
                    </div>

                    {{-- Status Pengiriman Email Keputusan --}}
                    <div class="form-field" style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--rok-surface-border); border-radius: 6px; padding: 12px 16px; margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="font-size: 13px; color: #e2e8f0;">STATUS EMAIL KEPUTUSAN:</strong>
                            @if ($candidate->decision_email_sent_at)
                                <span class="badge badge--success" style="padding: 2px 8px; background: rgba(34, 197, 94, 0.2); color: #4ade80; border-radius: 4px; font-size: 11px; font-weight: 700;">
                                    &check; TERKIRIM ({{ strtoupper($candidate->decision_email_type) }})
                                </span>
                            @else
                                <span class="badge" style="padding: 2px 8px; background: rgba(148, 163, 184, 0.2); color: #94a3b8; border-radius: 4px; font-size: 11px;">
                                    BELUM TERKIRIM
                                </span>
                            @endif
                        </div>
                        @if ($candidate->decision_email_sent_at)
                            <div style="font-size: 12px; color: #94a3b8;">
                                Terkirim pada: <strong>{{ $candidate->decision_email_sent_at->format('d M Y, H:i') }} WIB</strong> ke <code>{{ $candidate->email }}</code>
                            </div>
                        @endif
                        @if ($candidate->next_stage_token)
                            <div style="font-size: 12px; color: #38bdf8; margin-top: 6px; word-break: break-all;">
                                Tautan Tahap Lanjutan: <a href="{{ $candidate->next_stage_url }}" target="_blank" style="color: #38bdf8; text-decoration: underline;">{{ $candidate->next_stage_url }}</a>
                            </div>
                        @endif
                    </div>

                    <div class="form-field form-field--checkbox">
                        <label class="checkbox-label">
                            <input type="checkbox" name="send_decision_email" value="1" {{ !$candidate->decision_email_sent_at ? 'checked' : '' }}>
                            <span>Kirim email resmi keputusan seleksi ke alamat email kandidat</span>
                        </label>
                    </div>

                    <div class="form-field form-field--checkbox">
                        <label class="checkbox-label">
                            <input type="checkbox" name="contract_completed" value="1" {{ $candidate->contract_completed ? 'checked' : '' }}>
                            <span>Kontrak Atlet Fisik Telah Selesai Ditandatangani di Luar Sistem</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-rok btn-rok--outline btn-rok--full">
                        Simpan Evaluasi &amp; Kirim Keputusan
                    </button>
                </form>
            </div>

            {{-- PROMOTE TO ROSTER ACTION --}}
            <div class="mgmt-card mt-4 mgmt-card--highlight">
                <div class="mgmt-card__header">
                    <h3 style="display:inline-flex;align-items:center;gap:6px;"><i data-lucide="crown" style="width:16px;height:16px;"></i> INTEGRASI KE ROSTER TIM AKTIF</h3>
                </div>
                @if ($candidate->created_player_id)
                    <div class="alert-box alert-box--success">
                        <strong>KANDIDAT INI SUDAH MENJADI ANGGOTA ROSTER AKTIF!</strong>
                        <p>Terdaftar sebagai: <strong>{{ $candidate->createdPlayer?->nickname }}</strong> ({{ $candidate->createdPlayer?->role }}) di divisi {{ $candidate->createdPlayer?->division?->name }}.</p>
                    </div>
                @else
                    <p class="text-sm text-muted mb-3">
                        Jika kandidat dinyatakan lolos dan kontrak fisik telah selesai, pindahkan kandidat ini menjadi pemain resmi yang tampil di halaman publik Teams.
                    </p>
                    <form method="POST" action="{{ route('management.candidates.promote', $candidate) }}" onsubmit="return confirm('Pindahkan kandidat ini menjadi pemain resmi tim?')">
                        @csrf
                        <div class="form-row-2">
                            <div class="form-field">
                                <label class="form-label">Pilih Divisi Tim</label>
                                <select name="division_id" class="form-select" required>
                                    @foreach ($divisions as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Role dalam Roster</label>
                                <select name="role" class="form-select" required>
                                    <option value="{{ $candidate->role ?? 'Jungler' }}">{{ $candidate->role ?? 'Jungler' }} (Role Pilihan)</option>
                                    <option value="Gold Laner">Gold Laner</option>
                                    <option value="Mid Laner">Mid Laner</option>
                                    <option value="Jungler">Jungler</option>
                                    <option value="EXP Laner">EXP Laner</option>
                                    <option value="Roamer">Roamer</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Coach">Coach</option>
                                    <option value="Analyst">Analyst</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn-rok btn-rok--gold btn-rok--full mt-2" style="display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                            <i data-lucide="zap" style="width:14px;height:14px;"></i> Pindahkan ke Roster Aktif &rarr;
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
