<x-shopper::card class="[&>div:first-of-type]:p-0" aria-hidden="true">
    <div class="flex items-center justify-between p-4">
        <div class="h-5 w-40 animate-pulse rounded bg-sh-muted"></div>
        <div class="h-4 w-20 animate-pulse rounded bg-sh-muted"></div>
    </div>

    <div class="divide-y divide-sh-border border-t border-sh-border">
        @for ($i = 0; $i < 6; $i++)
            <div class="flex items-center gap-3 px-6 py-3">
                <div class="size-8 shrink-0 animate-pulse rounded-lg bg-sh-muted"></div>
                <div class="h-4 flex-1 animate-pulse rounded bg-sh-muted"></div>
                <div class="h-4 w-12 shrink-0 animate-pulse rounded bg-sh-muted"></div>
                <div class="h-4 w-10 shrink-0 animate-pulse rounded bg-sh-muted"></div>
            </div>
        @endfor
    </div>
</x-shopper::card>
