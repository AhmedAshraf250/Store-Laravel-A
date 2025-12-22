@extends('layouts.dashboard.app')

@section('title', 'Admins Trashes')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.admins.index') }}">Admins</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    <a href="{{ route('dashboard.admins.index') }}" class=" mb-5 btn btn-group-lg btn-outline-primary">
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

            {{-- @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->deleted_at }}</td>
                    <td>
                        <form action="{{ route('dashboard.admins.restore', $admin->id) }}" method="post">
                            @csrf
                            @method('put')
                            <input type="submit" class="btn btn-sm btn-info" value="Restore">
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('dashboard.admins.force-delete', $admin->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <input type="submit" class="btn btn-sm btn-danger" value="Full Delete!">
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"> No Admins Defined</td>
                </tr>
            @endforelse --}}

        </tbody>

    </table>



@endsection
