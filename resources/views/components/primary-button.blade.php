<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-emerald']) }}>
    {{ $slot }}
</button>
