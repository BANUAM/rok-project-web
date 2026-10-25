@extends('layouts.management')

@section('title', 'Monitoring Turnamen & Bracket | ROK Management')
@section('header_title', 'MONITORING TURNAMEN &amp; BRACKET')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <p class="text-muted">Pantau data turnamen aktif, bagan pertandingan, status tim, dan hasil score turnamen.</p>
        <a href="{{ url('/bracket') }}" target="_blank" class="btn-rok btn-rok--outline">
            Buka Simulator Bracket Publik &rarr;
        </a>
    </div>

    @forelse ($tournaments as $tourney)
        <div class="mgmt-card mb-4">
            <div class="mgmt-card__header">
                <div>
                    <span class="status-live-badge" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="radio" style="width:12px;height:12px;"></i> {{ strtoupper($tourney->status) }}</span>
                    <h3 class="mt-1">{{ $tourney->name }}</h3>
                    <div class="text-muted text-xs">Penyelenggara: {{ $tourney->organizer_name }} &bull; Format: {{ ucwords(str_replace('_', ' ', $tourney->format)) }}</div>
                </div>
                <div class="text-right">
                    <span class="badge-mini badge-mini--gold">{{ $tourney->tier_required ? strtoupper($tourney->tier_required) . ' TIER' : 'FREE' }}</span>
                </div>
            </div>

            {{-- Teams in Tournament --}}
            <div class="tourney-teams-strip mt-3">
                <h4 class="text-xs text-muted mb-2">TIM PESERTA ({{ $tourney->teams->count() }} TIM):</h4>
                <div class="teams-tags-wrap">
                    @foreach ($tourney->teams as $t)
                        <span class="team-tag-pill">
                            <span class="text-gold">#{{ $t->seed }}</span> {{ $t->team_name }}
                            @if ($t->whatsapp_number)
                                <a href="https://wa.me/{{ $t->whatsapp_number }}" target="_blank" class="text-xs ml-1" title="Hubungi WhatsApp" style="display:inline-flex;align-items:center;vertical-align:middle;">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="color:#25D366;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7.02 8.48 7.02 9.69C7.02 10.9 7.9 12.07 8.02 12.23C8.14 12.4 9.75 14.9 12.24 15.96C14.31 16.85 14.73 16.67 15.18 16.63C15.64 16.59 16.64 16.03 16.85 15.45C17.05 14.86 17.05 14.36 16.99 14.26C16.93 14.16 16.77 14.1 16.52 13.97C16.27 13.85 15.06 13.25 14.83 13.17C14.61 13.09 14.45 13.05 14.28 13.29C14.12 13.54 13.65 14.1 13.51 14.26C13.37 14.43 13.22 14.45 12.98 14.33C12.73 14.2 11.93 13.94 10.99 13.1C10.25 12.44 9.75 11.63 9.61 11.39C9.46 11.14 9.59 11.01 9.72 10.88C9.83 10.77 9.97 10.59 10.1 10.43C10.22 10.28 10.26 10.16 10.34 9.99C10.42 9.83 10.38 9.69 10.32 9.56C10.26 9.44 9.79 8.28 9.59 7.81C9.4 7.36 9.2 7.42 9.05 7.41C8.91 7.41 8.75 7.41 8.59 7.41L8.53 7.33Z"/></svg>
                                </a>
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Matches List --}}
            <div class="table-responsive mt-3">
                <table class="mgmt-table">
                    <thead>
                        <tr>
                            <th>Babak</th>
                            <th>Pertandingan</th>
                            <th>Skor</th>
                            <th>Pemenang</th>
                            <th>Status Match</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tourney->matches as $match)
                            <tr>
                                <td><strong>{{ $match->round_name }}</strong></td>
                                <td>
                                    <span>{{ $match->team1->team_name ?? 'TBD' }}</span>
                                    <span class="text-muted px-2">vs</span>
                                    <span>{{ $match->team2->team_name ?? 'TBD' }}</span>
                                </td>
                                <td>
                                    @if ($match->score1 !== null && $match->score2 !== null)
                                        <strong class="text-gold">{{ $match->score1 }} - {{ $match->score2 }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($match->winner)
                                        <strong class="text-gold" style="display:inline-flex;align-items:center;gap:4px;"><i data-lucide="trophy" style="width:13px;height:13px;"></i> {{ $match->winner->team_name }}</strong>
                                    @else
                                        <span class="text-muted">Belum Ditentukan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-badge--{{ $match->status }}">
                                        {{ ucfirst($match->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada pertandingan dijadwalkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="empty-state-box">
            <p>Belum ada turnamen terdaftar di sistem.</p>
        </div>
    @endforelse
</div>
@endsection
