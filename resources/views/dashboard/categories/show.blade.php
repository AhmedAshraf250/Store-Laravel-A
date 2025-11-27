@extends('layouts.dashboard.app')

@section('title', $category->name)

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.categories.index') }}">Categories</a>
    </li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Store</th>
                <th>Status</th>
                <th>Created_at</th>
            </tr>
        </thead>
        <tbody>


            @php
                // فى التكرار هنا تحت استخدمت الريلاشن "استور" فى كل لفة بمعنى ان فى كل لفه هيعمل جمله استعلام من قاعده البيانات لجلب الداتا اللى هو اسم الاستور يعنى
                // وبطبيعة الحال المفروض اقلل كمية جمل الاستعلام فى الابليكاشن على قدر المستطاع
                // ولتجنب هذه الاشكاليه يجب تحميل مسبق لهذه الداتا باستخدام ميثود "ويز" التى تعمل تحميل مسبق للريلاشنز التابعه لهذا المودل
                // كل هذه الميثود (بإستثناء ميثود باجينات) بترجع البيلدر والريلاشن فبنعدل عليها او بنضيف لها بعض الشروط قبل تنفيذها فى ميثود الباجينات
                $products = $category->products()->withoutGlobalScope('store')->with('store')->paginate(5);
            @endphp

            @forelse($products as $product)
                <tr>
                    <td>

                        <img src="{{ $product->image && Storage::disk('public')->exists($product->image)
                            ? Storage::disk('public')->url($product->image)
                            : asset('storage/uploads/product.png') }}"
                            alt="{{ $product->name }}" height="50" loading="lazy">
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->store->name ?? 'Store Not Found' }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5"> No Products Defined</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $products->links() }}

@endsection
