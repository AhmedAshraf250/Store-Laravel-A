<x-front-layout title="Profile">

    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">My Profile</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                            <li>Profile</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>

    @include('front.profile.partials.flash-messages')

    <!-- Start Profile Section -->
    <section class="section">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-4 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div
                                        style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                        <span style="font-size: 36px; color: white; font-weight: bold;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted mb-0">{{ $user->email }}</p>

                                @if ($user->hasVerifiedEmail())
                                    <span class="badge bg-success mt-2">
                                        <i class="lni lni-checkmark-circle"></i> Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning mt-2">
                                        <i class="lni lni-warning"></i> Unverified
                                    </span>
                                @endif
                            </div>

                            <!-- Navigation Pills -->
                            <ul class="nav nav-pills flex-column" id="profileTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ session('activeTab') == 'info' || !session('activeTab') ? 'active' : '' }}"
                                        id="info-tab" data-bs-toggle="pill" href="#info" role="tab">
                                        <i class="lni lni-user"></i> Profile Information
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ session('activeTab') == 'security' ? 'active' : '' }}"
                                        id="security-tab" data-bs-toggle="pill" href="#security" role="tab">
                                        <i class="lni lni-lock"></i> Security
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ session('activeTab') == 'settings' ? 'active' : '' }}"
                                        id="settings-tab" data-bs-toggle="pill" href="#settings" role="tab">
                                        <i class="lni lni-cog"></i> Settings
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('user.2fa') }}" class="nav-link">
                                        <i class="lni lni-shield"></i> Two-Factor Auth
                                        @if (auth()->user()->two_factor_confirmed_at)
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if (!$user->hasVerifiedEmail())
                        <div class="alert alert-warning mt-3">
                            <i class="lni lni-warning"></i>
                            <strong>Email Not Verified</strong>
                            <p class="mb-0 small">Please verify your email address.</p>
                            <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning w-100">
                                    Resend Verification Email
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8 col-12">
                    <div class="tab-content" id="profileTabContent">

                        <!-- Profile Information Tab -->
                        <div class="tab-pane fade {{ session('activeTab') == 'info' || !session('activeTab') ? 'show active' : '' }}"
                            id="info" role="tabpanel">
                            @include('front.profile.partials.update-profile-information')
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade {{ session('activeTab') == 'security' ? 'show active' : '' }}"
                            id="security" role="tabpanel">
                            @include('front.profile.partials.update-password')

                            <div class="mt-4">
                                @include('front.profile.partials.delete-account')
                            </div>
                        </div>

                        <!-- Settings Tab -->
                        <div class="tab-pane fade {{ session('activeTab') == 'settings' ? 'show active' : '' }}"
                            id="settings" role="tabpanel">
                            @include('front.profile.partials.settings')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Profile Section -->

</x-front-layout>
