@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full rounded border-gray-300 focus:border-purple-600 focus:ring-purple-600']) !!}>
