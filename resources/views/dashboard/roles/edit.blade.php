@extends('layouts.dashboard.app')

@section('title', 'Edit Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.roles.index') }}">Roles</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.roles.update', $role->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put') <!-- method 'put' for 'update' -->
        @include('dashboard.roles._form', [
            'button_label' => 'update',
        ])
    </form>

@endsection
