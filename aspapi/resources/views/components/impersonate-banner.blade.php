{{--
    Taruh komponen ini di layouts/member.blade.php,
    tepat SEBELUM tag <main> atau di paling atas <body>.
    Hanya muncul ketika sedang dalam mode impersonate.
--}}
@if (session('impersonator_id'))
<div style="position:sticky;top:0;z-index:999;background:#C0392B;color:#fff;padding:0.625rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;font-size:0.8rem;box-shadow:0 2px 8px rgba(0,0,0,0.2);">
    <div style="display:flex;align-items:center;gap:0.625rem;">
        <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        <span>
            Anda sedang masuk sebagai
            <strong>{{ auth()->user()->name }}</strong>
            <span style="opacity:0.75;">({{ auth()->user()->email }})</span>
        </span>
    </div>
    <form method="POST" action="{{ route('impersonate.leave') }}" style="margin:0;">
        @csrf
        <button type="submit"
                style="padding:0.3rem 0.875rem;background:rgba(255,255,255,0.2);color:#fff;border:1.5px solid rgba(255,255,255,0.5);border-radius:4px;font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;white-space:nowrap;">
            ← Kembali ke Akun Saya
        </button>
    </form>
</div>
@endif