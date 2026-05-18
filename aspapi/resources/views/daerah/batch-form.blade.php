@extends('layouts.daerah')
@php $title = 'Daftar Anggota Baru (Batch)'; @endphp

@section('content')

{{-- ── Flash Messages ─────────────────────────────────────────────── --}}
@if (session('success'))
<div style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;font-size:0.875rem;color:#276749;display:flex;align-items:flex-start;gap:0.5rem;">
    <span>✓</span> <span>{{ session('success') }}</span>
</div>
@endif

@if (session('batch_errors'))
<div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;">
    <p style="font-size:0.8rem;font-weight:700;color:#922B21;margin:0 0 0.5rem;">⚠ Baris yang gagal didaftarkan:</p>
    <ul style="margin:0;padding-left:1.25rem;">
        @foreach (session('batch_errors') as $err)
        <li style="font-size:0.78rem;color:#922B21;margin-bottom:0.2rem;">{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

@if ($errors->any())
<div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.25rem;">
    <p style="font-size:0.8rem;font-weight:700;color:#922B21;margin:0 0 0.5rem;">⚠ Pendaftaran gagal:</p>
    <ul style="margin:0;padding-left:1.25rem;">
        @foreach ($errors->all() as $error)
        <li style="font-size:0.78rem;color:#922B21;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ── Info banner ─────────────────────────────────────────────────── --}}
<div style="background:#EEF4FB;border-left:4px solid #2A7FC1;border-radius:4px;padding:0.875rem 1.25rem;margin-bottom:1.5rem;font-size:0.85rem;color:#1A3A5C;">
    <strong>Pendaftaran Batch</strong> — Daftarkan beberapa anggota sekaligus via upload Excel atau input manual.
    Setiap anggota akan langsung mendapat email berisi akun login.
</div>

<form method="POST" action="{{ route('daerah.batch.store') }}" enctype="multipart/form-data" id="batch-form">
@csrf

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

    {{-- ═══ KOLOM KIRI — Daftar Peserta ═══ --}}
    <div>

        {{-- Header peserta --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;margin-bottom:1.25rem;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #EEF4FB;display:flex;align-items:center;justify-content:space-between;">
                <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#2A7FC1;margin:0;">
                    Daftar Peserta
                </p>
                <div style="display:flex;gap:0.5rem;">
                    {{-- Upload Excel --}}
                    <button type="button" onclick="document.getElementById('modal-upload').style.display='flex'"
                            style="padding:0.375rem 0.75rem;background:#276749;color:#fff;border:none;border-radius:4px;font-size:0.72rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:0.3rem;">
                        ↑ Upload Excel
                    </button>
                    {{-- Tambah manual --}}
                    <button type="button" id="btn-add-row"
                            style="padding:0.375rem 0.75rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.72rem;font-weight:700;cursor:pointer;">
                        + Tambah Manual
                    </button>
                </div>
            </div>

            {{-- Import success notice --}}
            <div id="import-notice" style="display:none;background:#F0FFF4;border-bottom:1px solid #c3e6cb;padding:0.625rem 1.25rem;font-size:0.8rem;color:#276749;">
                ✓ <span id="import-notice-text"></span>
                <button type="button" onclick="document.getElementById('import-notice').style.display='none'"
                        style="float:right;background:none;border:none;color:#276749;cursor:pointer;font-weight:700;">✕</button>
            </div>

            {{-- Tabel peserta --}}
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;" id="participants-table">
                    <thead>
                        <tr style="background:#EEF4FB;">
                            <th style="padding:0.6rem 0.75rem;text-align:center;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;width:40px;">#</th>
                            <th style="padding:0.6rem 0.75rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Nama Lengkap *</th>
                            <th style="padding:0.6rem 0.75rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Email *</th>
                            <th style="padding:0.6rem 0.75rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;width:100px;">No. Telepon</th>
                            <th style="padding:0.6rem 0.75rem;text-align:left;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;width:70px;">Gender</th>
                            <th style="padding:0.6rem 0.75rem;text-align:center;font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;width:50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="participants-body">
                        {{-- Diisi JS --}}
                    </tbody>
                </table>
            </div>

            {{-- Footer tabel --}}
            <div style="padding:0.75rem 1.25rem;border-top:1px solid #EEF4FB;background:#F8FAFC;display:flex;justify-content:space-between;align-items:center;">
                <p style="font-size:0.78rem;color:#4A6580;margin:0;">
                    Total: <strong id="total-count">0</strong> peserta
                </p>
                <button type="button" id="btn-clear-all"
                        style="font-size:0.7rem;color:#C0392B;background:none;border:none;cursor:pointer;font-weight:600;display:none;">
                    Hapus Semua
                </button>
            </div>
        </div>

        {{-- Format kolom (collapsible) --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;overflow:hidden;">
            <button type="button" onclick="toggleFormatInfo()"
                    style="width:100%;padding:0.75rem 1.25rem;background:none;border:none;text-align:left;cursor:pointer;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;">
                    📋 Format Kolom Excel
                </span>
                <span id="format-chevron" style="color:#4A6580;font-size:0.75rem;">▼</span>
            </button>
            <div id="format-info" style="display:none;padding:0 1.25rem 1.25rem;">
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:0.75rem;">
                        <thead>
                            <tr style="background:#E2EDF7;">
                                <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;border:1px solid #D6E8F7;">A — Nama Lengkap *</th>
                                <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;border:1px solid #D6E8F7;">B — Email *</th>
                                <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;border:1px solid #D6E8F7;">C — No. Telepon</th>
                                <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;border:1px solid #D6E8F7;">D — Institusi</th>
                                <th style="padding:0.4rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;white-space:nowrap;border:1px solid #D6E8F7;">E — Gender (L/P)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-top:1px solid #D6E8F7;">
                                <td style="padding:0.4rem 0.75rem;color:#4A6580;border:1px solid #EEF4FB;">Budi Santoso</td>
                                <td style="padding:0.4rem 0.75rem;color:#4A6580;border:1px solid #EEF4FB;">budi@email.com</td>
                                <td style="padding:0.4rem 0.75rem;color:#4A6580;border:1px solid #EEF4FB;">08123456789</td>
                                <td style="padding:0.4rem 0.75rem;color:#4A6580;border:1px solid #EEF4FB;">Universitas XYZ</td>
                                <td style="padding:0.4rem 0.75rem;color:#4A6580;border:1px solid #EEF4FB;">L</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p style="font-size:0.72rem;color:#8A97A4;margin:0.5rem 0 0;">* Wajib diisi. Baris pertama (header) otomatis dilewati saat upload.</p>
                <a href="{{ route('daerah.batch.template') }}"
                   style="display:inline-block;margin-top:0.5rem;font-size:0.72rem;font-weight:700;color:#276749;text-decoration:none;">
                    ⬇ Download Template Excel
                </a>
            </div>
        </div>

    </div>

    {{-- ═══ KOLOM KANAN — Panel Aksi ═══ --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        {{-- Ringkasan --}}
        <div style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:1.25rem;">
            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#8A97A4;margin:0 0 1rem;">Ringkasan</p>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;color:#4A6580;">
                    <span>Total peserta</span>
                    <strong id="summary-count" style="color:#1A2A3A;">0</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;color:#4A6580;">
                    <span>Email valid</span>
                    <strong id="summary-valid" style="color:#276749;">0</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.82rem;color:#4A6580;">
                    <span>Email tidak valid</span>
                    <strong id="summary-invalid" style="color:#C0392B;">0</strong>
                </div>
            </div>
        </div>

        {{-- Validasi status --}}
        <div id="validation-panel" style="display:none;background:#FDECEA;border:1px solid #f5c6cb;border-radius:8px;padding:1rem;">
            <p style="font-size:0.75rem;font-weight:700;color:#922B21;margin:0 0 0.5rem;">⚠ Email tidak valid:</p>
            <ul id="validation-list" style="margin:0;padding-left:1.25rem;font-size:0.72rem;color:#922B21;"></ul>
        </div>

        {{-- Cek duplikat warning --}}
        <div id="duplicate-panel" style="display:none;background:#FEF8EC;border:1px solid #E8B84B;border-radius:8px;padding:1rem;">
            <p style="font-size:0.75rem;font-weight:700;color:#8B6914;margin:0 0 0.25rem;">⚠ <span id="dup-count">0</span> Email sudah terdaftar</p>
            <p style="font-size:0.72rem;color:#8B6914;margin:0;">Peserta dengan email duplikat akan dilewati saat pendaftaran.</p>
        </div>

        {{-- Submit --}}
        <button type="submit" id="btn-submit"
                style="width:100%;padding:0.875rem;background:#2A7FC1;color:#fff;border:none;border-radius:6px;font-size:0.8rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;transition:opacity 0.2s;">
            Daftarkan Anggota
        </button>

        <a href="{{ route('daerah.dashboard') }}"
           style="display:block;text-align:center;padding:0.625rem;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:6px;font-size:0.75rem;font-weight:700;text-decoration:none;">
            ← Kembali
        </a>

    </div>

</div>
</form>

{{-- ══════════════════════════════════════════════════════════════════
     MODAL: Upload Excel
══════════════════════════════════════════════════════════════════ --}}
<div id="modal-upload" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;width:700px;max-width:95vw;max-height:90vh;overflow:hidden;display:flex;flex-direction:column;">

        {{-- Modal header --}}
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #EEF4FB;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;">
            <div>
                <p style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1A2A3A;margin:0;">Upload Data Peserta</p>
                <p style="font-size:0.72rem;color:#8A97A4;margin:0.2rem 0 0;">Excel (.xlsx/.xls) atau CSV</p>
            </div>
            <button type="button" onclick="closeUploadModal()"
                    style="background:none;border:none;font-size:1.25rem;color:#8A97A4;cursor:pointer;">✕</button>
        </div>

        {{-- Modal body --}}
        <div style="padding:1.5rem;overflow-y:auto;flex:1;">

            {{-- Step 1: Download template --}}
            <div style="background:#F8FAFC;border:1px solid #D6E8F7;border-radius:6px;padding:1rem;margin-bottom:1rem;">
                <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin:0 0 0.5rem;">
                    Step 1 — Download Template
                </p>
                <a href="{{ route('daerah.batch.template') }}"
                   style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.5rem 1rem;background:#276749;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;text-decoration:none;">
                    ⬇ Download Template Excel
                </a>
            </div>

            {{-- Step 2: Upload file --}}
            <div style="background:#F8FAFC;border:1px solid #D6E8F7;border-radius:6px;padding:1rem;margin-bottom:1rem;">
                <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin:0 0 0.75rem;">
                    Step 2 — Upload File
                </p>
                <input type="file" id="excel-file-input" accept=".xlsx,.xls,.csv"
                       style="width:100%;padding:0.5rem;border:1.5px dashed #D6E8F7;border-radius:4px;font-size:0.82rem;color:#4A6580;cursor:pointer;"/>
                <p style="font-size:0.7rem;color:#8A97A4;margin:0.4rem 0 0;">Maks 5MB. Format .xlsx, .xls, atau .csv</p>
            </div>

            {{-- Preview area --}}
            <div id="preview-area" style="display:none;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                    <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin:0;">
                        Preview Data
                    </p>
                    <p style="font-size:0.72rem;color:#4A6580;margin:0;">
                        <span id="preview-valid-count" style="color:#276749;font-weight:700;">0</span> valid,
                        <span id="preview-invalid-count" style="color:#C0392B;font-weight:700;">0</span> tidak valid
                    </p>
                </div>
                <div style="border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;max-height:280px;overflow-y:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:0.78rem;">
                        <thead style="position:sticky;top:0;">
                            <tr style="background:#EEF4FB;">
                                <th style="padding:0.5rem 0.75rem;text-align:center;color:#2A7FC1;font-weight:700;width:35px;">#</th>
                                <th style="padding:0.5rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;">Nama</th>
                                <th style="padding:0.5rem 0.75rem;text-align:left;color:#2A7FC1;font-weight:700;">Email</th>
                                <th style="padding:0.5rem 0.75rem;text-align:center;color:#2A7FC1;font-weight:700;width:80px;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="preview-tbody"></tbody>
                    </table>
                </div>

                {{-- Validasi errors --}}
                <div id="preview-errors" style="display:none;margin-top:0.75rem;background:#FDECEA;border-radius:6px;padding:0.75rem;">
                    <p style="font-size:0.75rem;font-weight:700;color:#922B21;margin:0 0 0.25rem;">Baris tidak valid:</p>
                    <ul id="preview-error-list" style="margin:0;padding-left:1.25rem;font-size:0.72rem;color:#922B21;"></ul>
                </div>
            </div>

        </div>

        {{-- Modal footer --}}
        <div style="padding:1rem 1.5rem;border-top:1px solid #EEF4FB;display:flex;gap:0.75rem;justify-content:flex-end;flex-shrink:0;">
            <button type="button" onclick="closeUploadModal()"
                    style="padding:0.625rem 1.25rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;color:#4A6580;background:transparent;cursor:pointer;">
                Batal
            </button>
            <button type="button" id="btn-import" disabled onclick="importFromFile()"
                    style="padding:0.625rem 1.25rem;background:#276749;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;opacity:0.5;">
                Import <span id="import-count-label">0</span> Peserta Valid
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     MODAL: Konfirmasi Duplikat
══════════════════════════════════════════════════════════════════ --}}
<div id="modal-duplicate" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:110;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:10px;width:560px;max-width:95vw;max-height:80vh;overflow:hidden;display:flex;flex-direction:column;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #EEF4FB;flex-shrink:0;">
            <p style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1A2A3A;margin:0;">Email Sudah Terdaftar</p>
            <p style="font-size:0.72rem;color:#8A97A4;margin:0.2rem 0 0;">Peserta berikut sudah punya akun di sistem</p>
        </div>
        <div style="padding:1.25rem 1.5rem;overflow-y:auto;flex:1;" id="duplicate-list-content"></div>
        <div style="padding:1rem 1.5rem;border-top:1px solid #EEF4FB;display:flex;gap:0.75rem;justify-content:flex-end;flex-shrink:0;">
            <button type="button" onclick="document.getElementById('modal-duplicate').style.display='none'"
                    style="padding:0.625rem 1.25rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.75rem;font-weight:700;color:#4A6580;background:transparent;cursor:pointer;">
                ← Edit Data
            </button>
            <button type="button" id="btn-proceed-anyway" onclick="proceedSubmit()"
                    style="padding:0.625rem 1.25rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                Lanjutkan (Skip Duplikat)
            </button>
        </div>
    </div>
</div>

@push('scripts')
{{-- SheetJS untuk parse Excel di browser --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
// ══════════════════════════════════════════════════════════════════
//  STATE
// ══════════════════════════════════════════════════════════════════
let rowIndex      = 0;       // counter untuk name attribute
let importedRows  = [];      // data valid dari file
let pendingSubmit = false;   // flag setelah cek duplikat

// ══════════════════════════════════════════════════════════════════
//  INIT — tambah 1 baris kosong saat load
// ══════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function () {
    addRow();
    updateSummary();
});

// ══════════════════════════════════════════════════════════════════
//  ROW MANAGEMENT
// ══════════════════════════════════════════════════════════════════
function addRow(name = '', email = '', phone = '', gender = '') {
    const idx = rowIndex++;
    const tbody = document.getElementById('participants-body');
    const tr = document.createElement('tr');
    tr.style.cssText = 'border-bottom:1px solid #EEF4FB;';
    tr.dataset.rowId = idx;
    tr.innerHTML = `
        <td style="padding:0.5rem 0.75rem;text-align:center;font-size:0.78rem;color:#8A97A4;" class="row-num"></td>
        <td style="padding:0.4rem 0.5rem;">
            <input type="text" name="participants[${idx}][name]" value="${escHtml(name)}"
                   placeholder="Nama lengkap..."
                   style="width:100%;padding:0.375rem 0.5rem;border:1px solid #D6E8F7;border-radius:3px;font-size:0.8rem;color:#1A2A3A;outline:none;"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"
                   required/>
        </td>
        <td style="padding:0.4rem 0.5rem;">
            <input type="email" name="participants[${idx}][email]" value="${escHtml(email)}"
                   placeholder="email@contoh.com"
                   class="email-input"
                   style="width:100%;padding:0.375rem 0.5rem;border:1px solid #D6E8F7;border-radius:3px;font-size:0.8rem;color:#1A2A3A;outline:none;"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="validateEmailField(this)"
                   required/>
        </td>
        <td style="padding:0.4rem 0.5rem;">
            <input type="text" name="participants[${idx}][phone]" value="${escHtml(phone)}"
                   placeholder="08..."
                   style="width:100%;padding:0.375rem 0.5rem;border:1px solid #D6E8F7;border-radius:3px;font-size:0.8rem;color:#1A2A3A;outline:none;"
                   onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
        </td>
        <td style="padding:0.4rem 0.5rem;">
            <select name="participants[${idx}][gender]"
                    style="width:100%;padding:0.375rem 0.5rem;border:1px solid #D6E8F7;border-radius:3px;font-size:0.8rem;color:#1A2A3A;outline:none;background:#fff;">
                <option value="L" ${gender==='L'||gender===''?'selected':''}>L</option>
                <option value="P" ${gender==='P'?'selected':''}>P</option>
            </select>
        </td>
        <td style="padding:0.4rem 0.5rem;text-align:center;">
            <button type="button" onclick="removeRow(this)"
                    style="background:none;border:none;color:#C0392B;cursor:pointer;font-size:0.9rem;padding:0.25rem;"
                    title="Hapus baris">✕</button>
        </td>`;
    tbody.appendChild(tr);
    reindexRows();
    updateSummary();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('#participants-body tr');
    if (rows.length <= 1) return; // minimal 1 baris
    btn.closest('tr').remove();
    reindexRows();
    updateSummary();
}

function reindexRows() {
    document.querySelectorAll('#participants-body tr').forEach((tr, i) => {
        tr.querySelector('.row-num').textContent = i + 1;
    });
    // Show/hide hapus semua
    const count = document.querySelectorAll('#participants-body tr').length;
    document.getElementById('btn-clear-all').style.display = count > 1 ? 'inline' : 'none';
}

document.getElementById('btn-add-row').addEventListener('click', () => addRow());

document.getElementById('btn-clear-all').addEventListener('click', function () {
    if (!confirm('Hapus semua peserta?')) return;
    document.getElementById('participants-body').innerHTML = '';
    rowIndex = 0;
    addRow();
});

// ══════════════════════════════════════════════════════════════════
//  VALIDASI EMAIL
// ══════════════════════════════════════════════════════════════════
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}

function validateEmailField(input) {
    const val = input.value.trim().toLowerCase();
    input.value = val; // normalize
    if (val && !isValidEmail(val)) {
        input.style.borderColor = '#C0392B';
        input.style.background  = '#FFF5F5';
    } else {
        input.style.borderColor = '#D6E8F7';
        input.style.background  = '#fff';
    }
    updateSummary();
}

// ══════════════════════════════════════════════════════════════════
//  SUMMARY UPDATE
// ══════════════════════════════════════════════════════════════════
function updateSummary() {
    const rows   = document.querySelectorAll('#participants-body tr');
    const total  = rows.length;
    let valid    = 0, invalid = 0;
    const badList = [];

    rows.forEach((tr, i) => {
        const email = tr.querySelector('.email-input')?.value?.trim() || '';
        const name  = tr.querySelector('input[name*="[name]"]')?.value?.trim() || '';
        if (email && !isValidEmail(email)) {
            invalid++;
            badList.push(`Baris ${i+1}: "${email}"`);
        } else if (email) {
            valid++;
        }
    });

    document.getElementById('total-count').textContent    = total;
    document.getElementById('summary-count').textContent  = total;
    document.getElementById('summary-valid').textContent  = valid;
    document.getElementById('summary-invalid').textContent = invalid;

    const panel = document.getElementById('validation-panel');
    const list  = document.getElementById('validation-list');
    if (invalid > 0) {
        list.innerHTML = badList.map(b => `<li>${b}</li>`).join('');
        panel.style.display = 'block';
    } else {
        panel.style.display = 'none';
    }
}

// ══════════════════════════════════════════════════════════════════
//  UPLOAD EXCEL
// ══════════════════════════════════════════════════════════════════
function closeUploadModal() {
    document.getElementById('modal-upload').style.display = 'none';
    document.getElementById('excel-file-input').value    = '';
    document.getElementById('preview-area').style.display = 'none';
    document.getElementById('preview-tbody').innerHTML   = '';
    document.getElementById('preview-errors').style.display = 'none';
    document.getElementById('btn-import').disabled = true;
    document.getElementById('btn-import').style.opacity = '0.5';
    importedRows = [];
}

document.getElementById('excel-file-input').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (ev) {
        try {
            const data = new Uint8Array(ev.target.result);
            const wb   = XLSX.read(data, { type: 'array' });
            const ws   = wb.Sheets[wb.SheetNames[0]];
            const rows = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });
            parseAndPreview(rows);
        } catch (err) {
            alert('Gagal membaca file: ' + err.message);
        }
    };
    reader.readAsArrayBuffer(file);
});

function parseAndPreview(rawRows) {
    // Skip header row (baris pertama)
    const dataRows = rawRows.slice(1).filter(r => r.some(c => String(c).trim()));

    importedRows = [];
    const errors = [];
    let tbody    = '';

    dataRows.forEach((cols, i) => {
        const name   = String(cols[0] || '').trim();
        const email  = String(cols[1] || '').trim().toLowerCase();
        const phone  = String(cols[2] || '').trim();
        const inst   = String(cols[3] || '').trim();
        const gender = String(cols[4] || 'L').trim().toUpperCase();

        const rowNo   = i + 2; // +2 karena +1 header, +1 index
        const rowErrs = [];
        if (!name)  rowErrs.push('Nama kosong');
        if (!email) rowErrs.push('Email kosong');
        else if (!isValidEmail(email)) rowErrs.push('Email tidak valid');

        const ok = rowErrs.length === 0;
        if (ok) importedRows.push({ name, email, phone, gender });
        else    errors.push(`Baris ${rowNo}: ${rowErrs.join(', ')}`);

        const statusBadge = ok
            ? `<span style="color:#276749;font-weight:700;">✓</span>`
            : `<span style="color:#C0392B;font-weight:700;" title="${rowErrs.join(', ')}">✕</span>`;

        tbody += `<tr style="border-bottom:1px solid #EEF4FB;background:${ok?'#fff':'#FFF5F5'}">
            <td style="padding:0.4rem 0.75rem;text-align:center;color:#8A97A4;">${i+1}</td>
            <td style="padding:0.4rem 0.75rem;font-size:0.78rem;color:${ok?'#1A2A3A':'#C0392B'};">${escHtml(name)||'<em style="color:#aaa">kosong</em>'}</td>
            <td style="padding:0.4rem 0.75rem;font-size:0.78rem;color:${ok?'#1A2A3A':'#C0392B'};">${escHtml(email)||'<em style="color:#aaa">kosong</em>'}</td>
            <td style="padding:0.4rem 0.75rem;text-align:center;">${statusBadge}</td>
        </tr>`;
    });

    document.getElementById('preview-tbody').innerHTML   = tbody;
    document.getElementById('preview-area').style.display = 'block';
    document.getElementById('preview-valid-count').textContent   = importedRows.length;
    document.getElementById('preview-invalid-count').textContent = errors.length;
    document.getElementById('import-count-label').textContent    = importedRows.length;

    const errPanel = document.getElementById('preview-errors');
    if (errors.length) {
        document.getElementById('preview-error-list').innerHTML = errors.map(e => `<li>${e}</li>`).join('');
        errPanel.style.display = 'block';
    } else {
        errPanel.style.display = 'none';
    }

    const canImport = importedRows.length > 0;
    document.getElementById('btn-import').disabled      = !canImport;
    document.getElementById('btn-import').style.opacity = canImport ? '1' : '0.5';
}

function importFromFile() {
    if (!importedRows.length) return;

    // Hapus semua baris kosong yang ada
    document.getElementById('participants-body').innerHTML = '';
    rowIndex = 0;

    // Tambah baris dari data import
    importedRows.forEach(r => addRow(r.name, r.email, r.phone, r.gender));

    // Tutup modal
    closeUploadModal();

    // Tampilkan notice
    const notice = document.getElementById('import-notice');
    document.getElementById('import-notice-text').textContent =
        `${importedRows.length} peserta berhasil diimport dari file Excel.`;
    notice.style.display = 'block';

    updateSummary();
}

// Tutup modal klik backdrop
document.getElementById('modal-upload').addEventListener('click', function (e) {
    if (e.target === this) closeUploadModal();
});

// ══════════════════════════════════════════════════════════════════
//  FORMAT INFO TOGGLE
// ══════════════════════════════════════════════════════════════════
function toggleFormatInfo() {
    const info = document.getElementById('format-info');
    const chev = document.getElementById('format-chevron');
    if (info.style.display === 'none') {
        info.style.display = 'block';
        chev.textContent   = '▲';
    } else {
        info.style.display = 'none';
        chev.textContent   = '▼';
    }
}

// ══════════════════════════════════════════════════════════════════
//  FORM SUBMIT — validasi → cek duplikat → submit
// ══════════════════════════════════════════════════════════════════
document.getElementById('batch-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    // 1. Normalize email
    document.querySelectorAll('.email-input').forEach(inp => {
        inp.value = inp.value.trim().toLowerCase();
    });

    // 2. Validasi basic
    const rows  = document.querySelectorAll('#participants-body tr');
    if (rows.length === 0) {
        alert('Tambahkan minimal 1 peserta.');
        return;
    }

    const invalidEmails = [];
    rows.forEach((tr, i) => {
        const email = tr.querySelector('.email-input')?.value?.trim() || '';
        if (!isValidEmail(email)) invalidEmails.push(`Baris ${i+1}: "${email}"`);
    });

    if (invalidEmails.length) {
        alert('Email tidak valid:\n' + invalidEmails.join('\n'));
        return;
    }

    // 3. Jika sudah di-flag proceed (setelah konfirmasi duplikat)
    if (pendingSubmit) {
        pendingSubmit = false;
        submitForm();
        return;
    }

    // 4. Kumpulkan semua email untuk cek duplikat
    const emails = [];
    rows.forEach(tr => {
        const email = tr.querySelector('.email-input')?.value?.trim();
        if (email) emails.push(email);
    });

    // 5. Cek duplikat via AJAX
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.textContent = 'Memeriksa...';

    try {
        const res  = await fetch('{{ route("daerah.batch.check-duplicates") }}', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept':       'application/json',
            },
            body: JSON.stringify({ emails }),
        });

        if (!res.ok) throw new Error('Server error');
        const data = await res.json();

        if (data.duplicates && data.duplicates.length > 0) {
            showDuplicateModal(data.duplicates);
            btn.disabled    = false;
            btn.textContent = 'Daftarkan Anggota';
        } else {
            // Tidak ada duplikat → langsung submit
            pendingSubmit = true;
            submitForm();
        }
    } catch (err) {
        // Jika endpoint belum ada atau error → langsung submit saja
        pendingSubmit = true;
        submitForm();
    }
});

function submitForm() {
    const btn = document.getElementById('btn-submit');
    btn.disabled    = true;
    btn.textContent = 'Mendaftarkan...';
    document.getElementById('batch-form').submit();
}

// ══════════════════════════════════════════════════════════════════
//  MODAL DUPLIKAT
// ══════════════════════════════════════════════════════════════════
function showDuplicateModal(duplicates) {
    document.getElementById('dup-count').textContent = duplicates.length;
    document.getElementById('duplicate-panel').style.display = 'block';

    let html = '';
    duplicates.forEach((d, i) => {
        html += `
        <div style="border:1px solid #EEF4FB;border-radius:6px;padding:0.875rem;margin-bottom:0.75rem;">
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
                <p style="font-size:0.82rem;font-weight:700;color:#1A2A3A;margin:0;">${escHtml(d.name || d.email)}</p>
                <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:2px;background:#FEF8EC;color:#B8860B;">Sudah Terdaftar</span>
            </div>
            <p style="font-size:0.75rem;color:#4A6580;margin:0;">${escHtml(d.email)}</p>
            ${d.member_number ? `<p style="font-size:0.72rem;color:#8A97A4;margin:0.25rem 0 0;">NIA: ${d.member_number}</p>` : ''}
        </div>`;
    });

    document.getElementById('duplicate-list-content').innerHTML =
        `<p style="font-size:0.82rem;color:#4A6580;margin:0 0 0.75rem;">
            Peserta berikut sudah memiliki akun. Mereka akan <strong>dilewati (skip)</strong> saat pendaftaran.
         </p>` + html;

    document.getElementById('modal-duplicate').style.display = 'flex';
}

function proceedSubmit() {
    document.getElementById('modal-duplicate').style.display = 'none';
    pendingSubmit = true;
    submitForm();
}

// Tutup modal duplikat klik backdrop
document.getElementById('modal-duplicate').addEventListener('click', function (e) {
    if (e.target === this) this.style.display = 'none';
});

// ══════════════════════════════════════════════════════════════════
//  UTILITY
// ══════════════════════════════════════════════════════════════════
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush

@endsection