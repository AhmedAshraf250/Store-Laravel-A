@extends('layouts.dashboard.app')

@section('title', 'Categories')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')
    {{-- if (User::find(1)->can('category.crate'))  >> we can check permission of some user if he not the current authenticated user --}}
    {{-- check if the current authenticated user can create category or has permission --}}
    @if (Auth::user()->can('categories.create'))
        {{-- OR ==>> @can('categories.create') --}}
        <a href="{{ route('dashboard.categories.create') }}" class=" mb-5 btn btn-group-lg btn-outline-primary">
            Create Category
        </a>
    @endif

    <a href="{{ route('dashboard.categories.trash') }}" class=" mb-5 btn btn-group-lg btn-outline-info">
        Trash
    </a>


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
                <th>Parent</th>
                <th>Products #</th>
                <th>Status</th>
                <th>Created_at</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            {{-- $categories is collection of Eloquent Models (Collection Object)

        count() → Collection method (requires Eloquent Collection)
        @if ($categories) → always true (even empty Collection returns true)
        Always pair with count() or isNotEmpty() for accurate empty checks
        --}}
            {{-- @if ($categories->count()) --}}
            {{-- @foreach ($categories as $category) --}}
            {{-- <tr> --}}
            {{-- <td></td> --}}
            {{-- <td>{{$category->id}}</td> --}}
            {{-- <td>{{$category->name}}</td> --}}
            {{-- <td>{{$category->parent_id}}</td> --}}
            {{-- <td>{{$category->created_at}}</td> --}}
            {{-- <td> --}}
            {{-- <a href="{{route('categories.edit')}}" class="btn btn-sm btn-outline-success">Edit</a> --}}
            {{-- </td> --}}
            {{-- <td> --}}
            {{-- <form action="{{route('$categories.destroy')}}" method="post"> --}}
            {{-- @csrf --}}
            {{-- @method('delete') --}}
            {{-- --}}{{-- <input type="hidden" name="_method" value="delete"> --}}
            {{-- <input type="submit" class="btn btn-sm btn-outline-danger" value="Delete"> --}}
            {{-- </form> --}}
            {{-- </td> --}}
            {{-- </tr> --}}
            {{-- @endforeach --}}
            {{-- @else --}}
            {{-- <tr> --}}
            {{-- <td colspan="7"> No Categories Defined</td> --}}
            {{-- </tr> --}}
            {{-- @endif --}}

            <!--=========================================================================-->

            {{-- @forelse() empty @endforelse --}} {{-- empty Must exist --}}
            {{--
                @forelse → loops if data exists, shows @empty block if not
                Cleaner and safer than:  @if + @foreach  @else  @endif
            --}}
            @forelse($categories as $category)
                {{-- 
                    File::exists(path)
                    Storage::disk('public')->exists(path)
                    file_exists(path)
                --}}
                <tr>
                    <td>
                        <img src="{{ $category->image && File::exists(public_path('storage/' . $category->image))
                            ? asset('storage/' . $category->image)
                            : asset('storage/uploads/cate.jpg') }}"
                            alt="{{ $category->name }}" height="50" width="50"
                            class="w-12 h-12 object-cover rounded-full border-2 border-indigo-500 shadow-lg" loading="lazy"
                            onerror="this.src='{{ asset('storage/uploads/cate.jpg') }}'; this.onerror=null;">
                    </td>
                    <td>{{ $category->id }}</td>
                    <td><a href="{{ route('dashboard.categories.show', $category->id) }}"> {{ $category->name }}</a></td>
                    <td>{{ $category->parent->name }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->status }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        @can('categories.update')
                            <a href="{{ route('dashboard.categories.edit', $category->id) }}"
                                class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        @can('categories.delete')
                            <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="post">
                                @csrf
                                {{-- <input type="hidden" name="_method" value="delete"> --}}
                                @method('delete')
                                <input type="submit" class="btn btn-sm btn-outline-danger" value="Delete">
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No categories available yet.</td>
                </tr>
            @endforelse
        </tbody>

    </table>
    {{-- 
        - links() → generates pagination links for paginated data
        Requires data to be paginated using paginate() or simplePaginate() on query at controller or model level LIKE: $categories = Category::paginate(10);
        then in Blade view: {{ $categories->links() }} 

        [SUMMARY]:
        must use paginate() or simplePaginate() in controller/model to get paginated data
        then call links() in Blade view to render pagination links
    --}}

    {{-- Default pagination Style --}}
    {{ $categories->withQueryString()->links() }} {{-- === --}} {{-- $categories->appends(request()->all())->links() --}}
    {{-- {{ $categories->withQueryString()->links('pagination.custom') }}  --}}
    {{-- Custom Pagination (only here in this page or view file) --}}
    {{-- 
        - If I ever need to create a custom pagination layout instead of Laravel’s default one,
            I can design it in a separate file and include it inside the "links" method.
        -The "withQueryString" method appends all current query string parameters
            to every pagination link generated by the "links" method.
        - This ensures that any filters, search terms, or other query parameters present in the URL
            are retained when navigating between paginated pages.
    --}}

    {{-- 
        - To publish the pagination views to your application so you can customize them, run the following Artisan command:
            "> php artisan vendor:publish --tag=laravel-pagination"
        - The "vendor:publish" command copies certain files (like views or config files) from the
            vendor directory into the application so we can safely modify or customize them.
        - After running the command above, Laravel publishes all pagination view files from the
            core vendor folder into resources/views, allowing full customization when needed. 
    --}}

    {{-- LOOK: 'resources\views\vendor\pagination\bootstrap-4.blade.php' For more Info about pagination work --}}
@endsection
