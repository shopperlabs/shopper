@if(! app()->isProduction())
    <div class="my-10 flex justify-center text-center">
        <div class="bg-sh-card ring-sh-border flex items-center overflow-hidden rounded-lg shadow ring-1">
            <div class="border-sh-border flex shrink-0 items-center justify-center border-r p-3">
                <x-untitledui-info-circle class="text-sh-fg-muted size-5" aria-hidden="true" />
            </div>
            <div class="bg-sh-surface text-sh-fg-muted px-4 py-3 text-sm dark:bg-transparent">
                {{ __('shopper::words.learn_more') }}
                <a
                    href="https://docs.laravelshopper.dev/{{ shopper()->version() }}/{{ $link }}"
                    target="_blank"
                    class="text-primary-600 hover:text-primary-500 ml-1 inline-flex items-center"
                >
                    {{ $name }}
                    <x-untitledui-arrow-circle-broken-right
                        class="text-sh-fg-muted ml-2 size-5"
                        stroke-width="1.5"
                        aria-hidden="true"
                    />
                </a>
            </div>
        </div>
    </div>
@endif
