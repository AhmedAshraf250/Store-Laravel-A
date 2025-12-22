@extends('layouts.dashboard.app')

@section('title', 'Products')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    @can('create', App\Models\Product::class)
        <a href="{{ route('dashboard.products.create') }}" class=" mb-5 btn btn-group-lg btn-outline-primary">
            Create Product
        </a>
    @endcan

    <x-alert type="success" />
    <x-alert type="info" />

    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name" class="form-control mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
        </select>
        <button class="bth btn-dark mx-2" type="submit">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Store</th>
                <th>Status</th>
                <th>Created_at</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>

            @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ $product->image && file_exists(public_path('storage/' . $product->image))
                            ? asset('storage/' . $product->image)
                            : asset('storage/uploads/product.png') }}"
                            alt="{{ $product->name }}" height="50" loading="lazy"
                            onerror="this.src='{{ asset('storage/uploads/product.png') }}'; this.onerror=null;">
                    </td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>
                        {{ $product->category()->first()->name ?? 'Category Not Found' }}
                    </td>
                    <td>
                        {{ $product->store->name ?? 'Store Not Found' }}
                    </td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>
                        @can('update', $product)
                            <a href="{{ route('dashboard.products.edit', $product->id) }}"
                                class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        @can('delete', $product)
                            <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="post">
                                @csrf
                                @method('delete')

                                <input type="submit" class="btn btn-sm btn-outline-danger" value="Delete">
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8"> No Products Defined</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $products->withQueryString()->links() }} {{-- === --}} {{-- $products->appends(request()->all())->links() --}}


@endsection
