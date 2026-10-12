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
                    <span class="status-live-badge">&#x1F534; {{ strtoupper($tourney->status) }}</span>
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
                                <a href="https://wa.me/{{ $t->whatsapp_number }}" target="_blank" class="text-xs ml-1" title="Hubungi WhatsApp">&#x1F4AC;</a>
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
                                        <strong class="text-gold">&#x1F3C6; {{ $match->winner->team_name }}</strong>
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
