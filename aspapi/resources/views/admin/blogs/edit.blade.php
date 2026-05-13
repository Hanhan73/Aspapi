@extends('layouts.admin')
@php $title = 'Edit Blog'; $breadcrumbs = [['label' => 'Blog', 'url' => route('admin.blogs.index')], ['label' => 'Edit', 'url' => '#']]; @endphp
@section('content')

<form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.blogs._form')
</form>

<form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}"
      onsubmit="return confirm('Hapus blog ini?')" style="margin-top:0.75rem;max-width:300px;margin-left:auto;">
    @csrf @method('DELETE')
    <button type="submit"
            style="width:100%;padding:0.625rem;background:transparent;border:1.5px solid #C0392B;border-radius:4px;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C0392B;cursor:pointer;">
        Hapus Blog
    </button>
</form>

@endsection 