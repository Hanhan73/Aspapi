@php
    $layout = match(auth()->user()->role) {
        'admin'         => 'layouts.admin',
        'bendahara'     => 'layouts.bendahara',
        'aspapi_daerah' => 'layouts.daerah',
        default         => 'layouts.member',
    };
    $title = 'Pengaturan Akun';
@endphp

@extends($layout)

@section('content')

<div style="max-width:520px;">

    {{-- Info Akun --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.5rem;margin-bottom:1.25rem;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Informasi Akun</p>

        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            <div>
                <p style="font-size:0.7rem;color:#B0CCDF;text-transform:uppercase;letter-spacing:0.08em;">Nama</p>
                <p style="font-size:0.9rem;font-weight:600;color:#1A2A3A;margin-top:0.2rem;">{{ $user->name }}</p>
            </div>
            <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.5rem;margin-bottom:1.25rem;">
                <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Ganti Email</p>

                @if (session('success_email'))
                <div style="background:#F0FFF4;border-left:3px solid #276749;padding:0.75rem 1rem;border-radius:3px;font-size:0.8rem;color:#276749;margin-bottom:1rem;">
                    {{ session('success_email') }}
                </div>
                @endif

                @if ($errors->has('email') || $errors->has('current_password_email'))
                <div style="background:#FDECEA;border-left:3px solid #C0392B;padding:0.75rem 1rem;border-radius:3px;font-size:0.8rem;color:#C0392B;margin-bottom:1rem;">
                    @error('email')<p>{{ $message }}</p>@enderror
                    @error('current_password_email')<p>{{ $message }}</p>@enderror
                </div>
                @endif

                <form method="POST" action="{{ route('account.email') }}" style="display:flex;flex-direction:column;gap:1rem;">
                    @csrf

                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.4rem;">
                            Email Baru *
                        </label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"/>
                    </div>

                    <div>
                        <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.4rem;">
                            Konfirmasi dengan Password *
                        </label>
                        <input type="password" name="current_password" required
                            style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"/>
                    </div>

                    <div>
                        <button type="submit"
                                style="padding:0.625rem 1.5rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                            Simpan Email Baru
                        </button>
                    </div>
                </form>
            </div>
            <div>
                <p style="font-size:0.7rem;color:#B0CCDF;text-transform:uppercase;letter-spacing:0.08em;">Role</p>
                <p style="font-size:0.9rem;color:#1A2A3A;margin-top:0.2rem;">
                    {{ match($user->role) {
                        'admin'         => 'Administrator',
                        'bendahara'     => 'Bendahara',
                        'aspapi_daerah' => 'ASPAPI Daerah',
                        default         => 'Anggota',
                    } }}
                </p>
            </div>
        </div>
    </div>

    {{-- Ganti Password --}}
    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.5rem;">
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#4A6580;margin-bottom:1rem;">Ganti Password</p>

        @if (session('success'))
        <div style="background:#F0FFF4;border-left:3px solid #276749;padding:0.75rem 1rem;border-radius:3px;font-size:0.8rem;color:#276749;margin-bottom:1rem;">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div style="background:#FDECEA;border-left:3px solid #C0392B;padding:0.75rem 1rem;border-radius:3px;font-size:0.8rem;color:#C0392B;margin-bottom:1rem;">
            @foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('account.password') }}" style="display:flex;flex-direction:column;gap:1rem;">
            @csrf

            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.4rem;">
                    Password Saat Ini *
                </label>
                <input type="password" name="current_password" required
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"/>
            </div>

            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.4rem;">
                    Password Baru *
                </label>
                <input type="password" name="password" required minlength="8"
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"/>
                <p style="font-size:0.7rem;color:black;margin-top:0.25rem;">Minimal 8 karakter.</p>
            </div>

            <div>
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.4rem;">
                    Konfirmasi Password Baru *
                </label>
                <input type="password" name="password_confirmation" required
                       style="width:100%;padding:0.625rem 0.875rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;outline:none;box-sizing:border-box;"/>
            </div>

            <div>
                <button type="submit"
                        style="padding:0.625rem 1.5rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>

</div>

@endsection