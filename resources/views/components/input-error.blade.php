@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'bg-red-500 rounded text-white mb-2'])}}>
        <ul {{ $attributes->merge(['class' => 'font-medium text-sm space-y-1']) }}>
            @foreach ((array) $messages as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
