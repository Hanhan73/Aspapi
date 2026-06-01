<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarEnrollment;
use App\Models\SeminarAttempt;
use App\Models\SeminarAttemptAnswer;
use App\Models\SeminarCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeminarController extends Controller
{
    const QUOTA_PER_PERIOD = 3;
    const PRETEST_COUNT    = 5;

    private function getMember()
    {
        return auth()->user()->member;
    }

    private function getRemainingQuota($member): int
    {
        $periodStart = $member->dues_paid_at; // awal periode = tanggal iuran terakhir diverifikasi

        $used = SeminarEnrollment::where('member_id', $member->id)
            ->where('membership_period_start', $periodStart)
            ->count();

        return max(0, self::QUOTA_PER_PERIOD - $used);
    }

    /**
     * Helper: acak urutan opsi dan kembalikan sebagai array key => value
     * Key tetap asli (a/b/c/d/e) sehingga penilaian tetap valid.
     */
    private function buildShuffledOptions($questions): array
    {
        $shuffledOptions = [];
        foreach ($questions as $q) {
            $opts = $q->getOptions();
            $keys = array_keys($opts);
            shuffle($keys);
            // Simpan sebagai array [ ['key'=>'c','label'=>'...'], ... ]
            $shuffledOptions[$q->id] = array_map(fn($k) => ['key' => $k, 'label' => $opts[$k]], $keys);
        }
        return $shuffledOptions;
    }

    public function index(Request $request)
    {
        $member   = $this->getMember();
        $seminars = Seminar::where('is_active', true)
            ->withCount('questions')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('title', 'like', '%' . $request->search . '%')
                       ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $enrolledMap = SeminarEnrollment::where('member_id', $member->id)
            ->pluck('id', 'seminar_id')
            ->toArray();

        $enrolledIds    = array_keys($enrolledMap);
        $remainingQuota = $this->getRemainingQuota($member);
        $isActive       = $member->status === 'active';

        return view('member.seminar.index', compact(
            'seminars', 'enrolledIds', 'enrolledMap', 'remainingQuota', 'isActive', 'member'
        ));
    }

    public function mySeminars()
    {
        $member   = $this->getMember();
        $isActive = $member->status === 'active';

        $allEnrollments = SeminarEnrollment::where('member_id', $member->id)
            ->with(['seminar', 'certificate'])
            ->latest()
            ->get();

        $grouped = $allEnrollments->groupBy(function ($e) {
            return \Carbon\Carbon::parse($e->membership_period_start)->toDateString();
        });

        $currentPeriod = $member->dues_paid_at
            ? \Carbon\Carbon::parse($member->dues_paid_at)->toDateString()
            : null;

        $usedThisPeriod = $currentPeriod
            ? $grouped->get($currentPeriod, collect())->count()
            : 0;

        return view('member.seminar.my-seminars', compact(
            'grouped', 'isActive', 'currentPeriod', 'usedThisPeriod'
        ));
    }

    public function enroll(Request $request, Seminar $seminar)
    {
        $member = $this->getMember();

        abort_if($member->status !== 'active', 403, 'Keanggotaan tidak aktif.');

        $alreadyEnrolled = SeminarEnrollment::where('member_id', $member->id)
            ->where('seminar_id', $seminar->id)
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('error', 'Kamu sudah terdaftar di seminar ini.');
        }

        if ($this->getRemainingQuota($member) <= 0) {
            return back()->with('error', 'Kuota seminar untuk periode aktif ini sudah habis (maks. 3 seminar).');
        }

        if ($seminar->questions()->count() < self::PRETEST_COUNT) {
            return back()->with('error', 'Seminar ini belum siap (soal belum cukup).');
        }

        SeminarEnrollment::create([
            'member_id'               => $member->id,
            'seminar_id'              => $seminar->id,
            'membership_period_start' => $member->dues_paid_at,
            'status'                  => 'enrolled',
        ]);

        return redirect()->route('member.seminar.my-seminars')
            ->with('success', 'Berhasil mendaftar seminar "' . $seminar->title . '".');
    }

    public function show(SeminarEnrollment $enrollment)
    {


        
        $member = $this->getMember();
        dd($enrollment->member_id, $member?->id, $enrollment->member_id !== $member?->id);
        abort_if($enrollment->member_id !== $member->id, 403);

        $enrollment->load(['seminar.questions', 'certificate']);
        $preTest  = $enrollment->preTest();
        $postTest = $enrollment->postTest();

        return view('member.seminar.show', compact('enrollment', 'preTest', 'postTest'));
    }

    /**
     * Mulai pre-test: 5 soal acak, urutan opsi juga diacak & disimpan di session.
     */
    public function startPreTest(SeminarEnrollment $enrollment)
    {
        $member = $this->getMember();
        abort_if($enrollment->member_id !== $member->id, 403);
        abort_if($enrollment->isPreTestDone(), 403, 'Pre-test sudah selesai.');

        // Soal diacak dari DB
        $questions = $enrollment->seminar->questions()
            ->inRandomOrder()
            ->limit(self::PRETEST_COUNT)
            ->get();

        $attempt = SeminarAttempt::create([
            'enrollment_id' => $enrollment->id,
            'type'          => 'pre_test',
            'started_at'    => now(),
        ]);

        foreach ($questions as $q) {
            SeminarAttemptAnswer::create([
                'attempt_id'  => $attempt->id,
                'question_id' => $q->id,
            ]);
        }

        // Acak urutan opsi dan simpan ke session supaya tidak berubah saat reload
        $shuffledOptions = $this->buildShuffledOptions($questions);
        session(["test_options_{$attempt->id}" => $shuffledOptions]);

        return view('member.seminar.pre-test', compact('enrollment', 'attempt', 'questions', 'shuffledOptions'));
    }

    public function submitPreTest(Request $request, SeminarAttempt $attempt)
    {
        $enrollment = $attempt->enrollment;
        $member     = $this->getMember();
        abort_if($enrollment->member_id !== $member->id, 403);
        abort_if($attempt->submitted_at !== null, 403, 'Attempt sudah disubmit.');

        $answers = $request->input('answers', []);

        DB::transaction(function () use ($attempt, $answers, $enrollment) {
            foreach ($attempt->answers as $answerRecord) {
                $chosen    = $answers[$answerRecord->question_id] ?? null;
                $isCorrect = $chosen !== null
                    ? $chosen === $answerRecord->question->correct_answer
                    : false;

                $answerRecord->update([
                    'answer'     => $chosen,
                    'is_correct' => $isCorrect,
                ]);
            }

            $attempt->calculateAndSaveScore();
            $enrollment->update(['status' => 'pre_test_done']);
        });

        // Hapus session opsi
        session()->forget("test_options_{$attempt->id}");

        return redirect()->route('member.seminar.show', $enrollment)
            ->with('success', 'Pre-test selesai! Skor kamu: ' . $attempt->fresh()->score . '. Silakan lanjut ke materi.');
    }

    public function markMaterialRead(SeminarEnrollment $enrollment)
    {
        $member = $this->getMember();
        abort_if($enrollment->member_id !== $member->id, 403);
        abort_if(! $enrollment->isPreTestDone(), 403, 'Selesaikan pre-test dulu.');

        if ($enrollment->status === 'pre_test_done') {
            $enrollment->update(['status' => 'material_read']);
        }

        return redirect()->route('member.seminar.show', $enrollment)
            ->with('success', 'Materi ditandai selesai dibaca. Silakan kerjakan post-test.');
    }

    /**
     * Mulai post-test: semua soal diacak, opsi juga diacak & disimpan di session.
     */
    public function startPostTest(SeminarEnrollment $enrollment)
    {
        $member = $this->getMember();
        abort_if($enrollment->member_id !== $member->id, 403);
        abort_if(! $enrollment->isMaterialRead(), 403, 'Baca materi dulu sebelum post-test.');
        abort_if($enrollment->isCompleted(), 403, 'Kamu sudah lulus seminar ini.');

        // Cek attempt yang belum disubmit — lanjutkan jika ada
        $existingAttempt = SeminarAttempt::where('enrollment_id', $enrollment->id)
            ->where('type', 'post_test')
            ->whereNull('submitted_at')
            ->latest()
            ->first();

        if ($existingAttempt) {
            // Ambil soal sesuai urutan yang sudah tersimpan di answers
            $questions = $existingAttempt->answers()
                ->with('question')
                ->get()
                ->pluck('question');

            // Ambil shuffled options dari session, atau buat ulang jika session expired
            $shuffledOptions = session("test_options_{$existingAttempt->id}")
                ?? $this->buildShuffledOptions($questions);

            if (! session()->has("test_options_{$existingAttempt->id}")) {
                session(["test_options_{$existingAttempt->id}" => $shuffledOptions]);
            }

            return view('member.seminar.post-test', [
                'enrollment'      => $enrollment,
                'attempt'         => $existingAttempt,
                'questions'       => $questions,
                'shuffledOptions' => $shuffledOptions,
            ]);
        }

        // Attempt baru: soal diacak dari DB
        $questions = $enrollment->seminar->questions()->inRandomOrder()->get();

        $attempt = SeminarAttempt::create([
            'enrollment_id' => $enrollment->id,
            'type'          => 'post_test',
            'started_at'    => now(),
        ]);

        foreach ($questions as $q) {
            SeminarAttemptAnswer::create([
                'attempt_id'  => $attempt->id,
                'question_id' => $q->id,
            ]);
        }

        // Acak opsi dan simpan ke session
        $shuffledOptions = $this->buildShuffledOptions($questions);
        session(["test_options_{$attempt->id}" => $shuffledOptions]);

        return view('member.seminar.post-test', compact('enrollment', 'attempt', 'questions', 'shuffledOptions'));
    }

    public function submitPostTest(Request $request, SeminarAttempt $attempt)
    {
        $enrollment = $attempt->enrollment;
        $member     = $this->getMember();
        abort_if($enrollment->member_id !== $member->id, 403);
        abort_if($attempt->submitted_at !== null, 403, 'Attempt sudah disubmit.');

        $answers = $request->input('answers', []);

        DB::transaction(function () use ($attempt, $answers, $enrollment) {
            foreach ($attempt->answers as $answerRecord) {
                $chosen    = $answers[$answerRecord->question_id] ?? null;
                $isCorrect = $chosen !== null
                    ? $chosen === $answerRecord->question->correct_answer
                    : false;

                $answerRecord->update([
                    'answer'     => $chosen,
                    'is_correct' => $isCorrect,
                ]);
            }

            $attempt->calculateAndSaveScore();
            $attempt->refresh();

            if ($attempt->is_passed) {
                SeminarCertificate::create([
                    'enrollment_id'      => $enrollment->id,
                    'certificate_number' => SeminarCertificate::generateNumber(),
                    'score'              => $attempt->score,
                    'issued_at'          => now()->toDateString(),
                ]);
                $enrollment->update(['status' => 'completed']);
            } else {
                $enrollment->update(['status' => 'post_test_done']);
            }
        });

        // Hapus session opsi
        session()->forget("test_options_{$attempt->id}");

        $attempt->refresh();

        if ($attempt->is_passed) {
            return redirect()->route('member.seminar.show', $enrollment)
                ->with('success', 'Selamat! Kamu lulus dengan skor ' . $attempt->score . '. Sertifikat sudah tersedia.');
        }

        return redirect()->route('member.seminar.show', $enrollment)
            ->with('error', 'Skor kamu ' . $attempt->score . ', belum mencapai passing grade (' . $enrollment->seminar->passing_grade . '). Kamu bisa mengulang post-test.');
    }

    public function certificate(SeminarCertificate $certificate)
    {
        $member = $this->getMember();
        abort_if($certificate->enrollment->member_id !== $member->id, 403);

        $enrollment = $certificate->enrollment->load('seminar');
        $memberData = $member;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('member.seminar.certificate-pdf', compact('certificate', 'enrollment', 'memberData'));
        $pdf->setPaper([0, 0, 841.89, 595.28]);

        $filename = 'Sertifikat-' . str_replace('/', '-', $certificate->certificate_number) . '.pdf';
        return $pdf->stream($filename);
    }
}