<ul class="flex items-center space-x-4">
    @foreach ($steps as $step)
        <li
            @class([
                "inline-flex items-center text-sm leading-6",
                "cursor-pointer" => $step->complete,
            ])
            @if ($step->complete)
                wire:click="{{ $step->show() }}"
            @endif
        >
            <span
                @class([
                    "relative flex size-6 items-center justify-center rounded-full text-xs leading-5",
                    "bg-primary-600" => $step->complete,
                    "border border-gray-300 bg-white text-gray-500 dark:border-white/10 dark:bg-gray-950 dark:text-gray-400" => ! $step->complete,
                ])
            >
                @if ($step->complete)
                    <x-untitledui-check-circle class="size-5 text-white" stroke-width="1.5" aria-hidden="true" />
                @else
                    {{ $loop->index + 1 }}
                @endif
            </span>
            <span
                @class([
                    "ml-2 text-sm leading-6",
                    "text-gray-900 dark:text-white" => $step->complete,
                    "font-medium text-gray-900 dark:text-white" => $step->isCurrent(),
                    "text-gray-500 dark:text-gray-400" => ! (
                        $step->isCurrent() || $step->complete
                    ),
                ])
            >
                {{ $step->label }}
            </span>
            @if (! $loop->last)
                <div class="ml-5">
                    <x-untitledui-chevron-right
                        class="size-5 text-gray-400 dark:text-gray-300"
                        stroke-width="1.5"
                        aria-hidden="true"
                    />
                </div>
            @endif
        </li>
    @endforeach
</ul>
