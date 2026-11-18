@php
    $activeSlug = $tournamentSlug ?? ($activeTournament->slug ?? 'rok-invitational-season-1');
    $initialState = $initialBracketState ?? ($activeTournament->bracket_state ?? null);
    $isReadOnly = $readOnly ?? false;
    $isFreeTournament = ($activeTournament && (($activeTournament->tier_required ?? '') === 'free' || str_starts_with($activeSlug, 'rok-free-')));
    $isFree = $isFreePage ?? $isFreeTournament;
    $isPromo = $isPromoPage ?? false;
    $showRealtimeBar = $showRealtimeBar ?? (!$isPromo);
    $hasLogoAccess = $unlockLogos ?? (!$isFree && (Auth::check() && Auth::user()->hasBracketAccess()));
    $useWizard = (!$isReadOnly) && (!$isPromo);
    $shareUrl = route('bracket.live', ['slug' => $activeSlug]);
    $praTourneyFreeUrl = route('bracket.pra-tournament.free', ['slug' => $activeSlug]);
    $praTourneyMemberUrl = route('bracket.pra-tournament.membership', ['slug' => $activeSlug]);
    $praTourneyUrl = $isFree ? $praTourneyFreeUrl : $praTourneyMemberUrl;
    $praTourneyBtnLabel = $isFree ? '<i data-lucide="clipboard-list"></i> Link Pra-Turnamen Free' : '<i data-lucide="clipboard-list"></i> Link Pra-Turnamen Membership';
    $praTourneyPortalText = $isFree ? 'Portal Pra-Turnamen Free &nearr;' : 'Portal Pra-Turnamen Member &nearr;';
    $initialBoSettings = $initialState['boSettings'] ?? [
        'customBo' => false,
        'semi' => 'BO1',
        'final' => 'BO1',
        'bronzeMatch' => false,
        'bronzeBo' => 'BO1',
    ];
@endphp

<div class="rok-bracket-workspace" id="rokBracketWorkspace" data-slug="{{ $activeSlug }}" data-readonly="{{ $isReadOnly ? '1' : '0' }}" data-logo-access="{{ $hasLogoAccess ? '1' : '0' }}" data-free-mode="{{ $isFree ? '1' : '0' }}">

    @if ($isReadOnly)
        {{-- Read-Only Viewer Top Bar --}}
        @if ($showRealtimeBar)
        <div class="bracket-realtime-bar" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 10px 18px; margin-bottom: 20px; border-radius: 999px;">
            <div class="bracket-realtime-bar__status" style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                <span class="realtime-pulse-dot" id="realtimePulseDot" style="flex-shrink: 0;"></span>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <strong class="realtime-status-title" id="realtimeStatusText" style="font-size: 12px; letter-spacing: 0.8px; font-weight: 700; text-transform: uppercase;">
                        LIVE VIEW
                    </strong>
                    <span class="realtime-status-sub" id="realtimeLastSync" style="font-size: 11px; color: var(--rok-muted-light); opacity: 0.75;"></span>
                </div>
            </div>

            <div class="bracket-realtime-bar__actions" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; flex: 1 1 auto; justify-content: flex-end;">
                <div class="share-url-box" style="display: flex; align-items: center; gap: 6px; flex: 1 1 200px; max-width: 360px;">
                    <input type="text" id="shareBracketUrlInput" class="share-url-input" value="{{ $shareUrl }}" readonly onclick="this.select()" style="width: 100%; font-size: 12px; padding: 6px 12px; border-radius: 999px;">
                </div>
                <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                    <button type="button" class="btn-rok btn-rok--gold btn-rok--sm" id="copyShareLinkBtn" title="Salin Link Live" style="white-space: nowrap; border-radius: 999px; padding: 5px 12px; font-size: 12px;">
                        <i data-lucide="copy" style="width:13px;height:13px;"></i> Salin
                    </button>
                    <a href="https://wa.me/?text={{ urlencode('Pantau Live Bracket Turnamen secara Real-Time di ROK Web: ' . $shareUrl) }}" target="_blank" rel="noopener noreferrer" class="btn-rok btn-rok--outline btn-rok--sm" id="waShareLinkBtn" title="Share ke WhatsApp" style="white-space: nowrap; border-radius: 999px; padding: 5px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 5px;">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="color: #25D366; flex-shrink: 0;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7.02 8.48 7.02 9.69C7.02 10.9 7.9 12.07 8.02 12.23C8.14 12.4 9.75 14.9 12.24 15.96C14.31 16.85 14.73 16.67 15.18 16.63C15.64 16.59 16.64 16.03 16.85 15.45C17.05 14.86 17.05 14.36 16.99 14.26C16.93 14.16 16.77 14.1 16.52 13.97C16.27 13.85 15.06 13.25 14.83 13.17C14.61 13.09 14.45 13.05 14.28 13.29C14.12 13.54 13.65 14.1 13.51 14.26C13.37 14.43 13.22 14.45 12.98 14.33C12.73 14.2 11.93 13.94 10.99 13.1C10.25 12.44 9.75 11.63 9.61 11.39C9.46 11.14 9.59 11.01 9.72 10.88C9.83 10.77 9.97 10.59 10.1 10.43C10.22 10.28 10.26 10.16 10.34 9.99C10.42 9.83 10.38 9.69 10.32 9.56C10.26 9.44 9.79 8.28 9.59 7.81C9.4 7.36 9.2 7.42 9.05 7.41C8.91 7.41 8.75 7.41 8.59 7.41L8.53 7.33Z"/></svg>
                        <span>Share</span>
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Hidden inputs for Read-Only Viewer Mode --}}
        <input type="hidden" id="tourneyName" value="{{ $initialState['title'] ?? ($activeTournament->name ?? 'ROK Community Cup 2026') }}">
        <input type="hidden" id="tourneyFormat" value="{{ $initialState['format'] ?? 'single' }}">
        <input type="hidden" id="teamCountInput" value="{{ $initialState['teamCount'] ?? 8 }}">
        <input type="hidden" id="teamCountSelect" value="{{ $initialState['teamCount'] ?? 6 }}">
        <input type="hidden" id="boCustomEnabled" value="{{ !empty($initialBoSettings['customBo']) ? '1' : '0' }}">
        <input type="hidden" id="boSemiFormat" value="{{ $initialBoSettings['semi'] ?? 'BO1' }}">
        <input type="hidden" id="boFinalFormat" value="{{ $initialBoSettings['final'] ?? 'BO1' }}">
        <input type="hidden" id="boBronzeEnabled" value="{{ !empty($initialBoSettings['bronzeMatch']) ? '1' : '0' }}">
        <input type="hidden" id="boBronzeFormat" value="{{ $initialBoSettings['bronzeBo'] ?? 'BO1' }}">
        <div id="teamCountInputWrapper" style="display:none;"></div>
        <div id="teamCountHint" style="display:none;"></div>
        <div id="doubleEliminationWarning" hidden></div>
        <div id="teamInputsGrid" style="display:none;"></div>

        <div class="generator-card">
            {{-- Output Visualizer Deck --}}
            <div class="generated-bracket-output" id="bracketOutputWrapper">
                <div class="output-banner" style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 18px; padding-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span class="live-indicator-badge" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px; letter-spacing: 0.5px;"><i data-lucide="radio" style="width:11px;height:11px;"></i> LIVE</span>
                        <h3 id="outputTourneyTitle" style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.2px;">{{ $initialState['title'] ?? 'ROK Community Cup 2026' }}</h3>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        {{-- Zoom In & Zoom Out Interactive Toolbar --}}
                        <div class="bracket-zoom-toolbar">
                            <button type="button" class="btn-rok-zoom" id="btnZoomOut" title="Perkecil (Zoom Out)" aria-label="Zoom Out">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19 13H5v-2h14v2z"/></svg>
                            </button>
                            <button type="button" class="btn-rok-zoom btn-rok-zoom--reset" id="btnZoomReset" title="Reset 100%">
                                100%
                            </button>
                            <button type="button" class="btn-rok-zoom" id="btnZoomIn" title="Perbesar (Zoom In)" aria-label="Zoom In">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            </button>
                        </div>
                        <span class="badge-mini" id="outputFormatBadge" style="border-radius: 999px; padding: 4px 10px; font-size: 11px;">Single Elimination</span>
                    </div>
                </div>

                <div class="generated-tree-deck" id="generatedTreeDeck" style="position: relative; overflow: auto; padding: 16px 12px 24px 12px;">
                    <div class="bracket-zoom-canvas" id="bracketZoomCanvas" style="display: flex; gap: 26px; min-width: max-content; width: fit-content; height: max-content; align-self: flex-start; align-items: stretch; transform-origin: 0 0; transition: transform 0.38s cubic-bezier(0.16, 1, 0.3, 1);">
                        {{-- Rendered Bracket Tree using native .gen-* classes + SVG tournament lines --}}
                    </div>
                </div>
            </div>
        </div>
    @elseif ($useWizard)
        {{-- Organizer Multi-Step Wizard Stepper Navigation --}}
        <div class="bracket-wizard-nav" id="bracketWizardNav" role="tablist" aria-label="Langkah Pembuatan Bagan Turnamen">
            <button type="button" class="wizard-nav-step is-active" id="wizardNavStep1" data-step="1" role="tab" aria-selected="true" aria-controls="wizardPane1">
                <span class="wizard-nav-badge">
                    <span class="wizard-nav-num">1</span>
                    <i data-lucide="check" class="wizard-nav-check" style="display:none;width:14px;height:14px;"></i>
                </span>
                <div class="wizard-nav-text">
                    <span class="wizard-nav-step-label">LANGKAH 1</span>
                    <strong class="wizard-nav-title">Info Turnamen</strong>
                </div>
            </button>

            <div class="wizard-nav-divider" aria-hidden="true"></div>

            <button type="button" class="wizard-nav-step" id="wizardNavStep2" data-step="2" role="tab" aria-selected="false" aria-controls="wizardPane2">
                <span class="wizard-nav-badge">
                    <span class="wizard-nav-num">2</span>
                    <i data-lucide="check" class="wizard-nav-check" style="display:none;width:14px;height:14px;"></i>
                </span>
                <div class="wizard-nav-text">
                    <span class="wizard-nav-step-label">LANGKAH 2</span>
                    <strong class="wizard-nav-title">Daftar Tim &amp; Kontak</strong>
                </div>
            </button>

            <div class="wizard-nav-divider" aria-hidden="true"></div>

            <button type="button" class="wizard-nav-step" id="wizardNavStep3" data-step="3" role="tab" aria-selected="false" aria-controls="wizardPane3">
                <span class="wizard-nav-badge">
                    <span class="wizard-nav-num">3</span>
                    <i data-lucide="check" class="wizard-nav-check" style="display:none;width:14px;height:14px;"></i>
                </span>
                <div class="wizard-nav-text">
                    <span class="wizard-nav-step-label">LANGKAH 3</span>
                    <strong class="wizard-nav-title">Bagan &amp; Live Link</strong>
                </div>
            </button>
        </div>

        <div class="generator-card">
            {{-- PANE 1: Info Turnamen --}}
            <div class="wizard-pane is-active" id="wizardPane1" role="tabpanel" aria-labelledby="wizardNavStep1">
                <div class="wizard-pane-header">
                    <div>
                        <span class="wizard-pane-step-badge"><i data-lucide="info" style="width:12px;height:12px;"></i> LANGKAH 1 DARI 3</span>
                        <h3 class="wizard-pane-title">Informasi &amp; Format Turnamen</h3>
                        <p class="wizard-pane-desc">Tentukan nama turnamen, format bagan eliminasi, dan kuota tim peserta yang bertanding.</p>
                    </div>
                </div>

                <div class="form-row-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 8px;">
                    <div class="form-field">
                        <label for="tourneyName" class="form-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">Nama Turnamen</label>
                        <input
                            type="text"
                            id="tourneyName"
                            class="form-input"
                            value="{{ $initialState['title'] ?? ($activeTournament->name ?? 'ROK Community Cup 2026') }}"
                            placeholder="Nama Turnamen"
                            style="height: 40px; font-size: 13px; padding: 8px 14px; border-radius: 8px;"
                        >
                    </div>

                    <div class="form-field">
                        <label for="tourneyFormat" class="form-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">Format Pertandingan</label>
                        <select id="tourneyFormat" class="form-select" style="height: 40px; font-size: 13px; padding: 8px 14px; border-radius: 8px;">
                            <option value="single">Single Elimination</option>
                            <option value="double">Double Elimination</option>
                            <option value="league">League System</option>
                            <option value="swiss">Swiss Stage (Format M7 MLBB)</option>
                        </select>
                    </div>

                    <div class="form-field" id="teamCountField">
                        <label for="teamCountInput" class="form-label" id="teamCountLabel" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">Jumlah Tim Peserta</label>

                        <div id="teamCountInputWrapper">
                            <input
                                type="number"
                                id="teamCountInput"
                                class="form-input"
                                min="3"
                                max="64"
                                value="{{ $initialState['teamCount'] ?? 8 }}"
                                placeholder="3 - 64 Tim"
                                style="height: 40px; font-size: 13px; padding: 8px 14px; border-radius: 8px;"
                            >
                            <span class="form-hint" id="teamCountHint" style="display: none;"></span>
                        </div>

                        <select id="teamCountSelect" class="form-select" style="display: none; height: 40px; font-size: 13px; padding: 8px 14px; border-radius: 8px;">
                            <option value="4">4 Tim</option>
                            <option value="6" selected>6 Tim (2 Seed Teratas Mendapat Bye)</option>
                        </select>

                        <p class="bracket-format-warning" id="doubleEliminationWarning" role="alert" aria-live="polite" hidden>
                            Double Elimination dibatasi 4 atau 6 tim.
                        </p>
                    </div>
                </div>

                {{-- Single Elimination Best-of & Juara 3 Options --}}
                <div class="custom-bo-section" id="singleElimBoSection" style="margin-top: 14px; margin-bottom: 12px; padding: 14px 16px; background: rgba(255, 255, 255, 0.02); border: 1px solid var(--rok-surface-border); border-radius: 10px;">
                    <div style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 10px;">
                        <i data-lucide="info" style="width: 15px; height: 15px; color: var(--rok-gold); flex-shrink: 0; margin-top: 2px;"></i>
                        <div style="font-size: 12px; color: var(--rok-muted-light); line-height: 1.5;">
                            <strong style="color: var(--rok-silver);">Format Seri Default:</strong> Semua babak rata <strong>BO1</strong> dari babak penyisihan hingga babak Final.
                        </div>
                    </div>

                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                        <input type="checkbox" id="enableCustomBoCheckbox" {{ !empty($initialBoSettings['customBo']) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: var(--rok-gold); cursor: pointer;">
                        <span style="font-size: 13px; font-weight: 700; color: var(--rok-text-bright);">
                            Kustomisasi Format Seri (BO3, BO5, BO7) &amp; Perebutan Juara 3
                        </span>
                    </label>

                    <div id="customBoOptionsPanel" style="{{ !empty($initialBoSettings['customBo']) ? 'display: block;' : 'display: none;' }} margin-top: 14px; padding-top: 14px; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                        <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0 0 12px 0;">
                            Tentukan format seri Best of (BO) untuk babak penentuan dan perebutan juara:
                        </p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                            <div class="form-field">
                                <label for="semiFinalBoSelect" class="form-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">
                                    Format Babak Semi Final
                                </label>
                                <select id="semiFinalBoSelect" class="form-select" style="height: 38px; font-size: 13px; padding: 6px 12px; border-radius: 8px;">
                                    <option value="BO1" {{ ($initialBoSettings['semi'] ?? '') === 'BO1' ? 'selected' : '' }}>BO1 (Best of 1)</option>
                                    <option value="BO3" {{ ($initialBoSettings['semi'] ?? 'BO3') === 'BO3' ? 'selected' : '' }}>BO3 (Best of 3 - Rekomendasi)</option>
                                    <option value="BO5" {{ ($initialBoSettings['semi'] ?? '') === 'BO5' ? 'selected' : '' }}>BO5 (Best of 5)</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label for="grandFinalBoSelect" class="form-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">
                                    Format Babak Grand Final
                                </label>
                                <select id="grandFinalBoSelect" class="form-select" style="height: 38px; font-size: 13px; padding: 6px 12px; border-radius: 8px;">
                                    <option value="BO1" {{ ($initialBoSettings['final'] ?? '') === 'BO1' ? 'selected' : '' }}>BO1 (Best of 1)</option>
                                    <option value="BO3" {{ ($initialBoSettings['final'] ?? '') === 'BO3' ? 'selected' : '' }}>BO3 (Best of 3)</option>
                                    <option value="BO5" {{ ($initialBoSettings['final'] ?? 'BO5') === 'BO5' ? 'selected' : '' }}>BO5 (Best of 5 - Rekomendasi)</option>
                                    <option value="BO7" {{ ($initialBoSettings['final'] ?? '') === 'BO7' ? 'selected' : '' }}>BO7 (Best of 7)</option>
                                </select>
                            </div>

                            <div class="form-field" style="grid-column: 1 / -1; background: rgba(255, 255, 255, 0.02); padding: 12px 14px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 8px;">
                                    <input type="checkbox" id="enableBronzeMatchCheckbox" {{ !empty($initialBoSettings['bronzeMatch']) ? 'checked' : '' }} style="width: 15px; height: 15px; accent-color: var(--rok-gold); cursor: pointer;">
                                    <span style="font-size: 12px; font-weight: 700; color: var(--rok-silver);">
                                        Adakan Pertandingan Perebutan Juara 3 (Bronze Match)
                                    </span>
                                </label>
                                <div id="bronzeBoSelectWrapper" style="{{ !empty($initialBoSettings['bronzeMatch']) ? 'display: flex;' : 'display: none;' }} align-items: center; gap: 10px; margin-top: 6px; padding-left: 23px;">
                                    <span style="font-size: 12px; color: var(--rok-muted-light);">Format Perebutan Juara 3:</span>
                                    <select id="bronzeMatchBoSelect" class="form-select" style="height: 34px; font-size: 12px; padding: 4px 10px; border-radius: 6px; width: auto; min-width: 150px;">
                                        <option value="BO1" {{ ($initialBoSettings['bronzeBo'] ?? '') === 'BO1' ? 'selected' : '' }}>BO1 (Best of 1)</option>
                                        <option value="BO3" {{ ($initialBoSettings['bronzeBo'] ?? 'BO3') === 'BO3' ? 'selected' : '' }}>BO3 (Best of 3 - Rekomendasi)</option>
                                        <option value="BO5" {{ ($initialBoSettings['bronzeBo'] ?? '') === 'BO5' ? 'selected' : '' }}>BO5 (Best of 5)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wizard-actions-bar wizard-actions-bar--right-only">
                    <button type="button" class="btn-rok btn-rok--gold" id="btnWizardNext1" style="border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; padding: 8px 24px; font-size: 13px; font-weight: 800; box-shadow: 0 4px 14px rgba(251,191,36,0.3);">
                        <span>Lanjut ke Daftar Tim &amp; Kontak</span>
                        <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                    </button>
                </div>
            </div>

            {{-- PANE 2: Daftar Tim & Kontak --}}
            <div class="wizard-pane" id="wizardPane2" role="tabpanel" aria-labelledby="wizardNavStep2">
                <div class="wizard-pane-header">
                    <div>
                        <span class="wizard-pane-step-badge"><i data-lucide="users" style="width:12px;height:12px;"></i> LANGKAH 2 DARI 3</span>
                        <h3 class="wizard-pane-title">Daftar Tim Peserta &amp; Kontak Kapten</h3>
                        <p class="wizard-pane-desc">Masukkan nama tim dan nomor WhatsApp kapten untuk verifikasi jadwal serta koordinasi pertandingan.</p>
                    </div>
                    <div>
                        @if ($hasLogoAccess)
                            <span class="membership-unlocked-pill" style="font-size: 10px; padding: 4px 12px; border-radius: 999px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="crown" style="width:12px;height:12px;"></i> Logo Custom Aktif
                            </span>
                        @elseif ($isFree)
                            <span class="membership-locked-pill" style="font-size: 10px; padding: 4px 12px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="shield" style="width:12px;height:12px;"></i> Edisi Gratis
                            </span>
                        @else
                            <a href="{{ route('membership.payment', ['plan' => 'challenger']) }}" class="membership-locked-pill" style="font-size: 10px; padding: 4px 12px; border-radius: 999px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="lock" style="width:12px;height:12px;"></i> Unlock Logo Custom
                            </a>
                        @endif
                    </div>
                </div>

                <div class="team-inputs-container">
                    <div class="team-inputs-grid" id="teamInputsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 8px 10px; margin-bottom: 12px;">
                        {{-- Populated by JavaScript --}}
                    </div>
                </div>

                <div class="wizard-actions-bar">
                    <button type="button" class="btn-rok btn-rok--outline" id="btnWizardBack1" style="border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; padding: 7px 18px; font-size: 12px; font-weight: 700;">
                        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
                        <span>Kembali ke Info Turnamen</span>
                    </button>

                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-rok btn-rok--outline btn-drawing" id="btnReshuffleBracket" title="Acak urutan tim peserta (Drawing Turnamen)" style="border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; font-size: 12px;">
                            <i data-lucide="shuffle" style="width:14px;height:14px;"></i>
                            <span>Drawing Acak</span>
                        </button>
                        <a href="{{ $praTourneyUrl }}" target="_blank" class="btn-rok btn-rok--outline" id="praTourneyStep2Link" style="border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; font-size: 12px; border-color: rgba(255,255,255,0.2); color: #cbd5e1;" title="Buka Portal Pra-Turnamen untuk check-in tim peserta">
                            <i data-lucide="clipboard-list" style="width:14px;height:14px;"></i>
                            <span>Portal Pra-Turnamen &nearr;</span>
                        </a>
                        <button type="button" class="btn-rok btn-rok--gold" id="generateBracketBtn" style="border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; padding: 8px 24px; font-size: 13px; font-weight: 800; box-shadow: 0 4px 14px rgba(251,191,36,0.3);">
                            <i data-lucide="sparkles" style="width:15px;height:15px;"></i>
                            <span>Generate &amp; Buat Bagan</span>
                            <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- PANE 3: Bagan & Live Link --}}
            <div class="wizard-pane" id="wizardPane3" role="tabpanel" aria-labelledby="wizardNavStep3">
                <div class="wizard-step3-topbar">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-rok btn-rok--outline btn-rok--sm" id="btnWizardBackToEdit" style="border-radius: 999px; padding: 5px 14px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                            <i data-lucide="edit-3" style="width:13px;height:13px;"></i>
                            <span>Ubah Info / Daftar Tim</span>
                        </button>
                        <span style="font-size: 12px; color: var(--rok-muted-light); display: inline-flex; align-items: center; gap: 6px;">
                            <i data-lucide="check-circle-2" style="width:14px;height:14px;color:#22c55e;"></i>
                            Bagan turnamen aktif &amp; link live siap dibagikan
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="btn-rok btn-rok--outline btn-reset-scores btn-rok--sm" id="resetScoresBtn" style="border-radius: 999px; display: inline-flex; align-items: center; gap: 5px; padding: 5px 14px; font-size: 12px;">
                            <i data-lucide="rotate-ccw" style="width:13px;height:13px;"></i> Reset Skor
                        </button>
                    </div>
                </div>

                {{-- Live Sync & Shareable Link Bar (Disembunyikan khusus pada Halaman Promosi Bracket) --}}
                @if ($showRealtimeBar)
                <div class="bracket-realtime-bar" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 10px 18px; margin-bottom: 20px; border-radius: 999px;">
                    <div class="bracket-realtime-bar__status" style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                        <span class="realtime-pulse-dot" id="realtimePulseDot" style="flex-shrink: 0;"></span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong class="realtime-status-title" id="realtimeStatusText" style="font-size: 12px; letter-spacing: 0.8px; font-weight: 700; text-transform: uppercase;">
                                LIVE SYNC
                            </strong>
                            <span class="realtime-status-sub" id="realtimeLastSync" style="font-size: 11px; color: var(--rok-muted-light); opacity: 0.75;"></span>
                        </div>
                    </div>

                    <div class="bracket-realtime-bar__actions" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; flex: 1 1 auto; justify-content: flex-end;">
                        <div class="share-url-box" style="display: flex; align-items: center; gap: 6px; flex: 1 1 200px; max-width: 360px;">
                            <input type="text" id="shareBracketUrlInput" class="share-url-input" value="{{ $shareUrl }}" readonly onclick="this.select()" style="width: 100%; font-size: 12px; padding: 6px 12px; border-radius: 999px;">
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                            <button type="button" class="btn-rok btn-rok--gold btn-rok--sm" id="copyShareLinkBtn" title="Salin Link Live" style="white-space: nowrap; border-radius: 999px; padding: 5px 12px; font-size: 12px;">
                                <i data-lucide="copy" style="width:13px;height:13px;"></i> Salin
                            </button>
                            <a href="https://wa.me/?text={{ urlencode('Pantau Live Bracket Turnamen secara Real-Time di ROK Web: ' . $shareUrl) }}" target="_blank" rel="noopener noreferrer" class="btn-rok btn-rok--outline btn-rok--sm" id="waShareLinkBtn" title="Share ke WhatsApp" style="white-space: nowrap; border-radius: 999px; padding: 5px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 5px;">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="color: #25D366; flex-shrink: 0;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7.02 8.48 7.02 9.69C7.02 10.9 7.9 12.07 8.02 12.23C8.14 12.4 9.75 14.9 12.24 15.96C14.31 16.85 14.73 16.67 15.18 16.63C15.64 16.59 16.64 16.03 16.85 15.45C17.05 14.86 17.05 14.36 16.99 14.26C16.93 14.16 16.77 14.1 16.52 13.97C16.27 13.85 15.06 13.25 14.83 13.17C14.61 13.09 14.45 13.05 14.28 13.29C14.12 13.54 13.65 14.1 13.51 14.26C13.37 14.43 13.22 14.45 12.98 14.33C12.73 14.2 11.93 13.94 10.99 13.1C10.25 12.44 9.75 11.63 9.61 11.39C9.46 11.14 9.59 11.01 9.72 10.88C9.83 10.77 9.97 10.59 10.1 10.43C10.22 10.28 10.26 10.16 10.34 9.99C10.42 9.83 10.38 9.69 10.32 9.56C10.26 9.44 9.79 8.28 9.59 7.81C9.4 7.36 9.2 7.42 9.05 7.41C8.91 7.41 8.75 7.41 8.59 7.41L8.53 7.33Z"/></svg>
                                <span>Share</span>
                            </a>
                            <button type="button" class="btn-rok btn-rok--outline btn-rok--sm" id="copyPraTourneyLinkBtn" data-url="{{ $praTourneyUrl }}" title="Salin Link Pra-Turnamen" style="white-space: nowrap; border-radius: 999px; padding: 5px 12px; font-size: 12px; border-color: rgba(255,255,255,0.2); color: #cbd5e1;">
                                <i data-lucide="clipboard-list" style="width:13px;height:13px;"></i> Salin Pra-Turnamen
                            </button>
                            <a href="{{ $praTourneyUrl }}" target="_blank" class="btn-rok btn-rok--outline btn-rok--sm" title="Buka Portal Pra-Turnamen untuk check-in tim peserta" style="white-space: nowrap; border-radius: 999px; padding: 5px 12px; font-size: 12px; border-color: rgba(255,255,255,0.2); color: #cbd5e1; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="user-check" style="width:13px;height:13px;"></i> Portal Pra-Turnamen &nearr;
                            </a>
                            <a href="{{ $shareUrl }}" target="_blank" class="btn-rok btn-rok--outline btn-rok--sm" title="Buka tampilan penonton di tab baru" style="white-space: nowrap; border-radius: 999px; padding: 5px 12px; font-size: 12px;">
                                <i data-lucide="external-link" style="width:13px;height:13px;"></i> Live View
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Output Visualizer Deck --}}
                <div class="generated-bracket-output" id="bracketOutputWrapper">
                    <div class="output-banner" style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 18px; padding-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span class="live-indicator-badge" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px; letter-spacing: 0.5px;"><i data-lucide="radio" style="width:11px;height:11px;"></i> LIVE</span>
                            <h3 id="outputTourneyTitle" style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.2px;">{{ $initialState['title'] ?? 'ROK Community Cup 2026' }}</h3>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            {{-- Zoom In & Zoom Out Interactive Toolbar --}}
                            <div class="bracket-zoom-toolbar">
                                <button type="button" class="btn-rok-zoom" id="btnZoomOut" title="Perkecil (Zoom Out)" aria-label="Zoom Out">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19 13H5v-2h14v2z"/></svg>
                                </button>
                                <button type="button" class="btn-rok-zoom btn-rok-zoom--reset" id="btnZoomReset" title="Reset 100%">
                                    100%
                                </button>
                                <button type="button" class="btn-rok-zoom" id="btnZoomIn" title="Perbesar (Zoom In)" aria-label="Zoom In">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                </button>
                            </div>
                            <button type="button" class="btn-rok btn-rok--outline btn-rok--sm btn-drawing" id="btnReshuffleQuick" title="Drawing / Acak Urutan Tim" style="border-radius: 999px; font-size: 11px; padding: 4px 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="shuffle" style="width:12px;height:12px;"></i> Acak
                            </button>
                            <span class="badge-mini" id="outputFormatBadge" style="border-radius: 999px; padding: 4px 10px; font-size: 11px;">Single Elimination</span>
                        </div>
                    </div>

                    <div class="generated-tree-deck" id="generatedTreeDeck" style="position: relative; overflow: auto; padding: 16px 12px 24px 12px;">
                        <div class="bracket-zoom-canvas" id="bracketZoomCanvas" style="display: flex; gap: 26px; min-width: max-content; width: fit-content; height: max-content; align-self: flex-start; align-items: stretch; transform-origin: 0 0; transition: transform 0.38s cubic-bezier(0.16, 1, 0.3, 1);">
                            {{-- Rendered Bracket Tree using native .gen-* classes + SVG tournament lines --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Promo Classic Layout (Halaman Promosi Bracket): Formulir & Bagan Langsung Terbuka Tanpa Wizard Pagination --}}
        <div class="generator-card">
            <div class="generator-inputs">
                <div class="form-row-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 6px;">
                    <div class="form-field">
                        <label for="tourneyName" class="form-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">Nama Turnamen</label>
                        <input
                            type="text"
                            id="tourneyName"
                            class="form-input"
                            value="{{ $initialState['title'] ?? ($activeTournament->name ?? 'ROK Community Cup 2026') }}"
                            placeholder="Nama Turnamen"
                            style="height: 38px; font-size: 13px; padding: 6px 12px; border-radius: 8px;"
                        >
                    </div>

                    <div class="form-field">
                        <label for="tourneyFormat" class="form-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">Format Pertandingan</label>
                        <select id="tourneyFormat" class="form-select" style="height: 38px; font-size: 13px; padding: 6px 12px; border-radius: 8px;">
                            <option value="single">Single Elimination</option>
                            <option value="double">Double Elimination</option>
                            <option value="league">League System</option>
                            <option value="swiss">Swiss Stage (Format M7 MLBB)</option>
                        </select>
                    </div>

                    <div class="form-field" id="teamCountField">
                        <label for="teamCountInput" class="form-label" id="teamCountLabel" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; margin-bottom: 5px; display: block;">Jumlah Tim Peserta</label>

                        <div id="teamCountInputWrapper">
                            <input
                                type="number"
                                id="teamCountInput"
                                class="form-input"
                                min="3"
                                max="64"
                                value="{{ $initialState['teamCount'] ?? 8 }}"
                                placeholder="3 - 64 Tim"
                                style="height: 38px; font-size: 13px; padding: 6px 12px; border-radius: 8px;"
                            >
                            <span class="form-hint" id="teamCountHint" style="display: none;"></span>
                        </div>

                        <select id="teamCountSelect" class="form-select" style="display: none; height: 38px; font-size: 13px; padding: 6px 12px; border-radius: 8px;">
                            <option value="4">4 Tim</option>
                            <option value="6" selected>6 Tim (2 Seed Teratas Mendapat Bye)</option>
                        </select>

                        <p class="bracket-format-warning" id="doubleEliminationWarning" role="alert" aria-live="polite" hidden>
                            Double Elimination dibatasi 4 atau 6 tim.
                        </p>
                    </div>
                </div>

                {{-- Dynamic Teams Input Container (Team Name + WhatsApp + Team Logo) --}}
                <div class="team-inputs-container">
                    <div class="team-inputs-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin: 14px 0 8px 0;">
                        <span class="inputs-subtitle" style="margin: 0; font-size: 11px; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase;">Daftar Tim &amp; Kontak</span>
                        @if ($hasLogoAccess)
                            <span class="membership-unlocked-pill" style="font-size: 10px; padding: 2px 9px; border-radius: 999px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="crown" style="width:11px;height:11px;"></i> Logo Custom Aktif
                            </span>
                        @elseif ($isFree)
                            <span class="membership-locked-pill" style="font-size: 10px; padding: 2px 9px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="shield" style="width:11px;height:11px;"></i> Edisi Gratis
                            </span>
                        @else
                            <a href="{{ route('membership.payment', ['plan' => 'challenger']) }}" class="membership-locked-pill" style="font-size: 10px; padding: 2px 9px; border-radius: 999px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="lock" style="width:11px;height:11px;"></i> Unlock Logo Custom
                            </a>
                        @endif
                    </div>

                    <div class="team-inputs-grid" id="teamInputsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 8px 10px; margin-bottom: 12px;">
                        {{-- Populated by JavaScript --}}
                    </div>
                </div>

                <div class="generator-actions" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; justify-content: flex-end; margin-top: 14px; padding-top: 12px; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                    <button type="button" class="btn-rok btn-rok--outline btn-drawing" id="btnReshuffleBracket" title="Acak urutan tim peserta (Drawing Turnamen)" style="border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; font-size: 12px; height: 34px;">
                        <i data-lucide="shuffle" style="width:13px;height:13px;"></i> Drawing Acak
                    </button>
                    <button type="button" class="btn-rok btn-rok--outline btn-reset-scores" id="resetScoresBtn" style="border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; font-size: 12px; height: 34px;">
                        <i data-lucide="rotate-ccw" style="width:13px;height:13px;"></i> Reset Skor
                    </button>
                    <button type="button" class="btn-rok btn-rok--gold" id="generateBracketBtn" style="border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; padding: 7px 20px; font-size: 12px; font-weight: 800; height: 34px; box-shadow: 0 2px 10px rgba(251,191,36,0.25);">
                        <i data-lucide="sparkles" style="width:13px;height:13px;"></i> Generate &amp; Simpan
                    </button>
                </div>
            </div>

            {{-- Output Visualizer Deck --}}
            <div class="generated-bracket-output" id="bracketOutputWrapper">
                <div class="output-banner" style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; margin-bottom: 18px; padding-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span class="live-indicator-badge" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px; letter-spacing: 0.5px;"><i data-lucide="radio" style="width:11px;height:11px;"></i> LIVE</span>
                        <h3 id="outputTourneyTitle" style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.2px;">{{ $initialState['title'] ?? 'ROK Community Cup 2026' }}</h3>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        {{-- Zoom In & Zoom Out Interactive Toolbar --}}
                        <div class="bracket-zoom-toolbar">
                            <button type="button" class="btn-rok-zoom" id="btnZoomOut" title="Perkecil (Zoom Out)" aria-label="Zoom Out">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19 13H5v-2h14v2z"/></svg>
                            </button>
                            <button type="button" class="btn-rok-zoom btn-rok-zoom--reset" id="btnZoomReset" title="Reset 100%">
                                100%
                            </button>
                            <button type="button" class="btn-rok-zoom" id="btnZoomIn" title="Perbesar (Zoom In)" aria-label="Zoom In">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            </button>
                        </div>
                        <button type="button" class="btn-rok btn-rok--outline btn-rok--sm btn-drawing" id="btnReshuffleQuick" title="Drawing / Acak Urutan Tim" style="border-radius: 999px; font-size: 11px; padding: 4px 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="shuffle" style="width:12px;height:12px;"></i> Acak
                        </button>
                        <span class="badge-mini" id="outputFormatBadge" style="border-radius: 999px; padding: 4px 10px; font-size: 11px;">Single Elimination</span>
                    </div>
                </div>

                <div class="generated-tree-deck" id="generatedTreeDeck" style="position: relative; overflow: auto; padding: 16px 12px 24px 12px;">
                    <div class="bracket-zoom-canvas" id="bracketZoomCanvas" style="display: flex; gap: 26px; min-width: max-content; width: fit-content; height: max-content; align-self: flex-start; align-items: stretch; transform-origin: 0 0; transition: transform 0.38s cubic-bezier(0.16, 1, 0.3, 1);">
                        {{-- Rendered Bracket Tree using native .gen-* classes + SVG tournament lines --}}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Interactive Team Info, Match Points & Direct WhatsApp Modal --}}
<div class="rok-team-modal" id="rokTeamModal" hidden aria-modal="true" role="dialog">
    <div class="rok-team-modal__backdrop" id="closeTeamModalBackdrop"></div>
    <div class="rok-team-modal__dialog">
        <button type="button" class="rok-team-modal__close" id="closeTeamModalBtn" aria-label="Tutup">&times;</button>

        <div class="team-modal-header">
            <div class="team-modal-logo-wrap">
                <img src="/storage/tournaments/team-rok.svg" alt="Team Logo" id="modalTeamLogo" class="team-modal-logo">
            </div>
            <div>
                <span class="badge-mini" id="modalTeamSeed">SEED #1</span>
                <h3 class="team-modal-title" id="modalTeamName">Nama Tim</h3>
                <span class="team-modal-seed" id="modalTeamTourney">ROK Community Cup 2026</span>
            </div>
        </div>

        <div class="team-modal-points-card">
            <div class="points-card-header">
                <span>STATISTIK &amp; POIN PERTANDINGAN</span>
                <span style="color: var(--rok-gold); display: inline-flex; align-items: center; gap: 4px;"><i data-lucide="activity" style="width:13px;height:13px;"></i> LIVE SCORING</span>
            </div>
            <div class="points-stat-grid">
                <div class="points-stat-box points-stat-box--highlight">
                    <span class="stat-value" id="modalTeamPoints">0 Pts</span>
                    <span class="stat-label">Total Poin</span>
                </div>
                <div class="points-stat-box">
                    <span class="stat-value" id="modalTeamWins">0W</span>
                    <span class="stat-label">Menang</span>
                </div>
                <div class="points-stat-box">
                    <span class="stat-value" id="modalTeamLosses">0L</span>
                    <span class="stat-label">Kalah</span>
                </div>
                <div class="points-stat-box">
                    <span class="stat-value" id="modalTeamGameScore">0</span>
                    <span class="stat-label">Skor Game</span>
                </div>
            </div>
        </div>

        <div class="team-modal-wa-section">
            <div class="wa-section-label" style="display: flex; align-items: center; gap: 6px;"><i data-lucide="smartphone" style="width:14px;height:14px;"></i> KONTAK RESMI KAPTEN TIM (WHATSAPP)</div>
            <div class="wa-number-display" id="modalTeamWaNumber">+62 812-3456-7890</div>
            <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0 0 12px 0;">
                Hubungi tim ini langsung via WhatsApp untuk verifikasi hasil, koordinasi jadwal, atau pembagian lobby turnamen.
            </p>
            <a href="#" target="_blank" rel="noopener noreferrer" class="btn-rok btn-rok--gold btn-rok--full btn-wa-direct" id="modalTeamWaBtn" style="text-decoration: none; justify-content: center; display: inline-flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="color:#25D366;flex-shrink:0;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7.02 8.48 7.02 9.69C7.02 10.9 7.9 12.07 8.02 12.23C8.14 12.4 9.75 14.9 12.24 15.96C14.31 16.85 14.73 16.67 15.18 16.63C15.64 16.59 16.64 16.03 16.85 15.45C17.05 14.86 17.05 14.36 16.99 14.26C16.93 14.16 16.77 14.1 16.52 13.97C16.27 13.85 15.06 13.25 14.83 13.17C14.61 13.09 14.45 13.05 14.28 13.29C14.12 13.54 13.65 14.1 13.51 14.26C13.37 14.43 13.22 14.45 12.98 14.33C12.73 14.2 11.93 13.94 10.99 13.1C10.25 12.44 9.75 11.63 9.61 11.39C9.46 11.14 9.59 11.01 9.72 10.88C9.83 10.77 9.97 10.59 10.1 10.43C10.22 10.28 10.26 10.16 10.34 9.99C10.42 9.83 10.38 9.69 10.32 9.56C10.26 9.44 9.79 8.28 9.59 7.81C9.4 7.36 9.2 7.42 9.05 7.41C8.91 7.41 8.75 7.41 8.59 7.41L8.53 7.33Z"/></svg>
                <span>Chat Tim via WhatsApp</span>
            </a>
        </div>
    </div>
</div>

{{-- Team Logo Selector & Custom Upload Modal (For Challenger / Immortal Members) --}}
<div class="rok-team-modal" id="rokLogoPickerModal" hidden aria-modal="true" role="dialog">
    <div class="rok-team-modal__backdrop" id="closeLogoPickerBackdrop"></div>
    <div class="rok-team-modal__dialog" style="max-width: 480px;">
        <button type="button" class="rok-team-modal__close" id="closeLogoPickerBtn" aria-label="Tutup">&times;</button>
        <h3 style="font-size: 18px; font-weight: 800; margin: 0 0 4px 0;">Pilih Emblem atau Unggah Logo Tim</h3>
        <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0 0 16px 0;">
            Kustomisasi logo resmi tim turnamen (Khusus Member ROK Challenger &amp; Immortal).
        </p>

        <div style="margin-bottom: 18px;">
            <label class="form-label" style="display: block; margin-bottom: 6px; font-size: 12px;">Unggah File Logo (PNG, JPG, SVG, WebP):</label>
            <input type="file" id="customLogoFileInput" class="form-input" accept="image/*" style="padding: 6px 10px; font-size: 12px;">
        </div>

        <div>
            <span class="form-label" style="display: block; margin-bottom: 8px; font-size: 12px;">Atau Pilih Emblem Esports Resmi:</span>
            <div id="presetLogosGrid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                {{-- Populated by JS --}}
            </div>
        </div>
    </div>
</div>

{{-- Interactive Edit Match Result Modal (Modal Ubah Hasil Pertandingan) --}}
<div class="rok-team-modal" id="matchEditModal" hidden aria-modal="true" role="dialog">
    <div class="rok-team-modal__backdrop" id="closeMatchEditBackdrop"></div>
    <div class="rok-team-modal__dialog" style="max-width: 520px;">
        <button type="button" class="rok-team-modal__close" id="closeMatchEditBtn" aria-label="Tutup">&times;</button>
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
            <span class="badge-mini" id="modalMatchBadge" style="background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); font-weight: 800; border-radius: 999px; padding: 2px 10px; font-size: 11px;">
                MATCH
            </span>
            <span id="modalMatchBoBadge" style="font-size: 11px; font-weight: 800; color: var(--rok-muted-light); background: rgba(255,255,255,0.06); padding: 2px 8px; border-radius: 999px;">
                BO1
            </span>
        </div>
        <h3 style="font-size: 18px; font-weight: 800; margin: 0 0 6px 0;" id="modalMatchTitle">
            Ubah Hasil Pertandingan
        </h3>
        <p style="font-size: 12px; color: var(--rok-muted-light); margin: 0 0 16px 0;" id="modalMatchSub">
            Masukkan atau sesuaikan skor pertandingan untuk menentukan tim yang lolos ke babak selanjutnya.
        </p>

        {{-- Match Scoring Grid --}}
        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--rok-surface-border); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
            {{-- Team 1 Row --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px;">
                <div style="display: flex; align-items: center; gap: 10px; flex: 1 1 auto; min-width: 0;">
                    <img id="modalEditTeam1Logo" src="/storage/tournaments/team-rok.svg" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(212,175,55,0.4); flex-shrink: 0;">
                    <div style="min-width: 0;">
                        <strong id="modalEditTeam1Name" style="font-size: 14px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Tim 1</strong>
                        <span style="font-size: 11px; color: var(--rok-muted-light);">Posisi Tim A</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <button type="button" class="btn-rok-step" id="btnScore1Minus" style="width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--rok-surface-border); background: var(--rok-surface-card); color: #fff; cursor: pointer;">-</button>
                    <input type="number" id="modalScore1Input" min="0" max="99" style="width: 50px; height: 36px; text-align: center; font-size: 16px; font-weight: 800; background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); color: #fff; border-radius: 8px;">
                    <button type="button" class="btn-rok-step" id="btnScore1Plus" style="width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--rok-surface-border); background: var(--rok-surface-card); color: #fff; cursor: pointer;">+</button>
                    <button type="button" class="btn-quick-win" id="btnQuickWin1" style="font-size: 11px; font-weight: 700; padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(34,197,94,0.3); background: rgba(34,197,94,0.12); color: #4ade80; cursor: pointer; white-space: nowrap;">Menang</button>
                </div>
            </div>

            <div style="text-align: center; margin: 4px 0 14px 0; font-size: 11px; font-weight: 800; color: var(--rok-muted-light); letter-spacing: 1px;">VS</div>

            {{-- Team 2 Row --}}
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 10px; flex: 1 1 auto; min-width: 0;">
                    <img id="modalEditTeam2Logo" src="/storage/tournaments/team-aether.svg" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(212,175,55,0.4); flex-shrink: 0;">
                    <div style="min-width: 0;">
                        <strong id="modalEditTeam2Name" style="font-size: 14px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Tim 2</strong>
                        <span style="font-size: 11px; color: var(--rok-muted-light);">Posisi Tim B</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <button type="button" class="btn-rok-step" id="btnScore2Minus" style="width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--rok-surface-border); background: var(--rok-surface-card); color: #fff; cursor: pointer;">-</button>
                    <input type="number" id="modalScore2Input" min="0" max="99" style="width: 50px; height: 36px; text-align: center; font-size: 16px; font-weight: 800; background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); color: #fff; border-radius: 8px;">
                    <button type="button" class="btn-rok-step" id="btnScore2Plus" style="width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--rok-surface-border); background: var(--rok-surface-card); color: #fff; cursor: pointer;">+</button>
                    <button type="button" class="btn-quick-win" id="btnQuickWin2" style="font-size: 11px; font-weight: 700; padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(34,197,94,0.3); background: rgba(34,197,94,0.12); color: #4ade80; cursor: pointer; white-space: nowrap;">Menang</button>
                </div>
            </div>
        </div>

        {{-- Series Helper Target info --}}
        <div id="modalSeriesRuleHint" style="font-size: 11px; color: var(--rok-muted-light); margin-bottom: 18px; display: flex; align-items: center; gap: 6px;">
            <i data-lucide="info" style="width: 14px; height: 14px; color: var(--rok-gold);"></i>
            <span id="modalSeriesRuleText">Format BO3: Tim pertama yang mengumpulkan 2 poin menang dinyatakan sebagai pemenang pertandingan.</span>
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
            <button type="button" class="btn-rok btn-rok--outline btn-rok--sm" id="btnResetSingleMatch" style="border-radius: 6px; color: #f87171; border-color: rgba(239,68,68,0.3);">
                Reset Skor Match
            </button>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn-rok btn-rok--outline btn-rok--sm" id="btnCancelEditMatch" style="border-radius: 6px;">
                    Batal
                </button>
                <button type="button" class="btn-rok btn-rok--gold btn-rok--sm" id="btnSaveEditMatch" style="border-radius: 6px; font-weight: 800;">
                    Simpan Hasil
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const INITIAL_SERVER_STATE = @json($initialState);
    const ACTIVE_SLUG = @json($activeSlug);
    const SYNC_API_URL = @json(route('api.bracket.sync', ['slug' => $activeSlug]));
    const STATE_API_URL = @json(route('api.bracket.state', ['slug' => $activeSlug]));
    const IS_READ_ONLY = @json($isReadOnly);
    const HAS_LOGO_ACCESS = @json($hasLogoAccess);
    const IS_FREE = @json($isFree);
    const IS_PROMO = @json($isPromo);
    const USE_WIZARD = @json($useWizard);

    const presetLogos = [
        '/storage/tournaments/team-rok.svg',
        '/storage/tournaments/team-aether.svg',
        '/storage/tournaments/team-valkyrie.svg',
        '/storage/tournaments/team-shadow.svg',
        '/storage/tournaments/team-apex.svg',
        '/storage/tournaments/team-celestial.svg',
        '/storage/tournaments/team-kitsune.svg',
        '/storage/tournaments/team-eclipse.svg'
    ];

    const defaultTeamNames = Array.from({ length: 64 }, (_, i) => `Nama Tim ${i + 1}`);

    const formatLabels = {
        single: 'Single Elimination',
        double: 'Double Elimination',
        league: 'League System',
        swiss: 'Swiss Stage (Format M7 MLBB)'
    };

    let tournamentState = null;
    let selectedSeedForLogo = null;
    let syncDebounceTimer = null;
    let autoPollTimer = null;
    let isApplyingRemoteUpdate = false;

    function normalizeWaNumber(raw) {
        if (!raw) return '';
        let clean = String(raw).replace(/\D+/g, '');
        if (clean.startsWith('0')) {
            clean = '62' + clean.slice(1);
        } else if (!clean.startsWith('62') && clean.length > 0) {
            clean = '62' + clean;
        }
        return clean;
    }

    function formatWaDisplay(raw) {
        const clean = normalizeWaNumber(raw);
        if (!clean) return 'Belum diisi';
        if (clean.startsWith('62')) {
            return '+62 ' + clean.slice(2, 5) + '-' + clean.slice(5, 9) + '-' + clean.slice(9);
        }
        return '+' + clean;
    }

    function getOfficialWhatsAppSvg(size = 14) {
        return `<svg viewBox="0 0 24 24" width="${size}" height="${size}" fill="currentColor" style="display:inline-block;vertical-align:middle;color:#25D366;flex-shrink:0;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M8.53 7.33C8.37 7.33 8.1 7.39 7.87 7.64C7.65 7.89 7.02 8.48 7.02 9.69C7.02 10.9 7.9 12.07 8.02 12.23C8.14 12.4 9.75 14.9 12.24 15.96C14.31 16.85 14.73 16.67 15.18 16.63C15.64 16.59 16.64 16.03 16.85 15.45C17.05 14.86 17.05 14.36 16.99 14.26C16.93 14.16 16.77 14.1 16.52 13.97C16.27 13.85 15.06 13.25 14.83 13.17C14.61 13.09 14.45 13.05 14.28 13.29C14.12 13.54 13.65 14.1 13.51 14.26C13.37 14.43 13.22 14.45 12.98 14.33C12.73 14.2 11.93 13.94 10.99 13.1C10.25 12.44 9.75 11.63 9.61 11.39C9.46 11.14 9.59 11.01 9.72 10.88C9.83 10.77 9.97 10.59 10.1 10.43C10.22 10.28 10.26 10.16 10.34 9.99C10.42 9.83 10.38 9.69 10.32 9.56C10.26 9.44 9.79 8.28 9.59 7.81C9.4 7.36 9.2 7.42 9.05 7.41C8.91 7.41 8.75 7.41 8.59 7.41L8.53 7.33Z"/></svg>`;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getTeamCount() {
        const format = document.getElementById('tourneyFormat')?.value || 'single';
        if (format === 'double') {
            const select = document.getElementById('teamCountSelect');
            return select ? parseInt(select.value, 10) : 6;
        }
        const input = document.getElementById('teamCountInput');
        return input ? Math.max(3, Math.min(64, parseInt(input.value, 10) || 8)) : 8;
    }

    function syncTeamCountOptions() {
        const formatEl = document.getElementById('tourneyFormat');
        if (!formatEl) return;
        const format = formatEl.value;

        const inputWrapper = document.getElementById('teamCountInputWrapper');
        const countInput = document.getElementById('teamCountInput');
        const countSelect = document.getElementById('teamCountSelect');
        const warning = document.getElementById('doubleEliminationWarning');
        const hint = document.getElementById('teamCountHint');

        if (format === 'double') {
            if (inputWrapper) inputWrapper.style.display = 'none';
            if (countSelect) countSelect.style.display = 'block';
            if (warning) warning.hidden = false;
        } else {
            if (inputWrapper) inputWrapper.style.display = 'block';
            if (countSelect) countSelect.style.display = 'none';
            if (warning) warning.hidden = true;

            if (hint) {
                if (format === 'swiss') {
                    hint.textContent = 'Format Swiss Stage turnamen resmi M7 MLBB menggunakan 16 tim peserta.';
                    if (countInput) countInput.value = '16';
                } else if (format === 'league') {
                    hint.textContent = 'Format League System (kompetisi poin klasemen). Rekomendasi 4–16 tim.';
                } else {
                    hint.textContent = 'Bebas masukkan jumlah tim (3–64). Tim seed tertinggi otomatis lolos jika bukan pangkat 2.';
                }
            }
        }
    }

    function renderTeamInputs(existingTeams = null) {
        const container = document.getElementById('teamInputsGrid');
        if (!container) return;
        const count = getTeamCount();
        const currentList = existingTeams || (tournamentState?.teams) || null;
        container.replaceChildren();

        for (let i = 0; i < count; i++) {
            const seed = i + 1;
            const existing = currentList && currentList[i] ? currentList[i] : null;
            const defaultName = defaultTeamNames[i] || `Nama Tim ${seed}`;
            const nameVal = existing?.name || defaultName;
            const waVal = existing?.wa || existing?.whatsapp || '';
            const logoVal = existing?.logo || presetLogos[i % presetLogos.length];

            const item = document.createElement('div');
            item.className = 'team-input-item';
            item.style.cssText = 'position:relative;display:flex;align-items:center;gap:6px;border-radius:6px;padding:3px 10px 3px 8px;height:38px;box-sizing:border-box;transition:all 0.15s ease;';

            const seedBadge = document.createElement('span');
            seedBadge.className = 'seed-badge';
            seedBadge.style.cssText = 'font-weight:800;font-family:monospace;font-size:11px;width:20px;flex-shrink:0;letter-spacing:0.5px;text-align:center;';
            seedBadge.textContent = seed < 10 ? `0${seed}` : `${seed}`;

            const logoBtn = document.createElement('button');
            logoBtn.type = 'button';
            logoBtn.className = 'team-input-logo-btn';
            logoBtn.dataset.seed = seed;
            logoBtn.title = HAS_LOGO_ACCESS ? 'Ganti Logo Tim' : 'Aktifkan ROK Challenger / Immortal untuk kustomisasi logo';
            logoBtn.style.cssText = 'width:22px;height:22px;border-radius:4px;padding:0;cursor:pointer;flex-shrink:0;overflow:hidden;border:1px solid rgba(255,255,255,0.15);';

            const logoImg = document.createElement('img');
            logoImg.src = logoVal;
            logoImg.alt = nameVal;
            logoImg.id = `team_logo_preview_${i}`;
            logoImg.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;border-radius:4px;';
            logoImg.onerror = () => { logoImg.src = presetLogos[0]; };
            logoBtn.appendChild(logoImg);

            if (HAS_LOGO_ACCESS) {
                logoBtn.addEventListener('click', () => openLogoPicker(seed));
            } else {
                logoBtn.addEventListener('click', () => {
                    alert('Kustomisasi logo tim eksklusif untuk Member ROK Challenger & ROK Immortal.');
                });
            }

            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.className = 'team-name-input';
            nameInput.id = `team_input_${i}`;
            nameInput.value = nameVal;
            nameInput.placeholder = `Nama Tim ${seed}`;
            nameInput.maxLength = 60;
            nameInput.style.cssText = 'flex:1 1 auto;min-width:60px;background:transparent;border:none;outline:none;font-size:12px;font-weight:600;padding:2px 4px;box-shadow:none;';

            nameInput.addEventListener('input', () => {
                if (tournamentState && tournamentState.teams && tournamentState.teams[i]) {
                    const defaultName = defaultTeamNames[i] || `Nama Tim ${seed}`;
                    tournamentState.teams[i].name = nameInput.value.trim() || defaultName;

                    // If WhatsApp number is empty, ensure team is marked unclaimed and remove badge visually
                    if (!waInput.value.trim()) {
                        tournamentState.teams[i].is_claimed = false;
                        tournamentState.teams[i].claimed_at = null;
                        const badge = item.querySelector('.claimed-checkin-badge');
                        if (badge) badge.remove();
                    }
                    // TAHAN: Perubahan nama tim disimpan ke bagan saat klik 'Generate & Simpan Bagan'
                }
            });

            const divider = document.createElement('span');
            divider.className = 'team-input-divider';
            divider.style.cssText = 'width:1px;height:16px;flex-shrink:0;margin:0 2px;';

            const waInput = document.createElement('input');
            waInput.type = 'tel';
            waInput.className = 'team-wa-input';
            waInput.id = `team_wa_${i}`;
            waInput.value = waVal;
            waInput.placeholder = 'No WA';
            waInput.style.cssText = 'flex:0 0 85px;width:85px;background:transparent;border:none;outline:none;font-size:11px;font-family:monospace;padding:2px 4px;box-shadow:none;';

            waInput.addEventListener('input', () => {
                if (tournamentState && tournamentState.teams && tournamentState.teams[i]) {
                    const cleanVal = waInput.value.trim();
                    tournamentState.teams[i].wa = cleanVal;
                    tournamentState.teams[i].whatsapp = cleanVal;

                    if (!cleanVal) {
                        // User erased / deleted the WhatsApp number: remove check-in status and badge immediately
                        tournamentState.teams[i].is_claimed = false;
                        tournamentState.teams[i].claimed_at = null;
                        const badge = item.querySelector('.claimed-checkin-badge');
                        if (badge) badge.remove();
                    }
                    // TAHAN: Perubahan nomor WA disimpan ke bagan saat klik 'Generate & Simpan Bagan'
                }
            });

            const isActuallyClaimed = !!(existing?.is_claimed && (existing?.whatsapp || existing?.wa));
            if (isActuallyClaimed) {
                const claimedBadge = document.createElement('span');
                claimedBadge.className = 'claimed-checkin-badge';
                claimedBadge.style.cssText = 'position:absolute;top:-6px;right:12px;font-size:8.5px;padding:1px 7px;border-radius:999px;background:rgba(21,128,61,0.95);border:1px solid #4ade80;color:#ffffff;font-weight:800;letter-spacing:0.4px;backdrop-filter:blur(4px);box-shadow:0 2px 6px rgba(0,0,0,0.3);pointer-events:none;z-index:2;display:inline-flex;align-items:center;gap:3px;';
                claimedBadge.innerHTML = '<i data-lucide="check" style="width:10px;height:10px;"></i> Check-in';
                claimedBadge.title = 'Tim ini telah terdaftar melalui portal Pra-Turnamen';
                item.appendChild(claimedBadge);
            }

            if (IS_FREE) {
                item.append(seedBadge, nameInput, divider, waInput);
            } else {
                item.append(seedBadge, logoBtn, nameInput, divider, waInput);
            }
            container.appendChild(item);
        }
        window.renderLucideIcons?.();
    }

    function collectCurrentTeams() {
        const count = getTeamCount();
        const teams = [];
        for (let i = 0; i < count; i++) {
            const seed = i + 1;
            const nameEl = document.getElementById(`team_input_${i}`);
            const waEl = document.getElementById(`team_wa_${i}`);
            const logoImg = document.getElementById(`team_logo_preview_${i}`);
            const prev = (tournamentState && tournamentState.teams && tournamentState.teams[i]) ? tournamentState.teams[i] : {};

            const nameVal = nameEl ? nameEl.value.trim() : (prev.name || '');
            const finalName = nameVal || (defaultTeamNames[i] || `Nama Tim ${seed}`);

            // Respect the actual current input value; do NOT resurrect deleted number from prev
            const waVal = waEl ? waEl.value.trim() : (prev.whatsapp || prev.wa || '');
            const isClaimed = !!(prev.is_claimed && waVal);

            teams.push({
                ...prev,
                id: seed,
                seed: seed,
                name: finalName,
                wa: waVal,
                whatsapp: waVal,
                is_claimed: isClaimed,
                claimed_at: isClaimed ? (prev.claimed_at || null) : null,
                logo: IS_FREE ? null : (logoImg?.src || prev.logo || presetLogos[i % presetLogos.length])
            });
        }
        return teams;
    }

    // ====================================================
    // BRACKET STRUCTURE GENERATORS (MATCH CALCULATIONS)
    // ====================================================
    function newMatch(id, round, number, source1, source2, extra = {}) {
        return {
            id,
            round,
            number,
            source1,
            source2,
            score1: null,
            score2: null,
            winner: null,
            loser: null,
            ...extra
        };
    }

    function teamSource(team) {
        return { type: 'team', team };
    }

    function matchSource(match, outcome = 'winner') {
        return { type: outcome, matchId: match.id };
    }

    function resolveSource(source, matches) {
        if (!source) return null;
        if (source.type === 'team') {
            if (tournamentState && Array.isArray(tournamentState.teams) && source.team) {
                const live = tournamentState.teams.find(t =>
                    t.id === source.team.id || t.seed === source.team.seed
                );
                if (live) return live;
            }
            return source.team;
        }
        const parent = matches.find(m => m.id === source.matchId);
        if (!parent) return null;
        return source.type === 'loser' ? parent.loser : parent.winner;
    }

    function calculateMatch(match, matches) {
        const a = resolveSource(match.source1, matches);
        const b = resolveSource(match.source2, matches);
        match.team1 = a;
        match.team2 = b;
        match.winner = null;
        match.loser = null;

        if (a && !b && match.autoAdvance) {
            match.winner = a;
            return;
        }
        if (b && !a && match.autoAdvance) {
            match.winner = b;
            return;
        }
        if (a && b && Number.isInteger(match.score1) && Number.isInteger(match.score2) && match.score1 !== match.score2) {
            match.winner = match.score1 > match.score2 ? a : b;
            match.loser = match.score1 > match.score2 ? b : a;
        }
    }

    function cascadeMatchUpdates(parentMatch, matches) {
        if (!parentMatch || !matches) return;
        const children = matches.filter(m =>
            (m.source1 && m.source1.matchId === parentMatch.id) ||
            (m.source2 && m.source2.matchId === parentMatch.id)
        );
        children.forEach(child => {
            const oldT1 = child.team1;
            const oldT2 = child.team2;
            calculateMatch(child, matches);
            if ((oldT1 && child.team1?.id !== oldT1.id) || (oldT2 && child.team2?.id !== oldT2.id)) {
                child.score1 = null;
                child.score2 = null;
                child.winner = null;
                child.loser = null;
                cascadeMatchUpdates(child, matches);
            }
        });
    }

    function getSeededPairs(size) {
        if (size <= 2) return [[1, 2]];
        let seeds = [1, 2];
        while (seeds.length < size) {
            const next = [];
            const sum = seeds.length * 2 + 1;
            for (const s of seeds) {
                next.push(s);
                next.push(sum - s);
            }
            seeds = next;
        }
        const pairs = [];
        for (let i = 0; i < size; i += 2) {
            pairs.push([seeds[i], seeds[i + 1]]);
        }
        return pairs;
    }

    function getBoSettings() {
        if (IS_READ_ONLY) {
            const isCustom = document.getElementById('boCustomEnabled')?.value === '1';
            if (!isCustom) {
                return { customBo: false, semi: 'BO1', final: 'BO1', bronzeMatch: false, bronzeBo: 'BO1' };
            }
            return {
                customBo: true,
                semi: document.getElementById('boSemiFormat')?.value || 'BO3',
                final: document.getElementById('boFinalFormat')?.value || 'BO5',
                bronzeMatch: document.getElementById('boBronzeEnabled')?.value === '1',
                bronzeBo: document.getElementById('boBronzeFormat')?.value || 'BO3'
            };
        }
        const isCustom = document.getElementById('enableCustomBoCheckbox')?.checked ?? false;
        if (!isCustom) {
            return { customBo: false, semi: 'BO1', final: 'BO1', bronzeMatch: false, bronzeBo: 'BO1' };
        }
        return {
            customBo: true,
            semi: document.getElementById('semiFinalBoSelect')?.value || 'BO3',
            final: document.getElementById('grandFinalBoSelect')?.value || 'BO5',
            bronzeMatch: document.getElementById('enableBronzeMatchCheckbox')?.checked ?? false,
            bronzeBo: document.getElementById('bronzeMatchBoSelect')?.value || 'BO3'
        };
    }

    function makeElimination(teams, boSettings = null) {
        const bo = boSettings || getBoSettings();
        const customBo = Boolean(bo && bo.customBo);
        const semiBo = customBo ? (bo.semi || 'BO3') : 'BO1';
        const finalBo = customBo ? (bo.final || 'BO5') : 'BO1';
        const bronzeMatch = customBo ? Boolean(bo.bronzeMatch) : false;
        const bronzeBo = customBo ? (bo.bronzeBo || 'BO3') : 'BO1';

        const N = teams.length;
        let P = 1;
        while (P < N) P *= 2;

        const pairs = getSeededPairs(P);
        const matches = [];
        const rounds = [];

        // Round 1
        const round1Matches = [];
        let matchIdx = 1;
        const round1Count = pairs.length;
        const isRound1Semi = (round1Count === 2);
        const isRound1Final = (round1Count === 1);
        const round1Bo = isRound1Final ? finalBo : (isRound1Semi ? semiBo : 'BO1');
        const round1Title = eliminationRoundTitle(round1Count, P, 1);

        for (let i = 0; i < pairs.length; i++) {
            const [seed1, seed2] = pairs[i];
            const team1 = teams[seed1 - 1] || null;
            const team2 = (seed2 <= N) ? teams[seed2 - 1] : null;

            if (team1 && !team2) {
                const m = newMatch(`R1M${matchIdx++}`, 1, matchIdx, teamSource(team1), null, {
                    autoAdvance: true,
                    autoAdvanceNote: 'Lolos Otomatis ke Babak Berikutnya',
                    bo: round1Bo,
                    matchTitle: `${round1Title} #${i + 1}`
                });
                matches.push(m);
                round1Matches.push(m);
            } else if (team1 && team2) {
                const m = newMatch(`R1M${matchIdx++}`, 1, matchIdx, teamSource(team1), teamSource(team2), {
                    bo: round1Bo,
                    matchTitle: `${round1Title} #${i + 1}`
                });
                matches.push(m);
                round1Matches.push(m);
            }
        }

        rounds.push({
            title: round1Title,
            matches: round1Matches
        });

        // Subsequent rounds
        let prevMatches = round1Matches;
        let roundNo = 2;
        let matchCount = prevMatches.length / 2;
        let semiMatches = isRound1Semi ? round1Matches : null;

        while (matchCount >= 1) {
            const isSemi = (matchCount === 2);
            const isFinal = (matchCount === 1);
            const currentBo = isFinal ? finalBo : (isSemi ? semiBo : 'BO1');
            const roundTitle = eliminationRoundTitle(matchCount, P, roundNo);

            const currentMatches = [];
            for (let i = 0; i < matchCount; i++) {
                const extra = {
                    bo: currentBo,
                    matchTitle: `${roundTitle} #${i + 1}`
                };
                if (isFinal) {
                    extra.isFinal = true;
                    extra.matchTitle = 'Grand Final';
                }
                const m = newMatch(`R${roundNo}M${i + 1}`, roundNo, i + 1,
                    matchSource(prevMatches[i * 2]),
                    matchSource(prevMatches[i * 2 + 1]),
                    extra
                );
                matches.push(m);
                currentMatches.push(m);
            }

            if (isSemi) {
                semiMatches = currentMatches;
            }

            // If this is Grand Final round and 3rd place match is enabled
            if (isFinal && bronzeMatch && semiMatches && semiMatches.length === 2) {
                const bm = newMatch('BM1', roundNo, 2,
                    matchSource(semiMatches[0], 'loser'),
                    matchSource(semiMatches[1], 'loser'),
                    {
                        isBronze: true,
                        bo: bronzeBo,
                        matchTitle: 'Perebutan Juara 3'
                    }
                );
                matches.push(bm);
                rounds.push({
                    title: 'Grand Final & Juara 3',
                    matches: [currentMatches[0], bm]
                });
            } else {
                rounds.push({
                    title: roundTitle,
                    matches: currentMatches
                });
            }

            prevMatches = currentMatches;
            matchCount /= 2;
            roundNo++;
        }

        const grandFinalMatch = matches.find(m => m.isFinal) || prevMatches[0];
        const bronzeFinalMatch = matches.find(m => m.isBronze) || null;

        return {
            matches,
            rounds,
            championSource: grandFinalMatch ? matchSource(grandFinalMatch) : null,
            bronzeSource: bronzeFinalMatch ? matchSource(bronzeFinalMatch) : null,
            boSettings: bo
        };
    }

    function eliminationRoundTitle(matchCount, totalSize, roundNo) {
        if (matchCount === 1) return 'Grand Final';
        if (matchCount === 2) return 'Semi Finals';
        if (matchCount === 4) return 'Quarter Finals';
        if (matchCount === 8) return 'Babak 16 Besar';
        return `Babak Penyisihan (Ronde ${roundNo})`;
    }

    function makeDoubleElimination(teams) {
        if (teams.length === 6) {
            const matches = [], rounds = [];
            const add = (id, round, number, a, b, title, extra = {}) => {
                const match = newMatch(id, round, number, a, b, extra);
                matches.push(match);
                let group = rounds.find(item => item.title === title);
                if (!group) { group = { title, matches: [] }; rounds.push(group); }
                group.matches.push(match);
                return match;
            };

            const deciding1 = add('M1', 1, 1, teamSource(teams[2]), teamSource(teams[5]), 'Babak Penentuan', { eliminateLoser: true });
            const deciding2 = add('M2', 1, 2, teamSource(teams[3]), teamSource(teams[4]), 'Babak Penentuan', { eliminateLoser: true });

            const upper1a = add('M3', 2, 1, teamSource(teams[0]), matchSource(deciding1), 'Upper Bracket — Round 1');
            const upper1b = add('M4', 2, 2, teamSource(teams[1]), matchSource(deciding2), 'Upper Bracket — Round 1');

            const lower1 = add('M5', 3, 1, matchSource(upper1a, 'loser'), matchSource(upper1b, 'loser'), 'Lower Bracket — Round 1');
            const upperFinal = add('M6', 4, 1, matchSource(upper1a), matchSource(upper1b), 'Upper Bracket Final');

            const lowerFinal = add('M7', 5, 1, matchSource(lower1), matchSource(upperFinal, 'loser'), 'Lower Bracket — Round 2');
            const grandFinal = add('M8', 6, 1, matchSource(upperFinal), matchSource(lowerFinal), 'Grand Final');
            return { matches, rounds, championSource: matchSource(grandFinal) };
        }

        // 4 teams
        const matches = [], rounds = [];
        let upperPrev = teams.map(teamSource), upperRound = 1, upperCount = teams.length / 2;
        const upperRounds = [];
        while (upperCount >= 1) {
            const current = [];
            for (let i = 0; i < upperCount; i++) {
                const m = newMatch(`U${upperRound}M${i+1}`, upperRound, i+1, upperPrev[i*2], upperPrev[i*2+1]);
                matches.push(m); current.push(m);
            }
            upperRounds.push(current);
            rounds.push({ title: upperCount === 1 ? 'Upper Bracket Final' : `Upper Bracket — Round ${upperRound}`, matches: current });
            upperPrev = current.map(m => matchSource(m));
            upperCount /= 2;
            upperRound++;
        }

        let lowerSources = upperRounds[0].map(m => matchSource(m, 'loser'));
        let lowerRoundNo = 1;
        let lowerMatches = [];
        for (let i = 0; i < lowerSources.length; i += 2) {
            const m = newMatch(`L${lowerRoundNo}M${i/2+1}`, lowerRoundNo, i/2+1, lowerSources[i], lowerSources[i+1]);
            matches.push(m); lowerMatches.push(m);
        }
        rounds.push({ title: 'Lower Bracket — Final', matches: lowerMatches });
        const grandFinal = newMatch('GF1', 99, 1, upperPrev[0], matchSource(lowerMatches[0]), { grandFinal: true });
        matches.push(grandFinal);
        rounds.push({ title: 'Grand Final', matches: [grandFinal] });
        return { matches, rounds, championSource: matchSource(grandFinal) };
    }

    function makeRoundRobin(teams) {
        const matches = [], rounds = [];
        const roster = [...teams];
        if (roster.length % 2) roster.push(null);
        const n = roster.length, totalRounds = n - 1;
        let rotating = [...roster];
        for (let r = 0; r < totalRounds; r++) {
            const list = [];
            for (let i = 0; i < n / 2; i++) {
                const a = rotating[i], b = rotating[n - 1 - i];
                if (a && b) {
                    const m = newMatch(`R${r + 1}M${list.length + 1}`, r + 1, list.length + 1, teamSource(a), teamSource(b));
                    matches.push(m);
                    list.push(m);
                }
            }
            rounds.push({ title: `League System — Pekan ${r + 1}`, matches: list });
            rotating = [rotating[0], rotating[n - 1], ...rotating.slice(1, n - 1)];
        }
        return { matches, rounds, championSource: null };
    }

    // ====================================================
    // SWISS STAGE (M7 MLBB FORMAT)
    // ====================================================
    function getSwissTeamRecords(teams, allMatches) {
        const records = new Map();
        teams.forEach(t => {
            records.set(t.id, {
                team: t,
                wins: 0,
                losses: 0,
                playedOpponents: new Set(),
                status: 'active'
            });
        });

        allMatches.forEach(m => {
            if (!m.winner || !m.loser) return;
            const w = records.get(m.winner.id);
            const l = records.get(m.loser.id);
            if (w && l) {
                w.wins++;
                w.playedOpponents.add(l.team.id);
                l.losses++;
                l.playedOpponents.add(w.team.id);
            }
        });

        records.forEach(rec => {
            if (rec.wins >= 3) rec.status = 'qualified';
            else if (rec.losses >= 3) rec.status = 'eliminated';
            else rec.status = 'active';
        });

        return records;
    }

    function buildSwissRoundM7(teams, allMatches, roundNumber) {
        const records = getSwissTeamRecords(teams, allMatches);
        const matches = [];

        if (roundNumber === 1) {
            const sorted = [...teams].sort((a, b) => a.seed - b.seed);
            const half = sorted.length / 2;
            for (let i = 0; i < half; i++) {
                const teamA = sorted[i];
                const teamB = sorted[i + half];
                matches.push(newMatch(`SW1M${i + 1}`, 1, i + 1, teamSource(teamA), teamSource(teamB), {
                    poolLabel: 'POOL 0-0 (STAGE 1)'
                }));
            }
            return {
                title: `Swiss Stage — Stage 1 (Rekor 0-0)`,
                roundNumber: 1,
                matches
            };
        }

        const activeTeams = [...records.values()].filter(r => r.status === 'active');
        const groups = new Map();
        activeTeams.forEach(r => {
            const key = `${r.wins}-${r.losses}`;
            if (!groups.has(key)) groups.set(key, []);
            groups.get(key).push(r);
        });

        let matchIdx = 1;
        const sortedKeys = [...groups.keys()].sort((a, b) => {
            const [wA, lA] = a.split('-').map(Number);
            const [wB, lB] = b.split('-').map(Number);
            return wB - wA || lA - lB;
        });

        sortedKeys.forEach(key => {
            const pool = groups.get(key);
            const poolTeams = pool.map(p => p.team).sort((a, b) => a.seed - b.seed);
            const unassigned = [...poolTeams];

            let poolTitle = `POOL ${key}`;
            if (key === '2-0' || key === '2-1') poolTitle += ' (MATCH KUALIFIKASI)';
            else if (key === '0-2' || key === '1-2') poolTitle += ' (MATCH ELIMINASI)';
            else if (key === '2-2') poolTitle += ' (MATCH PENENTUAN FINAL)';

            while (unassigned.length >= 2) {
                const teamA = unassigned.shift();
                const recA = records.get(teamA.id);

                let opponentIdx = -1;
                for (let i = 0; i < unassigned.length; i++) {
                    const teamB = unassigned[i];
                    if (!recA.playedOpponents.has(teamB.id)) {
                        opponentIdx = i;
                        break;
                    }
                }
                if (opponentIdx === -1) opponentIdx = 0;

                const teamB = unassigned.splice(opponentIdx, 1)[0];
                matches.push(newMatch(`SW${roundNumber}M${matchIdx++}`, roundNumber, matchIdx, teamSource(teamA), teamSource(teamB), {
                    poolLabel: poolTitle
                }));
            }
        });

        return {
            title: `Swiss Stage — Stage ${roundNumber}`,
            roundNumber,
            matches
        };
    }

    function advanceSwiss(state) {
        state.matches.forEach(m => calculateMatch(m, state.matches));
        const current = state.rounds[state.rounds.length - 1];

        if (current && current.matches.length && current.matches.every(m => m.winner && m.loser) && state.rounds.length < 5) {
            const nextRoundNo = state.rounds.length + 1;
            const next = buildSwissRoundM7(state.teams, state.matches, nextRoundNo);
            if (next.matches.length > 0) {
                state.rounds.push(next);
                state.matches.push(...next.matches);
            }
        }
        renderTournament(true);
    }

    // ====================================================
    // TEAM STATS & POINTS CALCULATION
    // ====================================================
    function computeAllTeamStats(state) {
        const stats = new Map();
        (state.teams || []).forEach(t => {
            stats.set(t.id, {
                team: t,
                played: 0,
                wins: 0,
                losses: 0,
                gameWon: 0,
                gameLost: 0,
                points: 0
            });
        });

        (state.matches || []).forEach(m => {
            if (m.autoAdvance) return;
            const a = m.team1;
            const b = m.team2;
            if (!a || !b) return;

            const s1 = Number.isInteger(m.score1) ? m.score1 : null;
            const s2 = Number.isInteger(m.score2) ? m.score2 : null;
            if (s1 === null || s2 === null) return;

            const stA = stats.get(a.id);
            const stB = stats.get(b.id);
            if (stA && stB) {
                stA.played++;
                stB.played++;
                stA.gameWon += s1;
                stA.gameLost += s2;
                stB.gameWon += s2;
                stB.gameLost += s1;

                if (s1 > s2) {
                    stA.wins++;
                    stB.losses++;
                    stA.points += 3 + s1;
                    stB.points += s2;
                } else if (s2 > s1) {
                    stB.wins++;
                    stA.losses++;
                    stB.points += 3 + s2;
                    stA.points += s1;
                }
            }
        });

        return stats;
    }

    // ====================================================
    // MODAL OPENERS
    // ====================================================
    function openTeamModal(teamId) {
        if (!tournamentState) return;
        const team = tournamentState.teams.find(t => t.id === Number(teamId));
        if (!team) return;

        const statsMap = computeAllTeamStats(tournamentState);
        const st = statsMap.get(team.id) || { points: 0, wins: 0, losses: 0, gameWon: 0, gameLost: 0 };

        const modal = document.getElementById('rokTeamModal');
        const logoEl = document.getElementById('modalTeamLogo');
        const nameEl = document.getElementById('modalTeamName');
        const seedEl = document.getElementById('modalTeamSeed');
        const tourneyEl = document.getElementById('modalTeamTourney');
        const pointsEl = document.getElementById('modalTeamPoints');
        const winsEl = document.getElementById('modalTeamWins');
        const lossesEl = document.getElementById('modalTeamLosses');
        const gameEl = document.getElementById('modalTeamGameScore');
        const waNumEl = document.getElementById('modalTeamWaNumber');
        const waBtnEl = document.getElementById('modalTeamWaBtn');

        if (logoEl) {
            const logoWrap = logoEl.closest('.team-modal-logo-wrap');
            if (IS_FREE) {
                logoEl.style.display = 'none';
                if (logoWrap) logoWrap.style.display = 'none';
            } else {
                logoEl.style.display = 'block';
                if (logoWrap) logoWrap.style.display = 'flex';
                logoEl.src = team.logo || presetLogos[0];
            }
        }
        if (nameEl) nameEl.textContent = team.name;
        if (seedEl) seedEl.textContent = `SEED #${team.seed}`;
        if (tourneyEl) tourneyEl.textContent = tournamentState.title || 'ROK Web Tournament';
        if (pointsEl) pointsEl.textContent = `${st.points} Pts`;
        if (winsEl) winsEl.textContent = `${st.wins}W`;
        if (lossesEl) lossesEl.textContent = `${st.losses}L`;
        if (gameEl) gameEl.textContent = `${st.gameWon}-${st.gameLost} (${st.gameWon - st.gameLost >= 0 ? '+' : ''}${st.gameWon - st.gameLost})`;

        const cleanWa = normalizeWaNumber(team.wa);
        if (waNumEl) waNumEl.textContent = formatWaDisplay(team.wa);
        if (waBtnEl) {
            if (cleanWa) {
                const msg = encodeURIComponent(`Halo ${team.name}, kami dari panitia / lawan tanding di turnamen ${tournamentState.title}. Ingin koordinasi lobby pertandingan.`);
                waBtnEl.href = `https://wa.me/${cleanWa}?text=${msg}`;
                waBtnEl.style.opacity = '1';
                waBtnEl.style.pointerEvents = 'auto';
                waBtnEl.innerHTML = `<span style="display:inline-flex;align-items:center;gap:6px;"><i data-lucide="message-circle" style="width:16px;height:16px;"></i> Chat ${team.name} via WhatsApp</span>`;
            } else {
                waBtnEl.href = '#';
                waBtnEl.style.opacity = '0.5';
                waBtnEl.style.pointerEvents = 'none';
                waBtnEl.innerHTML = `<span style="display:inline-flex;align-items:center;gap:6px;"><i data-lucide="smartphone" style="width:16px;height:16px;"></i> Nomor WhatsApp Belum Diisi</span>`;
            }
            window.renderLucideIcons?.();
        }

        if (modal) modal.hidden = false;
    }

    function closeTeamModal() {
        const modal = document.getElementById('rokTeamModal');
        if (modal) modal.hidden = true;
    }

    function openLogoPicker(seed) {
        selectedSeedForLogo = seed;
        const modal = document.getElementById('rokLogoPickerModal');
        const grid = document.getElementById('presetLogosGrid');
        if (grid) {
            grid.replaceChildren();
            presetLogos.forEach(logoUrl => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'logo-preset-item-btn';
                btn.style.cssText = 'padding:6px;background:var(--rok-surface-card);border:1px solid var(--rok-surface-border);border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;';
                const img = document.createElement('img');
                img.src = logoUrl;
                img.style.cssText = 'width:42px;height:42px;border-radius:50%;object-fit:cover;display:block;';
                btn.appendChild(img);

                btn.addEventListener('click', () => {
                    applyTeamLogo(selectedSeedForLogo, logoUrl);
                    closeLogoPicker();
                });
                grid.appendChild(btn);
            });
        }
        if (modal) modal.hidden = false;
    }

    function closeLogoPicker() {
        const modal = document.getElementById('rokLogoPickerModal');
        if (modal) modal.hidden = true;
        selectedSeedForLogo = null;
    }

    function applyTeamLogo(seed, logoUrl) {
        const idx = seed - 1;
        const preview = document.getElementById(`team_logo_preview_${idx}`);
        if (preview) preview.src = logoUrl;
        if (tournamentState && tournamentState.teams && tournamentState.teams[idx]) {
            tournamentState.teams[idx].logo = logoUrl;
            renderTournament(true);
        }
    }

    // ====================================================
    // EDIT MATCH RESULT MODAL LOGIC
    // ====================================================
    let activeEditingMatchId = null;

    function openMatchEditModal(matchId) {
        if (!tournamentState || !tournamentState.matches) return;
        const match = tournamentState.matches.find(m => m.id === matchId);
        if (!match) return;

        activeEditingMatchId = matchId;
        const modal = document.getElementById('matchEditModal');
        if (!modal) return;

        const a = resolveSource(match.source1, tournamentState.matches);
        const b = resolveSource(match.source2, tournamentState.matches);

        const badgeEl = document.getElementById('modalMatchBadge');
        const boBadgeEl = document.getElementById('modalMatchBoBadge');
        const titleEl = document.getElementById('modalMatchTitle');
        const ruleHintEl = document.getElementById('modalSeriesRuleText');

        const seriesBo = match.bo || 'BO1';
        if (boBadgeEl) boBadgeEl.textContent = seriesBo;

        if (badgeEl) {
            if (match.isFinal) {
                badgeEl.textContent = 'GRAND FINAL';
                badgeEl.style.background = 'rgba(245,158,11,0.2)';
                badgeEl.style.color = '#fbbf24';
            } else if (match.isBronze) {
                badgeEl.textContent = 'PEREBUTAN JUARA 3';
                badgeEl.style.background = 'rgba(217,119,6,0.2)';
                badgeEl.style.color = '#f59e0b';
            } else {
                badgeEl.textContent = match.matchTitle || `MATCH ${match.id}`;
                badgeEl.style.background = 'rgba(255,255,255,0.08)';
                badgeEl.style.color = '#e2e8f0';
            }
        }

        if (titleEl) {
            titleEl.textContent = match.isFinal ? 'Ubah Hasil Grand Final' : (match.isBronze ? 'Ubah Hasil Perebutan Juara 3' : `Ubah Hasil Match (${match.id})`);
        }

        let winTarget = 1;
        if (seriesBo === 'BO3') winTarget = 2;
        else if (seriesBo === 'BO5') winTarget = 3;
        else if (seriesBo === 'BO7') winTarget = 4;

        if (ruleHintEl) {
            ruleHintEl.textContent = `Format ${seriesBo}: Tim pertama yang meraih ${winTarget} kemenangan dinyatakan sebagai pemenang seri pertandingan.`;
        }

        const t1Logo = document.getElementById('modalEditTeam1Logo');
        const t1Name = document.getElementById('modalEditTeam1Name');
        const s1Input = document.getElementById('modalScore1Input');
        if (t1Logo) t1Logo.src = a?.logo || presetLogos[0];
        if (t1Name) t1Name.textContent = a ? a.name : 'TBD (Menunggu babak sebelumnya)';
        if (s1Input) {
            s1Input.value = Number.isInteger(match.score1) ? match.score1 : '';
            s1Input.disabled = !a || !b;
        }

        const t2Logo = document.getElementById('modalEditTeam2Logo');
        const t2Name = document.getElementById('modalEditTeam2Name');
        const s2Input = document.getElementById('modalScore2Input');
        if (t2Logo) t2Logo.src = b?.logo || presetLogos[1];
        if (t2Name) t2Name.textContent = b ? b.name : 'TBD (Menunggu babak sebelumnya)';
        if (s2Input) {
            s2Input.value = Number.isInteger(match.score2) ? match.score2 : '';
            s2Input.disabled = !a || !b;
        }

        modal.hidden = false;
        window.renderLucideIcons?.();
    }

    function closeMatchEditModal() {
        const modal = document.getElementById('matchEditModal');
        if (modal) modal.hidden = true;
        activeEditingMatchId = null;
    }

    function saveMatchEdit() {
        if (!activeEditingMatchId || !tournamentState) return;
        const match = tournamentState.matches.find(m => m.id === activeEditingMatchId);
        if (!match) return;

        const s1Raw = document.getElementById('modalScore1Input')?.value;
        const s2Raw = document.getElementById('modalScore2Input')?.value;
        const s1 = (s1Raw !== '' && s1Raw !== null && !isNaN(parseInt(s1Raw, 10))) ? Math.max(0, parseInt(s1Raw, 10)) : null;
        const s2 = (s2Raw !== '' && s2Raw !== null && !isNaN(parseInt(s2Raw, 10))) ? Math.max(0, parseInt(s2Raw, 10)) : null;

        const oldWinnerId = match.winner?.id;
        match.score1 = s1;
        match.score2 = s2;
        calculateMatch(match, tournamentState.matches);

        // Record in scores map for compatibility
        if (!tournamentState.scores) tournamentState.scores = {};
        tournamentState.scores[match.id] = { s1: s1, s2: s2, score1: s1, score2: s2 };

        if (oldWinnerId && match.winner?.id !== oldWinnerId) {
            cascadeMatchUpdates(match, tournamentState.matches);
        }

        closeMatchEditModal();
        renderTournament(true);
    }

    // ====================================================
    // RENDER MATCH CARD (ORIGINAL ROBUST .gen-* STRUCTURE)
    // ====================================================
    function renderMatchCard(match, state) {
        const card = document.createElement('div');
        card.className = 'gen-match-box';
        card.dataset.matchId = match.id;
        card.dataset.round = match.round;

        if (match.isFinal) card.classList.add('gen-match-box--final');
        if (match.isBronze) card.classList.add('gen-match-box--bronze');

        // Top Header: BO Badge + Edit Result Button
        const headerEl = document.createElement('div');
        headerEl.className = 'gen-match-header';

        const badgeWrap = document.createElement('div');
        badgeWrap.className = 'gen-match-header__badges';

        const boBadge = document.createElement('span');
        boBadge.className = 'badge-bo';
        if (match.isFinal) {
            boBadge.className += ' badge-bo--final';
            boBadge.textContent = `🏆 Grand Final • ${match.bo || 'BO1'}`;
        } else if (match.isBronze) {
            boBadge.className += ' badge-bo--bronze';
            boBadge.textContent = `🥉 Juara 3 • ${match.bo || 'BO1'}`;
        } else if (match.stageTitle && match.stageTitle.includes('Semi')) {
            boBadge.className += ' badge-bo--semi';
            boBadge.textContent = `Semi Final • ${match.bo || 'BO1'}`;
        } else if (match.round && state.rounds && match.round === (state.rounds.length - 1)) {
            boBadge.className += ' badge-bo--semi';
            boBadge.textContent = `Semi Final • ${match.bo || 'BO1'}`;
        } else {
            boBadge.textContent = `Match • ${match.bo || 'BO1'}`;
        }
        badgeWrap.appendChild(boBadge);

        if (match.poolLabel) {
            const poolBadge = document.createElement('span');
            poolBadge.className = 'pool-tag-badge';
            poolBadge.style.cssText = 'font-size:9px;font-weight:800;padding:2px 7px;border-radius:999px;letter-spacing:0.5px;display:inline-flex;';
            if (match.poolLabel.includes('KUALIFIKASI')) {
                poolBadge.style.background = 'rgba(34,197,94,0.12)';
                poolBadge.style.color = '#4ade80';
                poolBadge.style.border = '1px solid rgba(34,197,94,0.25)';
            } else if (match.poolLabel.includes('ELIMINASI')) {
                poolBadge.style.background = 'rgba(239,68,68,0.12)';
                poolBadge.style.color = '#f87171';
                poolBadge.style.border = '1px solid rgba(239,68,68,0.25)';
            } else {
                poolBadge.style.background = 'rgba(148,163,184,0.1)';
                poolBadge.style.color = 'var(--rok-silver)';
                poolBadge.style.border = '1px solid rgba(255,255,255,0.06)';
            }
            poolBadge.textContent = match.poolLabel;
            badgeWrap.appendChild(poolBadge);
        }

        headerEl.appendChild(badgeWrap);

        if (!IS_READ_ONLY && !match.autoAdvance) {
            const editBtn = document.createElement('button');
            editBtn.type = 'button';
            editBtn.className = 'btn-edit-match';
            editBtn.title = 'Ubah skor & hasil pertandingan ini';
            editBtn.innerHTML = '<i data-lucide="edit-2" style="width:10px;height:10px;"></i> Ubah Hasil';
            editBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                openMatchEditModal(match.id);
            });
            headerEl.appendChild(editBtn);
        }

        card.appendChild(headerEl);

        const a = resolveSource(match.source1, state.matches);
        const b = resolveSource(match.source2, state.matches);

        // Auto-advance slot (Single Elimination BYE)
        if (match.autoAdvance) {
            const topRow = document.createElement('div');
            topRow.className = 'gen-team-row is-winner';
            topRow.style.cssText = 'padding:6px 8px;display:flex;align-items:center;justify-content:space-between;gap:8px;border-radius:8px;';

            const infoWrap = document.createElement('div');
            infoWrap.style.cssText = 'display:flex;align-items:center;gap:8px;flex:1 1 auto;min-width:0;overflow:hidden;';

            if (a && !IS_FREE) {
                const logoBtn = document.createElement('button');
                logoBtn.type = 'button';
                logoBtn.title = `Klik info Poin & Kontak WhatsApp ${a.name}`;
                logoBtn.style.cssText = 'background:none;border:none;padding:0;cursor:pointer;line-height:0;flex-shrink:0;';
                logoBtn.innerHTML = `<img src="${a.logo || presetLogos[0]}" alt="${a.name}" style="width:20px;height:20px;max-width:20px;max-height:20px;border-radius:50%;object-fit:cover;border:1px solid rgba(212,175,55,0.4);display:block;">`;
                logoBtn.addEventListener('click', () => openTeamModal(a.id));
                infoWrap.appendChild(logoBtn);
            }

            const name = document.createElement('span');
            name.className = 'gen-team-name';
            name.style.cssText = 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:700;flex:1 1 auto;min-width:0;cursor:pointer;transition:all 0.15s ease;';
            name.textContent = a ? a.name : 'TBD';
            if (a) {
                name.title = `Klik info Skor, Poin & Kontak WhatsApp ${a.name}`;
                name.addEventListener('click', () => openTeamModal(a.id));
                name.addEventListener('mouseenter', () => {
                    name.style.color = '#f59e0b';
                });
                name.addEventListener('mouseleave', () => {
                    name.style.color = '';
                });
            }
            infoWrap.appendChild(name);

            const byeBadge = document.createElement('span');
            byeBadge.style.cssText = 'font-size:9px;font-weight:800;color:#22c55e;background:rgba(34,197,94,0.12);padding:2px 7px;border-radius:999px;flex-shrink:0;letter-spacing:0.5px;';
            byeBadge.textContent = 'BYE';

            topRow.append(infoWrap, byeBadge);

            const advBadge = document.createElement('div');
            advBadge.className = 'badge-auto-advance';
            advBadge.style.cssText = 'color:#4ade80;font-size:10px;font-weight:700;padding:3px 6px;text-align:center;width:100%;box-sizing:border-box;display:inline-flex;align-items:center;justify-content:center;gap:4px;opacity:0.9;';
            advBadge.innerHTML = '<i data-lucide="check" style="width:11px;height:11px;"></i> Auto-Advance';

            card.append(topRow, advBadge);
            return card;
        }

        [a, b].forEach((team, i) => {
            const isWinner = match.winner && team && match.winner.id === team.id;
            const isLoser = match.winner && team && match.winner.id !== team.id;
            const row = document.createElement('div');
            row.className = `gen-team-row ${isWinner ? 'is-winner' : ''} ${isLoser ? 'is-loser' : ''}`.trim();

            const infoWrap = document.createElement('div');
            infoWrap.style.cssText = 'display:flex;align-items:center;gap:8px;flex:1 1 auto;min-width:0;overflow:hidden;';

            if (team && !IS_FREE) {
                const logoBtn = document.createElement('button');
                logoBtn.type = 'button';
                logoBtn.title = `Klik info Poin & Kontak WhatsApp ${team.name}`;
                logoBtn.style.cssText = 'background:none;border:none;padding:0;cursor:pointer;line-height:0;flex-shrink:0;';
                logoBtn.innerHTML = `<img src="${team.logo || presetLogos[0]}" alt="${team.name}" style="width:20px;height:20px;max-width:20px;max-height:20px;border-radius:50%;object-fit:cover;border:1px solid rgba(212,175,55,0.4);display:block;">`;
                logoBtn.addEventListener('click', () => openTeamModal(team.id));
                infoWrap.appendChild(logoBtn);
            }

            const name = document.createElement('span');
            name.className = 'gen-team-name';
            name.style.cssText = 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-width:0;flex:1 1 auto;cursor:pointer;font-weight:700;transition:all 0.15s ease;';
            name.textContent = team ? team.name : 'TBD';
            if (team) {
                name.title = `Klik info Skor, Poin & Kontak WhatsApp ${team.name}`;
                name.addEventListener('click', () => openTeamModal(team.id));
                name.addEventListener('mouseenter', () => {
                    name.style.color = '#f59e0b';
                });
                name.addEventListener('mouseleave', () => {
                    name.style.color = '';
                });
            }
            infoWrap.appendChild(name);

            if (team && team.wa) {
                const cleanWa = normalizeWaNumber(team.wa);
                const waLink = document.createElement('a');
                waLink.href = `https://wa.me/${cleanWa}?text=${encodeURIComponent('Halo ' + team.name + ', koordinasi match turnamen.')}`;
                waLink.target = '_blank';
                waLink.rel = 'noopener noreferrer';
                waLink.title = `Chat WA ${team.name}`;
                waLink.className = 'team-wa-badge-link';
                waLink.style.cssText = 'display:inline-flex;align-items:center;justify-content:center;padding:2px 6px;border-radius:999px;background:rgba(37,211,102,0.12);border:1px solid rgba(37,211,102,0.3);text-decoration:none;margin-left:4px;flex-shrink:0;cursor:pointer;line-height:1;transition:transform 0.15s;';
                waLink.innerHTML = getOfficialWhatsAppSvg(12);
                infoWrap.appendChild(waLink);
            }

            const input = document.createElement('input');
            input.type = 'number';
            input.min = '0';
            input.max = '999';
            input.step = '1';
            input.className = 'gen-score-input';
            input.setAttribute('aria-label', `Skor ${team?.name || 'TBD'}`);
            input.value = match[i === 0 ? 'score1' : 'score2'] ?? '';
            input.disabled = IS_READ_ONLY || !(a && b);
            input.placeholder = '–';

            input.addEventListener('change', () => {
                if (state.format === 'swiss' && match.round < state.rounds.length) {
                    state.rounds = state.rounds.slice(0, match.round);
                    state.matches = state.matches.filter(item => item.round <= match.round);
                }
                const key = i === 0 ? 'score1' : 'score2';
                const parsed = parseInt(input.value, 10);
                match[key] = isNaN(parsed) ? null : Math.max(0, parsed);

                calculateMatch(match, state.matches);

                if (state.format === 'swiss') {
                    advanceSwiss(state);
                } else {
                    renderTournament(true);
                }
            });

            row.append(infoWrap, input);
            card.appendChild(row);
        });

        return card;
    }

    // ====================================================
    // CLASSIC TOURNAMENT BRACKET CONNECTORS ("PANAH BAGAN")
    // Orthogonal tree lines M ... H ... V ... H ... with NO ARROWHEADS
    // ====================================================
    function drawAllBracketArrows() {
        const deck = document.getElementById('generatedTreeDeck');
        const canvas = document.getElementById('bracketZoomCanvas') || deck;
        if (!deck || !canvas || !tournamentState) return;

        // Only draw connector lines for elimination tree structures
        if (tournamentState.format === 'league' || tournamentState.format === 'swiss') {
            const oldSvg = canvas.querySelector('.bracket-connectors-svg');
            if (oldSvg) oldSvg.remove();
            return;
        }

        const oldSvg = canvas.querySelector('.bracket-connectors-svg');
        if (oldSvg) oldSvg.remove();

        const svgNS = 'http://www.w3.org/2000/svg';
        const svg = document.createElementNS(svgNS, 'svg');
        svg.setAttribute('class', 'bracket-connectors-svg');
        svg.setAttribute('aria-hidden', 'true');

        const width = Math.max(canvas.scrollWidth, canvas.clientWidth);
        const height = Math.max(canvas.scrollHeight, canvas.clientHeight);
        svg.setAttribute('width', String(width));
        svg.setAttribute('height', String(height));
        svg.style.cssText = `position:absolute;top:0;left:0;width:${width}px;height:${height}px;pointer-events:none;z-index:1;overflow:visible;`;

        canvas.prepend(svg);

        const canvasRect = canvas.getBoundingClientRect();
        const zoom = currentZoom || 1.0;
        const matchMap = new Map(tournamentState.matches.map(m => [m.id, m]));

        tournamentState.matches.forEach(targetMatch => {
            const targetEl = canvas.querySelector(`.gen-match-box[data-match-id="${targetMatch.id}"]`);
            if (!targetEl) return;

            const sources = [
                { src: targetMatch.source1, slot: 0 },
                { src: targetMatch.source2, slot: 1 }
            ];

            sources.forEach(({ src, slot }) => {
                if (!src || src.type === 'team' || !src.matchId) return;
                const sourceEl = canvas.querySelector(`.gen-match-box[data-match-id="${src.matchId}"]`);
                if (!sourceEl) return;

                const sourceMatch = matchMap.get(src.matchId);
                const sRect = sourceEl.getBoundingClientRect();
                const tRect = targetEl.getBoundingClientRect();

                const startX = (sRect.right - canvasRect.left) / zoom;
                const startY = (sRect.top - canvasRect.top + (sRect.height / 2)) / zoom;

                const endX = (tRect.left - canvasRect.left) / zoom;
                const rows = targetEl.querySelectorAll('.gen-team-row');
                let endY = (tRect.top - canvasRect.top + (tRect.height * (slot === 0 ? 0.35 : 0.70))) / zoom;
                if (rows[slot]) {
                    const rRect = rows[slot].getBoundingClientRect();
                    endY = (rRect.top - canvasRect.top + (rRect.height / 2)) / zoom;
                }

                if (endX <= startX + 5) return; // Only draw left-to-right connections

                const midX = startX + (endX - startX) * 0.5;
                const pathData = `M ${startX} ${startY} H ${midX} V ${endY} H ${endX}`;

                const path = document.createElementNS(svgNS, 'path');
                path.setAttribute('d', pathData);
                path.setAttribute('fill', 'none');

                const isWinnerResolved = (src.type === 'winner' && Boolean(sourceMatch?.winner)) ||
                                         (src.type === 'loser' && Boolean(sourceMatch?.loser));

                path.setAttribute('class', `bracket-arrow-path ${isWinnerResolved ? 'bracket-arrow-path--winner' : ''}`);

                if (src.type === 'loser') {
                    path.setAttribute('stroke-dasharray', '5 4');
                }

                svg.appendChild(path);
            });
        });

        // Connect the final match to the Champion box
        const champEl = canvas.querySelector('.champ-display-box');
        if (champEl && tournamentState.matches && tournamentState.matches.length > 0) {
            const finalMatch = tournamentState.matches.find(m => m.isFinal) || tournamentState.matches[tournamentState.matches.length - 1];
            if (finalMatch) {
                const finalEl = canvas.querySelector(`.gen-match-box[data-match-id="${finalMatch.id}"]`);
                if (finalEl) {
                    const fRect = finalEl.getBoundingClientRect();
                    const cRect = champEl.getBoundingClientRect();

                    const startX = (fRect.right - canvasRect.left) / zoom;
                    const startY = (fRect.top - canvasRect.top + (fRect.height / 2)) / zoom;
                    const endX = (cRect.left - canvasRect.left) / zoom;
                    const endY = (cRect.top - canvasRect.top + (cRect.height / 2)) / zoom;

                    if (endX > startX + 5) {
                        const midX = startX + (endX - startX) * 0.5;
                        const pathData = `M ${startX} ${startY} H ${midX} V ${endY} H ${endX}`;
                        const path = document.createElementNS(svgNS, 'path');
                        path.setAttribute('d', pathData);
                        path.setAttribute('fill', 'none');
                        const isChampResolved = Boolean(finalMatch.winner);
                        path.setAttribute('class', `bracket-arrow-path ${isChampResolved ? 'bracket-arrow-path--winner' : ''}`);
                        svg.appendChild(path);
                    }
                }
            }
        }
    }

    // ====================================================
    // RENDER DOUBLE ELIMINATION LAYOUT
    // ====================================================
    function renderDoubleBracket(deck, state) {
        const decidingRounds = state.rounds.filter(round => round.title === 'Babak Penentuan');
        const upperRounds = state.rounds.filter(round => round.title.startsWith('Upper Bracket'));
        const lowerRounds = state.rounds.filter(round => round.title.startsWith('Lower Bracket'));
        const grandFinalRounds = state.rounds.filter(round => round.title === 'Grand Final');

        const layout = document.createElement('div');
        layout.className = 'gen-double-layout';

        const makeColumns = (rounds, trackClass) => {
            const columns = document.createElement('div');
            columns.className = `gen-double-columns ${trackClass}`;
            rounds.forEach(round => {
                const col = document.createElement('div');
                col.className = 'gen-round-col gen-round-col--double';
                col.dataset.roundTitle = round.title;

                const heading = document.createElement('span');
                heading.className = 'gen-round-title';
                heading.textContent = round.title;
                col.appendChild(heading);

                const list = document.createElement('div');
                list.className = 'gen-matches-list';
                round.matches.forEach(match => list.appendChild(renderMatchCard(match, state)));
                col.appendChild(list);
                columns.appendChild(col);
            });
            return columns;
        };

        if (decidingRounds.length) {
            layout.classList.add('gen-double-layout--with-deciding');
            const deciding = document.createElement('section');
            deciding.className = 'gen-double-deciding';
            const heading = document.createElement('div');
            heading.className = 'gen-double-track-label';
            heading.textContent = 'BABAK PENENTUAN';
            deciding.appendChild(heading);
            const note = document.createElement('p');
            note.className = 'gen-double-deciding-note';
            note.textContent = 'Pemenang lanjut ke Upper Bracket; peserta yang kalah langsung tereliminasi.';
            deciding.appendChild(note);
            deciding.appendChild(makeColumns(decidingRounds, 'gen-double-deciding-columns'));
            layout.appendChild(deciding);
        }

        const upper = document.createElement('section');
        upper.className = 'gen-double-track gen-double-track--upper';
        const upperLabel = document.createElement('div');
        upperLabel.className = 'gen-double-track-label';
        upperLabel.textContent = 'UPPER BRACKET — JALUR PEMENANG';
        upper.appendChild(upperLabel);
        upper.appendChild(makeColumns(upperRounds, 'gen-double-upper-columns'));
        layout.appendChild(upper);

        const lower = document.createElement('section');
        lower.className = 'gen-double-track gen-double-track--lower';
        const lowerLabel = document.createElement('div');
        lowerLabel.className = 'gen-double-track-label';
        lowerLabel.textContent = 'LOWER BRACKET — JALUR BERTAHAN';
        lower.appendChild(lowerLabel);

        let displayedLowerRounds = lowerRounds;
        if (lowerRounds.length > 2) {
            displayedLowerRounds = [
                { title: 'Lower Bracket — Round 1', matches: lowerRounds[0].matches },
                {
                    title: 'Lower Bracket — Round 2 & Final',
                    matches: lowerRounds.slice(1).flatMap(round => round.matches)
                }
            ];
        }
        lower.appendChild(makeColumns(displayedLowerRounds, 'gen-double-lower-columns'));
        layout.appendChild(lower);

        if (grandFinalRounds.length) {
            const finalTrack = document.createElement('section');
            finalTrack.className = 'gen-double-grand-final';
            const heading = document.createElement('span');
            heading.className = 'gen-round-title';
            heading.textContent = 'GRAND FINAL';
            finalTrack.appendChild(heading);
            const list = document.createElement('div');
            list.className = 'gen-matches-list';
            grandFinalRounds.forEach(round => round.matches.forEach(match => list.appendChild(renderMatchCard(match, state))));
            finalTrack.appendChild(list);
            layout.appendChild(finalTrack);
        }

        deck.appendChild(layout);
    }

    // ====================================================
    // RENDER LEAGUE STANDINGS
    // ====================================================
    function renderStandings(deck, state) {
        const stats = computeAllTeamStats(state);
        const sorted = [...stats.values()].sort((a, b) => {
            return b.points - a.points || (b.gameWon - b.gameLost) - (a.gameWon - a.gameLost) || b.gameWon - a.gameWon;
        });

        const box = document.createElement('div');
        box.className = 'league-standings';
        box.innerHTML = `
            <h3>KLASEMEN SEMENTARA POIN LIGA</h3>
            <table class="standings-table">
                <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Tim</th>
                        <th>Main</th>
                        <th>M</th>
                        <th>K</th>
                        <th>+/-</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    ${sorted.map((item, idx) => `
                        <tr>
                            <td>#${idx + 1}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    ${IS_FREE ? '' : `<img src="${item.team.logo || presetLogos[0]}" style="width:18px;height:18px;border-radius:50%;object-fit:cover;">`}
                                    <span>${item.team.name}</span>
                                </div>
                            </td>
                            <td>${item.played}</td>
                            <td>${item.wins}</td>
                            <td>${item.losses}</td>
                            <td>${item.gameWon - item.gameLost >= 0 ? '+' : ''}${item.gameWon - item.gameLost}</td>
                            <td><strong>${item.points}</strong></td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        deck.appendChild(box);
    }

    // ====================================================
    // RENDER SWISS PROGRESS
    // ====================================================
    function renderSwissProgress(deck, state) {
        const records = getSwissTeamRecords(state.teams, state.matches);
        const qualifiedTeams = [...records.values()].filter(r => r.status === 'qualified');
        const eliminatedTeams = [...records.values()].filter(r => r.status === 'eliminated');
        const activeTeams = [...records.values()].filter(r => r.status === 'active');

        const overview = document.createElement('div');
        overview.className = 'swiss-overview-card';
        overview.style.cssText = 'min-width:280px;max-width:320px;flex-shrink:0;';
        overview.innerHTML = `
            <div style="background:var(--rok-surface-card);border:1px solid #22c55e;border-radius:var(--radius-md);padding:16px;margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <strong style="color:#22c55e;font-size:12px;font-weight:800;text-transform:uppercase;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check" style="width:13px;height:13px;"></i> LOLOS PLAYOFF (${qualifiedTeams.length}/8)</strong>
                    <span class="badge" style="background:rgba(34,197,94,0.15);color:#22c55e;font-size:10px;padding:2px 6px;border-radius:4px;">3 Menang</span>
                </div>
                ${qualifiedTeams.length === 0 ? '<p style="font-size:12px;color:var(--rok-muted-light);margin:0;">Belum ada tim yang lolos.</p>' : ''}
                <div style="display:grid;gap:6px;">
                    ${qualifiedTeams.map(q => `
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 8px;background:rgba(34,197,94,0.08);border-radius:4px;font-size:12px;">
                            <span style="font-weight:700;">${q.team.name}</span>
                            <strong style="color:#22c55e;font-family:monospace;">${q.wins}-${q.losses}</strong>
                        </div>
                    `).join('')}
                </div>
            </div>

            <div style="background:var(--rok-surface-card);border:1px solid rgba(239,68,68,0.4);border-radius:var(--radius-md);padding:16px;margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <strong style="color:#f87171;font-size:12px;font-weight:800;text-transform:uppercase;">&times; TERELIMINASI (${eliminatedTeams.length}/8)</strong>
                    <span class="badge" style="background:rgba(239,68,68,0.15);color:#f87171;font-size:10px;padding:2px 6px;border-radius:4px;">3 Kalah</span>
                </div>
                ${eliminatedTeams.length === 0 ? '<p style="font-size:12px;color:var(--rok-muted-light);margin:0;">Belum ada tim yang tereliminasi.</p>' : ''}
                <div style="display:grid;gap:6px;">
                    ${eliminatedTeams.map(e => `
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 8px;background:rgba(239,68,68,0.08);border-radius:4px;font-size:12px;">
                            <span style="color:var(--rok-silver);">${e.team.name}</span>
                            <strong style="color:#f87171;font-family:monospace;">${e.wins}-${e.losses}</strong>
                        </div>
                    `).join('')}
                </div>
            </div>

            <div style="background:var(--rok-surface-card);border:1px solid var(--rok-surface-border);border-radius:var(--radius-md);padding:16px;">
                <strong style="color:var(--rok-silver);font-size:12px;font-weight:800;text-transform:uppercase;display:block;margin-bottom:8px;">AKTIF BERKOMPETISI (${activeTeams.length})</strong>
                <div style="display:grid;gap:4px;max-height:220px;overflow-y:auto;">
                    ${activeTeams.map(a => `
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 6px;font-size:12px;border-bottom:1px solid var(--rok-surface-border);">
                            <span>${a.team.name}</span>
                            <span style="color:var(--rok-gold);font-family:monospace;font-weight:700;">${a.wins}W - ${a.losses}L</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
        deck.prepend(overview);
    }

    // ====================================================
    // MAIN RENDER TOURNAMENT
    // ====================================================
    function renderTournament(syncToServer = false) {
        const state = tournamentState;
        if (!state) return;

        state.matches.forEach(m => calculateMatch(m, state.matches));

        const titleEl = document.getElementById('outputTourneyTitle');
        const badgeEl = document.getElementById('outputFormatBadge');
        if (titleEl) titleEl.textContent = state.title;
        if (badgeEl) badgeEl.textContent = formatLabels[state.format] || state.format;

        const deck = document.getElementById('generatedTreeDeck');
        let canvas = document.getElementById('bracketZoomCanvas');
        if (!deck) return;
        if (!canvas) {
            canvas = document.createElement('div');
            canvas.className = 'bracket-zoom-canvas';
            canvas.id = 'bracketZoomCanvas';
            deck.appendChild(canvas);
        }
        canvas.replaceChildren();

        if (state.format === 'league') {
            renderStandings(canvas, state);
        }

        if (state.format === 'double') {
            renderDoubleBracket(canvas, state);
        } else {
            state.rounds.forEach(round => {
                const col = document.createElement('div');
                col.className = 'gen-round-col';
                col.dataset.roundTitle = round.title;

                const heading = document.createElement('span');
                heading.className = 'gen-round-title';
                heading.textContent = round.title;
                col.appendChild(heading);

                const list = document.createElement('div');
                list.className = 'gen-matches-list';
                round.matches.forEach(match => list.appendChild(renderMatchCard(match, state)));
                if (!round.matches.length) {
                    const empty = document.createElement('p');
                    empty.textContent = 'Tidak ada pertandingan pada ronde ini.';
                    list.appendChild(empty);
                }
                col.appendChild(list);
                canvas.appendChild(col);
            });
        }

        if (state.format === 'swiss') {
            renderSwissProgress(canvas, state);
        }

        if (state.format === 'single' || state.format === 'double') {
            const champion = state.championSource ? resolveSource(state.championSource, state.matches) : null;
            const finalMatch = state.matches.find(m => m.isFinal) || state.matches[state.matches.length - 1];
            const runnerUp = finalMatch ? finalMatch.loser : null;
            const bronzeMatch = state.matches.find(m => m.isBronze);
            const bronzeWinner = bronzeMatch ? bronzeMatch.winner : null;

            const col = document.createElement('div');
            col.className = 'gen-round-col gen-round-col--champ';
            const heading = document.createElement('span');
            heading.className = 'gen-round-title text-gold';
            heading.textContent = 'PODIUM & JUARA';
            col.appendChild(heading);

            // Juara 1 (Champion)
            const box = document.createElement('div');
            box.className = 'champ-display-box';
            const icon = document.createElement('span');
            icon.className = 'champ-icon';
            icon.innerHTML = '<i data-lucide="trophy" style="width:32px;height:32px;color:#f59e0b;"></i>';
            const name = document.createElement('strong');
            name.className = 'champ-crown-name';
            name.textContent = champion ? champion.name : 'Belum ditentukan';
            const sub = document.createElement('p');
            sub.className = 'champ-sub';
            sub.textContent = champion ? '🏆 Juara 1 Turnamen' : 'Isi skor pertandingan';
            box.append(icon, name, sub);
            col.appendChild(box);

            // Juara 2 (Runner-Up)
            if (finalMatch && runnerUp) {
                const ruBox = document.createElement('div');
                ruBox.className = 'champ-display-box';
                ruBox.style.cssText = 'margin-top: 12px; border-color: rgba(148, 163, 184, 0.35); background: linear-gradient(165deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.9) 100%);';
                ruBox.innerHTML = `
                    <span class="champ-icon" style="background: rgba(148, 163, 184, 0.15);"><i data-lucide="medal" style="width:22px;height:22px;color:#cbd5e1;"></i></span>
                    <strong class="champ-crown-name" style="font-size: 14px;">${runnerUp.name}</strong>
                    <p class="champ-sub" style="color: var(--rok-silver);">🥈 Juara 2 (Runner-Up)</p>
                `;
                col.appendChild(ruBox);
            }

            // Juara 3 (Bronze Match Winner)
            if (bronzeMatch) {
                const bBox = document.createElement('div');
                bBox.className = 'champ-display-box';
                bBox.style.cssText = 'margin-top: 12px; border-color: rgba(217, 119, 6, 0.35); background: linear-gradient(165deg, rgba(45, 26, 10, 0.7) 0%, rgba(20, 12, 5, 0.9) 100%);';
                bBox.innerHTML = `
                    <span class="champ-icon" style="background: rgba(217, 119, 6, 0.15);"><i data-lucide="award" style="width:22px;height:22px;color:#f59e0b;"></i></span>
                    <strong class="champ-crown-name" style="font-size: 14px;">${bronzeWinner ? bronzeWinner.name : 'Belum ditentukan'}</strong>
                    <p class="champ-sub" style="color: #f59e0b;">🥉 Juara 3 (Peringkat Ketiga)</p>
                `;
                col.appendChild(bBox);
            }

            canvas.appendChild(col);
        }

        window.renderLucideIcons?.();

        requestAnimationFrame(() => {
            drawAllBracketArrows();
            applyZoom(currentZoom);
        });

        if (syncToServer && !IS_READ_ONLY && !isApplyingRemoteUpdate) {
            scheduleRealtimeSync();
        }
    }

    let currentZoom = 1.0;
    const MIN_ZOOM = 0.5;
    const MAX_ZOOM = 2.0;
    const ZOOM_STEP = 0.15;

    function applyZoom(zoom) {
        currentZoom = Math.min(MAX_ZOOM, Math.max(MIN_ZOOM, Math.round(zoom * 100) / 100));
        const canvas = document.getElementById('bracketZoomCanvas');
        const zoomBtn = document.getElementById('btnZoomReset');
        const deck = document.getElementById('generatedTreeDeck');

        if (zoomBtn) {
            zoomBtn.textContent = `${Math.round(currentZoom * 100)}%`;
            zoomBtn.classList.remove('zoom-pulse-active');
            void zoomBtn.offsetWidth;
            zoomBtn.classList.add('zoom-pulse-active');
        }

        if (canvas && deck) {
            canvas.style.transform = `scale(${currentZoom})`;
            canvas.style.transformOrigin = '0 0';

            const baseW = canvas.offsetWidth;
            const baseH = canvas.offsetHeight;

            if (currentZoom < 1) {
                const pullH = Math.round(baseH * (1 - currentZoom));
                const pullW = Math.round(baseW * (1 - currentZoom));
                canvas.style.marginBottom = `-${pullH}px`;
                canvas.style.marginRight = `-${pullW}px`;
            } else if (currentZoom > 1) {
                const pushH = Math.round(baseH * (currentZoom - 1));
                const pushW = Math.round(baseW * (currentZoom - 1));
                canvas.style.marginBottom = `${pushH + 24}px`;
                canvas.style.marginRight = `${pushW + 24}px`;
            } else {
                canvas.style.marginBottom = '0px';
                canvas.style.marginRight = '0px';
            }

            deck.style.height = '';
        }
    }

    function buildTournament(syncToServer = true) {
        const title = (document.getElementById('tourneyName')?.value || '').trim() || 'ROK Community Cup 2026';
        const format = document.getElementById('tourneyFormat')?.value || 'single';
        const teams = collectCurrentTeams();
        const boSettings = getBoSettings();

        let engine;
        if (format === 'single') {
            engine = makeElimination(teams, boSettings);
        } else if (format === 'double') {
            engine = makeDoubleElimination(teams);
        } else if (format === 'league') {
            engine = makeRoundRobin(teams);
        } else {
            const round1 = buildSwissRoundM7(teams, [], 1);
            engine = {
                matches: round1.matches,
                rounds: [round1],
                championSource: null
            };
        }

        tournamentState = {
            title,
            format,
            teamCount: teams.length,
            teams,
            boSettings,
            ...engine
        };

        renderTournament(syncToServer);
    }

    function reshuffleTeams() {
        if (!confirm('Acak susunan tim / drawing bagan secara acak? Seluruh skor babak saat ini akan di-reset untuk babak baru yang adil.')) {
            return;
        }
        const teams = collectCurrentTeams();
        if (teams.length < 2) return;

        // Fisher-Yates shuffle
        for (let i = teams.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [teams[i], teams[j]] = [teams[j], teams[i]];
        }

        // Re-assign seeds 1..N
        teams.forEach((t, idx) => {
            t.seed = idx + 1;
            t.id = idx + 1;
        });

        // Re-render UI inputs with new positions
        renderTeamInputs(teams);

        // Rebuild tournament bracket
        const title = (document.getElementById('tourneyName')?.value || '').trim() || 'ROK Community Cup 2026';
        const format = document.getElementById('tourneyFormat')?.value || 'single';
        const boSettings = getBoSettings();

        let engine;
        if (format === 'single') {
            engine = makeElimination(teams, boSettings);
        } else if (format === 'double') {
            engine = makeDoubleElimination(teams);
        } else if (format === 'league') {
            engine = makeRoundRobin(teams);
        } else {
            const round1 = buildSwissRoundM7(teams, [], 1);
            engine = {
                matches: round1.matches,
                rounds: [round1],
                championSource: null
            };
        }

        tournamentState = {
            title,
            format,
            teamCount: teams.length,
            teams,
            boSettings,
            ...engine
        };

        renderTournament(true);
        alert('Drawing Berhasil! Urutan bagan tim turnamen telah diacak secara adil dan tersimpan.');
    }

    // ====================================================
    // REAL-TIME SYNC ENGINE
    // ====================================================
    function scheduleRealtimeSync() {
        if (IS_READ_ONLY || IS_PROMO || isApplyingRemoteUpdate || !tournamentState) return;
        clearTimeout(syncDebounceTimer);
        const pulse = document.getElementById('realtimePulseDot');
        if (pulse) pulse.style.background = '#f59e0b';

        syncDebounceTimer = setTimeout(async () => {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                let resp = await fetch(SYNC_API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ state: tournamentState })
                });
                if (resp.status === 404) {
                    resp = await fetch('/bracket/' + encodeURIComponent(ACTIVE_SLUG) + '/sync', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify({ state: tournamentState })
                    });
                }
                if (resp.ok) {
                    if (pulse) pulse.style.background = '#22c55e';
                    const lastSyncEl = document.getElementById('realtimeLastSync');
                    if (lastSyncEl) {
                        const now = new Date();
                        lastSyncEl.textContent = `Tersinkronisasi real-time pada ${now.toLocaleTimeString('id-ID')}.`;
                    }
                }
            } catch (err) {
                console.error('Failed to sync bracket state:', err);
            }
        }, 600);
    }

    function startAutoPolling() {
        if (!IS_READ_ONLY) return;
        autoPollTimer = setInterval(async () => {
            try {
                const pollUrl = STATE_API_URL + (STATE_API_URL.includes('?') ? '&' : '?') + '_t=' + Date.now();
                let resp = await fetch(pollUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (resp.status === 404) {
                    resp = await fetch('/bracket/' + encodeURIComponent(ACTIVE_SLUG) + '/state?_t=' + Date.now(), {
                        headers: { 'Accept': 'application/json' }
                    });
                }
                if (resp.ok) {
                    const data = await resp.json();
                    const remoteState = data.bracket_state || data.state;
                    if (remoteState && remoteState.updatedAt && (!tournamentState || remoteState.updatedAt !== tournamentState.updatedAt)) {
                        window.rokApplyStateUpdate(remoteState);
                        const pulse = document.getElementById('realtimePulseDot');
                        if (pulse) {
                            pulse.style.background = '#e2e8f0';
                            setTimeout(() => { pulse.style.background = '#22c55e'; }, 1000);
                        }
                    }
                }
            } catch (err) {
                // Silently handle offline/poll errors
            }
        }, 3000);
    }

    // Globally exposed real-time state updater for seamless integration
    window.rokApplyStateUpdate = function(remoteState) {
        if (!remoteState) return;
        isApplyingRemoteUpdate = true;
        if (remoteState.matches && remoteState.matches.length > 0) {
            tournamentState = remoteState;
        } else {
            const teams = remoteState.teams || [];
            const fmt = remoteState.format || 'single';
            const bo = remoteState.boSettings || getBoSettings();
            let eng;
            if (fmt === 'single') eng = makeElimination(teams, bo);
            else if (fmt === 'double') eng = makeDoubleElimination(teams);
            else if (fmt === 'league') eng = makeRoundRobin(teams);
            else {
                const r1 = buildSwissRoundM7(teams, [], 1);
                eng = { matches: r1.matches, rounds: [r1], championSource: null };
            }
            tournamentState = { ...remoteState, boSettings: bo, ...eng };
        }
        renderTournament(false);
        isApplyingRemoteUpdate = false;
    };

    window.rokGetTournamentState = function() {
        return tournamentState;
    };

    window.rokOpenMatchEdit = openMatchEditModal;

    // ====================================================
    // INITIALIZATION & EVENT LISTENERS
    // ====================================================
    document.addEventListener('DOMContentLoaded', () => {
        // Modal close buttons
        document.getElementById('closeTeamModalBtn')?.addEventListener('click', closeTeamModal);
        document.getElementById('closeTeamModalBackdrop')?.addEventListener('click', closeTeamModal);
        document.getElementById('closeLogoPickerBtn')?.addEventListener('click', closeLogoPicker);
        document.getElementById('closeLogoPickerBackdrop')?.addEventListener('click', closeLogoPicker);

        // Match Edit Modal buttons
        document.getElementById('closeMatchEditBtn')?.addEventListener('click', closeMatchEditModal);
        document.getElementById('closeMatchEditBackdrop')?.addEventListener('click', closeMatchEditModal);
        document.getElementById('btnCancelEditMatch')?.addEventListener('click', closeMatchEditModal);
        document.getElementById('btnSaveEditMatch')?.addEventListener('click', saveMatchEdit);

        // Match score adjustments in modal
        document.getElementById('btnScore1Minus')?.addEventListener('click', () => {
            const input = document.getElementById('modalScore1Input');
            if (input) input.value = Math.max(0, (parseInt(input.value, 10) || 0) - 1);
        });
        document.getElementById('btnScore1Plus')?.addEventListener('click', () => {
            const input = document.getElementById('modalScore1Input');
            if (input) input.value = (parseInt(input.value, 10) || 0) + 1;
        });
        document.getElementById('btnScore2Minus')?.addEventListener('click', () => {
            const input = document.getElementById('modalScore2Input');
            if (input) input.value = Math.max(0, (parseInt(input.value, 10) || 0) - 1);
        });
        document.getElementById('btnScore2Plus')?.addEventListener('click', () => {
            const input = document.getElementById('modalScore2Input');
            if (input) input.value = (parseInt(input.value, 10) || 0) + 1;
        });

        // Quick Win buttons based on series BO
        const getWinTarget = () => {
            const boText = document.getElementById('modalMatchBoBadge')?.textContent?.trim() || 'BO1';
            if (boText === 'BO3') return 2;
            if (boText === 'BO5') return 3;
            if (boText === 'BO7') return 4;
            return 1;
        };

        document.getElementById('btnQuickWin1')?.addEventListener('click', () => {
            const target = getWinTarget();
            const s1 = document.getElementById('modalScore1Input');
            const s2 = document.getElementById('modalScore2Input');
            if (s1 && s2) {
                s1.value = target;
                const currentS2 = parseInt(s2.value, 10) || 0;
                s2.value = (currentS2 >= target) ? Math.max(0, target - 1) : currentS2;
            }
        });

        document.getElementById('btnQuickWin2')?.addEventListener('click', () => {
            const target = getWinTarget();
            const s1 = document.getElementById('modalScore1Input');
            const s2 = document.getElementById('modalScore2Input');
            if (s1 && s2) {
                s2.value = target;
                const currentS1 = parseInt(s1.value, 10) || 0;
                s1.value = (currentS1 >= target) ? Math.max(0, target - 1) : currentS1;
            }
        });

        document.getElementById('btnResetSingleMatch')?.addEventListener('click', () => {
            const s1 = document.getElementById('modalScore1Input');
            const s2 = document.getElementById('modalScore2Input');
            if (s1) s1.value = '';
            if (s2) s2.value = '';
        });

        // Toggle Custom BO options panel
        const customBoCheckbox = document.getElementById('enableCustomBoCheckbox');
        const customBoPanel = document.getElementById('customBoOptionsPanel');
        customBoCheckbox?.addEventListener('change', () => {
            if (customBoPanel) {
                customBoPanel.style.display = customBoCheckbox.checked ? 'block' : 'none';
            }
        });

        // Toggle Bronze Match select wrapper
        const bronzeCheckbox = document.getElementById('enableBronzeMatchCheckbox');
        const bronzeWrapper = document.getElementById('bronzeBoSelectWrapper');
        bronzeCheckbox?.addEventListener('change', () => {
            if (bronzeWrapper) {
                bronzeWrapper.style.display = bronzeCheckbox.checked ? 'flex' : 'none';
            }
        });

        // Copy Pra-Tournament Link Button
        document.getElementById('copyPraTourneyLinkBtn')?.addEventListener('click', () => {
            const btn = document.getElementById('copyPraTourneyLinkBtn');
            const url = btn.getAttribute('data-url');
            if (url) {
                navigator.clipboard.writeText(url);
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i data-lucide="check" style="width:14px;height:14px;vertical-align:-2px;"></i> Link Pra-Turnamen Tersalin!';
                window.renderLucideIcons?.();
                setTimeout(() => { btn.innerHTML = originalHtml; window.renderLucideIcons?.(); }, 2000);
            }
        });

        // Copy Share Link Button
        document.getElementById('copyShareLinkBtn')?.addEventListener('click', () => {
            const input = document.getElementById('shareBracketUrlInput');
            if (input) {
                input.select();
                navigator.clipboard.writeText(input.value);
                const btn = document.getElementById('copyShareLinkBtn');
                if (btn) {
                    btn.innerHTML = '<i data-lucide="check" style="width:14px;height:14px;vertical-align:-2px;"></i> Link Tersalin!';
                    window.renderLucideIcons?.();
                    setTimeout(() => { btn.innerHTML = '<i data-lucide="copy" style="width:14px;height:14px;vertical-align:-2px;"></i> Salin Link Live'; window.renderLucideIcons?.(); }, 2000);
                }
            }
        });

        // Custom Logo File Input (Data URI for universal support)
        document.getElementById('customLogoFileInput')?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (event) => {
                if (selectedSeedForLogo && event.target?.result) {
                    applyTeamLogo(selectedSeedForLogo, event.target.result);
                    closeLogoPicker();
                }
            };
            reader.readAsDataURL(file);
        });

        // ====================================================
        // WIZARD / PAGINATION ENGINE (STEPS 1 -> 2 -> 3)
        // ====================================================
        let currentWizardStep = 1;
        let hasGeneratedBracket = false;

        function updateWizardNav(step) {
            const step1Btn = document.getElementById('wizardNavStep1');
            const step2Btn = document.getElementById('wizardNavStep2');
            const step3Btn = document.getElementById('wizardNavStep3');

            const updatePill = (btn, pillStep) => {
                if (!btn) return;
                btn.classList.remove('is-active', 'is-completed');
                btn.setAttribute('aria-selected', 'false');

                if (step === pillStep) {
                    btn.classList.add('is-active');
                    btn.setAttribute('aria-selected', 'true');
                } else if (step > pillStep || (pillStep <= 2 && hasGeneratedBracket)) {
                    btn.classList.add('is-completed');
                }
            };

            updatePill(step1Btn, 1);
            updatePill(step2Btn, 2);
            updatePill(step3Btn, 3);
        }

        function setWizardStep(step, shouldScroll = true) {
            if (!USE_WIZARD) return;
            const targetStep = Math.max(1, Math.min(3, parseInt(step, 10) || 1));
            currentWizardStep = targetStep;

            const pane1 = document.getElementById('wizardPane1');
            const pane2 = document.getElementById('wizardPane2');
            const pane3 = document.getElementById('wizardPane3');

            if (pane1) pane1.classList.toggle('is-active', targetStep === 1);
            if (pane2) pane2.classList.toggle('is-active', targetStep === 2);
            if (pane3) pane3.classList.toggle('is-active', targetStep === 3);

            updateWizardNav(targetStep);

            if (targetStep === 2) {
                renderTeamInputs();
            }

            if (targetStep === 3) {
                requestAnimationFrame(() => {
                    drawAllBracketArrows();
                    applyZoom(currentZoom);
                    window.renderLucideIcons?.();
                });
            }

            if (shouldScroll) {
                const ws = document.getElementById('rokBracketWorkspace');
                if (ws) {
                    ws.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            window.renderLucideIcons?.();
        }

        if (!IS_READ_ONLY) {
            const formatSelect = document.getElementById('tourneyFormat');
            const teamCountInput = document.getElementById('teamCountInput');
            const teamCountSelect = document.getElementById('teamCountSelect');
            const generateBtn = document.getElementById('generateBracketBtn');
            const resetBtn = document.getElementById('resetScoresBtn');

            const singleBoSection = document.getElementById('singleElimBoSection');
            formatSelect?.addEventListener('change', () => {
                if (singleBoSection) {
                    singleBoSection.style.display = (formatSelect.value === 'single') ? 'block' : 'none';
                }
                syncTeamCountOptions();
                renderTeamInputs();
                // TAHAN DULU: Jangan langsung generate bagan sampai tombol 'Generate & Simpan Bagan' diklik
            });

            teamCountInput?.addEventListener('input', () => {
                renderTeamInputs();
                // TAHAN DULU: Jangan langsung generate bagan sampai tombol 'Generate & Simpan Bagan' diklik
            });

            teamCountSelect?.addEventListener('change', () => {
                renderTeamInputs();
                // TAHAN DULU: Jangan langsung generate bagan sampai tombol 'Generate & Simpan Bagan' diklik
            });

            generateBtn?.addEventListener('click', () => {
                const titleInput = document.getElementById('tourneyName');
                if (!titleInput?.value.trim()) {
                    alert('Mohon isi nama turnamen terlebih dahulu.');
                    if (USE_WIZARD) setWizardStep(1);
                    titleInput?.focus();
                    return;
                }

                buildTournament(true);
                if (USE_WIZARD) {
                    hasGeneratedBracket = true;
                    setWizardStep(3);
                } else {
                    const deck = document.getElementById('generatedTreeDeck');
                    if (deck) {
                        deck.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }

                const originalHtml = generateBtn.innerHTML;
                generateBtn.innerHTML = '<i data-lucide="check-circle" style="width:16px;height:16px;vertical-align:-2px;"></i> Bagan Berhasil Dibuat!';
                generateBtn.style.background = '#15803d';
                generateBtn.style.borderColor = '#4ade80';
                generateBtn.style.color = '#ffffff';
                window.renderLucideIcons?.();
                setTimeout(() => {
                    generateBtn.innerHTML = originalHtml;
                    generateBtn.style.background = '';
                    generateBtn.style.borderColor = '';
                    generateBtn.style.color = '';
                    window.renderLucideIcons?.();
                }, 2200);
            });

            document.getElementById('btnWizardNext1')?.addEventListener('click', () => {
                const title = (document.getElementById('tourneyName')?.value || '').trim();
                if (!title) {
                    alert('Silakan masukkan nama turnamen terlebih dahulu sebelum lanjut ke daftar tim.');
                    document.getElementById('tourneyName')?.focus();
                    return;
                }
                syncTeamCountOptions();
                setWizardStep(2);
            });

            document.getElementById('btnWizardBack1')?.addEventListener('click', () => {
                setWizardStep(1);
            });

            document.getElementById('btnWizardBackToEdit')?.addEventListener('click', () => {
                setWizardStep(2);
            });

            document.getElementById('wizardNavStep1')?.addEventListener('click', () => {
                setWizardStep(1);
            });

            document.getElementById('wizardNavStep2')?.addEventListener('click', () => {
                const title = (document.getElementById('tourneyName')?.value || '').trim();
                if (!title) {
                    alert('Silakan masukkan nama turnamen terlebih dahulu.');
                    document.getElementById('tourneyName')?.focus();
                    return;
                }
                setWizardStep(2);
            });

            document.getElementById('wizardNavStep3')?.addEventListener('click', () => {
                if (!hasGeneratedBracket) {
                    alert('Silakan lengkapi langkah 1 dan 2, lalu klik "Generate & Buat Bagan".');
                    return;
                }
                setWizardStep(3);
            });

            document.getElementById('btnReshuffleBracket')?.addEventListener('click', reshuffleTeams);
            document.getElementById('btnReshuffleQuick')?.addEventListener('click', reshuffleTeams);

            resetBtn?.addEventListener('click', () => {
                if (confirm('Reset seluruh skor pertandingan turnamen ini?')) {
                    if (tournamentState && tournamentState.matches) {
                        tournamentState.matches.forEach(m => {
                            m.score1 = null;
                            m.score2 = null;
                            m.winner = null;
                            m.loser = null;
                        });
                        renderTournament(true);
                    }
                }
            });
        }

        // Initialize state: restore exact generated format & teams from server/session
        if (INITIAL_SERVER_STATE && INITIAL_SERVER_STATE.teams && INITIAL_SERVER_STATE.teams.length > 0) {
            const savedFormat = INITIAL_SERVER_STATE.format || 'single';
            const savedTitle = INITIAL_SERVER_STATE.title || INITIAL_SERVER_STATE.name || '';
            const savedCount = INITIAL_SERVER_STATE.teamCount || INITIAL_SERVER_STATE.teams.length;

            const formatSelect = document.getElementById('tourneyFormat');
            const titleInput = document.getElementById('tourneyName');
            const teamCountInput = document.getElementById('teamCountInput');
            const teamCountSelect = document.getElementById('teamCountSelect');

            if (formatSelect) formatSelect.value = savedFormat;
            if (titleInput && savedTitle) titleInput.value = savedTitle;
            if (savedFormat === 'double' && teamCountSelect) {
                teamCountSelect.value = String(savedCount);
            } else if (teamCountInput) {
                teamCountInput.value = String(savedCount);
            }
            syncTeamCountOptions();

            if (!IS_READ_ONLY) {
                renderTeamInputs(INITIAL_SERVER_STATE.teams);
            }

            if (INITIAL_SERVER_STATE.boSettings) {
                const bo = INITIAL_SERVER_STATE.boSettings;
                const customCb = document.getElementById('enableCustomBoCheckbox');
                const customPanel = document.getElementById('customBoOptionsPanel');
                const semiSel = document.getElementById('semiFinalBoSelect');
                const finalSel = document.getElementById('grandFinalBoSelect');
                const bronzeCb = document.getElementById('enableBronzeMatchCheckbox');
                const bronzeSel = document.getElementById('bronzeMatchBoSelect');
                const bronzeWrap = document.getElementById('bronzeBoSelectWrapper');

                if (customCb) customCb.checked = Boolean(bo.customBo);
                if (customPanel) customPanel.style.display = bo.customBo ? 'block' : 'none';
                if (semiSel && bo.semi) semiSel.value = bo.semi;
                if (finalSel && bo.final) finalSel.value = bo.final;
                if (bronzeCb) bronzeCb.checked = Boolean(bo.bronzeMatch);
                if (bronzeSel && bo.bronzeBo) bronzeSel.value = bo.bronzeBo;
                if (bronzeWrap) bronzeWrap.style.display = bo.bronzeMatch ? 'flex' : 'none';
            }

            if (INITIAL_SERVER_STATE.matches && INITIAL_SERVER_STATE.matches.length > 0) {
                tournamentState = INITIAL_SERVER_STATE;
                renderTournament(false);
                hasGeneratedBracket = true;
                if (USE_WIZARD) {
                    setWizardStep(3, false);
                }
            } else {
                const teams = INITIAL_SERVER_STATE.teams;
                const boSettings = INITIAL_SERVER_STATE.boSettings || getBoSettings();
                let engine;
                if (savedFormat === 'single') {
                    engine = makeElimination(teams, boSettings);
                } else if (savedFormat === 'double') {
                    engine = makeDoubleElimination(teams);
                } else if (savedFormat === 'league') {
                    engine = makeRoundRobin(teams);
                } else {
                    const round1 = buildSwissRoundM7(teams, [], 1);
                    engine = {
                        matches: round1.matches,
                        rounds: [round1],
                        championSource: null
                    };
                }

                tournamentState = {
                    title: savedTitle || (document.getElementById('tourneyName')?.value || 'ROK Tournament'),
                    format: savedFormat,
                    teamCount: teams.length,
                    teams: teams,
                    scores: INITIAL_SERVER_STATE.scores || {},
                    boSettings: boSettings,
                    ...engine
                };

                // Re-apply scores if any
                if (INITIAL_SERVER_STATE.scores && tournamentState.matches) {
                    tournamentState.matches.forEach(m => {
                        const sc = INITIAL_SERVER_STATE.scores[m.id];
                        if (sc) {
                            m.score1 = sc.s1;
                            m.score2 = sc.s2;
                            if (sc.s1 !== null && sc.s2 !== null && sc.s1 !== '' && sc.s2 !== '') {
                                const n1 = parseInt(sc.s1, 10);
                                const n2 = parseInt(sc.s2, 10);
                                if (n1 > n2) { m.winner = m.team1; m.loser = m.team2; }
                                else if (n2 > n1) { m.winner = m.team2; m.loser = m.team1; }
                            }
                        }
                    });
                }

                renderTournament(false);
            }
        } else {
            syncTeamCountOptions();
            renderTeamInputs();
            buildTournament(false);
            hasGeneratedBracket = false;
            if (USE_WIZARD) {
                setWizardStep(1, false);
            }
        }

        if (IS_READ_ONLY) {
            startAutoPolling();
        }

        const btnZoomIn = document.getElementById('btnZoomIn');
        const btnZoomOut = document.getElementById('btnZoomOut');
        const btnZoomReset = document.getElementById('btnZoomReset');
        const deckEl = document.getElementById('generatedTreeDeck');

        if (btnZoomIn) btnZoomIn.addEventListener('click', () => applyZoom(currentZoom + ZOOM_STEP));
        if (btnZoomOut) btnZoomOut.addEventListener('click', () => applyZoom(currentZoom - ZOOM_STEP));
        if (btnZoomReset) btnZoomReset.addEventListener('click', () => applyZoom(1.0));

        if (deckEl) {
            deckEl.addEventListener('wheel', (e) => {
                if (e.ctrlKey) {
                    e.preventDefault();
                    applyZoom(currentZoom + (e.deltaY < 0 ? ZOOM_STEP : -ZOOM_STEP));
                }
            }, { passive: false });
        }

        window.addEventListener('resize', () => {
            requestAnimationFrame(() => {
                drawAllBracketArrows();
                applyZoom(currentZoom);
            });
        });
    });
})();
</script>
