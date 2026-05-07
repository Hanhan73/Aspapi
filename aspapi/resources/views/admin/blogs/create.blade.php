@extends('layouts.admin')
@php $title = 'Tambah Blog'; $breadcrumbs = [['label' => 'Blog', 'url' => route('admin.blogs.index')], ['label' => 'Tambah', 'url' => '#']]; @endphp

@section('content')
<form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.blogs._form')
</form>
@endsection