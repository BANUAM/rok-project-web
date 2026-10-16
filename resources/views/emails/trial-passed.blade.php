<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat! Anda Lolos Seleksi Trial ROK</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0c0f16; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #e2e8f0;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #0c0f16; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #111622; border: 1px solid #1e2638; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.6);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 36px 36px 24px 36px; text-align: center; background: linear-gradient(180deg, #182030 0%, #111622 100%); border-bottom: 1px solid #1e2638;">
                            <div style="font-size: 24px; font-weight: 800; letter-spacing: 3px; color: #f8fafc; text-transform: uppercase;">
                                RULER OF KINGS
                            </div>
                            <div style="font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px;">
                                ESPORTS ORGANIZATION &bull; OFFICIAL TRIAL
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 36px;">
                            <div style="display: inline-block; padding: 6px 14px; background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; color: #4ade80; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">
                                &check; HASIL SELEKSI: LOLOS
                            </div>

                            <h1 style="margin: 0 0 16px 0; font-size: 24px; font-weight: 800; color: #ffffff; line-height: 1.3;">
                                Halo, {{ $candidate->name }}!
                            </h1>

                            <p style="margin: 0 0 18px 0; font-size: 15px; line-height: 1.6; color: #cbd5e1;">
                                Selamat! Berdasarkan evaluasi berkas persyaratan, statistik gameplay in-game, serta hasil pengerjaan <strong style="color: #ffffff;">Tes LSM (Learning & Strategy Mastery)</strong>, Tim Kepelatihan dan Manajemen <strong style="color: #ffffff;">Ruler Of Kings (ROK)</strong> dengan bangga menyatakan bahwa Anda telah:
                            </p>

                            <div style="background-color: #171f30; border-left: 4px solid #38bdf8; padding: 18px 20px; border-radius: 0 8px 8px 0; margin-bottom: 24px;">
                                <div style="font-size: 18px; font-weight: 700; color: #38bdf8; margin-bottom: 6px;">
                                    DINYATAKAN LOLOS SELEKSI AWAL
                                </div>
                                <div style="font-size: 13px; color: #94a3b8; line-height: 1.5;">
                                    Role Terdaftar: <strong style="color: #e2e8f0;">{{ $candidate->role }}</strong><br>
                                    IGN / Nickname: <strong style="color: #e2e8f0;">{{ $candidate->nickname ?? $candidate->name }}</strong>
                                </div>
                            </div>

                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #94a3b8;">
                                Langkah selanjutnya adalah sesi koordinasi teknis (scrim online bersama tim inti, wawancara visi kompetitif, serta pengenalan regulasi tim). Silakan akses portal khusus tahap lanjutan Anda melalui tombol di bawah:
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $nextStageUrl }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 50%, #94a3b8 100%); color: #0b0f17; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 6px; box-shadow: 0 4px 20px rgba(226, 232, 240, 0.3);">
                                    AKSES TAHAPAN BERIKUTNYA &rarr;
                                </a>
                            </div>

                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #64748b; line-height: 1.5; text-align: center;">
                                Tautan di atas bersifat rahasia dan dikhususkan untuk akun Anda. Jangan bagikan tautan ini kepada pihak lain.
                            </p>

                            @if($candidate->reviewer_notes)
                                <div style="margin-top: 24px; padding: 16px; background: #0c1017; border-radius: 8px; border: 1px dashed #1e2638;">
                                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Catatan Pelatih:</div>
                                    <div style="font-size: 13px; color: #cbd5e1; font-style: italic;">"{{ $candidate->reviewer_notes }}"</div>
                                </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 36px; background-color: #0b0e15; border-top: 1px solid #1a2030; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
                                &copy; {{ date('Y') }} Ruler Of Kings Esports. Hak cipta dilindungi undang-undang.<br>
                                Email ini dikirim secara otomatis oleh Sistem Seleksi Resmi ROK.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
