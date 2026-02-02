{{-- Flash Messages Component --}}
{{-- Add this at the top of resources/views/front/profile/edit.blade.php --}}

@if (session('success') || session('error') || session('status') || session('warning'))
    <div class="container mb-4">
        <div class="row">
            <div class="col-12">

                {{-- Success Messages --}}
                @if (session('success') && !session('verified'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="lni lni-checkmark-circle" style="font-size: 1.5rem; margin-right: 0.75rem;"></i>
                            <div>
                                <strong>Success!</strong>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Email Verified Success --}}
                @if (session('verified'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="lni lni-checkmark-circle" style="font-size: 1.5rem; margin-right: 0.75rem;"></i>
                            <div>
                                <strong>Email Verified!</strong>
                                <p class="mb-0">Your email has been successfully verified. You now have full access to
                                    all features.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Profile Updated --}}
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="lni lni-checkmark-circle" style="font-size: 1.5rem; margin-right: 0.75rem;"></i>
                            <div>
                                <strong>Profile Updated!</strong>
                                <p class="mb-0">Your profile information has been updated successfully.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Password Updated --}}
                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="lni lni-lock" style="font-size: 1.5rem; margin-right: 0.75rem;"></i>
                            <div>
                                <strong>Password Changed!</strong>
                                <p class="mb-0">Your password has been updated successfully.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Settings Updated --}}
                @if (session('status') === 'settings-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="lni lni-cog" style="font-size: 1.5rem; margin-right: 0.75rem;"></i>
                            <div>
                                <strong>Settings Updated!</strong>
                                <p class="mb-0">Your preferences have been saved.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="lni lni-cross-circle" style="font-size: 1.5rem; margin-right: 0.75rem;"></i>
                            <div>
                                <strong>Error!</strong>
                                <p class="mb-0">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Warning Messages --}}
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="lni lni-warning" style="font-size: 1.5rem; margin-right: 0.75rem;"></i>
                            <div>
                                <strong>Notice!</strong>
                                <p class="mb-0">{{ session('warning') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif

<style>
    .alert {
        border-left: 4px solid;
        font-size: 1rem;
    }

    .alert-success {
        border-left-color: #28a745;
    }

    .alert-danger {
        border-left-color: #dc3545;
    }

    .alert-warning {
        border-left-color: #ffc107;
    }
</style>
