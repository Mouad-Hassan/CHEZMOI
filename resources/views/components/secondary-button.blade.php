<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-outline text-xs px-4 py-2 uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
