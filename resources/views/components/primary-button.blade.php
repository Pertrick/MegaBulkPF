<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-primary text-darkmode text-sm font-semibold tracking-wide border border-primary hover:bg-transparent hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-darkmode disabled:opacity-50 transition duration-150',
]) }}>
    {{ $slot }}
</button>
