@extends('layouts.dashboard.app')

@section('title', 'Roles Trashes')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.roles.index') }}">Roles</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    <a href="{{ route('dashboard.roles.index') }}" class=" mb-5 btn btn-group-lg btn-outline-primary">
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
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->deleted_at }}</td>
                    <td>
                        <form action="{{ route('dashboard.roles.restore', $role->id) }}" method="post">
                            @csrf
                            @method('put')
                            <input type="submit" class="btn btn-sm btn-info" value="Restore">
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('dashboard.roles.force-delete', $role->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <input type="submit" class="btn btn-sm btn-danger" value="Full Delete!">
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"> No Roles Defined</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $roles->withQueryString()->links() }}

@endsection
