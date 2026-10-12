@extends('layouts.management')

@section('title', 'Kelola Partner & Sponsor | ROK Management')
@section('header_title', 'OFFICIAL PARTNERS & SPONSORS')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <p class="text-muted">Kelola logo sponsor, media partner, dan community partner yang tampil di footer dan halaman Home.</p>
        <button type="button" class="btn-rok btn-rok--gold" onclick="document.getElementById('modalAddPartner').style.display = 'flex'">
            + Tambah Partner Baru
        </button>
    </div>

    <div class="mgmt-card">
        <div class="table-responsive">
            <table class="mgmt-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nama Brand</th>
                        <th>Kategori</th>
                        <th>Website</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($partners as $partner)
                        <tr>
                            <td>
                                <img src="{{ $partner->logo_url }}" alt="" class="table-logo-preview">
                            </td>
                            <td><strong>{{ $partner->name }}</strong></td>
                            <td><span class="badge-mini">{{ $partner->category }}</span></td>
                            <td>
                                @if ($partner->website_url)
                                    <a href="{{ $partner->website_url }}" target="_blank" class="text-gold text-xs">Kunjungi Web &rarr;</a>
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td><p class="text-muted text-xs line-clamp-1">{{ $partner->description }}</p></td>
                            <td>
                                <form method="POST" action="{{ route('management.partners.delete', $partner) }}" onsubmit="return confirm('Hapus partner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada partner atau sponsor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add Partner --}}
<div id="modalAddPartner" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3>TAMBAH PARTNER / SPONSOR BARU</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddPartner').style.display = 'none'">&times;</button>
        </div>
        <form method="POST" action="{{ route('management.partners.store') }}" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Nama Partner / Brand <span class="text-gold">*</span></label>
                    <input type="text" name="name" class="form-input" required placeholder="Contoh: Secretlab">
                </div>
                <div class="form-field">
                    <label class="form-label">Kategori <span class="text-gold">*</span></label>
                    <input type="text" name="category" class="form-input" required placeholder="Official Gaming Chair">
                </div>
            </div>
            <div class="form-field">
                <label class="form-label">Website URL</label>
                <input type="url" name="website_url" class="form-input" placeholder="https://brand.com">
            </div>
            <div class="form-field">
                <label class="form-label">Deskripsi Kerjasama</label>
                <textarea name="description" class="form-textarea" rows="2" placeholder="Detail sponsorship dan dukungan perangkat..."></textarea>
            </div>
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Logo Partner (SVG / PNG / JPG)</label>
                    <input type="file" name="logo" class="form-input" accept="image/*">
                </div>
                <div class="form-field">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-input" value="1" required>
                </div>
            </div>
            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddPartner').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Partner &rarr;</button>
            </div>
        </form>
    </div>
</div>
@endsection
