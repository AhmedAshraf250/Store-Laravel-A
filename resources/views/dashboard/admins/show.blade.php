@extends('layouts.dashboard.app')

@section('title', $admin->name)

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.admins.index') }}">Admins</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Created_at</th>
            </tr>
        </thead>
        <tbody>
            SHOW ADMIN
        </tbody>

    </table>

@endsection
