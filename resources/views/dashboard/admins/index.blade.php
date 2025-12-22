@extends('layouts.dashboard.app')

@section('title', 'Admins')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    {{-- @if (Auth::user()->can('admin.crate'))
    @endif --}}

    <a href="{{ route('dashboard.admins.create') }}" class=" mb-5 btn btn-group-lg btn-outline-primary">
        Create Admin
    </a>

    {{-- <a href="{{ route('dashboard.admins.trash') }}" class=" mb-5 btn btn-group-lg btn-outline-info">
        Trash
    </a> --}}


    <x-alert type="success" />
    <x-alert type="info" />

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Created At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>

            @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td><a href="{{ route('dashboard.admins.show', $admin->id) }}"> {{ $admin->name }}</a></td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->roles->pluck('name')->implode(', ') }}</td>
                    <td>{{ $admin->created_at }}</td>
                    <td>
                        @can('admins.update')
                            <a href="{{ route('dashboard.admins.edit', $admin->id) }}"
                                class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        @can('admins.delete')
                            <form action="{{ route('dashboard.admins.destroy', $admin->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-sm btn-outline-danger" value="Delete">
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No admins available yet.</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $admins->withQueryString()->links() }}
@endsection
