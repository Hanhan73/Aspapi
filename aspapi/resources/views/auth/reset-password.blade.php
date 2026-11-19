<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password — ASPAPI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/logo-aspapi.png') }}" />
</head>

<body style="background:#F8FAFC;font-family:'DM Sans',sans-serif;">
    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;">
        <div style="width:100%;max-width:400px;">

            <div style="text-align:center;margin-bottom:2rem;">
                <img src="{{ asset('images/logo-aspapi.png') }}" style="height:56px;margin:0 auto 1rem;"
                    onerror="this.style.display='none'" />
            </div>

            <p
                style="font-size:0.65rem;font-weight:700;letter-spacing:0.16em;text-transform:uppercase;color:#C0392B;margin-bottom:0.5rem;text-align:center;">
                Portal ASPAPI</p>
            <h2
                style="font-family:'DM Serif Display',serif;font-size:1.75rem;color:#1A2A3A;margin-bottom:0.5rem;text-align:center;">
                Buat Password Baru</h2>

            @if ($errors->any())
            <div
                style="background:#FDECEA;border-left:4px solid #C0392B;border-radius:4px;padding:0.875rem 1rem;margin-bottom:1.5rem;">
                <p style="font-size:0.8rem;color:#922B21;">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div style="margin-bottom:1.25rem;">
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required
                        style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Password
                        Baru</label>
                    <input type="password" name="password" required minlength="8"
                        style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                </div>

                <div style="margin-bottom:1.75rem;">
                    <label
                        style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.5rem;">Konfirmasi
                        Password</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                        style="width:100%;padding:0.75rem 1rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"
                        onfocus="this.style.borderColor='#2A7FC1'" onblur="this.style.borderColor='#D6E8F7'" />
                </div>

                <button type="submit"
                    style="width:100%;padding:0.875rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;transition:background 0.2s;"
                    onmouseover="this.style.background='#1A5F9A'" onmouseout="this.style.background='#2A7FC1'">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</body>

</html>