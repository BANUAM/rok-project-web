@extends('layouts.app')

@section('title', 'Tes LSM Calon Pemain | ROK Revamp')
@section('description', 'Ujian tertulis online taktik dan pemahaman strategi Mobile Legends untuk calon atlet esports Ruler Of Kings.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge">TAHAP 03 &bull; EVALUASI LSM ONLINE</span>
            <h1 class="page-title">{{ $lsm->title }}</h1>
            <p class="page-subtitle">
                {{ $lsm->description }}
            </p>
        </div>
    </section>

    <section class="lsm-exam-section">
        <div class="section-inner">
            {{-- Exam Header Bar: Timer & Counter --}}
            <div class="exam-status-bar">
                <div class="exam-candidate-info">
                    <span class="avatar-circle"><i data-lucide="user" style="width:18px;height:18px;"></i></span>
                    <div>
                        <strong>{{ $candidate->name }} ({{ $candidate->nickname }})</strong>
                        <span class="candidate-role-pill">{{ $candidate->role }} &bull; {{ $candidate->rank_display }}</span>
                    </div>
                </div>

                <div class="exam-timer-box" id="examTimerBox">
                    <span class="timer-icon"><i data-lucide="clock" style="width:16px;height:16px;"></i></span>
                    <span>Waktu Tersisa:</span>
                    <strong id="timerDisplay">{{ sprintf('%02d:00', $lsm->duration_minutes) }}</strong>
                </div>
            </div>

            {{-- Exam Questions Form --}}
            <form
                method="POST"
                action="{{ route('trial.submit-lsm', ['token' => $candidate->access_token]) }}"
                id="lsmExamForm"
                class="lsm-exam-form"
            >
                @csrf

                @php $questions = $lsm->questions; @endphp

                <div class="questions-deck">
                    @foreach ($questions as $qIndex => $q)
                        <div
                            class="question-card {{ $qIndex === 0 ? 'is-active' : '' }}"
                            id="q-card-{{ $qIndex }}"
                            data-question-index="{{ $qIndex }}"
                        >
                            <div class="question-card__header">
                                <span class="q-number">SOAL {{ $qIndex + 1 }} DARI {{ $questions->count() }}</span>
                                <span class="q-topic">Pemahaman Taktis &amp; Strategi MLBB</span>
                            </div>

                            @if ($q->image_path)
                                <div class="question-card__image">
                                    <img src="{{ $q->image_url }}" alt="Ilustrasi Soal {{ $qIndex + 1 }}">
                                </div>
                            @endif

                            <h3 class="question-card__text">
                                {{ $q->question }}
                            </h3>

                            <div class="options-group">
                                @foreach ($q->options as $optIndex => $opt)
                                    <label class="option-label" for="opt-{{ $opt->id }}">
                                        <input
                                            type="radio"
                                            name="answers[{{ $q->id }}]"
                                            id="opt-{{ $opt->id }}"
                                            value="{{ $opt->id }}"
                                            class="option-radio"
                                            onchange="markQuestionAnswered({{ $qIndex }})"
                                            required
                                        >
                                        <span class="option-letter">{{ chr(65 + $optIndex) }}</span>
                                        <span class="option-text">{{ $opt->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Exam Navigation Bar --}}
                <div class="exam-nav-bar">
                    <button
                        type="button"
                        id="btnPrevQ"
                        class="btn-rok btn-rok--ghost"
                        onclick="navigateQuestion(-1)"
                        disabled
                    >
                        &larr; Soal Sebelumnya
                    </button>

                    {{-- Question Quick Numbers --}}
                    <div class="question-nav-pills">
                        @foreach ($questions as $qIndex => $q)
                            <button
                                type="button"
                                class="q-pill {{ $qIndex === 0 ? 'is-active' : '' }}"
                                id="q-pill-{{ $qIndex }}"
                                onclick="jumpToQuestion({{ $qIndex }})"
                            >
                                {{ $qIndex + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <button
                        type="button"
                        id="btnNextQ"
                        class="btn-rok btn-rok--outline"
                        onclick="navigateQuestion(1)"
                    >
                        Soal Berikutnya &rarr;
                    </button>

                    <button
                        type="submit"
                        id="btnSubmitExam"
                        class="btn-rok btn-rok--gold"
                        style="display: none; align-items: center; gap: 6px;"
                        onclick="return confirm('Apakah kamu yakin ingin menyelesaikan dan mengirimkan hasil ujian LSM ini? Jawaban tidak dapat diubah kembali.')"
                    >
                        <span>Kirim Jawaban LSM</span> <i data-lucide="check" style="width:14px;height:14px;"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
let currentQuestion = 0;
const totalQuestions = {{ $questions->count() }};
let remainingSeconds = {{ $lsm->duration_minutes * 60 }};

// Countdown Timer
const timerDisplay = document.getElementById('timerDisplay');
const timerInterval = setInterval(() => {
    remainingSeconds--;
    if (remainingSeconds <= 0) {
        clearInterval(timerInterval);
        alert('Waktu ujian telah habis! Jawaban kamu akan otomatis dikirimkan.');
        document.getElementById('lsmExamForm').submit();
        return;
    }
    const mins = Math.floor(remainingSeconds / 60);
    const secs = remainingSeconds % 60;
    timerDisplay.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}, 1000);

function jumpToQuestion(index) {
    document.querySelectorAll('.question-card').forEach(c => c.classList.remove('is-active'));
    document.querySelectorAll('.q-pill').forEach(p => p.classList.remove('is-active'));

    currentQuestion = index;
    const targetCard = document.getElementById(`q-card-${index}`);
    const targetPill = document.getElementById(`q-pill-${index}`);

    if (targetCard) targetCard.classList.add('is-active');
    if (targetPill) targetPill.classList.add('is-active');

    // Button states
    document.getElementById('btnPrevQ').disabled = (currentQuestion === 0);
    if (currentQuestion === totalQuestions - 1) {
        document.getElementById('btnNextQ').style.display = 'none';
        document.getElementById('btnSubmitExam').style.display = 'inline-flex';
    } else {
        document.getElementById('btnNextQ').style.display = 'inline-flex';
        document.getElementById('btnSubmitExam').style.display = 'none';
    }
}

function navigateQuestion(delta) {
    const nextIdx = currentQuestion + delta;
    if (nextIdx >= 0 && nextIdx < totalQuestions) {
        jumpToQuestion(nextIdx);
    }
}

function markQuestionAnswered(qIndex) {
    const pill = document.getElementById(`q-pill-${qIndex}`);
    if (pill) pill.classList.add('is-answered');
}
</script>
@endsection
