<x-front-layout title="Two-Factor Authentication">

    <x-slot:breadcrumb>
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
                            <li><a href="{{ route('profile.edit') }}"><i class="lni lni-user"></i> Profile</a></li>
                            <li>2FA</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:breadcrumb>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-12">

                    {{-- Back to Profile --}}
                    <div class="mb-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-link">
                            <i class="lni lni-arrow-left"></i> Back to Profile
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="lni lni-shield" style="font-size: 64px; color: #5a5ac9;"></i>
                                <h3 class="mt-3">Two-Factor Authentication (2FA)</h3>
                                <p class="text-muted">
                                    Add an extra layer of security to your account.
                                </p>
                            </div>

                            @php
                                $user = auth()->user();
                            @endphp

                            {{-- Email Verification Check --}}
                            @if (!$user->hasVerifiedEmail())
                                <div class="alert alert-warning">
                                    <i class="lni lni-warning"></i>
                                    <strong>Email Verification Required</strong>
                                    <p class="mb-2">You must verify your email address before enabling 2FA.</p>
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="lni lni-envelope"></i> Resend Verification Email
                                        </button>
                                    </form>
                                </div>

                                {{-- STATE 1: 2FA Not Enabled --}}
                            @elseif (!$user->two_factor_secret)
                                <div class="alert alert-secondary">
                                    <h6 class="alert-heading">
                                        <i class="lni lni-shield"></i> Two-Factor Authentication is Disabled
                                    </h6>
                                    <p class="mb-0">
                                        When enabled, you'll be prompted for a secure code during login.
                                        You can retrieve this code from Google Authenticator, Authy, or similar apps.
                                    </p>
                                </div>

                                <div class="d-grid">
                                    <form method="POST" action="{{ route('two-factor.enable') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="lni lni-lock"></i> Enable Two-Factor Authentication
                                        </button>
                                    </form>
                                </div>

                                {{-- STATE 2: Secret Exists but NOT Confirmed --}}
                            @elseif ($user->two_factor_secret && !$user->two_factor_confirmed_at)
                                <div class="alert alert-warning">
                                    <i class="lni lni-information"></i>
                                    <strong>Almost There!</strong>
                                    <p class="mb-0">Please scan the QR code below and enter a code to confirm 2FA is
                                        working.</p>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <i class="lni lni-cross-circle"></i>
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                {{-- QR Code Section --}}
                                <div class="card bg-light mb-4">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Step 1: Scan QR Code</h5>
                                        <p class="text-muted small">
                                            Open your authenticator app and scan this code:
                                        </p>
                                        <div class="my-4">
                                            {!! $user->twoFactorQrCodeSvg() !!}
                                        </div>
                                        <p class="text-muted small">
                                            Using Google Authenticator, Authy, or Microsoft Authenticator
                                        </p>
                                    </div>
                                </div>

                                {{-- Confirmation Form --}}
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Step 2: Confirm with Code</h5>
                                        <p class="text-muted small mb-4">
                                            Enter the 6-digit code from your authenticator app to confirm:
                                        </p>

                                        <form method="POST" action="{{ route('two-factor.confirm') }}">
                                            @csrf

                                            <div class="mb-4">
                                                <label for="code" class="form-label">Authentication Code</label>
                                                <input type="text"
                                                    class="form-control text-center @error('code') is-invalid @enderror"
                                                    id="code" name="code" inputmode="numeric" maxlength="6"
                                                    pattern="[0-9]{6}" placeholder="000000"
                                                    style="font-size: 28px; letter-spacing: 10px; font-weight: bold;"
                                                    required autofocus>
                                                @error('code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-success btn-lg">
                                                    <i class="lni lni-checkmark"></i> Confirm & Complete Setup
                                                </button>

                                                {{-- Cancel Button --}}
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-toggle="modal" data-bs-target="#cancelSetupModal">
                                                    Cancel Setup
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- STATE 3: Fully Enabled and Confirmed --}}
                            @else
                                <div class="alert alert-success">
                                    <h6 class="alert-heading">
                                        <i class="lni lni-checkmark-circle"></i> Two-Factor Authentication is Active
                                    </h6>
                                    <p class="mb-0">Your account is protected with 2FA.</p>
                                </div>

                                {{-- Success Messages --}}
                                @if (session('status') == 'two-factor-confirmed')
                                    <div class="alert alert-success alert-dismissible fade show">
                                        <i class="lni lni-checkmark-circle"></i>
                                        <strong>Success!</strong> 2FA has been enabled and confirmed.
                                        Please save your recovery codes below in a secure place.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @if (session('status') == 'recovery-codes-regenerated')
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        <i class="lni lni-warning"></i>
                                        <strong>Recovery Codes Regenerated!</strong>
                                        Your old codes no longer work. Save these new codes.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                {{-- Recovery Codes (Show Once) --}}
                                @if (session('status') == 'two-factor-confirmed' ||
                                        session('status') == 'recovery-codes-regenerated' ||
                                        session('show_recovery_codes'))
                                    <div class="card border-warning mb-4">
                                        <div class="card-header bg-warning text-dark">
                                            <h5 class="mb-0">
                                                <i class="lni lni-key"></i> Recovery Codes - Save These Now!
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-warning mb-3">
                                                <strong>Important:</strong> Store these codes in a secure password
                                                manager.
                                                They can be used to access your account if you lose your 2FA device.
                                                <br>
                                                <strong>This is your only chance to see them!</strong>
                                            </div>

                                            <div class="recovery-codes p-3 bg-light rounded border mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted">Click code to copy</small>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        onclick="copyAllCodes()">
                                                        <i class="lni lni-files"></i> Copy All
                                                    </button>
                                                </div>
                                                <ul class="list-unstyled mb-0"
                                                    style="font-family: monospace; font-size: 14px;"
                                                    id="recoveryCodes">
                                                    @foreach ($user->recoveryCodes() as $code)
                                                        <li class="mb-1 p-2 rounded hover-bg" style="cursor: pointer;"
                                                            onclick="copyCode('{{ $code }}')">
                                                            {{ $code }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="confirmSaved"
                                                    onchange="toggleContinue()">
                                                <label class="form-check-label" for="confirmSaved">
                                                    I have saved these recovery codes in a secure place
                                                </label>
                                            </div>

                                            <div class="d-grid mt-3">
                                                <button type="button" class="btn btn-primary" id="continueBtn"
                                                    disabled onclick="hideRecoveryCodes()">
                                                    <i class="lni lni-checkmark"></i> Continue
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Normal State: 2FA Active --}}
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <i class="lni lni-cog"></i> Manage 2FA
                                            </h5>

                                            <div class="d-grid gap-2">
                                                <form method="POST"
                                                    action="{{ route('two-factor.recovery-codes') }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-secondary w-100">
                                                        <i class="lni lni-reload"></i> Regenerate Recovery Codes
                                                    </button>
                                                </form>

                                                <button type="button" class="btn btn-danger w-100"
                                                    data-bs-toggle="modal" data-bs-target="#disable2FAModal">
                                                    <i class="lni lni-lock-alt"></i> Disable Two-Factor Authentication
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Cancel Setup Modal --}}
    @if ($user->two_factor_secret && !$user->two_factor_confirmed_at)
        <div class="modal fade" id="cancelSetupModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel 2FA Setup?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to cancel the 2FA setup? You'll need to start over if you want to
                            enable it later.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Continue
                            Setup</button>
                        <form method="POST" action="{{ route('two-factor.disable') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Yes, Cancel Setup</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Disable 2FA Modal --}}
    @if ($user->two_factor_confirmed_at)
        <div class="modal fade" id="disable2FAModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="lni lni-warning"></i> Disable Two-Factor Authentication
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <strong>Warning!</strong> Your account will be less secure.
                            </div>
                            <p>Are you sure you want to disable two-factor authentication?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Disable 2FA</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('code');

            // Auto-format code input (numbers only)
            if (codeInput) {
                codeInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });

        // Toggle continue button
        function toggleContinue() {
            const checkbox = document.getElementById('confirmSaved');
            const button = document.getElementById('continueBtn');
            if (checkbox && button) {
                button.disabled = !checkbox.checked;
            }
        }

        // Hide recovery codes section
        function hideRecoveryCodes() {
            window.location.href = '{{ route('user.2fa') }}';
        }

        // Copy single code
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                showToast('Code copied!');
            });
        }

        // Copy all codes
        function copyAllCodes() {
            const codes = Array.from(document.querySelectorAll('#recoveryCodes li'))
                .map(li => li.textContent.trim())
                .join('\n');

            navigator.clipboard.writeText(codes).then(() => {
                showToast('All codes copied!');
            });
        }

        // Show toast notification
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-body bg-success text-white">
                    <i class="lni lni-checkmark-circle"></i> ${message}
                </div>
            </div>
        `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }
    </script>

    <style>
        .hover-bg:hover {
            background-color: #e9ecef;
        }
    </style>

</x-front-layout>
