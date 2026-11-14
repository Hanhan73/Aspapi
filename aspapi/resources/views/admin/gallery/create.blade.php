@extends('layouts.admin')
@php
    $title = 'Tambah Album';
    $breadcrumbs = [
        ['label' => 'Galeri', 'url' => route('admin.gallery.index')],
        ['label' => 'Tambah', 'url' => '#'],
    ];
@endphp

@section('content')
<form method="POST" action="{{ route('admin.gallery.store') }}">
    @csrf
    @include('admin.gallery._form')
</form>
@endsection
