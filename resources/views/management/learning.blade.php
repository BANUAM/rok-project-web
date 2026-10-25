@extends('layouts.management')

@section('title', 'Kelola Modul Pembelajaran | ROK Management')
@section('header_title', 'KURIKULUM &amp; MODUL LEARNING')

@section('content')
<div class="mgmt-page-deck">
    <div class="mgmt-page-actions">
        <p class="text-muted">Kelola silabus, video strategi, dan bab materi pembelajaran untuk member dan atlet ROK.</p>
        <button type="button" class="btn-rok btn-rok--gold" onclick="document.getElementById('modalAddModule').style.display = 'flex'">
            + Tambah Modul Baru
        </button>
    </div>

    <div class="modules-management-deck">
        @forelse ($modules as $mod)
            <div class="mgmt-card mb-4">
                <div class="mgmt-card__header">
                    <div class="header-left">
                        <span class="badge-mini">{{ $mod->category }}</span>
                        <h3 class="mt-1">{{ $mod->title }}</h3>
                    </div>
                    <button type="button" class="btn-sm btn-sm--gold" onclick="openAddLessonModal({{ $mod->id }}, '{{ addslashes($mod->title) }}')">
                        + Tambah Lesson Materi
                    </button>
                </div>
                <p class="text-muted text-sm">{{ $mod->description }}</p>

                <div class="table-responsive mt-3">
                    <table class="mgmt-table">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Judul Materi Lesson</th>
                                <th>Format</th>
                                <th>Durasi / File</th>
                                <th>Tipe Akses</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mod->lessons as $lesson)
                                <tr>
                                    <td><strong>#{{ $lesson->sort_order }}</strong></td>
                                    <td>
                                        <strong>{{ $lesson->title }}</strong>
                                        <p class="text-muted text-xs line-clamp-1">{{ $lesson->summary }}</p>
                                    </td>
                                    <td>
                                        @if ($lesson->isPdf())
                                            <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; display:inline-flex; align-items:center; gap:4px;">
                                                <i data-lucide="file-text" style="width:12px;height:12px;"></i> PDF
                                            </span>
                                        @else
                                            <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4); padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; display:inline-flex; align-items:center; gap:4px;">
                                                <i data-lucide="video" style="width:12px;height:12px;"></i> Video
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($lesson->isPdf())
                                            <span>{{ $lesson->duration ?: ($lesson->formatted_file_size ?: 'Dokumen PDF') }}</span>
                                        @else
                                            <span>{{ $lesson->duration ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $lesson->is_free_preview ? 'status-badge--passed' : 'status-badge--under_review' }}">
                                            {{ $lesson->is_free_preview ? 'Free Preview' : 'Member Only' }}
                                        </span>
                                    </td>
                                    <td class="text-xs">
                                        <div style="display: flex; gap: 8px; align-items: center;">
                                            @if ($lesson->isPdf() && $lesson->pdf_url)
                                                <a href="{{ $lesson->pdf_url }}" target="_blank" class="text-gold" title="Buka PDF">
                                                    Unduh &rarr;
                                                </a>
                                            @elseif ($lesson->video_url)
                                                <a href="{{ $lesson->video_url }}" target="_blank" class="text-gold" title="Tonton Video">
                                                    Tonton &rarr;
                                                </a>
                                            @endif
                                            <form method="POST" action="{{ route('management.learning.lessons.delete', $lesson) }}" onsubmit="return confirm('Hapus materi lesson ini?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-sm--danger" style="padding: 2px 6px; font-size: 10px;">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Belum ada lesson materi di modul ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="empty-state-box">
                <p>Belum ada modul pembelajaran yang dibuat.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Modal Add Module --}}
<div id="modalAddModule" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3>TAMBAH MODUL KURIKULUM BARU</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddModule').style.display = 'none'">&times;</button>
        </div>
        <form method="POST" action="{{ route('management.learning.modules.store') }}" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-field">
                <label class="form-label">Judul Modul <span class="text-gold">*</span></label>
                <input type="text" name="title" class="form-input" required placeholder="Contoh: Macro Mastery & Laning Phase">
            </div>
            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-input" value="Macro Strategy" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-input" value="1" required>
                </div>
            </div>
            <div class="form-field">
                <label class="form-label">Deskripsi Ringkas</label>
                <textarea name="description" class="form-textarea" rows="2" placeholder="Silabus materi yang dipelajari..."></textarea>
            </div>
            <div class="form-field">
                <label class="form-label">Thumbnail Modul (SVG / PNG)</label>
                <input type="file" name="thumbnail" class="form-input" accept="image/*">
            </div>
            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddModule').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Modul &rarr;</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Add Lesson --}}
<div id="modalAddLesson" class="mgmt-modal" style="display: none;">
    <div class="mgmt-modal__backdrop" onclick="this.parentElement.style.display = 'none'"></div>
    <div class="mgmt-modal__dialog">
        <div class="mgmt-modal__header">
            <h3 id="addLessonModalTitle">TAMBAH LESSON MATERI</h3>
            <button type="button" class="mgmt-modal__close" onclick="document.getElementById('modalAddLesson').style.display = 'none'">&times;</button>
        </div>
        <form id="addLessonForm" method="POST" action="" enctype="multipart/form-data" class="mgmt-modal__form">
            @csrf
            <div class="form-field">
                <label class="form-label">Tipe Format Materi <span class="text-gold">*</span></label>
                <div style="display: flex; gap: 20px; margin-top: 6px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #ffffff;">
                        <input type="radio" name="content_type" value="video" checked onchange="toggleLessonType('video')">
                        <span style="display:inline-flex;align-items:center;gap:5px;"><i data-lucide="video" style="width:14px;height:14px;"></i> Video Pembelajaran (YouTube/Vimeo)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #ffffff;">
                        <input type="radio" name="content_type" value="pdf" onchange="toggleLessonType('pdf')">
                        <span style="display:inline-flex;align-items:center;gap:5px;"><i data-lucide="file-text" style="width:14px;height:14px;"></i> Dokumen / Playbook PDF</span>
                    </label>
                </div>
            </div>

            <div class="form-field">
                <label class="form-label">Judul Materi Lesson <span class="text-gold">*</span></label>
                <input type="text" name="title" class="form-input" required placeholder="Contoh: Analisis Rotasi Mid Lane atau Playbook Draft Season 2026">
            </div>

            {{-- Video Fields --}}
            <div id="videoFieldsGroup" class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Video Embed URL (YouTube/Vimeo)</label>
                    <input type="url" name="video_url" id="lessonVideoUrl" class="form-input" placeholder="https://www.youtube-nocookie.com/embed/...">
                </div>
                <div class="form-field">
                    <label class="form-label">Durasi Video</label>
                    <input type="text" name="duration" class="form-input" placeholder="15:30">
                </div>
            </div>

            {{-- PDF Fields --}}
            <div id="pdfFieldsGroup" class="form-row-2" style="display: none;">
                <div class="form-field">
                    <label class="form-label">Unggah File PDF (Maks. 10MB) <span class="text-gold">*</span></label>
                    <input type="file" name="pdf_file" id="lessonPdfFile" class="form-file" accept="application/pdf,.pdf">
                </div>
                <div class="form-field">
                    <label class="form-label">Keterangan / Estimasi Baca</label>
                    <input type="text" name="pdf_duration" id="lessonPdfDuration" class="form-input" placeholder="Contoh: 15 Hal PDF / 20 Menit">
                </div>
            </div>

            <div class="form-field">
                <label class="form-label">Rangkuman / Deskripsi Materi</label>
                <textarea name="summary" class="form-textarea" rows="2" placeholder="Poin-poin penting materi ini..."></textarea>
            </div>

            <div class="form-row-2">
                <div class="form-field">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-input" value="1" required>
                </div>
                <div class="form-field form-field--checkbox">
                    <label class="checkbox-label mt-4">
                        <input type="checkbox" name="is_free_preview" value="1">
                        <span>Jadikan Free Preview (Dapat diakses tanpa membership)</span>
                    </label>
                </div>
            </div>
            <div class="mgmt-modal__footer">
                <button type="button" class="btn-rok btn-rok--ghost" onclick="document.getElementById('modalAddLesson').style.display = 'none'">Batal</button>
                <button type="submit" class="btn-rok btn-rok--gold">Simpan Materi &rarr;</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddLessonModal(moduleId, moduleTitle) {
    document.getElementById('addLessonModalTitle').textContent = `TAMBAH MATERI: ${moduleTitle}`;
    document.getElementById('addLessonForm').action = `{{ url('/management/learning/modules') }}/${moduleId}/lessons`;
    toggleLessonType('video');
    document.getElementById('modalAddLesson').style.display = 'flex';
}

function toggleLessonType(type) {
    const videoGroup = document.getElementById('videoFieldsGroup');
    const pdfGroup = document.getElementById('pdfFieldsGroup');
    const videoInput = document.getElementById('lessonVideoUrl');
    const pdfInput = document.getElementById('lessonPdfFile');

    if (type === 'pdf') {
        videoGroup.style.display = 'none';
        pdfGroup.style.display = 'grid';
        videoInput.removeAttribute('required');
        pdfInput.setAttribute('required', 'required');
    } else {
        videoGroup.style.display = 'grid';
        pdfGroup.style.display = 'none';
        pdfInput.removeAttribute('required');
    }
}
</script>
@endsection
