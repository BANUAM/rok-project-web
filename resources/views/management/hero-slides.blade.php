@extends('layouts.management')

@section('title', 'Kelola Hero Banners | ROK Management')
@section('header_title', 'HERO BANNERS (HOMEPAGE SLIDER)')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <p class="text-muted">Kelola gambar banner, headline slogan, dan tombol CTA yang tampil di hero section utama.</p>
        <button type="button" class="btn-rok btn-rok--gold" onclick="document.getElementById('modalAddSlide').style.display = 'flex'">
            + Tambah Banner Baru
        </button>
    </div>

    <div class="slides-table-card mgmt-card">
        <div class="table-responsive">
            <table class="mgmt-table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Preview Gambar</th>
                        <th>Judul Banner</th>
                        <th>Tombol CTA</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($slides as $slide)
                        <tr>
                            <td><strong>#{{ $slide->sort_order }}</strong></td>
                            <td>
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($slide->image_path) }}" alt="" class="table-img-preview" onerror="this.src='{{ asset('img/dummy-division.svg') }}'">
                            </td>
                            <td>
                                <strong>{{ $slide->title }}</strong>
                                <p class="text-muted text-xs line-clamp-1">{{ $slide->description }}</p>
                            </td>
                            <td>
                                <div class="text-xs">
                                    <span class="badge-mini">{{ $slide->cta_primary_text }}</span> &rarr; {{ $slide->cta_primary_url }}
                                </div>
                                <div class="text-xs mt-1">
                                    <span class="badge-mini">{{ $slide->cta_secondary_text }}</span> &rarr; {{ $slide->cta_secondary_url }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $slide->is_active ? 'status-badge--passed' : 'status-badge--rejected' }}">
                                    {{ $slide->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('management.hero-slides.delete', $slide) }}" onsubmit="return confirm('Hapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada slide hero.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add Slide --}}
<div id="modalAddSlide" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3>TAMBAH BANNER HERO BARU</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddSlide').style.display = 'none'">&times;</button>
        </div>
        <form method="POST" action="{{ route('management.hero-slides.store') }}" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-field">
                <label class="form-label">Judul Banner (Headline) <span class="text-gold">*</span></label>
                <input type="text" name="title" class="form-input" required placeholder="Contoh: RULER OF KINGS REVAMP">
            </div>
            <div class="form-field">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-textarea" rows="2" placeholder="Deskripsi slogan atau pengantar..."></textarea>
            </div>
            <div class="form-field">
                <label class="form-label">Upload File Gambar Banner (PNG / JPG / SVG) <span class="text-gold">*</span></label>
                <input type="file" name="image" class="form-input" accept="image/*">
            </div>
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Teks Tombol Utama</label>
                    <input type="text" name="cta_primary_text" class="form-input" value="Join Our Trial" required>
                </div>
                <div class="form-field">
                    <label class="form-label">URL Tombol Utama</label>
                    <input type="text" name="cta_primary_url" class="form-input" value="/trial" required>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Teks Tombol Sekunder</label>
                    <input type="text" name="cta_secondary_text" class="form-input" value="Explore Teams" required>
                </div>
                <div class="form-field">
                    <label class="form-label">URL Tombol Sekunder</label>
                    <input type="text" name="cta_secondary_url" class="form-input" value="/teams" required>
                </div>
            </div>
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="sort_order" class="form-input" value="1" required>
                </div>
                <div class="form-field form-field--checkbox">
                    <label class="checkbox-label mt-4">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Langsung Aktifkan Banner</span>
                    </label>
                </div>
            </div>
            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddSlide').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Banner &rarr;</button>
            </div>
        </form>
    </div>
</div>
@endsection
