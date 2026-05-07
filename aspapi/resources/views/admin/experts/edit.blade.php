@extends('layouts.admin')
@php $title = 'Edit Dewan Pakar'; @endphp
@section('content')
<form method="POST" action="{{ route('admin.experts.update', $expert) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.experts._form')
</form>
@endsection