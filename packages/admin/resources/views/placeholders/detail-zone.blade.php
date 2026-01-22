<x-shopper::card
    class="divide-y divide-gray-200 ring-gray-200 dark:divide-white/10 dark:ring-white/10"
    aria-hidden="true"
>
    <div class="flex items-center justify-between p-4 lg:p-5">
        <x-shopper::skeleton class="h-4 w-1/3 dark:bg-gray-950" aria-hidden="true" />
        <x-shopper::skeleton class="h-4 w-20 dark:bg-gray-950" aria-hidden="true" />
    </div>
    <div class="p-4 lg:p-5">
        <div class="grid gap-6 sm:grid-cols-2">
            @foreach ([0, 1, 2, 3] as $item)
                <div class="flex items-start space-x-3">
                    <x-shopper::skeleton class="size-5 dark:bg-gray-950" aria-hidden="true" />
                    <div class="flex-1 space-y-1">
                        <x-shopper::skeleton class="h-4 w-1/3 dark:bg-gray-950" aria-hidden="true" />
                        <x-shopper::skeleton class="h-4 w-1/2 dark:bg-gray-950" aria-hidden="true" />
                    </div>
                </div>
            @endforeach

            <div class="lg:col-span-2">
                <div class="flex items-start space-x-3">
                    <x-shopper::skeleton class="size-5 dark:bg-gray-950" aria-hidden="true" />
                    <div class="flex-1 space-y-2">
                        <x-shopper::skeleton class="h-4 w-1/3 dark:bg-gray-950" aria-hidden="true" />
                        <x-shopper::skeleton class="h-11 w-full dark:bg-gray-950" aria-hidden="true" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shopper::card>
