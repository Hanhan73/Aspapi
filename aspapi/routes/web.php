<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\MemberController;
use App\Http\Controllers\Public\RegionController;
use App\Http\Controllers\Public\LspController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\DocumentController;
use App\Http\Controllers\Public\PartnerController;
use App\Http\Controllers\Public\AgendaPublicController;
use App\Http\Controllers\Public\SeminarPublicController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BoardController;
use App\Http\Controllers\Admin\AdvisorController;
use App\Http\Controllers\Admin\ExpertController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\Admin\BlogAdminController;
use App\Http\Controllers\Admin\DocumentAdminController;
use App\Http\Controllers\Admin\MemberAdminController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;
use App\Http\Controllers\Admin\SeminarAdminController;
use App\Http\Controllers\Admin\SuperAdminController;

use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\BiodataController;
use App\Http\Controllers\Member\PaymentController;
use App\Http\Controllers\Member\CardController;
use App\Http\Controllers\Member\SeminarController;
use App\Http\Controllers\Member\SeminarCertificateController;

use App\Http\Controllers\Bendahara\BendaharaController;
use App\Http\Controllers\Bendahara\RekapController;

use App\Http\Controllers\Daerah\RegionMemberController;

use App\Http\Controllers\AccountController;

use App\Http\Controllers\Admin\ImpersonateController;



/* ─────────────────────────────────────────
   PUBLIC ROUTES
───────────────────────────────────────── */

// ── AUTH ANGGOTA ──
Route::get('/daftar',          [RegisterController::class, 'showForm'])->name('register');
Route::post('/daftar',         [RegisterController::class, 'store'])->name('register.store');
Route::get('/daftar/lama',     [RegisterController::class, 'showOldForm'])->name('register.old');
Route::post('/daftar/lama',    [RegisterController::class, 'storeOld'])->name('register.old.store');
Route::get('/verifikasi-email/{token}', [RegisterController::class, 'verifyEmail'])->name('verify.email');
Route::get('/login',           [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
Route::post('/login',          [App\Http\Controllers\Admin\AuthController::class, 'loginMember'])->name('login.member');

Route::get('/api/cities/{provinceId}', function ($provinceId) {
    return response()->json(
        \App\Models\City::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name'])
    );
});

// ── MEMBER PORTAL ──
Route::prefix('member')->name('member.')->middleware(['auth', 'role:anggota'])->group(function () {
    Route::get('/',                [MemberDashboard::class, 'index'])->name('dashboard');
    Route::get('/biodata',         [BiodataController::class, 'edit'])->name('biodata');
    Route::post('/biodata',        [BiodataController::class, 'update'])->name('biodata.update');
    Route::post('/biodata/unlock', [BiodataController::class, 'unlock'])->name('biodata.unlock');
    Route::get('/pembayaran',      [PaymentController::class, 'index'])->name('payment');
    Route::post('/pembayaran',     [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/kartu',           [CardController::class, 'show'])->name('card');
    Route::post('/kartu/generate',  [CardController::class, 'generate'])->name('card.generate');
    Route::get('/kartu/download',  [CardController::class, 'download'])->name('card.download');
    Route::post('/kartu/preference', [CardController::class, 'updatePreference'])->name('card.preference');

    Route::prefix('seminar')->name('seminar.')->group(function () {
        // Daftar seminar tersedia
        Route::get('/',                                   [SeminarController::class, 'index'])->name('index');

        // Seminar saya
        Route::get('/saya',                               [SeminarController::class, 'mySeminars'])->name('my-seminars');

        Route::get('/seminar/certificate/{certificate}', [SeminarCertificateController::class, 'download'])
            ->name('certificate');

        // Daftar ke seminar
        Route::post('/{seminar}/daftar',                  [SeminarController::class, 'enroll'])->name('enroll');

        // Detail seminar (progress page)
        Route::get('/enrollment/{enrollment}',            [SeminarController::class, 'show'])->name('show');

        // Pre-test
        Route::get('/enrollment/{enrollment}/pre-test',   [SeminarController::class, 'startPreTest'])->name('pretest.start');
        Route::post('/attempt/{attempt}/pre-test/submit', [SeminarController::class, 'submitPreTest'])->name('pretest.submit');

        // Tandai materi selesai
        Route::post('/enrollment/{enrollment}/materi-selesai', [SeminarController::class, 'markMaterialRead'])->name('material.done');

        // Post-test
        Route::get('/enrollment/{enrollment}/post-test',  [SeminarController::class, 'startPostTest'])->name('posttest.start');
        Route::post('/attempt/{attempt}/post-test/submit', [SeminarController::class, 'submitPostTest'])->name('posttest.submit');
    });
});

// ── BENDAHARA PORTAL ──
Route::prefix('bendahara')->name('bendahara.')->middleware(['auth', 'role:bendahara,admin'])->group(function () {
    Route::get('/',                          [BendaharaController::class, 'index'])->name('dashboard');
    Route::get('/pembayaran',                [BendaharaController::class, 'payments'])->name('payments');
    Route::post('/pembayaran/{id}/verify',   [BendaharaController::class, 'verify'])->name('payment.verify');
    Route::post('/pembayaran/{id}/reject',   [BendaharaController::class, 'reject'])->name('payment.reject');
    Route::get('/batch',                     [BendaharaController::class, 'batches'])->name('batches');
    Route::post('/batch/{id}/verify',        [BendaharaController::class, 'verifyBatch'])->name('batch.verify');
    Route::get('/batch/{id}',        [BendaharaController::class, 'showBatch'])->name('batch.show');
    Route::post('/batch/{id}/reject', [BendaharaController::class, 'rejectBatch'])->name('batch.reject');
    // Dalam group bendahara
    Route::get('/rekap', [RekapController::class, 'rekap'])->name('rekap');
    Route::get('/iuran', [RekapController::class, 'iuran'])->name('iuran');
    Route::get('/rekap/export',  [RekapController::class, 'rekapExport'])->name('rekap.export');
    Route::get('/iuran/export',  [RekapController::class, 'iuranExport'])->name('iuran.export');
});

// ── ASPAPI DAERAH PORTAL ──
Route::prefix('daerah')->name('daerah.')->middleware(['auth', 'role:aspapi_daerah,admin'])->group(function () {
    Route::get('/',              [RegionMemberController::class, 'index'])->name('dashboard');
    Route::get('/anggota',       [RegionMemberController::class, 'members'])->name('members');
    Route::get('/daftar-batch',  [RegionMemberController::class, 'batchForm'])->name('batch.form');
    Route::post('/daftar-batch', [RegionMemberController::class, 'batchStore'])->name('batch.store');
    Route::get('/bayar-batch',   [RegionMemberController::class, 'payBatchForm'])->name('pay.form');
    Route::post('/bayar-batch',  [RegionMemberController::class, 'payBatchStore'])->name('pay.store');
    Route::get('/riwayat-batch',        [RegionMemberController::class, 'payBatchHistory'])->name('pay.batches');
    Route::get('/riwayat-batch/{id}',   [RegionMemberController::class, 'payBatchShow'])->name('pay.batch.show');
    Route::get('/daftar-batch/template', [RegionMemberController::class, 'downloadTemplate'])
        ->name('batch.template');
    Route::post('/check-duplicates', [RegionMemberController::class, 'checkDuplicates'])
        ->name('batch.check-duplicates');
    Route::get('/anggota/export', [RegionMemberController::class, 'exportMembers'])->name('members.export');
    Route::get('/verifikasi',             [RegionMemberController::class, 'verifyIndex'])->name('verify.index');
    Route::post('/verifikasi/{id}/approve',     [RegionMemberController::class, 'verifyApprove'])->name('verify.approve');
    Route::post('/verifikasi/{id}/reject',      [RegionMemberController::class, 'verifyReject'])->name('verify.reject');
    Route::post('/verifikasi/{id}/approve-old', [RegionMemberController::class, 'verifyApproveOld'])->name('verify.approve-old');

    Route::prefix('agenda')->name('agenda.')->group(function () {
        Route::get('/',            [\App\Http\Controllers\Daerah\AgendaController::class, 'index'])->name('index');
        Route::get('/tambah',      [\App\Http\Controllers\Daerah\AgendaController::class, 'create'])->name('create');
        Route::post('/',           [\App\Http\Controllers\Daerah\AgendaController::class, 'store'])->name('store');
        Route::get('/{agenda}/edit', [\App\Http\Controllers\Daerah\AgendaController::class, 'edit'])->name('edit');
        Route::put('/{agenda}',    [\App\Http\Controllers\Daerah\AgendaController::class, 'update'])->name('update');
        Route::delete('/{agenda}', [\App\Http\Controllers\Daerah\AgendaController::class, 'destroy'])->name('destroy');
    });
});

// ── AUTH ADMIN ──
Route::get('/admin/login',   [AuthController::class, 'showLogin'])->name('admin.login')->middleware('guest');
Route::post('/admin/login',  [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/sitemap.xml', [\App\Http\Controllers\Public\SitemapController::class, 'index'])
    ->name('sitemap');

// ── HOME ──
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── PROFIL ──
Route::prefix('profil')->name('profile.')->group(function () {
    Route::get('/visi-misi',       [ProfileController::class, 'visionMission'])->name('vision-mission');
    Route::get('/sejarah',         [ProfileController::class, 'history'])->name('history');
    Route::get('/inisiator',       [ProfileController::class, 'initiators'])->name('initiators');
    Route::get('/kongres',         [ProfileController::class, 'congress'])->name('congress');
    Route::get('/dewan-penasihat', [ProfileController::class, 'advisors'])->name('advisors');
    Route::get('/dewan-pakar',     [ProfileController::class, 'experts'])->name('experts');
    Route::get('/pengurus',        [ProfileController::class, 'board'])->name('board');
});

// ── ANGGOTA ──
Route::prefix('anggota')->name('members.')->group(function () {
    Route::get('/jenis-syarat', [MemberController::class, 'types'])->name('types');
    Route::get('/daftar',       [MemberController::class, 'registerForm'])->name('register');
    Route::post('/daftar',      [MemberController::class, 'registerStore'])->name('register.store');
    Route::get('/direktori',    [MemberController::class, 'directory'])->name('directory');
});

// ── ASPAPI DAERAH (PUBLIC) ──
Route::prefix('aspapi-daerah')->name('regions.')->group(function () {
    Route::get('/',       [RegionController::class, 'index'])->name('index');
    Route::get('/{slug}', [RegionController::class, 'show'])->name('show');
});

// ── LSP ──
Route::prefix('lsp')->name('lsp.')->group(function () {
    Route::get('/', [LspController::class, 'index'])->name('index');
});

// ── BERITA (PUBLIC) ──
Route::prefix('berita')->name('news.')->group(function () {
    Route::get('/',       [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
});

// ── BLOG (PUBLIC) ──
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/',       [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

Route::get('/seminar-pelatihan', [SeminarPublicController::class, 'index'])->name('public.seminars');
Route::get('/agenda',            [AgendaPublicController::class,  'index'])->name('public.agenda');

// ── DOKUMEN (PUBLIC) ──
Route::prefix('download')->name('documents.')->group(function () {
    Route::get('/',                [DocumentController::class, 'index'])->name('index');
    Route::get('/{document}/unduh', [DocumentController::class, 'download'])->name('download');
});

Route::get('/mitra', [PartnerController::class, 'index'])->name('partners.index');

Route::prefix('account')->name('account.')->middleware('auth')->group(function () {
    Route::get('/settings',  [AccountController::class, 'show'])->name('settings');
    Route::post('/password', [AccountController::class, 'updatePassword'])->name('password');
    Route::post('/account/email', [AccountController::class, 'updateEmail'])->name('email');
});


/* ─────────────────────────────────────────
ADMIN ROUTES
───────────────────────────────────────── */

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::middleware('role:superadmin')->prefix('akun')->name('superadmin.')->group(function () {
            Route::get('/',                                      [SuperAdminController::class, 'index'])->name('users');
            Route::get('/tambah',                                [SuperAdminController::class, 'create'])->name('create');
            Route::post('/',                                     [SuperAdminController::class, 'store'])->name('store');
            Route::get('/{user}/edit',                           [SuperAdminController::class, 'edit'])->name('edit');
            Route::put('/{user}',                                [SuperAdminController::class, 'update'])->name('update');
            Route::post('/{user}/reset-password',                [SuperAdminController::class, 'resetPassword'])->name('reset-password');
            Route::delete('/{user}',                             [SuperAdminController::class, 'destroy'])->name('destroy');
        });
        
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Pengurus
        Route::resource('pengurus', BoardController::class)
            ->names('boards')
            ->parameters(['pengurus' => 'board']);

        // Dewan Penasihat
        Route::resource('dewan-penasihat', AdvisorController::class)
            ->names('advisors')
            ->parameters(['dewan-penasihat' => 'advisor']);

        // Dewan Pakar
        Route::resource('dewan-pakar', ExpertController::class)
            ->names('experts')
            ->parameters(['dewan-pakar' => 'expert']);

        // Berita — FIX: parameter 'news' supaya tidak jadi {beritum}
        Route::resource('berita', NewsAdminController::class)
            ->names('news')
            ->parameters(['berita' => 'news']);

        // Blog — FIX: parameter 'blog' supaya tidak di-pluralize aneh
        Route::resource('blog', BlogAdminController::class)
            ->names('blogs')
            ->parameters(['blog' => 'blog']);

        Route::get('dokumen/sort',              [DocumentAdminController::class, 'sortIndex'])->name('documents.sort');
        Route::post('dokumen/sort/documents',   [DocumentAdminController::class, 'sortDocuments'])->name('documents.sort.documents');
        Route::post('dokumen/sort/categories',  [DocumentAdminController::class, 'sortCategories'])->name('documents.sort.categories');

        // Dokumen
        Route::resource('dokumen', DocumentAdminController::class)
            ->names('documents')
            ->parameters(['dokumen' => 'document']);

        Route::get('anggota/export', [MemberAdminController::class, 'export'])->name('members.export');

        // Anggota
        Route::resource('anggota', MemberAdminController::class)
            ->names('members')
            ->parameters(['anggota' => 'member']);

        Route::post('/anggota/{member}/assign-region', [MemberAdminController::class, 'assignRegion'])
            ->name('members.assign-region');

        // ASPAPI Daerah
        Route::resource('daerah', AdminRegionController::class)
            ->names('regions')
            ->parameters(['daerah' => 'region']);
        Route::get('daerah/{region}/account', [AdminRegionController::class, 'manageAccount'])
            ->name('regions.account');
        Route::post('daerah/{region}/account', [AdminRegionController::class, 'storeAccount'])
            ->name('regions.account.store');
        Route::post('daerah/{region}/account/reset-password', [AdminRegionController::class, 'resetPassword'])
            ->name('regions.account.reset-password');

        Route::resource('mitra', AdminPartnerController::class)
            ->names('partners')
            ->parameters(['mitra' => 'partner']);
        Route::post('mitra/reorder', [AdminPartnerController::class, 'reorder'])
            ->name('partners.reorder');

        // Verifikasi anggota
        Route::get('/verifikasi-anggota', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'index'])
            ->name('member.verify.index');
        Route::post('/verifikasi-anggota/{id}/approve', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'approve'])
            ->name('member.verify.approve');
        Route::post('/verifikasi-anggota/{id}/reject', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'reject'])
            ->name('member.verify.reject');
        Route::post('/verifikasi-anggota/{id}/approve-old', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'approveOldMember'])
            ->name('member.verify.approve-old');

        Route::post('/anggota/{member}/reset-password', [ResetPasswordController::class, 'reset'])
            ->name('member.reset-password');

        Route::post('/anggota/{member}/set-password', [ResetPasswordController::class, 'setPassword'])
            ->name('member.set-password');

        Route::prefix('seminar')->name('seminar.')->group(function () {
            Route::get('/',                                    [SeminarAdminController::class, 'index'])->name('index');
            Route::get('/create',                              [SeminarAdminController::class, 'create'])->name('create');
            Route::post('/',                                   [SeminarAdminController::class, 'store'])->name('store');
            Route::get('/{seminar}/edit',                      [SeminarAdminController::class, 'edit'])->name('edit');
            Route::put('/{seminar}',                           [SeminarAdminController::class, 'update'])->name('update');
            Route::delete('/{seminar}',                        [SeminarAdminController::class, 'destroy'])->name('destroy');

            // Manajemen soal
            Route::get('/{seminar}/soal',                      [SeminarAdminController::class, 'questions'])->name('questions');
            Route::post('/{seminar}/soal',                     [SeminarAdminController::class, 'storeQuestion'])->name('questions.store');
            Route::put('/soal/{question}',                     [SeminarAdminController::class, 'updateQuestion'])->name('questions.update');
            Route::delete('/soal/{question}',                  [SeminarAdminController::class, 'destroyQuestion'])->name('questions.destroy');

            // Import soal dari Excel
            Route::get('/soal/template',                 [SeminarAdminController::class, 'downloadTemplate'])->name('template');
            Route::post('/{seminar}/soal/import',        [SeminarAdminController::class, 'importQuestions'])->name('import');

            // Laporan peserta
            Route::get('/{seminar}/peserta',                   [SeminarAdminController::class, 'enrollments'])->name('enrollments');
        });

        Route::prefix('agenda')->name('agenda.')->group(function () {
            Route::get('/',                  [\App\Http\Controllers\Admin\AgendaAdminController::class, 'index'])->name('index');
            Route::get('/tambah',            [\App\Http\Controllers\Admin\AgendaAdminController::class, 'create'])->name('create');
            Route::post('/',                 [\App\Http\Controllers\Admin\AgendaAdminController::class, 'store'])->name('store');
            Route::get('/{agenda}/edit',     [\App\Http\Controllers\Admin\AgendaAdminController::class, 'edit'])->name('edit');
            Route::put('/{agenda}',          [\App\Http\Controllers\Admin\AgendaAdminController::class, 'update'])->name('update');
            Route::post('/{agenda}/approve', [\App\Http\Controllers\Admin\AgendaAdminController::class, 'approve'])->name('approve');
            Route::post('/{agenda}/reject',  [\App\Http\Controllers\Admin\AgendaAdminController::class, 'reject'])->name('reject');
            Route::delete('/{agenda}',       [\App\Http\Controllers\Admin\AgendaAdminController::class, 'destroy'])->name('destroy');
        });
    });


Route::middleware('auth')->group(function () {
    Route::post('/impersonate/{userId}', [ImpersonateController::class, 'impersonate'])
        ->name('impersonate');
    Route::post('/impersonate-leave', [ImpersonateController::class, 'leave'])
        ->name('impersonate.leave');
});
