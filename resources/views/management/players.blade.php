@extends('layouts.management')

@section('title', 'Kelola Roster & Pemain | ROK Management')
@section('header_title', 'ROSTER ATLET & MANAGEMENT STAFF')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <form method="GET" action="{{ route('management.players') }}" class="inline-filter-form">
            <label class="filter-label">Filter Divisi:</label>
            <select name="division_id" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $d)
                    <option value="{{ $d->id }}" {{ request('division_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <button type="button" class="btn-rok btn-rok--gold" onclick="document.getElementById('modalAddPlayer').style.display = 'flex'">
            + Tambah Anggota / Roster
        </button>
    </div>

    <div class="mgmt-card">
        <div class="table-responsive">
            <table class="mgmt-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nickname &amp; Nama Asli</th>
                        <th>Divisi</th>
                        <th>Role / Posisi</th>
                        <th>Umur &amp; Domisili</th>
                        <th>Status Publik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($players as $player)
                        <tr>
                            <td>
                                <img src="{{ $player->photo_url }}" alt="" class="table-avatar">
                            </td>
                            <td>
                                <strong>{{ $player->nickname }}</strong>
                                <div class="text-muted text-xs">{{ $player->real_name }}</div>
                            </td>
                            <td><span class="badge-mini">{{ $player->division?->name ?? 'Tanpa Divisi' }}</span></td>
                            <td>
                                <span class="badge-tag">{{ $player->role }}</span>
                            </td>
                            <td>
                                <div class="text-xs">{{ $player->age ? $player->age . ' Thn' : '-' }}</div>
                                <div class="text-muted text-xs">{{ $player->domicile ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="status-badge {{ $player->status === 'active' && $player->is_active ? 'status-badge--passed' : 'status-badge--rejected' }}">
                                    {{ $player->status === 'active' && $player->is_active ? 'Aktif (Tampil Publik)' : 'Non-Aktif (Disembunyikan)' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btn-group">
                                    {{-- Quick Toggle Status Form --}}
                                    <form method="POST" action="{{ route('management.players.update', $player) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="division_id" value="{{ $player->division_id }}">
                                        <input type="hidden" name="real_name" value="{{ $player->real_name }}">
                                        <input type="hidden" name="nickname" value="{{ $player->nickname }}">
                                        <input type="hidden" name="role" value="{{ $player->role }}">
                                        <input type="hidden" name="sort_order" value="{{ $player->sort_order }}">
                                        <input type="hidden" name="status" value="{{ $player->status === 'active' ? 'inactive' : 'active' }}">
                                        <input type="hidden" name="is_active" value="{{ $player->status === 'active' ? '0' : '1' }}">
                                        <button type="submit" class="btn-sm btn-sm--outline">
                                            {{ $player->status === 'active' ? 'Non-aktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('management.players.delete', $player) }}" onsubmit="return confirm('Hapus pemain ini dari sistem?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm ml-2">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada pemain pada daftar ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add Player --}}
<div id="modalAddPlayer" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3>TAMBAH PEMAIN / STAFF KE ROSTER</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddPlayer').style.display = 'none'">&times;</button>
        </div>
        <form method="POST" action="{{ route('management.players.store') }}" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Divisi <span class="text-gold">*</span></label>
                    <select name="division_id" class="form-select" required>
                        @foreach ($divisions as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Posisi / Role <span class="text-gold">*</span></label>
                    <select name="role" class="form-select" required>
                        <optgroup label="Manajemen & Coaching">
                            <option value="Manager">Manager</option>
                            <option value="Coach">Coach</option>
                            <option value="Analyst">Analyst</option>
                        </optgroup>
                        <optgroup label="5 Role Mobile Legends">
                            <option value="Gold Laner">Gold Laner</option>
                            <option value="Mid Laner">Mid Laner</option>
                            <option value="Jungler">Jungler</option>
                            <option value="EXP Laner">EXP Laner</option>
                            <option value="Roamer">Roamer</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Nama Asli <span class="text-gold">*</span></label>
                    <input type="text" name="real_name" class="form-input" required placeholder="Mohammad Tegar">
                </div>
                <div class="form-field">
                    <label class="form-label">Nickname (IGN) <span class="text-gold">*</span></label>
                    <input type="text" name="nickname" class="form-input" required placeholder="Tegar">
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-field">
                    <label class="form-label">Umur</label>
                    <input type="number" name="age" class="form-input" placeholder="20">
                </div>
                <div class="form-field">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="birthplace" class="form-input" placeholder="Bekasi">
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="birthdate" class="form-input">
                </div>
            </div>

            <div class="form-field">
                <label class="form-label">Alamat / Domisili</label>
                <input type="text" name="domicile" class="form-input" placeholder="Bekasi, Jawa Barat">
            </div>

            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Foto Profil Pemain (SVG / PNG / JPG)</label>
                    <input type="file" name="photo" class="form-input" accept="image/*">
                </div>
                <div class="form-field">
                    <label class="form-label">Status Awal</label>
                    <select name="status" class="form-select">
                        <option value="active" selected>Aktif (Tampil Publik)</option>
                        <option value="inactive">Non-Aktif (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div class="form-field">
                <label class="form-label">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" class="form-input" value="1" required>
            </div>

            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddPlayer').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Roster &rarr;</button>
            </div>
        </form>
    </div>
</div>
@endsection
