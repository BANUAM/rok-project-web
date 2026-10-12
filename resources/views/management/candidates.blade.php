@extends('layouts.management')

@section('title', 'Kandidat Trial & Rekrutmen | ROK Management')
@section('header_title', 'DATA KANDIDAT TRIAL ATLET')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <form method="GET" action="{{ route('management.candidates') }}" class="inline-filter-form">
            <label class="filter-label">Filter Status:</label>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status Seleksi</option>
                <option value="registered" {{ request('status') === 'registered' ? 'selected' : '' }}>Pendaftaran Awal</option>
                <option value="profile_completed" {{ request('status') === 'profile_completed' ? 'selected' : '' }}>Profil Terisi</option>
                <option value="lsm_completed" {{ request('status') === 'lsm_completed' ? 'selected' : '' }}>LSM Selesai</option>
                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Sedang Ditinjau Coach</option>
                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Lolos Seleksi (Passed)</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Tidak Lolos (Rejected)</option>
            </select>
        </form>

        <span class="text-muted text-sm">
            Total: <strong>{{ $candidates->count() }}</strong> Kandidat Terdaftar
        </span>
    </div>

    <div class="mgmt-card">
        <div class="table-responsive">
            <table class="mgmt-table">
                <thead>
                    <tr>
                        <th>Kandidat</th>
                        <th>Kontak</th>
                        <th>Role &amp; Rank</th>
                        <th>Nilai LSM</th>
                        <th>Status Seleksi</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($candidates as $cand)
                        <tr>
                            <td>
                                <strong>{{ $cand->name }}</strong>
                                <div class="text-xs text-muted">IGN: <strong>{{ $cand->nickname ?? 'Belum diisi' }}</strong></div>
                            </td>
                            <td>
                                <div class="text-xs">{{ $cand->email }}</div>
                                <div class="text-xs text-muted">Token: <code>{{ substr($cand->access_token, 0, 10) }}...</code></div>
                            </td>
                            <td>
                                <span class="badge-tag">{{ $cand->role ?? '-' }}</span>
                                <div class="text-xs">{{ $cand->rank_display }}</div>
                            </td>
                            <td>
                                @if ($cand->lsmAttempt)
                                    <strong>{{ number_format($cand->lsmAttempt->score, 1) }}%</strong>
                                    <div class="text-xs {{ $cand->lsmAttempt->is_passed ? 'text-gold' : 'text-danger' }}">
                                        {{ $cand->lsmAttempt->correct_answers }} / {{ $cand->lsmAttempt->total_questions }} Benar
                                    </div>
                                @else
                                    <span class="text-muted text-xs">Belum Mengerjakan</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-badge--{{ $cand->status }}">
                                    {{ $cand->status_label }}
                                </span>
                            </td>
                            <td>
                                <span class="text-xs">{{ $cand->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td>
                                <a href="{{ route('management.candidates.detail', $cand) }}" class="btn-sm btn-sm--gold">
                                    Review &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada data kandidat untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
