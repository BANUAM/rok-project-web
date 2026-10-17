@extends('layouts.bracket-studio')

@section('title', 'Daftar Turnamen & Bracket Studio | ROK Esports')
@section('header_title', 'BRACKET STUDIO — WORKSPACE KHUSUS ORGANIZER (' . strtoupper($tierSlug) . ')')

@section('content')
<div class="mgmt-page-deck">
    {{-- Welcome & Membership Status Banner --}}
    <div class="mgmt-card mb-4" style="border-left: 4px solid #38bdf8;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge-mini" style="margin-bottom: 6px; display: inline-block;">&#x1F451; MEMBERSHIP AKTIF: ROK {{ strtoupper($tierSlug) }}</span>
                <h3 style="font-size: 20px; margin-bottom: 4px;">Selamat Datang di Workspace Khusus Bracket Turnamen, {{ Auth::user()->name }}!</h3>
                <p class="text-sm text-muted" style="margin: 0;">
                    Kelola bagan pertandingan secara real-time, unggah logo tim resmi, hubungkan nomor WhatsApp setiap tim lawan tanding, dan bagikan tautan live bracket ke seluruh peserta.
                </p>
            </div>
            <a href="{{ route('bracket.studio.editor') }}" class="btn-rok btn-rok--gold">
                &#x2694; Buka Live Bracket Editor
            </a>
        </div>
    </div>

    <div class="mgmt-grid-2">
        {{-- Create New Tournament Card --}}
        <div class="mgmt-card">
            <div class="mgmt-card__header">
                <h3>&#x2795; BUAT TURNAMEN LIVE BARU</h3>
            </div>
            <form method="POST" action="{{ route('bracket.studio.store') }}">
                @csrf
                <div class="form-field">
                    <label class="form-label">Nama Turnamen</label>
                    <input type="text" name="name" class="form-input" placeholder="Contoh: ROK Immortal Championship Season 2" required>
                </div>

                <div class="form-field">
                    <label class="form-label">Format Pertandingan</label>
                    <select name="format" class="form-select" required>
                        <option value="single">Single Elimination (3–64 Tim, Otomatis Bye)</option>
                        <option value="double">Double Elimination (Format MPL 4 / 6 Tim)</option>
                        <option value="league">League System (Klasemen &amp; Poin)</option>
                        <option value="swiss">Swiss Stage (Format M7 MLBB 16 Tim)</option>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-label">Jumlah Tim Peserta</label>
                    <input type="number" name="team_count" class="form-input" min="3" max="64" value="8" required>
                </div>

                <div class="form-field">
                    <label class="form-label">Deskripsi Singkat Turnamen</label>
                    <textarea name="description" class="form-textarea" rows="2" placeholder="Informasi hadiah, jadwal, atau aturan singkat..."></textarea>
                </div>

                <button type="submit" class="btn-rok btn-rok--gold btn-rok--full">
                    Buat Turnamen &amp; Buka Studio
                </button>
            </form>
        </div>

        {{-- Existing Tournaments List --}}
        <div class="mgmt-card">
            <div class="mgmt-card__header">
                <h3>&#x1F3C6; DAFTAR TURNAMEN LIVE ANDA ({{ $tournaments->count() }})</h3>
            </div>

            <div style="display: grid; gap: 14px;">
                @foreach ($tournaments as $tourney)
                    @php
                        $shareLink = route('bracket.live', ['slug' => $tourney->slug]);
                    @endphp
                    <div class="studio-tourney-item">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap;">
                            <div>
                                <span class="badge-mini" style="text-transform: uppercase;">{{ str_replace('_', ' ', $tourney->format) }} &bull; {{ $tourney->team_count ?: 8 }} TIM</span>
                                <h4 style="font-size: 17px; margin: 4px 0;">{{ $tourney->name }}</h4>
                                <p class="text-sm text-muted" style="margin: 0 0 8px 0;">
                                    Terakhir diperbarui: {{ $tourney->updated_at?->diffForHumans() ?? 'Baru saja' }}
                                </p>
                            </div>
                            <span class="status-live-badge" style="font-size: 11px; padding: 4px 10px;">&#x1F534; LIVE REAL-TIME</span>
                        </div>

                        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-top: 8px;">
                            <a href="{{ route('bracket.studio.editor', ['slug' => $tourney->slug]) }}" class="btn-rok btn-rok--gold btn-rok--sm">
                                &#x270E; Kelola &amp; Edit Skor Live
                            </a>
                            <a href="{{ $shareLink }}" target="_blank" class="btn-rok btn-rok--outline btn-rok--sm">
                                &#x1F517; Buka Link Share Penonton &nearr;
                            </a>
                            <button type="button" class="btn-rok btn-rok--outline btn-rok--sm" onclick="navigator.clipboard.writeText('{{ $shareLink }}'); this.textContent='✓ Link Tersalin!';">
                                &#x1F4CB; Copy Link
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
