@extends('layouts.dashboard.app')

@section('title', 'Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    {{-- @if (Auth::user()->can('role.crate'))
    @endif --}}
    <a href="{{ route('dashboard.roles.create') }}" class=" mb-5 btn btn-group-lg btn-outline-primary">
        Create Role
    </a>

    {{-- <a href="{{ route('dashboard.roles.trash') }}" class=" mb-5 btn btn-group-lg btn-outline-info">
        Trash
    </a> --}}


    <x-alert type="success" />
    <x-alert type="info" />

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created_at</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>

            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><a href="{{ route('dashboard.roles.show', $role->id) }}"> {{ $role->name }}</a></td>
                    <td>{{ $role->created_at }}</td>
                    <td>
                        @can('roles.update')
                            <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                                class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        @can('roles.delete')
                            <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-sm btn-outline-danger" value="Delete">
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No roles available yet.</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $roles->withQueryString()->links() }} {{-- === --}} {{-- $roles->appends(request()->all())->links() --}}
    {{-- {{ $roles->withQueryString()->links('pagination.custom') }}  --}}
    {{-- Custom Pagination (only here in this page or view file) --}}
@endsection
