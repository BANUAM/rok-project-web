@extends('layouts.management')

@section('title', 'Dashboard Overview | ROK Management')
@section('header_title', 'DASHBOARD OVERVIEW')

@section('content')
<div class="mgmt-dashboard">
    {{-- Metrics Cards --}}
    <div class="metrics-grid">
        <div class="metric-card metric-card--gold">
            <div class="metric-icon">&#x1F4CB;</div>
            <div class="metric-body">
                <span class="metric-label">TOTAL KANDIDAT TRIAL</span>
                <strong class="metric-value">{{ $metrics['candidates_count'] }}</strong>
                <span class="metric-sub">{{ $metrics['candidates_pending'] }} Menunggu Evaluasi</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">&#x1F3AE;</div>
            <div class="metric-body">
                <span class="metric-label">ATLET ROSTER AKTIF</span>
                <strong class="metric-value">{{ $metrics['players_count'] }}</strong>
                <span class="metric-sub">{{ $metrics['divisions_count'] }} Divisi Kompetitif</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">&#x1F3C6;</div>
            <div class="metric-body">
                <span class="metric-label">TROFI &amp; PRESTASI</span>
                <strong class="metric-value">{{ $metrics['achievements_count'] }}</strong>
                <span class="metric-sub">Pencapaian Organisasi</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">&#x1F91D;</div>
            <div class="metric-body">
                <span class="metric-label">OFFICIAL PARTNERS</span>
                <strong class="metric-value">{{ $metrics['partners_count'] }}</strong>
                <span class="metric-sub">Sponsor &amp; Media</span>
            </div>
        </div>
    </div>

    {{-- Quick Action Cards --}}
    <div class="quick-actions-bar">
        <a href="{{ route('management.candidates') }}" class="quick-action-btn">
            <span>&#x1F50D;</span> Evaluasi Calon Pemain
        </a>
        <a href="{{ route('management.players') }}" class="quick-action-btn">
            <span>&#x2795;</span> Tambah Roster Tim
        </a>
        <a href="{{ route('management.hero-slides') }}" class="quick-action-btn">
            <span>&#x1F5BC;</span> Kelola Banner Home
        </a>
        <a href="{{ route('management.achievements') }}" class="quick-action-btn">
            <span>&#x1F3C6;</span> Tambah Prestasi
        </a>
        <a href="{{ route('management.lsm') }}" class="quick-action-btn">
            <span>&#x1F9E0;</span> Konfigurasi Tes LSM
        </a>
    </div>

    {{-- 2 Columns Section: Latest Candidates & Division Summary --}}
    <div class="dashboard-tables-grid">
        {{-- Candidates Table --}}
        <div class="mgmt-card">
            <div class="mgmt-card__header">
                <h3>KANDIDAT TRIAL TERBARU</h3>
                <a href="{{ route('management.candidates') }}" class="card-link">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="mgmt-table">
                    <thead>
                        <tr>
                            <th>Kandidat</th>
                            <th>Role &amp; Rank</th>
                            <th>Status</th>
                            <th>Nilai LSM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestCandidates as $candidate)
                            <tr>
                                <td>
                                    <strong>{{ $candidate->name }}</strong>
                                    <div class="text-muted text-xs">IGN: {{ $candidate->nickname ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge-tag">{{ $candidate->role ?? '-' }}</span>
                                    <div class="text-xs">{{ $candidate->rank_display }}</div>
                                </td>
                                <td>
                                    <span class="status-badge status-badge--{{ $candidate->status }}">
                                        {{ $candidate->status_label }}
                                    </span>
                                </td>
                                <td>
                                    @if ($candidate->lsmAttempt)
                                        <strong>{{ number_format($candidate->lsmAttempt->score, 1) }}%</strong>
                                        <div class="text-xs">{{ $candidate->lsmAttempt->is_passed ? 'Lulus' : 'Di Bawah Ambang' }}</div>
                                    @else
                                        <span class="text-muted">Belum Tes</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('management.candidates.detail', $candidate) }}" class="btn-sm btn-sm--outline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada kandidat trial yang mendaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Division Rosters Overview --}}
        <div class="mgmt-card">
            <div class="mgmt-card__header">
                <h3>DIVISI KOMPETITIF</h3>
                <a href="{{ route('management.divisions') }}" class="card-link">Kelola Divisi &rarr;</a>
            </div>
            <div class="division-summary-list">
                @foreach ($activeDivisions as $div)
                    <div class="div-item-card">
                        <div class="div-item-left">
                            <img src="{{ $div->icon_url }}" alt="" class="div-avatar">
                            <div>
                                <strong>{{ $div->name }}</strong>
                                <div class="text-muted text-xs">{{ $div->game_name }}</div>
                            </div>
                        </div>
                        <div class="div-item-right">
                            <span class="count-badge">{{ $div->players_count }} Roster</span>
                            <a href="{{ route('management.players', ['division_id' => $div->id]) }}" class="btn-sm btn-sm--ghost">
                                Buka
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
