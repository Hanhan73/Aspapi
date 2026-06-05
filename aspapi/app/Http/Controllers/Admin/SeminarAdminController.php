<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use App\Models\SeminarMaterial;
use App\Models\SeminarQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SeminarAdminController extends Controller
{
    // ── SEMINAR CRUD ───────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $seminars = Seminar::withCount(['questions', 'enrollments'])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('title', 'like', '%' . $request->search . '%')
                       ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest()
            ->paginate(15)
            ->withQueryString();
        return view('admin.seminar.index', compact('seminars'));
    }

    public function create()
    {
        return view('admin.seminar.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'                  => 'required|string|max:255',
            'category'               => 'nullable|string|max:100',
            'description'            => 'nullable|string',
            'thumbnail'              => 'nullable|image|max:2048',
            'passing_grade'          => 'required|integer|min:1|max:100',
            'is_active'              => 'boolean',
            'materials'              => 'required|array|min:1',
            'materials.*.label'      => 'required|string|max:255',
            'materials.*.url'        => 'required|url|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('seminars/thumbnails', 'public');
        }
        $data['is_active'] = $request->boolean('is_active', true);

        $seminar = Seminar::create(\Arr::except($data, ['materials']));

        foreach ($request->input('materials', []) as $i => $mat) {
            SeminarMaterial::create([
                'seminar_id' => $seminar->id,
                'label'      => $mat['label'],
                'url'        => $mat['url'],
                'sort_order' => $i,
            ]);
        }

        return redirect()->route('admin.seminar.index')
            ->with('success', 'Seminar berhasil dibuat.');
    }

    public function edit(Seminar $seminar)
    {
        $seminar->load('materials');
        return view('admin.seminar.edit', compact('seminar'));
    }

    public function update(Request $request, Seminar $seminar)
    {
        $data = $request->validate([
            'title'                  => 'required|string|max:255',
            'category'               => 'nullable|string|max:100',
            'description'            => 'nullable|string',
            'thumbnail'              => 'nullable|image|max:2048',
            'passing_grade'          => 'required|integer|min:1|max:100',
            'is_active'              => 'boolean',
            'materials'              => 'required|array|min:1',
            'materials.*.label'      => 'required|string|max:255',
            'materials.*.url'        => 'required|url|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($seminar->thumbnail) Storage::disk('public')->delete($seminar->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('seminars/thumbnails', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');

        $seminar->update(\Arr::except($data, ['materials']));

        // Sync materials: hapus semua lama, insert ulang sesuai urutan baru
        $seminar->materials()->delete();
        foreach ($request->input('materials', []) as $i => $mat) {
            SeminarMaterial::create([
                'seminar_id' => $seminar->id,
                'label'      => $mat['label'],
                'url'        => $mat['url'],
                'sort_order' => $i,
            ]);
        }

        return redirect()->route('admin.seminar.index')
            ->with('success', 'Seminar berhasil diperbarui.');
    }

    public function destroy(Seminar $seminar)
    {
        if ($seminar->thumbnail) Storage::disk('public')->delete($seminar->thumbnail);
        $seminar->delete();
        return back()->with('success', 'Seminar dihapus.');
    }

    // ── SOAL ──────────────────────────────────────────────────────────────────

    public function questions(Seminar $seminar)
    {
        $questions = $seminar->questions()->orderBy('sort_order')->paginate(20);
        return view('admin.seminar.questions', compact('seminar', 'questions'));
    }

    public function storeQuestion(Request $request, Seminar $seminar)
    {
        $data = $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:500',
            'option_b'       => 'required|string|max:500',
            'option_c'       => 'required|string|max:500',
            'option_d'       => 'required|string|max:500',
            'option_e'       => 'required|string|max:500',
            'correct_answer' => 'required|in:a,b,c,d,e',
        ]);

        $data['seminar_id'] = $seminar->id;
        $data['sort_order'] = $seminar->questions()->max('sort_order') + 1;
        SeminarQuestion::create($data);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, SeminarQuestion $question)
    {
        $data = $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:500',
            'option_b'       => 'required|string|max:500',
            'option_c'       => 'required|string|max:500',
            'option_d'       => 'required|string|max:500',
            'option_e'       => 'required|string|max:500',
            'correct_answer' => 'required|in:a,b,c,d,e',
        ]);

        $question->update($data);
        return back()->with('success', 'Soal #' . $question->id . ' berhasil diperbarui.');
    }

    public function destroyQuestion(SeminarQuestion $question)
    {
        $question->delete();
        return back()->with('success', 'Soal dihapus.');
    }

    // ── IMPORT SOAL DARI EXCEL ─────────────────────────────────────────────────

    public function downloadTemplate()
    {
        $path = public_path('templates\template-import-soal-aspapi.xlsx');
        return response()->download($path, 'template-import-soal-aspapi.xlsx');
    }

    public function importQuestions(Request $request, Seminar $seminar)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $file        = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet       = $spreadsheet->getSheetByName('Soal') ?? $spreadsheet->getActiveSheet();

        $errors   = [];
        $imported = 0;
        $lastSort = $seminar->questions()->max('sort_order') ?? 0;

        $highestRow = $sheet->getHighestDataRow();

        for ($row = 4; $row <= $highestRow; $row++) {
            $no       = trim((string) $sheet->getCell("A{$row}")->getValue());
            $question = trim((string) $sheet->getCell("B{$row}")->getValue());

            if ($question === '') continue;

            $optA    = trim((string) $sheet->getCell("C{$row}")->getValue());
            $optB    = trim((string) $sheet->getCell("D{$row}")->getValue());
            $optC    = trim((string) $sheet->getCell("E{$row}")->getValue());
            $optD    = trim((string) $sheet->getCell("F{$row}")->getValue());
            $optE    = trim((string) $sheet->getCell("G{$row}")->getValue());
            $correct = strtolower(trim((string) $sheet->getCell("H{$row}")->getValue()));

            $rowErrors = [];
            if ($optA === '') $rowErrors[] = 'Opsi A kosong';
            if ($optB === '') $rowErrors[] = 'Opsi B kosong';
            if ($optC === '') $rowErrors[] = 'Opsi C kosong';
            if ($optD === '') $rowErrors[] = 'Opsi D kosong';
            if (! in_array($correct, ['a','b','c','d','e'])) $rowErrors[] = "Jawaban benar tidak valid ({$correct})";
            if ($optE === '') $rowErrors[] = 'Opsi E kosong (wajib diisi)';

            if ($rowErrors) {
                $errors[] = "Baris {$row}: " . implode(', ', $rowErrors);
                continue;
            }

            SeminarQuestion::create([
                'seminar_id'     => $seminar->id,
                'question'       => $question,
                'option_a'       => $optA,
                'option_b'       => $optB,
                'option_c'       => $optC,
                'option_d'       => $optD,
                'option_e'       => $optE ?: null,
                'correct_answer' => $correct,
                'sort_order'     => ++$lastSort,
            ]);
            $imported++;
        }

        if ($errors) {
            $msg = "Import selesai: {$imported} soal berhasil ditambahkan. "
                . count($errors) . " baris dilewati: " . implode(' | ', array_slice($errors, 0, 5));
            return back()->with('warning', $msg);
        }

        return back()->with('success', "{$imported} soal berhasil diimport dari Excel.");
    }

    // ── LAPORAN ENROLLMENTS ────────────────────────────────────────────────────

    public function enrollments(Seminar $seminar)
    {
        $enrollments = $seminar->enrollments()
            ->with(['member', 'certificate'])
            ->latest()->paginate(20);
        return view('admin.seminar.enrollments', compact('seminar', 'enrollments'));
    }
}