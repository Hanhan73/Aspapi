@extends('layouts.admin')
@php $title = 'Edit Berita'; $breadcrumbs = [['label' => 'Berita', 'url' => route('admin.news.index')], ['label' => 'Edit', 'url' => '#']]; @endphp

@section('content')
<form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.news._form')
</form>
@endsection