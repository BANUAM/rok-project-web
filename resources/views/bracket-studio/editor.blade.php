@extends('layouts.bracket-studio')

@section('title', 'Live Editor: ' . $tournament->name . ' | ROK Bracket Studio')
@section('header_title', 'LIVE BRACKET EDITOR — ' . strtoupper($tournament->name))

@section('content')
<div class="mgmt-page-deck">
    {{-- Switcher Bar between User's Tournaments --}}
    <div class="mgmt-card mb-4" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding: 16px 22px;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <span style="font-size: 13px; font-weight: 700; color: var(--rok-muted-light);">PILIH TURNAMEN AKTIF:</span>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach ($allTournaments as $item)
                    <a
                        href="{{ route('bracket.studio.editor', ['slug' => $item->slug]) }}"
                        class="btn-rok btn-rok--sm {{ $item->id === $tournament->id ? 'btn-rok--gold' : 'btn-rok--outline' }}"
                    >
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>
        </div>
        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--outline btn-rok--sm">
            &#x2795; Buat Turnamen Baru
        </a>
    </div>

    {{-- Full Interactive Real-Time Bracket Engine with Logo & WhatsApp Unlocked --}}
    @include('components.bracket-engine', [
        'activeTournament' => $tournament,
        'tournamentSlug' => $tournament->slug,
        'initialBracketState' => $tournament->bracket_state,
        'readOnly' => false,
        'unlockLogos' => true,
    ])
</div>
@endsection
