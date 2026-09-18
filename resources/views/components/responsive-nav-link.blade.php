@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-gold text-start text-base font-medium text-gold-dark bg-beige-50 focus:outline-none focus:text-gold-dark focus:bg-beige-100 focus:border-gold-dark transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-charcoal/70 hover:text-charcoal hover:bg-beige-50 hover:border-beige-200 focus:outline-none focus:text-charcoal focus:bg-beige-50 focus:border-beige-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
