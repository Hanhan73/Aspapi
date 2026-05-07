<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Login — ASPAPI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:#F8FAFC;font-family:'DM Sans',sans-serif;">
<div style="min-height:100vh;display:flex;">

    {{-- Kiri: Branding --}}
    <div style="display:none;width:42%;background:linear-gradient(135deg,#111E2A,#1A5F9A,#2A7FC1);position:relative;overflow:hidden;" id="branding-panel">
        <div style="position:absolute;inset-x:0;top:0;height:4px;background:linear-gradient(90deg,#C0392B,#E8B84B);"></div>
        <div style="position:absolute;right:-80px;top:-80px;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
        <div style="position:absolute;left:40px;bottom:120px;width:120px;height:120px;border-radius:50%;background:rgba(232,184,75,0.08);"></div>
        <div style="position:relative;height:100%;display:flex;flex-direction:column;justify-content:center;padding:3rem;">
            <img src="{{ asset('images/logo-aspapi.png') }}" style="height:52px;width:auto;object-fit:contain;margin-bottom:2rem;" onerror="this.style.display='none'"/>
            <h1 style="font-family:'DM Serif Display',serif;color:#fff;font-size:2rem;line-height:1.2;margin-bottom:1rem;">
                Selamat Datang<br/>di Portal ASPAPI
            </h1>
            <p style="color:rgba(168,212,245,0.8);font-size:0.875rem;line-height:1.8;margin-bottom:2.5rem;">
                Masuk untuk mengakses portal anggota, kelola biodata, pembayaran, dan kartu tanda anggota Anda.
            </p>
            <p style="font-size:0.7rem;color:#E8B84B;font-style:italic;letter-spacing:0.06em;">
                Competent · Competitive · Collaborative
            </p>
        </div>
    </div>

    {{-- Kanan: Form --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:2rem;">
        <div style="width:100%;max-width:400px;">

            {{-- Mobile logo --}}
            <div style="text-align:center;margin-bottom:2rem;" class="lg-hidden">
                <img src="{{ asset('images/logo-aspapi.png') }}" style="height:48px;margin:0 auto 0.75rem;" onerror="this.style.display='none'"/>
            </div>

            <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#C0392B;margin-bottom:0.5rem;">Portal ASPAPI</p>
            <h2 style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#1A2A3A;margin-bottom:0.5rem;">Masuk ke Akun Anda</h2>
            <p style="font-size:0.875rem;color:#4A6580;margin-bottom:2rem;">Untuk Anggota, Bendahara, dan ASPAPI Daerah.</p>

            @if ($errors->any())
            <div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:0.875rem 1rem;margin-bottom:1.5rem;">
                <p style="font-size:0.8rem;color:#922B21;">{{ $errors->first() }}</p>
            </div>
            @endif

            @if (session('success'))
            <div style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:0.875rem 1rem;margin-bottom:1.5rem;">
                <p style="font-size:0.8rem;color:#276749;">{{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login.member') }}">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                           onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                </div>

                <div style="margin-bottom:1.75rem;">
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Password</label>
                    <div style="position:relative;">
                        <input type="password" name="password" id="pw" required
                               style="width:100%;padding:0.75rem 3rem 0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;transition:border-color 0.2s;"
                               onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                        <button type="button" onclick="togglePw()"
                                style="position:absolute;right:0.875rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#B0CCDF;padding:0;">
                            <svg id="eye" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                        <input type="checkbox" name="remember" style="width:14px;height:14px;accent-color:#2A7FC1;"/>
                        <span style="font-size:0.8rem;color:#4A6580;">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        style="width:100%;padding:0.875rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:background 0.2s;"
                        onmouseover="this.style.background='#1A5F9A'" onmouseout="this.style.background='#2A7FC1'">
                    Masuk
                </button>
            </form>

            {{-- Register links --}}
            <div style="margin-top:1.75rem;padding-top:1.75rem;border-top:1px solid #EEF4FB;text-align:center;">
                <p style="font-size:0.825rem;color:#4A6580;margin-bottom:0.75rem;">Belum punya akun?</p>
                <div style="display:flex;gap:0.75rem;justify-content:center;">
                    <a href="{{ route('register') }}"
                       style="font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:0.5rem 1rem;border:1.5px solid #2A7FC1;color:#2A7FC1;border-radius:3px;text-decoration:none;">
                        Daftar Anggota Baru
                    </a>
                    <a href="{{ route('register.old') }}"
                       style="font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:0.5rem 1rem;border:1.5px solid #C0392B;color:#C0392B;border-radius:3px;text-decoration:none;">
                        Anggota Lama
                    </a>
                </div>
            </div>

            {{-- Admin link --}}
            <div style="text-align:center;margin-top:1.25rem;">
                <a href="{{ route('admin.login') }}"
                   style="font-size:0.75rem;color:#B0CCDF;text-decoration:none;"
                   onmouseover="this.style.color='#2A7FC1'" onmouseout="this.style.color='#B0CCDF'">
                    Login sebagai Admin →
                </a>
            </div>

            <p style="text-align:center;font-size:0.7rem;color:#D6E8F7;margin-top:2rem;">
                &copy; {{ date('Y') }} ASPAPI. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</div>

<script>
function togglePw() {
    const input = document.getElementById('pw');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

<style>
@media (min-width: 1024px) {
    #branding-panel { display: block !important; }
    .lg-hidden { display: none !important; }
}
</style>

</body>
</html>