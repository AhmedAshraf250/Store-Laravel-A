<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">
            <i class="lni lni-cog"></i> Account Settings
        </h5>

        @if (session('status') === 'settings-updated')
            <div class="alert alert-success alert-dismissible fade show">
                <i class="lni lni-checkmark-circle"></i>
                Your settings have been updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.settings.update') }}">
            @csrf
            @method('PATCH')

            {{-- Language Preference --}}
            <div class="mb-4">
                <h6 class="mb-3">
                    <i class="lni lni-world"></i> Language & Region
                </h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="locale" class="form-label">Preferred Language</label>
                        <select class="form-select @error('locale') is-invalid @enderror" id="locale" name="locale">
                            <option value="en"
                                {{ old('locale', $user->profile->locale ?? 'en') === 'en' ? 'selected' : '' }}>
                                English
                            </option>
                            <option value="ar"
                                {{ old('locale', $user->profile->locale ?? 'en') === 'ar' ? 'selected' : '' }}>
                                العربية (Arabic)
                            </option>
                        </select>
                        @error('locale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select @error('country') is-invalid @enderror" id="country"
                            name="country">
                            <option value="">Select Country</option>
                            <option value="EG"
                                {{ old('country', $user->profile->country ?? '') === 'EG' ? 'selected' : '' }}>Egypt
                            </option>
                            <option value="US"
                                {{ old('country', $user->profile->country ?? '') === 'US' ? 'selected' : '' }}>United
                                States</option>
                            <option value="GB"
                                {{ old('country', $user->profile->country ?? '') === 'GB' ? 'selected' : '' }}>United
                                Kingdom</option>
                            <option value="SA"
                                {{ old('country', $user->profile->country ?? '') === 'SA' ? 'selected' : '' }}>Saudi
                                Arabia</option>
                            <option value="AE"
                                {{ old('country', $user->profile->country ?? '') === 'AE' ? 'selected' : '' }}>UAE
                            </option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="my-4">

            {{-- Email Notifications --}}
            <div class="mb-4">
                <h6 class="mb-3">
                    <i class="lni lni-envelope"></i> Email Notifications
                </h6>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="email_orders" name="email_orders" value="1"
                        {{ old('email_orders', $user->email_orders ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="email_orders">
                        Order confirmations and updates
                    </label>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="email_promotions" name="email_promotions"
                        value="1"
                        {{ old('email_promotions', $user->email_promotions ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="email_promotions">
                        Promotional emails and special offers
                    </label>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="email_newsletter" name="email_newsletter"
                        value="1"
                        {{ old('email_newsletter', $user->email_newsletter ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="email_newsletter">
                        Newsletter and product updates
                    </label>
                </div>
            </div>

            <hr class="my-4">

            {{-- Security Settings --}}
            <div class="mb-4">
                <h6 class="mb-3">
                    <i class="lni lni-shield"></i> Security
                </h6>

                <div class="list-group">
                    <a href="{{ route('user.2fa') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Two-Factor Authentication</h6>
                                <small class="text-muted">Add an extra layer of security</small>
                            </div>
                            <div>
                                @if ($user->two_factor_confirmed_at)
                                    <span class="badge bg-success">
                                        <i class="lni lni-checkmark"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Disabled</span>
                                @endif
                                <i class="lni lni-arrow-right ms-2"></i>
                            </div>
                        </div>
                    </a>

                    @if (!$user->hasVerifiedEmail())
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Email Verification</h6>
                                    <small class="text-danger">Your email is not verified</small>
                                </div>
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        Verify Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="lni lni-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
