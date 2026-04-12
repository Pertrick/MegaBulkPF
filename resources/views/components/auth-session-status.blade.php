@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-success mb-6']) }}>
        {{ $status }}
    </div>
@endif
