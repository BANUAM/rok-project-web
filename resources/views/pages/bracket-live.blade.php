@extends('layouts.app')

@section('title', $tournament->name . ' — Live Real-Time Bracket | ROK Web')
@section('description', 'Pantau bagan turnamen ' . $tournament->name . ' secara real-time, lihat poin pertandingan, dan hubungi tim lawan melalui WhatsApp.')

@php
    $isFree = ($tournament->tier_required === 'free' || str_starts_with($tournament->slug, 'rok-free-'));
@endphp

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge">&#x1F534; LIVE REAL-TIME SHARED BRACKET</span>
            <h1 class="page-title">{{ $tournament->name }}</h1>
            <p class="page-subtitle-gold">
                Diselenggarakan oleh: {{ $tournament->organizer_name }} &bull; Real-Time Sync Aktif
            </p>
            <p class="page-subtitle">
                Halaman ini terhubung secara real-time. Ketika penyelenggara memperbarui skor atau tim, bagan di layar Anda akan otomatis berubah tanpa perlu me-refresh halaman. Klik <strong>{{ $isFree ? 'Nama Tim' : 'Logo Tim' }}</strong> untuk melihat <strong>Poin Pertandingan</strong> dan <strong>Chat WhatsApp Tim</strong>.
            </p>

            @if ($canEdit)
                <div style="margin-top: 18px;">
                    <a href="{{ route('bracket.studio.editor', ['slug' => $tournament->slug]) }}" class="btn-rok btn-rok--gold">
                        &#x2694; Kelola Turnamen Ini di Bracket Studio
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="generator-tool-section" style="padding-top: 24px;">
        <div class="section-inner">
            @include('components.bracket-engine', [
                'activeTournament' => $tournament,
                'tournamentSlug' => $tournament->slug,
                'initialBracketState' => $tournament->bracket_state,
                'readOnly' => true,
                'unlockLogos' => !$isFree,
                'isFreePage' => $isFree,
            ])
        </div>
    </section>
</div>
@endsection
