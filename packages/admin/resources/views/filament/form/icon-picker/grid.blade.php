<div
    x-show="! loading"
    class="@md:grid-cols-3 @lg:grid-cols-4 @2xl:grid-cols-6 grid max-h-96 grid-cols-2 gap-2 overflow-scroll p-px"
>
    <template x-for="icon in results" :key="icon.name">
        <div
            role="button"
            x-on:click.prevent="select(icon)"
            x-bind:class="{
                'bg-primary-500! dark:bg-primary-600! text-white!': state == icon.name,
            }"
            class="flex min-w-0 flex-col items-center gap-2 rounded-lg bg-gray-50 p-4 text-center text-gray-600 ring-1 ring-gray-950/10 dark:bg-white/5 dark:text-gray-400 dark:ring-white/20"
        >
            <div x-html="icon.html" class="flex size-6 items-center justify-center [&>svg]:size-6"></div>
            <span
                class="my-auto text-gray-500 dark:text-gray-400"
                x-bind:class="{
                    'text-white!': state == icon.name,
                }"
                x-text="icon.label"
            ></span>
        </div>
    </template>
</div>
