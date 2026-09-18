<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl border-2 border-red-500 bg-red-500 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-red-600 hover:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
