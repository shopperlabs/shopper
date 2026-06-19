<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4" aria-hidden="true">
    @for ($i = 0; $i < 4; $i++)
        <x-shopper::card>
            <x-slot:title>
                <div class="flex items-center justify-between">
                    <div class="h-4 w-20 animate-pulse rounded bg-sh-muted"></div>
                    <div class="size-5 animate-pulse rounded bg-sh-muted"></div>
                </div>
            </x-slot:title>

            <div class="h-7 w-28 animate-pulse rounded bg-sh-muted"></div>
            <div class="mt-3 h-4 w-32 animate-pulse rounded bg-sh-muted"></div>
        </x-shopper::card>
    @endfor
</div>
