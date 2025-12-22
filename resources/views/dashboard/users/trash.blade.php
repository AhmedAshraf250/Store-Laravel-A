@extends('layouts.dashboard.app')

@section('title', 'Users Trashes')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    <a href="{{ route('dashboard.users.index') }}" class=" mb-5 btn btn-group-lg btn-outline-primary">
        Back
    </a>


    <x-alert type="success" />
    <x-alert type="info" />

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Deleted_at</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>

            {{-- @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->deleted_at }}</td>
                    <td>
                        <form action="{{ route('dashboard.users.restore', $user->id) }}" method="post">
                            @csrf
                            @method('put')
                            <input type="submit" class="btn btn-sm btn-info" value="Restore">
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('dashboard.users.force-delete', $user->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <input type="submit" class="btn btn-sm btn-danger" value="Full Delete!">
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"> No Users Defined</td>
                </tr>
            @endforelse --}}

        </tbody>

    </table>



@endsection
