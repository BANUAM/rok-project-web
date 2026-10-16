<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Hasil Seleksi Trial - Ruler Of Kings</title>
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
                            <h1 style="margin: 0 0 16px 0; font-size: 24px; font-weight: 800; color: #ffffff; line-height: 1.3;">
                                Halo, {{ $candidate->name }}
                            </h1>

                            <p style="margin: 0 0 18px 0; font-size: 15px; line-height: 1.6; color: #cbd5e1;">
                                Terima kasih atas minat, dedikasi, serta waktu yang Anda luangkan dalam mengikuti tahapan seleksi trial pemain bersama <strong style="color: #ffffff;">Ruler Of Kings (ROK)</strong>.
                            </p>

                            <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.6; color: #cbd5e1;">
                                Setelah melalui proses peninjauan menyeluruh terhadap data profil, statistik performa, dan hasil pengujian taktis pada batch ini, dengan berat hati kami menginformasikan bahwa <strong style="color: #ffffff;">Anda belum dapat melangkah ke tahapan berikutnya pada periode kali ini</strong>.
                            </p>

                            <div style="background-color: #151a26; border-left: 4px solid #64748b; padding: 18px 20px; border-radius: 0 8px 8px 0; margin-bottom: 24px;">
                                <div style="font-size: 14px; font-weight: 700; color: #cbd5e1; margin-bottom: 4px;">
                                    Tetap Semangat &amp; Terus Berkembang!
                                </div>
                                <div style="font-size: 13px; color: #94a3b8; line-height: 1.5;">
                                    Proses seleksi kami sangat kompetitif dengan kuota roster yang sangat terbatas. Keputusan ini bukan cerminan batas potensi Anda sebagai pemain esports. Kami sangat mengapresiasi kerja keras Anda dan mendorong Anda untuk terus mengasah kemampuan mekanik maupun makro.
                                </div>
                            </div>

                            @if($candidate->reviewer_notes)
                                <div style="margin-top: 20px; padding: 16px; background: #0c1017; border-radius: 8px; border: 1px dashed #1e2638;">
                                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Catatan Tim Pelatih:</div>
                                    <div style="font-size: 13px; color: #cbd5e1; font-style: italic;">"{{ $candidate->reviewer_notes }}"</div>
                                </div>
                            @endif

                            <p style="margin: 24px 0 0 0; font-size: 14px; line-height: 1.6; color: #94a3b8;">
                                Pintu Ruler Of Kings akan selalu terbuka untuk talenta yang berkomitmen tinggi pada pembukaan trial musim berikutnya. Kami berharap yang terbaik untuk perjalanan kompetitif Anda.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 36px; background-color: #0b0e15; border-top: 1px solid #1a2030; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #475569; line-height: 1.5;">
                                &copy; {{ date('Y') }} Ruler Of Kings Esports. Hak cipta dilindungi undang-undang.<br>
                                Email ini dikirim secara resmi oleh Sistem Seleksi ROK.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
