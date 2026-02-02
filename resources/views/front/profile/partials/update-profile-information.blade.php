<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">
            <i class="lni lni-user"></i> Profile Information
        </h5>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="lni lni-checkmark-circle"></i>
                Your profile has been updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="row">
                <!-- Name -->
                <div class="col-md-12 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-12 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if ($user->isDirty('email') && !$user->hasVerifiedEmail())
                        <div class="text-muted small mt-1">
                            Your email address is unverified.
                        </div>
                    @endif
                </div>

                <!-- First Name -->
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                        name="first_name" value="{{ old('first_name', $user->profile->first_name ?? '') }}">
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                        name="last_name" value="{{ old('last_name', $user->profile->last_name ?? '') }}">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Birthday -->
                <div class="col-md-6 mb-3">
                    <label for="birthday" class="form-label">Birthday</label>
                    <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday"
                        name="birthday" value="{{ old('birthday', $user->profile->birthday ?? '') }}"
                        max="{{ date('Y-m-d') }}">
                    @error('birthday')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gender -->
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="male"
                            {{ old('gender', $user->profile->gender ?? '') === 'male' ? 'selected' : '' }}>Male
                        </option>
                        <option value="female"
                            {{ old('gender', $user->profile->gender ?? '') === 'female' ? 'selected' : '' }}>Female
                        </option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Street Address -->
                <div class="col-md-12 mb-3">
                    <label for="street_address" class="form-label">Street Address</label>
                    <input type="text" class="form-control @error('street_address') is-invalid @enderror"
                        id="street_address" name="street_address"
                        value="{{ old('street_address', $user->profile->street_address ?? '') }}">
                    @error('street_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- City -->
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                        name="city" value="{{ old('city', $user->profile->city ?? '') }}">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- State -->
                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">State/Province</label>
                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                        name="state" value="{{ old('state', $user->profile->state ?? '') }}">
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Postal Code -->
                <div class="col-md-6 mb-3">
                    <label for="postal_code" class="form-label">Postal Code</label>
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                        id="postal_code" name="postal_code"
                        value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Country -->
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
                            {{ old('country', $user->profile->country ?? '') === 'SA' ? 'selected' : '' }}>Saudi Arabia
                        </option>
                        <option value="AE"
                            {{ old('country', $user->profile->country ?? '') === 'AE' ? 'selected' : '' }}>UAE</option>
                        <!-- Add more countries as needed -->
                    </select>
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Locale -->
                <div class="col-md-6 mb-3">
                    <label for="locale" class="form-label">Language</label>
                    <select class="form-select @error('locale') is-invalid @enderror" id="locale" name="locale">
                        <option value="en"
                            {{ old('locale', $user->profile->locale ?? 'en') === 'en' ? 'selected' : '' }}>English
                        </option>
                        <option value="ar"
                            {{ old('locale', $user->profile->locale ?? 'en') === 'ar' ? 'selected' : '' }}>العربية
                        </option>
                    </select>
                    @error('locale')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="lni lni-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
