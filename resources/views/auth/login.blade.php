@extends('layouts.app')

@section('title', 'Masuk Akun | Ruler Of Kings')
@section('description', 'Masuk ke akun Ruler Of Kings untuk melanjutkan pembelian membership dan akses fitur komunitas.')

@section('content')
<div class="page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
    <div class="auth-card" style="width: 100%; max-width: 480px; background: var(--rok-card-bg, #111622); border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 36px; box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="badge-mini" style="font-size: 11px; letter-spacing: 2px;">AUTHENTICATION</span>
            <h1 style="font-size: 26px; font-weight: 800; margin: 8px 0 6px 0;">MASUK KE AKUN ROK</h1>
            <p style="color: var(--rok-text-muted, #94a3b8); font-size: 14px; margin: 0;">Silakan masuk untuk mengakses <strong>ROK Bracket Studio</strong>, kurikulum akademi, atau mengelola membership Anda.</p>
        </div>

        @if (session('status'))
            <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; padding: 12px 14px; color: #4ade80; font-size: 13px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 6px; padding: 12px 14px; color: #f87171; font-size: 13px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="rokLoginForm">
            @csrf

            <div class="form-field" style="margin-bottom: 18px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Email atau Username</label>
                <input type="text" name="login" id="loginInput" value="{{ old('login') }}" class="form-input" style="width: 100%; padding: 12px 16px; border: 1px solid var(--rok-surface-border); border-radius: 6px; font-size: 14px;" required autofocus placeholder="nama@email.com atau username">
            </div>

            <div class="form-field" style="margin-bottom: 18px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Kata Sandi (Password)</label>
                <input type="password" name="password" id="passwordInput" class="form-input" style="width: 100%; padding: 12px 16px; border: 1px solid var(--rok-surface-border); border-radius: 6px; font-size: 14px;" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--rok-text-muted, #94a3b8); cursor: pointer;">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="hero-button hero-button--primary" style="width: 100%; padding: 14px; font-size: 14px; font-weight: 800; cursor: pointer; text-align: center; border: none; border-radius: 6px;">
                MASUK KE AKUN &rarr;
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--rok-surface-border);">
            <p style="color: var(--rok-text-muted, #94a3b8); font-size: 13px; margin: 0;">
                Belum memiliki akun?
                <a href="{{ route('register') }}" style="color: var(--rok-gold); font-weight: 700; text-decoration: none;">
                    Daftar Akun Baru &rarr;
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
