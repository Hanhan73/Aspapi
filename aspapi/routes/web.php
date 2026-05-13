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

use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\BiodataController;
use App\Http\Controllers\Member\PaymentController;
use App\Http\Controllers\Member\CardController;
use App\Http\Controllers\Bendahara\BendaharaController;
use App\Http\Controllers\Daerah\RegionMemberController;

use App\Http\Controllers\Admin\RegionController as AdminRegionController;

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
    Route::get('/pembayaran',      [PaymentController::class, 'index'])->name('payment');
    Route::post('/pembayaran',     [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/kartu',           [CardController::class, 'show'])->name('card');
    Route::get('/kartu/generate',  [CardController::class, 'generate'])->name('card.generate');
    Route::get('/kartu/download',  [CardController::class, 'download'])->name('card.download');
});

// ── BENDAHARA PORTAL ──
Route::prefix('bendahara')->name('bendahara.')->middleware(['auth', 'role:bendahara,admin'])->group(function () {
    Route::get('/',                          [BendaharaController::class, 'index'])->name('dashboard');
    Route::get('/pembayaran',                [BendaharaController::class, 'payments'])->name('payments');
    Route::post('/pembayaran/{id}/verify',   [BendaharaController::class, 'verify'])->name('payment.verify');
    Route::post('/pembayaran/{id}/reject',   [BendaharaController::class, 'reject'])->name('payment.reject');
    Route::get('/batch',                     [BendaharaController::class, 'batches'])->name('batches');
    Route::post('/batch/{id}/verify',        [BendaharaController::class, 'verifyBatch'])->name('batch.verify');
});

// ── ASPAPI DAERAH PORTAL ──
Route::prefix('daerah')->name('daerah.')->middleware(['auth', 'role:aspapi_daerah,admin'])->group(function () {
    Route::get('/',              [RegionMemberController::class, 'index'])->name('dashboard');
    Route::get('/anggota',       [RegionMemberController::class, 'members'])->name('members');
    Route::get('/daftar-batch',  [RegionMemberController::class, 'batchForm'])->name('batch.form');
    Route::post('/daftar-batch', [RegionMemberController::class, 'batchStore'])->name('batch.store');
    Route::get('/bayar-batch',   [RegionMemberController::class, 'payBatchForm'])->name('pay.form');
    Route::post('/bayar-batch',  [RegionMemberController::class, 'payBatchStore'])->name('pay.store');
});

// ── AUTH ADMIN ──
Route::get('/admin/login',   [AuthController::class, 'showLogin'])->name('admin.login')->middleware('guest');
Route::post('/admin/login',  [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

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

// ── DOKUMEN (PUBLIC) ──
Route::prefix('download')->name('documents.')->group(function () {
    Route::get('/',            [DocumentController::class, 'index'])->name('index');
    Route::get('/{id}/unduh',  [DocumentController::class, 'download'])->name('download');
});

Route::get('/mitra', [App\Http\Controllers\PartnerController::class, 'index'])->name('partners.index');


/* ─────────────────────────────────────────
   ADMIN ROUTES
───────────────────────────────────────── */

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

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

        // Anggota
        Route::resource('anggota', MemberAdminController::class)
            ->names('members')
            ->parameters(['anggota' => 'member']);

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

        Route::resource('mitra', App\Http\Controllers\Admin\PartnerController::class)
     ->names('admin.partners')
     ->parameters(['mitra' => 'partner']);
     Route::post('mitra/reorder', [App\Http\Controllers\Admin\PartnerController::class, 'reorder'])
     ->name('admin.partners.reorder');

        // Verifikasi anggota
        Route::get('/verifikasi-anggota', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'index'])
            ->name('member.verify.index');
        Route::post('/verifikasi-anggota/{id}/approve', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'approve'])
            ->name('member.verify.approve');
        Route::post('/verifikasi-anggota/{id}/reject', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'reject'])
            ->name('member.verify.reject');
        Route::post('/verifikasi-anggota/{id}/approve-old', [\App\Http\Controllers\Admin\MemberVerificationController::class, 'approveOldMember'])
            ->name('member.verify.approve-old');
    });

