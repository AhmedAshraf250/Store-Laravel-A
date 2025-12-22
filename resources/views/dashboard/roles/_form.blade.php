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
    <x-form.input label="Role Name" class="form-control form-control-lg" type="text" name="name" :value="$role->name ?? ''" />
</div>

<fieldset class="form-group abilities-box">
    <legend class="fw-bold mb-4 fs-4">
        {{ __('Abilities') }}
    </legend>

    {{-- app('abilities') ==> return [ 'categories.view' => 'View Category', 'products.view' => 'View Product' ]; --}}
    @foreach (app('abilities') as $ability_code => $ability_name)
        <div class="ability-row mb-4 p-4 rounded">

            {{-- Ability name --}}
            <div class="ability-title mb-3">
                {{ __($ability_name) }}
            </div>

            {{-- Options --}}
            <div class="ability-options">
                <label class="ability-option allow">
                    <input type="radio" name="abilities[{{ $ability_code }}]" value="allow" {{-- @checked($role?->abilities?->pluck('type')->contains('allow')) --}}
                        @checked(($role_abilities[$ability_code] ?? null) === 'allow')>
                    <span>{{ __('Allow') }}</span>
                </label>

                <label class="ability-option deny">
                    <input type="radio" name="abilities[{{ $ability_code }}]" value="deny"
                        @checked(($role_abilities[$ability_code] ?? null) === 'deny')>
                    <span>{{ __('Deny') }}</span>
                </label>

                <label class="ability-option inherit">
                    <input type="radio" name="abilities[{{ $ability_code }}]" value="inherit"
                        @checked(($role_abilities[$ability_code] ?? null) === 'inherit')>
                    <span>{{ __('Inherit') }}</span>
                </label>
            </div>
        </div>
    @endforeach
</fieldset>


{{-- SUBMIT --}}
<div class="form-group">
    <button class=" form-control btn btn-success" type="submit" id="">{{ $button_label ?? 'Save' }}</button>
</div>







@push('styles')
    <style>
        /* Container */
        .abilities-box {
            border: 1px solid #e5e7eb;
            padding: 24px;
            border-radius: 14px;
        }

        /* Row */
        .ability-row {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            transition: all 0.25s ease;
        }

        .ability-row:hover {
            background: #f1f5f9;
        }

        /* Ability title */
        .ability-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            border-left: 4px solid #3b82f6;
            padding-left: 12px;
        }

        /* Options container */
        .ability-options {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        /* Option */
        .ability-option {
            cursor: pointer;
        }

        .ability-option input {
            display: none;
        }

        .ability-option span {
            min-width: 110px;
            height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 999px;
            border: 1px solid #d1d5db;
            font-weight: 600;

            transition: all 0.2s ease;
        }

        /* Hover */
        .ability-option:hover span {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        /* Checked states */
        .ability-option.allow input:checked+span {
            background: #dcfce7;
            border-color: #22c55e;
            color: #166534;
        }

        .ability-option.deny input:checked+span {
            background: #fee2e2;
            border-color: #ef4444;
            color: #7f1d1d;
        }

        .ability-option.inherit input:checked+span {
            background: #e5e7eb;
            border-color: #64748b;
            color: #0f172a;
        }
    </style>
@endpush
