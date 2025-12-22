@if ($errors->any())
    <div class="alert alert-danger">
        <h3>Errors Occured!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li> {{ $error }} </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <x-form.input label="Admin Name" class="form-control form-control-lg" type="text" name="name" :value="$admin->name ?? ''" />
</div>

<div class="form-group">
    <x-form.input label="Admin Email" class="form-control form-control-lg" type="email" name="email"
        :value="$admin->email ?? ''" />
</div>

<fieldset class="form-group abilities-box">
    <legend class="fw-bold mb-4 fs-4">
        {{ __('Roles') }}
    </legend>

    @foreach ($roles as $role)
        {{-- Options --}}
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="roles[]" value="{{ $role->id }}"
                    @checked(in_array($role->id, old('roles', $admin->roles->pluck('id')->toArray())))>
                <span>{{ $role->name }}</span>
            </label>
        </div>
    @endforeach
</fieldset>


{{-- SUBMIT --}}
<div class="form-group">
    <button class=" form-control btn btn-primary" type="submit" id="">{{ $button_label ?? 'Save' }}</button>
</div>
