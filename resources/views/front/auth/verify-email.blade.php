<x-front-layout title="تفعيل البريد الإلكتروني">

    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">Verify Email</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i>Home</a></li>
                            <li>Verify Email</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>

    <!-- Start Email Verification Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-12">

                    {{-- رسالة نجاح التسجيل --}}
                    @if (session('registered'))
                        <div class="alert alert-success mb-4" style="font-size: 1.1rem;">
                            <i class="lni lni-checkmark-circle" style="font-size: 1.5rem;"></i>
                            <strong style="font-size: 1.2rem;">مرحباً بك!</strong> تم إنشاء حسابك بنجاح.
                        </div>
                    @endif

                    <div class="card login-form">
                        <div class="card-body">

                            <div class="alert alert-info" style="font-size: 1.05rem; padding: 1.25rem;">
                                <i class="lni lni-information" style="font-size: 1.5rem;"></i>
                                <strong style="font-size: 1.15rem;">تذكر</strong>
                                <br>
                                <span style="font-weight: 500;">يمكنك تصفح الموقع بحرية، لكن لتفعيل جميع المميزات، يُفضل
                                    تفعيل بريدك الإلكتروني.</span>
                            </div>

                            <div class="text-center mb-4">
                                <i class="lni lni-envelope" style="font-size: 80px; color: #5a5ac9;"></i>
                                <h3 class="mt-3" style="font-size: 2rem; font-weight: 700;">تفعيل بريدك الإلكتروني
                                </h3>
                                <p style="font-size: 1.1rem; color: #666; font-weight: 500;">
                                    تم إرسال رسالة تفعيل إلى:
                                    <br>
                                    <strong style="font-size: 1.2rem; color: #333;">{{ Auth::user()->email }}</strong>
                                </p>
                            </div>

                            {{-- رسالة إعادة الإرسال --}}
                            @if (session('status') == 'verification-link-sent')
                                <div class="alert alert-success" style="font-size: 1.05rem;">
                                    <i class="lni lni-checkmark-circle" style="font-size: 1.4rem;"></i>
                                    <strong>تم إرسال رسالة تفعيل جديدة إلى بريدك الإلكتروني!</strong>
                                </div>
                            @endif

                            {{-- رسالة خطأ --}}
                            @if (session('error'))
                                <div class="alert alert-danger" style="font-size: 1.05rem;">
                                    <i class="lni lni-cross-circle" style="font-size: 1.4rem;"></i>
                                    <strong>{{ session('error') }}</strong>
                                </div>
                            @endif

                            <div class="row">
                                {{-- قسم إدخال OTP --}}
                                <div class="col-lg-6 mb-4">
                                    <div class="border rounded p-4 h-100" style="border: 2px solid #e0e0e0;">
                                        <h5 class="mb-3" style="font-size: 1.3rem; font-weight: 700; color: #333;">
                                            <i class="lni lni-code"></i>
                                            الطريقة الأولى: كود التفعيل
                                        </h5>
                                        <p style="font-size: 1rem; color: #666; font-weight: 500;">
                                            أدخل الكود المكون من 6 أرقام المُرسل إلى بريدك:
                                        </p>

                                        <form method="POST" action="{{ route('verification.verify.otp') }}">
                                            @csrf

                                            <div class="form-group">
                                                <input
                                                    class="form-control text-center @error('otp') is-invalid @enderror"
                                                    type="text" name="otp" id="otp" maxlength="6"
                                                    pattern="[0-9]{6}" placeholder="000000"
                                                    style="font-size: 32px; letter-spacing: 12px; font-weight: 800; padding: 1rem; border: 2px solid #ddd;"
                                                    required>
                                                @error('otp')
                                                    <div class="invalid-feedback" style="font-size: 1rem;">
                                                        {{ $message }}</div>
                                                @enderror
                                            </div>

                                            <button class="btn btn-primary w-100" type="submit"
                                                style="font-size: 1.1rem; font-weight: 600; padding: 0.75rem;">
                                                <i class="lni lni-checkmark"></i> تفعيل الآن
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                {{-- قسم الرابط المباشر --}}
                                <div class="col-lg-6 mb-4">
                                    <div class="border rounded p-4 h-100"
                                        style="border: 2px solid #e0e0e0; background-color: #f8f9fa;">
                                        <h5 class="mb-3" style="font-size: 1.3rem; font-weight: 700; color: #333;">
                                            <i class="lni lni-link"></i>
                                            الطريقة الثانية: الرابط المباشر
                                        </h5>
                                        <p style="font-size: 1rem; color: #666; font-weight: 500;">
                                            افتح بريدك الإلكتروني واضغط على زر <strong>"تفعيل البريد مباشرة"</strong>
                                        </p>

                                        <div class="text-center my-4">
                                            <i class="lni lni-inbox" style="font-size: 64px; color: #888;"></i>
                                        </div>

                                        <p style="font-size: 1rem; color: #888; font-weight: 600; text-align: center;">
                                            تحقق من صندوق الوارد أو مجلد Spam
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- إعادة إرسال --}}
                            <div class="text-center mt-4">
                                <p style="font-size: 1.1rem; color: #666; font-weight: 600;">لم تستلم الرسالة؟</p>
                                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary"
                                        style="font-size: 1.05rem; font-weight: 600; padding: 0.7rem 1.5rem;">
                                        <i class="lni lni-reload"></i> إعادة إرسال رسالة التفعيل
                                    </button>
                                </form>
                            </div>

                            {{-- تخطي التفعيل --}}
                            <div class="text-center mt-4">
                                <a href="{{ route('home') }}" class="btn btn-link text-muted"
                                    style="font-size: 1rem; font-weight: 600;">
                                    <i class="lni lni-arrow-right"></i> متابعة التصفح بدون تفعيل
                                </a>
                            </div>

                            {{-- تسجيل خروج --}}
                            <div class="text-center mt-3">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-muted"
                                        style="font-size: 1rem; font-weight: 600;">
                                        <i class="lni lni-exit"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Email Verification Area -->

    <script>
        // Auto-format OTP input (numbers only)
        document.getElementById('otp')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>

</x-front-layout>
