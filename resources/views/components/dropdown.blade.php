@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};

$dropdownId = 'dropdown-' . uniqid();
@endphp

<div class="relative" id="{{ $dropdownId }}">
    <div onclick="toggleDropdown('{{ $dropdownId }}')">
        {{ $trigger }}
    </div>

    <div id="{{ $dropdownId }}-menu"
            class="absolute z-50 mt-2 {{ $width }} rounded-2xl shadow-xl border border-beige-100 {{ $alignmentClasses }} hidden transition-all duration-200"
            onclick="closeDropdown('{{ $dropdownId }}')">
        <div class="rounded-2xl ring-0 {{ $contentClasses }} py-2">
            {{ $content }}
        </div>
    </div>
</div>

<script>
    function toggleDropdown(id) {
        const menu = document.getElementById(id + '-menu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    function closeDropdown(id) {
        const menu = document.getElementById(id + '-menu');
        if (menu) {
            menu.classList.add('hidden');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('{{ $dropdownId }}');
        if (dropdown && !dropdown.contains(e.target)) {
            closeDropdown('{{ $dropdownId }}');
        }
    });
</script>
