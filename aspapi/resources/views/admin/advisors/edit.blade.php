@extends('layouts.admin')
@php $title = 'Edit Dewan Penasihat'; @endphp
@section('content')

<form method="POST" action="{{ route('admin.advisors.update', $advisor) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.advisors._form')
</form>

<form method="POST" action="{{ route('admin.advisors.destroy', $advisor) }}"
      onsubmit="return confirm('Hapus data ini?')" style="margin-top:0.75rem;max-width:300px;margin-left:auto;">
    @csrf @method('DELETE')
    <button type="submit"
            style="width:100%;padding:0.625rem;background:transparent;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C0392B;cursor:pointer;">
        Hapus
    </button>
</form>

@endsection