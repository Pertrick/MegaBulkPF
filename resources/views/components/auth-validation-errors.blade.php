@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'mb-6 text-left']) }}>
        <div class="font-medium text-error text-sm">
            {{ __('Whoops! Something went wrong.') }}
        </div>
        <ul class="mt-2 list-disc list-inside text-sm text-error/90">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
