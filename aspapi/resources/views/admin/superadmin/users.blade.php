@extends('layouts.admin')

@php $title = 'Kelola Akun'; @endphp

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C0392B;margin-bottom:0.25rem;">Super Admin</p>
        <h1 style="font-family:'DM Serif Display',serif;font-size:1.5rem;color:#1A2A3A;margin:0;">Kelola Akun</h1>
    </div>
    <a href="{{ route('admin.superadmin.create') }}"
       style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;background:#2A7FC1;color:#fff;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun
    </a>
</div>

@if(session('success'))
<div style="background:#EEF4FB;border:1px solid #6AAFE6;border-radius:4px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.875rem;color:#1A2A3A;">
    {!! session('success') !!}
</div>
@endif

@if(session('error'))
<div style="background:#FEF2F2;border:1px solid #C0392B;border-radius:4px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.875rem;color:#C0392B;">
    {{ session('error') }}
</div>
@endif

{{-- Tabel --}}
<div style="background:#fff;border:1px solid #D6E8F7;border-radius:6px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#EEF4FB;">
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Nama</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Email</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Role</th>
                <th style="padding:0.75rem 1rem;text-align:left;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Region</th>
                <th style="padding:0.75rem 1rem;text-align:right;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#2A7FC1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr style="border-top:1px solid #EEF4FB;">
                <td style="padding:0.875rem 1rem;">
                    <p style="font-size:0.875rem;font-weight:600;color:#1A2A3A;margin:0;">{{ $user->name }}</p>
                </td>
                <td style="padding:0.875rem 1rem;">
                    <p style="font-size:0.875rem;color:#4A6580;margin:0;">{{ $user->email }}</p>
                </td>
                <td style="padding:0.875rem 1rem;">
                    @php
                        $roleColor = match($user->role) {
                            'admin'         => '#2A7FC1',
                            'bendahara'     => '#E8B84B',
                            'aspapi_daerah' => '#27ae60',
                            default         => '#4A6580',
                        };
                        $roleLabel = match($user->role) {
                            'admin'         => 'Admin',
                            'bendahara'     => 'Bendahara',
                            'aspapi_daerah' => 'Daerah',
                            default         => $user->role,
                        };
                    @endphp
                    <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:3px;font-size:0.65rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;background:{{ $roleColor }}1a;color:{{ $roleColor }};border:1px solid {{ $roleColor }}44;">
                        {{ $roleLabel }}
                    </span>
                </td>
                <td style="padding:0.875rem 1rem;">
                    <p style="font-size:0.8rem;color:#4A6580;margin:0;">
                        {{ $user->region?->province ?? '—' }}
                    </p>
                </td>
                <td style="padding:0.875rem 1rem;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.5rem;flex-wrap:wrap;">
                        {{-- Impersonate --}}
                        <form method="POST" action="{{ route('impersonate', $user->id) }}">
                            @csrf
                            <button type="submit"
                                    style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;background:transparent;border:1.5px solid #6AAFE6;color:#2A7FC1;border-radius:3px;cursor:pointer;">
                                Login Sebagai
                            </button>
                        </form>

                        {{-- Reset Password --}}
                        <form method="POST" action="{{ route('admin.superadmin.reset-password', $user->id) }}"
                              onsubmit="return confirm('Reset password {{ $user->name }}? Password baru akan ditampilkan di layar.')">
                            @csrf
                            <button type="submit"
                                    style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;background:transparent;border:1.5px solid #E8B84B;color:#c9922a;border-radius:3px;cursor:pointer;">
                                Reset PW
                            </button>
                        </form>

                        {{-- Edit --}}
                        <a href="{{ route('admin.superadmin.edit', $user->id) }}"
                           style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;background:transparent;border:1.5px solid #D6E8F7;color:#4A6580;border-radius:3px;text-decoration:none;">
                            Edit
                        </a>

                        {{-- Hapus --}}
                        <form method="POST" action="{{ route('admin.superadmin.destroy', $user->id) }}"
                              onsubmit="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="font-size:0.65rem;font-weight:700;padding:0.25rem 0.625rem;background:transparent;border:1.5px solid #C0392B;color:#C0392B;border-radius:3px;cursor:pointer;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:3rem;text-align:center;color:#B0CCDF;font-size:0.875rem;">
                    Belum ada akun.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection