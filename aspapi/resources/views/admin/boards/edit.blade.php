@extends('layouts.admin')
@php $title = 'Edit Pengurus'; @endphp
@section('content')

<form method="POST" action="{{ route('admin.boards.update', $board) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.boards._form')
</form>

<form method="POST" action="{{ route('admin.boards.destroy', $board) }}"
      onsubmit="return confirm('Hapus data ini?')" style="margin-top:0.75rem;max-width:300px;margin-left:auto;">
    @csrf @method('DELETE')
    <button type="submit"
            style="width:100%;padding:0.625rem;background:transparent;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C0392B;cursor:pointer;">
        Hapus
    </button>
</form>

@endsection