@extends('layouts.dashboard.app')

@section('title', 'Edit Admins')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.admins.index') }}">Admins</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.admins.update', $admin->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put') <!-- method 'put' for 'update' -->
        @include('dashboard.admins._form', [
            'button_label' => 'update',
        ])
    </form>

@endsection
