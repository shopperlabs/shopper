<x-shopper::card
    class="divide-y divide-sh-border ring-sh-border"
    aria-hidden="true"
>
    <div class="flex items-center justify-between p-4 lg:p-5">
        <div class="flex items-center gap-3">
            <x-shopper::skeleton class="size-6 rounded-full" aria-hidden="true" />
            <x-shopper::skeleton class="h-4 w-1/3" aria-hidden="true" />
        </div>
        <x-shopper::skeleton class="h-4 w-20" aria-hidden="true" />
    </div>
    <div class="p-4 lg:p-5">
        <div class="grid gap-6 sm:grid-cols-2">
            @foreach ([0, 1, 2, 3] as $item)
                <div class="flex items-start space-x-3">
                    <x-shopper::skeleton class="size-5" aria-hidden="true" />
                    <div class="flex-1 space-y-1">
                        <x-shopper::skeleton class="h-4 w-1/3" aria-hidden="true" />
                        <x-shopper::skeleton class="h-4 w-1/2" aria-hidden="true" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-shopper::card>
