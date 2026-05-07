@extends('layouts.admin')
@php
    $title = 'Tambah Berita';
    $breadcrumbs = [
        ['label' => 'Berita', 'url' => route('admin.news.index')],
        ['label' => 'Tambah', 'url' => '#'],
    ];
@endphp

@section('content')
<form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.news._form')
</form>
@endsection