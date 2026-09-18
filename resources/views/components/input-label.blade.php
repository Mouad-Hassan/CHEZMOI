@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-charcoal']) }}>
    {{ $value ?? $slot }}
</label>
