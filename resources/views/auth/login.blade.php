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

        {{-- Pre-activated Demo Membership Accounts Quick Fill --}}
        <div class="demo-account-box" style="background: rgba(212, 168, 83, 0.08); border: 1px dashed rgba(212, 168, 83, 0.45); border-radius: 10px; padding: 14px 16px; margin-bottom: 22px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 11px; font-weight: 800; letter-spacing: 1px; color: var(--rok-gold);">&#x1F451; AKUN KHUSUS DEMO MEMBERSHIP</span>
                <span style="font-size: 11px; color: var(--rok-text-muted, #94a3b8);">Klik untuk isi otomatis</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <button type="button" onclick="fillDemoLogin('immortal@rok.com', 'immortal123')" class="btn-demo-fill" style="background: rgba(212, 168, 83, 0.16); border: 1px solid var(--rok-gold); border-radius: 6px; padding: 8px 10px; text-align: left; cursor: pointer; color: inherit;">
                    <strong style="display: block; font-size: 12px; color: var(--rok-gold);">&#x1F451; ROK IMMORTAL</strong>
                    <span style="font-size: 11px; color: var(--rok-text-muted, #94a3b8);">immortal@rok.com</span>
                </button>
                <button type="button" onclick="fillDemoLogin('challenger@rok.com', 'challenger123')" class="btn-demo-fill" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.4); border-radius: 6px; padding: 8px 10px; text-align: left; cursor: pointer; color: inherit;">
                    <strong style="display: block; font-size: 12px; color: #38bdf8;">&#x2694; ROK CHALLENGER</strong>
                    <span style="font-size: 11px; color: var(--rok-text-muted, #94a3b8);">challenger@rok.com</span>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" id="rokLoginForm">
            @csrf

            <div class="form-field" style="margin-bottom: 18px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px;">Email atau Username</label>
                <input type="text" name="login" id="loginInput" value="{{ old('login') }}" class="form-input" style="width: 100%; padding: 12px 16px; border: 1px solid var(--rok-surface-border); border-radius: 6px; font-size: 14px;" required autofocus placeholder="immortal@rok.com atau username">
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

<script>
function fillDemoLogin(login, password) {
    document.getElementById('loginInput').value = login;
    document.getElementById('passwordInput').value = password;
}
</script>
@endsection
