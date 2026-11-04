@extends('layouts.app')

@section('title', 'Tagihan Pembayaran #' . $order->order_number . ' | Ruler Of Kings')
@section('description', 'Detail pesanan dan instruksi pembayaran paket membership resmi Ruler Of Kings.')

@section('content')
<div class="page-container" style="padding-top: 40px; padding-bottom: 80px;">
    <div class="site-container" style="max-width: 800px; margin: 0 auto;">
        
        <div style="background: var(--rok-surface-card); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            
            {{-- Header --}}
            <div style="padding: 32px; background: var(--rok-surface-card); border-bottom: 1px solid var(--rok-surface-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div>
                    <span class="badge-mini">INVOICE PESANAN</span>
                    <h1 style="font-size: 24px; font-weight: 800; color: var(--rok-text-bright); margin: 4px 0 2px 0;">TAGIHAN: {{ $order->order_number }}</h1>
                    <span style="font-size: 13px; color: var(--rok-text-muted);">Diterbitkan pada {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                <div>
                    @if ($order->isPaid())
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; color: #16a34a; font-size: 13px; font-weight: 800; text-transform: uppercase;">
                            <i data-lucide="check-circle-2" style="width:14px;height:14px;"></i> PEMBAYARAN LUNAS
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; background: rgba(234, 179, 8, 0.15); border: 1px solid #eab308; border-radius: 6px; color: #ca8a04; font-size: 13px; font-weight: 800; text-transform: uppercase;">
                            <i data-lucide="hourglass" style="width:14px;height:14px;"></i> MENUNGGU PEMBAYARAN
                        </span>
                    @endif
                </div>
            </div>

            {{-- Body --}}
            <div style="padding: 32px;">
                @if (session('success'))
                    <div style="background: rgba(56, 189, 248, 0.15); border: 1px solid #38bdf8; border-radius: 8px; padding: 14px 18px; color: #0284c7; font-size: 14px; margin-bottom: 24px;">
                        &check; {{ session('success') }}
                    </div>
                @endif

                {{-- Order Details Table --}}
                <div style="margin-bottom: 28px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--rok-text-bright); margin-bottom: 12px; letter-spacing: 1px;">RINCIAN PAKET MEMBERSHIP</h3>
                    <div style="background: var(--rok-bg); border: 1px solid var(--rok-surface-border); border-radius: 8px; overflow: hidden;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                            <tbody>
                                <tr style="border-bottom: 1px solid var(--rok-surface-border);">
                                    <td style="padding: 14px 18px; color: var(--rok-text-muted); width: 35%;">Nama Paket:</td>
                                    <td style="padding: 14px 18px; color: var(--rok-text-bright); font-weight: 700;">{{ $order->plan?->name }} ({{ $order->plan?->badge_title }})</td>
                                </tr>
                                <tr style="border-bottom: 1px solid var(--rok-surface-border);">
                                    <td style="padding: 14px 18px; color: var(--rok-text-muted);">Metode Pembayaran:</td>
                                    <td style="padding: 14px 18px; color: var(--rok-text-bright); text-transform: uppercase; font-weight: 700;">{{ str_replace('_', ' ', $order->payment_method) }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid var(--rok-surface-border);">
                                    <td style="padding: 14px 18px; color: var(--rok-text-muted);">Nomor Referensi:</td>
                                    <td style="padding: 14px 18px; color: var(--rok-text-bright); font-family: monospace;">{{ $order->payment_reference }}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid var(--rok-surface-border);">
                                    <td style="padding: 14px 18px; color: var(--rok-text-muted);">Pemesan:</td>
                                    <td style="padding: 14px 18px; color: var(--rok-text-bright);">{{ $order->user?->name }} ({{ $order->user?->email }})</td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 18px; color: var(--rok-text-muted); font-weight: 700;">Total Tagihan:</td>
                                    <td style="padding: 14px 18px; color: var(--rok-gold); font-size: 20px; font-weight: 900; font-family: var(--font-sub);">{{ $order->formatted_amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Payment Instructions --}}
                @if (!$order->isPaid())
                    <div style="background: var(--rok-bg); border: 1px solid var(--rok-surface-border); border-radius: 8px; padding: 24px; margin-bottom: 28px;">
                        <h3 style="font-size: 16px; font-weight: 800; color: var(--rok-text-bright); margin-bottom: 14px;">PETUNJUK TRANSFER PEMBAYARAN</h3>
                        
                        @if ($order->payment_method === 'bank_bca')
                            <div style="margin-bottom: 14px;">
                                <span style="font-size: 12px; color: var(--rok-text-muted); display: block;">Bank Tujuan:</span>
                                <strong style="font-size: 15px; color: var(--rok-text-bright);">Bank Central Asia (BCA)</strong>
                            </div>
                            <div style="margin-bottom: 14px;">
                                <span style="font-size: 12px; color: var(--rok-text-muted); display: block;">Nomor Rekening:</span>
                                <strong style="font-size: 22px; color: #0284c7; font-family: monospace; letter-spacing: 2px;">8040 1928 3719</strong>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--rok-text-muted); display: block;">Atas Nama:</span>
                                <strong style="font-size: 15px; color: var(--rok-text-bright);">PT RULER OF KINGS ESPORTS</strong>
                            </div>
                        @elseif ($order->payment_method === 'bank_mandiri')
                            <div style="margin-bottom: 14px;">
                                <span style="font-size: 12px; color: var(--rok-text-muted); display: block;">Bank Tujuan:</span>
                                <strong style="font-size: 15px; color: var(--rok-text-bright);">Bank Mandiri</strong>
                            </div>
                            <div style="margin-bottom: 14px;">
                                <span style="font-size: 12px; color: var(--rok-text-muted); display: block;">Nomor Rekening:</span>
                                <strong style="font-size: 22px; color: #ca8a04; font-family: monospace; letter-spacing: 2px;">1370 0291 8820</strong>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--rok-text-muted); display: block;">Atas Nama:</span>
                                <strong style="font-size: 15px; color: var(--rok-text-bright);">PT RULER OF KINGS ESPORTS</strong>
                            </div>
                        @else
                            <div style="margin-bottom: 14px;">
                                <span style="font-size: 12px; color: var(--rok-text-muted); display: block;">Metode Pembayaran:</span>
                                <strong style="font-size: 15px; color: var(--rok-text-bright);">QRIS Interaktif (Gopay/OVO/Dana/BCA/ShopeePay)</strong>
                            </div>
                            <p style="font-size: 13px; color: var(--rok-text-secondary); line-height: 1.5;">
                                Pindai barcode QRIS resmi yang tertera pada loket kasir atau hubungi staf keuangan kami melalui WhatsApp dengan melampirkan nomor order <strong style="color: var(--rok-text-bright);">{{ $order->order_number }}</strong>.
                            </p>
                        @endif

                        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed var(--rok-surface-border); font-size: 13px; color: var(--rok-text-muted); line-height: 1.5;">
                            <em>* Sertakan kode referensi <strong>{{ $order->payment_reference }}</strong> atau nomor order pada berita transfer agar transaksi dapat diverifikasi secara instan.</em>
                        </div>
                    </div>
                @endif

                <div style="display: flex; gap: 14px; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <a href="{{ route('bracket') }}" class="btn-rok btn-rok--ghost" style="text-decoration: none;">
                        &larr; Kembali ke Turnamen &amp; Bracket
                    </a>
                    
                    <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin ROK, saya ingin konfirmasi pembayaran membership #' . $order->order_number . ' senilai ' . $order->formatted_amount) }}" target="_blank" class="hero-button hero-button--primary" style="padding: 12px 24px; text-decoration: none; font-size: 13px; font-weight: 800;">
                        Konfirmasi Pembayaran via WhatsApp &rarr;
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
