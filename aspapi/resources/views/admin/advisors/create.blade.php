@extends('layouts.admin')
@php $title = 'Tambah Dewan Penasihat'; @endphp
@section('content')
<form method="POST" action="{{ route('admin.advisors.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.advisors._form')
</form>
@endsection