@extends('layouts.admin')

@php $title = 'Edit Akun'; @endphp

@section('content')

<div style="max-width:560px;">

    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('admin.superadmin.users') }}"
           style="font-size:0.75rem;color:#4A6580;text-decoration:none;display:inline-flex;align-items:center;gap:0.375rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h1 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin:0.5rem 0 0;">Edit Akun</h1>
    </div>

    <div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;padding:1.5rem;">
        <form method="POST" action="{{ route('admin.superadmin.update', $user->id) }}">
            @csrf @method('PUT')

            {{-- Nama --}}
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.375rem;">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       style="width:100%;padding:0.5rem 0.75rem;border:1.5px solid {{ $errors->has('name') ? '#C0392B' : '#D6E8F7' }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;box-sizing:border-box;">
                @error('name')<p style="font-size:0.75rem;color:#C0392B;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Email Login --}}
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.375rem;">Email Login</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       style="width:100%;padding:0.5rem 0.75rem;border:1.5px solid {{ $errors->has('email') ? '#C0392B' : '#D6E8F7' }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;box-sizing:border-box;">
                @error('email')<p style="font-size:0.75rem;color:#C0392B;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Email Notifikasi --}}
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.375rem;">Email Notifikasi</label>
                <input type="email" name="notification_email"
                       value="{{ old('notification_email', $user->notification_email) }}"
                       placeholder="Kosongkan jika sama dengan email login..."
                       style="width:100%;padding:0.5rem 0.75rem;border:1.5px solid {{ $errors->has('notification_email') ? '#C0392B' : '#D6E8F7' }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;box-sizing:border-box;">
                <p style="font-size:0.7rem;color:#B0CCDF;margin-top:0.25rem;">Email yang menerima notifikasi sistem (verifikasi biodata, pembayaran, dll). Jika dikosongkan, sistem akan menggunakan fallback dari ENV.</p>
                @error('notification_email')<p style="font-size:0.75rem;color:#C0392B;margin-top:0.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Role --}}
            <div style="margin-bottom:1.5rem;" x-data="{ role: '{{ old('role', $user->role) }}' }">
                <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.375rem;">Role</label>
                <select name="role" x-model="role" required
                        style="width:100%;padding:0.5rem 0.75rem;border:1.5px solid {{ $errors->has('role') ? '#C0392B' : '#D6E8F7' }};border-radius:4px;font-size:0.875rem;color:#1A2A3A;">
                    <option value="admin"         {{ old('role', $user->role) === 'admin'         ? 'selected' : '' }}>Admin (Ketua/Sekjen)</option>
                    <option value="bendahara"     {{ old('role', $user->role) === 'bendahara'     ? 'selected' : '' }}>Bendahara</option>
                    <option value="aspapi_daerah" {{ old('role', $user->role) === 'aspapi_daerah' ? 'selected' : '' }}>ASPAPI Daerah</option>
                </select>
                @error('role')<p style="font-size:0.75rem;color:#C0392B;margin-top:0.25rem;">{{ $message }}</p>@enderror

                <div x-show="role === 'aspapi_daerah'" style="margin-top:1rem;">
                    <label style="display:block;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#4A6580;margin-bottom:0.375rem;">Region</label>
                    <select name="region_id"
                            style="width:100%;padding:0.5rem 0.75rem;border:1.5px solid #D6E8F7;border-radius:4px;font-size:0.875rem;color:#1A2A3A;">
                        <option value="">-- Pilih Region --</option>
                        @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id', $user->region_id) == $region->id ? 'selected' : '' }}>
                            {{ $region->province }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit"
                    style="padding:0.625rem 1.25rem;background:#2A7FC1;color:#fff;border:none;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;cursor:pointer;">
                Simpan Perubahan
            </button>
        </form>
    </div>

</div>

@endsection