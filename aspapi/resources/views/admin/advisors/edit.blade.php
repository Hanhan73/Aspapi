@extends('layouts.admin')
@php $title = 'Edit Dewan Penasihat'; @endphp
@section('content')
<form method="POST" action="{{ route('admin.advisors.update', $advisor) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.advisors._form')
</form>
@endsection