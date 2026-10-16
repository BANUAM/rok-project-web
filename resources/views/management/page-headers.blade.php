@extends('layouts.management')

@section('title', 'Manajemen Header Halaman | ROK Management')
@section('header_title', 'PENGELOLAAN GAMBAR HEADER & BANNER HALAMAN')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-card mb-4">
        <div class="mgmt-card__header">
            <h3>&#x1F5BC; BANNER HEADER HALAMAN PUBLIK</h3>
        </div>
        <p class="text-sm text-muted">
            Kelola judul, subjudul, dan gambar banner untuk bagian header pada halaman publik utama (Teams, Trial, Achievements, Learning &amp; Development, dan Bracket). Gambar akan otomatis beradaptasi dengan rasio lanskap dan gradasi estetik gelap ROK.
        </p>
    </div>

    <div class="mgmt-grid-2">
        @foreach ($pageDefinitions as $key => $page)
            @php
                $h = $headers->get($key);
            @endphp
            <div class="mgmt-card">
                <div class="mgmt-card__header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span class="badge-mini" style="text-transform: uppercase;">KEY: {{ $key }}</span>
                        <h3 style="margin-top: 4px;">{{ $page['name'] }}</h3>
                    </div>
                    <a href="{{ url($page['url']) }}" target="_blank" class="btn-rok btn-rok--sm btn-rok--outline" title="Lihat di Web">
                        Lihat Halaman &nearr;
                    </a>
                </div>

                {{-- Banner Preview --}}
                <div style="margin: 16px 0; border: 1px solid var(--rok-surface-border); border-radius: 8px; overflow: hidden; background: #0c0f17; position: relative;">
                    @if ($h && $h->image_url)
                        <div style="position: relative; width: 100%; height: 160px; overflow: hidden;">
                            <img src="{{ $h->image_url }}" alt="{{ $page['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(11,15,23,0.3) 0%, rgba(11,15,23,0.85) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 16px;">
                                <span style="font-size: 11px; font-weight: 800; color: var(--rok-gold); letter-spacing: 1.5px; text-transform: uppercase;">PREVIEW HEADER</span>
                                <strong style="font-size: 18px; color: #ffffff;">{{ $h->title }}</strong>
                                <span style="font-size: 12px; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $h->subtitle }}</span>
                            </div>
                        </div>
                    @else
                        <div style="padding: 30px 20px; text-align: center; color: #64748b;">
                            <span style="font-size: 28px; display: block; margin-bottom: 6px;">&#x1F5BC;</span>
                            <span style="font-size: 13px;">Belum ada gambar banner kustom (menggunakan tampilan default website)</span>
                        </div>
                    @endif
                </div>

                {{-- Form Update Header --}}
                <form method="POST" action="{{ route('management.page-headers.update', $key) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-field">
                        <label class="form-label">Judul Utama Header (Title)</label>
                        <input type="text" name="title" class="form-input" value="{{ old('title', $h->title ?? strtoupper($page['name'])) }}" required>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Subjudul / Deskripsi Singkat</label>
                        <textarea name="subtitle" class="form-textarea" rows="2" placeholder="Deskripsi singkat pendukung header...">{{ old('subtitle', $h->subtitle ?? $page['description']) }}</textarea>
                    </div>

                    <div class="form-field">
                        <label class="form-label">Unggah Gambar Banner Baru (JPG, PNG, WebP, SVG &bull; Maks 5MB)</label>
                        <input type="file" name="image" class="form-file" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                        <span class="form-hint">Disarankan gambar rasio lanskap 16:9 atau 21:9 dengan resolusi minimal 1920x600 piksel.</span>
                    </div>

                    <div style="display: flex; gap: 16px; align-items: center; margin: 16px 0; flex-wrap: wrap;">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" {{ ($h && !$h->is_active) ? '' : 'checked' }}>
                            <span>Aktifkan Banner di Halaman</span>
                        </label>

                        @if ($h && $h->image_path)
                            <label class="checkbox-label" style="color: #ef4444;">
                                <input type="checkbox" name="remove_image" value="1">
                                <span>Hapus Gambar Banner (Gunakan Polos)</span>
                            </label>
                        @endif
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 16px;">
                        <button type="submit" class="btn-rok btn-rok--gold" style="flex: 1;">
                            Simpan Perubahan
                        </button>
                        @if ($h)
                            <button type="button" class="btn-rok btn-rok--danger" onclick="if(confirm('Reset konfigurasi header halaman ini?')) document.getElementById('reset-form-{{ $key }}').submit();">
                                Reset
                            </button>
                        @endif
                    </div>
                </form>

                @if ($h)
                    <form id="reset-form-{{ $key }}" method="POST" action="{{ route('management.page-headers.delete', $key) }}" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
