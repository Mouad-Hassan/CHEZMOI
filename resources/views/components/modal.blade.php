@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div id="modal-{{ $name }}"
     class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 {{ $show ? '' : 'hidden' }}"
     style="display: {{ $show ? 'block' : 'none' }};">
    <div class="fixed inset-0 transform transition-all"
         onclick="closeModal('{{ $name }}')">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div class="mb-6 bg-white rounded-3xl overflow-hidden shadow-xl border border-beige-100 transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto">
        {{ $slot }}
    </div>
</div>

<script>
    @if ($show)
        document.body.classList.add('overflow-y-hidden');
    @endif

    function openModal(name) {
        const modal = document.getElementById('modal-' + name);
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.classList.add('overflow-y-hidden');
        }
    }

    function closeModal(name) {
        const modal = document.getElementById('modal-' + name);
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.classList.remove('overflow-y-hidden');
        }
    }

    // Listen for modal events
    window.addEventListener('open-modal', (e) => {
        if (e.detail === '{{ $name }}') {
            openModal('{{ $name }}');
        }
    });

    window.addEventListener('close-modal', (e) => {
        if (e.detail === '{{ $name }}') {
            closeModal('{{ $name }}');
        }
    });

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('{{ $name }}');
        }
    });
</script>
