@extends('layouts.admin')
@php $title = 'Tambah Pengurus'; @endphp
@section('content')
<form method="POST" action="{{ route('admin.boards.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.boards._form')
</form>
@endsection