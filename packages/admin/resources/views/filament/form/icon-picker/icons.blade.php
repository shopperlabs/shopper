@props([
    'withTooltips' => true,
])

<div
    x-show="! loading"
    class="@sm:grid-cols-6 @lg:grid-cols-8 grid max-h-64 grid-cols-4 gap-1.5 overflow-y-auto p-1"
>
    <template x-for="icon in results" :key="icon.name">
        <div class="flex items-center justify-center">
            <div
                role="button"
                x-on:click.prevent="select(icon)"
                @if ($withTooltips)
                    x-tooltip="{
                        content: icon.label,
                        theme: $store.theme,
                    }"
                @endif
                x-bind:class="state == icon.name ? 'ring-primary-500 ring-2' : 'hover:bg-sh-muted'"
                class="text-sh-fg flex items-center justify-center rounded-lg p-2.5"
            >
                <span x-html="icon.html" class="flex size-5 items-center justify-center [&>svg]:size-5"></span>
            </div>
        </div>
    </template>
</div>
