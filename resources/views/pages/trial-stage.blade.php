@extends('layouts.app')

@section('title', 'Tahap Lanjutan Seleksi | ROK Revamp')
@section('description', 'Akses khusus tahap lanjutan seleksi atlet esports Ruler Of Kings bagi kandidat yang lolos seleksi awal.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge" style="color: #4ade80; border-color: rgba(74, 222, 128, 0.4); background: rgba(74, 222, 128, 0.1);">
                &check; AKSESTAHAP LANJUTAN RESMI
            </span>
            <h1 class="page-title">SELAMAT DATANG DI SESI LANJUTAN</h1>
            <p class="page-subtitle">
                Anda telah berhasil melewati seleksi awal dan tes LSM. Silakan simak informasi teknis dan jadwal tahapan uji tanding (scrim) serta wawancara di bawah.
            </p>
        </div>
    </section>

    <section class="teams-section" style="padding-top: 20px;">
        <div class="site-container" style="max-width: 900px; margin: 0 auto;">
            {{-- Candidate Status Box --}}
            <div style="background: #111622; border: 1px solid #1e2638; border-radius: var(--radius-md); padding: 32px; margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px;">DATA KANDIDAT RESMI</span>
                        <h2 style="font-size: 26px; font-weight: 800; color: #ffffff; margin: 4px 0;">{{ $candidate->name }}</h2>
                        <p style="color: #94a3b8; font-size: 14px; margin: 0;">IGN: <strong style="color: #ffffff;">{{ $candidate->nickname ?? $candidate->name }}</strong> &bull; Role: <strong style="color: #e2e8f0;">{{ $candidate->role }}</strong></p>
                    </div>
                    <span style="display: inline-block; padding: 8px 18px; background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; color: #4ade80; font-weight: 800; font-size: 13px; text-transform: uppercase;">
                        Status: LOLOS SELEKSI
                    </span>
                </div>

                {{-- Agenda & Next Steps --}}
                <h3 style="font-size: 18px; font-weight: 800; color: #e2e8f0; margin-bottom: 16px; letter-spacing: 1px;">AGENDA TAHAP BERIKUTNYA</h3>
                
                <div style="display: grid; gap: 16px; margin-bottom: 24px;">
                    <div style="background: #161c2b; border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 20px; display: flex; gap: 16px; align-items: flex-start;">
                        <span style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-weight: 800; font-size: 18px; flex-shrink: 0;">1</span>
                        <div>
                            <strong style="color: #ffffff; font-size: 16px; display: block; margin-bottom: 4px;">Sesi Wawancara Online &amp; Visi Atlet</strong>
                            <p style="color: #94a3b8; font-size: 14px; line-height: 1.6; margin: 0;">
                                Manager tim akan menghubungi nomor kontak atau email Anda untuk penjadwalan interview tatap maya (Google Meet/Discord). Persiapkan komitmen waktu dan motivasi bertanding.
                            </p>
                        </div>
                    </div>

                    <div style="background: #161c2b; border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 20px; display: flex; gap: 16px; align-items: flex-start;">
                        <span style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-weight: 800; font-size: 18px; flex-shrink: 0;">2</span>
                        <div>
                            <strong style="color: #ffffff; font-size: 16px; display: block; margin-bottom: 4px;">Uji Tanding (Scrim) Bersama Tim Inti &amp; Coach</strong>
                            <p style="color: #94a3b8; font-size: 14px; line-height: 1.6; margin: 0;">
                                Anda akan diundang dalam 3-5 game scrim kompetitif untuk menguji chemistry, komunikasi in-game (shoutcalling), serta fleksibilitas hero pool Anda.
                            </p>
                        </div>
                    </div>

                    <div style="background: #161c2b; border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 20px; display: flex; gap: 16px; align-items: flex-start;">
                        <span style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 8px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-weight: 800; font-size: 18px; flex-shrink: 0;">3</span>
                        <div>
                            <strong style="color: #ffffff; font-size: 16px; display: block; margin-bottom: 4px;">Penandatanganan Kontrak Atlet &amp; Official Roster</strong>
                            <p style="color: #94a3b8; font-size: 14px; line-height: 1.6; margin: 0;">
                                Pemain yang sukses menyelesaikan scrim akan diberikan draft kontrak resmi dan diresmikan masuk ke dalam daftar roster aktif Ruler Of Kings.
                            </p>
                        </div>
                    </div>
                </div>

                @if($candidate->reviewer_notes)
                    <div style="padding: 16px 20px; background: #0c1017; border-radius: 8px; border-left: 4px solid var(--rok-gold); margin-bottom: 24px;">
                        <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Catatan Khusus Tim Pelatih:</div>
                        <div style="font-size: 14px; color: #e2e8f0; font-style: italic;">"{{ $candidate->reviewer_notes }}"</div>
                    </div>
                @endif

                <div style="text-align: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.06);">
                    <p style="color: #94a3b8; font-size: 14px; margin-bottom: 16px;">
                        Pertanyaan atau butuh konfirmasi segera? Hubungi Official Discord / WhatsApp Manajemen:
                    </p>
                    <a href="https://discord.gg/rulerofkings" target="_blank" class="hero-button hero-button--primary" style="display: inline-block; padding: 12px 28px; text-decoration: none;">
                        Gabung Discord Official ROK &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
