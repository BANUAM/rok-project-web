@extends('layouts.app')

@section('title', 'Verifikasi Alamat Email | Ruler Of Kings')
@section('description', 'Verifikasi alamat email Anda untuk melanjutkan transaksi pembelian membership Ruler Of Kings.')

@section('content')
<div class="page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
    <div style="width: 100%; max-width: 500px; background: #111622; border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.6); text-align: center;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 50%; background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.4); color: #38bdf8; font-size: 28px; margin-bottom: 20px;">
            <i data-lucide="mail" style="width:30px;height:30px;"></i>
        </div>

        <span class="badge-mini" style="font-size: 11px; letter-spacing: 2px;">EMAIL VERIFICATION REQUIRED</span>
        <h1 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 10px 0 12px 0;">VERIFIKASI EMAIL ANDA</h1>

        <p style="color: #cbd5e1; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
            Terima kasih telah mendaftar di <strong>Ruler Of Kings</strong>! Sebelum melanjutkan ke proses pembayaran membership, kami telah mengirimkan tautan verifikasi ke:
        </p>

        <div style="background: #0b0e15; border: 1px solid var(--rok-surface-border); border-radius: 6px; padding: 12px; margin-bottom: 24px;">
            <strong style="color: #38bdf8; font-size: 15px;">{{ Auth::user()->email }}</strong>
        </div>

        <p style="color: #94a3b8; font-size: 13px; line-height: 1.5; margin-bottom: 28px;">
            Silakan periksa kotak masuk (inbox) atau folder spam email Anda, lalu klik tautan verifikasi yang tersedia.
        </p>

        @if (session('success'))
            <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; padding: 12px 14px; color: #4ade80; font-size: 13px; margin-bottom: 24px; text-align: left;">
                &check; {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 6px; padding: 12px 14px; color: #f87171; font-size: 13px; margin-bottom: 24px; text-align: left;">
                &excl; {{ session('error') }}
            </div>
        @endif

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="hero-button hero-button--primary" style="width: 100%; padding: 12px; font-size: 13px; font-weight: 800; cursor: pointer; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> KIRIM ULANG EMAIL VERIFIKASI
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-rok btn-rok--ghost" style="width: 100%; padding: 10px; font-size: 13px; color: #94a3b8;">
                    Keluar / Gunakan Akun Lain
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
