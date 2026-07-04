<div x-show="! loading" class="flex max-h-96 flex-col gap-2 overflow-scroll p-px">
    <template x-for="icon in results" :key="icon.name">
        <div
            role="button"
            x-on:click.prevent="select(icon)"
            x-bind:class="{
                'bg-primary-500! dark:bg-primary-600! text-white!': state == icon.name,
            }"
            class="rounded-lg bg-gray-50 p-2 text-gray-600 ring-1 ring-gray-950/10 dark:bg-white/5 dark:text-gray-400 dark:ring-white/20"
        >
            <div class="flex flex-row items-center gap-2">
                <span x-html="icon.html" class="flex size-5 items-center justify-center [&>svg]:size-5"></span>
                <span
                    class="text-gray-500 dark:text-gray-400"
                    x-bind:class="{
                        'text-white!': state == icon.name,
                    }"
                    x-text="icon.label"
                ></span>
            </div>
        </div>
    </template>
</div>
