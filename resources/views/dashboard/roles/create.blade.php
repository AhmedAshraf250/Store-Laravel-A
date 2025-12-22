@extends('layouts.dashboard.app')

@section('title', 'Create Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.roles.index') }}">Roles</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.roles.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('dashboard.roles._form')
    </form>

@endsection
