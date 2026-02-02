<x-front-layout title="Register">

    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">Register</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                            <li>Register</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>

    <!-- Start Account Register Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <form class="card login-form" action="{{ route('register') }}" method="post">

                        @csrf

                        <div class="card-body">
                            <div class="title">
                                <h3>Register Now</h3>
                                <p>You can register using your social media account or email address.</p>
                            </div>
                            <div class="social-login">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <a class="btn facebook-btn"
                                            href="{{ route('auth.socialite.redirect', 'facebook') }}">
                                            <i class="lni lni-facebook-filled"></i>
                                            Facebook</a>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <a class="btn twitter-btn" href="javascript:void(0)">
                                            <i class="lni lni-twitter-original"></i>
                                            Twitter</a>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12">
                                        <a class="btn google-btn"
                                            href="{{ route('auth.socialite.redirect', 'google') }}">
                                            <i class="lni lni-google"></i>
                                            Google</a>
                                    </div>
                                </div>
                            </div>
                            <div class="alt-option">
                                <span>Or</span>
                            </div>

                            <div class="form-group input-group">
                                <label for="reg-name">Full Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    name="name" id="reg-name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group input-group">
                                <label for="reg-email">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    name="{{ config('fortify.username') }}" id="reg-email" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group input-group">
                                <label for="reg-password">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                    name="password" id="reg-password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group input-group">
                                <label for="reg-password-confirm">Confirm Password</label>
                                <input class="form-control" type="password" name="password_confirmation"
                                    id="reg-password-confirm" required>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="terms"
                                    class="form-check-input width-auto @error('terms') is-invalid @enderror"
                                    id="terms-check" required>
                                <label class="form-check-label" for="terms-check">
                                    I agree to the <a href="#">Terms & Conditions</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="button">
                                <button class="btn" type="submit">Register</button>
                            </div>

                            @if (Route::has('login'))
                                <p class="outer-link">Already have an account?
                                    <a href="{{ route('login') }}">Login here</a>
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Account Register Area -->

</x-front-layout>
