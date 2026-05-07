@extends('layouts.admin')
@php $title = 'Tambah Dewan Pakar'; @endphp
@section('content')
<form method="POST" action="{{ route('admin.experts.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.experts._form')
</form>
@endsection