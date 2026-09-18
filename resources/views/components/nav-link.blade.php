@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-gold text-sm font-medium leading-5 text-charcoal focus:outline-none focus:border-gold-dark transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-charcoal/70 hover:text-charcoal hover:border-beige-200 focus:outline-none focus:text-charcoal focus:border-beige-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
