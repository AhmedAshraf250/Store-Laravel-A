<x-front-layout title="Two Factor Authentication">

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

</x-front-layout>
