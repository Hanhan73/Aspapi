<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Daftar Anggota Lama — ASPAPI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:#F8FAFC;font-family:'DM Sans',sans-serif;">

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;">
    <div style="width:100%;max-width:520px;">

        <div style="text-align:center;margin-bottom:2rem;">
            <img src="{{ asset('images/logo-aspapi.png') }}" style="height:56px;margin:0 auto 1rem;" onerror="this.style.display='none'"/>
            <h1 style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#1A2A3A;">Daftar Anggota Lama</h1>
            <p style="font-size:0.875rem;color:#4A6580;margin-top:0.375rem;">Untuk anggota yang sudah terdaftar sebelumnya</p>
        </div>

        <div style="background:#FEF8EC;border-left:4px solid #E8B84B;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.825rem;color:#8B6914;">
            Daftar di sini jika Anda sudah pernah menjadi anggota ASPAPI sebelumnya. Admin akan memverifikasi status keanggotaan lama Anda.
        </div>

        @if ($errors->any())
        <div style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:1rem;margin-bottom:1.5rem;">
            @foreach ($errors->all() as $e)
            <p style="font-size:0.8rem;color:#922B21;">{{ $e }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register.old.store') }}"
              style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:2rem;">
            @csrf

            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Nama Lengkap <span style="color:#C0392B;">*</span>
                </label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required
                       style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Email <span style="color:#C0392B;">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                    Tahun Pertama Bergabung ASPAPI <span style="color:#C0392B;">*</span>
                </label>
                <input type="number" name="claimed_join_year"
                       value="{{ old('claimed_join_year') }}"
                       min="2010" max="{{ now()->year }}"
                       placeholder="contoh: 2015" required
                       style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                <p style="font-size:0.72rem;color:#B0CCDF;margin-top:0.375rem;">Tahun ini akan diverifikasi oleh Admin ASPAPI.</p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.75rem;">
                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Password <span style="color:#C0392B;">*</span></label>
                    <input type="password" name="password" required
                           style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                           onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                </div>
                <div>
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Konfirmasi <span style="color:#C0392B;">*</span></label>
                    <input type="password" name="password_confirmation" required
                           style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                           onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'"/>
                </div>
            </div>

            <button type="submit"
                    style="width:100%;padding:0.875rem;background:#C0392B;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                Daftar sebagai Anggota Lama
            </button>
        </form>

        <div style="text-align:center;margin-top:1.5rem;font-size:0.825rem;color:#4A6580;">
            Sudah punya akun? <a href="{{ route('login') }}" style="color:#2A7FC1;font-weight:700;">Masuk</a>
            &nbsp;·&nbsp;
            Anggota baru? <a href="{{ route('register') }}" style="color:#2A7FC1;font-weight:700;">Daftar di sini</a>
        </div>
    </div>
</div>
</body>
</html>