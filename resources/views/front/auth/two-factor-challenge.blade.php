{{-- <x-front-layout title="Two Factor Authentication">

    <!-- Start Account Login Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <form class="card login-form" action="{{ route('two-factor.login') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="title">
                                <h3>Two Factor Authentication</h3>
                                <p>Please enter 2FA code</p>
                            </div>

                            @if ($errors->has('code'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('code') }}
                                </div>
                            @endif

                            <div class="form-group input-group">
                                <label for="code">2 FA Code</label>
                                <input class="form-control" type="number" name="code" id="code">
                            </div>

                            <div class="alt-option">
                                <span>Or</span>
                            </div>

                            <div class="form-group input-group">
                                <label for="recovery_code">2 FA Recovery Code</label>
                                <input class="form-control" type="string" name="recovery_code" id="recovery_code">
                            </div>

                            <div class="button">
                                <button class="btn" type="submit">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Account Login Area -->

</x-front-layout> --}}




<x-front-layout title="Two-Factor Challenge">

    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">Two-Factor Authentication</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                            <li>2FA Challenge</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>

    <!-- Start Two-Factor Challenge Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <div class="card login-form">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="icon-lg mx-auto mb-3">
                                    <i class="lni lni-shield" style="font-size: 64px; color: #5a5ac9;"></i>
                                </div>
                                <h3>Two-Factor Authentication</h3>
                                <p class="text-muted">
                                    Please confirm access to your account by entering the authentication code
                                    provided by your authenticator application.
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <i class="lni lni-cross-circle"></i>
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <!-- Code Input Form (Default) -->
                            <form method="POST" action="{{ route('two-factor.login') }}" id="codeForm">
                                @csrf

                                <div class="form-group input-group mb-4">
                                    <label for="code" class="form-label">Authentication Code</label>
                                    <input type="text"
                                        class="form-control text-center @error('code') is-invalid @enderror"
                                        id="code" name="code" inputmode="numeric" maxlength="6"
                                        pattern="[0-9]{6}" placeholder="000000"
                                        style="font-size: 24px; letter-spacing: 8px; font-weight: bold;" required
                                        autofocus>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="button">
                                    <button class="btn w-100" type="submit">
                                        <i class="lni lni-checkmark"></i> Verify
                                    </button>
                                </div>
                            </form>

                            <!-- Recovery Code Form (Hidden by default) -->
                            <form method="POST" action="{{ route('two-factor.login') }}" id="recoveryForm"
                                style="display: none;">
                                @csrf

                                <div class="form-group input-group mb-4">
                                    <label for="recovery_code" class="form-label">Recovery Code</label>
                                    <input type="text"
                                        class="form-control @error('recovery_code') is-invalid @enderror"
                                        id="recovery_code" name="recovery_code" placeholder="Enter recovery code"
                                        style="font-family: monospace;">
                                    @error('recovery_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Enter one of your emergency recovery codes
                                    </small>
                                </div>

                                <div class="button">
                                    <button class="btn w-100" type="submit">
                                        <i class="lni lni-checkmark"></i> Use Recovery Code
                                    </button>
                                </div>
                            </form>

                            <!-- Toggle Link -->
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-link" id="toggleFormBtn" onclick="toggleForm()">
                                    <i class="lni lni-key"></i> Use a recovery code instead
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="text-center mt-3">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-muted">
                                        <i class="lni lni-arrow-left"></i> Back to Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Two-Factor Challenge Area -->

    <script>
        // Auto-format code input (numbers only)
        document.getElementById('code')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Toggle between code and recovery form
        function toggleForm() {
            const codeForm = document.getElementById('codeForm');
            const recoveryForm = document.getElementById('recoveryForm');
            const toggleBtn = document.getElementById('toggleFormBtn');

            if (codeForm.style.display === 'none') {
                codeForm.style.display = 'block';
                recoveryForm.style.display = 'none';
                toggleBtn.innerHTML = '<i class="lni lni-key"></i> Use a recovery code instead';
            } else {
                codeForm.style.display = 'none';
                recoveryForm.style.display = 'block';
                toggleBtn.innerHTML = '<i class="lni lni-reload"></i> Use authentication code';
            }
        }
    </script>

</x-front-layout>
