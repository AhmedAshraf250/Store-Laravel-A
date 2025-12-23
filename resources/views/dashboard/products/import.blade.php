@extends('layouts.dashboard.app')

@section('title', 'Import Products')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item "><a href="{{ route('dashboard.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <form action="{{ route('dashboard.products.import') }}" method="post" {{-- enctype="multipart/form-data" --}}>
        @csrf
        <div class="form-group">
            <x-form.input label="Product Count" class="form-control form-control-lg" name="count" />
        </div>
        <button type="submit" class="mt-3 btn btn-primary">Start Import</button>
    </form>

@endsection
