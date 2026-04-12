@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'w-full rounded-lg border border-dark_border bg-darkmode/60 text-grey placeholder-muted shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-60',
]) !!}>
