{{-- 
    @props([
    'attribute1' => 'defualt value'
    'attribute2' => 'defualt value'
    ..
    ])
    البروبس وظيفتها هنا إننى بفهم اللارافيل إن الكومبوننت (component) ده ايه هيا المتغيرات او الاتريبيوتس اللى يتوقعها ويستقبلها
--}}
@props([
    'label' => false,
    'type' => 'text',
    'name',
    'value' => '',
])

@if ($label)
    <label for="">{{ $label }}</label>
@endif

<input type={{ $type }} name={{ $name }} value="{{ old($name, $value) }}" {{-- @class(['form-control', 'is-invalid' => $errors->has($name)]) --}}
    {{ $attributes->class(['is-invalid' => $errors->has($name)]) }}>
{{-- $attributes → Attribute Bag (Component "Rest" Props)
        • Special variable unique to Blade components
        • Contains all attributes passed to the component that are not explicitly defined in @props
        • Can be merged with additional attributes using ->class(), ->merge(), etc. --}}

{{-- @if ($errors->has('name')) //todo  @endif   ===   @error('name') //todo @enderror --}}
@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    {{-- $message (within @error)
            • Scoped exclusively to the @error('field') ... @enderror block
            • Holds the first validation error string for that field --}}
@enderror
