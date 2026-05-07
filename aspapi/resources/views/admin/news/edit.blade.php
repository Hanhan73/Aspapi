@extends('layouts.admin')
@php
    $title = 'Edit Berita';
    $breadcrumbs = [
        ['label' => 'Berita', 'url' => route('admin.news.index')],
        ['label' => 'Edit', 'url' => '#'],
    ];
@endphp

@section('content')

{{-- Form utama UPDATE — tidak mengandung form hapus di dalamnya --}}
<form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.news._form')
</form>

{{-- Form hapus berdiri SENDIRI di luar form utama agar tidak nested --}}
<form method="POST" action="{{ route('admin.news.destroy', $news) }}"
      onsubmit="return confirm('Hapus berita ini secara permanen?')"
      style="margin-top:0.75rem;">
    @csrf @method('DELETE')
    <button type="submit"
            style="width:100%;padding:0.625rem;background:transparent;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C0392B;cursor:pointer;">
        Hapus Berita
    </button>
</form>

@endsection