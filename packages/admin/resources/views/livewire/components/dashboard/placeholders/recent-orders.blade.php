<x-shopper::card class="[&>div:first-of-type]:p-0" aria-hidden="true">
    <div class="flex items-center justify-between p-4">
        <div class="bg-sh-muted h-5 w-40 animate-pulse rounded"></div>
        <div class="bg-sh-muted h-4 w-20 animate-pulse rounded"></div>
    </div>

    <div class="divide-sh-border border-sh-border divide-y border-t">
        @for ($i = 0; $i < 5; $i++)
            <div class="flex items-center gap-3 px-6 py-3">
                <div class="bg-sh-muted h-4 w-16 shrink-0 animate-pulse rounded"></div>
                <div class="bg-sh-muted size-7 shrink-0 animate-pulse rounded-lg"></div>
                <div class="bg-sh-muted h-4 flex-1 animate-pulse rounded"></div>
                <div class="bg-sh-muted h-4 w-16 shrink-0 animate-pulse rounded"></div>
                <div class="bg-sh-muted h-5 w-16 shrink-0 animate-pulse rounded-full"></div>
            </div>
        @endfor
    </div>
</x-shopper::card>
