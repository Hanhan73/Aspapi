@extends('layouts.admin')
@section('title', 'Kelola Akun — ASPAPI '.$region->province)

@section('content')
<div class="max-w-lg">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.regions.index') }}" class="text-neutral-400 hover:text-navy text-sm">← Kembali</a>
        <h1 class="text-xl font-bold text-navy">Akun ASPAPI {{ $region->province }}</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    {{-- Akun aktif --}}
    @if ($user)
    <div class="card p-5 mb-6">
        <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-3">Akun Aktif</p>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                <span class="text-primary-600 font-bold">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="font-semibold text-navy text-sm">{{ $user->name }}</p>
                <p class="text-xs text-neutral-500">{{ $user->email }}</p>
            </div>
        </div>

        <form action="{{ route('admin.regions.account.reset-password', $region) }}" method="POST"
              class="mt-5 pt-5 border-t border-neutral-100 space-y-3">
            @csrf
            <p class="text-xs font-bold uppercase tracking-widest text-neutral-400">Reset Password</p>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <input type="password" name="password" class="form-input text-sm"
                           placeholder="Password baru">
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <input type="password" name="password_confirmation" class="form-input text-sm"
                           placeholder="Konfirmasi">
                </div>
            </div>
            <button type="submit" class="btn btn-outline text-xs">Reset Password</button>
        </form>
    </div>
    @else
    <div class="card p-6 mb-6 border-dashed text-center">
        <p class="text-sm text-neutral-400">Belum ada akun login untuk daerah ini.</p>
    </div>
    @endif

    {{-- Buat / ganti akun --}}
    <div class="card p-5">
        <p class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4">
            {{ $user ? 'Ganti Akun' : 'Buat Akun Baru' }}
        </p>
        <form action="{{ route('admin.regions.account.store', $region) }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input @error('email') border-accent-red @enderror"
                       value="{{ old('email') }}"
                       placeholder="daerah.{{ $region->slug }}@aspapi.or.id" required>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input"
                           placeholder="Min. 8 karakter" required>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary text-sm">
                {{ $user ? 'Ganti Akun' : 'Buat Akun' }}
            </button>
        </form>
    </div>
</div>
@endsection