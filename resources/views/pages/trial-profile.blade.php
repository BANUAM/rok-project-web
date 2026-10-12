@extends('layouts.app')

@section('title', 'Lengkapi Profil Calon Pemain | ROK Revamp')
@section('description', 'Pengisian formulir identitas lengkap dan upload berkas bukti rank Mobile Legends calon pemain trial ROK.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge">TAHAP 02 &bull; PROFIL &amp; BERKAS</span>
            <h1 class="page-title">FORMULIR IDENTITAS LENGKAP</h1>
            <p class="page-subtitle">
                Halo, <strong>{{ $candidate->name }}</strong>. Silakan lengkapi data diri dan unggah tangkapan layar bukti permainan Mobile Legends kamu.
            </p>
        </div>
    </section>

    <section class="trial-form-section">
        <div class="section-inner">
            {{-- Token Info Bar --}}
            <div class="token-banner">
                <div class="token-info">
                    <span class="token-badge">TOKEN TRIAL RESMI:</span>
                    <code>{{ $candidate->access_token }}</code>
                </div>
                <p class="token-hint">
                    Simpan URL halaman ini jika kamu ingin melanjutkan pengisian berkas di lain waktu.
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('trial.save-profile', ['token' => $candidate->access_token]) }}"
                enctype="multipart/form-data"
                class="rok-card-form"
            >
                @csrf

                <div class="form-section-title">
                    <h4>1. IDENTITAS DIRI &amp; DATA PRIBADI</h4>
                </div>

                <div class="form-row-2">
                    <div class="form-field">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input
                            type="text"
                            id="name"
                            class="form-input is-readonly"
                            value="{{ $candidate->name }}"
                            readonly
                        >
                    </div>

                    <div class="form-field">
                        <label for="nickname" class="form-label">Nickname In-Game (IGN) <span class="text-gold">*</span></label>
                        <input
                            type="text"
                            name="nickname"
                            id="nickname"
                            class="form-input @error('nickname') is-invalid @enderror"
                            value="{{ old('nickname', $candidate->nickname) }}"
                            placeholder="Contoh: ViperX"
                            required
                        >
                        @error('nickname') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-field">
                        <label for="age" class="form-label">Umur (Tahun) <span class="text-gold">*</span></label>
                        <input
                            type="number"
                            name="age"
                            id="age"
                            min="14"
                            max="35"
                            class="form-input @error('age') is-invalid @enderror"
                            value="{{ old('age', $candidate->age) }}"
                            placeholder="18"
                            required
                        >
                        @error('age') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="birthplace" class="form-label">Tempat Lahir <span class="text-gold">*</span></label>
                        <input
                            type="text"
                            name="birthplace"
                            id="birthplace"
                            class="form-input @error('birthplace') is-invalid @enderror"
                            value="{{ old('birthplace', $candidate->birthplace) }}"
                            placeholder="Jakarta"
                            required
                        >
                        @error('birthplace') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="birthdate" class="form-label">Tanggal Lahir <span class="text-gold">*</span></label>
                        <input
                            type="date"
                            name="birthdate"
                            id="birthdate"
                            class="form-input @error('birthdate') is-invalid @enderror"
                            value="{{ old('birthdate', $candidate->birthdate?->format('Y-m-d')) }}"
                            required
                        >
                        @error('birthdate') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-field">
                    <label for="address" class="form-label">Alamat / Domisili Lengkap Saat Ini <span class="text-gold">*</span></label>
                    <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-input @error('address') is-invalid @enderror"
                        value="{{ old('address', $candidate->address) }}"
                        placeholder="Contoh: Jl. Tebet Timur Dalam No. 12, Jakarta Selatan"
                        required
                    >
                    @error('address') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-section-title">
                    <h4>2. KEAHLIAN GAME MOBILE LEGENDS</h4>
                </div>

                <div class="form-row-3">
                    <div class="form-field">
                        <label for="role" class="form-label">Role Utama <span class="text-gold">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Pilih Role</option>
                            @foreach (['Gold Laner', 'Mid Laner', 'Jungler', 'EXP Laner', 'Roamer'] as $roleOpt)
                                <option value="{{ $roleOpt }}" {{ old('role', $candidate->role) === $roleOpt ? 'selected' : '' }}>
                                    {{ $roleOpt }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="ml_tier_id" class="form-label">Tier Mobile Legends <span class="text-gold">*</span></label>
                        <select name="ml_tier_id" id="ml_tier_id" class="form-select @error('ml_tier_id') is-invalid @enderror" required>
                            <option value="">Pilih Tier</option>
                            @foreach ($tiers as $tier)
                                <option value="{{ $tier->id }}" {{ old('ml_tier_id', $candidate->ml_tier_id) == $tier->id ? 'selected' : '' }}>
                                    {{ $tier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ml_tier_id') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="stars" class="form-label">Jumlah Stars <span class="text-gold">*</span></label>
                        <input
                            type="number"
                            name="stars"
                            id="stars"
                            min="0"
                            max="999"
                            class="form-input @error('stars') is-invalid @enderror"
                            value="{{ old('stars', $candidate->stars ?? 0) }}"
                            placeholder="Contoh: 125"
                            required
                        >
                        @error('stars') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-section-title">
                    <h4>3. UPLOAD BERKAS BUKTI (FORMAT: JPG, JPEG, PNG — MAKS 5 MB)</h4>
                </div>

                <div class="form-row-3">
                    <div class="upload-field-card">
                        <label for="profile_screenshot" class="upload-label">
                            <span class="upload-icon">&#x1F4C1;</span>
                            <strong>1. Screenshot Profil MLBB</strong>
                            <p>Menampilkan ID, nickname, dan level akun</p>
                        </label>
                        <input
                            type="file"
                            name="profile_screenshot"
                            id="profile_screenshot"
                            accept="image/png, image/jpeg, image/jpg"
                            class="upload-input"
                        >
                        @if ($candidate->profile_screenshot_path)
                            <span class="upload-status upload-status--saved">&#10003; Berkas sudah diunggah</span>
                        @endif
                        @error('profile_screenshot') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="upload-field-card">
                        <label for="hero_screenshot" class="upload-label">
                            <span class="upload-icon">&#x2694;</span>
                            <strong>2. Screenshot Hero Favorit</strong>
                            <p>Menampilkan total pertandingan &amp; win rate hero andalan</p>
                        </label>
                        <input
                            type="file"
                            name="hero_screenshot"
                            id="hero_screenshot"
                            accept="image/png, image/jpeg, image/jpg"
                            class="upload-input"
                        >
                        @if ($candidate->favorite_hero_screenshot_path)
                            <span class="upload-status upload-status--saved">&#10003; Berkas sudah diunggah</span>
                        @endif
                        @error('hero_screenshot') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="upload-field-card">
                        <label for="tier_screenshot" class="upload-label">
                            <span class="upload-icon">&#x1F3C6;</span>
                            <strong>3. Screenshot Tier &amp; Stars</strong>
                            <p>Menampilkan bukti tier rank terkini musim ini</p>
                        </label>
                        <input
                            type="file"
                            name="tier_screenshot"
                            id="tier_screenshot"
                            accept="image/png, image/jpeg, image/jpg"
                            class="upload-input"
                        >
                        @if ($candidate->tier_screenshot_path)
                            <span class="upload-status upload-status--saved">&#10003; Berkas sudah diunggah</span>
                        @endif
                        @error('tier_screenshot') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-actions-bar">
                    <button type="submit" class="btn-rok btn-rok--gold btn-rok--lg">
                        Simpan Profil &amp; Lanjut ke Tes LSM &rarr;
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
