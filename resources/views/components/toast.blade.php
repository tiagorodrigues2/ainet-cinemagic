
@php
    $colors = match ($type) {
        'success' => 'bg-green-100 border-green-500 border-2 border-solid text-green-700',
        'warning' => 'bg-yellow-100 border-yellow-500 border-2 border-solid text-yellow-700',
        'error' => 'bg-red-100 border-red-500 border-2 border-solid text-red-700',
        default => 'bg-blue-100 border-blue-500 border-2 border-solid text-blue-700',
    };
@endphp

<div class="my-4 z-0">
    <div class="{{ $colors }} px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">{{ $title }}:</strong>
        <span>{{ $message }}</span>
    </div>
</div>
