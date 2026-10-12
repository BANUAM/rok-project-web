@extends('layouts.management')

@section('title', 'Kelola Prestasi | ROK Management')
@section('header_title', 'PRESTASI, SERTIFIKAT & TROFI')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <p class="text-muted">Kelola rekor juara, trofi, sertifikat turnamen, dan caption pencapaian organisasi.</p>
        <button type="button" class="btn-rok btn-rok--gold" onclick="document.getElementById('modalAddAch').style.display = 'flex'">
            + Tambah Prestasi Baru
        </button>
    </div>

    <div class="mgmt-card">
        <div class="table-responsive">
            <table class="mgmt-table">
                <thead>
                    <tr>
                        <th>Sertifikat/Gambar</th>
                        <th>Judul Prestasi</th>
                        <th>Tim / Divisi</th>
                        <th>Tahun</th>
                        <th>Highlight Home</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($achievements as $ach)
                        <tr>
                            <td>
                                <img src="{{ $ach->image_url }}" alt="" class="table-img-preview">
                            </td>
                            <td>
                                <strong>{{ $ach->title }}</strong>
                                <p class="text-muted text-xs line-clamp-1">{{ $ach->caption }}</p>
                            </td>
                            <td>
                                <span class="badge-mini">{{ $ach->team_name ?? 'Ruler Of Kings' }}</span>
                                @if ($ach->division)
                                    <div class="text-xs text-muted">{{ $ach->division->name }}</div>
                                @endif
                            </td>
                            <td><strong>{{ $ach->event_year }}</strong></td>
                            <td>
                                <span class="status-badge {{ $ach->is_highlight ? 'status-badge--passed' : 'status-badge--rejected' }}">
                                    {{ $ach->is_highlight ? 'Ya (Home Preview)' : 'Tidak' }}
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('management.achievements.delete', $ach) }}" onsubmit="return confirm('Hapus prestasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data prestasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add Achievement --}}
<div id="modalAddAch" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3>TAMBAH PRESTASI / PENCAPAIAN BARU</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddAch').style.display = 'none'">&times;</button>
        </div>
        <form method="POST" action="{{ route('management.achievements.store') }}" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-field">
                <label class="form-label">Judul Prestasi / Turnamen <span class="text-gold">*</span></label>
                <input type="text" name="title" class="form-input" required placeholder="Juara 1 — National Esports Cup 2026">
            </div>

            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Nama Tim</label>
                    <input type="text" name="team_name" class="form-input" value="ROK Prime">
                </div>
                <div class="form-field">
                    <label class="form-label">Divisi Terkait</label>
                    <select name="division_id" class="form-select">
                        <option value="">Pilih Divisi (Opsional)</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Tahun Kejuaraan <span class="text-gold">*</span></label>
                    <input type="number" name="event_year" class="form-input" value="{{ date('Y') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">File Bukti / Sertifikat (PNG / JPG / SVG)</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </div>

            <div class="form-field">
                <label class="form-label">Caption / Deskripsi Pencapaian</label>
                <textarea name="caption" class="form-textarea" rows="2" placeholder="Detail kemenangan, skor, dan jalannya turnamen..."></textarea>
            </div>

            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-input" value="1" required>
                </div>
                <div class="form-field form-field--checkbox">
                    <label class="checkbox-label mt-4">
                        <input type="checkbox" name="is_highlight" value="1" checked>
                        <span>Tampilkan di Preview Halaman Home</span>
                    </label>
                </div>
            </div>

            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddAch').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Prestasi &rarr;</button>
            </div>
        </form>
    </div>
</div>
@endsection
