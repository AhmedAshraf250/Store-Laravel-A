{{-- 
one of the key differences between traditional @include and Blade Components is scope isolation.

    Blade Components vs. @include – Scope Isolation

    @include ('path.to.view') 
    → Inherits ALL variables from the parent view
    → Tight coupling → harder to reuse

    <x-alert /> or <x-card />
    → Completely isolated scope (by design)
    → Variables inside the component are private
    → Parent variables are inaccessible unless explicitly passed
    → Only declared @props() and $slot are available
--}}

{{--
- {alert} is the component name
- {type} is a prop that can be 'success' or 'error' or any other type you want to passed to the component
- this is thier context when we want to use it in any blade file like below (rendering the component):
<x-alert type="success" />
<x-alert type="error" />
--}}

@if (session()->has($type))
    <div class="alert alert-{{ $type }}">
        {{ session($type) }}
    </div>
@endif
