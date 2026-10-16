@extends('layouts.app')

@section('title', 'Daftar Akun Baru | Ruler Of Kings')
@section('description', 'Daftarkan akun resmi Ruler Of Kings untuk membeli paket membership, membuka akses kurikulum taktis, dan mengelola turnamen.')

@section('content')
<div class="page-container" style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 60px 20px;">
    <div style="width: 100%; max-width: 480px; background: #111622; border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 36px; box-shadow: 0 10px 40px rgba(0,0,0,0.6);">
        <div style="text-align: center; margin-bottom: 28px;">
            <span class="badge-mini" style="font-size: 11px; letter-spacing: 2px;">REGISTRATION</span>
            <h1 style="font-size: 26px; font-weight: 800; color: #ffffff; margin: 8px 0 6px 0;">DAFTAR AKUN ROK</h1>
            <p style="color: #94a3b8; font-size: 14px; margin: 0;">Buat akun untuk mengakses paket membership, materi taktis, dan turnamen resmi.</p>
        </div>

        @if ($errors->any())
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 6px; padding: 12px 14px; color: #f87171; font-size: 13px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-field" style="margin-bottom: 16px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px;">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" style="width: 100%; padding: 12px 16px; background: #0b0e15; border: 1px solid var(--rok-surface-border); border-radius: 6px; color: #ffffff; font-size: 14px;" required placeholder="Contoh: Ryan Pratama">
            </div>

            <div class="form-field" style="margin-bottom: 16px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px;">Alamat Email <span style="color: #ef4444;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-input" style="width: 100%; padding: 12px 16px; background: #0b0e15; border: 1px solid var(--rok-surface-border); border-radius: 6px; color: #ffffff; font-size: 14px;" required placeholder="nama@email.com">
                <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Email aktif untuk menerima tautan verifikasi.</span>
            </div>

            <div class="form-field" style="margin-bottom: 16px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px;">Username <span style="color: #ef4444;">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}" class="form-input" style="width: 100%; padding: 12px 16px; background: #0b0e15; border: 1px solid var(--rok-surface-border); border-radius: 6px; color: #ffffff; font-size: 14px;" required placeholder="ryan_rok">
                <span style="font-size: 11px; color: #64748b; margin-top: 4px; display: block;">Hanya huruf, angka, garis bawah (_), dan tanda strip (-).</span>
            </div>

            <div class="form-field" style="margin-bottom: 16px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px;">Kata Sandi (Password) <span style="color: #ef4444;">*</span></label>
                <input type="password" name="password" class="form-input" style="width: 100%; padding: 12px 16px; background: #0b0e15; border: 1px solid var(--rok-surface-border); border-radius: 6px; color: #ffffff; font-size: 14px;" required placeholder="Minimal 8 karakter">
            </div>

            <div class="form-field" style="margin-bottom: 24px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px;">Konfirmasi Kata Sandi <span style="color: #ef4444;">*</span></label>
                <input type="password" name="password_confirmation" class="form-input" style="width: 100%; padding: 12px 16px; background: #0b0e15; border: 1px solid var(--rok-surface-border); border-radius: 6px; color: #ffffff; font-size: 14px;" required placeholder="Ulangi kata sandi">
            </div>

            <button type="submit" class="hero-button hero-button--primary" style="width: 100%; padding: 14px; font-size: 14px; font-weight: 800; cursor: pointer; text-align: center; border: none; border-radius: 6px;">
                DAFTAR SEKARANG &rarr;
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06);">
            <p style="color: #94a3b8; font-size: 13px; margin: 0;">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" style="color: var(--rok-gold); font-weight: 700; text-decoration: none;">
                    Masuk ke Akun &rarr;
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
