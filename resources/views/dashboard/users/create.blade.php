@extends('layouts.dashboard.app')

@section('title', 'Create Users')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item ">
        <a href="{{ route('dashboard.users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.users.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('dashboard.users._form')
    </form>

@endsection
