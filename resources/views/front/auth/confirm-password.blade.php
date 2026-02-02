{{-- <x-front-layout title="Two Factor Authentication">

    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">Two Factor Authentication</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                            <li>2 FA</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>

    <!-- Start Account Login Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <form class="card login-form" action="{{ route('password.confirm') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="title">
                                <h3>Two Factor Authentication</h3>
                                <p>Please enter your password to enable 2FA</p>
                            </div>

                            @if ($errors->has('password'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif

                            <div class="form-group input-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" name="password" id="password" required>
                            </div>

                            <div class="button">
                                <button class="btn" type="submit">Confirm</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Account Login Area -->

</x-front-layout> --}}



<x-front-layout title="Confirm Password">

    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">Confirm Password</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                            <li>Confirm Password</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>

    <!-- Start Confirm Password Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div class="card login-form">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="icon-lg mx-auto mb-3">
                                    <i class="lni lni-lock" style="font-size: 64px; color: #5a5ac9;"></i>
                                </div>
                                <h3>Confirm Password</h3>
                                <p class="text-muted">
                                    This is a secure area. Please confirm your password before continuing.
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <i class="lni lni-cross-circle"></i>
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf

                                <div class="form-group input-group mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required autofocus
                                        autocomplete="current-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="button">
                                    <button class="btn w-100" type="submit">
                                        <i class="lni lni-checkmark"></i> Confirm
                                    </button>
                                </div>
                            </form>

                            <!-- Back Link -->
                            <div class="text-center mt-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-link text-muted">
                                    <i class="lni lni-arrow-left"></i> Back to Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Confirm Password Area -->

</x-front-layout>
