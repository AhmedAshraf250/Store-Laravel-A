@extends('layouts.dashboard.app')

@section('title', $user->name)

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Created_at</th>
            </tr>
        </thead>
        <tbody>
            USERS
        </tbody>

    </table>



@endsection
