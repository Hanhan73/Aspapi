<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Anggota Baru — ASPAPI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/logo-aspapi.png') }}" />
</head>

<body style="background:#F8FAFC;font-family:'DM Sans',sans-serif;">

    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;">
        <div style="width:100%;max-width:520px;">

            {{-- Logo --}}
            <div style="text-align:center;margin-bottom:2rem;">
                <img src="{{ asset('images/logo-aspapi.png') }}" style="height:56px;margin:0 auto 1rem;"
                    onerror="this.style.display='none'" />
                <h1 style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#1A2A3A;">Daftar Anggota Baru
                </h1>
                <p style="font-size:0.875rem;color:#4A6580;margin-top:0.375rem;">Bergabung sebagai anggota ASPAPI</p>
            </div>

            {{-- Alert --}}
            <div
                style="background:#EEF4FB;border-left:4px solid #2A7FC1;border-radius:4px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.825rem;color:#1A5F9A;">
                Setelah mendaftar, Anda akan menerima email verifikasi. Setelah email diverifikasi, lengkapi biodata dan
                tunggu verifikasi dari Admin.
            </div>

            @if ($errors->any())
            <div
                style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:1rem;margin-bottom:1.5rem;">
                @foreach ($errors->all() as $e)
                <p style="font-size:0.8rem;color:#922B21;">{{ $e }}</p>
                @endforeach
            </div>
            @endif

            @if (session('success'))
            <div
                style="background:#F0FFF4;border-left:4px solid #276749;border-radius:4px;padding:1rem;margin-bottom:1.5rem;">
                <p style="font-size:0.8rem;color:#276749;">{{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}"
                style="background:#fff;border:1px solid #D6E8F7;border-radius:8px;padding:2rem;">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                        Nama Lengkap <span style="color:#C0392B;">*</span>
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required
                        style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                        Email <span style="color:#C0392B;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                            Password <span style="color:#C0392B;">*</span>
                        </label>
                        <input type="password" name="password" required
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                    </div>
                    <div>
                        <label
                            style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">
                            Konfirmasi Password <span style="color:#C0392B;">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                            style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                            onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                    </div>
                </div>

                <button type="submit"
                    style="width:100%;padding:0.875rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                    Daftar Sekarang
                </button>
            </form>

            <div style="text-align:center;margin-top:1.5rem;font-size:0.825rem;color:#4A6580;">
                Sudah punya akun? <a href="{{ route('login') }}" style="color:#2A7FC1;font-weight:700;">Masuk</a>
                &nbsp;·&nbsp;
                Anggota lama? <a href="{{ route('register.old') }}" style="color:#C0392B;font-weight:700;">Daftar di
                    sini</a>
            </div>

        </div>
    </div>
</body>

</html>