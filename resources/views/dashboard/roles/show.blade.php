@extends('layouts.dashboard.app')

@section('title', $role->name)

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.roles.index') }}">Roles</a>
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


            @php
                $products = $role->products()->withoutGlobalScope('store')->with('store')->paginate(5);
            @endphp

            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2"> No Products Defined</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $products->links() }}

@endsection
