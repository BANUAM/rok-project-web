@extends('layouts.management')

@section('title', 'Kelola Divisi Tim | ROK Management')
@section('header_title', 'DIVISI TIM KOMPETITIF')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <p class="text-muted">Kelola divisi permainan (e.g. MLBB Men, MLBB Ladies, dll) yang mengelompokkan para pro player.</p>
        <button type="button" class="btn-rok btn-rok--gold" onclick="document.getElementById('modalAddDiv').style.display = 'flex'">
            + Tambah Divisi Baru
        </button>
    </div>

    <div class="mgmt-card">
        <div class="table-responsive">
            <table class="mgmt-table">
                <thead>
                    <tr>
                        <th>Ikon</th>
                        <th>Nama Divisi</th>
                        <th>Judul Game</th>
                        <th>Jumlah Roster</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($divisions as $div)
                        <tr>
                            <td>
                                <img src="{{ $div->icon_url }}" alt="" class="table-avatar">
                            </td>
                            <td><strong>{{ $div->name }}</strong></td>
                            <td><span class="badge-mini">{{ $div->game_name }}</span></td>
                            <td><strong>{{ $div->players_count }}</strong> Anggota</td>
                            <td><p class="text-muted text-xs line-clamp-1">{{ $div->description }}</p></td>
                            <td>
                                <div class="action-btn-group">
                                    <a href="{{ route('management.players', ['division_id' => $div->id]) }}" class="btn-sm btn-sm--outline">
                                        Lihat Roster
                                    </a>
                                    <form method="POST" action="{{ route('management.divisions.delete', $div) }}" onsubmit="return confirm('Hapus divisi ini? Semua pemain di dalamnya akan dipisahkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm ml-2">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada divisi yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add Division --}}
<div id="modalAddDiv" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3>TAMBAH DIVISI KOMPETITIF BARU</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddDiv').style.display = 'none'">&times;</button>
        </div>
        <form method="POST" action="{{ route('management.divisions.store') }}" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Nama Divisi <span class="text-gold">*</span></label>
                    <input type="text" name="name" class="form-input" required placeholder="Contoh: ROK Valkyrie (MLBB Ladies)">
                </div>
                <div class="form-field">
                    <label class="form-label">Game Esports <span class="text-gold">*</span></label>
                    <input type="text" name="game_name" class="form-input" value="Mobile Legends: Bang Bang" required>
                </div>
            </div>
            <div class="form-field">
                <label class="form-label">Deskripsi Divisi</label>
                <textarea name="description" class="form-textarea" rows="2" placeholder="Fokus divisi dan kompetisi yang diikuti..."></textarea>
            </div>
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Ikon / Logo Divisi (SVG / PNG)</label>
                    <input type="file" name="icon" class="form-input" accept="image/*">
                </div>
                <div class="form-field">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-input" value="1" required>
                </div>
            </div>
            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddDiv').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Divisi &rarr;</button>
            </div>
        </form>
    </div>
</div>
@endsection
