<x-shopper::card class="divide-y divide-gray-100 ring-gray-100 dark:divide-white/10">
    <div class="flex items-center justify-between p-4 lg:p-5">
        <x-shopper::skeleton class="h-4 w-1/3 dark:bg-gray-950" />
        <x-shopper::skeleton class="h-4 w-20 dark:bg-gray-950" />
    </div>
    <div class="p-4 lg:p-5">
        <div class="grid gap-6 sm:grid-cols-2">
            @foreach ([0, 1, 2, 3] as $item)
                <div class="flex items-start space-x-3">
                    <x-shopper::skeleton class="size-5 rounded-lg dark:bg-gray-950" />
                    <div class="flex-1 space-y-1">
                        <x-shopper::skeleton class="h-4 w-1/3 dark:bg-gray-950" />
                        <x-shopper::skeleton class="h-4 w-1/2 dark:bg-gray-950" />
                    </div>
                </div>
            @endforeach

            <div class="lg:col-span-2">
                <div class="flex items-start space-x-3">
                    <x-shopper::skeleton class="size-5 rounded-lg dark:bg-gray-950" />
                    <div class="flex-1 space-y-2">
                        <x-shopper::skeleton class="h-4 w-1/3 dark:bg-gray-950" />
                        <x-shopper::skeleton class="h-11 w-full dark:bg-gray-950" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shopper::card>
