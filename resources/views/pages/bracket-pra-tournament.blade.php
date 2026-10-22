@extends('layouts.app')

@section('title', 'Pra-Turnamen & Check-in Tim | ' . $tournament->name . ' | ROK Web')
@section('description', 'Portal pendaftaran mandiri kapten tim turnamen ' . $tournament->name . '. Masukkan nama tim, nomor WhatsApp, dan logo tim ke slot bagan pertandingan.')

@section('content')
<div class="page-container pra-tournament-page" id="praTourneyApp" data-slug="{{ $tournament->slug }}" data-has-logo="{{ $hasLogoAccess ? '1' : '0' }}">
    {{-- Hero Section --}}
    <section class="page-hero">
        <div class="page-hero__inner">
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                <span class="section-badge" style="background: rgba(56, 189, 248, 0.15); border: 1px solid #38bdf8; color: #38bdf8;">
                    &#x1F4CB; PORTAL PRA-TURNAMEN &amp; CHECK-IN TIM
                </span>
                <span class="realtime-pulse-dot" id="praRealtimeDot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 10px #22c55e;"></span>
                <span style="font-size: 12px; color: #4ade80; font-weight: 700;">Live Auto-Sync</span>
            </div>

            <h1 class="page-title">{{ $tournament->name }}</h1>
            <p class="page-subtitle-gold">
                Diselenggarakan oleh: {{ $tournament->organizer_name }} &bull; Format: {{ strtoupper($tournament->format ?? 'Single Elimination') }}
            </p>
            <p class="page-subtitle" style="max-width: 760px; margin: 0 auto 20px auto;">
                Selamat datang di sesi Pra-Turnamen. Kapten tim dapat memilih <strong>1 slot kosong</strong> yang tersedia untuk memasukkan Nama Tim, Kontak WhatsApp resmi, dan Logo Tim. Setiap kapten hanya diperbolehkan mengklaim 1 slot. Penyelenggara dapat mengacak (reshuffle) urutan tim sebelum kick-off turnamen.
            </p>

            {{-- Quick Stats Bar --}}
            <div class="pra-stats-bar" style="display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; margin-top: 18px;">
                <div class="pra-stat-pill" style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); padding: 8px 16px; border-radius: 20px; font-size: 13px;">
                    <span style="color: var(--rok-muted-light);">Total Kuota:</span>
                    <strong id="statTotalSlots" style="color: var(--rok-silver);">{{ $totalSlots }} Tim</strong>
                </div>
                <div class="pra-stat-pill" style="background: var(--rok-surface-card); border: 1px solid rgba(34, 197, 94, 0.35); padding: 8px 16px; border-radius: 20px; font-size: 13px;">
                    <span style="color: var(--rok-muted-light);">Slot Terisi:</span>
                    <strong id="statClaimedCount" style="color: #4ade80;">{{ $claimedCount }}</strong>
                </div>
                <div class="pra-stat-pill" style="background: var(--rok-surface-card); border: 1px solid rgba(56, 189, 248, 0.35); padding: 8px 16px; border-radius: 20px; font-size: 13px;">
                    <span style="color: var(--rok-muted-light);">Slot Tersedia:</span>
                    <strong id="statVacantCount" style="color: #38bdf8;">{{ max(0, $totalSlots - $claimedCount) }}</strong>
                </div>
            </div>

            {{-- Top Action Buttons --}}
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap; margin-top: 22px;">
                <button type="button" class="btn-rok btn-rok--outline btn-rok--sm" id="btnCopyPraLink" style="border-radius: 20px; padding: 8px 18px; border-color: rgba(56, 189, 248, 0.5); color: #38bdf8; font-weight: 700;">
                    &#x1F4CB; Salin Link Pra-Turnamen Ini
                </button>
                <a href="{{ $shareUrl }}" target="_blank" class="btn-rok btn-rok--outline btn-rok--sm" style="border-radius: 20px; padding: 8px 18px;">
                    &#x1F517; Lihat Live Bracket &nearr;
                </a>
                @if ($canEdit)
                    <a href="{{ route('bracket.studio.editor', ['slug' => $tournament->slug]) }}" class="btn-rok btn-rok--gold btn-rok--sm" style="border-radius: 20px; padding: 8px 18px;">
                        &#x2694; Kelola Master di Bracket Studio
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- User's Claimed Slot Alert (Shown if this device has already claimed a slot) --}}
    <div class="section-inner" id="myClaimedBanner" style="display: none; margin-bottom: 24px;">
        <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid #22c55e; border-radius: 12px; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 24px;">&#x2705;</span>
                <div>
                    <strong style="color: #4ade80; font-size: 14px; display: block;">Perangkat Anda Telah Melakukan Check-in Turnamen</strong>
                    <span style="font-size: 13px; color: var(--rok-silver);" id="myClaimedDetails">
                        Tim Anda telah terdaftar dan slot Anda terkunci secara aman.
                    </span>
                </div>
            </div>
            <span style="font-size: 12px; padding: 4px 10px; background: rgba(34,197,94,0.25); color: #86efac; border-radius: 20px; font-weight: 700;">
                1 Tim Per Perangkat
            </span>
        </div>
    </div>

    {{-- Interactive Slots Grid Section --}}
    <section class="pra-slots-section" style="padding-bottom: 36px;">
        <div class="section-inner">
            <div class="section-header" style="text-align: center; margin-bottom: 24px;">
                <h2 style="font-size: 24px; font-family: var(--font-heading); margin-bottom: 6px;">
                    Daftar Slot Peserta Turnamen
                </h2>
                <p style="font-size: 13px; color: var(--rok-muted-light); margin: 0;">
                    Pilih slot yang kosong untuk mengklaim tempat tim Anda. Slot yang telah terdaftar berwarna hijau dan terkunci.
                </p>
            </div>

            @php
                $teamsList = $tournament->bracket_state['teams'] ?? [];
            @endphp

            <div class="pra-slots-grid" id="praSlotsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
                @for ($i = 0; $i < $totalSlots; $i++)
                    @php
                        $team = $teamsList[$i] ?? null;
                        $isClaimed = !empty($team['is_claimed']);
                        $teamName = $team['name'] ?? ('Slot #' . ($i + 1));
                        $teamWa = $team['whatsapp'] ?? ($team['wa'] ?? '');
                        $teamLogo = $team['logo'] ?? '/storage/tournaments/team-rok.svg';
                    @endphp

                    <div class="pra-slot-card {{ $isClaimed ? 'pra-slot-card--claimed' : 'pra-slot-card--vacant' }}"
                         id="slot_card_{{ $i }}"
                         data-slot="{{ $i }}"
                         data-claimed="{{ $isClaimed ? '1' : '0' }}"
                         style="background: var(--rok-surface-card); border: 1px solid {{ $isClaimed ? 'rgba(34, 197, 94, 0.4)' : 'var(--rok-surface-border)' }}; border-radius: 12px; padding: 18px; position: relative; transition: all 0.25s ease;">
                        
                        {{-- Top Slot Badge --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                            <span style="font-size: 12px; font-weight: 800; font-family: var(--font-sub); color: var(--rok-gold);">
                                SLOT #{{ $i + 1 }}
                            </span>
                            @if ($isClaimed)
                                <span class="badge-claimed" style="font-size: 11px; padding: 3px 8px; border-radius: 20px; background: rgba(34, 197, 94, 0.16); border: 1px solid #22c55e; color: #4ade80; font-weight: 700;">
                                    &#x2713; TERDAFTAR
                                </span>
                            @else
                                <span class="badge-vacant" style="font-size: 11px; padding: 3px 8px; border-radius: 20px; background: rgba(56, 189, 248, 0.12); border: 1px solid rgba(56, 189, 248, 0.4); color: #38bdf8; font-weight: 700;">
                                    &#x25CB; TERSEDIA
                                </span>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; overflow: hidden; border: 2px solid {{ $isClaimed ? '#22c55e' : 'var(--rok-surface-border)' }}; flex-shrink: 0; background: #0f172a; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ $teamLogo }}" alt="{{ $teamName }}" class="slot-logo-img" style="width: 100%; height: 100%; object-fit: cover; {{ $isClaimed ? '' : 'opacity: 0.4;' }}" onerror="this.src='/storage/tournaments/team-rok.svg'">
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <h4 class="slot-team-name" style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: {{ $isClaimed ? '#fff' : 'var(--rok-muted-light)' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $isClaimed ? $teamName : 'Slot Kosong' }}
                                </h4>
                                @if ($isClaimed)
                                    <span style="font-size: 12px; color: #4ade80; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        &#x1F4F1; {{ !empty($teamWa) ? substr($teamWa, 0, 4) . '****' . substr($teamWa, -3) : 'WhatsApp Terdata' }}
                                    </span>
                                @else
                                    <span style="font-size: 12px; color: var(--rok-muted-light); display: block;">
                                        Belum ada tim yang mendaftar
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Action / Status --}}
                        <div class="slot-card-actions">
                            @if ($isClaimed)
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                    <span style="font-size: 11px; color: var(--rok-muted-light);">
                                        &#x1F512; Slot Terkunci
                                    </span>
                                    @if (!empty($teamWa))
                                        <a href="https://wa.me/{{ $teamWa }}" target="_blank" rel="noopener noreferrer" class="btn-rok btn-rok--outline btn-rok--sm" style="font-size: 11px; padding: 4px 10px; border-radius: 20px; border-color: rgba(37, 211, 102, 0.4); color: #25D366; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7.02 8.48 7.02 9.69C7.02 10.9 7.9 12.07 8.02 12.23C8.14 12.4 9.75 14.9 12.24 15.96C14.31 16.85 14.73 16.67 15.18 16.63C15.64 16.59 16.64 16.03 16.85 15.45C17.05 14.86 17.05 14.36 16.99 14.26C16.93 14.16 16.77 14.1 16.52 13.97C16.27 13.85 15.06 13.25 14.83 13.17C14.61 13.09 14.45 13.05 14.28 13.29C14.12 13.54 13.65 14.1 13.51 14.26C13.37 14.43 13.22 14.45 12.98 14.33C12.73 14.2 11.93 13.94 10.99 13.1C10.25 12.44 9.75 11.63 9.61 11.39C9.46 11.14 9.59 11.01 9.72 10.88C9.83 10.77 9.97 10.59 10.1 10.43C10.22 10.28 10.26 10.16 10.34 9.99C10.42 9.83 10.38 9.69 10.32 9.56C10.26 9.44 9.79 8.28 9.59 7.81C9.4 7.36 9.2 7.42 9.05 7.41C8.91 7.41 8.75 7.41 8.59 7.41L8.53 7.33Z"/></svg>
                                            <span>Chat WA</span>
                                        </a>
                                    @endif
                                </div>
                            @else
                                <button type="button" class="btn-rok btn-rok--gold btn-rok--full btn-claim-slot" data-slot="{{ $i }}" style="border-radius: 20px; font-weight: 700; font-size: 13px; padding: 8px 14px;">
                                    &#x270E; Klaim Slot #{{ $i + 1 }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Live Tournament Bracket View --}}
    <section class="pra-live-bracket-section" style="padding-top: 24px; padding-bottom: 48px; border-top: 1px solid var(--rok-surface-border);">
        <div class="section-inner">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 18px;">
                <div>
                    <h3 style="font-size: 20px; font-family: var(--font-heading); margin: 0 0 4px 0;">
                        Bagan Turnamen Real-Time
                    </h3>
                    <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0;">
                        Setiap perubahan slot otomatis tersinkronisasi. Penyelenggara dapat melakukan Drawing / Acak Bagan secara adil sebelum pertandingan dimulai.
                    </p>
                </div>
            </div>

            @include('components.bracket-engine', [
                'activeTournament' => $tournament,
                'tournamentSlug' => $tournament->slug,
                'initialBracketState' => $tournament->bracket_state,
                'readOnly' => true,
                'unlockLogos' => $hasLogoAccess,
                'showRealtimeBar' => true,
            ])
        </div>
    </section>
</div>

{{-- Modal Claim Slot Tim --}}
<div class="rok-team-modal" id="praClaimModal" hidden aria-modal="true" role="dialog">
    <div class="rok-team-modal__backdrop" id="closeClaimModalBackdrop"></div>
    <div class="rok-team-modal__dialog" style="max-width: 480px;">
        <button type="button" class="rok-team-modal__close" id="closeClaimModalBtn" aria-label="Tutup">&times;</button>
        
        <div style="margin-bottom: 18px;">
            <span class="badge-mini" id="modalSlotBadge" style="background: rgba(56,189,248,0.15); border: 1px solid #38bdf8; color: #38bdf8;">
                SLOT #1
            </span>
            <h3 style="font-size: 19px; font-weight: 800; margin: 6px 0 4px 0;">Check-in &amp; Pendaftaran Tim</h3>
            <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0;">
                Satu kapten hanya dapat mengklaim 1 slot turnamen. Data terkunci setelah dikonfirmasi.
            </p>
        </div>

        <form id="praClaimForm">
            <input type="hidden" id="claimSlotIndex" value="0">

            {{-- Nama Tim --}}
            <div class="form-field" style="margin-bottom: 14px;">
                <label for="claimTeamName" class="form-label" style="font-size: 13px; font-weight: 700;">Nama Tim Resmi *</label>
                <input type="text" id="claimTeamName" class="form-input" placeholder="Contoh: ROK Prime Esports" required minlength="2" maxlength="40" style="font-size: 13px;">
            </div>

            {{-- Nomor WhatsApp --}}
            <div class="form-field" style="margin-bottom: 14px;">
                <label for="claimTeamWa" class="form-label" style="font-size: 13px; font-weight: 700;">Nomor WhatsApp Kapten Tim *</label>
                <input type="tel" id="claimTeamWa" class="form-input" placeholder="Contoh: 081234567890" required minlength="8" maxlength="20" style="font-size: 13px;">
                <span style="font-size: 11px; color: var(--rok-muted-light); display: block; margin-top: 4px;">
                    Nomor WhatsApp digunakan untuk verifikasi dan kontak resmi antar kapten saat pertandingan.
                </span>
            </div>

            {{-- Pilihan Logo Tim --}}
            <div class="form-field" style="margin-bottom: 20px;">
                <label class="form-label" style="font-size: 13px; font-weight: 700;">Logo Tim Peserta</label>
                
                @if ($hasLogoAccess)
                    <div style="margin-bottom: 10px;">
                        <span style="font-size: 11px; color: var(--rok-gold); display: block; margin-bottom: 6px; font-weight: 700;">
                            &#x1F451; Akses Member: Unggah Logo Tim Sendiri (PNG/JPG/SVG)
                        </span>
                        <input type="file" id="claimCustomLogoFile" accept="image/*" class="form-input" style="font-size: 12px; padding: 6px 10px;">
                    </div>
                @else
                    <span style="font-size: 11px; color: var(--rok-silver); display: block; margin-bottom: 6px;">
                        Pilih emblem resmi ROK untuk tim Anda:
                    </span>
                @endif

                {{-- Preset Logo Selector Grid --}}
                <div id="claimPresetLogos" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 8px;">
                    @php
                        $presetLogos = [
                            '/storage/tournaments/team-rok.svg',
                            '/storage/tournaments/team-aether.svg',
                            '/storage/tournaments/team-valkyrie.svg',
                            '/storage/tournaments/team-shadow.svg',
                            '/storage/tournaments/team-apex.svg',
                            '/storage/tournaments/team-wolves.svg',
                            '/storage/tournaments/team-kitsune.svg',
                            '/storage/tournaments/team-eclipse.svg',
                        ];
                    @endphp
                    @foreach ($presetLogos as $idx => $pLogo)
                        <label class="preset-logo-opt" style="cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #0b1120; border: 2px solid {{ $idx === 0 ? 'var(--rok-gold)' : 'var(--rok-surface-border)' }}; border-radius: 8px; padding: 6px; transition: all 0.2s;">
                            <input type="radio" name="claimLogoRadio" value="{{ $pLogo }}" {{ $idx === 0 ? 'checked' : '' }} style="display: none;">
                            <img src="{{ $pLogo }}" alt="Preset Emblem {{ $idx + 1 }}" style="width: 36px; height: 36px; object-fit: contain;">
                        </label>
                    @endforeach
                </div>
            </div>

            <div id="claimErrorMessage" style="display: none; padding: 10px 14px; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 8px; color: #fca5a5; font-size: 12px; margin-bottom: 14px;"></div>

            {{-- Submit Actions --}}
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn-rok btn-rok--outline" id="btnCancelClaim" style="border-radius: 20px; padding: 8px 18px;">
                    Batal
                </button>
                <button type="submit" class="btn-rok btn-rok--gold" id="btnSubmitClaim" style="border-radius: 20px; padding: 8px 22px; font-weight: 700;">
                    Konfirmasi &amp; Kunci Slot
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(() => {
    const SLUG = @json($tournament->slug);
    const CLAIM_API_URL = @json(route('api.bracket.claim-slot', ['slug' => $tournament->slug]));
    const STATE_API_URL = @json(route('api.bracket.state', ['slug' => $tournament->slug]));
    const HAS_LOGO_ACCESS = @json($hasLogoAccess);
    const LOCAL_STORAGE_KEY = 'rok_pra_claimed_slot_' + SLUG;

    let selectedCustomLogoDataUrl = null;
    let isSubmitting = false;

    // Check LocalStorage for previously claimed slot
    function checkMyClaimedSlot() {
        const mySlot = localStorage.getItem(LOCAL_STORAGE_KEY);
        const banner = document.getElementById('myClaimedBanner');
        const details = document.getElementById('myClaimedDetails');

        if (mySlot !== null && mySlot !== undefined) {
            const slotIdx = parseInt(mySlot, 10);
            if (banner && details) {
                banner.style.display = 'block';
                details.textContent = `Anda telah mendaftarkan tim Anda di Slot #${slotIdx + 1}. Satu kapten/perangkat dibatasi 1 slot.`;
            }
            const card = document.getElementById(`slot_card_${slotIdx}`);
            if (card) {
                card.style.borderColor = 'var(--rok-gold)';
                card.style.boxShadow = '0 0 16px rgba(251, 191, 36, 0.2)';
                const badge = card.querySelector('.badge-claimed');
                if (badge) {
                    badge.innerHTML = '&#x2605; TIM ANDA';
                    badge.style.background = 'rgba(251, 191, 36, 0.2)';
                    badge.style.borderColor = 'var(--rok-gold)';
                    badge.style.color = 'var(--rok-gold)';
                }
            }
        }
    }

    function openClaimModal(slotIdx) {
        // Enforce 1 slot per device/captain
        const existingClaim = localStorage.getItem(LOCAL_STORAGE_KEY);
        if (existingClaim !== null && existingClaim !== undefined) {
            alert(`Anda sudah mengklaim Slot #${parseInt(existingClaim, 10) + 1}. Setiap kapten hanya diperbolehkan mendaftarkan 1 tim di turnamen ini.`);
            return;
        }

        const modal = document.getElementById('praClaimModal');
        const badge = document.getElementById('modalSlotBadge');
        const slotInput = document.getElementById('claimSlotIndex');
        const errorDiv = document.getElementById('claimErrorMessage');

        if (slotInput) slotInput.value = slotIdx;
        if (badge) badge.textContent = `SLOT #${slotIdx + 1}`;
        if (errorDiv) {
            errorDiv.style.display = 'none';
            errorDiv.textContent = '';
        }

        if (modal) {
            modal.hidden = false;
        }
    }

    function closeClaimModal() {
        const modal = document.getElementById('praClaimModal');
        if (modal) modal.hidden = true;
    }

    // Radio logo border highlight
    document.querySelectorAll('input[name="claimLogoRadio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.preset-logo-opt').forEach(opt => {
                opt.style.borderColor = 'var(--rok-surface-border)';
            });
            const parent = radio.closest('.preset-logo-opt');
            if (parent) parent.style.borderColor = 'var(--rok-gold)';
            selectedCustomLogoDataUrl = null;
        });
    });

    // Custom logo file reader
    const customFileInput = document.getElementById('claimCustomLogoFile');
    if (customFileInput) {
        customFileInput.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (event) => {
                selectedCustomLogoDataUrl = event.target?.result;
                document.querySelectorAll('.preset-logo-opt').forEach(opt => {
                    opt.style.borderColor = 'var(--rok-surface-border)';
                });
            };
            reader.readAsDataURL(file);
        });
    }

    // Claim form submission
    document.getElementById('praClaimForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (isSubmitting) return;

        const slotIdx = parseInt(document.getElementById('claimSlotIndex')?.value || '0', 10);
        const teamName = (document.getElementById('claimTeamName')?.value || '').trim();
        const teamWa = (document.getElementById('claimTeamWa')?.value || '').trim();
        const errorDiv = document.getElementById('claimErrorMessage');
        const submitBtn = document.getElementById('btnSubmitClaim');

        if (!teamName || teamName.length < 2) {
            if (errorDiv) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Nama tim harus diisi minimal 2 karakter.';
            }
            return;
        }

        if (!teamWa || teamWa.length < 8) {
            if (errorDiv) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Nomor WhatsApp kapten harus valid minimal 8 digit.';
            }
            return;
        }

        // Selected logo
        let finalLogo = selectedCustomLogoDataUrl;
        if (!finalLogo) {
            const checkedRadio = document.querySelector('input[name="claimLogoRadio"]:checked');
            finalLogo = checkedRadio ? checkedRadio.value : '/storage/tournaments/team-rok.svg';
        }

        isSubmitting = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengonfirmasi Slot...';
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(CLAIM_API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({
                    slot_index: slotIdx,
                    team_name: teamName,
                    whatsapp: teamWa,
                    logo: finalLogo
                })
            });

            const data = await res.json();

            if (!res.ok || !data.ok) {
                throw new Error(data.message || 'Gagal mengklaim slot. Silakan coba kembali.');
            }

            // Save claimed slot locally to lock device
            localStorage.setItem(LOCAL_STORAGE_KEY, slotIdx);

            alert(data.message || 'Selamat! Tim Anda berhasil didaftarkan.');
            closeClaimModal();
            window.location.reload();

        } catch (err) {
            if (errorDiv) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = err.message || 'Terjadi kesalahan sistem saat menyimpan slot.';
            }
        } finally {
            isSubmitting = false;
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Konfirmasi & Kunci Slot';
            }
        }
    });

    // Copy pra-turnamen URL button
    document.getElementById('btnCopyPraLink')?.addEventListener('click', () => {
        navigator.clipboard.writeText(window.location.href);
        const btn = document.getElementById('btnCopyPraLink');
        if (btn) {
            const original = btn.textContent;
            btn.textContent = '✓ Link Pra-Turnamen Tersalin!';
            setTimeout(() => { btn.textContent = original; }, 2000);
        }
    });

    // Event listeners
    document.addEventListener('DOMContentLoaded', () => {
        checkMyClaimedSlot();

        document.querySelectorAll('.btn-claim-slot').forEach(btn => {
            btn.addEventListener('click', () => {
                const slot = parseInt(btn.getAttribute('data-slot') || '0', 10);
                openClaimModal(slot);
            });
        });

        document.getElementById('closeClaimModalBtn')?.addEventListener('click', closeClaimModal);
        document.getElementById('closeClaimModalBackdrop')?.addEventListener('click', closeClaimModal);
        document.getElementById('btnCancelClaim')?.addEventListener('click', closeClaimModal);

        // Auto polling every 7 seconds to sync newly claimed slots in real-time
        setInterval(async () => {
            try {
                const res = await fetch(STATE_API_URL, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const json = await res.json();
                if (json && json.bracket_state && json.bracket_state.teams) {
                    const teams = json.bracket_state.teams;
                    let claimedCount = 0;
                    teams.forEach((t, idx) => {
                        if (t.is_claimed) {
                            claimedCount++;
                            const card = document.getElementById(`slot_card_${idx}`);
                            if (card && card.getAttribute('data-claimed') !== '1') {
                                // Slot was just claimed remotely!
                                card.setAttribute('data-claimed', '1');
                                card.className = 'pra-slot-card pra-slot-card--claimed';
                                card.style.borderColor = 'rgba(34, 197, 94, 0.4)';
                                const nameEl = card.querySelector('.slot-team-name');
                                if (nameEl) {
                                    nameEl.textContent = t.name || `Team ${idx + 1}`;
                                    nameEl.style.color = '#fff';
                                }
                                const logoImg = card.querySelector('.slot-logo-img');
                                if (logoImg && t.logo) {
                                    logoImg.src = t.logo;
                                    logoImg.style.opacity = '1';
                                }
                                const actions = card.querySelector('.slot-card-actions');
                                if (actions) {
                                    actions.innerHTML = `
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                            <span style="font-size: 11px; color: var(--rok-muted-light);">&#x1F512; Slot Terkunci</span>
                                            ${t.whatsapp ? `<a href="https://wa.me/${t.whatsapp}" target="_blank" rel="noopener noreferrer" class="btn-rok btn-rok--outline btn-rok--sm" style="font-size: 11px; padding: 4px 10px; border-radius: 20px; border-color: rgba(37, 211, 102, 0.4); color: #25D366; text-decoration: none;">Chat WA</a>` : ''}
                                        </div>
                                    `;
                                }
                            }
                        }
                    });

                    // Update stats
                    const claimedStat = document.getElementById('statClaimedCount');
                    const vacantStat = document.getElementById('statVacantCount');
                    if (claimedStat) claimedStat.textContent = claimedCount;
                    if (vacantStat) vacantStat.textContent = Math.max(0, teams.length - claimedCount);
                }
            } catch (e) {
                // Ignore network poll errors
            }
        }, 7000);
    });
})();
</script>
@endsection
