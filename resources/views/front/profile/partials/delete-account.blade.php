<div class="card border-danger">
    <div class="card-body">
        <h5 class="card-title text-danger mb-4">
            <i class="lni lni-trash"></i> Delete Account
        </h5>

        <p class="text-muted">
            Once your account is deleted, all of its resources and data will be permanently deleted.
            Before deleting your account, please download any data or information that you wish to retain.
        </p>

        <!-- Delete Button (Trigger Modal) -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="lni lni-trash"></i> Delete Account
        </button>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="lni lni-warning"></i> Delete Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>

                    <p>
                        Are you sure you want to delete your account? Once your account is deleted,
                        all of its resources and data will be permanently deleted.
                    </p>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Please enter your password to confirm:
                        </label>
                        <input type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror" id="password"
                            name="password" required placeholder="Your password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="lni lni-trash"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
