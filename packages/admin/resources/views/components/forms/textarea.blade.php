@blaze

@props(['value' => null])

<textarea
    {{ $attributes->twMerge(['class' => 'block w-full rounded-lg py-2 px-3 border-0 text-sh-fg ring-1 ring-sh-border placeholder:text-sh-fg-muted focus:ring-2 focus:ring-primary-600 dark:focus:ring-primary-500 sm:text-sm sm:leading-6']) }}
    rows="3"
>
    {{ $value }}
</textarea>
