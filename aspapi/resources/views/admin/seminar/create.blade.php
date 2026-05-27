@extends('layouts.admin')
@section('title', 'Buat Seminar Baru')

@section('content')
<div class="p-6">

    <div class="mb-6">
        <a href="{{ route('admin.seminar.index') }}" class="text-xs text-neutral-400 hover:text-primary">← Kembali</a>
        <h1 class="text-xl font-extrabold text-navy mt-2">Buat Seminar Baru</h1>
    </div>

    <form method="POST" action="{{ route('admin.seminar.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.seminar._form', ['seminar' => null])
        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition">
                Simpan Seminar
            </button>
        </div>
    </form>
</div>
@endsection