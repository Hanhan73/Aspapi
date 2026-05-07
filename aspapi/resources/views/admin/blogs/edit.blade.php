@extends('layouts.admin')
@php $title = 'Edit Blog'; $breadcrumbs = [['label' => 'Blog', 'url' => route('admin.blogs.index')], ['label' => 'Edit', 'url' => '#']]; @endphp

@section('content')
<form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.blogs._form')
</form>
@endsection