@extends('layouts.bracket-studio')

@section('title', 'Live Editor: ' . $tournament->name . ' | ROK Bracket Studio')
@section('header_title', 'LIVE BRACKET EDITOR — ' . strtoupper($tournament->name))

@section('content')
<div class="mgmt-page-deck">
    {{-- Switcher Bar between User's Tournaments --}}
    <div class="mgmt-card mb-4" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding: 12px 18px; border-radius: 12px; background: rgba(15, 23, 42, 0.55); border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase; color: var(--rok-muted-light);">PILIH TURNAMEN:</span>
            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                @foreach ($allTournaments as $item)
                    <a
                        href="{{ route('bracket.studio.editor', ['slug' => $item->slug]) }}"
                        class="btn-rok btn-rok--sm {{ $item->id === $tournament->id ? 'btn-rok--gold' : 'btn-rok--outline' }}"
                        style="border-radius: 999px; padding: 4px 12px; font-size: 12px;"
                    >
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>
        </div>
        <a href="{{ route('bracket.studio.index') }}" class="btn-rok btn-rok--outline btn-rok--sm" style="border-radius: 999px; padding: 4px 12px; font-size: 12px; display:inline-flex;align-items:center;gap:5px;">
            <i data-lucide="plus" style="width:12px;height:12px;"></i> Buat Turnamen
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
