<ul class="flex items-center space-x-4">
    @foreach($steps as $step)
        <li
            @class([
                'inline-flex items-center text-sm leading-6',
                'cursor-pointer' => $step->complete
            ])
            @if ($step->complete)
                wire:click="{{ $step->show() }}"
            @endif
        >
            <span @class([
                'relative flex h-6 w-6 items-center justify-center rounded-full text-xs leading-5',
                'bg-primary-600' => $step->complete,
                'border border-secondary-300 bg-white text-secondary-500 dark:border-secondary-700 dark:bg-secondary-950 dark:text-secondary-400' => ! $step->complete,
            ])>
                @if($step->complete)
                    <x-untitledui-check-circle
                        class="h-5 w-5 text-white"
                        stroke-width="1.5"
                        aria-hidden="true"
                    />
                @else
                    {{ $loop->index + 1 }}
                @endif
            </span>
            <span @class([
                'ml-2 text-sm leading-6',
                'text-secondary-900 dark:text-white' => $step->complete,
                'text-secondary-900 font-medium dark:text-white' => $step->isCurrent(),
                'text-secondary-500 dark:text-secondary-400' => ! ($step->isCurrent() || $step->complete)
            ])>
                {{ $step->label }}
            </span>
            @if(! $loop->last)
                <div class="ml-5">
                    <x-untitledui-chevron-right
                        class="h-5 w-5 text-secondary-400 dark:text-secondary-300"
                        stroke-width="1.5"
                        aria-hidden="true"
                    />
                </div>
            @endif
        </li>
    @endforeach
</ul>
