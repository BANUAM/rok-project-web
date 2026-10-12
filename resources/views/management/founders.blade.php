@extends('layouts.management')

@section('title', 'Kelola Founders | ROK Management')
@section('header_title', 'FOUNDERS & LEADERSHIP')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <p class="text-muted">Kelola pendiri dan jajaran pimpinan tertinggi yang ditampilkan di bagian atas halaman Teams.</p>
        <button type="button" class="btn-rok btn-rok--gold" onclick="document.getElementById('modalAddFounder').style.display = 'flex'">
            + Tambah Founder
        </button>
    </div>

    <div class="founders-table-card mgmt-card">
        <div class="table-responsive">
            <table class="mgmt-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Founder</th>
                        <th>Jabatan</th>
                        <th>Bio</th>
                        <th>Sosial Media</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($founders as $founder)
                        <tr>
                            <td>
                                <img src="{{ $founder->photo_url }}" alt="" class="table-avatar">
                            </td>
                            <td><strong>{{ $founder->name }}</strong></td>
                            <td><span class="badge-mini">{{ $founder->role_title }}</span></td>
                            <td><p class="text-muted text-xs line-clamp-2">{{ $founder->bio }}</p></td>
                            <td>
                                <div class="text-xs">
                                    @if ($founder->instagram_url) <a href="{{ $founder->instagram_url }}" target="_blank" class="text-gold">IG</a> @endif
                                    @if ($founder->linkedin_url) <a href="{{ $founder->linkedin_url }}" target="_blank" class="text-gold ml-2">LinkedIn</a> @endif
                                </div>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('management.founders.delete', $founder) }}" onsubmit="return confirm('Hapus data founder ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data founder.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add Founder --}}
<div id="modalAddFounder" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3>TAMBAH FOUNDER BARU</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddFounder').style.display = 'none'">&times;</button>
        </div>
        <form method="POST" action="{{ route('management.founders.store') }}" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Nama Lengkap <span class="text-gold">*</span></label>
                    <input type="text" name="name" class="form-input" required placeholder="Reynaldi Pratama">
                </div>
                <div class="form-field">
                    <label class="form-label">Jabatan (Role Title) <span class="text-gold">*</span></label>
                    <input type="text" name="role_title" class="form-input" required placeholder="Founder & CEO">
                </div>
            </div>
            <div class="form-field">
                <label class="form-label">Biografi Singkat</label>
                <textarea name="bio" class="form-textarea" rows="2" placeholder="Pengalaman dan visi pendiri..."></textarea>
            </div>
            <div class="form-field">
                <label class="form-label">Foto Profil (PNG / JPG / SVG)</label>
                <input type="file" name="photo" class="form-input" accept="image/*">
            </div>
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-input" placeholder="https://instagram.com/...">
                </div>
                <div class="form-field">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" class="form-input" placeholder="https://linkedin.com/...">
                </div>
            </div>
            <div class="form-field">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-input" value="1" required>
            </div>
            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddFounder').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Founder &rarr;</button>
            </div>
        </form>
    </div>
</div>
@endsection
