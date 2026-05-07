@extends('layouts.admin')
@php $title = 'Edit Pengurus'; @endphp
@section('content')
<form method="POST" action="{{ route('admin.boards.update', $board) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.boards._form')
</form>
@endsection