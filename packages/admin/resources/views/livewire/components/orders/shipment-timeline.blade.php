<div class="rounded-lg border border-gray-200 bg-white dark:border-white/10 dark:bg-white/5">
    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-white/10">
        <div class="flex items-center gap-2">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                {{ __('shopper::pages/orders.shipment.timeline') }}
            </h3>
            @if ($this->shipping->tracking_number)
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    #{{ $this->shipping->tracking_number }}
                </span>
            @endif
            @if ($this->shipping->status)
                <x-filament::badge
                    size="sm"
                    :color="$this->shipping->status->getColor()"
                    :icon="$this->shipping->status->getIcon()"
                >
                    {{ $this->shipping->status->getLabel() }}
                </x-filament::badge>
            @endif
        </div>
        {{ $this->addEventAction }}
    </div>

    <div class="p-4">
        @if ($this->events->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/orders.shipment.no_events') }}
            </p>
        @else
            <ol class="relative border-l border-gray-200 dark:border-white/10">
                @foreach ($this->events as $event)
                    <li class="mb-6 ml-6 last:mb-0">
                        <span
                            @class([
                                'absolute -left-3 flex size-6 items-center justify-center rounded-full ring-4 ring-white dark:ring-gray-900',
                                'bg-blue-100 dark:bg-blue-900' => $event->status->getColor() === 'info',
                                'bg-primary-100 dark:bg-primary-900' => $event->status->getColor() === 'primary',
                                'bg-indigo-100 dark:bg-indigo-900' => $event->status->getColor() === 'indigo',
                                'bg-yellow-100 dark:bg-yellow-900' => $event->status->getColor() === 'warning',
                                'bg-green-100 dark:bg-green-900' => $event->status->getColor() === 'green',
                                'bg-red-100 dark:bg-red-900' => $event->status->getColor() === 'danger',
                                'bg-gray-100 dark:bg-gray-800' => $event->status->getColor() === 'gray',
                            ])
                        >
                            <x-filament::icon :icon="$event->status->getIcon()" class="size-3.5 text-gray-600 dark:text-gray-300" />
                        </span>
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $event->status->getLabel() }}
                            </h4>
                            <time class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $event->occurred_at->translatedFormat('j M Y H:i') }}
                            </time>
                        </div>
                        @if ($event->location)
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                {{ $event->location }}
                            </p>
                        @endif
                        @if ($event->description)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $event->description }}
                            </p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

    <x-filament-actions::modals />
</div>
