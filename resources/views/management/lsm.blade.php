@extends('layouts.management')

@section('title', 'Manajemen Tes LSM | ROK Management')
@section('header_title', 'PENGELOLAAN SOAL &amp; KONFIGURASI LSM')

@section('content')
<div class="mgmt-page-deck">
    @if ($lsm)
        <div class="cd-two-cols">
            {{-- Left: Test Configuration Settings --}}
            <div class="cd-col">
                <div class="mgmt-card">
                    <div class="mgmt-card__header">
                        <h3>KONFIGURASI PENGATURAN TES LSM</h3>
                        <span class="status-badge {{ $lsm->is_published ? 'status-badge--passed' : 'status-badge--rejected' }}">
                            {{ $lsm->is_published ? 'Dipublikasikan' : 'Draft' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('management.lsm.settings', $lsm) }}" class="mgmt-modal__form">
                        @csrf
                        <div class="form-field">
                            <label class="form-label">Judul Ujian LSM <span class="text-gold">*</span></label>
                            <input type="text" name="title" class="form-input" value="{{ old('title', $lsm->title) }}" required>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Deskripsi &amp; Petunjuk Ujian</label>
                            <textarea name="description" class="form-textarea" rows="3">{{ old('description', $lsm->description) }}</textarea>
                        </div>

                        <div class="form-row-3">
                            <div class="form-field">
                                <label class="form-label">Batas Jumlah Soal</label>
                                <input type="number" name="question_limit" class="form-input" value="{{ old('question_limit', $lsm->question_limit) }}" required>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Durasi Waktu (Menit)</label>
                                <input type="number" name="duration_minutes" class="form-input" value="{{ old('duration_minutes', $lsm->duration_minutes) }}" required>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Ambang Kelulusan (%)</label>
                                <input type="number" name="passing_score" class="form-input" value="{{ old('passing_score', $lsm->passing_score) }}" required>
                            </div>
                        </div>

                        <div class="form-field form-field--checkbox">
                            <label class="checkbox-label">
                                <input type="checkbox" name="is_published" value="1" {{ $lsm->is_published ? 'checked' : '' }}>
                                <span>Publikasikan Tes (Calon kandidat dapat mengakses ujian ini)</span>
                            </label>
                        </div>

                        <button type="submit" class="btn-rok btn-rok--gold btn-rok--full">
                            Simpan Pengaturan LSM &rarr;
                        </button>
                    </form>
                </div>

                {{-- Ringkasan Peserta yang Mengerjakan --}}
                <div class="mgmt-card mt-4">
                    <div class="mgmt-card__header">
                        <h3>STATISTIK PESERTA UJIAN</h3>
                        <span class="badge-mini">{{ $lsm->attempts->count() }} Percobaan</span>
                    </div>
                    <div class="table-responsive">
                        <table class="mgmt-table">
                            <thead>
                                <tr>
                                    <th>Kandidat</th>
                                    <th>Skor</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lsm->attempts->take(5) as $att)
                                    <tr>
                                        <td><strong>{{ $att->candidate?->name }}</strong></td>
                                        <td><strong>{{ number_format($att->score, 1) }}%</strong></td>
                                        <td>
                                            <span class="status-badge {{ $att->is_passed ? 'status-badge--passed' : 'status-badge--rejected' }}">
                                                {{ $att->is_passed ? 'Lolos' : 'Gagal' }}
                                            </span>
                                        </td>
                                        <td class="text-xs">{{ $att->completed_at ? $att->completed_at->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada peserta mengerjakan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right: Questions Bank & Add Question Form --}}
            <div class="cd-col">
                <div class="mgmt-card">
                    <div class="mgmt-card__header">
                        <h3>BANK SOAL PILIHAN GANDA ({{ $lsm->questions->count() }} SOAL)</h3>
                        <button type="button" class="btn-sm btn-sm--gold" onclick="document.getElementById('addQuestionBox').scrollIntoView({ behavior: 'smooth' })">
                            + Buat Soal Baru
                        </button>
                    </div>

                    <div class="questions-admin-list">
                        @foreach ($lsm->questions as $idx => $q)
                            <div class="q-admin-item">
                                <div class="q-admin-item__header">
                                    <strong>#{{ $idx + 1 }}. {{ $q->question }}</strong>
                                    <form method="POST" action="{{ route('management.lsm.questions.delete', $q) }}" onsubmit="return confirm('Hapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-sm">Hapus</button>
                                    </form>
                                </div>
                                @if ($q->image_path)
                                    <div class="q-admin-item__img">
                                        <img src="{{ $q->image_url }}" alt="Ilustrasi" style="max-height: 100px;">
                                    </div>
                                @endif
                                <div class="q-admin-item__options">
                                    @foreach ($q->options as $oIdx => $opt)
                                        <div class="q-admin-option {{ $opt->is_correct ? 'is-correct' : '' }}">
                                            <span>{{ chr(65 + $oIdx) }}. {{ $opt->option_text }}</span>
                                            @if ($opt->is_correct)
                                                <strong class="text-gold">&#10003; Kunci Jawaban</strong>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Add Question Form --}}
                <div class="mgmt-card mt-4" id="addQuestionBox">
                    <div class="mgmt-card__header">
                        <h3>TAMBAH SOAL LSM BARU</h3>
                    </div>
                    <form method="POST" action="{{ route('management.lsm.questions.store', $lsm) }}" enctype="multipart/form-data" class="mgmt-modal__form">
                        @csrf
                        <div class="form-field">
                            <label class="form-label">Teks Pertanyaan <span class="text-gold">*</span></label>
                            <textarea name="question" class="form-textarea" rows="3" required placeholder="Tuliskan pertanyaan taktik game..."></textarea>
                        </div>

                        <div class="form-field">
                            <label class="form-label">Gambar Ilustrasi Soal (Opsional, PNG / JPG / SVG)</label>
                            <input type="file" name="image" class="form-input" accept="image/*">
                        </div>

                        <div class="options-creation-deck">
                            <label class="form-label">Pilihan Jawaban &amp; Kunci Jawaban <span class="text-gold">*</span></label>
                            <div class="option-create-row">
                                <input type="radio" name="correct_option_index" value="0" checked title="Tandai sebagai kunci jawaban">
                                <span class="opt-tag">A</span>
                                <input type="text" name="options[]" class="form-input" placeholder="Pilihan jawaban A" required>
                            </div>
                            <div class="option-create-row mt-2">
                                <input type="radio" name="correct_option_index" value="1" title="Tandai sebagai kunci jawaban">
                                <span class="opt-tag">B</span>
                                <input type="text" name="options[]" class="form-input" placeholder="Pilihan jawaban B" required>
                            </div>
                            <div class="option-create-row mt-2">
                                <input type="radio" name="correct_option_index" value="2" title="Tandai sebagai kunci jawaban">
                                <span class="opt-tag">C</span>
                                <input type="text" name="options[]" class="form-input" placeholder="Pilihan jawaban C" required>
                            </div>
                            <div class="option-create-row mt-2">
                                <input type="radio" name="correct_option_index" value="3" title="Tandai sebagai kunci jawaban">
                                <span class="opt-tag">D</span>
                                <input type="text" name="options[]" class="form-input" placeholder="Pilihan jawaban D" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-rok btn-rok--gold btn-rok--full mt-4">
                            + Simpan Soal ke Bank LSM &rarr;
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="empty-state-box">
            <p>Data modul LSM belum tersedia. Silakan jalankan seeder terlebih dahulu.</p>
        </div>
    @endif
</div>
@endsection
